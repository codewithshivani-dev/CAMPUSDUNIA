@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Class Fee Payments</title>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
<!-- Flatpickr Datepicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary: #4361ee;
        --primary-light: #e6eeff;
        --success: #10b981;
        --success-light: #d1fae5;
        --danger: #ef4444;
        --danger-light: #fee2e2;
        --dark: #1f2937;
        --border: #e5e7eb;
        --gray-100: #f3f4f6;
        --gray-300: #d1d5db;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
    }

    /* Header Section */
    .dashboard-header {
        background: linear-gradient(135deg, var(--primary) 0%, #3a56d4 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        color: white;
    }

    .header-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .header-subtitle {
        font-size: 1rem;
        opacity: 0.9;
    }

    /* Stats Cards */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 12px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .stat-icon-primary {
        background: var(--primary-light);
        color: var(--primary);
    }

    .stat-icon-success {
        background: var(--success-light);
        color: var(--success);
    }

    .stat-icon-danger {
        background: var(--danger-light);
        color: var(--danger);
    }

    .stat-icon-teal {
        background: #ccfbf1;
        color: #0d9488;
    }

    .stat-content {
        flex: 1;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.85rem;
        color: var(--gray-500);
        font-weight: 500;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--border);
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .filter-group {
        margin-bottom: 0;
    }

    .filter-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--dark);
        margin-bottom: 0.5rem;
        display: block;
    }

    .filter-select,
    .filter-input {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.875rem;
        background: white;
    }

    .filter-select:focus,
    .filter-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .filter-buttons {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        margin-top: 1rem;
        padding-top: 1rem;
    }

    .btn-filter {
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn-filter-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        text-decoration: none;
    }

    .btn-filter-secondary:hover {
        background: #e2e8f0;
        text-decoration: none;
    }

    /* Table Design */
    .academic-table {
        background: white;
        border-radius: 12px;
        /*overflow: hidden;*/
        border: 1px solid var(--border);
    }

    .table-header {
        background: linear-gradient(135deg, var(--dark) 0%, var(--gray-600) 100%);
        color: white;
    }

    .table-header th {
        font-weight: 500;
        padding: 1rem 1.25rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .table-body tr {
        border-bottom: 1px solid var(--border);
    }

    .table-body tr:hover {
        background-color: var(--primary-light);
    }

    .table-body td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
    }

    /* Student Info */
    .student-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .student-name {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.95rem;
    }

    .duration-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        background: var(--primary-light);
        color: var(--primary);
        width: fit-content;
    }

    /* Academic Info */
    .academic-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        min-width: 220px;
    }

    .department {
        font-size: 0.75rem;
        color: var(--gray-500);
        font-weight: 500;
        text-transform: uppercase;
    }

    .course-name {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.95rem;
    }

    .academic-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.25rem;
    }

    .meta-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.2rem 0.5rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .batch-tag {
        background: var(--primary-light);
        color: var(--primary);
    }

    .year-tag {
        background: var(--success-light);
        color: var(--success);
    }

    .semester-tag {
        background: #ede9fe;
        color: #8b5cf6;
    }

    .section-tag {
        background: var(--gray-100);
        color: var(--gray-600);
    }

    /* Mode Info */
    .mode-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .mode-type {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.9rem;
    }

    .offline-badge {
        background: #dbeafe;
        color: #3b82f6;
        padding: 0.2rem 0.5rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 500;
        width: fit-content;
    }

    /* Amount Columns */
    /*.amount-column {*/
    /*    text-align: right;*/
    /*    min-width: 110px;*/
    /*}*/

    .amount-display {
        font-weight: 700;
        font-size: 1rem;
        color: var(--dark);
    }

    .amount-label {
        font-size: 0.75rem;
        color: var(--gray-500);
        text-transform: uppercase;
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .fee-amount {
        color: var(--primary);
    }

    .late-fee-amount {
        font-weight: 600;
        color: var(--danger);
    }

    .no-late-fee,
    .no-discount {
        color: var(--gray-400);
        font-style: italic;
        font-size: 0.85rem;
    }

    .discount-amount {
        font-weight: 600;
        color: var(--success);
    }

    .payable-amount {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--dark);
        background: var(--primary-light);
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        display: inline-block;
        border: 2px solid var(--primary);
    }

    /* Date & Status */
    .date-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .date-group {
        display: flex;
        flex-direction: column;
        gap: 0.125rem;
    }

    .date-label {
        font-size: 0.7rem;
        color: var(--gray-500);
        text-transform: uppercase;
        font-weight: 600;
    }

    .date-value {
        font-size: 0.85rem;
        color: var(--dark);
        font-weight: 500;
        background: var(--gray-100);
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        border: 1px solid var(--border);
    }

    .status-badge {
        padding: 0.35rem 0.875rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .status-paid {
        background-color: var(--success-light);
        color: var(--success);
    }

    .status-pending {
        background-color: #fef3c7;
        color: #d97706;
    }

    .status-overdue {
        background-color: var(--danger-light);
        color: var(--danger);
    }

    .updated-info {
        font-size: 0.75rem;
        color: var(--gray-500);
        display: flex;
        align-items: center;
        gap: 0.375rem;
        min-width: 120px;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 120px;
    }

    /* Payment Modal */
    .payment-modal {
        max-width: 500px;
    }

    .payment-details {
        background: var(--gray-100);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .payment-detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--border);
    }

    .payment-detail-row:last-child {
        border-bottom: none;
    }

    .payment-amount-display {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
        text-align: center;
        margin: 1rem 0;
    }

    .payment-method-options {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin: 1.5rem 0;
    }

    .payment-method-btn {
        padding: 1rem;
        border: 2px solid var(--border);
        border-radius: 8px;
        background: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .payment-method-btn:hover,
    .payment-method-btn.active {
        border-color: var(--primary);
        background: var(--primary-light);
    }

    .payment-method-icon {
        font-size: 1.5rem;
        color: var(--primary);
    }

    /* Table Footer */
    .table-footer {
        background: white;
        padding: 1rem 1.25rem;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .records-count {
        font-size: 0.875rem;
        color: var(--gray-500);
    }

    .pagination-buttons {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .pagination-btn {
        padding: 0.5rem 1rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: white;
        color: var(--dark);
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        cursor: pointer;
    }

    .pagination-btn:hover:not(:disabled) {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        text-decoration: none;
    }

    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .pagination-info {
        padding: 0.5rem 1rem;
        background: var(--gray-100);
        border-radius: 8px;
        font-size: 0.875rem;
        color: var(--gray-600);
    }

    /* Date Range */
    .date-range-wrapper {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .date-field {
        position: relative;
        width: 100%;
    }

    .date-field input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background: #fff;
        font-size: 14px;
    }

    .date-field label {
        position: absolute;
        top: 50%;
        left: 12px;
        transform: translateY(-50%);
        background: #fff;
        padding: 0 4px;
        font-size: 12px;
        color: #6b7280;
        pointer-events: none;
        transition: 0.2s ease;
    }

    .date-field input:focus+label,
    .date-field input:not(:placeholder-shown)+label {
        top: 3px;
        font-size: 11px;
        color: #2563eb;
    }

    .date-field input:focus {
        border-color: #2563eb;
        outline: none;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
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
        width: 40px;
        height: 40px;
        border: 4px solid #ccc;
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--gray-500);
    }

    .empty-icon {
        font-size: 3rem;
        color: var(--gray-300);
        margin-bottom: 1rem;
    }
        /* ERP Table Styles */
        .erp-table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
        }

        .erp-table thead {
            background: var(--primary-gradient);
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .erp-table th {
            padding: 15px 8px;
            font-weight: 600;
            color: white;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s;
            position: relative;
        }

        .erp-table th h6 {
            color: rgba(255,255,255,0.9);
            font-size: 11px;
            margin: 2px 0 0;
        }

        .erp-table th:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .erp-table th.sortable {
            padding-right: 30px;
        }

        .sort-icons {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sort-icon {
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            line-height: 1;
        }

        .sort-icon.active {
            color: white;
        }

        .erp-table td {
            padding: 15px 8px;
            color: #334155;
            font-size: 14px;
            vertical-align: middle;
        }

        .erp-table tbody tr {
            transition: background-color 0.2s, transform 0.2s;
        }

        .erp-table tbody tr:hover {  
            background-color: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .erp-table tbody tr:last-child td {
            border-bottom: none;
        }
        .table-responsive{
            overflow-x: hidden;
        }
</style>


<div id="pageLoader">
    <div class="spinner"></div>
</div>

<div class="container-fluid">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="header-title">
                    <i class="bi bi-building-fill me-2"></i>Class Fee Payments
                </h1>
                <p class="header-subtitle">Track and manage all Class fee payments across departments</p>
            </div>
            <button class="btn btn-light d-none align-items-center gap-2">
                <i class="bi bi-plus-circle"></i> New Payment
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon stat-icon-primary">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $paginator->total() }}</div>
                    <div class="stat-label">Total Students</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon-success">
                    <i class="bi bi-currency-rupee"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">₹{{ number_format($totalPayable, 2) }}</div>
                    <div class="stat-label">Total Payable</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon-teal">
                    <i class="bi bi-credit-card"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalPaidCount }}</div>
                    <div class="stat-label">Paid Payments</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $totalOverdueCount }}</div>
                    <div class="stat-label">Overdue Payments</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <form method="GET">
        <div class="filter-section">
            <div class="filter-row">

                <div class="filter-group">
                    <label class="filter-label">Department</label>
                    <input list="departmentsList" name="department" value="{{ request('department') }}"
                        class="filter-input" placeholder="Search Department">

                    <input type="hidden" name="department_id" id="department_id_hidden"
                        value="{{ request('department_id') }}">

                    <datalist id="departmentsList">
                        @foreach($departments as $dept)
                        <option value="{{ $dept->department }}" data-id="{{ $dept->department_id }}"></option>
                        @endforeach
                    </datalist>

                </div>

                <div class="filter-group">
                    <label class="filter-label">Class</label>
                    <input list="courseList" name="course" value="{{ request('course') }}"
                        class="filter-input auto-submit">

                    <datalist id="courseList">
                        @foreach($courses as $course)
                        <option value="{{ $course }}">
                            @endforeach
                    </datalist>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Batch</label>
                    <input list="batchList" name="batch" value="{{ request('batch') }}"
                        class="filter-input auto-submit">

                    <datalist id="batchList">
                        @foreach($batches as $batch)
                        <option value="{{ $batch }}">
                            @endforeach
                    </datalist>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Academic Year</label>
                    <select class="filter-select auto-submit" name="academic_year">
                        <option value="">Current Academic Year</option>
                        @foreach($academicYears as $year)
                        <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Payment Status</label>
                    <select class="filter-select auto-submit" name="payment_status">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending
                        </option>
                        <option value="overdue" {{ request('payment_status') == 'overdue' ? 'selected' : '' }}>Overdue
                        </option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Search Student</label>
                    <input type="text" name="student_search" value="{{ request('student_search') }}"
                        class="filter-input auto-submit" placeholder="Search student">
                </div>

                <div class="filter-group">
                    <label class="filter-label">Payment Type</label>
                    <input list="paymentTypeList" name="payment_type" value="{{ request('payment_type') }}"
                        class="filter-input auto-submit">

                    <datalist id="paymentTypeList">
                        <option value="online">
                        <option value="cash">
                        <option value="bank">
                        <option value="emi">
                    </datalist>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Mode Type</label>
                    <input list="modeTypeList" name="mode_type" value="{{ request('mode_type') }}"
                        class="filter-input auto-submit">

                    <datalist id="modeTypeList">
                        <option value="full_time">
                        <option value="part_time">
                    </datalist>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Date Range</label>

                    <div class="date-range-wrapper">

                        <!-- Start Date -->
                        <div class="date-field">
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="filter-input auto-submit" placeholder=" ">
                            <label>Start Date</label>
                        </div>

                        <!-- End Date -->
                        <div class="date-field">
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="filter-input auto-submit" placeholder=" ">
                            <label>End Date</label>
                        </div>

                    </div>
                </div>
                <div class="filter-buttons">
                    <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary"
                        style="color:#64748b !important;text-decoration:none">
                        <i class="bi bi-x-circle"></i>
                        Reset Filters
                    </a>
                </div>
            </div>

        </div>
    </form>

    <!-- Table: one row per student, showing current installment -->
    <div class="academic-table">
         <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table table table-hover mb-0">
                <thead class="table-header">
                    <tr>
                        <th class="sticky-main-2 sortable">STUDENT</th>
                        <th class="sortable">ACADEMIC DETAILS</th>
                        <th class="sortable">MODE</th>
                        <th class="sortable">FEE AMOUNT</th>
                        <th class="sortable">LATE FEE</th>
                        <th class="sortable">DISCOUNT</th>
                        <th class="sortable">PAYABLE</th>
                        <th class="sortable">DATES / STATUS</th>
                        <th class="sortable">UPDATED</th>
                        <th class="sortable">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="table-body" id="paymentsTableBody">
                    @forelse($paginator as $student)
                    @php
                    $current = $student['current_installment'];
                    $hasCurrent = !is_null($current);
                    $dueDate = $hasCurrent ? \Carbon\Carbon::parse($current['due_date']) : null;
                    $status = $hasCurrent ? ($current['payment_status'] === 'paid' ? 'paid' : ($dueDate &&
                    $dueDate->lt(\Carbon\Carbon::today()) ? 'overdue' : 'pending')) : 'no_installments';
                    @endphp
                    <tr class="payment-row" data-student-hash="{{ $student['student_hash_id'] }}">
                        <!-- Student Column -->
                        <td class="sticky-main-2">
                            <div class="student-info">
                                <div class="student-name">{{ $student['student_name'] }}</div>
                                <div class="duration-badge">
                                    <i class="bi bi-person-badge"></i> {{ $student['student_reg'] }}
                                </div>
                            </div>
                        </td>

                        <!-- Academic Details Column -->
                        <td>
                            <div class="academic-info">
                                <div class="department">{{ $student['department'] }}</div>
                                <div class="course-name">{{ $student['course'] }}</div>
                                <div class="academic-meta">
                                    <span class="meta-tag batch-tag"><i class="bi bi-calendar-week"></i>
                                        {{ $student['batch'] }}</span>
                                    <span class="meta-tag year-tag"><i class="bi bi-calendar"></i>
                                        {{ $student['academic_year'] }}</span>
                                    <span class="meta-tag semester-tag"><i class="bi bi-journal"></i>
                                        {{ $student['semester'] }}</span>
                                    <span class="meta-tag section-tag"><i class="bi bi-people"></i>
                                        {{ $student['section'] }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Mode Column -->
                        <td>
                            <div class="mode-info">
                                <div class="mode-type">{{ ucfirst($student['mode_type']) }}</div>
                                <span class="mode-badge offline-badge">{{ ucfirst($student['mode_of_course']) }}</span>
                            </div>
                        </td>

                        <!-- Current Fee Amount -->
                        <td class="amount-column">
                            <!-- <div class="amount-label">Current Fee</div> -->
                            <div class="amount-display fee-amount">
                                ₹{{ $hasCurrent ? number_format($current['fee_amount'], 2) : '0.00' }}
                            </div>
                        </td>

                        <!-- Current Late Fee -->
                        <td class="amount-column">
                            <div class="amount-label">Late Fee</div>
                            <div class="late-fee-container">
                                @if($hasCurrent && $current['late_fee'] > 0)
                                <div class="late-fee-amount">+₹{{ number_format($current['late_fee'], 2) }}</div>
                                @else
                                <div class="no-late-fee">No late fee</div>
                                @endif
                            </div>
                        </td>

                        <!-- Current Discount -->
                        <td class="amount-column">
                            <div class="amount-label">Discount</div>
                            <div class="discount-container">
                                @if($hasCurrent && $current['discount'] > 0)
                                <div class="discount-amount">-₹{{ number_format($current['discount'], 2) }}</div>
                                @else
                                <div class="no-discount">No discount</div>
                                @endif
                            </div>
                        </td>

                        <!-- Current Payable -->
                        <td class="amount-column">
                            <div class="amount-label">Payable</div>
                            <div class="payable-amount">
                                @php
                                $payableAmount = 0;
                                if($hasCurrent) {
                                // Calculate payable as: fee_amount + late_fee - discount
                                $payableAmount = ($current['fee_amount'] ?? 0) +
                                ($current['late_fee'] ?? 0) -
                                ($current['discount'] ?? 0);
                                }
                                @endphp
                                ₹{{ number_format($payableAmount, 2) }}
                            </div>
                        </td>

                        <!-- Due Date & Status (combined for brevity) -->
                        <td>
                            <div class="date-info">
                                <div class="date-group">
                                    <div class="date-label">Due Date</div>
                                    <div class="date-value">
                                        {{ $hasCurrent ? \Carbon\Carbon::parse($current['due_date'])->format('d M Y') : 'N/A' }}
                                    </div>
                                </div>
                                <div class="status-column mt-2">
                                    @if($status == 'paid')
                                    <span class="status-badge status-paid"><i class="bi bi-check-circle"></i>
                                        Paid</span>
                                    @elseif($status == 'overdue')
                                    <span class="status-badge status-overdue"><i class="bi bi-exclamation-triangle"></i>
                                        Overdue</span>
                                    @elseif($status == 'pending')
                                    <span class="status-badge status-pending"><i class="bi bi-clock"></i> Pending</span>
                                    @else
                                    <span class="status-badge status-none"><i class="bi bi-dash"></i> No fee</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Updated At -->
                        <td>
                            <div class="updated-info">
                                <i class="bi bi-clock-history"></i>
                                {{ $hasCurrent ? \Carbon\Carbon::parse($current['updated_at'])->diffForHumans() : 'N/A' }}
                            </div>
                        </td>

                        <!-- Actions -->
                        <td>
                            <div class="action-buttons"
                                style="display: flex; flex-direction: column; gap: 8px; min-width: 120px;">
                                @if($hasCurrent && $current['payment_status'] != 'paid')
                                <button class="btn btn-sm btn-success w-100"
                                    title="Click to record payment for this installment"
                                    onclick="newrecordPayment('{{ $student['student_hash_id'] }}', {{ $current['id'] }})">
                                    <i class="bi bi-cash-coin me-2"></i>Collect Fee

                                </button>
                                @endif

                                <button class="btn btn-sm btn-info w-100 text-white"
                                    title="View complete payment history"
                                    onclick="window.open('{{ route('student.installments', [
                                        'student_hash' => trim($student['student_hash_id']),
                                        'academic_year' => request('academic_year')
                                    ]) }}', '_blank')">
                                    <i class="bi bi-clock-history me-2"></i>View More
                                </button>


                                @if(!$hasCurrent)
                                <div class="alert alert-warning py-2 px-3 mb-0" style="font-size: 12px;">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    No current installment
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="empty-state">
                            <div class="empty-icon"><i class="bi bi-building"></i></div>
                            <h4 class="text-muted mb-2">No Class fee records found</h4>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Floating Horizontal Scrollbar -->
        <div class="table-scroll-top" id="tableScrollTop">
            <div class="table-scroll-inner"></div>
        </div>
        <!-- Table Footer with Pagination -->
        @if($paginator->total() > 0)
        <div class="table-footer">
            <div class="records-count">
                Showing <span>{{ $paginator->firstItem() }}</span> to <span>{{ $paginator->lastItem() }}</span>
                of <span>{{ $paginator->total() }}</span> students
            </div>
            <div class="pagination-buttons">
                @if ($paginator->onFirstPage())
                <button class="pagination-btn" disabled>
                    <i class="bi bi-chevron-left"></i> Previous
                </button>
                @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn"
                    onclick="showLoader(); return true;">
                    <i class="bi bi-chevron-left"></i> Previous
                </a>
                @endif

                <div class="pagination-info">
                    Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
                </div>

                @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn" onclick="showLoader(); return true;">
                    Next <i class="bi bi-chevron-right"></i>
                </a>
                @else
                <button class="pagination-btn" disabled>
                    Next <i class="bi bi-chevron-right"></i>
                </button>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered payment-modal">
        <div class="modal-content" style="width: 650px;">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Record Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="payment-details">
                    <h6 id="studentNameDisplay"></h6>
                    <div class="payment-detail-row">
                        <span>Class:</span>
                        <span id="courseDisplay"></span>
                    </div>
                    <div class="payment-detail-row">
                        <span>Fee Amount:</span>
                        <span id="feeAmountDisplay"></span>
                    </div>
                    <div class="payment-detail-row">
                        <span>Late Fee:</span>
                        <span id="lateFeeDisplay"></span>
                    </div>
                    <div class="payment-detail-row">
                        <span>Discount:</span>
                        <span id="discountDisplay"></span>
                    </div>
                    <div class="payment-detail-row">
                        <span>Payable Amount:</span>
                        <strong id="payableAmountDisplay"></strong>
                    </div>
                </div>

                <div class="payment-amount-display" id="finalAmountDisplay"></div>

                <div class="mb-3">
                    <label class="form-label">Payment Date</label>
                    <input type="date" class="form-control" id="paymentDate" value="{{ date('Y-m-d') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Transaction ID (Optional)</label>
                    <input type="text" class="form-control" id="transactionId" placeholder="TXN1234567890">
                    <small class="text-muted">Enter transaction ID if available, or leave blank for automatic
                        generation.</small>
                    <input type="hidden" id="feeReferenceId" value="">
                </div>
                <div class="mb-3">

                    <label class="form-label mb-3">Select Payment Method</label>
                    <div class="payment-method-options">
                        <div class="payment-method-btn" data-method="cash" onclick="selectPaymentMethod('cash')">
                            <i class="bi bi-cash payment-method-icon"></i>
                            <span>Cash</span>
                        </div>
                        <div class="payment-method-btn" data-method="bank" onclick="selectPaymentMethod('bank')">
                            <i class="bi bi-credit-card payment-method-icon"></i>
                            <span>Bank Transfer</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmPaymentBtn" onclick="confirmPayment()"
                        disabled>
                        <i class="bi bi-check-circle me-2"></i>Confirm Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
