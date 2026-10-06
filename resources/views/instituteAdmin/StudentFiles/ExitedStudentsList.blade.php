@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Exited Students</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
    --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
    --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
    --success-gradient: linear-gradient(135deg, #10b981, #059669);
}

/* Page Header */
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
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
    transform: rotate(25deg);
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
    position: relative;
    z-index: 1;
}

.page-title i {
    font-size: 32px;
    filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
}

.page-header .header-actions {
    display: flex;
    gap: 12px;
    position: relative;
    z-index: 1;
}

.btn-back {
    padding: 12px 24px;
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 10px;
    color: white;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    text-decoration: none;
}

.btn-back:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
}

/* Statistics Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 25px;
}

.stat-card {
    background: white;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
    transition: all 0.3s;
    border-left: 4px solid var(--primary-color);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.stat-card .stat-number {
    font-size: 28px;
    font-weight: 700;
    color: #1e293b;
}

.stat-card .stat-label {
    font-size: 14px;
    color: #64748b;
    margin-top: 4px;
}

.stat-card .stat-icon {
    float: right;
    font-size: 32px;
    opacity: 0.2;
}

.stat-card.stat-danger {
    border-left-color: #ef4444;
}

.stat-card.stat-warning {
    border-left-color: #f59e0b;
}

.stat-card.stat-success {
    border-left-color: #10b981;
}

.stat-card.stat-info {
    border-left-color: #3b82f6;
}

.stat-card.stat-purple {
    border-left-color: #8b5cf6;
}

/* Filter Container */
.filter-container {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    position: relative;
    overflow: hidden;
}

.filter-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--danger-gradient);
}

.filter-container h6 {
    color: var(--primary-color);
    font-weight: 700;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 15px;
}

.filter-form {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: flex-end;
}

.filter-group {
    flex: 1;
    min-width: 180px;
    position: relative;
}

.filter-group .filter-icon {
    position: absolute;
    top: 50%;
    left: 12px;
    transform: translateY(-50%);
    color: var(--primary-color);
    font-size: 14px;
    z-index: 2;
    pointer-events: none;
}

.filter-group input,
.filter-group select {
    width: 100%;
    padding: 10px 12px 10px 36px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s;
    background: #f8fafc;
}

.filter-group input:focus,
.filter-group select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    background: white;
}

.filter-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

.btn-filter {
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.btn-filter-primary {
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 4px 14px rgba(67, 97, 238, 0.2);
}

.btn-filter-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
}

.btn-filter-secondary {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.btn-filter-secondary:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
}

/* Table Styles */
.table-wrapper {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
}

.table-wrapper .table-responsive {
    overflow-x: auto;
}

.erp-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.erp-table thead {
    background: var(--primary-gradient);
}

.erp-table thead th {
    padding: 14px 16px;
    color: white;
    font-weight: 600;
    text-align: left;
    font-size: 13px;
    letter-spacing: 0.3px;
    white-space: nowrap;
    position: sticky;
    top: 0;
    z-index: 10;
}

.erp-table thead th.sortable {
    cursor: pointer;
    user-select: none;
    padding-right: 30px;
    position: relative;
}

.erp-table thead th.sortable:hover {
    background: rgba(255, 255, 255, 0.1);
}

.sort-icons {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    flex-direction: column;
    gap: 1px;
}

.sort-icon {
    color: rgba(255, 255, 255, 0.4);
    font-size: 10px;
    line-height: 1;
}

.sort-icon.active {
    color: white;
}

.erp-table tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.2s;
}

.erp-table tbody tr:hover {
    background: #f8fafc;
}

.erp-table tbody tr:last-child {
    border-bottom: none;
}

.erp-table tbody td {
    padding: 12px 16px;
    color: #334155;
    vertical-align: middle;
}

/* Status Badges */
.exit-type-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.exit-type-course_completion {
    background: #dcfce7;
    color: #166534;
}

.exit-type-mid_session {
    background: #fef3c7;
    color: #92400e;
}

