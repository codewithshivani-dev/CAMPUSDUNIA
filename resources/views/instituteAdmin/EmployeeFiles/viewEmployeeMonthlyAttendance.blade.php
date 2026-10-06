@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<title>Employee Monthly Attendance</title>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
    --shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.15);
}

/* Bulk Actions Styles (from custom fee page) */
.bulk-actions-container {
    margin: 20px 25px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    animation: slideDown 0.3s ease;
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    border: 2px solid #e0e0e0;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bulk-actions-container.active {
    display: flex;
}

.selected-count {
    font-weight: 500;
    color: #475569;
    margin-right: auto;
    font-size: 14px;
}

.bulk-action-btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    color: #475569;
    transition: all 0.2s;
    font-size: 14px;
    display: flex;
    align-items: center;
    border: 1px solid transparent;
    margin-right: 5px;
}

.bulk-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.bulk-action-btn:active {
    transform: translateY(0);
}

.bulk-action-btn.download {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.bulk-action-btn.download:hover {
    background: #bbf7d0;
}

.bulk-action-btn.mark-present {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #bfdbfe;
}

.bulk-action-btn.mark-present:hover {
    background: #bfdbfe;
}

.bulk-action-btn.mark-absent {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.bulk-action-btn.delete {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.bulk-action-btn.delete:hover {
    background: #fecaca;
}

.bulk-action-btn.mark-absent:hover {
    background: #fecaca;
}

.bulk-action-btn.clear {
    background: transparent;
    color: #64748b;
    border: 1px solid #cbd5e1;
}

.bulk-action-btn.clear:hover {
    background: #f1f5f9;
}

.bulk-action-btn i {
    margin-right: 5px;
}

/* Checkbox styling */
.select-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
    border-radius: 4px;
    border: 2px solid #cbd5e1;
    transition: all 0.2s;
}

.select-checkbox:hover {
    border-color: #3b82f6;
}

.select-checkbox:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
}

/* Enhanced Table Styles */
.attendance-container {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    /*overflow: hidden;*/
    animation: fadeInUp 0.6s ease;
}

/* Page Header */
.page-header {
    background: var(--primary-gradient);
    padding: 15px 15px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
}

.page-header h4 {
    color: white;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    /*font-size: 24px;*/
}

.page-header h4 i {
    background: rgba(255, 255, 255, 0.2);
    padding: 12px;
    border-radius: 12px;
    margin-right: 8px;
}

.page-header .badge {
    background: rgba(255, 255, 255, 0.2) !important;
    color: white !important;
    padding: 8px 15px;
    border-radius: 30px;
    font-weight: 500;
    font-size: 14px;
    margin-left: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Filters Section */
.filters-section {
    background: linear-gradient(135deg, #f8faff 0%, #f0f4ff 100%);
    padding: 25px;
    border-bottom: 1px solid #e2e8f0;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.filter-group {
    position: relative;
}

.filter-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #2c3e50;
    font-size: 14px;
}

.filter-group label i {
    color: #4361ee;
}

.filter-input {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 14px;
    background: white;
    transition: all 0.3s;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.filter-input:focus {
    outline: none;
    border-color: #4361ee;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.filter-input:hover {
    border-color: #3a0ca3;
}

.filter-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn {
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.btn-primary {
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.4);
}

.btn-secondary {
    background: white;
    color: #475569;
    border: 2px solid #e0e0e0;
}

.btn-secondary:hover {
    background: #f8fafc;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #4361ee;
}

/* Summary Cards */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin: 25px;
}

.summary-card {
    background: white;
    border-radius: 15px;
    padding: 25px 20px;
    border: 2px solid #e0e0e0;
    transition: all 0.3s ease;
    position: relative;
    /*overflow: hidden;*/
    cursor: pointer;
    text-align: center;
}

.summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    border-color: #4361ee;
}

.summary-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
}

