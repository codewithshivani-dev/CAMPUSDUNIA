@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --primary-light: rgba(67, 97, 238, 0.1);
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --danger-color: #ef4444;
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
        --dark: #1f2937;
        --gray: #6b7280;
        --light-gray: #f9fafb;
        --border: #e5e7eb;
        --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
    }

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: none;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .card-header {
        background: var(--primary-gradient);
        color: white;
        border-radius: 20px 20px 0 0 !important;
        padding: 20px 25px;
        border: none;
    }

    .card-header h5 {
        margin: 0;
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header h5 i {
        font-size: 1.3rem;
    }

    .card-body {
        padding: 30px;
    }

    /* Progress Steps */
    .progress-container {
        margin-bottom: 30px;
        padding: 10px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 12px;
    }

    .progress-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 20px;
    }

    .progress-steps::before {
        content: '';
        position: absolute;
        top: 18px;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--border), var(--primary-color), var(--border));
        z-index: 1;
        border-radius: 10px;
    }

    .progress-bar {
        position: absolute;
        top: 18px;
        left: 0;
        height: 4px;
        background: var(--success-gradient);
        z-index: 2;
        transition: width 0.3s ease;
        border-radius: 10px;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 3;
        position: relative;
    }

    .step-number {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: white;
        color: var(--gray);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-bottom: 8px;
        transition: all 0.3s;
        border: 3px solid var(--border);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        font-size: 1.2rem;
    }

    .step.active .step-number {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.4);
    }

    .step.completed .step-number {
        background: var(--success-gradient);
        color: white;
        border-color: transparent;
    }

    .step-text {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--gray);
    }

    .step.active .step-text {
        color: var(--primary-color);
        font-weight: 700;
    }

    .step.completed .step-text {
        color: var(--success-color);
    }

    /* Form Labels */
    .form-label {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 8px;
        font-size: 0.95rem;
        letter-spacing: 0.3px;
    }

    .form-label i {
        margin-right: 8px;
        color: var(--primary-color);
        font-size: 1rem;
    }

    .text-danger {
        color: var(--danger-color) !important;
    }

    /* Form Controls */
    .form-control,
    .form-select {
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s;
        background: white;
        width: 100%;
        height: 45px;
        padding: 0 14px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        outline: none;
        transform: translateY(-2px);
    }

    .form-control:hover,
    .form-select:hover {
        border-color: var(--secondary-color);
    }

    .form-control[readonly],
    .form-control.bg-light {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important;
        border-color: var(--border);
        color: var(--dark);
        cursor: default;
    }

    /* Selection Cards */
    .selection-card {
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s;
        background: white;
        position: relative;
        overflow: hidden;
    }

    .selection-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: transparent;
        transition: all 0.3s;
    }

    .selection-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(67, 97, 238, 0.15);
    }

    .selection-card:hover::before {
        background: var(--primary-gradient);
    }

    .selection-card.selected {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.15);
    }

    .selection-card.auto-selected {
        border-color: var(--success-color);
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        position: relative;
        animation: pulse 2s infinite;
    }

    .selection-card.auto-selected::after {
        content: "✓ Auto-selected";
        position: absolute;
        right: 15px;
        top: 15px;
        background: var(--success-gradient);
        color: white;
        padding: 5px 15px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    .selection-card .card-title {
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 8px;
        font-size: 1.1rem;
    }

    .selection-card .card-text {
        color: var(--gray);
        font-size: 0.9rem;
        margin: 0;
    }

    /* Branch Info Messages */
    .single-branch-info {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border: 2px solid var(--success-color);
        border-radius: 12px;
        padding: 20px;
        margin: 15px 0;
        border-left: 4px solid var(--success-color);
    }

    .multiple-branches-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border: 2px solid var(--primary-color);
        border-radius: 12px;
        padding: 20px;
        margin: 15px 0;
        border-left: 4px solid var(--primary-color);
    }

    /* Fee Categories */
    .fee-category {
        background: white;
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s;
    }

    .fee-category.active {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.1);
    }

    .fee-category-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        margin-bottom: 0;
    }

    .fee-category-header .form-check {
        display: flex;
        align-items: center;
        margin: 0;
        padding: 0;
    }

    .fee-category-header .form-check-input {
        width: 20px;
        height: 20px;
        cursor: pointer;
        border: 2px solid var(--border);
        margin-right: 10px;
    }

    .fee-category-header .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .fee-category-header .form-check-label {
        font-weight: 700;
        color: var(--dark);
        cursor: pointer;
        font-size: 1rem;
    }

    .fee-category-header i {
        color: var(--primary-color);
        font-size: 1.1rem;
        transition: transform 0.3s;
    }

    .fee-category.active .fee-category-header i {
        transform: rotate(180deg);
    }

    .fee-category-content {
        display: none;
        padding-top: 20px;
        margin-top: 15px;
        border-top: 2px solid var(--border);
    }

    .fee-category.active .fee-category-content {
        display: block;
    }

    /* Duration Blocks */
    .duration-block {
        background: white;
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .duration-block::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--info-gradient);
    }

    .duration-block:hover {
        border-color: var(--primary-color);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
    }

    .duration-block h6 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 15px;
    }

    .duration-block h6 i {
        color: var(--primary-color);
    }

    /* Section Items */
    .section-item {
        background: white;
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .section-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: transparent;
        transition: all 0.3s;
    }

    .section-item:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.1);
    }

    .section-item:hover::before {
        background: var(--primary-gradient);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border);
    }

    .section-name {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    .remove-section {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border: none;
        color: var(--danger-color);
        cursor: pointer;
        font-size: 1.2rem;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .remove-section:hover {
        background: var(--danger-gradient);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
    }

    .seats-warning {
        border-color: var(--warning-color) !important;
        background: linear-gradient(135deg, #fff3cd, #ffe69c);
    }

    .seats-error {
        border-color: var(--danger-color) !important;
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-5px);
        }

        75% {
            transform: translateX(5px);
        }
    }

    /* Academic Year Badges */
    .academic-year-badge {
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 30px;
        padding: 8px 15px;
        margin: 5px;
        display: inline-block;
        font-size: 0.85rem;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.2);
    }

    .academic-year-badge strong {
        color: white;
        display: block;
    }

    /* Buttons */
    .btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
    }

    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
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

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-success {
        background: var(--success-gradient);
        color: white;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #059669, #10b981);
    }

    .btn-success:disabled {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-outline-secondary {
        background: transparent;
        border: 2px solid var(--border);
        color: var(--gray);
        text-decoration: none;
    }

    .btn-outline-secondary:hover {
        background: var(--primary-gradient);
        border-color: transparent;
        transform: translateY(-3px);
        color: white;
    }

    .btn-outline-secondary a {
        color: inherit;
        text-decoration: none;
    }

    .btn-outline-secondary:hover a {
        color: white;
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 0.85rem;
    }

    /* Alert Styles */
    .alert {
        border-radius: 12px;
        border: none;
        padding: 15px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-left: 4px solid transparent;
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #166534;
        border-left-color: var(--success-color);
    }

    .alert-danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border-left-color: var(--danger-color);
    }

    .alert-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
        border-left-color: var(--primary-color);
    }

    .alert-warning {
        background: linear-gradient(135deg, #fff3cd, #ffe69c);
        color: #856404;
        border-left-color: var(--warning-color);
    }

    .alert ul {
        margin-left: 20px;
    }

    .alert .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Custom Checkbox */
    .form-check-input {
        width: 1.2em;
        height: 1.2em;
        margin-right: 8px;
        border: 2px solid var(--border);
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* Section Transitions */
    .section {
        display: none;
    }

    .section.active {
        display: block;
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Batch Info Section */
    .batch-in-fee-section {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .batch-in-fee-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--info-gradient);
    }

    .batch-in-fee-section h6 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Fee Section */
    .fee-section-with-batch {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
        position: relative;
        overflow: hidden;
    }

    .fee-section-with-batch::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--success-gradient);
    }

    .fee-section-with-batch .form-label {
        color: var(--primary-color);
        font-weight: 700;
        font-size: 1.1rem;
    }

    /* Late Fee Options */
    .late-fee-options,
    .partial-fee-options {
        background: linear-gradient(135deg, #fff3cd, #ffe69c);
        border: 2px solid var(--warning-color);
        border-radius: 8px;
        padding: 15px;
        margin-top: 10px;
    }

    .late-fee-options label,
    .partial-fee-options label {
        color: #856404;
        font-weight: 600;
    }

    /* Card shadow */
    .card.shadow-sm {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid var(--border);
        border-radius: 12px;
    }

    .card.shadow-sm .text-primary {
        color: var(--primary-color) !important;
    }

    /* Total Fee */
    #totalFeeLabel {
        color: var(--primary-color);
        font-weight: 700;
    }

    input[name="total_fee"] {
        background: linear-gradient(135deg, #e8edff, #dbe4ff);
        border: 2px solid var(--primary-color);
        font-weight: 700;
        color: var(--primary-color);
    }

    /* Form Switch */
    .form-switch .form-check-input {
        width: 3rem;
        height: 1.5rem;
        border: 2px solid var(--border);
    }

    .form-switch .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* Loading Spinner */
    .fa-spinner,
    .fa-spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 20px;
        }

        .step-number {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .step-text {
            font-size: 0.8rem;
        }

        .btn-outline-secondary {
            margin-bottom: 10px;
        }
    }
