@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
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

/* Modern Design System with Gradients */
.conversion-info {
    background: linear-gradient(135deg, #f0f9ff 0%, #e6f7ff 100%);
    border-left: 4px solid var(--primary-color);
    border-radius: 12px;
    padding: 16px 20px;
}

.conversion-rule {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 8px;
    padding: 8px 12px;
    margin: 5px 0;
    border: 1px solid #e2e8f0;
}

.conversion-rule .badge {
    font-size: 0.75rem;
    padding: 4px 8px;
}

/* Highlight for special leave types */
.leave-row.half-day-row td:first-child {
    border-left: 4px solid #f59e0b;
    background: linear-gradient(135deg, #fff7ed, #ffedd5);
}

.leave-row.short-leave-row td:first-child {
    border-left: 4px solid #10b981;
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
}

/* Page Header - Enhanced */
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

/* Legend Badges */
.legend-badges {
    display: flex;
    gap: 10px;
}

.badge.bg-primary-light {
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
    color: var(--primary-color);
    padding: 8px 16px;
    border-radius: 30px;
    font-weight: 600;
    box-shadow: 0 4px 10px rgba(67, 97, 238, 0.1);
}

/* Card Styling - Enhanced */
.card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s;
}

.card:hover {
    box-shadow: 0 15px 35px rgba(67, 97, 238, 0.12);
}

.card.border-0.shadow-lg {
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15) !important;
}

/* Step Numbers */
.step-number {
    width: 32px;
    height: 32px;
    font-size: 14px;
    font-weight: 600;
    background: var(--primary-gradient) !important;
    box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
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

/* Form Controls - Enhanced */
.form-control,
.input-group-text {
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s;
    background: white;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    outline: none;
}

.form-control:hover {
    border-color: var(--secondary-color);
}

.form-control:disabled,
.form-control[readonly] {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-color: #e2e8f0;
}

.input-group-text {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-left: none;
    color: var(--primary-color);
    font-weight: 500;
}

.input-group-text.bg-primary-light {
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-color) !important;
    font-weight: 600;
}

/* Assignment Options - Enhanced */
.assignment-option {
    cursor: pointer;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    transition: all 0.3s;
    background: white;
    height: 100%;
}

.assignment-option:hover {
    transform: translateY(-5px);
    border-color: var(--primary-color);
    box-shadow: 0 15px 35px rgba(67, 97, 238, 0.15);
}

.assignment-option.active {
    border-color: var(--primary-color);
    background: linear-gradient(135deg, #f8fafc, #e7f1ff);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.assignment-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    border-radius: 50%;
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 10px 25px rgba(67, 97, 238, 0.2);
}

.assignment-option[data-target="multipleOption"] .assignment-icon {
    background: var(--success-gradient);
}

.assignment-option[data-target="singleOption"] .assignment-icon {
    background: var(--info-gradient);
}

.assignment-option h6 {
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
}

.assignment-option p {
    color: #64748b;
}

/* Form Check Styling */
.form-check-input {
    width: 18px;
    height: 18px;
    cursor: pointer;
    border-radius: 4px;
    border: 2px solid #cbd5e1;
    transition: all 0.2s;
    margin-top: 0;
}

.form-check-input:hover {
    border-color: var(--primary-color);
    transform: scale(1.1);
}

.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
}

.form-check-input[type="radio"] {
    border-radius: 50%;
}

.form-check-input[type="radio"]:checked {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='2' fill='%23fff'/%3e%3c/svg%3e");
}

.form-check-label {
    color: #475569;
    font-weight: 500;
    cursor: pointer;
}

/* Employee List Container */
.employee-list-container {
    max-height: 400px;
    overflow-y: auto;
    padding: 10px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 12px;
}

.employee-list-container::-webkit-scrollbar {
    width: 8px;
}

.employee-list-container::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.employee-list-container::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #4361ee, #3a0ca3);
    border-radius: 4px;
}

.employee-checkbox {
    padding: 15px;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    margin-bottom: 8px;
    transition: all 0.3s;
    background: white;
}

.employee-checkbox:hover {
    background: linear-gradient(135deg, #f8fafc, #e7f1ff);
    border-color: var(--primary-color);
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.1);
}

.employee-checkbox .form-check-input:checked~label .employee-avatar {
    background: var(--primary-gradient) !important;
}

.employee-avatar {
    width: 36px;
    height: 36px;
    font-size: 16px;
    font-weight: 600;
    background: var(--primary-gradient) !important;
    color: white;
    box-shadow: 0 4px 10px rgba(67, 97, 238, 0.2);
}

/* Search Results Container */
.search-results-container {
    max-height: 300px;
    overflow-y: auto;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    background: white;
}

.search-results-container .list-group-item {
    border: none;
    border-bottom: 1px solid #f1f5f9;
    padding: 12px 16px;
    transition: all 0.3s;
}