.summary-card.present::before {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.summary-card.absent::before {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.summary-card.leave::before {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.summary-card.avg::before {
    background: var(--primary-gradient);
}

.summary-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 28px;
}

.summary-card.present .summary-icon {
    background: linear-gradient(135deg, #10b98115 0%, #05966915 100%);
    color: #059669;
}

.summary-card.absent .summary-icon {
    background: linear-gradient(135deg, #ef444415 0%, #dc262615 100%);
    color: #dc2626;
}

.summary-card.leave .summary-icon {
    background: linear-gradient(135deg, #f59e0b15 0%, #d9770615 100%);
    color: #d97706;
}

.summary-card.avg .summary-icon {
    background: linear-gradient(135deg, #4361ee15 0%, #3a0ca315 100%);
    color: #4361ee;
}

.summary-value {
    font-size: 36px;
    font-weight: 800;
    margin-bottom: 5px;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.summary-label {
    font-size: 14px;
    color: #64748b;
    font-weight: 500;
    margin-bottom: 5px;
}

.summary-subtext {
    font-size: 12px;
    color: #94a3b8;
    font-weight: 400;
}

.summary-card.active {
    transform: translateY(-4px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    border: 2px solid #4361ee;
}

/* Status Filter Tabs */
.status-tabs {
    display: flex;
    gap: 10px;
    margin: 25px;
    flex-wrap: wrap;
}

.status-tab {
    padding: 10px 20px;
    border-radius: 30px;
    background: white;
    border: 2px solid #e0e0e0;
    color: #64748b;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.status-tab:hover {
    border-color: #4361ee;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.15);
}

.status-tab.active {
    background: var(--primary-gradient);
    color: white;
    border-color: transparent;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
}

.status-tab .count-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

/* Table Styles */
.attendance-table-container {
    border-radius: 0 0 15px 15px;
    margin: 0 20px 0px 20px;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    background: white;
}

.attendance-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
}

.attendance-table th {
    /* background: linear-gradient(135deg, #f8faff 0%, #eef2ff 100%); */
    padding: 15px 12px;
    font-weight: 600;
    /* color: #2c3e50; */
    text-align: center;
    border-bottom: 2px solid #e0e0e0;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    /*position: sticky;*/
    left: 0;
}

.attendance-table th:first-child {
    /*position: sticky;*/
    left: 0;
    /* background: linear-gradient(135deg, #f8faff 0%, #eef2ff 100%); */
    z-index: 10;
    min-width: 280px;
}

.attendance-table .bttn {
    display: inline;
}

.attendance-table td {
    padding: 12px;
    border-bottom: 1px solid #f1f5f9;
    /*text-align: center;*/
    vertical-align: middle;
    transition: background 0.2s;
}

.attendance-table td:first-child {
    position: sticky;
    left: 0;
    background: white;
    z-index: 9;
}

.attendance-table tr:hover td:first-child {
    background: #f8fafc;
}

.attendance-table tr:hover td {
    background: #f8fafc;
}

/* Employee Info */
.employee-info {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 5px 0;
}

.employee-avatar {
    width: 35px;
    height: 35px;
    border-radius: 12px;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 14px;
    box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
}

.employee-details {
    flex: 1;
    text-align: left;
}

.employee-name {
    font-weight: 700;
    /*color: #1e293b;*/
    /*margin-bottom: 4px;*/
}

.employee-meta {
    font-size: 12px;
    color: #64748b;
    display: flex;
    flex-direction: column;
    gap: 3px;
}

/* Day Header */
.day-number {
    font-size: 16px;
    /*font-weight: 700;*/
    /* color: #1e293b; */
}

.current-day .day-number {
    color: #4361ee;
    background: rgba(67, 97, 238, 0.1);
    padding: 5px 10px;
    border-radius: 20px;
}

/* Status Cells */
.status-cell {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 70px;
    padding: 8px 12px;
    margin: 0 auto;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.status-present {
    background: linear-gradient(135deg, #10b98115 0%, #05966915 100%);
    color: #059669;
    border: 1px solid #10b98140;
}

.status-absent {
    background: linear-gradient(135deg, #ef444415 0%, #dc262615 100%);
    color: #dc2626;
    border: 1px solid #ef444440;
}

.status-leave {
    background: linear-gradient(135deg, #f59e0b15 0%, #d9770615 100%);
    color: #d97706;
    border: 1px solid #f59e0b40;
}

.status-weekend {
    background: #f8fafc;
    color: #94a3b8;
    border: 1px solid #e2e8f0;
}

.status-future {
    background: #f8fafc;
    color: #94a3b8;
    border: 1px dashed #e2e8f0;
}

.status-cell:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    cursor: pointer;
}

/* Attendance Time */
.attendance-time {
    margin-top: 5px;
    font-size: 11px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2px;
}

.attendance-time .in-time {
    color: #059669;
    font-weight: 600;
}

.attendance-time .out-time {
    color: #0284c7;
    font-weight: 600;
}

/* Loading Animation */
.loading {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top: 2px solid white;
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

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Legend Section */
.mt-4.p-4.bg-white {
    border-radius: 12px;
    border: 2px solid #e0e0e0;
    margin: 25px;
    padding: 20px !important;
}

.mt-4.p-4.bg-white h6 {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 15px;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        gap: 15px;
    }

    .filter-grid {
        grid-template-columns: 1fr;
    }

    .summary-grid {
        grid-template-columns: repeat(2, 1fr);
        margin: 15px;
    }

    .attendance-table-container {
        margin: 15px;
    }

    .employee-avatar {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }

    .status-cell {
        min-width: 60px;
        font-size: 11px;
        padding: 6px 8px;
    }

    .bulk-actions-container {
        flex-direction: column;
        align-items: stretch;
        margin: 15px;
    }

    .bulk-actions-container .d-flex {
        flex-wrap: wrap;
        gap: 8px;
    }
}

@media (max-width: 480px) {
    .summary-grid {
        grid-template-columns: 1fr;
    }

    .filter-actions,
    .status-tabs {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }

    .status-tab {
        width: 100%;
        justify-content: center;
    }

    .bulk-action-btn {
        width: 100%;
        justify-content: center;
    }
}

/* Pagination Container */
.pagination {
    display: flex;
    list-style: none;
    gap: 6px;
    padding: 0;

}

.page-item .page-link {
    padding: 6px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    text-decoration: none;
    color: #333;
    font-size: 13px;
}

.page-item.active .page-link {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
}

.page-item.disabled .page-link {
    background: #f5f5f5;
    color: #aaa;
}

/* Pagination Wrapper */
.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 16px;
    flex-wrap: wrap;
    gap: 10px;
    padding: 0px 20px;
}

/* Showing Text */
.pagination-info {
    font-size: 13px;
    color: #6b7280;
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

        .table-responsive{
            overflow-x: hidden;
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

</style>

<div class="container-fluid">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Page Header with Gradient -->
    <div class="page-header">
        <h4>
            <i class="fas fa-calendar-check"></i>
            Employee Attendance
            <span class="badge">
                {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
            </span>
        </h4>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-secondary" onclick="window.print()">
                <i class="fas fa-print"></i>
                Print Report
            </button>
        </div>
    </div>

    <!-- Attendance Container -->
    <div class="attendance-container">
        <!-- Filters Section -->
        <div class="filters-section">
            <form method="GET" action="{{ route('attendance.monthly') }}" id="filterForm">

                <input type="hidden" name="status" id="statusFilter" value="{{ $statusFilter }}">

                <!-- Hidden Fields -->
                <input type="hidden" name="month" id="month" value="{{ $month }}">
                <input type="hidden" name="year" id="year" value="{{ $year }}">
                <input type="hidden" name="department_id" id="department_id" value="{{ $departmentId }}">
                <input type="hidden" name="employee_id" id="employee_id" value="{{ $employeeId }}">
                <input type="hidden" name="shift_id" id="shift_id" value="{{ $shiftId }}">

                <div class="filter-grid">

                    <!-- Month -->
                    <div class="filter-group">
                        <label><i class="fas fa-calendar-alt"></i> Month</label>
                        <input type="text" class="filter-input datalist-input" list="monthList"
                            placeholder="Search Month" data-hidden="month"
                            value="{{ \Carbon\Carbon::create()->month($month)->format('F') }}">
                        <datalist id="monthList">
                            @foreach(range(1,12) as $m)
                            <option value="{{ \Carbon\Carbon::create()->month($m)->format('F') }}" data-id="{{ $m }}">
                            </option>
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Year -->
                    <div class="filter-group">
                        <label><i class="fas fa-calendar"></i> Year</label>
                        <input type="text" class="filter-input datalist-input" list="yearList" placeholder="Search Year"
                            data-hidden="year" value="{{ $year }}">
                        <datalist id="yearList">
                            @foreach(range(now()->year - 2, now()->year + 1) as $y)
                            <option value="{{ $y }}" data-id="{{ $y }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Department -->
                    <div class="filter-group">
                        <label><i class="fas fa-building"></i> Department</label>
                        <input type="text" class="filter-input datalist-input" list="departmentList"
                            placeholder="Search Department" data-hidden="department_id"
                            value="{{ optional($departments->firstWhere('department_id', $departmentId))->department }}">
                        <datalist id="departmentList">
                            @foreach($departments as $dept)
                            <option value="{{ $dept->department }}" data-id="{{ $dept->department_id }}">
                            </option>
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Shift -->
                    <div class="filter-group">
                        <label><i class="fas fa-clock"></i> Shift</label>
                        <input type="text" class="filter-input datalist-input" list="shiftList"
                            placeholder="Search Shift" data-hidden="shift_id"
                            value="{{ optional($shifts->firstWhere('id', $shiftId))->shift_name }}">
                        <datalist id="shiftList">
                            @foreach($shifts as $shift)
                            <option value="{{ $shift->shift_name }}" data-id="{{ $shift->id }}">
                            </option>
                            @endforeach
                        </datalist>
                    </div>

                    <!-- Employee -->
                    <div class="filter-group">
                        <label><i class="fas fa-user"></i> Employee</label>
                        <input type="text" class="filter-input datalist-input" list="employeeList"
                            placeholder="Search Employee" data-hidden="employee_id"
                            value="{{ optional($allEmployees->firstWhere('employee_id', $employeeId))->name }}">
                        <datalist id="employeeList">
                            @foreach($allEmployees as $emp)
                            <option value="{{ $emp->name }} ({{ $emp->employee_code }})"
                                data-id="{{ $emp->employee_id }}">
                            </option>
                            @endforeach
                        </datalist>
                    </div>

                </div>

                <div class="filter-actions">
                    <a href="{{ route('attendance.monthly') }}" class="btn btn-secondary"> <i
                            class="fas fa-undo-alt"></i> Reset All
                    </a>
                </div>
            </form>
        </div>

        <!-- Status Filter Tabs -->
        <div class="status-tabs">
            <div class="status-tab {{ !$statusFilter ? 'active' : '' }}" onclick="setStatusFilter('')">
                <i class="fas fa-users"></i>
                All Employees
                <span class="count-badge">{{ $allEmployees->count() ?? $employees->count() }}</span>
            </div>

            <div class="status-tab {{ $statusFilter == 'present' ? 'active' : '' }}" style="display:none;"
                onclick="setStatusFilter('present')">
                <i class="fas fa-check-circle"></i>
                Present Today
                <span class="count-badge">{{ $todaySummary['present'] ?? 0 }}</span>
            </div>

            <div class="status-tab {{ $statusFilter == 'absent' ? 'active' : '' }}" onclick="setStatusFilter('absent')"
                style="display:none;">
                <i class="fas fa-times-circle"></i>
                Absent Today
                <span class="count-badge">{{ $todaySummary['absent'] ?? 0 }}</span>
            </div>

            <div class="status-tab {{ $statusFilter == 'leave' ? 'active' : '' }}" onclick="setStatusFilter('leave')"
                style="display:none;">
                <i class="fas fa-calendar-week"></i>
                On Leave Today
                <span class="count-badge">{{ $todaySummary['on_leave'] ?? 0 }}</span>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-grid">
            <!-- Today's Attendance -->
            <div class="summary-card present {{ $statusFilter === 'present' ? 'active' : '' }}"
                onclick="setStatusFilter('present')">
                <div class="summary-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="summary-value">{{ $todaySummary['present'] ?? 0 }}</div>
                <div class="summary-label">Present Today</div>
                <div class="summary-subtext">{{ $todaySummary['date'] ?? 'Today' }}</div>
            </div>

            <div class="summary-card absent {{ $statusFilter === 'absent' ? 'active' : '' }}"
                onclick="setStatusFilter('absent')">
                <div class="summary-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="summary-value">{{ $todaySummary['absent'] ?? 0 }}</div>
                <div class="summary-label">Absent Today</div>
                <div class="summary-subtext">{{ $todaySummary['date'] ?? 'Today' }}</div>
            </div>

            <div class="summary-card leave {{ $statusFilter === 'leave' ? 'active' : '' }}"
                onclick="setStatusFilter('leave')">
                <div class="summary-icon">
                    <i class="fas fa-calendar-week"></i>
                </div>
                <div class="summary-value">{{ $todaySummary['on_leave'] ?? 0 }}</div>
                <div class="summary-label">On Leave Today</div>
                <div class="summary-subtext">{{ $todaySummary['date'] ?? 'Today' }}</div>
            </div>

            <!-- Monthly Summary -->
            <div class="summary-card avg">
                <div class="summary-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="summary-value">{{ $monthlySummary['avg_attendance'] ?? 0 }}%</div>
                <div class="summary-label">Attendance Rate</div>
                <div class="summary-subtext">
                    {{ $monthlySummary['working_days'] ?? 0 }} working days
                </div>
            </div>
        </div>

        {{-- Bulk Actions Container (Added from custom fee page) --}}
        <div class="bulk-actions-container" id="bulkActionsContainer">
            <div class="selected-count" id="selectedCount">0 employees selected</div>
            <div class="d-flex flex-wrap">
                <div class="dropdown me-2">
                    <button class="bulk-action-btn download dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bi bi-download"></i>
                        Download
                    </button>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="bulkAction('download','excel')">
                                <i class="bi bi-file-earmark-excel text-success me-2"></i>
                                Excel (.xlsx)
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="bulkAction('download','csv')">
                                <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                CSV (.csv)
                            </a>
                        </li>
                    </ul>
                </div>
                <button class="bulk-action-btn mark-present d-none" onclick="bulkAction('mark_present')">
                    <i class="bi bi-check-circle"></i>
                    Mark Present
                </button>
                <button class="bulk-action-btn mark-absent d-none" onclick="bulkAction('mark_absent')">
                    <i class="bi bi-x-circle"></i>
                    Mark Absent
                </button>
                <button class="bulk-action-btn delete d-none" onclick="bulkAction('bulk_delete')">
                    <i class="bi bi-trash"></i>
                    Bulk Delete
                </button>
                <button class="bulk-action-btn clear mr-0" onclick="clearSelection()">
                    <i class="bi bi-x-lg"></i>
                    Clear
                </button>
            </div>
        </div>

        <div>
        <!-- Attendance Table -->
            <div class="attendance-table-container table-responsive custom-table-wrapper" id="tableWrapper">
                <table class="erp-table attendance-table">
                    <thead>
                        <tr>
                            <th class="sticky-checkbox text-start" style="min-width: 30px; width: 40px;">
                                <input type="checkbox" id="selectAll" class="select-checkbox">
                            </th>
                            <th class="sticky-main text-start sortable">
                                <div class="fw-bold">Employee Details</div>
                                <div class="small mt-1">
                                    Name &nbsp;|&nbsp; Department &nbsp;|&nbsp; Shift
                                </div>
                            </th>
    
                            @foreach($dates as $date)
                                @php
                                    $dateObj = \Carbon\Carbon::parse($date);
                                    $isToday = $dateObj->toDateString() == now()->toDateString();
                                @endphp
                                <th class="sortable {{ $isToday ? 'today-column' : '' }}">
                                    <div class="day-number">{{ $dateObj->day }} {{ $dateObj->format('F') }} {{ $dateObj->format('Y') }}</div>
                                    <div class="small">{{ $dateObj->format('D') }}</div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        @php
                        // Get initials for avatar
                        $initials = '';
                        if ($employee->name) {
                        $nameParts = explode(' ', $employee->name);
                        if (count($nameParts) >= 2) {
                        $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
                        } else {
                        $initials = strtoupper(substr($employee->name, 0, 2));
                        }
                        }
    
                        // Get week_off_days for this employee's shift
                        $weekOffDays = [];
                        if ($employee->resolved_shift && isset($employee->resolved_shift->weekly_off_days)) {
                        $weeklyOffData = $employee->resolved_shift->weekly_off_days;
                        if (is_string($weeklyOffData)) {
                        $weeklyOffData = json_decode($weeklyOffData, true) ?? [];
                        }
    
                        if (is_array($weeklyOffData) && !empty($weeklyOffData)) {
                        $dayMap = [
                        'Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3,
                        'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6, 'Sunday' => 7
                        ];
    
                        foreach ($weeklyOffData as $dayName) {
                        if (isset($dayMap[$dayName])) {
                        $weekOffDays[] = $dayMap[$dayName];
                        }
                        }
                        }
                        }
    
                        if (empty($weekOffDays)) {
                        $weekOffDays = [7];
                        }
    
                        $weeklyOffDisplay = [];
                        if ($employee->resolved_shift && isset($employee->resolved_shift->weekly_off_days)) {
                        $displayData = $employee->resolved_shift->weekly_off_days;
                        if (is_string($displayData)) {
                        $weeklyOffDisplay = json_decode($displayData, true) ?? [];
                        } elseif (is_array($displayData)) {
                        $weeklyOffDisplay = $displayData;
                        }
                        }
                        $weekOffText = !empty($weeklyOffDisplay) ? ' | Off: ' . implode(', ', $weeklyOffDisplay) : '';
                        @endphp
    
                        <tr>
                            <td class="sticky-checkbox">
                                <input type="checkbox" class="employee-checkbox select-checkbox"
                                    value="{{ $employee->employee_id }}">
                            </td>
                            <td class="sticky-main">
                                <div class="employee-info">
                                    <div class="employee-details">
                                        <div class="d-flex gap-1">
                                            <span class="d-none employee-avatar">
                                            {{ $initials ?: 'E' }}
                                            </span>
                                            <div>
                                                <div class="employee-name">{{ $employee->name }}</div>
                                                <small class="text-muted">{{ $employee->employee_code }}</small>
                                                <div><span class="small">{{ $employee->department_name ?? 'No Department' }}</span></div>
                                            </div>
                                        </div>
                                        <div class="employee-meta">
                                            @if($employee->resolved_shift)
                                            @php
                                            $startTime =
                                            \Carbon\Carbon::parse($employee->resolved_shift->start_time)->format('h:i A');
                                            $endTime =
                                            \Carbon\Carbon::parse($employee->resolved_shift->end_time)->format('h:i A');
                                            @endphp
                                            <span class="shift-timing-badge">
                                                <i class="fas fa-clock"></i>
                                                {{ $startTime }} - {{ $endTime }}
                                                <small>({{ ucfirst($employee->resolved_shift_type) }}){{ $weekOffText }}</small>
                                            </span>
                                            @else
                                            <span class="badge bg-secondary text-white">
                                                <i class="fas fa-clock"></i> No Shift
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
    
                            @foreach($dates as $date)
                            @php
                            $dateObj = \Carbon\Carbon::parse($date);
                            $isToday = $dateObj->toDateString() == now()->toDateString();
                            $isFuture = $dateObj->gt(now());
    
                            $isWeeklyOff = false;
                            if (!empty($weekOffDays)) {
                            $dayOfWeek = $dateObj->dayOfWeekIso;
                            $isWeeklyOff = in_array($dayOfWeek, $weekOffDays);
                            }
    
                            $key = $employee->employee_id . '_' . $date;
                            $record = $attendance[$key][0] ?? null;
                            $leaveRecord = $leaveData[$key] ?? null;
    
                            if ($isWeeklyOff) {
                            $status = 'weekend';
                            $statusText = 'Off';
                            $checkInTime = $checkOutTime = null;
                            } elseif ($isFuture) {
                            $status = 'future';
                            $statusText = 'N/A';
                            $checkInTime = $checkOutTime = null;
                            } elseif ($record) {
                            if ($record->status === 'Present') {
                            $status = 'present';
                            $statusText = 'Present';
    
                            $checkInLog = $record->logs->where('check_type', 'IN')->first();
                            $checkOutLog = $record->logs->where('check_type', 'OUT')->first();
    
                            $checkInTime = $checkInLog ? \Carbon\Carbon::parse($checkInLog->check_time, 'UTC')
                            ->setTimezone('Asia/Kolkata')->format('h:i A') : null;
                            $checkOutTime = $checkOutLog ? \Carbon\Carbon::parse($checkOutLog->check_time, 'UTC')
                            ->setTimezone('Asia/Kolkata')->format('h:i A') : null;
                            } else {
                            $status = 'absent';
                            $statusText = $record->status;
                            $checkInTime = $checkOutTime = null;
                            }
                            } elseif ($leaveRecord) {
                            $status = 'leave';
                            $statusText = 'Leave';
                            $checkInTime = $checkOutTime = null;
                            } else {
                            $status = 'absent';
                            $statusText = 'Absent';
                            $checkInTime = $checkOutTime = null;
                            }
                            @endphp
    
                            <td class="text-center">
                                <div class="status-cell status-{{ $status }}"
                                    title="{{ $dateObj->format('d M Y') }} - {{ $employee->name }}: {{ $statusText }}"
                                    onclick="showAttendanceDetails(
                                        '{{ $employee->employee_id }}',
                                        '{{ $employee->name }}',
                                        '{{ $date }}',
                                        '{{ $status }}',
                                        '{{ $statusText }}',
                                        '{{ $checkInTime ?? '' }}',
                                        '{{ $checkOutTime ?? '' }}'
                                    )">
                                    {{ $statusText }}
                                </div>
    
                                @if($status === 'present')
                                <div class="attendance-time">
                                    @if($checkInTime)
                                    <div class="in-time">IN {{ $checkInTime }}</div>
                                    @endif
                                    @if($checkOutTime)
                                    <div class="out-time">OUT {{ $checkOutTime }}</div>
                                    @endif
                                </div>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ count($dates) + 2 }}" class="py-5">
                                <div class="text-center">
                                    <i class="fas fa-users display-1 text-muted mb-3"></i>
                                    <h4 class="text-muted mb-2">No Employees Found</h4>
                                    <p class="text-muted">No employees match the selected filters.</p>
                                    <button onclick="resetFilters()" class="btn bttn btn-primary">
                                        <i class="fas fa-undo-alt"></i>
                                        Reset Filters
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        
            <div class="px-4">
                <div class="table-scroll-top" id="tableScrollTop">
                <div class="table-scroll-inner"></div>
            </div>
        </div>
        
            <div class="pagination-wrapper">
            <div class="pagination-info">
                Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }}
                of {{ $employees->total() }} employees
            </div>

            @if ($employees->hasPages())
            <nav>
                <ul class="pagination mb-0">

                    {{-- Previous Page --}}
                    @if ($employees->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">Prev</span></li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $employees->previousPageUrl() }}">Prev</a>
                    </li>
                    @endif

                    {{-- Page Numbers --}}
                    @for ($i = 1; $i <= $employees->lastPage(); $i++)
                        <li class="page-item {{ $employees->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $employees->url($i) }}">{{ $i }}</a>
                        </li>
                        @endfor

                        {{-- Next Page --}}
                        @if ($employees->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $employees->nextPageUrl() }}">Next</a>
                        </li>
                        @else
                        <li class="page-item disabled"><span class="page-link">Next</span></li>
                        @endif

                </ul>
            </nav>
            @endif
        </div>
        </div>
        <!-- Legend Section -->
        <div class="mt-4 p-4 bg-white rounded-lg border">
            <h6 class="fw-bold mb-3">Status Legend:</h6>
            <div class="d-flex flex-wrap gap-2">
                <div class="d-flex align-items-center">
                    <div class="status-cell status-present me-1">Present</div>
                    <small>Marked as present</small>
                </div>
                <div class="d-flex align-items-center">
                    <div class="status-cell status-absent me-1">Absent</div>
                    <small>No attendance record</small>
                </div>
                <div class="d-flex align-items-center">
                    <div class="status-cell status-leave me-1">Leave</div>
                    <small>Approved leave</small>
                </div>
                <div class="d-flex align-items-center">
                    <div class="status-cell status-weekend me-1">Off</div>
                    <small>Weekly Off Days</small>
                </div>
                <div class="d-flex align-items-center">
                    <div class="status-cell status-future me-1">N/A</div>
                    <small>Upcoming</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for details -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--primary-gradient); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-clock me-2"></i>
                    Attendance Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    style="background-color: white;"></button>
            </div>
            <div class="modal-body p-0" id="modalBody">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    
        const wrapper = document.getElementById('tableWrapper');
        const todayColumn = document.querySelector('.today-column');
    
        if (!wrapper || !todayColumn) return;
    
        setTimeout(() => {
    
            const stickyColumnsWidth = 260;
    
            wrapper.scrollLeft =
                todayColumn.offsetLeft -
                stickyColumnsWidth;
    
        }, 200);
    
    });
document.addEventListener('DOMContentLoaded', function() {

    const selectAllCheckbox = document.getElementById('selectAll');
    const bulkActionsContainer = document.getElementById('bulkActionsContainer');
    const selectedCountElement = document.getElementById('selectedCount');

    function getEmployeeCheckboxes() {
        return document.querySelectorAll('.employee-checkbox');
    }

    function updateSelectionUI() {

        const employeeCheckboxes = getEmployeeCheckboxes();
        const selectedCheckboxes = document.querySelectorAll('.employee-checkbox:checked');
        const selectedCount = selectedCheckboxes.length;

        // Update count
        selectedCountElement.textContent = selectedCount + ' employee(s) selected';

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = selectedCount === employeeCheckboxes.length && employeeCheckboxes
                .length > 0;
            selectAllCheckbox.indeterminate = selectedCount > 0 && selectedCount < employeeCheckboxes.length;
        }
    }

    // Select All
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {

            const employeeCheckboxes = getEmployeeCheckboxes();

            employeeCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });

            updateSelectionUI();
        });
    }

    // Individual checkbox change
    document.addEventListener('change', function(e) {

        if (e.target.classList.contains('employee-checkbox')) {
            updateSelectionUI();
        }

    });

    // Initialize count
    updateSelectionUI();


    // Filter form submission handling
    const form = document.getElementById('filterForm');

    document.querySelectorAll('.datalist-input').forEach(input => {

        input.addEventListener('change', function() {

            const listId = this.getAttribute('list');
            const hiddenInputId = this.dataset.hidden;
            const hiddenInput = document.getElementById(hiddenInputId);

            const option = document.querySelector(`#${listId} option[value="${this.value}"]`);

            hiddenInput.value = option ? option.dataset.id : '';

            form.submit();

        });

    });

});