flatpickr("#dateRangeFilter", {
    mode: "range",
    dateFormat: "Y-m-d",
    placeholder: "Select date range"
});

// Global variables
let activeFilters = {};
let selectedPaymentMethod = null;
let selectedEMIPlan = null;
let currentStudentHash = null;
let currentInstallmentId = null;
let currentReceiptData = null;
let currentPaymentIndex = null;
const studentInstallments = @json($paginator -> items());

// ==================== FILTERING FUNCTIONS ====================
function showLoader() {
    const loader = document.getElementById('pageLoader');
    if (loader) loader.style.display = 'flex';
}

// 🔥 Department handling (same as before)
const deptInput = document.querySelector('input[list="departmentsList"]');
const hiddenInput = document.getElementById('department_id_hidden');

deptInput.addEventListener('change', function() {
    const inputValue = this.value.trim();
    const options = document.querySelectorAll('#departmentsList option');

    let selectedId = '';

    options.forEach(option => {
        if (option.value === inputValue) {
            selectedId = option.dataset.id;
        }
    });

    hiddenInput.value = selectedId;
    showLoader();
    this.form.submit();
});

// 🔥 GLOBAL AUTO FILTER WITH TRIM SUPPORT
document.querySelectorAll('.auto-submit').forEach(input => {

    input.addEventListener('input', function() {
        clearTimeout(this.delayTimer);

        this.delayTimer = setTimeout(() => {

            let value = this.value;

            // ✅ Trim check
            if (typeof value === 'string') {
                value = value.trim();
            }

            // ✅ If empty after trim → reset + submit
            if (value === '') {
                this.value = '';
            }
            showLoader();
            this.form.submit();

        }, 1000);
    });

    input.addEventListener('change', function() {
        this.form.submit();
    });
});