.exit-type-cancellation {
    background: #fee2e2;
    color: #991b1b;
}

.dues-badge {
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.dues-cleared {
    background: #dcfce7;
    color: #166534;
}

.dues-pending {
    background: #fee2e2;
    color: #991b1b;
}

.no-due-badge {
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.no-due-yes {
    background: #dbeafe;
    color: #1e40af;
}

.no-due-no {
    background: #f1f5f9;
    color: #64748b;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #64748b;
}

.empty-state-icon {
    font-size: 64px;
    color: #cbd5e1;
    margin-bottom: 20px;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-top: 1px solid #e2e8f0;
    flex-wrap: wrap;
    gap: 10px;
}

.pagination-wrapper .info {
    color: #64748b;
    font-size: 14px;
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
        font-size: 22px;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .filter-group {
        min-width: 140px;
        flex: 1 1 100%;
    }

    .filter-actions {
        flex: 1 1 100%;
    }

    .btn-filter {
        flex: 1;
        justify-content: center;
    }

    .erp-table thead th,
    .erp-table tbody td {
        padding: 10px 12px;
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}

/* Student Name with registration */
.student-name {
    font-weight: 600;
    color: #1e293b;
}

.student-reg {
    font-size: 12px;
    color: #64748b;
    display: block;
}

/* Action buttons in table */
.action-btn {
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
}

.action-btn-view {
    background: #e0f2fe;
    color: #0369a1;
}

.action-btn-view:hover {
    background: #bae6fd;
    transform: translateY(-2px);
}

.action-btn-exit-info {
    background: #fef3c7;
    color: #92400e;
}

.action-btn-exit-info:hover {
    background: #fde68a;
    transform: translateY(-2px);
}

.action-btn-delete {
    background: #fee2e2;
    color: #991b1b;
}

.action-btn-delete:hover {
    background: #fecaca;
    transform: translateY(-2px);
}

/* Alert messages */
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
    background: #dcfce7;
    border: 1px solid #86efac;
    color: #166534;
}

.alert-danger {
    background: #fee2e2;
    border: 1px solid #fca5a5;
    color: #991b1b;
}

/* Loading spinner */
.loading-spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

/* Seat Summary Styles */
.card-header {
    border-bottom: none;
}

.card-header h6 {
    font-weight: 600;
}

.alert-info,
.alert-warning,
.alert-success,
.alert-secondary {
    border-radius: 10px;
    padding: 12px 16px;
}

.table-bordered th,
.table-bordered td {
    padding: 6px 12px;
    font-size: 13px;
    border-color: #e2e8f0;
}

.table-bordered th {
    background-color: #f8fafc;
    width: 45%;
    font-weight: 600;
}

/* Scrollable history */
.card-body {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
}

.card-body::-webkit-scrollbar {
    width: 6px;
}

.card-body::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.card-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.card-body::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-door-open-fill"></i>
            Exited
            <span style="font-size: 16px; font-weight: 400; opacity: 0.8;">
                ( total {{ $exitedStudents->total() }}
                )
            </span>
        </h1>
        <div class="header-actions">
            <a href="{{ route('students.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i>
                Back to Students
            </a>

        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-danger">
            <span class="stat-icon"><i class="bi bi-door-open"></i></span>
            <div class="stat-number">{{ $statistics['total_exits'] }}</div>
            <div class="stat-label">Total Exits</div>
        </div>
        <div class="stat-card stat-success">
            <span class="stat-icon"><i class="bi bi-check-circle"></i></span>
            <div class="stat-number">{{ $statistics['course_completion'] }}</div>
            <div class="stat-label">Course Completion</div>
        </div>
        <div class="stat-card stat-warning">
            <span class="stat-icon"><i class="bi bi-clock-history"></i></span>
            <div class="stat-number">{{ $statistics['mid_session'] }}</div>
            <div class="stat-label">Mid-Session</div>
        </div>
        <div class="stat-card stat-info">
            <span class="stat-icon"><i class="bi bi-x-circle"></i></span>
            <div class="stat-number">{{ $statistics['cancellation'] }}</div>
            <div class="stat-label">Cancellation</div>
        </div>
        <div class="stat-card stat-success d-none">
            <span class="stat-icon"><i class="bi bi-currency-dollar"></i></span>
            <div class="stat-number">{{ $statistics['dues_cleared'] }}</div>
            <div class="stat-label">Dues Cleared</div>
        </div>
        <div class="stat-card stat-purple  d-none">
            <span class="stat-icon"><i class="bi bi-file-check"></i></span>
            <div class="stat-number">{{ $statistics['no_due_generated'] }}</div>
            <div class="stat-label">No Due Generated</div>
        </div>
    </div>

    <!-- Messages -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Filters -->
    <div class="filter-container">
        <h6><i class="bi bi-funnel-fill"></i> Filter Exited Students</h6>
        <form method="GET" action="{{ url()->current() }}" class="filter-form">
            <div class="filter-group">
                <i class="bi bi-search filter-icon"></i>
                <input type="text" name="search" placeholder="Search by name, reg no, email..."
                    value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <i class="bi bi-qr-code-scan filter-icon"></i>
                <input type="text" name="registration_number" placeholder="Registration Number"
                    value="{{ request('registration_number') }}">
            </div>
            <div class="filter-group">
                <i class="bi bi-person filter-icon"></i>
                <input type="text" name="student_name" placeholder="Student Name" value="{{ request('student_name') }}">
            </div>
            <div class="filter-group">
                <i class="bi bi-tag filter-icon"></i>
                <select name="exit_type">
                    <option value="">All Exit Types</option>
                    <option value="course_completion"
                        {{ request('exit_type') == 'course_completion' ? 'selected' : '' }}>
                        Course Completion
                    </option>
                    <option value="mid_session" {{ request('exit_type') == 'mid_session' ? 'selected' : '' }}>
                        Mid-Session
                    </option>
                    <option value="cancellation" {{ request('exit_type') == 'cancellation' ? 'selected' : '' }}>
                        Cancellation
                    </option>
                </select>
            </div>
            <div class="filter-group">
                <i class="bi bi-calendar2-week filter-icon"></i>
                <select name="academic_year">
                    <option value="">All Academic Years</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <i class="bi bi-collection filter-icon"></i>
                <select name="batch">
                    <option value="">All Batches</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch }}" {{ request('batch') == $batch ? 'selected' : '' }}>
                            {{ $batch }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <i class="bi bi-calendar3 filter-icon"></i>
                <input type="date" name="exit_date_from" placeholder="From Date"
                    value="{{ request('exit_date_from') }}">
            </div>
            <div class="filter-group">
                <i class="bi bi-calendar3 filter-icon"></i>
                <input type="date" name="exit_date_to" placeholder="To Date" value="{{ request('exit_date_to') }}">
            </div>
            <div class="filter-group d-none">
                <i class="bi bi-currency-dollar filter-icon"></i>
                <select name="dues_cleared">
                    <option value="">All Dues Status</option>
                    <option value="yes" {{ request('dues_cleared') == 'yes' ? 'selected' : '' }}>
                        Dues Cleared
                    </option>
                    <option value="no" {{ request('dues_cleared') == 'no' ? 'selected' : '' }}>
                        Dues Pending
                    </option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter btn-filter-primary">
                    <i class="bi bi-funnel-fill"></i> Apply Filters
                </button>
                <a href="{{ url()->current() }}" class="btn-filter btn-filter-secondary">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
            </div>
            <input type="hidden" name="sort_by" value="{{ request('sort_by', 'exited_at') }}">
            <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">
        </form>
    </div>

    <!-- Table -->
    <div class="table-wrapper">
        <div class="table-responsive">
            <table class="erp-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="sortable" onclick="sortTable('student_name')">
                            Student
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'student_name' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'student_name' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('exit_type')">
                            Exit Type
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'exit_type' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'exit_type' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable" onclick="sortTable('exit_date')">
                            Exit Date
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'exit_date' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'exit_date' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th>Exit Reason</th>
                        <th class="sortable d-none" onclick="sortTable('dues_cleared')">
                            Dues
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'dues_cleared' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'dues_cleared' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable  d-none" onclick="sortTable('no_due_certificate_generated')">
                            No Due
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'no_due_certificate_generated' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'no_due_certificate_generated' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable " onclick="sortTable('exited_at')">
                            Exited By
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'exited_at' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'exited_at' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th class="sortable " onclick="sortTable('exited_at')">
                            Exited At
                            <div class="sort-icons">
                                <i
                                    class="sort-icon bi bi-caret-up-fill {{ request('sort_by') == 'exited_at' && request('sort_order') == 'asc' ? 'active' : '' }}"></i>
                                <i
                                    class="sort-icon bi bi-caret-down-fill {{ request('sort_by') == 'exited_at' && request('sort_order') == 'desc' ? 'active' : '' }}"></i>
                            </div>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exitedStudents as $index => $exit)
                    <tr>
                        <td>{{ $exitedStudents->firstItem() + $index }}</td>
                        <td>
                            <div class="student-name">
                                {{ $exit->student->first_name ?? 'N/A' }}
                                {{ $exit->student->middle_name ?? '' }}
                                {{ $exit->student->last_name ?? '' }}
                            </div>
                            <span class="student-reg">
                                <i class="bi bi-qr-code"></i>
                                {{ $exit->student->registration_number ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <span class="exit-type-badge exit-type-{{ $exit->exit_type }}">
                                {{ ucfirst(str_replace('_', ' ', $exit->exit_type)) }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($exit->exit_date)->format('d-m-Y') }}</td>
                        <td>
                            <span title="{{ $exit->exit_reason ?? 'No reason provided' }}">
                                {{ Str::limit($exit->exit_reason ?? 'N/A', 30) }}
                            </span>
                        </td>


                        <td>
                            <div style="font-size: 12px;">
                                <strong>{{ $exit->exitedBy->name ?? 'System' }}</strong>

                            </div>
                        </td>
                        <td>
                            <div style="font-size: 12px;">
                                <strong> {{ \Carbon\Carbon::parse($exit->exited_at)->format('d-m-Y H:i') }}</strong>

                            </div>
                        </td>
                        <td>
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                @if($exit->student)
                                <a href="{{ url('/student-details/' . $exit->student->student_hash_id) }}"
                                    class="action-btn action-btn-view" title="View Student">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                @endif
                                <button class="action-btn action-btn-exit-info"
                                    onclick="viewExitDetails('{{ $exit->id }}')" title="View Exit Details">
                                    <i class="bi bi-info-circle"></i> Details
                                </button>
                                @if(!$exit->no_due_certificate_generated)
                                <button class="action-btn action-btn-view d-none"
                                    onclick="generateNoDue('{{ $exit->id }}', '{{ $exit->student->student_hash_id ?? '' }}')"
                                    title="Generate No Due Certificate">
                                    <i class="bi bi-file-check"></i> No Due
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-door-open"></i>
                                </div>
                                <h4 style="color: #1e293b;">No exited students found</h4>
                                <p class="text-muted">Try adjusting your filters or exit a student first</p>
                                
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($exitedStudents->hasPages())
        <div class="pagination-wrapper">
            <div class="info">
                Showing {{ $exitedStudents->firstItem() ?? 0 }} to {{ $exitedStudents->lastItem() ?? 0 }}
                of {{ $exitedStudents->total() }} results
            </div>
            <div>
                {{ $exitedStudents->appends(request()->except('page'))->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Exit Details Modal -->
<div class="modal fade" id="exitDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--danger-gradient);">
                <h5 class="modal-title" style="color: white;">
                    <i class="bi bi-info-circle me-2"></i>
                    Exit Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    style="filter: invert(1);"></button>
            </div>
            <div class="modal-body" id="exitDetailsContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-danger" role="status"></div>
                    <p class="mt-2">Loading exit details...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ============================================
// SORT FUNCTION
// ============================================
function sortTable(column) {
    const urlParams = new URLSearchParams(window.location.search);
    const currentSortBy = urlParams.get('sort_by') || 'exited_at';
    const currentSortOrder = urlParams.get('sort_order') || 'desc';

    let newSortOrder = 'asc';
    if (currentSortBy === column) {
        newSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
    }

    urlParams.set('sort_by', column);
    urlParams.set('sort_order', newSortOrder);
    urlParams.delete('page');

    window.location.href = window.location.pathname + '?' + urlParams.toString();
}

// ============================================
// VIEW EXIT DETAILS WITH SEAT SUMMARY
// ============================================
// ============================================
// VIEW EXIT DETAILS WITH SEAT SUMMARY
// ============================================
function viewExitDetails(exitId) {
    $('#exitDetailsModal').modal('show');
    $('#exitDetailsContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-danger" role="status"></div>
            <p class="mt-2">Loading exit details...</p>
        </div>
    `);

    $.ajax({
        url: `/institute/admin/exit-details/${exitId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                let html = `
                    <div class="row g-4">
                        <!-- Student Information -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-primary"><i class="bi bi-person"></i> Student Information</h6>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <p><strong>Name:</strong> ${data.student_name}</p>
                                            <p><strong>Registration:</strong> ${data.registration_number}</p>
                                            <p><strong>Email:</strong> ${data.email || 'N/A'}</p>
                                            <p><strong>Mobile:</strong> ${data.mobile || 'N/A'}</p>
                                        </div>
                                        <div class="col-6">
                                            <p><strong>Course:</strong> ${data.course || 'N/A'}</p>
                                            <p><strong>Course Subtype:</strong> ${data.course_subtype || 'N/A'}</p>
                                           
                                            <p><strong>Academic Year:</strong> ${data.academic_year || 'N/A'}</p>
                                            ${data.batch ? `<p><strong>Batch:</strong> ${data.batch}</p>` : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Exit Information -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-danger"><i class="bi bi-door-open"></i> Exit Information</h6>
                                    <hr>
                                    <p><strong>Exit Type:</strong> 
                                        <span class="exit-type-badge exit-type-${data.exit_type}">
                                            ${data.exit_type_label}
                                        </span>
                                    </p>
                                    <p><strong>Exit Date:</strong> ${data.exit_date}</p>
                                    <p><strong>Exit Reason:</strong> ${data.exit_reason || 'Not specified'}</p>
                                    ${data.course_end_date && data.course_end_date !== 'N/A' ? 
                                        `<p><strong>Course End Date:</strong> ${data.course_end_date}</p>` : ''}
                                    <p><strong>Exited By:</strong> ${data.exited_by || 'System'}</p>
                                    <p><strong>Exited At:</strong> ${data.exited_at}</p>
                                    <p><strong>IP Address:</strong> ${data.ip_address || 'N/A'}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // ============================================
                // SEAT SUMMARY SECTION
                // ============================================
                if (data.seat_summary) {
                    const seat = data.seat_summary;
                    // REMOVED: const data = response.data; - This was causing the error
                    html += `
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-warning">
                                    <div class="card-header" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
                                        <h6 class="mb-0"><i class="bi bi-chair me-2"></i> Seat Release Summary</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="alert alert-info mb-0">
                                                    <strong><i class="bi bi-book me-1"></i> Course:</strong>
                                                    <span class="d-block">${data.course || 'N/A'} ${data.course_subtype || 'N/A'}</span>
                                                    ${seat.course_code ? `<small class="text-muted">Code: ${seat.course_code}</small>` : ''}
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="alert alert-warning mb-0">
                                                    <strong><i class="bi bi-grid-3x3 me-1"></i> Section:</strong>
                                                    <span class="d-block">${seat.section_display_name || 'N/A'}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="alert alert-success mb-0">
                                                    <strong><i class="bi bi-arrow-up-circle me-1"></i> Seat Released:</strong>
                                                    <span class="d-block" style="font-size: 20px; font-weight: 700; color: #059669;">
                                                        +${seat.released_seat_count || 1}
                                                    </span>
                                                    <small class="text-muted">Student exited from this course</small>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        
                                        <div class="row g-3 mt-2">
                                            <div class="col-md-6">
                                                <h6 class="text-danger"><i class="bi bi-arrow-left-circle"></i> Before Exit</h6>
                                                <table class="table table-sm table-bordered">
                                                    <tr>
                                                        <th>Total Course Seats</th>
                                                        <td>${seat.total_seats || 'N/A'}</td>
                                                    </tr>
                                                     <tr>
                                                        <th>Section Capacity</th>
                                                        <td>${seat.section_total_capacity || 'N/A'}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Section Occupied</th>
                                                        <td>${seat.section_previous_occupied ?? 'N/A'}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Section Available</th>
                                                        <td>${seat.section_previous_available ?? 'N/A'}</td>
                                                    </tr>
                                                   
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-success"><i class="bi bi-arrow-right-circle"></i> After Exit</h6>
                                                <table class="table table-sm table-bordered">
                                                    <tr>
                                                        <th>Total Course Seats</th>
                                                        <td>${seat.total_seats || 'N/A'}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Section Occupied</th>
                                                        <td>${seat.section_new_occupied ?? 'N/A'}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Section Available</th>
                                                        <td>${seat.section_new_available ?? 'N/A'}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Section Capacity</th>
                                                        <td>${seat.section_total_capacity || 'N/A'}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        
                                        <div class="alert alert-secondary mt-3 mb-0">
                                            <small>
                                                <strong><i class="bi bi-info-circle me-1"></i> Reason:</strong>
                                                ${seat.reason || 'Student exited from course'}
                                                <br>
                                                <strong>Performed By:</strong> ${seat.performed_by || 'System'} 
                                                <span class="text-muted">at ${seat.performed_at || 'N/A'}</span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }

                // ============================================
                // EXIT HISTORY (if multiple exits)
                // ============================================
                if (data.exit_history && data.exit_history.length > 1) {
                    html += `
                        <div class="row mt-3 d-none">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i> Exit History (${data.exit_history.length} records)</h6>
                                    </div>
                                    <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Date & Time</th>
                                                    <th>Section</th>
                                                    <th>Reason</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                    `;
                    data.exit_history.forEach((history, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${history.performed_at}</td>
                                <td>${history.section_id || 'N/A'}</td>
                                <td><small>${history.reason || 'N/A'}</small></td>
                            </tr>
                        `;
                    });
                    html += `
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }

                $('#exitDetailsContent').html(html);
            } else {
                $('#exitDetailsContent').html(`
                    <div class="alert alert-danger">${response.message}</div>
                `);
            }
        },
        error: function(xhr) {
            let errorMessage = 'Error loading exit details';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            $('#exitDetailsContent').html(`
                <div class="alert alert-danger">${errorMessage}</div>
            `);
        }
    });
}

// ============================================
// GENERATE NO DUE CERTIFICATE
// ============================================
function generateNoDue(exitId, studentHashId) {
    Swal.fire({
        title: 'Generate No Due Certificate?',
        text: 'This will generate a No Due Certificate for this student.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Generate',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                text: 'Generating No Due Certificate',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: `/institute/admin/exit/generate-no-due/${exitId}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            confirmButtonColor: '#10b981'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            confirmButtonColor: '#10b981'
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage,
                        confirmButtonColor: '#10b981'
                    });
                }
            });
        }
    });
}

// ============================================
// AUTO-SUBMIT FILTERS
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('.filter-group input, .filter-group select');
    let typingTimer;

    filterInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                this.closest('form').submit();
            }, 800);
        });

        input.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
});
</script>
@endsection