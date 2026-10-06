@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
    <title>Academic Fee Payments</title>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <!-- Flatpickr Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-light: #e6eeff;
            --primary-lighter: #f0f4ff;
            --success: #10b981;
            --success-light: #d1fae5;
            --info: #3b82f6;
            --warning: #f59e0b;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --purple: #8b5cf6;
            --purple-light: #ede9fe;
            --teal: #0d9488;
            --teal-light: #ccfbf1;
            --dark: #1f2937;
            --light: #f9fafb;
            --border: #e5e7eb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
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
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle at 100% 0%, rgba(255,255,255,0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        
        .header-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .header-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 1.5rem;
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
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.2s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
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
            background: var(--primary-lighter);
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
        
        .stat-icon-purple {
            background: var(--purple-light);
            color: var(--purple);
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--border);
        }
        
        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
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
        
        .filter-select, .filter-input {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.875rem;
            background: white;
            transition: all 0.2s ease;
        }
        
        .filter-select:focus, .filter-input:focus {
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
            border-top: 1px solid var(--border);
        }
        
        .filter-btn {
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-apply {
            background: var(--primary);
            color: white;
            border: 1px solid var(--primary);
        }
        
        .btn-apply:hover {
            background: #3a56d4;
            border-color: #3a56d4;
            transform: translateY(-1px);
        }
        
        .btn-reset {
            background: white;
            color: var(--dark);
            border: 1px solid var(--border);
        }
        
        .btn-reset:hover {
            background: var(--gray-100);
            border-color: var(--gray-300);
        }
        
        /* Table Design */
        .academic-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--border);
        }
        
        .table-header {
            background: linear-gradient(135deg, var(--dark) 0%, var(--gray-600) 100%);
            color: white;
        }
        
        .table-header th {
            font-weight: 500;
            padding: 1rem 1.25rem;
            border: none;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        
        .table-body tr {
            border-bottom: 1px solid var(--border);
            transition: all 0.2s ease;
        }
        
        .table-body tr:hover {
            background-color: var(--primary-light);
        }
        
        .table-body td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border: none;
        }
        
        /* Academic Info Column */
        .academic-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            min-width: 220px;
        }
        
        .department-course {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .department {
            font-size: 0.75rem;
            color: var(--gray-500);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            background: var(--primary-lighter);
            color: var(--primary);
        }
        
        .year-tag {
            background: var(--success-light);
            color: var(--success);
        }
        
        .semester-tag {
            background: var(--purple-light);
            color: var(--purple);
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
        
        .mode-badges {
            display: flex;
            gap: 0.375rem;
        }
        
        .mode-badge {
            padding: 0.2rem 0.5rem;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 500;
        }
        
        .full-time-badge {
            background: var(--success-light);
            color: var(--success);
        }
        
        .offline-badge {
            background: var(--info-light);
            color: var(--info);
        }
        
        /* Student Info */
        .student-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
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
            background: var(--primary-lighter);
            color: var(--primary);
            width: fit-content;
        }
        
        /* Amount Columns */
        .amount-column {
            text-align: right;
            min-width: 110px;
        }
        
        .amount-display {
            font-weight: 700;
            font-size: 1rem;
            color: var(--dark);
        }
        
        .amount-label {
            font-size: 0.75rem;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
            font-weight: 600;
        }
        
        /* Fee Amount Column */
        .fee-amount {
            color: var(--primary);
        }
        
        /* Late Fee Column */
        .late-fee-container {
            text-align: right;
        }
        
        .late-fee-amount {
            font-weight: 600;
            color: var(--danger);
        }
        
        .late-fee-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.2rem 0.5rem;
            background: var(--danger-light);
            color: var(--danger);
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 500;
            margin-top: 0.25rem;
        }
        
        .no-late-fee {
            color: var(--gray-400);
            font-style: italic;
            font-size: 0.85rem;
        }
        
        /* Discount Column */
        .discount-container {
            text-align: right;
        }
        
        .discount-amount {
            font-weight: 600;
            color: var(--success);
        }
        
        .discount-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.2rem 0.5rem;
            background: var(--success-light);
            color: var(--success);
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 500;
            margin-top: 0.25rem;
        }
        
        .no-discount {
            color: var(--gray-400);
            font-style: italic;
            font-size: 0.85rem;
        }
        
        /* Payable Amount Column */
        .payable-amount {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--dark);
            background: var(--primary-lighter);
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            display: inline-block;
            border: 2px solid var(--primary);
        }
        
        /* Dates Column */
        .date-cell {
            min-width: 160px;
        }
        
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
            letter-spacing: 0.5px;
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
        
        .date-null {
            color: var(--gray-400);
            font-style: italic;
            background: transparent;
            border: 1px dashed var(--gray-300);
        }
        
        /* Status Column */
        .status-column {
            min-width: 100px;
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
        
        /* Updated At */
        .updated-info {
            font-size: 0.75rem;
            color: var(--gray-500);
            display: flex;
            align-items: center;
            gap: 0.375rem;
            min-width: 120px;
        }
        
        /* Actions Column */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }
        
        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: white;
            color: var(--gray-500);
            transition: all 0.2s ease;
        }
        
        .action-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-1px);
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
        }
        
        .pagination-btn {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
            color: var(--dark);
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .pagination-btn:hover:not(:disabled) {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--gray-500);
        }
        
        .empty-icon {
            font-size: 3rem;
            color: var(--gray-300);
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        /* Summary Row */
        .summary-row {
            background: var(--gray-100);
            font-weight: 600;
        }
        
        .summary-cell {
            font-weight: 700;
            color: var(--dark);
            text-align: right;
        }
        
        .summary-total {
            background: var(--primary-lighter);
            color: var(--primary);
            font-size: 1.1rem;
        }
        
        @media (max-width: 1200px) {
            .table-responsive {
                overflow-x: auto;
            }
            
            .academic-table {
                min-width: 1400px;
            }
        }
        
        @media (max-width: 768px) {
            .filter-row {
                grid-template-columns: 1fr;
            }
            
            .filter-buttons {
                flex-direction: column;
            }
            
            .filter-btn {
                width: 100%;
                justify-content: center;
            }
            
            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="header-title">
                        <i class="bi bi-mortarboard-fill me-2"></i>Miscellaneous Fee Payments
                    </h1>
                    <p class="header-subtitle">Track and manage all Miscellaneous fee payments across departments</p>
                </div>
                <button class="btn btn-light d-flex align-items-center gap-2">
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
                        <div class="stat-value">{{ count($GetFeeStructure) }}</div>
                        <div class="stat-label">Total Payments</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon stat-icon-success">
                        <i class="bi bi-currency-rupee"></i>
                    </div>
                    <div class="stat-content">
                        @php
                            $totalPayable = 0;
                            $totalLateFee = 0;
                            $totalDiscount = 0;
                            $totalFeeAmount = 0;
                            foreach($GetFeeStructure as $payment) {
                                $payable = floatval($payment['pay_fee_amount']);
                                $lateFee = floatval($payment['late_fee_amount']);
                                $discount = floatval($payment['discount_amount']);
                                $feeAmount = floatval($payment['fee_amount']);
                                $totalPayable += $payable;
                                $totalLateFee += $lateFee;
                                $totalDiscount += $discount;
                                $totalFeeAmount += $feeAmount;
                            }
                        @endphp
                        <div class="stat-value">₹{{ number_format($totalPayable, 2) }}</div>
                        <div class="stat-label">Total Payable</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon stat-icon-danger">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">₹{{ number_format($totalLateFee, 2) }}</div>
                        <div class="stat-label">Late Fees</div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon stat-icon-purple">
                        <i class="bi bi-percent"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">₹{{ number_format($totalDiscount, 2) }}</div>
                        <div class="stat-label">Discounts</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Department</label>
                    <select class="filter-select" id="departmentFilter">
                        <option value="">All Departments</option>
                        @php
                            $departments = array_unique(array_column($GetFeeStructure, 'department'));
                            foreach($departments as $dept) {
                                echo "<option value=\"{$dept}\">{$dept}</option>";
                            }
                        @endphp
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Course</label>
                    <select class="filter-select" id="courseFilter">
                        <option value="">All Courses</option>
                        @php
                            $courses = array_unique(array_column($GetFeeStructure, 'course'));
                            foreach($courses as $course) {
                                echo "<option value=\"{$course}\">{$course}</option>";
                            }
                        @endphp
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Batch</label>
                    <select class="filter-select" id="batchFilter">
                        <option value="">All Batches</option>
                        @php
                            $batches = array_unique(array_column($GetFeeStructure, 'batch'));
                            foreach($batches as $batch) {
                                echo "<option value=\"{$batch}\">{$batch}</option>";
                            }
                        @endphp
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Payment Status</label>
                    <select class="filter-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Student Name</label>
                    <input type="text" class="filter-input" id="studentNameFilter" placeholder="Search by student name">
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Academic Year</label>
                    <select class="filter-select" id="academicYearFilter">
                        <option value="">All Years</option>
                        @php
                            $years = array_unique(array_column($GetFeeStructure, 'academic_year'));
                            foreach($years as $year) {
                                echo "<option value=\"{$year}\">{$year}</option>";
                            }
                        @endphp
                    </select>
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Date Range</label>
                    <input type="text" class="filter-input date-range-input" id="dateRangeFilter" placeholder="Select date range">
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">Mode Type</label>
                    <select class="filter-select" id="modeTypeFilter">
                        <option value="">All Modes</option>
                        <option value="full_time">Full Time</option>
                        <option value="part_time">Part Time</option>
                    </select>
                </div>
            </div>
            
            <div class="filter-buttons">
                <button class="btn btn-reset filter-btn" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise"></i> Reset All
                </button>
                <button class="btn btn-apply filter-btn" onclick="applyFilters()">
                    <i class="bi bi-funnel"></i> Apply Filters
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="academic-table">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-header">
                        <tr>                           
                            <th>STUDENT</th>
                            <th>ACADEMIC DETAILS</th>
                            <th>MODE</th>
                            <th class="text-right">FEE AMOUNT</th>
                            <th class="text-right">LATE FEE</th>
                            <th class="text-right">DISCOUNT</th>
                            <th class="text-right">PAYABLE</th>
                            <th>DATES</th>
                            <th>STATUS</th>
                            <th>UPDATED</th>
                            <th class="text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="table-body" id="paymentsTableBody">
                        @forelse($GetFeeStructure as $index => $payment)
                            @php
                                $lateFee = floatval($payment['late_fee_amount']);
                                $discount = floatval($payment['discount_amount']);
                                $payableAmount = floatval($payment['pay_fee_amount']);
                                $feeAmount = floatval($payment['fee_amount']);
                                $hasLateFee = $lateFee > 0;
                                $hasDiscount = $discount > 0;
                            @endphp
                            
                            <tr class="payment-row"
                                data-department="{{ strtolower($payment['department']) }}"
                                data-course="{{ strtolower($payment['course']) }}"
                                data-batch="{{ strtolower($payment['batch']) }}"
                                data-academic-year="{{ $payment['academic_year'] }}"
                                data-student="{{ strtolower($payment['student_name']) }}"
                                data-status="{{ $payment['payment_status'] }}"
                                data-mode-type="{{ $payment['mode_type'] }}"
                                data-pay-date="{{ $payment['pay_date'] }}"
                                data-late-fee="{{ $lateFee }}"
                                data-discount="{{ $discount }}">
                                
                                <!-- Student Column -->
                                <td>
                                    <div class="student-info">
                                        <div class="student-name">{{ $payment['student_name'] }}</div>
                                        <div class="duration-badge">
                                            <i class="bi bi-calendar-week"></i>
                                            {{ $payment['fee_duration_type'] }}
                                        </div>
                                        <div class="duration-badge">
                                            <i class="bi bi-calendar-week"></i>
                                            {{$payment['student_reg']}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="academic-info">
                                        <div class="department-course">
                                            <div class="department">{{ $payment['department'] }}</div>
                                            <div class="course-name">{{ $payment['course'] }}</div>
                                        </div>
                                        <div class="academic-meta">
                                            <span class="meta-tag batch-tag" title="Batch">
                                                <i class="bi bi-calendar-week"></i>
                                                {{ $payment['batch'] }}
                                            </span>
                                            <span class="meta-tag year-tag" title="Academic Year">
                                                <i class="bi bi-calendar"></i>
                                                {{ $payment['academic_year'] }}
                                            </span>
                                            <span class="meta-tag semester-tag" title="Semester">
                                                <i class="bi bi-journal"></i>
                                                {{ $payment['semester'] }}
                                            </span>
                                            <span class="meta-tag section-tag" title="Section">
                                                <i class="bi bi-people"></i>
                                                {{ $payment['Section'] }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Mode Type Column -->
                                <td>
                                    <div class="mode-info">
                                        <div class="mode-type">
                                            @php
                                                $modeType = $payment['mode_type'] == 'full_time' ? 'Full Time' : 'Part Time';
                                                echo $modeType;
                                            @endphp
                                        </div>
                                        <div class="mode-badges">
                                            <span class="mode-badge full-time-badge">
                                                {{ $payment['mode_type'] == 'full_time' ? 'Full Time' : 'Part Time' }}
                                            </span>
                                            <span class="mode-badge offline-badge">
                                                {{ $payment['mode_of_course'] == 'offline' ? 'Offline' : 'Online' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                
                                
                                <!-- Fee Amount Column -->
                                <td class="amount-column">
                                    <div class="amount-label" style="font-size:16px;font-weight:bold;color:#000;">{{ $payment['id_type'] }}</div>
                                    <div class="amount-display fee-amount">
                                        ₹{{ number_format($feeAmount, 2) }}
                                    </div>
                                </td>
                                
                                <!-- Late Fee Column -->
                                <td class="amount-column">
                                    <div class="amount-label">Late Fee</div>
                                    <div class="late-fee-container">
                                        @if($hasLateFee)
                                            <div class="late-fee-amount">
                                                +₹{{ number_format($lateFee, 2) }}
                                            </div>
                                            <div class="late-fee-badge">
                                                <i class="bi bi-clock"></i>
                                                Applied
                                            </div>
                                        @else
                                            <div class="no-late-fee">
                                                No late fee
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Discount Column -->
                                <td class="amount-column">
                                    <div class="amount-label">Discount</div>
                                    <div class="discount-container">
                                        @if($hasDiscount)
                                            <div class="discount-amount">
                                                -₹{{ number_format($discount, 2) }}
                                            </div>
                                            <div class="discount-badge">
                                                <i class="bi bi-percent"></i>
                                                Applied
                                            </div>
                                        @else
                                            <div class="no-discount">
                                                No discount
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- Payable Amount Column -->
                                <td class="amount-column">
                                    <div class="amount-label">Payable</div>
                                    <div class="payable-amount">
                                        ₹{{ number_format($payableAmount, 2) }}
                                    </div>
                                </td>
                                
                                <!-- Dates Column -->
                                <td class="date-cell">
                                    <div class="date-info">
                                        <div class="date-group">
                                            <div class="date-label">Pay Date</div>
                                            <div class="date-value">
                                                {{ \Carbon\Carbon::parse($payment['pay_date'])->format('d M Y') }}
                                            </div>
                                        </div>
                                        <div class="date-group">
                                            <div class="date-label">Due Date</div>
                                            <div class="date-value">
                                                {{ \Carbon\Carbon::parse($payment['due_date'])->format('d M Y') }}
                                            </div>
                                        </div>
                                        <div class="date-group">
                                            <div class="date-label">Start Date</div>
                                            <div class="date-value {{ $payment['start_date'] ? '' : 'date-null' }}">
                                                {{ $payment['start_date'] ? \Carbon\Carbon::parse($payment['start_date'])->format('d M Y') : 'Not set' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Status Column -->
                                <td class="status-column">
                                    @if($payment['payment_status'] == 'paid')
                                        <span class="status-badge status-paid">
                                            <i class="bi bi-check-circle"></i>
                                            Paid
                                        </span>
                                    @else
                                        @php
                                            $dueDate = \Carbon\Carbon::parse($payment['due_date']);
                                            $today = \Carbon\Carbon::today();
                                            $status = $dueDate->lt($today) ? 'status-overdue' : 'status-pending';
                                            $statusText = $dueDate->lt($today) ? 'Overdue' : 'Pending';
                                        @endphp
                                        <span class="status-badge {{ $status }}">
                                            <i class="bi bi-clock"></i>
                                            {{ $statusText }}
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- Updated At Column -->
                                <td>
                                    <div class="updated-info">
                                        <i class="bi bi-clock-history"></i>
                                        {{ \Carbon\Carbon::parse($payment['updated_at'])->diffForHumans() }}
                                    </div>
                                </td>
                                
                                <!-- Actions Column -->
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn" title="View Details" onclick="viewPayment({{ $index }})">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="action-btn" title="Download Receipt" onclick="downloadReceipt({{ $index }})">
                                            <i class="bi bi-download"></i>
                                        </button>
                                        @if($payment['payment_status'] != 'paid')
                                            <button class="action-btn" title="Mark as Paid" onclick="markAsPaid({{ $index }})">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="empty-state">
                                    <div class="empty-icon">
                                        <i class="bi bi-mortarboard"></i>
                                    </div>
                                    <h4 class="text-muted mb-2">No academic fee payments found</h4>
                                    <p class="text-muted">Start by recording a new academic fee payment</p>
                                    <button class="btn btn-primary mt-3 d-none">
                                        <i class="bi bi-plus-circle me-2"></i>Add Payment
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                        
                        <!-- Summary Row -->
                        @if(count($GetFeeStructure) > 0)
                            @php
                                $totalFeeAmount = 0;
                                $totalLateFee = 0;
                                $totalDiscount = 0;
                                $totalPayable = 0;
                                
                                foreach($GetFeeStructure as $payment) {
                                    $totalFeeAmount += floatval($payment['fee_amount']);
                                    $totalLateFee += floatval($payment['late_fee_amount']);
                                    $totalDiscount += floatval($payment['discount_amount']);
                                    $totalPayable += floatval($payment['pay_fee_amount']);
                                }
                            @endphp
                            <tr class="summary-row">
                                <td colspan="3" class="summary-cell">Totals:</td>
                                <td class="summary-cell">₹{{ number_format($totalFeeAmount, 2) }}</td>
                                <td class="summary-cell">₹{{ number_format($totalLateFee, 2) }}</td>
                                <td class="summary-cell">₹{{ number_format($totalDiscount, 2) }}</td>
                                <td class="summary-cell summary-total">₹{{ number_format($totalPayable, 2) }}</td>
                                <td colspan="4"></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <!-- Table Footer -->
            @if(count($GetFeeStructure) > 0)
                <div class="table-footer">
                    <div class="records-count">
                        Showing <span id="visibleCount">{{ count($GetFeeStructure) }}</span> of {{ count($GetFeeStructure) }} payments
                    </div>
                    <div class="pagination-buttons">
                        <button class="pagination-btn" disabled>
                            <i class="bi bi-chevron-left"></i> Previous
                        </button>
                        <button class="pagination-btn">
                            Next <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Flatpickr Datepicker -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize date range picker
        flatpickr("#dateRangeFilter", {
            mode: "range",
            dateFormat: "Y-m-d",
            placeholder: "Select date range"
        });

        let activeFilters = {};

        function applyFilters() {
            const department = document.getElementById('departmentFilter').value;
            const course = document.getElementById('courseFilter').value;
            const batch = document.getElementById('batchFilter').value;
            const academicYear = document.getElementById('academicYearFilter').value;
            const studentName = document.getElementById('studentNameFilter').value;
            const status = document.getElementById('statusFilter').value;
            const modeType = document.getElementById('modeTypeFilter').value;
            const dateRange = document.getElementById('dateRangeFilter').value;
            
            // Store active filters
            activeFilters = {};
            if (department) activeFilters.department = department;
            if (course) activeFilters.course = course;
            if (batch) activeFilters.batch = batch;
            if (academicYear) activeFilters.academicYear = academicYear;
            if (studentName) activeFilters.studentName = studentName;
            if (status) activeFilters.status = status;
            if (modeType) activeFilters.modeType = modeType;
            if (dateRange) activeFilters.dateRange = dateRange;
            
            filterPayments();
        }
        
        function filterPayments() {
            const rows = document.querySelectorAll('.payment-row');
            let visibleCount = 0;
            
            rows.forEach(row => {
                let show = true;
                
                // Department filter
                if (activeFilters.department && row.dataset.department !== activeFilters.department.toLowerCase()) {
                    show = false;
                }
                
                // Course filter
                if (activeFilters.course && row.dataset.course !== activeFilters.course.toLowerCase()) {
                    show = false;
                }
                
                // Batch filter
                if (activeFilters.batch && row.dataset.batch !== activeFilters.batch.toLowerCase()) {
                    show = false;
                }
                
                // Academic year filter
                if (activeFilters.academicYear && row.dataset.academicYear !== activeFilters.academicYear) {
                    show = false;
                }
                
                // Student name filter
                if (activeFilters.studentName) {
                    const studentName = row.dataset.student;
                    if (!studentName.includes(activeFilters.studentName.toLowerCase())) {
                        show = false;
                    }
                }
                
                // Status filter
                if (activeFilters.status && row.dataset.status !== activeFilters.status) {
                    show = false;
                }
                
                // Mode type filter
                if (activeFilters.modeType && row.dataset.modeType !== activeFilters.modeType) {
                    show = false;
                }
                
                // Date range filter
                if (activeFilters.dateRange) {
                    const [startDate, endDate] = activeFilters.dateRange.split(' to ');
                    const payDate = row.dataset.payDate;
                    if (payDate < startDate || (endDate && payDate > endDate)) {
                        show = false;
                    }
                }
                
                // Show/hide row
                row.style.display = show ? '' : 'none';
                if (show) visibleCount++;
            });
            
            // Update visible count
            document.getElementById('visibleCount').textContent = visibleCount;
            
            // Show empty state if no rows visible
            if (visibleCount === 0 && rows.length > 0) {
                const tbody = document.getElementById('paymentsTableBody');
                const emptyStateRow = tbody.querySelector('.empty-state');
                if (!emptyStateRow) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="11" class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-search"></i>
                                </div>
                                <h4 class="text-muted mb-2">No matching payments found</h4>
                                <p class="text-muted">Try adjusting your filters</p>
                                <button class="btn btn-outline-primary mt-3" onclick="resetFilters()">
                                    <i class="bi bi-arrow-clockwise me-2"></i>Reset Filters
                                </button>
                            </td>
                        </tr>
                    `;
                }
            } else if (visibleCount > 0) {
                // Remove empty state if present
                const emptyStateRow = document.querySelector('.empty-state');
                if (emptyStateRow && emptyStateRow.closest('tr')) {
                    emptyStateRow.closest('tr').remove();
                }
            }
            
            // Update summary row if present
            updateSummaryRow();
        }
        
        function updateSummaryRow() {
            const visibleRows = document.querySelectorAll('.payment-row:not([style*="display: none"])');
            
            let totalFeeAmount = 0;
            let totalLateFee = 0;
            let totalDiscount = 0;
            let totalPayable = 0;
            
            visibleRows.forEach(row => {
                const feeAmount = parseFloat(row.cells[3].textContent.replace('₹', '').replace(',', ''));
                const lateFee = parseFloat(row.cells[4].querySelector('.late-fee-amount, .no-late-fee')?.textContent?.replace('+₹', '').replace(',', '')) || 0;
                const discount = parseFloat(row.cells[5].querySelector('.discount-amount, .no-discount')?.textContent?.replace('-₹', '').replace(',', '')) || 0;
                const payable = parseFloat(row.cells[6].textContent.replace('₹', '').replace(',', ''));
                
                totalFeeAmount += isNaN(feeAmount) ? 0 : feeAmount;
                totalLateFee += isNaN(lateFee) ? 0 : lateFee;
                totalDiscount += isNaN(discount) ? 0 : discount;
                totalPayable += isNaN(payable) ? 0 : payable;
            });
            
            // Update summary row if it exists
            const summaryRow = document.querySelector('.summary-row');
            if (summaryRow) {
                summaryRow.cells[3].textContent = '₹' + totalFeeAmount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                summaryRow.cells[4].textContent = '₹' + totalLateFee.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                summaryRow.cells[5].textContent = '₹' + totalDiscount.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                summaryRow.cells[6].textContent = '₹' + totalPayable.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            }
        }
        
        function resetFilters() {
            activeFilters = {};
            document.getElementById('departmentFilter').value = '';
            document.getElementById('courseFilter').value = '';
            document.getElementById('batchFilter').value = '';
            document.getElementById('academicYearFilter').value = '';
            document.getElementById('studentNameFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('modeTypeFilter').value = '';
            document.getElementById('dateRangeFilter').value = '';
            
            // Show all rows
            const rows = document.querySelectorAll('.payment-row');
            rows.forEach(row => row.style.display = '');
            document.getElementById('visibleCount').textContent = rows.length;
            
            // Remove empty state if present
            const emptyStateRow = document.querySelector('.empty-state');
            if (emptyStateRow && emptyStateRow.closest('tr') && rows.length > 0) {
                emptyStateRow.closest('tr').remove();
            }
            
            // Update summary row
            updateSummaryRow();
        }
        
        // Action functions
        function viewPayment(index) {
            const payment = @json($GetFeeStructure)[index];
            alert(`Viewing payment details for ${payment.student_name} - ${payment.course}`);
            // Implement actual view functionality
        }
        
        function downloadReceipt(index) {
            const payment = @json($GetFeeStructure)[index];
            alert(`Downloading receipt for ${payment.student_name}`);
            // Implement download functionality
        }
        
        function markAsPaid(index) {
            const payment = @json($GetFeeStructure)[index];
            if (confirm(`Mark payment as paid for ${payment.student_name}?`)) {
                // Implement mark as paid functionality
                alert(`Payment marked as paid for ${payment.student_name}`);
            }
        }
        
        // Add event listeners for filter inputs
        document.getElementById('studentNameFilter').addEventListener('input', applyFilters);
        
        // Add hover effects to table rows
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.payment-row');
            rows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
                });
                
                row.addEventListener('mouseleave', function() {
                    this.style.boxShadow = 'none';
                });
            });
            
            // Initialize summary row
            updateSummaryRow();
        });
    </script>
@endsection