// ==================== PAYMENT RECORDING (NEW) ====================
window.newrecordPayment = function(studentHash, installmentId) {

    // ✅ FIRST assign
    currentStudentHash = studentHash;
    currentInstallmentId = installmentId;

    // ✅ THEN find
    const student = studentInstallments.find(s => s.student_hash_id === studentHash);

    if (!student || !student.current_installment) {
        alert('No current installment found for this student.');
        return;
    }

    openPaymentModal(student);
};

function openPaymentModal(student) {
    const inst = student.current_installment;

    // Original fee amount (before discount)
    const originalFee = parseFloat(inst.fee_amount || 0);
    const lateFee = parseFloat(inst.late_fee || 0);
    const discount = parseFloat(inst.discount || 0);
    const payableAmount = originalFee + lateFee - discount;

    document.getElementById('studentNameDisplay').textContent = student.student_name;
    document.getElementById('courseDisplay').textContent = `${student.course} - ${student.department}`;

    // Show original fee amount
    document.getElementById('feeAmountDisplay').textContent = '₹' + originalFee.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    });

    document.getElementById('lateFeeDisplay').textContent = lateFee > 0 ?
        '+₹' + lateFee.toLocaleString('en-IN', {
            minimumFractionDigits: 2
        }) : '₹0.00';
    document.getElementById('discountDisplay').textContent = discount > 0 ?
        '-₹' + discount.toLocaleString('en-IN', {
            minimumFractionDigits: 2
        }) : '₹0.00';
    document.getElementById('payableAmountDisplay').textContent = '₹' + payableAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    });
    document.getElementById('finalAmountDisplay').textContent = '₹' + payableAmount.toLocaleString('en-IN', {
        minimumFractionDigits: 2
    });

    // Clear transaction ID field - let backend generate if empty
    document.getElementById('transactionId').value = '';

    // Reset UI
    selectedPaymentMethod = null;
    document.querySelectorAll('.payment-method-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('confirmPaymentBtn').disabled = true;

    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();
}

