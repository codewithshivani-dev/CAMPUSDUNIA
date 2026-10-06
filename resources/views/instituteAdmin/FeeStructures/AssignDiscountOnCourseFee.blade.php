@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Assign Class Fee Discount</title>
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
    --accent-glow: 0 0 15px rgba(67, 97, 238, 0.3);
}

/* Page Header - exactly matching department management */
.page-header {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px 30px;
    background: var(--primary-gradient);
    border-radius: 16px;
    box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
    display: none;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.page-title {
    /*font-size: 28px;*/
    font-weight: 700;
    color: white;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    position: relative;
    z-index: 1;
}

.page-title i {
    font-size: 32px;
    filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
}

/* Back button styling */
.btn-filter {
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 700;
    cursor: pointer;
    border: none;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    font-size: 14px;
    text-decoration: none;
}

.btn-filter:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    color: white !important;
    text-decoration: none !important;
}

.btn-filter:active {
    transform: translateY(-1px);
}

.btn-filter-primary {
    background: var(--primary-gradient);
    color: white;
    position: relative;
    overflow: hidden;
    text-decoration: none;
}

.btn-filter-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn-filter-primary:hover::before {
    left: 100%;
}

.btn-filter-secondary {
        background: var(--primary-gradient);
    color: white;
    position: relative;
    overflow: hidden;
    text-decoration: none;
}

.btn-filter-secondary:hover {
  
    color: #fff !important;
}

/* Section Box - Enhanced */
.section-box {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 25px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    position: relative;
    overflow: hidden;
}

.section-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--primary-gradient);
}

/* Section Header */
.section-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--primary-color);
}

.section-icon {
    font-size: 28px;
    margin-right: 10px;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.section-title {
    margin: 0;
    color: #1e293b;
    font-weight: 700;
    /*font-size: 18px;*/
}

/* Search Box */
.search-box {
    position: relative;
    margin-bottom: 15px;
}

.search-box .form-control {
    padding-left: 45px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    height: 45px;
    transition: all 0.3s;
}

.search-box .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    outline: none;
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary-color);
    font-size: 18px;
}

/* Assignee Counter */
.assignee-counter {
    display: inline-block;
    background: var(--primary-gradient);
    color: white;
    padding: 4px 8px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    /*margin-left: 10px;*/
    box-shadow: 0 4px 10px rgba(67, 97, 238, 0.2);
}

/* No Results */
.no-results {
    text-align: center;
    padding: 40px 20px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 12px;
}

.no-results i {
    font-size: 48px;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 15px;
}

/* Discount Card - Enhanced */
.discount-card {
    transition: all 0.3s ease;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    background: white;
}

.discount-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(67, 97, 238, 0.15);
}

.discount-card.selected {
    border-color: var(--primary-color);
    background: linear-gradient(135deg, #f8fafc, #e7f1ff);
    box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.1);
}

.discount-card .badge {
    font-size: 11px;
    border-radius: 30px;
}

.discount-savings {
    font-size: 16px;
    font-weight: 700;
    color: var(--primary-color);
}

.discount-usage {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    padding: 4px 6px;
    border-radius: 30px;
    font-size: 11px;
    color: #475569;
}

.discount-validity {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    padding: 4px 6px;
    border-radius: 30px;
    font-size: 11px;
    color: #166534;
}

/* Fee Structure Card - Enhanced */
.fee-structure-card {
    background: white;
    border: 2px solid var(--primary-color);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 10px 25px rgba(67, 97, 238, 0.1);
}

.fee-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #e2e8f0;
}

.fee-item.total {
    font-weight: bold;
    font-size: 18px;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    border-top: 2px solid var(--primary-color);
    margin-top: 10px;
    padding-top: 15px;
    border-bottom: none;
}