</style>

<div class="container-fluid">
    <div class="form-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i> 
             @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                    Class Fee Structure Management
                                    @else
                                     Course Fee Structure Management
                                    @endif</h5>
        </div>
        <div class="card-body">

            <!-- Progress Steps -->
            <div class="progress-container">
                <div class="progress-steps">
                    <div class="progress-bar" id="progress-bar"></div>
                    <div class="step active" id="step1">
                        <div class="step-number">1</div>
                        <div class="step-text">Select  @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                    Class 
                                    @else
                                     Branch 
                                    @endif</div>
                    </div>
                    <div class="step" id="step2">
                        <div class="step-number">2</div>
                        <div class="step-text">Configure Fees</div>
                    </div>
                </div>
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

            <form id="course-fee-form" class="form1">
                @csrf
                <input type="hidden" name="product_id" id="product_id">

                <!-- Step 1: Branch Selection -->
                <div class="section active" id="section-branch">
                    <h5 class="mb-4" style="color: var(--primary-color);">
                        <i class="fas fa-search me-2"></i>Select @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                    Class 
                                @else
                                     Branch 
                                @endif
                    </h5>

                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label for="department_category_id" class="form-label">
                                <i class="fas fa-layer-group"></i> Select Department Category
                            </label>
                            <select id="department_category_id" name="department_category_id" class="form-control"
                                required>
                                <option value="">-- Select Department Category --</option>
                                @foreach($departmentCategories as $category)
                                <option value="{{ $category->department_category_id }}">{{ $category->category_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label class="form-label">
                                <i class="fas fa-building"></i> Select Department <span class="text-danger">*</span>
                            </label>
                            <select id="department_id" name="department_id" class="form-control" required>
                                <option value="">-- Choose Department --</option>
                            </select>
                            @if($errors->has('department_id'))
                            <div class="alert alert-danger mt-2">
                                {{ $errors->first('department_id')}}
                            </div>
                            @endif
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label for="course_type" class="form-label">
                                <i class="fas fa-tag"></i> Select @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                    Class 
                                @else
                                     Branch 
                                @endif
                            </label>
                            <select id="course_type" name="course_type" class="form-control" required disabled>
                                <option value="">-- Select Type --</option>
                            </select>
                        </div>
                    </div>

                    <!-- Branch Selection Messages -->
                    <div id="branch-message-container" style="display: none;">
                        <!-- Messages will be shown here -->
                    </div>

                    <div id="branches-container" style="display: none;">
                        <label class="form-label">
                            <i class="fas fa-list me-2"></i> Available Records
                        </label>
                        <div id="branches-list" class="row">
                            <!-- Branches will be loaded here -->
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-primary" id="next-to-fees" disabled>
                            Next: Configure Fees <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Fee Configuration with Course Dates -->
                <div class="section" id="section-fees">
                    <h5 class="mb-4" style="color: var(--primary-color);">
                        <i class="fas fa-cog me-2"></i> Configure Fee Structure
                    </h5>

                    <!-- Branch Information -->
                    <div class="batch-in-fee-section">
                        <h6>
                            <i class="fas fa-info-circle me-2"></i> @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                    Class 
                                @else
                                     Branch 
                                @endif Information
                        </h6>
                        <div id="selected-branch-info" class="mb-3"></div>

                        <input type="hidden" name="sub_type" id="sub_type">
                        <input type="hidden" name="branch_id" id="branch_id">
                        <input type="hidden" name="mode_of_course" id="mode_of_course">
                        <input type="hidden" name="mode_type" id="mode_type">
                        <input type="hidden" name="course_duration" id="course_duration_hidden">
                        <input type="hidden" name="course_length" id="course_length_hidden">
                    </div>

                    <!-- Course Duration & Dates -->
                    <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
                        <h5 class="mb-4" style="color: var(--primary-color);">
                            <i class="fas fa-calendar-alt me-2"></i> @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                    Class 
                                @else
                                     Branch 
                                @endif Duration & Dates
                        </h5>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Duration Type</label>
                                <select name="course_duration_display" style="padding:0px 10px;"
                                    id="course_duration_type" class="form-control" disabled>
                                    <option value="Hourly">Hourly</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                    <option value="Quarterly">Quarterly</option>
                                    <option value="Half_yearly">Half Yearly</option>
                                    <option value="Yearly">Yearly</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Length</label>
                                <input type="number" name="course_length_display" id="course_duration_term"
                                    class="form-control bg-light" placeholder="e.g., 12" disabled>
                                <small class="text-muted d-block mt-1">Loaded from branch details</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Mode of @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                    Class 
                                @else
                                     Branch 
                                @endif</label>
                                <input type="text" name="mode_of_course_display" id="mode_of_course_display"
                                    class="form-control bg-light" readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Mode Type</label>
                                <input type="text" name="mode_type_display" id="mode_type_display"
                                    class="form-control bg-light" readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Type</label>
                                <input type="text" name="course_type_display" id="course_type_display"
                                    class="form-control bg-light" readonly>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="course_start_date" id="session_start"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">End Date</label>
                                <input type="date" name="course_end_date" id="session_end"
                                    class="form-control bg-light" readonly>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label">Generated Academic Years</label>
                            <div id="academic-years-display"
                                class="p-3 bg-light rounded-4 border text-center">
                                <small class="text-muted">Academic years will appear here after setting dates</small>
                            </div>
                        </div>
                    </div>

                    <!-- Seats and Sections Management -->
                    <div class="batch-in-fee-section mt-4">
                        <h6>
                            <i class="fas fa-chair me-2"></i> Seats and Sections Management
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Total Number of Seats *</label>
                                <input type="number" name="total_seats" id="total_seats" class="form-control"
                                    placeholder="e.g., 60" min="1" required>
                                <small class="text-muted">Total capacity for this batch</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Available Seats</label>
                                <input type="number" name="available_seats" id="available_seats" class="form-control"
                                    readonly>
                                <small class="text-muted">Automatically calculated based on sections</small>
                            </div>
                        </div>

                        <!-- Sections Container -->
                        <div class="sections-container mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label mb-0">Sections</label>
                                <button type="button" class="btn btn-sm btn-success" id="add-section-btn">
                                    <i class="fas fa-plus me-1"></i>Add Section
                                </button>
                            </div>

                            <div id="sections-list">
                                <!-- Sections will be added here dynamically -->
                            </div>

                            <div class="seats-summary mt-3">
                                <div class="alert alert-info py-2">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span id="seats-summary-text">Total seats: 0 | Allocated: 0 | Available: 0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Batch Information -->
                    <div class="batch-in-fee-section mt-4">
                        <h6>
                            <i class="fas fa-users me-2"></i> Batch Information
                        </h6>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Batch Name *</label>
                                <input type="text" name="batch" id="batch_name" class="form-control"
                                    placeholder="e.g., Batch 2024, Morning Batch, etc." required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Batch Year *</label>
                                <select name="batch_year" id="batch_year" class="form-control" required
                                    style="padding: 0px 15px;">
                                    <option value="">Select Year</option>
                                    @for($year = date('Y'); $year <= date('Y') + 5; $year++) 
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Batch Status</label>
                                <select name="batch_status" id="batch_status" class="form-control"
                                    style="padding: 0px 15px;">
                                    <option value="running">Running</option>
                                    <option value="complete">Complete</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Fee Categories - ONLY COURSE AND REGISTRATION -->
                    <div class="fee-section-with-batch mt-4">
                        <label class="form-label mb-3">Fee Categories</label>

                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            All fee categories will follow the @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                 Class
                                @else
                                 Courses
                                @endif duration and dates specified above.
                        </div>

                       @php
                            $courseFeeLabel = 'Add Courses Fee';
                            if (isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type === 'School') {
                                $courseFeeLabel = 'Add Class Fee';
                            }

                            $feeTypes = [
                                'course' => $courseFeeLabel,
                                'registration' => 'Registration Fee',
                            ];
                        @endphp

                        @foreach($feeTypes as $type => $label)
                        <div class="fee-category" id="fee-category-{{ $type }}">
                            <div class="fee-category-header">
                                <div class="form-check">
                                    <input class="form-check-input fee-checkbox" type="checkbox"
                                        id="{{ $type }}FeeCheckbox" data-type="{{ $type }}">
                                    <label class="form-check-label" for="{{ $type }}FeeCheckbox">
                                        {{ $label }}
                                    </label>
                                </div>
                                <i class="fas fa-chevron-down"></i>
                            </div>

                            <div class="fee-category-content" id="{{ $type }}FeeDetails" style="display: none;">
                                @if($type == 'miscellaneous')
                                <div class="mb-3">
                                    <label class="form-label">Fee Type Description</label>
                                    <input type="text" name="{{ $type }}_fee_type" class="form-control"
                                        placeholder="e.g., Exam Fee, Library Fee, etc.">
                                </div>
                                @endif

                                <div class="mb-3">
                                    <label class="form-label">Payment Duration</label>
                                    <select class="form-select fee-duration-select" name="{{ $type }}_fee_duration"
                                        data-type="{{ $type }}">
                                        <option value="">Select Duration</option>
                                    </select>
                                </div>

                                <div id="{{ $type }}-input-container">
                                    <!-- Fee inputs will be generated here -->
                                </div>

                                <!-- Late Fee Options -->
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input late-fee-checkbox"
                                                id="{{ $type }}_late_fee" data-type="{{ $type }}">
                                            <label class="form-check-label" for="{{ $type }}_late_fee">Apply Late
                                                Fee</label>
                                        </div>
                                        <div class="late-fee-options" id="{{ $type }}_late_fee_options"
                                            style="display: none;">
                                            <div class="mt-2">
                                                <label>Late Fee Type:</label>
                                                <div class="form-check">
                                                    <input type="radio" name="{{ $type }}_late_fee_type" value="flat"
                                                        class="form-check-input">
                                                    <label class="form-check-label">Flat Amount</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" name="{{ $type }}_late_fee_type"
                                                        value="percentage" class="form-check-input">
                                                    <label class="form-check-label">Percentage</label>
                                                </div>
                                            </div>
                                            <div class="late-fee-amount mt-2" style="display: none;">
                                                <label class="form-label">Late Fee Amount</label>
                                                <input type="number" name="{{ $type }}_late_fee_amount"
                                                    class="form-control" placeholder="Enter amount">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input partial-fee-checkbox"
                                                id="{{ $type }}_partial_fee" data-type="{{ $type }}">
                                            <label class="form-check-label" for="{{ $type }}_partial_fee">Allow Partial
                                                Payment</label>
                                        </div>
                                        <div class="partial-fee-options" id="{{ $type }}_partial_fee_options"
                                            style="display: none;">
                                            <div class="mt-2">
                                                <label>Partial Payment Type:</label>
                                                <div class="form-check">
                                                    <input type="radio" name="{{ $type }}_partial_fee_type"
                                                        value="fixed" class="form-check-input">
                                                    <label class="form-check-label">Fixed Amount</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="radio" name="{{ $type }}_partial_fee_type"
                                                        value="percentage" class="form-check-input">
                                                    <label class="form-check-label">Percentage</label>
                                                </div>
                                            </div>
                                            <div class="partial-fee-amount mt-2" style="display: none;">
                                                <label class="form-label">Partial Fee Amount</label>
                                                <input type="number" name="{{ $type }}_partial_fee_amount"
                                                    class="form-control" placeholder="Enter amount">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Total Fee -->
                    <div class="mb-4 mt-4">
                        <label class="form-label" id="totalFeeLabel">Total Fee</label>
                        <input type="text" name="total_fee" class="form-control form-control-lg" readonly
                            style="font-weight: bold;">
                    </div>

                    <!-- Active Status -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                checked>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-outline-secondary me-2">
                            <a href="/add-course-fee-structure" style="text-decoration:none; color: inherit;">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to @if(isset($serviceInstitutedetails->type) && $serviceInstitutedetails->type == 'School')
                                    Class 
                                @else
                                    Branch 
                                @endif Selection
                            </a>
                        </button>
                        <button type="submit" class="btn btn-success" id="submit-fee-form">
                            <i class="fas fa-save me-2"></i>Save Fee Structure
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Keep all your existing JavaScript exactly as is -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

<script>
// Global variables
let fieldCount = 0;
const maxFields = 2;
let formDataArray = [];
let generatedBatchId = '';
let academicYearsData = [];

// Seats and Sections Management
let sections = [];
let sectionCounter = 0;

// Track if we have a single branch that's auto-selected
let autoSelectedBranchId = null;

// Fixed: Department loading function
function loadDepartmentsByCategory(categoryId) {
    const departmentSelect = document.getElementById('department_id');

    if (!categoryId) {
        departmentSelect.innerHTML = '<option value="">-- Choose Department --</option>';
        return;
    }

    departmentSelect.innerHTML = '<option value="">Loading departments...</option>';

    // Fixed: Correct AJAX endpoint - try both possible endpoints
    const endpoints = [
        `/ajax/departments-by-category?category_id=${categoryId}`
    ];

    // Try first endpoint, if fails try second
    fetch(endpoints[0])
        .then(res => {
            if (!res.ok) {
                throw new Error('First endpoint failed, trying second');
            }
            return res.json();
        })
        .then(data => {
            console.log('Departments data from first endpoint:', data);
            handleDepartmentResponse(data, departmentSelect);
        })
        .catch((error) => {
            console.log('First endpoint failed, trying second:', error);
            // Try second endpoint
            fetch(endpoints[1])
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Second endpoint also failed');
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Departments data from second endpoint:', data);
                    handleDepartmentResponse(data, departmentSelect);
                })
                .catch((secondError) => {
                    console.error('Both endpoints failed:', secondError);
                    departmentSelect.innerHTML = '<option value="">Error loading departments</option>';
                    Toastify({
                        text: "Error loading departments. Please check console for details.",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    }).showToast();
                });
        });
}