// Clear selection
function clearSelection() {

    document.querySelectorAll('.employee-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });

    const selectAllCheckbox = document.getElementById('selectAll');

    if (selectAllCheckbox) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
    }

    const selectedCountElement = document.getElementById('selectedCount');
    selectedCountElement.textContent = '0 employee(s) selected';

}

function bulkAction(action, format=null) {
    const selectedEmployees = Array.from(document.querySelectorAll('.employee-checkbox:checked'))
        .map(checkbox => checkbox.value);

    if (selectedEmployees.length === 0) {
        alert('Please select at least one employee.');
        return;
    }

    switch (action) {
            case 'download':

                    if (!format) {
                        alert("Please select a format");
                        return;
                    }

                    const ids = selectedEmployees.join(',');

                    const url = `/attendance/download?ids=${ids}&type=${format}`;

                    window.location.href = url;
                
            break;
        case 'mark_present':
            if (confirm(`Mark ${selectedEmployees.length} employee(s) as present for today?`)) {
                // Show loading state
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<span class="loading-spinner"></span> Updating...';
                btn.disabled = true;

                // Simulate update - replace with actual API call
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                    clearSelection();
                    alert(`${selectedEmployees.length} employee(s) marked as present`);
                    location.reload();
                }, 1500);
            }
            break;

        case 'mark_absent':
            if (confirm(`Mark ${selectedEmployees.length} employee(s) as absent for today?`)) {
                // Show loading state
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<span class="loading-spinner"></span> Updating...';
                btn.disabled = true;

                // Simulate update - replace with actual API call
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                    clearSelection();
                    alert(`${selectedEmployees.length} employee(s) marked as absent`);
                    location.reload();
                }, 1500);
            }
            break;
        case 'bulk_delete':
            if (confirm(
                    `Are you sure you want to delete ${selectedEmployees.length} employee(s)? This action cannot be undone.`
                )) {
                // Show loading state
                const btn = event.target.closest('button');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
                btn.disabled = true;

                // Simulate delete
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                    clearSelection();
                    alert(`${selectedEmployees.length} employee(s) deleted successfully`);
                }, 1500);
            }
            break;

        default:
            alert(`${action} action triggered for ${selectedEmployees.length} employee(s)`);
    }
}