function selectPaymentMethod(method) {
    selectedPaymentMethod = method;
    document.querySelectorAll('.payment-method-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.method === method) btn.classList.add('active');
    });
    document.getElementById('confirmPaymentBtn').disabled = false;
}

function selectEMIPlan(installments) {
    selectedEMIPlan = installments;
    document.querySelectorAll('.emi-option').forEach(option => {
        option.classList.remove('active');
        if (parseInt(option.dataset.installments) === installments) {
            option.classList.add('active');
        }
    });
    document.getElementById('confirmPaymentBtn').disabled = false;
}

function confirmPayment() {
    if (!selectedPaymentMethod) {
        alert('Please select a payment method');
        return;
    }

    // Get the student data using the stored student hash
    const student = studentInstallments.find(s => s.student_hash_id === currentStudentHash);
    if (!student || !student.current_installment) {
        alert('Student or installment data not found');
        return;
    }

    const installment = student.current_installment;
    const paymentDate = document.getElementById('paymentDate').value;

    // Get transaction ID - send as is, backend will generate if empty
    const transactionId = document.getElementById('transactionId').value.trim();

    // Calculate payable amount
    const feeAmount = parseFloat(installment.fee_amount || 0);
    const lateFee = parseFloat(installment.late_fee || 0);
    const discount = parseFloat(installment.discount || 0);
    const payableAmount = feeAmount + lateFee - discount;

    const paymentRecord = {
        type: 'Course',
        id: currentInstallmentId,
        payableAmount: payableAmount,
        paymentMethod: selectedPaymentMethod,
        student_hash_id: currentStudentHash,
        student_name: student.student_name,
        course: student.course,
        fee_amount: feeAmount,
        late_fee: lateFee,
        discount: discount,
        payment_date: paymentDate,
        transaction_id: transactionId // Send as is, backend handles empty
    };

    // Log the data being sent for debugging
    console.log('Sending payment data:', paymentRecord);

    // Make AJAX call to process payment
    $.ajax({
        url: "{{ route('admin.payments.confirm') }}",
        type: 'POST',
        data: paymentRecord,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            $('#confirmPaymentBtn').prop('disabled', true).text('Processing...');
        },
        success: function(response) {
            console.log('Payment success:', response);
            if (response.status === true) {
                updatePaymentStatusInTable(currentStudentHash);

                // Show success message with reference ID from response
                showSweetSuccess({
                    student_name: student.student_name,
                    payable_amount: payableAmount,
                    reference_id: response.reference_id || transactionId || 'Generated'
                });

                const modal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                modal.hide();

                setTimeout(() => {
                    location.reload();
                }, 3000);
            } else {
                alert(response.message || 'Payment failed');
            }
        },
        error: function(xhr) {
            console.error('Payment error:', xhr.responseText);
            if (xhr.responseJSON && xhr.responseJSON.message) {
                alert('Error: ' + xhr.responseJSON.message);
            } else {
                alert('Server error occurred. Please check console for details.');
            }
        },
        complete: function() {
            $('#confirmPaymentBtn').prop('disabled', false).text('Confirm Payment');
        }
    });
}