/* Duration Badges */
.duration-badge {
    padding: 6px 16px;
    border-radius: 30px;
    font-weight: 600;
    font-size: 12px;
    display: inline-block;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.badge-monthly {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.badge-quarterly {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
}

.badge-halfyearly {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.badge-yearly {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.badge-semester {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: white;
}

/* Student Card - Enhanced */
.student-card {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.3s;
}

.student-card:hover {
    border-color: var(--primary-color);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(67, 97, 238, 0.1);
}

.student-card.selected {
    background: linear-gradient(135deg, #f8fafc, #e7f1ff);
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.1);
}

.student-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary-gradient);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
    box-shadow: 0 4px 10px rgba(67, 97, 238, 0.2);
}

/* Discount Section */
.discount-section {
    border-right: 2px solid #f59e0b;
}

/* Applicability Card - Enhanced */
.applicability-card {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 10px;
    background: white;
}

.applicability-card:hover {
    border-color: var(--primary-color);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(67, 97, 238, 0.1);
}

.applicability-card.selected {
    border-color: var(--primary-color);
    background: linear-gradient(135deg, #f8fafc, #e7f1ff);
}

.applicability-icon {
    font-size: 28px;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-right: 10px;
}

/* Summary Box - Enhanced */
.summary-box {
    background: white;
    border: 2px solid var(--primary-color);
    border-radius: 16px;
    padding: 25px;
    margin-top: 20px;
    box-shadow: 0 10px 30px rgba(67, 97, 238, 0.1);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #e2e8f0;
}

.summary-row.total {
    font-weight: bold;
    font-size: 18px;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    border-top: 2px solid var(--primary-color);
    margin-top: 10px;
    padding-top: 15px;
    border-bottom: none;
}

/* Existing Discount Badge */
.existing-discount-badge {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    color: white;
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 30px;
    margin-left: 5px;
    display: inline-block;
    box-shadow: 0 2px 5px rgba(139, 92, 246, 0.3);
}

/* Installment Item - Enhanced */
.installment-item {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px;
    margin-bottom: 8px;
    transition: all 0.3s;
}

.installment-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.installment-header {
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 5px;
}

.installment-details {
    font-size: 0.9rem;
    color: #64748b;
}

/* Disabled Student */
.disabled-student {
    opacity: 0.6;
    cursor: not-allowed;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
}

.disabled-student:hover {
    transform: none;
    border-color: #e2e8f0;
    box-shadow: none;
}

/* Toast Container */
.toast-container {
    z-index: 9999;
}

/* Form Controls - Enhanced */
.form-control, .form-select {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 15px;
    transition: all 0.3s;
    height: auto;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    outline: none;
}

.form-control:hover, .form-select:hover {
    border-color: var(--secondary-color);
}

.form-label {
    font-weight: 600;
    color: #475569;
    margin-bottom: 8px;
}

/* Alert Messages - Enhanced */
.alert {
    border: none;
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.alert-success {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    border: 1px solid #86efac;
    color: #166534;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border: 1px solid #fca5a5;
    color: #991b1b;
}

.alert-info {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    border: 1px solid #93c5fd;
    color: #1e40af;
}

.alert-warning {
    background: linear-gradient(135deg, #fed7aa, #fdba74);
    border: 1px solid #fcd34d;
    color: #92400e;
}

/* Loading Spinner */
.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top-color: var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.spinner-border.text-primary {
    color: var(--primary-color) !important;
}

/* Buttons - Enhanced */
.btn {
    border-radius: 10px;
    padding: 10px 24px;
    font-weight: 500;
    transition: all 0.3s;
    border: none;
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

.btn-primary:hover {
    background: var(--primary-gradient);
    color: white;
}

.btn-success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn-success:hover {
    background: linear-gradient(135deg, #059669, #10b981);
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #dc2626, #ef4444);
    color: white;
}

.btn-outline-danger {
    background: transparent;
    border: 2px solid #ef4444;
    color: #ef4444;
}

.btn-outline-danger:hover {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    border-color: transparent;
    color: white;
}

.btn-outline-primary {
    background: transparent;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
}

.btn-outline-primary:hover {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
}

/* Main Card */
.card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    margin-top: 0 !important; /* Remove top margin since header handles spacing */
}

.card.shadow-sm {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
}

/* Header inside card - removed as we now have page-header */
.card h3 {
    display: none;
}

.card hr {
    display: none;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        gap: 16px;
    }
    
    .section-box {
        padding: 15px;
    }
    
    .summary-box {
        padding: 20px;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 10px;
    }
    
    /*.d-flex {*/
    /*    flex-wrap: wrap;*/
    /*}*/
}

/* Additional Enhancements */
.fw-semibold {
    font-weight: 600;
}

.text-primary {
    color: var(--primary-color) !important;
}

.bg-primary {
    background: var(--primary-gradient) !important;
}

.badge {
    font-weight: 500;
    letter-spacing: 0.3px;
}

/* Loader */
#pageLoader {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(5px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #e2e8f0;
    border-top-color: var(--primary-color);
    border-radius: 50%;
    animation: spin 0.9s linear infinite;
}

.discount-savings p{
    margin: 0px;
}

.discount-savings-desc{
    max-width: 200px;
}

#applicabilityDetails{
    display: flex;
    flex-wrap: wrap;
}

#autoDurationInfo{
    display: flex;
    flex-wrap: wrap;
}
</style>

<div id="pageLoader">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Page Header - Exactly matching department management -->
    <div class="page-header">
        <h4 class="page-title">
            <i class="fas fa-tag"></i>
            Class Fee Discount
        </h4>
        <a href="{{ route('fee.structure.view') }}" class="btn-filter btn-filter-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('course-fee.discount.assign') }}" id="assignDiscountForm">
                @csrf

                <!-- Step 1: Course Selection -->
                <div class="section-box">
                    <div class="section-header">
                        <div class="section-icon">🏛️</div>
                        <h5 class="section-title">Step 1: Select Class</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Department Category *</label>
                                <select class="form-control" id="department_category" required>
                                    <option value="">Select Category</option>
                                    @foreach($departmentCategories as $category)
                                        <option value="{{ $category->department_category_id }}">
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Department *</label>
                                <select class="form-control" id="department_id" disabled required>
                                    <option value="">Select Department</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Class*</label>
                                <select class="form-control" id="course_type" disabled required>
                                    <option value="">Select Class</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Class/Stream *</label>
                                <select class="form-control" id="branch_course" disabled required>
                                    <option value="">Select Class/Stream</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" onclick="loadFeeStructure()" id="loadFeeBtn" disabled>
                                <i class="fas fa-search me-2"></i>Load Fee Structure
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Fee Structure Details -->
                <div class="section-box" id="feeStructureSection" style="display: none;">
                    <div class="section-header">
                        <div class="section-icon">💰</div>
                        <h5 class="section-title">Step 2: Class Fee Structure</h5>
                    </div>

                    <div id="feeStructureDetails">
                        <!-- Fee structure will be loaded here -->
                    </div>
                </div>

                <!-- Step 3: Select Students -->
                <div class="section-box" id="studentsSection" style="display: none;">
                    <div class="section-header">
                        <div class="section-icon">👥</div>
                        <h5 class="section-title">Step 3: Select Students <span id="studentsCount" class="assignee-counter" style="display: none;">0</span></h5>
                    </div>

                    <div id="studentsLoading" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading students...</p>
                    </div>

                    <!-- Search Box -->
                    <div id="searchContainer" class="search-box" style="display: none;">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchStudent" class="form-control" placeholder="Search by name or registration number...">
                    </div>

                    <div id="studentsContainer" class="mb-3">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-users fa-2x mb-3" style="color: var(--primary-color);"></i>
                            <p>Please load fee structure to view students</p>
                        </div>
                    </div>

                    <!-- Selected Students Info -->
                    <div id="selectedStudentsInfo" class="alert alert-info mt-3" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div>
                                <strong><span id="selectedStudentsCount">0</span> student(s) selected</strong>
                                <div class="small mt-1" id="selectedStudentsList">
                                    <!-- Selected students list will appear here -->
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" id="clearStudentsSelection">
                                <i class="fas fa-times me-1"></i>Clear All
                            </button>
                        </div>
                    </div>

                    <!-- No Students Message -->
                    <div id="noStudentsMessage" class="alert alert-warning mt-3" style="display: none;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>No students found</strong>
                        <div class="small mt-1">No students are enrolled in this Class.</div>
                    </div>
                </div>

                <!-- Step 4: Available Discounts -->
                <div class="section-box discount-section" id="discountsSection" style="display: none;">
                    <div class="section-header">
                        <div class="section-icon">🎫</div>
                        <h5 class="section-title">Step 4: Apply Discount</h5>
                    </div>

                    <div id="discountsInfo" class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Select from available discounts for Class fees. Discounts can be applied to Class fee or registration fee.
                    </div>

                    <div id="discountsLoading" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading available discounts...</p>
                    </div>

                    <div id="discountsContainer" class="mb-3">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-tag fa-2x mb-3" style="color: var(--primary-color);"></i>
                            <p>Complete previous steps to view available discounts</p>
                            <small class="text-muted">Discounts are loaded based on selected Class</small>
                        </div>
                    </div>

                    <!-- No Discounts Message -->
                    <div id="noDiscountsMessage" class="alert alert-warning mt-3" style="display: none;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fa-2x me-3" style="color: #f59e0b;"></i>
                            <div>
                                <strong>No discounts available</strong>
                                <div class="small mt-1">
                                    No active discounts are available for Class fees at the moment.
                                    <a href="{{ route('discounts.create') }}" class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="fas fa-plus me-1"></i>Create Discount
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Discount Info -->
                    <div id="selectedDiscountInfo" class="alert alert-success mt-3" style="display: none;">
                        <div class="d-flex flex-wrap justify-content-between align-items-center w-100">
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
                </div>

                <!-- Step 5: Discount Applicability (Only 2 Options) -->
                <div class="section-box" id="applicabilitySection" style="display: none;">
                    <div class="section-header">
                        <div class="section-icon">⚙️</div>
                        <h5 class="section-title">Step 5: Discount Settings</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Discount Applicability *</label>
                                <div class="mb-3">
                                    <!-- Annual Discount Option -->
                                    <div class="applicability-card" onclick="selectApplicability('annual')" id="optionAnnual">
                                        <div class="d-flex align-items-center">
                                            <div class="applicability-icon me-3">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                            <div>
                                                <div class="option-title fw-semibold">Annual Discount</div>
                                                <div class="small text-muted">Apply discount once for the entire academic year</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Per Installment Option -->
                                    <div class="applicability-card" onclick="selectApplicability('per_installment')" id="optionPerInstallment">
                                        <div class="d-flex align-items-center">
                                            <div class="applicability-icon me-3">
                                                <i class="fas fa-calendar-check"></i>
                                            </div>
                                            <div>
                                                <div class="option-title fw-semibold">Per Installment</div>
                                                <div class="small text-muted">Apply discount separately for each installment</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="discount_applicability" name="discount_applicability" value="annual">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- Duration Info (Only for Per Installment) -->
                            <div class="form-group" id="durationInfoSection" style="display: none;">
                                <label class="form-label">Installment Duration</label>
                                <select class="form-control" id="installment_duration" name="installment_duration" disabled>
                                    <!-- Will be auto-populated based on course mode -->
                                </select>
                                <small class="text-muted" id="durationHelpText">
                                    Auto-detected from course fee structure
                                </small>
                            </div>

                            <!-- Auto-selected duration display -->
                            <div id="autoDurationInfo" class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Annual Discount Selected</strong> Discount will be applied once for the entire academic year. No duration selection needed.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Info -->
                    <div class="">
                        <div id="applicabilityDetails" class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Annual Discount:</strong> The discount will be recorded for the entire course fee amount.
                        </div>
                    </div>
                </div>

                <!-- Step 6: Summary -->
                <div class="section-box" id="summarySection" style="display: none;">
                    <div class="section-header">
                        <div class="section-icon">📋</div>
                        <h5 class="section-title">Step 6: Summary</h5>
                    </div>

                    <div class="summary-box">
                        <div class="text-center mb-4">
                            <span id="discountAppliedBadge" class="badge text-white bg-success ms-2" style="display: none; padding: 8px 16px;">
                                <i class="fas fa-tag me-1"></i>Discount Applied
                            </span>
                        </div>

                        <div class="summary-row">
                            <span>Class:</span>
                            <span id="summary_course">-</span>
                        </div>

                        <div class="summary-row">
                            <span>Students Selected:</span>
                            <span id="summary_students">0</span>
                        </div>

                        <div class="summary-row">
                            <span>Class Fee:</span>
                            <span id="summary_course_fee">₹0.00</span>
                        </div>

                        <div class="summary-row">
                            <span>Registration Fee:</span>
                            <span id="summary_registration_fee">₹0.00</span>
                        </div>

                        <div class="summary-row">
                            <span>Total Fee (Before Discount):</span>
                            <span id="summary_total_before">₹0.00</span>
                        </div>

                        <div class="summary-row" id="discountRow" style="display: none;">
                            <span>Discount:</span>
                            <span id="summary_discount">₹0.00</span>
                        </div>

                        <div class="summary-row">
                            <span>Applicability:</span>
                            <span id="summary_applicability">Annual Discount</span>
                        </div>

                        <div class="summary-row">
                            <span>Duration:</span>
                            <span id="summary_duration">Academic Year</span>
                        </div>

                        <div class="summary-row total">
                            <span>Total Payable (After Discount):</span>
                            <span id="summary_total_after">₹0.00</span>
                        </div>

                        <div id="discountDetails" class="mt-3 text-muted small" style="display: none;">
                            <i class="fas fa-info-circle me-1"></i>
                            <span id="discountDetailText"></span>
                        </div>
                    </div>
                </div>

                <!-- Hidden Fields -->
                <input type="hidden" name="product_id" id="product_id">
                <input type="hidden" name="course_fee_id" id="course_fee_id">
                <input type="hidden" name="discount_id" id="discount_id">
                <input type="hidden" id="selected_students" name="selected_students">

                <!-- Submit Button -->
                <div class="text-end">
                    <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                        <i class="fas fa-check-circle me-2"></i>Assign Discount
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// All your existing JavaScript code remains exactly the same
$(document).ready(function() {
    let selectedStudents = [];
    let selectedDiscount = null;
    let currentFeeStructure = null;
    let currentProductId = null;
    let allStudents = [];
    let filteredStudents = [];
    let discountApplicability = 'annual'; // Default to annual

    // Function to show toast messages
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        const toastContainer = document.querySelector('.toast-container') || (() => {
            const container = document.createElement('div');
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(container);
            return container;
        })();
        
        toastContainer.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    }

    // Auto-select single branch if only one exists
    function autoSelectSingleBranch() {
        const branchSelect = $('#branch_course');
        if (branchSelect.find('option').length === 2) { // 1 for default + 1 branch
            branchSelect.val(branchSelect.find('option:last').val());
            setTimeout(() => loadFeeStructure(), 100);
        }
    }

    // Department category change handler
    $('#department_category').on('change', function() {
        const categoryId = $(this).val();
        if (categoryId) {
            loadDepartments(categoryId);
        } else {
            resetDepartmentSelection();
        }
    });

    // Department change handler
    $('#department_id').on('change', function() {
        const departmentId = $(this).val();
        if (departmentId) {
            loadCourseTypes(departmentId);
        } else {
            resetCourseTypeSelection();
        }
    });

    // Course type change handler
    $('#course_type').on('change', function() {
        const courseType = $(this).val();
        const departmentId = $('#department_id').val();
        if (courseType && departmentId) {
            loadBranches(departmentId, courseType);
        } else {
            resetBranchSelection();
        }
    });

    // Clear selections handlers
    $('#clearStudentsSelection').on('click', function() {
        clearStudentsSelection();
    });

    $('#clearDiscountSelection').on('click', function() {
        clearDiscountSelection();
    });

    // Search functionality
    $(document).on('input', '#searchStudent', function() {
        const searchTerm = $(this).val().toLowerCase();
        filterStudents(searchTerm);
    });

    // Form submission handler
    $(document).on('submit', '#assignDiscountForm', function(e) {
        e.preventDefault();
        
        if (selectedStudents.length === 0) {
            showToast('Please select at least one student', 'danger');
            return;
        }
        
        if (!selectedDiscount) {
            showToast('Please select a discount', 'danger');
            return;
        }
        
        // Format students array properly - each student should be an object
        const studentsArray = selectedStudents.map(student => ({
            student_hash_id: student.student_hash_id
        }));
        
        // Set selected students as JSON string
        $('#selected_students').val(JSON.stringify(studentsArray));
        
        // Create FormData to send the data properly
        const formData = new FormData(this);
        
        // Override the students field with proper format
        formData.set('students', JSON.stringify(studentsArray));
        
        // Show loading
        $('#submitBtn').prop('disabled', true).html(
            '<span class="loading-spinner me-2"></span>Processing...'
        );
        
        // Submit form using AJAX
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Response:', response);
                if (response.success) {
                    showToast(response.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showToast(response.message || 'Failed to assign discount', 'danger');
                    $('#submitBtn').prop('disabled', false).html(
                        '<i class="fas fa-check-circle me-2"></i>Assign Discount'
                    );
                }
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                let errorMessage = 'Error assigning discount. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Show validation errors
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(field => {
                        errors[field].forEach(error => {
                            showToast(error, 'danger');
                        });
                    });
                }
                $('#submitBtn').prop('disabled', false).html(
                    '<i class="fas fa-check-circle me-2"></i>Assign Discount'
                );
            }
        });
    });

    // Function to reset department selection
    function resetDepartmentSelection() {
        $('#department_id').html('<option value="">Select Department</option>').prop('disabled', true);
        resetCourseTypeSelection();
    }

    // Function to reset course type selection
    function resetCourseTypeSelection() {
        $('#course_type').html('<option value="">Select Class</option>').prop('disabled', true);
        resetBranchSelection();
    }

    // Function to reset branch selection
    function resetBranchSelection() {
        $('#branch_course').html('<option value="">Select Branch</option>').prop('disabled', true);
        $('#loadFeeBtn').prop('disabled', true);
        resetFeeStructure();
    }

    // Function to reset fee structure
    function resetFeeStructure() {
        $('#feeStructureSection').hide();
        $('#feeStructureDetails').html('');
        $('#studentsSection').hide();
        $('#discountsSection').hide();
        $('#applicabilitySection').hide();
        $('#summarySection').hide();
        clearStudentsSelection();
        clearDiscountSelection();
        currentFeeStructure = null;
        currentProductId = null;
        allStudents = [];
        filteredStudents = [];
        selectedStudents = [];
        selectedDiscount = null;
    }

    // Function to load departments
    function loadDepartments(categoryId) {
        $.ajax({
            url: '{{ route("ajax.departments.by.category") }}',
            method: 'GET',
            data: { category_id: categoryId },
            success: function(response) {
                if (response.success) {
                    let options = '<option value="">Select Department</option>';
                    response.departments.forEach(dept => {
                        options += `<option value="${dept.department_id}">${dept.department}</option>`;
                    });
                    $('#department_id').html(options).prop('disabled', false);
                }
            },
            error: function() {
                showToast('Error loading departments', 'danger');
            }
        });
    }

    // Function to load course types
    function loadCourseTypes(departmentId) {
        $.ajax({
            url: '{{ route("ajax.course.types.by.department") }}',
            method: 'GET',
            data: { department_id: departmentId },
            success: function(response) {
                if (response.status === 'success') {
                    let options = '<option value="">Select Class</option>';
                    response.courses.forEach(course => {
                        options += `<option value="${course.finacp_merchant_sub_category_type}">${course.finacp_merchant_sub_category_type}</option>`;
                    });
                    $('#course_type').html(options).prop('disabled', false);
                }
            },
            error: function() {
                showToast('Error loading course types', 'danger');
            }
        });
    }

    // Function to load branches
    function loadBranches(departmentId, courseType) {
        $.ajax({
            url: '{{ route("ajax.branches.by.course") }}',
            method: 'GET',
            data: { 
                department_id: departmentId,
                course_type: courseType
            },
            success: function(response) {
                if (response.status === 'success') {
                    let options = '<option value="">Select Branch</option>';
                    response.branches.forEach(branch => {
                        options += `<option value="${branch.product_id}">${branch.sub_type}</option>`;
                    });
                    $('#branch_course').html(options).prop('disabled', false);
                    $('#loadFeeBtn').prop('disabled', false);
                    
                    // Auto-select if only one branch
                    if (response.branches.length === 1) {
                        autoSelectSingleBranch();
                    }
                }
            },
            error: function() {
                showToast('Error loading branches', 'danger');
            }
        });
    }

    // Function to load fee structure
    window.loadFeeStructure = function() {
        const productId = $('#branch_course').val();
        if (!productId) {
            showToast('Please select a branch first', 'danger');
            return;
        }

        currentProductId = productId;
        
        $.ajax({
            url: '{{ route("get.course.fee.structure") }}',
            method: 'GET',
            data: { product_id: productId },
            beforeSend: function() {
                $('#loadFeeBtn').prop('disabled', true).html(
                    '<span class="loading-spinner me-2"></span>Loading...'
                );
            },
            success: function(response) {
                $('#loadFeeBtn').prop('disabled', false).html(
                    '<i class="fas fa-search me-2"></i>Load Fee Structure'
                );
                
                if (response.success) {
                    currentFeeStructure = response.fee_structure;
                    // Set hidden fields
                    $('#product_id').val(productId);
                    $('#course_fee_id').val(currentFeeStructure.id);
                    
                    // Display fee structure
                    displayFeeStructure(currentFeeStructure);
                    
                    // Load students
                    loadStudents();
                    
                } else {
                    showToast('Failed to load fee structure: ' + response.message, 'danger');
                }
            },
            error: function(xhr) {
                $('#loadFeeBtn').prop('disabled', false).html(
                    '<i class="fas fa-search me-2"></i>Load Fee Structure'
                );
                showToast('Error loading fee structure. Please try again.', 'danger');
                console.error('Error:', xhr);
            }
        });
    }

    // Function to display fee structure
    function displayFeeStructure(feeStructure) {
        let html = '';
        const modeType = feeStructure.mode_type;
        const courseFee = parseFloat(feeStructure.course_fee || feeStructure.total_fee || 0);
        const registrationFee = parseFloat(feeStructure.registration_fee || 0);
        const totalFee = parseFloat(feeStructure.total_fee || 0);
        
        // Extract duration from fee_details
        let durationFromSchedule = 'N/A';
        if (feeStructure.fee_details && feeStructure.fee_details.course_fee) {
            const courseFeeDetails = feeStructure.fee_details.course_fee;
            if (courseFeeDetails.duration) {
                durationFromSchedule = courseFeeDetails.duration;
            }
        }
        
        // Store duration for later use
        currentFeeStructure.payment_duration = durationFromSchedule;
        
        // Get duration badge class
        let badgeClass = '';
        switch(modeType) {
            case 'monthly': badgeClass = 'badge-monthly'; break;
            case 'quarterly': badgeClass = 'badge-quarterly'; break;
            case 'half_yearly': badgeClass = 'badge-halfyearly'; break;
            case 'yearly': badgeClass = 'badge-yearly'; break;
            case 'semester': badgeClass = 'badge-semester'; break;
            default: badgeClass = 'badge-secondary';
        }
        
        // Parse fee details for installments
        let installmentsHtml = '';
        if (feeStructure.fee_details && feeStructure.fee_details.course_fee) {
            const courseFeeDetails = feeStructure.fee_details.course_fee;
            if (courseFeeDetails.payments && courseFeeDetails.payments.length > 0) {
                installmentsHtml = `
                    <div class="mt-3">
                        <h6 class="fw-semibold mb-3">Payment Schedule:</h6>
                        <div class="alert alert-info d-flex flex-wrap">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Duration: <strong>${durationFromSchedule}</strong>
                            <div class="small mt-1">
                                Collection Mode: <strong>${modeType}</strong>
                            </div>
                        </div>
                        <div class="mt-2">
                `;
                
                courseFeeDetails.payments.forEach((payment, index) => {
                    installmentsHtml += `
                        <div class="installment-item">
                            <div class="installment-header">Installment ${index + 1}</div>
                            <div class="installment-details">
                                Amount: ₹${parseFloat(payment.amount || 0).toFixed(2)}<br>
                                Start Date: ${payment.start_date || 'N/A'}<br>
                                End Date: ${payment.end_date || 'N/A'}
                            </div>
                        </div>
                    `;
                });
                
                installmentsHtml += '</div></div>';
            }
        }
        
        // Display basic info
        html = `
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <p><strong>Class Type:</strong> ${feeStructure.course_type || 'N/A'}</p>
                        <p><strong>Stream:</strong> ${feeStructure.sub_type || 'N/A'}</p>
                        <p><strong>Mode Type:</strong> <span class="duration-badge ${badgeClass}">${modeType || 'N/A'}</span></p>
                        <p><strong>Academic Year:</strong> ${feeStructure.academic_year || 'N/A'}</p>
                        <p><strong>Batch:</strong> ${feeStructure.batch || 'N/A'}</p>
                        <p><strong>Registration Fee:</strong> ₹${registrationFee.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fee-breakdown">
                        <h6 class="fw-semibold mb-3">Class Fee Summary</h6>
                        <div class="fee-item">
                            <span>Total Class Fee:</span>
                            <span>₹${totalFee.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                        </div>
                        ${installmentsHtml}
                    </div>
                </div>
            </div>
        `;
        
        $('#feeStructureDetails').html(html);
        $('#feeStructureSection').show();
    }

    // Function to load students
    function loadStudents() {
        if (!currentProductId) {
            showToast('Please select a Class first', 'danger');
            return;
        }

        $('#studentsLoading').show();
        $('#studentsContainer').hide();
        $('#searchContainer').hide();
        $('#selectedStudentsInfo').hide();
        $('#noStudentsMessage').hide();

        $.ajax({
            url: '{{ route("ajax.students.by.product") }}',
            method: 'GET',
            data: { product_id: currentProductId },
            success: function(response) {
                $('#studentsLoading').hide();

                if (response.success && response.students && response.students.length > 0) {
                    allStudents = response.students;
                    filteredStudents = [...allStudents];
                    renderStudents();
                    $('#studentsCount').text(response.students.length).show();
                    $('#searchContainer').show();
                    $('#searchStudent').val('');
                    $('#studentsSection').show();
                } else {
                    showNoStudents();
                    $('#studentsSection').show();
                }
                
                // Load discounts after students are loaded
                loadAvailableDiscounts();
            },
            error: function() {
                $('#studentsLoading').hide();
                showNoStudents();
            }
        });
    }

    // Function to render students
    function renderStudents() {
        if (filteredStudents.length === 0) {
            showNoResults('No students match your search');
            return;
        }

        let html = '';
        
        filteredStudents.forEach(function(student, index) {
            const hasDiscount = student.has_active_discount || false;
            const firstName = student.full_name ? student.full_name.split(' ')[0] : '';
            const avatarText = firstName ? firstName.charAt(0).toUpperCase() : '?';
            const isSelected = selectedStudents.some(s => s.student_hash_id === student.student_hash_id);
            
            // Format existing discounts
            let existingDiscountsHtml = '';
            if (student.existing_discounts && student.existing_discounts.length > 0) {
                student.existing_discounts.forEach(discount => {
                    const discountType = discount.discount_type === 'percentage' ? '%' : '₹';
                    existingDiscountsHtml += `<span class="existing-discount-badge">${discount.discount_name || 'Discount'}: ${discount.discount_value}${discountType}</span>`;
                });
            }
            
            html += `
                <div class="student-card ${isSelected ? 'selected' : ''} ${hasDiscount ? 'disabled-student' : ''}" 
                    data-id="${student.student_hash_id}" 
                    data-name="${student.full_name || 'Unknown'}" 
                    data-regno="${student.registration_number || ''}"
                    data-has-discount="${hasDiscount}">
                    <div class="d-flex align-items-center">
                        <div class="student-avatar me-2 ${hasDiscount ? 'bg-secondary' : ''}">
                            ${avatarText}
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                ${student.full_name || 'Unknown'}
                                ${existingDiscountsHtml}
                            </h6>
                            <p class="mb-0 text-muted small">
                                ${student.registration_number ? 'Reg No: ' + student.registration_number : ''}
                                <br>
                                Class Fee: ₹${student.total_fee ? parseFloat(student.total_fee).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00'}
                            </p>
                        </div>
                        ${isSelected ? '<i class="fas fa-check text-success"></i>' : ''}
                        ${hasDiscount ? '<i class="fas fa-ban text-danger" title="Already has discount"></i>' : ''}
                    </div>
                </div>
            `;
        });

        $('#studentsContainer').html(html).show();
        
        // Add click handler for student cards
        $('.student-card:not(.disabled-student)').on('click', function() {
            const studentHashId = $(this).data('id');
            const studentName = $(this).data('name');
            const registrationNumber = $(this).data('regno');
            const hasDiscount = $(this).data('has-discount');
            
            if (hasDiscount) {
                showToast('This student already has a discount', 'warning');
                return;
            }
            
            const studentIndex = selectedStudents.findIndex(s => s.student_hash_id === studentHashId);
            
            if (studentIndex === -1) {
                // Add student
                selectedStudents.push({
                    student_hash_id: studentHashId,
                    name: studentName,
                    registration_number: registrationNumber
                });
                $(this).addClass('selected');
            } else {
                // Remove student
                selectedStudents.splice(studentIndex, 1);
                $(this).removeClass('selected');
            }
            
            updateSelectedStudentsInfo();
            updateSummary();
            updateSubmitButton();
        });
    }

    // Function to filter students
    function filterStudents(searchTerm) {
        if (!searchTerm) {
            filteredStudents = [...allStudents];
        } else {
            filteredStudents = allStudents.filter(student => {
                const fullName = (student.full_name || '').toLowerCase();
                const regNo = (student.registration_number || '').toLowerCase();
                return fullName.includes(searchTerm) || regNo.includes(searchTerm);
            });
        }
        renderStudents();
    }

    // Function to update selected students info
    function updateSelectedStudentsInfo() {
        const count = selectedStudents.length;
        if (count > 0) {
            let listHtml = '';
            selectedStudents.slice(0, 3).forEach(student => {
                listHtml += `<div class="mb-1">${student.name} (${student.registration_number || 'No Reg No'})</div>`;
            });
            
            if (count > 3) {
                listHtml += `<div class="text-muted">and ${count - 3} more...</div>`;
            }
            
            $('#selectedStudentsCount').text(count);
            $('#selectedStudentsList').html(listHtml);
            $('#selectedStudentsInfo').show();
        } else {
            $('#selectedStudentsInfo').hide();
        }
    }

    // Function to clear student selection
    function clearStudentsSelection() {
        $('.student-card').removeClass('selected');
        selectedStudents = [];
        updateSelectedStudentsInfo();
        updateSummary();
        updateSubmitButton();
    }

    // Function to show no students
    function showNoStudents() {
        $('#studentsContainer').html(`
            <div class="no-results">
                <i class="fas fa-user-slash"></i>
                <p class="mt-3">No students found for this Class</p>
            </div>
        `).show();
        $('#searchContainer').hide();
        $('#studentsCount').hide();
        $('#noStudentsMessage').show();
    }

    // Function to show no results
    function showNoResults(message) {
        $('#studentsContainer').html(`
            <div class="no-results">
                <i class="fas fa-user-slash"></i>
                <p class="mt-3">${message}</p>
            </div>
        `).show();
    }

    // Function to load available discounts
    function loadAvailableDiscounts() {
        if (!currentFeeStructure) return;

        $('#discountsLoading').show();
        $('#discountsContainer').hide();
        $('#noDiscountsMessage').hide();
        $('#selectedDiscountInfo').hide();

        $.ajax({
            url: '{{ route("ajax.discounts.course") }}',
            method: 'GET',
            success: function(response) {
                $('#discountsLoading').hide();

                if (response.success && response.discounts && response.discounts.length > 0) {
                    renderDiscounts(response.discounts);
                    $('#discountsSection').show();
                } else {
                    showNoDiscountsAvailable();
                    $('#discountsSection').show();
                }
            },
            error: function() {
                $('#discountsLoading').hide();
                showNoDiscountsAvailable();
                $('#discountsSection').show();
            }
        });
    }

    // Function to render discounts
    function renderDiscounts(discounts) {
        let html = '';
        
        // IMPORTANT: Use total_fee instead of course_fee for discount calculation
        const courseFeeTotal = currentFeeStructure.total_fee || currentFeeStructure.course_fee || 0;
        const studentCount = selectedStudents.length || 1;
        
        discounts.forEach(function(discount, index) {
            const discountId = discount.discount_hash_id || discount.id;
            const discountName = discount.name || discount.discount_name || 'Unnamed Discount';
            const couponCode = discount.coupon_code || discount.discount_code || 'N/A';
            const discountType = discount.type || discount.discount_type || 'flat';
            const discountValue = parseFloat(discount.value || discount.discount_value) || 0;
            const description = discount.description || '';
            
            // Calculate discount amount - ALWAYS use total_fee for discount calculation
            let discountAmount = 0;
            let discountText = '';
            let discountTypeBadge = '';
            let usageText = '';
            
            if (discountType === 'percentage' || discountType === 'Percentage') {
                discountAmount = (courseFeeTotal * discountValue) / 100;
                discountText = `${discountValue}%`;
                discountTypeBadge = 'bg-success';
                console.log('Percentage discount amount:', discountAmount);
            } else if (discountType === 'flat' || discountType === 'Flat' || discountType === 'Fixed') {
                discountAmount = discountValue;
                discountText = `₹${discountValue.toFixed(2)}`;
                discountTypeBadge = 'bg-info';
                console.log('Flat discount amount:', discountAmount);
            } else {
                // Default to flat if type is unknown
                discountAmount = discountValue;
                discountText = `₹${discountValue.toFixed(2)}`;
                discountTypeBadge = 'bg-warning';
            }
            
            // Ensure discount doesn't exceed course fee
            if (discountAmount > courseFeeTotal) {
                discountAmount = courseFeeTotal;
            }
            
            // Get usage from the response
            const currentUsage = discount.used_count || discount.current_usage || 0;
            const maxUsage = discount.max_usage || null;
            
            // Format usage text
            if (maxUsage) {
                const remaining = maxUsage - currentUsage;
                usageText = `${currentUsage}/${maxUsage} used • ${remaining} remaining`;
            } else {
                usageText = 'Unlimited uses';
            }
            
            // Format validity
            let validityText = '';
            if (discount.valid_to) {
                const validTo = new Date(discount.valid_to);
                const today = new Date();
                const daysLeft = Math.ceil((validTo - today) / (1000 * 60 * 60 * 24));
                
                if (daysLeft > 0) {
                    validityText = `Valid for ${daysLeft} more days`;
                } else {
                    validityText = 'Expired';
                }
            } else {
                validityText = 'No expiry';
            }
            
            const isSelected = selectedDiscount && selectedDiscount.id === discountId;
            
            html += `
                <div class="card mb-3 discount-card ${isSelected ? 'selected' : ''}" 
                    data-id="${discountId}"
                    data-name="${discountName}"
                    data-code="${couponCode}"
                    data-type="${discountType.toLowerCase()}"
                    data-value="${discountValue}"
                    data-description="${description}">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-start">
                            <div class="col-md-10 flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    <h6 class="mb-0 me-2">${discountName}</h6>
                                    <span class="badge text-white me-2 ${discountTypeBadge}">${discountType.toUpperCase()} ${discountText}</span>
                                </div>
                                
                                <div class="mb-2">
                                    <span class="badge text-white bg-primary">${couponCode}</span>
                                    <span class="badge text-white bg-success">
                                        <i class="fas fa-rupee-sign me-1"></i>${discountAmount.toFixed(2)}
                                    </span>
                                </div>
                                
                                <div class="small">
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge discount-usage">
                                            <i class="fas fa-users me-1"></i>${usageText}
                                        </span>
                                        <span class="bagde discount-validity">
                                            <i class="fas fa-calendar-check me-1"></i>${validityText}
                                        </span>
                                    </div>
                                </div>
                                ${description ? `<p class="small text-muted">${description}</p>` : ''}
                            </div>
                            <div class="col-md-2">
                                <div class="discount-savings d-flex flex-wrap justify-content-end mb-2">
                                    <div class="discount-savings-desc d-flex flex-wrap justify-content-end">
                                        <strong class="me-2">Saves:</strong>
                                        <p class="me-2"><i class="fas fa-rupee-sign"></i> ${discountAmount.toFixed(2)}</p>
                                        <p class="small text-muted me-2">Per Student</p>
                                    </div>
                                    <button type="button" class="btn btn-sm ${isSelected ? 'btn-outline-danger' : 'btn-primary'} select-discount-btn me-2">
                                        ${isSelected ? '<i class="fas fa-times me-1"></i>Remove' : '<i class="fas fa-check me-1"></i>Apply'}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        $('#discountsContainer').html(html).show();
        
        // Add click handlers for discounts
        $('.select-discount-btn').on('click', function(e) {
            e.stopPropagation();
            const card = $(this).closest('.discount-card');
            
            if ($(this).hasClass('btn-primary')) {
                selectDiscount(card);
            } else {
                clearDiscountSelection();
            }
        });
        
        // Also allow clicking anywhere on the card to select
        $('.discount-card').on('click', function(e) {
            if (!$(e.target).closest('.select-discount-btn').length) {
                const card = $(this);
                if (!card.hasClass('selected')) {
                    selectDiscount(card);
                }
            }
        });
    }

    // Function to select discount
    function selectDiscount(card) {
        $('.discount-card').removeClass('selected');
        card.addClass('selected');
        
        selectedDiscount = {
            id: card.data('id'),
            name: card.data('name'),
            code: card.data('code'),
            type: card.data('type').toLowerCase(), // Ensure lowercase
            value: parseFloat(card.data('value')), // Ensure it's a number
            description: card.data('description')
        };
        
        console.log('Selected Discount:', selectedDiscount);
        console.log('Discount Type:', selectedDiscount.type);
        console.log('Discount Value:', selectedDiscount.value);
        console.log('Is Number?', !isNaN(selectedDiscount.value));
        
        // Show applicability section
        $('#applicabilitySection').show();
        
        // Update UI
        $('#selectedDiscountName').text(selectedDiscount.name);
        $('#selectedDiscountCode').text(selectedDiscount.code);
        $('#selectedDiscountType').text(selectedDiscount.type.toUpperCase());
        
        // Format value display
        if (selectedDiscount.type === 'percentage') {
            $('#selectedDiscountValue').text(`${selectedDiscount.value}%`);
        } else {
            $('#selectedDiscountValue').text(`₹${selectedDiscount.value}`);
        }
        
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
        
        // Update summary
        updateSummary();
        updateSubmitButton();
    }

    // Function to show no discounts available
    function showNoDiscountsAvailable() {
        $('#discountsContainer').hide();
        $('#noDiscountsMessage').show();
        $('#selectedDiscountInfo').hide();
        $('#applicabilitySection').hide();
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
        $('#applicabilitySection').hide();
        $('#discount_id').val('');
        updateSummary();
        updateSubmitButton();
    }

    // Function to select applicability (Annual or Per Installment)
    window.selectApplicability = function(type) {
        discountApplicability = type;
        $('#discount_applicability').val(type);
        
        // Remove selected class from all options
        $('.applicability-card').removeClass('selected');
        
        // Add selected class to clicked option
        $(`#option${type.charAt(0).toUpperCase() + type.slice(1)}`).addClass('selected');
        
        // Update UI based on selection
        updateApplicabilityUI(type);
        
        // Update summary
        updateSummary();
        updateSubmitButton();
    }

    // Function to update applicability UI
    function updateApplicabilityUI(type) {
        const durationInfoSection = $('#durationInfoSection');
        const autoDurationInfo = $('#autoDurationInfo');
        const applicabilityDetails = $('#applicabilityDetails');
        const installmentDurationSelect = $('#installment_duration');
        
        if (type === 'annual') {
            // Hide duration info, show auto info
            durationInfoSection.hide();
            autoDurationInfo.show().html(`
                <i class="fas fa-info-circle me-2"></i>
                <div>
                    <strong>Annual Discount Selected</strong>
                    <div class="small mt-1">
                        Discount will be applied once for the entire academic year. No duration selection needed.
                    </div>
                </div>
            `);
            
            applicabilityDetails.html(`
                <i class="fas fa-info-circle me-2"></i>
                <strong>Annual Discount:</strong> The discount will be recorded for the entire course fee amount.
            `);
            
            // Disable the select
            installmentDurationSelect.prop('disabled', true);
            
        } else if (type === 'per_installment') {
            // Show duration info based on course mode
            durationInfoSection.show();
            autoDurationInfo.hide();
            
            // Enable the select
            installmentDurationSelect.prop('disabled', false);
            
            // Set installment duration based on course mode or payment schedule duration
            if (currentFeeStructure) {
                // Try to get duration from payment schedule first
                let durationText = 'N/A';
                let durationValue = 'one_time';
                
                if (currentFeeStructure.payment_duration && currentFeeStructure.payment_duration !== 'N/A') {
                    durationText = currentFeeStructure.payment_duration;
                    durationValue = getDurationValueFromText(durationText);
                } else {
                    // Fallback to mode_type
                    const modeType = currentFeeStructure.mode_type;
                    const duration = getDurationForMode(modeType);
                    durationText = duration.label;
                    durationValue = duration.value;
                }
                
                installmentDurationSelect.html(`<option value="${durationValue}" selected>${durationText}</option>`);
                $('#durationHelpText').html(`Duration from payment schedule: <strong>${durationText}</strong>`);
            }
            
            applicabilityDetails.html(`
                <i class="fas fa-info-circle me-2"></i>
                <strong>Per Installment:</strong> The discount will be applied separately to each installment based on the course payment schedule.
            `);
        }
    }

    // Helper function to convert duration text to value
    function getDurationValueFromText(durationText) {
        const text = durationText.toLowerCase();
        
        if (text.includes('month')) return 'monthly';
        if (text.includes('quarter')) return 'quarterly';
        if (text.includes('half') || text.includes('semi')) return 'half_yearly';
        if (text.includes('year') || text.includes('annual')) return 'yearly';
        if (text.includes('semester')) return 'semester';
        return 'one_time';
    }

    // Update the getDurationForMode function
    function getDurationForMode(modeType) {
        const modeTypeLower = modeType ? modeType.toLowerCase() : '';
        
        switch(modeTypeLower) {
            case 'monthly':
                return { value: 'monthly', label: 'Monthly' };
            case 'quarterly':
                return { value: 'quarterly', label: 'Quarterly' };
            case 'half_yearly':
            case 'semi_yearly':
                return { value: 'half_yearly', label: 'Half Yearly' };
            case 'yearly':
            case 'annual':
                return { value: 'yearly', label: 'Yearly' };
            case 'semester':
            case 'semester_wise':
                return { value: 'semester', label: 'Semester' };
            default:
                return { value: 'one_time', label: 'One Time' };
        }
    }

    // Function to get duration for mode type
    function getDurationForMode(modeType) {
        const modeTypeLower = modeType ? modeType.toLowerCase() : '';
        
        switch(modeTypeLower) {
            case 'monthly':
                return { value: 'monthly', label: 'Monthly' };
            case 'quarterly':
                return { value: 'quarterly', label: 'Quarterly' };
            case 'half_yearly':
                return { value: 'half_yearly', label: 'Half Yearly' };
            case 'yearly':
                return { value: 'yearly', label: 'Yearly' };
            case 'semester':
                return { value: 'semester', label: 'Semester' };
            default:
                return { value: 'one_time', label: 'One Time' };
        }
    }

    // Function to update summary
    function updateSummary() {
        if (!currentFeeStructure) return;
        
        // IMPORTANT: Use correct fee values
        const courseFee = parseFloat(currentFeeStructure.total_fee || currentFeeStructure.course_fee || 0);
        const registrationFee = parseFloat(currentFeeStructure.registration_fee || 0);
        const totalPayable = courseFee + registrationFee; // This is the actual payable amount
        const studentCount = selectedStudents.length;
        
        // Calculate discount amount (only for display, not for deduction)
        let discountAmount = 0;
        let discountDetail = '';
        
        if (selectedDiscount) {
            const discountValue = parseFloat(selectedDiscount.value);
            const discountType = selectedDiscount.type.toLowerCase();
            
            console.log('Discount Value:', discountValue);
            console.log('Discount Type:', discountType);
            
            if (discountType === 'percentage') {
                discountAmount = (courseFee * discountValue) / 100;
                discountDetail = `${discountValue}% of course fee`;
                console.log('Percentage discount calculated:', discountAmount);
            } else {
                discountAmount = discountValue;
                discountDetail = `₹${discountValue} flat discount`;
                console.log('Flat discount calculated:', discountAmount);
            }
            
            // Ensure discount doesn't exceed course fee
            if (discountAmount > courseFee) {
                discountAmount = courseFee;
            }
            
            console.log('Final discount amount:', discountAmount);
        }
        
        // Get applicability display text
        let applicabilityText = '';
        let durationText = '';
        
        if (discountApplicability === 'annual') {
            applicabilityText = 'Annual Discount';
            durationText = 'Academic Year';
        } else if (discountApplicability === 'per_installment') {
            applicabilityText = 'Per Installment';
            if (currentFeeStructure && $('#installment_duration').val()) {
                durationText = $('#installment_duration option:selected').text();
            } else {
                durationText = 'Installment Wise';
            }
        }
        
        // Update summary display
        $('#summary_course').text(`${currentFeeStructure.course_type} - ${currentFeeStructure.sub_type}`);
        $('#summary_students').text(studentCount);
        $('#summary_course_fee').text('₹' + courseFee.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#summary_registration_fee').text('₹' + registrationFee.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        $('#summary_total_before').text('₹' + totalPayable.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        
        // Only show discount if there is one
        if (selectedDiscount && discountAmount > 0) {
            $('#summary_discount').text('₹' + discountAmount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            $('#discountRow').show();
            $('#discountAppliedBadge').show();
            $('#discountDetailText').text(`${selectedDiscount.name} (${selectedDiscount.code}): ${discountDetail} - Applied ${applicabilityText.toLowerCase()}`);
            $('#discountDetails').show();
        } else {
            $('#summary_discount').text('₹0.00');
            $('#discountRow').hide();
            $('#discountAppliedBadge').hide();
            $('#discountDetails').hide();
        }
        
        $('#summary_applicability').text(applicabilityText);
        $('#summary_duration').text(durationText);
        
        // IMPORTANT: Show the original total payable WITHOUT deducting discount
        $('#summary_total_after').text('₹' + totalPayable.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        
        // Add a note to clarify discount is recorded but not deducted
        if (selectedDiscount && discountAmount > 0) {
            $('#discountDetails').html(`
                <div class="d-flex flex-wrap alert alert-info small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    <strong>Note:</strong> Discount amount of ₹${discountAmount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})} will be recorded in the system.
                    Total payable fee remains unchanged as per the Class fee structure.
                </div>
            `).show();
        }
        
        // Show summary section if we have data
        if (currentFeeStructure) {
            $('#summarySection').show();
        }
    }
    // Function to update submit button
    function updateSubmitButton() {
        const hasStudents = selectedStudents.length > 0;
        const hasDiscount = selectedDiscount !== null;
        const isValid = hasStudents && hasDiscount;
        
        $('#submitBtn').prop('disabled', !isValid);
    }

    // Initialize with Annual selected by default
    selectApplicability('annual');
});
</script>
@endsection