function handleDepartmentResponse(data, departmentSelect) {
    if (!data.success && !data.departments) {
        departmentSelect.innerHTML = '<option value="">Error loading departments</option>';
        return;
    }

    departmentSelect.innerHTML = '<option value="">-- Choose Department --</option>';

    // Handle both response formats
    const departments = data.departments || data.data || [];

    if (departments && departments.length > 0) {
        departments.forEach(dept => {
            const option = document.createElement('option');
            option.value = dept.department_id || dept.id;
            option.textContent = dept.department || dept.name;
            departmentSelect.appendChild(option);
        });
    } else {
        departmentSelect.innerHTML = '<option value="">No departments found</option>';
    }
}

function initializeSeatsAndSections() {
    sections = [];
    sectionCounter = 0;
    updateSeatsSummary();
}

function addSection() {
    const totalSeats = parseInt($('#total_seats').val()) || 0;

    if (totalSeats <= 0) {
        Toastify({
            text: "Please set total number of seats first",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
        }).showToast();
        return;
    }

    const allocatedSeats = calculateAllocatedSeats();
    const availableSeats = totalSeats - allocatedSeats;

    if (availableSeats <= 0) {
        Toastify({
            text: `No available seats left. Total seats: ${totalSeats}, Allocated: ${allocatedSeats}`,
            duration: 4000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
        }).showToast();
        return;
    }

    sectionCounter++;
    const sectionId = `section_${sectionCounter}`;

    const sectionHtml = `
        <div class="section-item" id="${sectionId}">
            <div class="section-header">
                <span class="section-name">New Section</span>
                <button type="button" class="remove-section" onclick="removeSection('${sectionId}')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Section Name *</label>
                    <input type="text" class="form-control section-name-input" 
                           placeholder="e.g., A, B, Morning, Evening, Regular, Weekend" 
                           oninput="updateSection('${sectionId}')"
                           required>
                    <small class="text-muted">Enter a unique section name</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Number of Seats *</label>
                    <input type="number" class="form-control section-seats-input" 
                           min="1" max="${availableSeats}" 
                           value="1"
                           placeholder="Max: ${availableSeats}" 
                           oninput="updateSection('${sectionId}')"
                           required>
                    <small class="text-muted">Seats allocated to this section (Max: ${availableSeats})</small>
                </div>
            </div>
            <div class="section-validation mt-2" id="${sectionId}_validation" style="display: none;">
                <small class="text-danger"></small>
            </div>
        </div>
    `;

    $('#sections-list').append(sectionHtml);

    // Add to sections array with initial values
    sections.push({
        id: sectionId,
        name: 'New Section',
        seats: 1 // Default to 1 seat
    });

    updateSeatsSummary();
}

function removeSection(sectionId) {
    $(`#${sectionId}`).remove();
    sections = sections.filter(section => section.id !== sectionId);
    updateSeatsSummary();
    validateAllSections(); // Revalidate all remaining sections
}

function updateSection(sectionId) {
    const sectionElement = $(`#${sectionId}`);
    const nameInput = sectionElement.find('.section-name-input');
    const seatsInput = sectionElement.find('.section-seats-input');
    const validationElement = sectionElement.find('.section-validation');

    const sectionIndex = sections.findIndex(section => section.id === sectionId);
    if (sectionIndex !== -1) {
        const newName = nameInput.val().trim();
        const newSeats = parseInt(seatsInput.val()) || 0;
        const totalSeats = parseInt($('#total_seats').val()) || 0;
        const allocatedSeats = calculateAllocatedSeats();
        const availableSeats = totalSeats - (allocatedSeats - (sections[sectionIndex].seats || 0));

        // Clear previous validation
        validationElement.hide().find('small').text('');
        sectionElement.removeClass('seats-error seats-warning');

        let hasError = false;
        let errorMessage = '';

        // Validate section name uniqueness
        if (newName) {
            const isNameUnique = validateSectionNameUniqueness(sectionId, newName);
            if (!isNameUnique) {
                hasError = true;
                errorMessage = 'Section name must be unique';
            }
        }

        // Validate seats allocation
        if (newSeats > availableSeats) {
            hasError = true;
            if (errorMessage) errorMessage += ' | ';
            errorMessage += `Only ${availableSeats} seats available. Increase total seats to add more.`;
        }

        if (newSeats < 1) {
            hasError = true;
            if (errorMessage) errorMessage += ' | ';
            errorMessage += 'Seats must be at least 1';
        }

        // Show validation errors
        if (hasError) {
            validationElement.show().find('small').text(errorMessage);
            sectionElement.addClass('seats-error');
            return;
        }

        // Update section in array
        sections[sectionIndex].name = newName || 'Unnamed Section';
        sections[sectionIndex].seats = newSeats;

        // Update the section header display
        const displayName = newName || 'Unnamed Section';
        sectionElement.find('.section-name').text(displayName);

        // Update max attribute for all sections
        updateAllSectionMaxSeats();

        // Update seats summary
        updateSeatsSummary();

        // Show warning if this section is using all available seats
        if (newSeats === availableSeats && availableSeats > 0) {
            sectionElement.addClass('seats-warning');
        }
    }
}