function resetFilters() {
    window.location.href = "{{ route('attendance.monthly') }}";
}

function setStatusFilter(status) {
    const statusInput = document.getElementById('statusFilter');
    if (statusInput) {
        statusInput.value = status;
    } else {
        console.warn('statusFilter input not found');
    }

    document.querySelectorAll('.status-tab').forEach(tab => {
        tab.classList.remove('active');
    });

    if (status === 'present') {
        document.querySelector('.status-tab[onclick="setStatusFilter(\'present\')"]').classList.add('active');
    } else if (status === 'absent') {
        document.querySelector('.status-tab[onclick="setStatusFilter(\'absent\')"]').classList.add('active');
    } else if (status === 'leave') {
        document.querySelector('.status-tab[onclick="setStatusFilter(\'leave\')"]').classList.add('active');
    } else {
        document.querySelector('.status-tab[onclick="setStatusFilter(\'\')"]').classList.add('active');
    }

    document.getElementById('filterForm').submit();
}

function showAttendanceDetails(employeeId, employeeName, date, status, statusText, checkInTime, checkOutTime) {
    // Fetch attendance details via AJAX
    fetch(`/attendance/details/${employeeId}/${date}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            let details = '';

            if (data.success && data.attendance) {
                const att = data.attendance;
                const inTime = att.check_time || checkInTime || 'N/A';
                const outTime = att.check_out_time || checkOutTime || 'N/A';

                details = `
<div class="row g-2 p-3">
    <div class="col-6">
        <div class="card border-success">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-sign-in-alt text-success"></i>
                    <small class="text-muted">Check In</small>
                </div>
                <div class="fw-bold fs-5">${inTime}</div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card border-info">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-sign-out-alt text-info"></i>
                    <small class="text-muted">Check Out</small>
                </div>
                <div class="fw-bold fs-5">${outTime}</div>
            </div>
        </div>
    </div>
</div>
`;
            } else {
                if (checkInTime || checkOutTime) {
                    details = `
<div class="row g-2 p-3">
    <div class="col-6">
        <div class="card border-success">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-sign-in-alt text-success"></i>
                    <small class="text-muted">Check In</small>
                </div>
                <div class="fw-bold fs-5">${checkInTime || 'N/A'}</div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card border-info">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-sign-out-alt text-info"></i>
                    <small class="text-muted">Check Out</small>
                </div>
                <div class="fw-bold fs-5">${checkOutTime || 'N/A'}</div>
            </div>
        </div>
    </div>
</div>
<div class="text-center py-3">
    <small class="text-muted">No detailed record found in database</small>
</div>
`;
                } else {
                    details = `
<div class="text-center py-4">
    <i class="fas fa-info-circle display-6 text-muted mb-3"></i>
    <p class="text-muted">No attendance details available for this day</p>
</div>
`;
                }
            }

            const dateObj = new Date(date);
            const formattedDate = dateObj.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            let statusColor = 'secondary';
            if (status === 'present') statusColor = 'success';
            if (status === 'absent') statusColor = 'danger';
            if (status === 'leave') statusColor = 'warning';
            if (status === 'weekend') statusColor = 'info';

            const modalContent = `
<div class="p-0">
    <div class="p-3 bg-light border-bottom">
        <div class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-user text-primary"></i>
                <span class="fw-bold">${employeeName}</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-calendar text-primary"></i>
                <span>${formattedDate}</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-circle text-${statusColor}"></i>
                <span class="badge bg-${statusColor} fs-6">${statusText}</span>
            </div>
        </div>
    </div>
    <div class="p-3">
        <h6 class="mb-3">Time Logs</h6>
        ${details}
    </div>
</div>
`;

            document.getElementById('modalBody').innerHTML = modalContent;

            const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error fetching attendance details:', error);

            const dateObj = new Date(date);
            const formattedDate = dateObj.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            let statusColor = 'secondary';
            if (status === 'present') statusColor = 'success';
            if (status === 'absent') statusColor = 'danger';
            if (status === 'leave') statusColor = 'warning';
            if (status === 'weekend') statusColor = 'info';

            const fallbackContent = `
<div class="p-0">
    <div class="p-3 bg-light border-bottom">
        <div class="d-flex flex-column gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-user text-primary"></i>
                <span class="fw-bold">${employeeName}</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-calendar text-primary"></i>
                <span>${formattedDate}</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-circle text-${statusColor}"></i>
                <span class="badge bg-${statusColor} fs-6">${statusText}</span>
            </div>
        </div>
    </div>
    <div class="p-3">
        <h6 class="mb-3">Time Logs</h6>
        <div class="row g-2">
            <div class="col-6">
                <div class="card border-success">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="fas fa-sign-in-alt text-success"></i>
                            <small class="text-muted">Check In</small>
                        </div>
                        <div class="fw-bold fs-5">${checkInTime || 'N/A'}</div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card border-info">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="fas fa-sign-out-alt text-info"></i>
                            <small class="text-muted">Check Out</small>
                        </div>
                        <div class="fw-bold fs-5">${checkOutTime || 'N/A'}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
`;

            document.getElementById('modalBody').innerHTML = fallbackContent;
            const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
            modal.show();
        });
}


// Auto-refresh every 3 minutes for real-time updates
setTimeout(() => {
    if (!document.querySelector('.loading')) {
        location.reload();
    }
}, 180000);
</script>
@endsection