.search-results-container .list-group-item:hover {
    background: linear-gradient(135deg, #f8fafc, #e7f1ff);
    transform: translateX(5px);
}

.search-results-container .list-group-item:last-child {
    border-bottom: none;
}

/* Table Styling - Enhanced */
.table {
    margin-bottom: 0;
}

.table thead th {
    border-bottom: 2px solid #e2e8f0;
    font-weight: 600;
    color: #475569;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    padding: 15px 16px;
}

.table tbody tr {
    transition: all 0.3s;
}

.table tbody tr:hover {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.table tbody td {
    padding: 16px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

/* Calculation Cards - Enhanced */
.calculation-card {
    text-align: center;
    padding: 25px;
    border-radius: 16px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border: 2px solid #e2e8f0;
    transition: all 0.3s;
}

.calculation-card:hover {
    border-color: var(--primary-color);
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(67, 97, 238, 0.1);
}

.calculation-label {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
}

.calculation-value {
    font-size: 36px;
    font-weight: 800;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 5px;
    line-height: 1.2;
}

.calculation-value.text-primary {
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Badge Styling - Enhanced */
.badge {
    padding: 6px 12px;
    font-weight: 600;
    border-radius: 30px;
}

.badge.bg-primary-light {
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe) !important;
    color: var(--primary-color);
}

.badge.bg-success {
    background: var(--success-gradient) !important;
    color: white;
}

.badge.bg-info {
    background: var(--info-gradient) !important;
    color: white;
}

/* Stat Cards - Enhanced */
.stat-card {
    text-align: center;
    padding: 25px;
    border-radius: 16px;
    background: white;
    border: 2px solid #e2e8f0;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--primary-gradient);
    transition: width 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 35px rgba(67, 97, 238, 0.15);
}

.stat-card:hover::before {
    width: 8px;
}

.stat-label {
    font-size: 14px;
    color: #64748b;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
}

.stat-value {
    font-weight: 800;
    margin-bottom: 4px;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 28px;
}

.stat-value.text-success {
    background: var(--success-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stat-value.text-warning {
    background: var(--warning-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Info Card - Enhanced */
.info-card {
    border-radius: 16px;
    background: linear-gradient(135deg, #e0f2fe, #bae6fd);
    border-left: 4px solid var(--primary-color);
    padding: 15px 20px;
}

.alert-info {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    border: 1px solid #93c5fd;
    color: #1e40af;
    border-radius: 16px;
}

.alert-success {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    border: 1px solid #86efac;
    color: #166534;
    border-radius: 16px;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border: 1px solid #fca5a5;
    color: #991b1b;
    border-radius: 16px;
}

/* Button Styling - Enhanced */
.btn {
    border-radius: 10px;
    font-weight: 500;
    padding: 10px 20px;
    transition: all 0.3s;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
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

.btn-outline-secondary {
    background: transparent;
    border: 2px solid #e2e8f0;
    color: #475569;
}

.btn-outline-secondary:hover {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    border-color: #cbd5e1;
    color: #1e293b;
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

.btn-outline-danger {
    background: transparent;
    border: 2px solid #ef4444;
    color: #ef4444;
}

.btn-outline-danger:hover {
    background: var(--danger-gradient);
    border-color: transparent;
    color: white;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

/* Conversion Tooltip */
.conversion-tooltip {
    position: relative;
    display: inline-block;
}

.conversion-tooltip .tooltip-text {
    visibility: hidden;
    width: 200px;
    background: linear-gradient(135deg, #1e293b, #0f172a);
    color: #fff;
    text-align: center;
    border-radius: 8px;
    padding: 8px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -100px;
    opacity: 0;
    transition: opacity 0.3s;
    font-size: 12px;
}

.conversion-tooltip:hover .tooltip-text {
    visibility: visible;
    opacity: 1;
}

/* Loading Spinner */
.spinner-border-sm {
    width: 1.5rem;
    height: 1.5rem;
    border-width: 0.2rem;
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in {
    animation: fadeIn 0.3s ease-out;
}

.leave-row {
    animation: fadeIn 0.2s ease-out;
}

/* Responsive Design */
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

    .legend-badges {
        width: 100%;
    }

    .assignment-option {
        margin-bottom: 16px;
    }

    .table-responsive {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
    }

    .stat-card,
    .calculation-card {
        margin-bottom: 16px;
    }

    .table thead {
        display: none;
    }

    .table tbody tr {
        display: block;
        margin-bottom: 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px;
    }

    .table tbody td {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        border: none;
        position: relative;
        min-height: 50px;
    }

    .table tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        color: var(--primary-color);
        margin-right: 10px;
    }

    .employee-checkbox {
        width: 100%;
    }
}
</style>

<div class="container-fluid py-4">
    <!-- Page Header - Enhanced -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-calendar-plus"></i>
            Assign Leave Quota
        </h1>
        <div class="legend-badges">
            <span class="badge bg-primary-light text-primary">
                <i class="bi bi-calendar-day me-1"></i>Yearly Full Days Only
            </span>
        </div>
    </div>

    <!-- Alerts - Enhanced -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div class="flex-grow-1">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div class="flex-grow-1">{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <!-- Missing Deductions Warning Banner -->
    @if(isset($hasMissingDeductions) && $hasMissingDeductions)
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
        <div class="d-flex align-items-start">
            <i class="bi bi-exclamation-triangle-fill me-3 fs-2"></i>
            <div class="flex-grow-1">
                <h5 class="alert-heading fw-bold mb-2">Deduction Rules Not Configured!</h5>
                <p class="mb-2">You cannot assign leave quotas because deduction rules are not configured for the following leave types:</p>
                <p class="mb-0">Please configure deduction rules first before assigning leave quotas.</p>
            </div>
            <div>
                <a href="{{ route('attendance.deductions.index') }}" class="btn btn-light btn-lg me-2">
                    <i class="bi bi-gear-fill me-2"></i> Configure Deduction Rules
                </a>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Form Card -->
    <div class="card border-0 shadow-lg">
        <div class="card-body p-4">
            <form action="{{ route('leaves.assign.store') }}" method="POST" id="assignLeaveForm">
                @csrf

                <!-- Step 1: Department Selection -->
                <div class="mb-4">
                    <h5 class="fw-semibold mb-3" style="color: var(--primary-color);">
                        <span
                            class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center me-2">1</span>
                        Select Department
                    </h5>
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold">Department Category</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0">
                                    <i class="bi bi-folder text-primary"></i>
                                </span>
                                <select id="department_category" name="department_category_id"
                                    class="form-control border-start-0" required>
                                    <option value="">Select Category</option>
                                    @foreach($departmentCategories as $category)
                                    <option value="{{ $category->department_category_id }}">
                                        {{ $category->category_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold">Department</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0">
                                    <i class="bi bi-building text-primary"></i>
                                </span>
                                <select id="department" name="department_id" class="form-control border-start-0"
                                    required disabled>
                                    <option value="">Select Department</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Employee Selection -->
                <div class="mb-4" id="employeeSelectionSection" style="display: none;">
                    <h5 class="fw-semibold mb-3" style="color: var(--primary-color);">
                        <span
                            class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center me-2">2</span>
                        Select Assignment Method
                    </h5>
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <div class="card assignment-option h-100" data-target="departmentOption">
                                <div class="card-body text-center p-4">
                                    <div class="assignment-icon mb-3">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-2">Entire Department</h6>
                                    <p class="text-muted small mb-3">Assign to all employees in this department</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="assignment_method"
                                            value="department" id="methodDepartment">
                                        <label class="form-check-label" for="methodDepartment">
                                            Select Department
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card assignment-option h-100" data-target="multipleOption">
                                <div class="card-body text-center p-4">
                                    <div class="assignment-icon mb-3">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-2">Multiple Employees</h6>
                                    <p class="text-muted small mb-3">Select specific employees from department</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="assignment_method"
                                            value="multiple" id="methodMultiple">
                                        <label class="form-check-label" for="methodMultiple">
                                            Select Employees
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card assignment-option h-100" data-target="singleOption">
                                <div class="card-body text-center p-4">
                                    <div class="assignment-icon mb-3">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-2">Single Employee</h6>
                                    <p class="text-muted small mb-3">Search and select individual employee</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="assignment_method"
                                            value="single" id="methodSingle">
                                        <label class="form-check-label" for="methodSingle">
                                            Search Employee
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Employee Selection Methods -->
                    <!-- Department Method -->
                    <div class="assignment-content mt-4" id="departmentOption" style="display: none;">
                        <div class="card border">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>
                                    <div>
                                        <h6 class="fw-semibold mb-0">Assign to Entire Department</h6>
                                        <p class="text-muted small mb-0">
                                            Leave quotas will be assigned to all employees in <span
                                                id="selectedDepartmentName" class="fw-semibold"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Multiple Employees Method -->
                    <div class="assignment-content mt-4" id="multipleOption" style="display: none;">
                        <div class="card border">
                            <div class="card-header bg-light py-3"
                                style="background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="fw-semibold mb-0">Select Employees from Department</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="selectAllEmployees">
                                            <label class="form-check-label small" for="selectAllEmployees">Select
                                                All</label>
                                        </div>
                                        <span class="badge bg-primary">
                                            <span id="selectedCount">0</span> selected
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="employeeList" class="employee-list-container">
                                    <!-- Employees will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Single Employee Method -->
                    <div class="assignment-content mt-4" id="singleOption" style="display: none;">
                        <div class="card border">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Search Employee</h6>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" id="employeeSearch" class="form-control"
                                        placeholder="Search by employee name or ID...">
                                    <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                                <div id="searchResults" class="mt-3 search-results-container" style="display: none;">
                                    <!-- Search results will appear here -->
                                </div>
                                <div id="selectedEmployeeCard" class="mt-3" style="display: none;">
                                    <div class="card bg-light border">
                                        <div class="card-body py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="employee-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-semibold mb-0" id="selectedEmployeeName"></h6>
                                                        <small class="text-muted" id="selectedEmployeeInfo"></small>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    id="removeSelectedEmployee">
                                                    <i class="bi bi-x"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Leave Configuration -->
                <div class="mb-4" id="leaveConfigSection" style="display: none;">
                    <h5 class="fw-semibold mb-3" style="color: var(--primary-color);">
                        <span
                            class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center me-2">3</span>
                        Configure Yearly Leave Quotas
                    </h5>

                    <!-- Session Year -->
                    @php
                    $month = date('m');
                    $year = date('Y');
                    $currentSession = ($month >= 4) ? $year . '-' . ($year + 1) : ($year - 1) . '-' . $year;
                    @endphp
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Academic Year</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0">
                                    <i class="bi bi-calendar text-primary"></i>
                                </span>
                                <input type="text" name="session_year" id="session_year"
                                    class="form-control border-start-0 bg-light" value="{{ $currentSession }}" required
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card alert alert-info border-0">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                                    <div>
                                        <h6 class="fw-semibold mb-1">Yearly Allocation</h6>
                                        <p class="small mb-0">Leave quotas are assigned annually and cannot be changed
                                            for the academic year.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deduction Info Card -->
                    <div class="conversion-info mb-4">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-percent me-2 mt-1 fs-5" style="color: var(--primary-color);"></i>
                            <div>
                                <h6 class="fw-semibold mb-2">Deduction Configuration (from Leave Types):</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="conversion-rule">
                                            <span class="badge bg-success me-2">✓</span>
                                            <span class="fw-semibold">Approved Leave:</span>
                                            <span>Percentage deducted from daily salary when leave is approved</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="conversion-rule">
                                            <span class="badge bg-danger me-2">✗</span>
                                            <span class="fw-semibold">Unapproved Leave:</span>
                                            <span>Percentage deducted when leave is not approved</span>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="bi bi-info-circle"></i> Deduction percentages are configured in the
                                    <a href="{{ route('attendance.deductions.index') }}">Deduction Configuration</a>
                                    page
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Leave Types Table -->
                    <div class="card border">
                        <div class="card-header py-3" style="background: linear-gradient(135deg, #f8fafc, #f1f5f9);">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="fw-semibold mb-0">Yearly Leave Types Configuration</h6>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i> All leave types are pre-configured from Leave
                                    Types Management
                                </small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="40%">Leave Type</th>
                                            <th width="30%" class="text-center">
                                                <span class="badge bg-primary-light text-primary px-3 py-1">
                                                    <i class="bi bi-calendar-day me-1"></i>Full Days (Yearly)
                                                </span>
                                            </th>
                                            <th width="25%">Deduction (% of daily salary)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="leaveRows">
                                        @foreach($leaveDeductions as $index => $deduction)
                                        <tr class="leave-row" data-leave-type="{{ strtolower($deduction->leave_type) }}"
                                            data-is-custom="{{ $deduction->is_custom ? 'true' : 'false' }}"
                                            data-leave-type-id="{{ $deduction->leave_type_id ?? '' }}">
                                            <td data-label="Leave Type">
                                                <input type="hidden" name="leave_types[{{ $index }}][type]"
                                                    value="{{ $deduction->leave_type }}">
                                                <input type="hidden" name="leave_types[{{ $index }}][leave_type_id]"
                                                    value="{{ $deduction->leave_type_id ?? '' }}">
                                                <input type="hidden" name="leave_types[{{ $index }}][is_custom]"
                                                    value="{{ $deduction->is_custom ? 1 : 0 }}">
                                                <strong>{{ $deduction->leave_type }}</strong>
                                                @if($deduction->is_custom)
                                                <span class="badge bg-warning ms-2">Custom</span>
                                                @else
                                                <span class="badge bg-secondary ms-2">Default</span>
                                                @endif
                                            </td>
                                            <td data-label="Full Days">
                                                <div class="input-group input-group-sm justify-content-center">
                                                    <input type="number" name="leave_types[{{ $index }}][full_days]"
                                                        class="form-control full-days text-center"
                                                        placeholder="Enter days" min="0" step="1" required
                                                        style="max-width: 150px;" value="0">
                                                    <span
                                                        class="input-group-text bg-primary-light border-primary-light text-primary fw-semibold">Per Year</span>
                                                </div>
                                            </td>
                                            <td data-label="Deduction">
                                                <div class="text-center">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <span class="badge bg-success">Approved:
                                                            {{ $deduction->approved_deduction_percentage }}%</span>
                                                        <span class="badge bg-danger">Unapproved:
                                                            {{ $deduction->unapproved_deduction_percentage }}%</span>
                                                    </div>
                                                    <small class="text-muted d-block mt-1">of daily salary</small>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Total Days Calculation -->
                            <div class="mt-4 pt-3 border-top">
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="calculation-card">
                                            <div class="calculation-label">Total Full Days Assigned (Yearly)</div>
                                            <div class="calculation-value" id="totalFullDays">0</div>
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle"></i>
                                                For Half Days: 2 half days = 1 full day | For Short Leave: 4 short
                                                leaves = 1 full day
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="card border mt-4">
                        <div class="card-header py-3" style="background: linear-gradient(135deg, #f8fafc, #f1f5f9);">
                            <h6 class="fw-semibold mb-0">
                                <i class="bi bi-bar-chart me-2" style="color: var(--primary-color);"></i>Assignment
                                Summary
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="stat-card">
                                        <div class="stat-label">Total Leave Types</div>
                                        <div class="stat-value" id="totalLeaveTypes">{{ count($leaveDeductions) }}</div>
                                        <small class="text-muted">Types configured</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-card">
                                        <div class="stat-label">Total Days/Year</div>
                                        <div class="stat-value" id="totalFullDaysSummary">0</div>
                                        <small class="text-muted">Annual allocation</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-card">
                                        <div class="stat-label">Assignment Scope</div>
                                        <div class="stat-value" id="assignmentScope">-</div>
                                        <small class="text-muted" id="scopeDetails">Select method</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <button type="reset" class="btn btn-outline-secondary" id="resetBtn">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset Form
                    </button>
                    <button type="submit" class="btn btn-primary px-4" id="submitBtn" disabled>
                        <i class="bi bi-check-circle me-1"></i>Assign Leave Quotas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let allEmployees = [];
    let selectedEmployees = new Set();

    // DOM Elements
    const employeeSelectionSection = document.getElementById('employeeSelectionSection');
    const leaveConfigSection = document.getElementById('leaveConfigSection');
    const submitBtn = document.getElementById('submitBtn');

    // Check if deductions are missing
    @if(isset($hasMissingDeductions) && $hasMissingDeductions)
        // Disable ALL form elements
        disableAllFormElements();
        
        // Show alert with redirect button
        Swal.fire({
            icon: 'warning',
            title: '⚠️ Deduction Rules Required',
            html: `
                <div class="text-left">
                    <p class="mb-3"><strong>You cannot assign leave quotas because deduction rules are not configured for the following leave types:</strong></p>
                    <ul class="text-left mb-3" style="text-align: left;">
                        @foreach($missingTypes as $type)
                            <li><strong class="text-danger">{{ $type }}</strong></li>
                        @endforeach
                    </ul>
                    <p class="text-muted mb-0">Please configure deduction rules first before assigning leave quotas.</p>
                </div>
            `,
            confirmButtonText: 'Go to Deduction Configuration',
            confirmButtonColor: '#4361ee',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("attendance.deductions.index") }}';
            }
        });
    @endif

    function disableAllFormElements() {
        // Disable department category
        const deptCategory = document.getElementById('department_category');
        if (deptCategory) deptCategory.disabled = true;
        
        // Disable department select
        const department = document.getElementById('department');
        if (department) department.disabled = true;
        
        // Disable all assignment method radios
        document.querySelectorAll('input[name="assignment_method"]').forEach(radio => {
            radio.disabled = true;
        });
        
        // Disable all assignment option cards click
        document.querySelectorAll('.assignment-option').forEach(card => {
            card.style.opacity = '0.5';
            card.style.cursor = 'not-allowed';
            card.style.pointerEvents = 'none';
        });
        
        // Disable all full days inputs
        document.querySelectorAll('.full-days').forEach(input => {
            input.disabled = true;
            input.style.backgroundColor = '#f3f4f6';
        });
        
        // Disable submit button
        if (submitBtn) submitBtn.disabled = true;
        
        // Disable reset button
        const resetBtn = document.getElementById('resetBtn');
        if (resetBtn) resetBtn.disabled = true;
        
        // Hide employee selection section
        const employeeSection = document.getElementById('employeeSelectionSection');
        if (employeeSection) employeeSection.style.display = 'none';
        
        // Hide leave config section
        const leaveConfig = document.getElementById('leaveConfigSection');
        if (leaveConfig) leaveConfig.style.display = 'none';
        
        // Add overlay to the form
        const formCard = document.querySelector('.card.border-0.shadow-lg');
        if (formCard) {
            formCard.style.position = 'relative';
            const overlay = document.createElement('div');
            overlay.style.position = 'absolute';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.right = '0';
            overlay.style.bottom = '0';
            overlay.style.backgroundColor = 'rgba(255, 255, 255, 0.7)';
            overlay.style.borderRadius = '20px';
            overlay.style.zIndex = '10';
            overlay.style.cursor = 'not-allowed';
            formCard.style.position = 'relative';
            formCard.appendChild(overlay);
        }
    }

    // Department Category Change
    document.getElementById('department_category').addEventListener('change', function() {
        // Skip if disabled (deductions missing)
        if (this.disabled) return;
        
        const categoryId = this.value;
        const departmentSelect = document.getElementById('department');

        if (!categoryId) {
            departmentSelect.innerHTML = '<option value="">Select Department</option>';
            departmentSelect.disabled = true;
            resetEmployeeSection();
            return;
        }

        // Show loading
        departmentSelect.innerHTML = '<option value="">Loading departments...</option>';
        departmentSelect.disabled = false;
        resetEmployeeSection();

        // Fetch departments
        fetch(`/ajax/departments-by-category?category_id=${categoryId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.departments.length > 0) {
                    let options = '<option value="">Select Department</option>';
                    data.departments.forEach(dept => {
                        const shiftInfo = dept.shift_name ? ` (${dept.shift_name})` : '';
                        const employeeCount = dept.employees_count ?
                            ` [${dept.employees_count} employees]` : '';
                        options +=
                            `<option value="${dept.department_id}" data-name="${dept.department}">${dept.department}${shiftInfo}${employeeCount}</option>`;
                    });
                    departmentSelect.innerHTML = options;
                } else {
                    departmentSelect.innerHTML = '<option value="">No departments found</option>';
                }
            })
            .catch(error => {
                console.error('Error loading departments:', error);
                departmentSelect.innerHTML = '<option value="">Error loading departments</option>';
            });
    });

    // Department Change
    document.getElementById('department').addEventListener('change', function() {
        // Skip if disabled (deductions missing)
        if (this.disabled) return;
        
        const deptId = this.value;
        const selectedOption = this.options[this.selectedIndex];
        const departmentName = selectedOption.getAttribute('data-name') || '';

        if (!deptId) {
            resetEmployeeSection();
            return;
        }

        // Show employee selection section
        employeeSelectionSection.style.display = 'block';
        document.getElementById('selectedDepartmentName').textContent = departmentName;
        resetAssignmentMethods();

        // Hide leave config section
        leaveConfigSection.style.display = 'none';
        submitBtn.disabled = true;

        // Load employees for this department
        loadDepartmentEmployees(deptId);
    });

    // Load Department Employees
    function loadDepartmentEmployees(deptId) {
        fetch(`/ajax/employees-by-department?department_id=${deptId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.employees?.length > 0) {
                    allEmployees = data.employees;
                    populateEmployeeList(allEmployees);
                } else {
                    allEmployees = [];
                    document.getElementById('employeeList').innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-people-slash text-muted fs-1 mb-3 d-block"></i>
                        <p class="text-muted mb-0">No employees found in this department</p>
                    </div>
                `;
                }
            })
            .catch(error => {
                console.error('Error loading employees:', error);
                allEmployees = [];
            });
    }

    // Populate Employee List
    function populateEmployeeList(employees) {
        const container = document.getElementById('employeeList');
        if (employees.length === 0) {
            container.innerHTML = '<div class="text-center py-4 text-muted">No employees found</div>';
            return;
        }

        let html = '<div class="row g-2">';
        employees.forEach(emp => {
            const isSelected = selectedEmployees.has(emp.employee_id.toString());
            html += `
            <div class="col-md-6">
                <div class="form-check employee-checkbox">
                    <input type="checkbox" value="${emp.employee_id}" 
                           class="form-check-input emp-checkbox" id="emp_${emp.employee_id}"
                           ${isSelected ? 'checked' : ''}>
                    <label class="form-check-label w-100" for="emp_${emp.employee_id}">
                        <div class="d-flex align-items-center">
                            <div class="employee-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                ${emp.name.charAt(0).toUpperCase()}
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold">${emp.name}</div>
                                <small class="text-muted">ID: ${emp.employee_id} • ${emp.designation || 'N/A'}</small>
                            </div>
                            <span class="badge bg-light text-dark border">${emp.employee_code || ''}</span>
                        </div>
                    </label>
                </div>
            </div>`;
        });
        html += '</div>';
        container.innerHTML = html;

        // Add event listeners
        document.querySelectorAll('.emp-checkbox').forEach(cb => {
            cb.addEventListener('change', handleEmployeeCheckboxChange);
        });
        updateSelectedCount();
    }

    function updateCalculationDisplay() {
        const calculationCard = document.querySelector('.calculation-card');
        if (calculationCard) {
            // Add conversion note
            const existingNote = calculationCard.querySelector('.conversion-note');
            if (!existingNote) {
                const conversionNote = document.createElement('div');
                conversionNote.className = 'conversion-note mt-2';
                conversionNote.innerHTML = `
                    <small class="text-muted d-block">
                        <i class="bi bi-info-circle me-1"></i>
                    </small>
                `;
                calculationCard.querySelector('small').after(conversionNote);
            }
        }
    }

    // Handle Employee Checkbox Change
    function handleEmployeeCheckboxChange(e) {
        const employeeId = e.target.value;
        if (e.target.checked) {
            selectedEmployees.add(employeeId);
        } else {
            selectedEmployees.delete(employeeId);
        }
        updateSelectedCount();
    }

    // Update Selected Count
    function updateSelectedCount() {
        const count = selectedEmployees.size;
        document.getElementById('selectedCount').textContent = count;
        document.getElementById('selectAllEmployees').checked = count > 0 && count === allEmployees.length;

        if (document.getElementById('multipleOption').style.display === 'block') {
            updateAssignmentScope();
        }
    }

    // Assignment Method Selection
    document.querySelectorAll('input[name="assignment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Skip if disabled (deductions missing)
            if (this.disabled) return;
            
            const method = this.value;

            // Hide all content sections
            document.querySelectorAll('.assignment-content').forEach(section => {
                section.style.display = 'none';
            });

            // Show selected section
            document.getElementById(`${method}Option`).style.display = 'block';

            // Update UI based on method
            updateAssignmentMethod(method);

            // Show leave config section
            leaveConfigSection.style.display = 'block';

            // Update scope
            updateAssignmentScope();
        });
    });

    // Update Assignment Method
    function updateAssignmentMethod(method) {
        // Remove active class from all cards
        document.querySelectorAll('.assignment-option').forEach(card => {
            card.classList.remove('active');
        });

        // Add active class to selected card
        document.querySelector(`.assignment-option[data-target="${method}Option"]`).classList.add('active');

        // Update submit button state
        submitBtn.disabled = false;

        // Update scope display
        updateAssignmentScope();
    }

    // Update Assignment Scope
    function updateAssignmentScope() {
        const method = document.querySelector('input[name="assignment_method"]:checked');
        if (!method) return;

        const deptSelect = document.getElementById('department');
        const departmentName = deptSelect.options[deptSelect.selectedIndex].getAttribute('data-name') || '';

        let scopeText = '';
        let detailsText = '';

        switch (method.value) {
            case 'department':
                scopeText = 'Department';
                detailsText = `All employees in ${departmentName}`;
                break;
            case 'multiple':
                scopeText = selectedEmployees.size + ' Employees';
                detailsText = selectedEmployees.size > 0 ?
                    `Selected from ${departmentName}` :
                    'No employees selected';
                break;
            case 'single':
                const selectedEmp = document.getElementById('selectedEmployeeName').textContent;
                scopeText = '1 Employee';
                detailsText = selectedEmp || 'No employee selected';
                break;
        }

        document.getElementById('assignmentScope').textContent = scopeText;
        document.getElementById('scopeDetails').textContent = detailsText;
    }

    // Select All Employees
    document.getElementById('selectAllEmployees').addEventListener('change', function() {
        selectedEmployees.clear();
        if (this.checked) {
            allEmployees.forEach(emp => {
                selectedEmployees.add(emp.employee_id.toString());
            });
        }

        // Update checkboxes
        document.querySelectorAll('.emp-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });

        updateSelectedCount();
    });

    // Employee Search
    document.getElementById('employeeSearch').addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        const resultsDiv = document.getElementById('searchResults');

        if (query.length < 2) {
            resultsDiv.style.display = 'none';
            return;
        }

        const filtered = allEmployees.filter(emp =>
            emp.name.toLowerCase().includes(query) ||
            emp.employee_id.toString().includes(query) ||
            (emp.employee_code && emp.employee_code.toLowerCase().includes(query))
        );

        if (filtered.length === 0) {
            resultsDiv.innerHTML = '<div class="text-center py-3 text-muted">No employees found</div>';
            resultsDiv.style.display = 'block';
            return;
        }

        let html = '<div class="list-group">';
        filtered.forEach(emp => {
            html += `
            <a href="#" class="list-group-item list-group-item-action select-employee" data-id="${emp.employee_id}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-0">${emp.name}</h6>
                        <small class="text-muted">ID: ${emp.employee_id} • ${emp.designation || 'N/A'}</small>
                    </div>
                    <span class="badge bg-light text-dark">${emp.employee_code || ''}</span>
                </div>
            </a>`;
        });
        html += '</div>';

        resultsDiv.innerHTML = html;
        resultsDiv.style.display = 'block';

        // Add event listeners
        document.querySelectorAll('.select-employee').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const empId = this.getAttribute('data-id');
                selectEmployee(empId);
            });
        });
    });

    // Select Employee from Search
    function selectEmployee(employeeId) {
        const employee = allEmployees.find(emp => emp.employee_id.toString() === employeeId);
        if (!employee) return;

        // Hide search results
        document.getElementById('searchResults').style.display = 'none';
        document.getElementById('employeeSearch').value = '';

        // Show selected employee card
        document.getElementById('selectedEmployeeName').textContent = employee.name;
        document.getElementById('selectedEmployeeInfo').textContent =
            `ID: ${employee.employee_id} • ${employee.designation || 'N/A'}`;
        document.getElementById('selectedEmployeeCard').style.display = 'block';

        // Set the employee ID in a hidden field
        selectedEmployees.clear();
        selectedEmployees.add(employeeId);

        // Update scope
        updateAssignmentScope();
    }

    // Remove Selected Employee
    document.getElementById('removeSelectedEmployee').addEventListener('click', function() {
        selectedEmployees.clear();
        document.getElementById('selectedEmployeeCard').style.display = 'none';
        updateAssignmentScope();
    });

    // Clear Search
    document.getElementById('clearSearch').addEventListener('click', function() {
        document.getElementById('employeeSearch').value = '';
        document.getElementById('searchResults').style.display = 'none';
    });

    // Update Leave Summary with conversion logic
    function updateLeaveSummary() {
        const rows = document.querySelectorAll('#leaveRows .leave-row');
        const totalTypes = rows.length;
        document.getElementById('totalLeaveTypes').textContent = totalTypes;

        let totalCalculatedFullDays = 0;

        rows.forEach(row => {
            const leaveType = row.getAttribute('data-leave-type') || '';
            const fullDaysInput = row.querySelector('.full-days');
            const rawValue = parseFloat(fullDaysInput.value) || 0;

            // Apply conversions based on leave type (case insensitive)
            const lowerLeaveType = leaveType.toLowerCase();

            if (lowerLeaveType === 'half days' || lowerLeaveType === 'half_day' || lowerLeaveType ===
                'half_days' || lowerLeaveType.includes('half')) {
                // 2 half days = 1 full day
                totalCalculatedFullDays += (rawValue / 2);
            } else if (lowerLeaveType === 'short leave' || lowerLeaveType === 'short_leave' ||
                lowerLeaveType.includes('short')) {
                // 4 short leaves = 1 full day
                totalCalculatedFullDays += (rawValue / 4);
            } else {
                // Regular leave types
                totalCalculatedFullDays += rawValue;
            }
        });

        // Update display with 2 decimal places
        const totalFormatted = totalCalculatedFullDays.toFixed(2);
        document.getElementById('totalFullDays').textContent = totalFormatted;
        document.getElementById('totalFullDaysSummary').textContent = totalFormatted;

        updateAssignmentScope();
    }

    // Add event listeners for all full days inputs
    document.querySelectorAll('#leaveRows .full-days').forEach(input => {
        input.addEventListener('input', updateLeaveSummary);
        input.addEventListener('change', updateLeaveSummary);
    });

    // Initial calculation
    updateLeaveSummary();

    // Form Validation
    document.getElementById('assignLeaveForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const method = document.querySelector('input[name="assignment_method"]:checked');
        if (!method) {
            Swal.fire({
                icon: 'warning',
                title: 'Selection Required',
                text: 'Please select an assignment method.',
                confirmButtonColor: '#4361ee'
            });
            return;
        }

        if (method.value === 'multiple' && selectedEmployees.size === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Employees Selected',
                text: 'Please select at least one employee.',
                confirmButtonColor: '#4361ee'
            });
            return;
        }

        if (method.value === 'single' && selectedEmployees.size === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Employee Selected',
                text: 'Please select an employee.',
                confirmButtonColor: '#4361ee'
            });
            return;
        }

        const leaveRows = document.querySelectorAll('#leaveRows .leave-row');
        if (leaveRows.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Leave Types',
                text: 'No leave types available to assign.',
                confirmButtonColor: '#4361ee'
            });
            return;
        }

        let isValid = true;
        let hasZeroDays = false;

        // Validate each row
        leaveRows.forEach(row => {
            const fullDaysInput = row.querySelector('.full-days');

            if (!fullDaysInput.value || parseFloat(fullDaysInput.value) < 0) {
                isValid = false;
                row.style.backgroundColor = '#fee2e2';
            } else if (parseFloat(fullDaysInput.value) === 0) {
                hasZeroDays = true;
                row.style.backgroundColor = '#fef3c7';
            } else {
                row.style.backgroundColor = '';
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Values',
                text: 'Please enter valid numbers for all leave types.',
                confirmButtonColor: '#4361ee'
            });
            return;
        }

        if (hasZeroDays) {
            Swal.fire({
                icon: 'warning',
                title: 'Zero Days Detected',
                text: 'Some leave types have 0 days assigned. They will not be allocated. Do you want to continue?',
                showCancelButton: true,
                confirmButtonColor: '#4361ee',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, continue',
                cancelButtonText: 'No, go back'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitForm();
                }
            });
            return;
        }

        submitForm();

        function submitForm() {
            // Add selected employees to form as hidden inputs
            const form = document.getElementById('assignLeaveForm');

            // Remove existing employee inputs to avoid duplicates
            form.querySelectorAll('input[name="employee_ids[]"]').forEach(input => input.remove());

            selectedEmployees.forEach(empId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'employee_ids[]';
                input.value = empId;
                form.appendChild(input);
            });

            // Submit the form
            form.submit();
        }
    });

    // Reset Functions
    function resetEmployeeSection() {
        employeeSelectionSection.style.display = 'none';
        leaveConfigSection.style.display = 'none';
        submitBtn.disabled = true;
        selectedEmployees.clear();
        allEmployees = [];

        document.querySelectorAll('.assignment-content').forEach(section => {
            section.style.display = 'none';
        });

        document.querySelectorAll('input[name="assignment_method"]').forEach(radio => {
            radio.checked = false;
        });

        document.querySelectorAll('.assignment-option').forEach(card => {
            card.classList.remove('active');
        });
    }

    function resetAssignmentMethods() {
        document.getElementById('employeeList').innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border spinner-border-sm text-primary"></div>
                <p class="text-muted mt-2 mb-0">Loading employees...</p>
            </div>
        `;
        document.getElementById('searchResults').style.display = 'none';
        document.getElementById('selectedEmployeeCard').style.display = 'none';
        document.getElementById('employeeSearch').value = '';
    }

    // Reset Form
    document.getElementById('resetBtn').addEventListener('click', function() {
        resetEmployeeSection();
        document.getElementById('department_category').selectedIndex = 0;
        document.getElementById('department').innerHTML = '<option value="">Select Department</option>';
        document.getElementById('department').disabled = true;

        // Reset leave rows to initial state
        const tbody = document.getElementById('leaveRows');
        tbody.querySelectorAll('.full-days').forEach(input => {
            input.value = '';
        });

        // Reset summary
        updateLeaveSummary();
    });
    
    updateCalculationDisplay();

    // Initial summary update
    updateLeaveSummary();
});
</script>
@endsection