function validateAllSections() {
    sections.forEach(section => {
        updateSection(section.id);
    });
}

function updateAllSectionMaxSeats() {
    const totalSeats = parseInt($('#total_seats').val()) || 0;

    sections.forEach(section => {
        const sectionElement = $(`#${section.id}`);
        const seatsInput = sectionElement.find('.section-seats-input');
        const allocatedSeats = calculateAllocatedSeats();
        const availableSeats = totalSeats - (allocatedSeats - (section.seats || 0));

        // Update max attribute and placeholder
        seatsInput.attr('max', availableSeats);
        seatsInput.attr('placeholder', `Max: ${availableSeats}`);

        // Update the help text
        seatsInput.closest('.col-md-6').find('.text-muted').text(
            `Seats allocated to this section (Max: ${availableSeats})`);
    });
}

function validateSectionNameUniqueness(currentSectionId, sectionName) {
    if (!sectionName) return true;

    const duplicateSection = sections.find(section =>
        section.id !== currentSectionId &&
        section.name.toLowerCase() === sectionName.toLowerCase()
    );

    return !duplicateSection;
}

function calculateAllocatedSeats() {
    return sections.reduce((total, section) => total + (section.seats || 0), 0);
}

function updateSeatsSummary() {
    const totalSeats = parseInt($('#total_seats').val()) || 0;
    const allocatedSeats = calculateAllocatedSeats();
    const availableSeats = totalSeats - allocatedSeats;

    $('#available_seats').val(availableSeats);

    let summaryText = `Total seats: ${totalSeats} | Allocated: ${allocatedSeats} | Available: ${availableSeats}`;

    if (availableSeats < 0) {
        summaryText += ' | ❌ Overallocated!';
    } else if (availableSeats === 0) {
        summaryText += ' | ✅ Fully allocated';
    } else if (availableSeats > 0) {
        summaryText += ` | ⚠️ ${availableSeats} seats available for new sections`;
    }

    // Add sections info
    if (sections.length > 0) {
        const sectionNames = sections.map(section => `${section.name} (${section.seats} seats)`).join(', ');
        summaryText += ` | Sections: ${sectionNames}`;
    }

    $('#seats-summary-text').html(summaryText);

    // Update add section button state and text
    const addSectionBtn = $('#add-section-btn');
    if (availableSeats <= 0) {
        addSectionBtn.prop('disabled', true)
            .addClass('btn-secondary')
            .removeClass('btn-success')
            .html('<i class="fas fa-plus me-1"></i>No Seats Available');
    } else {
        addSectionBtn.prop('disabled', false)
            .removeClass('btn-secondary')
            .addClass('btn-success')
            .html(`<i class="fas fa-plus me-1"></i>Add Section (${availableSeats} seats available)`);
    }
}

// Enhanced form validation before submission
function validateSectionsBeforeSubmit() {
    const totalSeats = parseInt($('#total_seats').val()) || 0;

    // Check if total seats is set
    if (totalSeats <= 0) {
        Toastify({
            text: "Please set total number of seats",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
        }).showToast();
        $('#total_seats').focus();
        return false;
    }

    // Check if at least one section exists
    if (sections.length === 0) {
        Toastify({
            text: "Please add at least one section",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
        }).showToast();
        return false;
    }

    // Check for duplicate section names
    const sectionNames = sections.map(section => section.name.toLowerCase());
    const uniqueNames = new Set(sectionNames);
    if (uniqueNames.size !== sectionNames.length) {
        Toastify({
            text: "Please ensure all section names are unique",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
        }).showToast();
        return false;
    }

    // Check for empty section names
    const emptyNames = sections.filter(section => !section.name || section.name === 'Unnamed Section');
    if (emptyNames.length > 0) {
        Toastify({
            text: "Please provide names for all sections",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
        }).showToast();
        return false;
    }

    // Check seats allocation
    const allocatedSeats = calculateAllocatedSeats();
    if (allocatedSeats > totalSeats) {
        Toastify({
            text: `Total allocated seats (${allocatedSeats}) exceed available seats (${totalSeats})`,
            duration: 4000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
        }).showToast();
        return false;
    }

    // Check if any section has invalid seats
    const invalidSections = sections.filter(section => section.seats < 1);
    if (invalidSections.length > 0) {
        Toastify({
            text: "All sections must have at least 1 seat",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
        }).showToast();
        return false;
    }

    return true;
}