function updatePaymentStatusInTable(studentHash) {
    const row = document.querySelector(`.payment-row[data-student-hash="${studentHash}"]`);
    if (row) {
        // Update status badge
        const statusColumn = row.querySelector('.status-column');
        if (statusColumn) {
            statusColumn.innerHTML =
                '<span class="status-badge status-paid"><i class="bi bi-check-circle"></i> Paid</span>';
        }

        // Update action buttons (remove payment button, add receipt button)
        const actionButtons = row.querySelector('.action-buttons');
        if (actionButtons) {
            // Remove payment button if exists
            const paymentBtn = actionButtons.querySelector('[onclick*="newrecordPayment"]');
            if (paymentBtn) paymentBtn.remove();

            // Add receipt button if not exists
            if (!actionButtons.querySelector('[onclick*="viewReceipt"]')) {
                const receiptBtn = document.createElement('button');
                receiptBtn.className = 'action-btn';
                receiptBtn.title = 'View Receipt';
                receiptBtn.innerHTML = '<i class="bi bi-receipt"></i>';
                receiptBtn.setAttribute('onclick', `viewReceipt('${studentHash}', ${currentInstallmentId})`);
                actionButtons.appendChild(receiptBtn);
            }
        }
    }
}

function showSweetSuccess(paymentData) {
    Swal.fire({
        icon: 'success',
        title: 'Payment Successful 🎉',
        html: `
            <div style="text-align:left; font-size:14px;">
                <p><b>Student:</b> ${paymentData.student_name}</p>
                <p><b>Amount:</b> ₹${paymentData.payable_amount.toFixed(2)}</p>
                <p><b>Reference ID:</b> <code>${paymentData.reference_id}</code></p>
            </div>
        `,
        confirmButtonText: 'OK',
        confirmButtonColor: '#10b981',
        timer: 4000,
        timerProgressBar: true
    });
}

function viewAllInstallments(studentHash) {
    // Open in new tab, keep main page accessible
    const url = `/student-installments/${encodeURIComponent(studentHash)}`;
    window.open(url, '_blank');
}

document.addEventListener('DOMContentLoaded', function() {
    // Handle pagination links
    document.querySelectorAll('.pagination-btn').forEach(link => {
        if (link.tagName === 'A') {
            link.addEventListener('click', function(e) {
                // Let the link navigate normally, but show loader
                setTimeout(() => {
                    showLoader();
                }, 10);
            });
        }
    });
});
// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    // Initialize flatpickr date range picker
    flatpickr("#dateRangeFilter", {
        mode: "range",
        dateFormat: "Y-m-d",
        placeholder: "Select date range"
    });
});
</script>
@endsection