// Generate random IDs
function generateRandomId(prefix = '', length = 10) {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let result = prefix;
    for (let i = 0; i < length; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return result;
}

// Generate batch ID once per form
function generateBatchId() {
    if (!generatedBatchId) {
        generatedBatchId = generateRandomId('BATCH_', 8);
    }
    return generatedBatchId;
}

// Generate academic year ID
function generateAcademicYearId() {
    return generateRandomId('ACAD_', 8);
}

// Calculate academic years data
function calculateAcademicYearsData() {
    const durationType = document.getElementById("course_duration_type").value;
    const length = parseInt(document.getElementById("course_duration_term").value);
    const startDateStr = document.getElementById("session_start").value;

    academicYearsData = []; // Reset

    if (!durationType || isNaN(length) || !startDateStr) return;

    let currentDate = new Date(startDateStr);

    // Calculate total course duration in years
    let totalYears = calculateTotalYears(durationType, length);

    // Generate academic years based on total years
    for (let i = 0; i < totalYears; i++) {
        const yearStart = new Date(currentDate);
        const yearEnd = new Date(yearStart);
        yearEnd.setFullYear(yearEnd.getFullYear() + 1);

        // Adjust end date if it's the last year and doesn't complete a full year
        if (i === totalYears - 1) {
            const courseEndDate = calculateCourseEndDate(new Date(startDateStr), durationType, length);
            if (courseEndDate < yearEnd) {
                yearEnd.setTime(courseEndDate.getTime());
            }
        }

        const academicYearId = generateAcademicYearId();
        const academicYearLabel = `${yearStart.getFullYear()}-${yearEnd.getFullYear()}`;
        const sessionRange = `${formatDateForDisplay(yearStart)} - ${formatDateForDisplay(yearEnd)}`;

        academicYearsData.push({
            academic_year_id: academicYearId,
            academic_year: academicYearLabel,
            session_range: sessionRange,
            course_start_date: formatDateForDB(yearStart),
            course_end_date: formatDateForDB(yearEnd),
            duration_label: `Year ${i + 1}`
        });

        // Move to next year
        currentDate = new Date(yearEnd);
    }

    // Update the academic year display
    updateAcademicYearDisplay();

    // Also populate batch year dropdown with all years in the batch
    const startYear = new Date(startDateStr).getFullYear();
    const endYear = startYear + totalYears - 1; // -1 because we include start year
    populateBatchYearDropdown(startYear, endYear);
}

// Calculate total years based on duration type and length
function calculateTotalYears(durationType, length) {
    switch (durationType.toLowerCase()) {
        case 'hourly':
            return Math.ceil(length / (24 * 365)); // Approximate hours to years
        case 'weekly':
            return Math.ceil((length * 7) / 365); // Weeks to years
        case 'monthly':
            return Math.ceil(length / 12); // Months to years
        case 'quarterly':
            return Math.ceil(length / 4); // Quarters to years (4 quarters = 1 year)
        case 'half_yearly':
            return Math.ceil(length / 2); // Half years to years (2 half years = 1 year)
        case 'yearly':
            return length; // Already in years
        default:
            return length;
    }
}

// Calculate the actual course end date
function calculateCourseEndDate(startDate, durationType, length) {
    let endDate = new Date(startDate);

    switch (durationType.toLowerCase()) {
        case 'hourly':
            endDate.setHours(endDate.getHours() + length);
            break;
        case 'weekly':
            endDate.setDate(endDate.getDate() + (7 * length));
            break;
        case 'monthly':
            endDate.setMonth(endDate.getMonth() + length);
            break;
        case 'quarterly':
            endDate.setMonth(endDate.getMonth() + (3 * length));
            break;
        case 'half_yearly':
            endDate.setMonth(endDate.getMonth() + (6 * length));
            break;
        case 'yearly':
            endDate.setFullYear(endDate.getFullYear() + length);
            break;
    }

    return endDate;
}

function formatDateForDisplay(date) {
    return date.toLocaleDateString('en-GB', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
}

function formatDateForDB(date) {
    // If it's already in correct format (YYYY-MM-DD)
    if (typeof date === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(date)) {
        return date;
    }

    // If it's empty or null
    if (!date) {
        return '';
    }

    // Try to create a Date object
    try {
        const dateObj = new Date(date);
        if (!isNaN(dateObj.getTime())) {
            return dateObj.toISOString().split('T')[0];
        }
    } catch (e) {
        console.error('Error formatting date:', e);
    }

    return '';
}

function getPeriodName(i, type) {
    switch (type) {
        case "monthly":
            return `Month ${i + 1}`;
        case "quarterly":
            return `Quarter ${i + 1}`;
        case "half_yearly":
            return `Half Year ${i + 1}`;
        case "yearly":
            return `Year ${i + 1}`;
        case "weekly":
            return `Week ${i + 1}`;
        case "hourly":
            return `Hour ${i + 1}`;
        default:
            return `Term ${i + 1}`;
    }
}

function updateAcademicYearDisplay() {
    const displayContainer = document.getElementById("academic-years-display");

    if (academicYearsData.length === 0) {
        displayContainer.innerHTML =
            '<small class="text-muted">Academic years will appear here after setting dates</small>';
        return;
    }

    const durationType = document.getElementById("course_duration_type").value;
    const length = document.getElementById("course_duration_term").value;

    let html = `
        <div class="mb-2">
            <strong>${academicYearsData.length} Academic Year(s) for ${length} ${durationType}(s):</strong>
        </div>
    `;

    academicYearsData.forEach((year, index) => {
        html += `
            <div class="academic-year-badge">
                <strong>${year.academic_year}</strong><br>
                <small>${year.session_range}</small><br>
                <small>${year.duration_label}</small>
            </div>
        `;
    });

    displayContainer.innerHTML = html;
}

// Modified form submission to handle multiple academic years
function prepareFormDataForSubmission() {
    const baseFormData = collectBaseFormData();
    const submissions = [];

    // Generate batch ID once for all submissions
    const batchId = generateBatchId();

    // Get the selected batch year from dropdown
    const selectedBatchYear = document.querySelector('select[name="batch_year"]').value;

    // Create one submission per academic year
    academicYearsData.forEach((academicYear, index) => {
        const submission = {
            ...baseFormData,
            batch_id: batchId,
            batch_year: selectedBatchYear, // Use the selected batch year
            academic_year_id: academicYear.academic_year_id,
            academic_year: academicYear.academic_year,
            session_range: academicYear.session_range,
            course_start_date: academicYear.course_start_date,
            course_end_date: academicYear.course_end_date,
        };

        submissions.push(submission);
    });

    return submissions;
}

function collectBaseFormData() {
    const getSafeValue = (selector) => {
        const el = document.querySelector(selector);
        return el ? el.value : '';
    };

    const formData = {
        product_id: getSafeValue('input[name="product_id"]'),
        department_id: getSafeValue('select[name="department_id"]'),
        course_type: getSafeValue('select[name="course_type"]'),
        sub_type: getSafeValue('input[name="sub_type"]'),
        mode_of_course: getSafeValue('input[name="mode_of_course"]'),
        mode_type: getSafeValue('input[name="mode_type"]'),
        course_duration: getSafeValue('input[name="course_duration_hidden"]'),
        course_length: getSafeValue('input[name="course_length_hidden"]'),
        batch: getSafeValue('input[name="batch"]'),
        batch_year: getSafeValue('select[name="batch_year"]'),
        batch_status: getSafeValue('select[name="batch_status"]'),
        total_seats: getSafeValue('input[name="total_seats"]'),
        available_seats: getSafeValue('input[name="available_seats"]'),
        sections: JSON.stringify(sections),
        total_fee: getSafeValue('input[name="total_fee"]'),
        is_active: document.querySelector('input[name="is_active"]').checked ? 1 : 0
    };

    // Fee categories - ONLY COURSE AND REGISTRATION
    ['course', 'registration'].forEach(type => {
        const checkbox = document.querySelector(`#${type}FeeCheckbox`);
        if (checkbox && checkbox.checked) {
            formData[`${type}_fee`] = JSON.stringify(collectFeeData(type));
        } else {
            formData[`${type}_fee`] = null;
        }
    });

    return formData;
}

function collectFeeData(type) {
    const feeData = {
        duration: document.querySelector(`select[name="${type}_fee_duration"]`)?.value || '',
        payments: [],
        late_fee: null,
        partial_payment: null
    };

    const blocks = document.querySelectorAll(`#${type}-input-container .duration-block`);
    blocks.forEach(block => {
        const index = block.dataset.index;
        const amount = document.querySelector(`input[name="${type}_fee_amount_${index}"]`)?.value;
        const startDateInput = document.querySelector(`input[name="${type}_start_date_${index}"]`);
        const endDateInput = document.querySelector(`input[name="${type}_end_date_${index}"]`);

        const startDate = startDateInput?.value;
        const endDate = endDateInput?.value;

        if (amount || startDate || endDate) {
            feeData.payments.push({
                amount: amount || null,
                start_date: startDate ? formatDateForDB(startDate) : null,
                end_date: endDate ? formatDateForDB(endDate) : null
            });
        }
    });

    // Late Fee
    if (document.querySelector(`#${type}_late_fee`)?.checked) {
        const feeType = document.querySelector(`input[name="${type}_late_fee_type"]:checked`)?.value;
        const amount = document.querySelector(`input[name="${type}_late_fee_amount"]`)?.value;
        if (feeType && amount) {
            feeData.late_fee = {
                type: feeType,
                amount: amount
            };
        }
    }

    // Partial Payment
    if (document.querySelector(`#${type}_partial_fee`)?.checked) {
        const partialType = document.querySelector(`input[name="${type}_partial_fee_type"]:checked`)?.value;
        const amount = document.querySelector(`input[name="${type}_partial_fee_amount"]`)?.value;
        if (partialType && amount) {
            feeData.partial_payment = {
                type: partialType,
                amount: amount
            };
        }
    }

    return feeData;
}

// Existing functions from your original code
function calculateTotalFee() {
    let total = 0;
    let selectedFees = [];
    $('.fee-checkbox:checked').each(function() {
        const type = $(this).data('type');
        const capitalizedType = capitalizeFirstLetter(type);
        const container = $(`#${type}-input-container`);
        let typeTotal = 0;
        container.find('.fee-amount-input:visible').each(function() {
            const val = parseFloat($(this).val());
            if (!isNaN(val)) {
                typeTotal += val;
            }
        });
        if (typeTotal > 0) {
            selectedFees.push(capitalizedType + ' Fee');
            total += typeTotal;
        }
    });

    const label = selectedFees.length > 0 ? `Total Fee (${selectedFees.join(' + ')})` : 'Total Fee';
    $('#totalFeeLabel').text(label);
    $('input[name="total_fee"]').val(total.toFixed(2));
}


function calculateEndDate() {
    const durationType = document.getElementById("course_duration_type").value;
    const length = parseInt(document.getElementById("course_duration_term").value);
    const startDateStr = document.getElementById("session_start").value;

    if (!durationType || isNaN(length) || !startDateStr) {
        document.getElementById("session_end").value = "";
        return;
    }

    let startDate = new Date(startDateStr);
    let endDate = new Date(startDate);

    switch (durationType.toLowerCase()) {
        case "hourly":
            endDate.setHours(endDate.getHours() + length);
            break;
        case "weekly":
            endDate.setDate(endDate.getDate() + (7 * length));
            break;
        case "monthly":
            endDate.setMonth(endDate.getMonth() + length);
            break;
        case "quarterly":
            endDate.setMonth(endDate.getMonth() + (3 * length));
            break;
        case "half_yearly":
            endDate.setMonth(endDate.getMonth() + (6 * length));
            break;
        case "yearly":
            endDate.setFullYear(endDate.getFullYear() + length);
            break;
    }

    // Format YYYY-MM-DD
    const endDateFormatted = endDate.toISOString().split('T')[0];
    document.getElementById("session_end").value = endDateFormatted;
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

// Global fee durations for each category
const feeDurations = {
    course: ["Hourly", "Weekly", "Monthly", "Quarterly", "Half_yearly", "Yearly", "One Time"],
    registration: ["One Time"]
};

const feeDurationLimits = {
    "Hourly": ["Hourly", "One Time"],
    "Weekly": ["Hourly", "Weekly", "One Time"],
    "Monthly": ["Monthly", "One Time"],
    "Quarterly": ["Monthly", "Quarterly", "One Time"],
    "Half_yearly": ["Monthly", "Quarterly", "Half_yearly", "One Time"],
    "Yearly": ["Monthly", "Quarterly", "Half_yearly", "Yearly", "One Time"]
};

const durationToMonths = {
    "Hourly": 1 / (24 * 30),
    "Weekly": 1 / 4,
    "Monthly": 1,
    "Quarterly": 3,
    "Half_yearly": 6,
    "Yearly": 12,
    "One Time": 0
};

function getOptionsHtml(durations) {
    return durations.map(d => `<option value="${d}">${d}</option>`).join('');
}

function setSameFee(containerId, isChecked) {
    const container = document.getElementById(containerId);
    if (!container) return;
    const inputs = container.querySelectorAll(".fee-amount-input");
    if (inputs.length === 0) return;
    const firstValue = inputs[0].value;
    inputs.forEach((input, index) => {
        if (index !== 0) {
            input.value = isChecked ? firstValue : '';
        }
    });
    calculateTotalFee();
}

function createInputFields(type) {
    const courseDuration = $('#course_duration_type').val();
    const term = parseFloat($('#course_duration_term').val());
    const feeDuration = $(`select[name='${type}_fee_duration']`).val();
    const container = $(`#${type}-input-container`);
    if (!courseDuration || !term || !feeDuration || !container.length) return;

    container.html('');

    if (feeDuration === "One Time") {
        const blockHtml = `
            <div class="card shadow-sm p-3 mb-3 rounded duration-block" data-index="1">
                <h6 class="text-primary mb-3">One Time Payment</h6>
                <div class="mb-3">
                    <label class="form-label">Fee Amount</label>
                    <input type="number" name="${type}_fee_amount_1" class="form-control fee-amount-input" placeholder="Enter amount" />
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Fee Start Date</label>
                        <input type="date" name="${type}_start_date_1" class="form-control" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fee Due Date</label>
                        <input type="date" name="${type}_end_date_1" class="form-control" />
                    </div>
                </div>
            </div>
        `;
        container.append(blockHtml);
        calculateTotalFee();
        return;
    }

    const courseMonths = durationToMonths[courseDuration] * term;
    const feeMonths = durationToMonths[feeDuration];
    if (!courseMonths || !feeMonths) return;

    const rawCount = courseMonths / feeMonths;
    const count = Math.floor(rawCount);
    const hasPartial = rawCount > count;
    const maxFields = 50;
    let totalBlocks = hasPartial ? count + 1 : count;
    if (totalBlocks > maxFields) totalBlocks = maxFields;

    for (let i = 1; i <= totalBlocks; i++) {
        const isPartial = (i === totalBlocks && hasPartial);
        let blockHtml = `
            <div class="card shadow-sm p-3 mb-3 rounded duration-block" data-index="${i}">
                <h6 class="text-primary mb-3">${feeDuration} ${i}${isPartial ? ' (Partial)' : ''}</h6>
                <div class="mb-3">
                    <label class="form-label">Fee Amount</label>
                    <input type="number" name="${type}_fee_amount_${i}" class="form-control fee-amount-input" placeholder="Enter amount" />
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Fee Start Date</label>
                        <input type="date" name="${type}_start_date_${i}" class="form-control" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fee Due Date</label>
                        <input type="date" name="${type}_end_date_${i}" class="form-control" />
                    </div>
                </div>
            </div>
        `;
        if (i === 1) {
            blockHtml += `
                <div class="mb-3">
                    <input type="checkbox" id="${type}_sameFeeCheckbox" class="same-fee-checkbox" data-type="${type}">
                    <label for="${type}_sameFeeCheckbox">Same Fee for all durations</label>
                </div>
            `;
        }
        container.append(blockHtml);
    }
    calculateTotalFee();
}

function populateBatchYearDropdown(startYear, endYear) {
    const batchYearSelect = $('#batch_year');
    batchYearSelect.empty();
    batchYearSelect.append('<option value="">Select Year</option>');

    // Add all years from startYear to endYear
    for (let year = startYear; year <= endYear; year++) {
        batchYearSelect.append(`<option value="${year}">${year}</option>`);
    }

    // Set default to the start year
    batchYearSelect.val(startYear);
}

// Main document ready function
$(document).ready(function() {
    let selectedBranch = null;
    let currentStep = 1;
    let branchDetails = null;

    // Fixed: Add event listener for department category change
    $('#department_category_id').change(function() {
        const categoryId = $(this).val();
        console.log('Category changed:', categoryId); // Debug log
        loadDepartmentsByCategory(categoryId);

        // Reset downstream selects
        $('#department_id').val('').prop('disabled', false);
        $('#course_type').val('').prop('disabled', true);
        $('#branches-container').hide();
        $('#next-to-fees').prop('disabled', true);
        $('#branch-message-container').hide().empty();
        // $('#basic-details-missing-container').hide();
        autoSelectedBranchId = null;
    });

    // Seats and sections event listeners
    $('#total_seats').on('input', function() {
        const totalSeats = parseInt($(this).val()) || 0;
        const allocatedSeats = calculateAllocatedSeats();

        if (totalSeats > 0) {
            // If total seats is reduced below allocated seats, show warning
            if (totalSeats < allocatedSeats) {
                Toastify({
                    text: `Warning: Total seats (${totalSeats}) is less than allocated seats (${allocatedSeats})`,
                    duration: 4000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #ffc107, #ff6b35)",
                }).showToast();
            }

            updateAllSectionMaxSeats();
            updateSeatsSummary();
            validateAllSections();
        } else {
            updateSeatsSummary();
        }
    });

    $('#add-section-btn').on('click', function() {
        addSection();
    });



    // Update progress bar
    function updateProgress() {
        const progress = ((currentStep - 1) / 1) * 100;
        $('#progress-bar').css('width', progress + '%');

        $('.step').removeClass('active completed');
        for (let i = 1; i <= 2; i++) {
            if (i < currentStep) {
                $('#step' + i).addClass('completed');
            } else if (i === currentStep) {
                $('#step' + i).addClass('active');
            }
        }
    }

    // Navigate to step
    window.goToStep = function(step) {
        currentStep = step;
        updateProgress();

        $('.section').removeClass('active');
        $('#section-' + ['branch', 'fees'][step - 1]).addClass('active');
    };

    // Department change event
    $('#department_id').change(function() {
        let deptId = this.value;
        let courseSelect = $('#course_type');

        // Reset downstream selects
        $('#branches-container').hide();
        $('#next-to-fees').prop('disabled', true);
        $('#branch-message-container').hide().empty();
        // $('#basic-details-missing-container').hide();
        autoSelectedBranchId = null;

        if (deptId) {
            courseSelect.prop('disabled', true).addClass('loading').html(
                '<option value="">Loading courses...</option>');

            // Try multiple endpoints for courses too
            const endpoints = [
                `/ajax/course-types-by-department?department_id=${deptId}`
            ];

            fetch(endpoints[0])
                .then(res => {
                    if (!res.ok) {
                        throw new Error('First endpoint failed, trying second');
                    }
                    return res.json();
                })
                .then(data => {
                    console.log('Courses data from first endpoint:', data);
                    handleCourseResponse(data, courseSelect);
                })
                .catch(error => {
                    console.log('First endpoint failed, trying second:', error);
                    fetch(endpoints[1])
                        .then(res => {
                            if (!res.ok) {
                                throw new Error('Second endpoint also failed');
                            }
                            return res.json();
                        })
                        .then(data => {
                            console.log('Courses data from second endpoint:', data);
                            handleCourseResponse(data, courseSelect);
                        })
                        .catch(secondError => {
                            console.error('Both endpoints failed:', secondError);
                            courseSelect.html(
                                '<option value="">Error loading courses</option>');
                            courseSelect.prop('disabled', false).removeClass('loading');
                        });
                });
        } else {
            courseSelect.prop('disabled', true).html(
                '<option value="">-- Select Course Type --</option>');
        }
    });

    function handleCourseResponse(data, courseSelect) {
        courseSelect.html('<option value="">-- Select Course Type --</option>');

        // Handle both response formats
        const courses = data.courses || data.data || [];

        if ((data.status === 'success' || data.success) && courses.length > 0) {
            courses.forEach(course => {
                courseSelect.append(
                    `<option value="${course.finacp_merchant_sub_category_type || course.course_type}">${course.finacp_merchant_sub_category_type || course.course_type}</option>`
                );
            });
        } else {
            courseSelect.append('<option value="">No Courses Found</option>');
        }
        courseSelect.prop('disabled', false).removeClass('loading');
    }

    // Course change event
    $('#course_type').change(function() {
        let courseType = this.value;
        let deptId = $('#department_id').val();

        // Reset containers
        $('#branch-message-container').hide().empty();
        // $('#basic-details-missing-container').hide();
        autoSelectedBranchId = null;

        if (courseType && deptId) {
            loadBranches(deptId, courseType);
        } else {
            $('#branches-container').hide();
            $('#next-to-fees').prop('disabled', true);
        }
    });

    // Load branches function
    function loadBranches(departmentId, courseType) {
        $('#branches-list').html('<div class="text-center p-3">Loading branches...</div>');
        $('#branches-container').show();
        $('#next-to-fees').prop('disabled', true);
        $('#branch-message-container').hide().empty();
        // $('#basic-details-missing-container').hide();

        // Try multiple endpoints for branches
        const endpoints = [
            `/ajax/get-branches-by-course?department_id=${departmentId}&course_type=${encodeURIComponent(courseType)}`
        ];

        fetch(endpoints[0])
            .then(res => {
                if (!res.ok) {
                    throw new Error('First endpoint failed, trying second');
                }
                return res.json();
            })
            .then(data => {
                console.log('Branches data from first endpoint:', data);
                handleBranchResponse(data);
            })
            .catch(error => {
                console.log('First endpoint failed, trying second:', error);
                fetch(endpoints[1])
                    .then(res => {
                        if (!res.ok) {
                            throw new Error('Second endpoint also failed');
                        }
                        return res.json();
                    })
                    .then(data => {
                        console.log('Branches data from second endpoint:', data);
                        handleBranchResponse(data);
                    })
                    .catch(secondError => {
                        console.error('Both endpoints failed:', secondError);
                        $('#branches-list').html(
                            '<div class="text-center p-3 text-muted">Error loading branches. Please check console.</div>'
                        );
                    });
            });
    }

    function handleBranchResponse(data) {
        if (data.status === 'success' && data.branches && data.branches.length > 0) {
            let branchesHtml = '';

            data.branches.forEach(branch => {
                branchesHtml += `
                    <div class="col-md-6 mb-3">
                        <div class="selection-card" 
                            data-branch-id="${branch.product_id}" 
                            data-branch-name="${branch.sub_type}">
                            <div class="card-body">
                                <h6 class="card-title">${branch.sub_type}</h6>
                                <p class="card-text">
                                    <small>Course: ${branch.course_type}</small><br>
                                    <small class="text-muted">Click to select and check details</small>
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            });

            $('#branches-list').html(branchesHtml);

            // Show appropriate message
            if (data.branches.length === 1) {
                showBranchSelectionMessage(1);
                // Auto-select single branch
                autoSelectBranch(data.branches[0]);
            } else {
                showBranchSelectionMessage(data.branches.length);
            }
        } else {
            let errorMessage = 'No branches found for this course.';
            if (data.message) {
                errorMessage = data.message;
            }
            $('#branches-list').html(
                `<div class="text-center p-3 text-muted">${errorMessage}</div>`);
        }
    }

    function showBranchSelectionMessage(numBranches) {
        const messageContainer = $('#branch-message-container');

        if (numBranches === 1) {
            messageContainer.html(`
                <div class="single-branch-info">
                    <h6><i class="fas fa-check-circle text-success me-2"></i>Single Record Found</h6>
                    <p class="mb-0">Only one record is available. It has been auto-selected for you.</p>
                </div>
            `).show();
        } else {
            messageContainer.html(`
                <div class="multiple-branches-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Multiple Records Available</h6>
                    <p class="mb-0">Found ${numBranches} Records. Please select one to continue.</p>
                </div>
            `).show();
        }
    }

    function showBasicDetailsMissing() {
        $('#branches-container').hide();
        // $('#basic-details-missing-container').show();
        $('#branch-message-container').html(`
            <div class="alert alert-warning">
                <h6><i class="fas fa-exclamation-triangle me-2"></i>Basic Details Required</h6>
                <p class="mb-0">No branches have basic details configured. You need to add course duration, mode, and other basic information first.</p>
            </div>
        `).show();
    }

    function autoSelectBranch(branch) {
        autoSelectedBranchId = branch.product_id;
        $(`.selection-card[data-branch-id="${branch.product_id}"]`)
            .addClass('selected auto-selected branch-auto-selected');
        selectedBranch = branch.product_id;

        // Check if branch has basic details
        checkBranchHasBasicDetails(branch.product_id, branch.sub_type);
    }
    // Branch selection
    $(document).on('click', '.selection-card', function() {
        // Don't allow changing auto-selected single branch
        if (autoSelectedBranchId && $(this).data('branch-id') === autoSelectedBranchId) {
            Toastify({
                text: "This record is auto-selected. Only one record available.",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "linear-gradient(to right, #17a2b8, #20c997)",
            }).showToast();
            return;
        }

        $('.selection-card').removeClass('selected');
        $(this).addClass('selected');

        selectedBranch = $(this).data('branch-id');
        const branchName = $(this).data('branch-name');

        // Check if branch has basic details before enabling next button
        checkBranchHasBasicDetails(selectedBranch, branchName);
    });
    // Function to check if branch has basic details
    function checkBranchHasBasicDetails(branchId, branchName) {
        // Show loading state
        $('#next-to-fees').prop('disabled', true).html(
            '<i class="fas fa-spinner fa-spin me-2"></i>Checking details...');

        // Use your existing getBranchDetailsForFee endpoint
        $.ajax({
            url: '/get-branch-details-for-fee',
            method: 'GET',
            data: {
                branch_id: branchId
            },
            success: function(response) {
                if (response.status === 'success' && response.branch) {
                    const branch = response.branch;

                    // Check if branch has all required basic details
                    const hasBasicDetails = branch.mode_of_course &&
                        branch.mode_type &&
                        branch.course_duration &&
                        branch.course_length;

                    if (hasBasicDetails) {
                        // Enable next button
                        $('#next-to-fees').prop('disabled', false).html(
                            'Next: Configure Fees <i class="fas fa-arrow-right ms-2"></i>');

                        // Clear any warning messages
                        $('#branch-message-container').find('.alert-warning').remove();

                        Toastify({
                            text: `Selected: ${branchName}`,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "linear-gradient(to right, #007bff, #6610f2)",
                        }).showToast();
                    } else {
                        // Show missing details warning
                        showMissingDetailsWarning(branchId, branchName, branch);
                        $('#next-to-fees').prop('disabled', true).html(
                            'Next: Configure Fees <i class="fas fa-arrow-right ms-2"></i>');
                    }
                } else {
                    // Handle error
                    Toastify({
                        text: `Error loading details for ${branchName}`,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    }).showToast();
                    $('#next-to-fees').prop('disabled', true).html(
                        'Next: Configure Fees <i class="fas fa-arrow-right ms-2"></i>');
                }
            },
            error: function() {
                Toastify({
                    text: `Error checking details for ${branchName}`,
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                }).showToast();
                $('#next-to-fees').prop('disabled', true).html(
                    'Next: Configure Fees <i class="fas fa-arrow-right ms-2"></i>');
            }
        });
    }

    // Function to show missing details warning
    function showMissingDetailsWarning(branchId, branchName, branch) {
        // Create a list of missing fields
        const missingFields = [];
        if (!branch.mode_of_course) missingFields.push('Mode of Course');
        if (!branch.mode_type) missingFields.push('Mode Type');
        if (!branch.course_duration) missingFields.push('Course Duration');
        if (!branch.course_length) missingFields.push('Course Length');

        const warningHtml = `
        <div class="alert alert-warning mt-3">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Basic Details Required</h6>
            <p class="mb-2">"${branchName}" is missing basic details:</p>
            <div class="mt-2">
                <a href="{{ route('course.basic.form') }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-plus-circle me-1"></i>Add Missing Details
                </a>
            </div>
        </div>
    `;

        // Remove any existing warning and add new one
        $('#branch-message-container').find('.alert-warning').remove();
        $('#branch-message-container').append(warningHtml);
    }
    // Next to fees
    $('#next-to-fees').click(function() {
        if (!selectedBranch) return;

        // Load branch details and proceed to step 2
        loadBranchDetailsAndProceed(selectedBranch);
    });

    // Function to load branch details and proceed to step 2
    function loadBranchDetailsAndProceed(branchId) {
        $.ajax({
            url: '/get-branch-details-for-fee',
            method: 'GET',
            data: {
                branch_id: branchId
            },
            beforeSend: function() {
                $('#next-to-fees').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Loading...');
            },
            success: function(response) {
                if (response.status === 'success') {
                    branchDetails = response.branch;

                    // Double-check that branch has all required details
                    const hasAllDetails = branchDetails.mode_of_course &&
                        branchDetails.mode_type &&
                        branchDetails.course_duration &&
                        branchDetails.course_length;

                    if (!hasAllDetails) {
                        // This shouldn't happen if we checked earlier, but just in case
                        const branchName = $(`.selection-card[data-branch-id="${branchId}"]`).data(
                            'branch-name');
                        showMissingDetailsWarning(branchId, branchName, branchDetails);
                        $('#next-to-fees').prop('disabled', false).html(
                            'Next: Configure Fees <i class="fas fa-arrow-right ms-2"></i>');
                        return;
                    }

                    displayBranchInfoAndSetup(branchDetails);
                    $('#product_id').val(branchDetails.product_id);
                    goToStep(2);
                } else {
                    alert('Error: ' + response.message);
                }
                $('#next-to-fees').prop('disabled', false).html(
                    'Next: Configure Fees <i class="fas fa-arrow-right ms-2"></i>');
            },
            error: function(xhr) {
                console.error('Error loading branch details:', xhr);
                alert('Error loading branch details. Please try again.');
                $('#next-to-fees').prop('disabled', false).html(
                    'Next: Configure Fees <i class="fas fa-arrow-right ms-2"></i>');
            }
        });
    }

    function displayBranchInfoAndSetup(branch) {
        // Display branch info
        $('#selected-branch-info').html(`
            <strong>${branch.course_type} - ${branch.sub_type}</strong><br>
            <small>Duration: ${branch.course_length} ${branch.course_duration} | Mode: ${branch.mode_of_course} | Type: ${branch.mode_type}</small>
        `);

        // Set hidden values
        $('#sub_type').val(branch.sub_type);
        $('#mode_of_course').val(branch.mode_of_course);
        $('#mode_type').val(branch.mode_type);
        $('#course_duration_hidden').val(branch.course_duration);
        $('#course_length_hidden').val(branch.course_length);

        // Populate course duration fields from backend
        $('#course_duration_type').val(branch.course_duration);
        $('#course_duration_term').val(branch.course_length);

        // Display readonly fields
        $('#mode_of_course_display').val(branch.mode_of_course);
        $('#mode_type_display').val(branch.mode_type);
        $('#course_type_display').val(branch.course_type);

        // Generate and display batch ID
        const batchId = generateBatchId();
        $('#batch_id_display').val(batchId);

        // Set default start date (today + 1 month)
        const defaultStartDate = new Date();
        defaultStartDate.setMonth(defaultStartDate.getMonth() + 1);
        defaultStartDate.setDate(1); // First day of next month
        $('#session_start').val(defaultStartDate.toISOString().split('T')[0]);

        // Trigger calculations
        calculateEndDate();
        calculateAcademicYearsData();
        initializeSeatsAndSections();
        // Generate batch information
        const startDate = new Date($('#session_start').val());
        const endDate = new Date($('#session_end').val());
        generateBatchInformation(startDate, endDate);
    }

    function generateBatchInformation(startDate) {
        if (!startDate) return;

        const batchYear = startDate.getFullYear(); // Starting year

        // Calculate academic year range based on total course duration
        const durationType = $('#course_duration_type').val();
        const length = parseInt($('#course_duration_term').val());
        const totalYears = calculateTotalYears(durationType, length);
        const endYear = batchYear + totalYears - 1; // -1 because we include start year

        const academicYear = totalYears > 1 ? `${batchYear}-${endYear + 1}` : `${batchYear}`;

        // Update batch name
        $('#batch_name').val(`Batch ${batchYear} (${academicYear})`);

        // Note: The batch year dropdown is now populated in calculateAcademicYearsData
        // so we don't need to call it here again
    }

    // Modified event listeners for date calculations
    document.getElementById("course_duration_type").addEventListener("change", function() {
        calculateEndDate();
        calculateAcademicYearsData();
    });

    document.getElementById("course_duration_term").addEventListener("input", function() {
        calculateEndDate();
        calculateAcademicYearsData();
    });

    document.getElementById("session_start").addEventListener("change", function() {
        calculateEndDate();
        calculateAcademicYearsData();
    });

    // Duration type label update
    $('#course_duration_type').on('change', function() {
        const selectedText = this.options[this.selectedIndex].text;
        const label = $('#courseLengthLabel');
        if (selectedText && selectedText !== "-- Select --") {
            label.text(`Course Length (${selectedText})`);
        } else {
            label.text('Course Length');
        }

        const durationMap = {
            'hourly': 'Hourly',
            'weekly': 'Weekly',
            'monthly': 'Monthly',
            'quarterly': 'Quarterly',
            'half_yearly': 'Half_Yearly',
            'yearly': 'Yearly'
        };
        const selected = this.value.toLowerCase();
        const period = durationMap[selected] || 'Year';
        $('#feeAcademicLabel').text(`Academic ${period}s`);
    });

    // Fee category toggles
    $('.fee-category-header').on('click', function() {
        const category = $(this).closest('.fee-category');
        category.toggleClass('active');
        category.find('.fee-category-content').slideToggle();
    });

    $('.fee-checkbox').on('change', function() {
        const type = $(this).data('type');
        const container = $('#' + type + 'FeeDetails');
        if (this.checked) {
            const courseDuration = $('#course_duration_type').val();
            let allowedDurations = feeDurationLimits[courseDuration] || [];
            if (!allowedDurations.includes("One Time")) {
                allowedDurations.push("One Time");
            }
            const validDurations = (feeDurations[type] || []).filter(d => allowedDurations.includes(d));
            const $select = container.find('.fee-duration-select');
            $select.html(getOptionsHtml(validDurations));
            container.slideDown();
            if (validDurations.length === 1) {
                $select.val(validDurations[0]).trigger('change');
            } else {
                $select.trigger('change');
            }
        } else {
            container.slideUp().find('input, select').val('');
            $('#' + type + '-input-container').html('');
        }
        calculateTotalFee();
    });

    // Fee duration change
    $(document).on('change', '.fee-duration-select', function() {
        const type = $(this).data('type');
        createInputFields(type);
    });

    // Same fee checkbox
    $(document).on('change', '.same-fee-checkbox', function() {
        const type = $(this).data('type');
        setSameFee(`${type}-input-container`, this.checked);
    });

    // Late fee logic
    $('.late-fee-checkbox').on('change', function() {
        const type = $(this).data('type');
        const options = $('#' + type + '_late_fee_options');
        options.toggle(this.checked);
    });

    // Partial fee logic
    $('.partial-fee-checkbox').on('change', function() {
        const type = $(this).data('type');
        const options = $(`#${type}_partial_fee_options`);
        options.toggle(this.checked);
    });

    $(document).on('change', 'input[type=radio][name$="_late_fee_type"]', function() {
        const $optionsContainer = $(this).closest('.late-fee-options');
        const $amountContainer = $optionsContainer.find('.late-fee-amount');
        const selectedType = $(this).val();
        let labelText = selectedType === 'flat' ? 'Late Fee Amount (Flat ₹)' :
            'Late Fee Percentage (%)';
        $amountContainer.find('label').text(labelText);
        $amountContainer.find('input').attr('placeholder', selectedType === 'flat' ?
            'Enter flat amount' : 'Enter percentage');
        $amountContainer.show();
    });

    $(document).on('change', 'input[type=radio][name$="_partial_fee_type"]', function() {
        const $optionsContainer = $(this).closest('.partial-fee-options');
        const $amountContainer = $optionsContainer.find('.partial-fee-amount');
        const selectedType = $(this).val();
        let labelText = selectedType === 'fixed' ? 'Partial Fee Amount (₹)' :
            'Partial Fee Percentage (%)';
        $amountContainer.find('label').text(labelText);
        $amountContainer.find('input').attr('placeholder', selectedType === 'fixed' ?
            'Enter fixed amount' : 'Enter percentage');
        $amountContainer.show();
    });

    // Update total when fee amount inputs change
    $(document).on('input', '.fee-amount-input', function() {
        calculateTotalFee();
    });

    // Recalculate fee input fields when course duration or term changes
    $('#course_duration_type, #course_duration_term').on('change', function() {
        $('.fee-checkbox:checked').each(function() {
            const type = $(this).data('type');
            createInputFields(type);
        });
    });

    $('#course_duration_type').on('change', function() {
        const courseDuration = $(this).val();
        const allowedDurations = feeDurationLimits[courseDuration] || [];
        allowedDurations.push("One Time");
        $('.fee-checkbox:checked').each(function() {
            const type = $(this).data('type');
            const $dropdown = $(`#${type}FeeDetails`).find('.fee-duration-select');
            $dropdown.find('option').each(function() {
                const val = $(this).val();
                if (allowedDurations.includes(val)) {
                    $(this).prop('disabled', false);
                } else {
                    $(this).prop('disabled', true);
                }
            });
            const selected = $dropdown.val();
            if (!allowedDurations.includes(selected)) {
                $dropdown.val('');
            }
            $dropdown.trigger('change');
        });
    });

    // Form submission
    $('#course-fee-form').on('submit', function(e) {
        e.preventDefault();

        // Validate sections before submission
        if (!validateSectionsBeforeSubmit()) {
            return;
        }
        if (academicYearsData.length === 0) {
            alert('Please calculate academic years first by setting dates.');
            return;
        }

        const submissions = prepareFormDataForSubmission();

        console.log('Submitting data for', submissions.length, 'academic years');
        console.log('Batch ID:', submissions[0].batch_id);

        $.ajax({
            url: "{{ route('save.course.fee.structure') }}",
            method: "POST",
            data: JSON.stringify({
                submissions: submissions,
                total_records: submissions.length
            }),
            contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#submit-fee-form').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Saving ' + submissions
                    .length + ' records...');
            },
            success: function(response) {
                console.log('Success:', response);

                // Show success message
                Toastify({
                    text: `Successfully created ${submissions.length} academic year records!`,
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #28a745, #20c997)",
                }).showToast();

                // Redirect to fee structure view page after a short delay
                setTimeout(function() {
                    window.location.href = "{{ route('fee.structure.view') }}";
                }, 1500); // 1.5 second delay to show the success message

            },
            error: function(xhr) {
                console.error('Error:', xhr);
                if (xhr.status === 422) {
                    // Show validation errors in a more user-friendly way
                    let errorMessage = 'Please fix the following errors:\n';
                    const errors = xhr.responseJSON.errors;
                    for (const field in errors) {
                        errorMessage += `\n• ${errors[field].join(', ')}`;
                    }
                    alert(errorMessage);
                } else {
                    alert('Something went wrong! Please try again.');
                }
                $('#submit-fee-form').prop('disabled', false).html(
                    '<i class="fas fa-save me-2"></i>Save Fee Structure');
            }
        });
    });

    // Initialize progress
    updateProgress();
});
</script>
@endsection