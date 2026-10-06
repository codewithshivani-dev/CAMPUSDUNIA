@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<title>Attendance Review for Payroll</title>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --success-gradient: linear-gradient(135deg, #10b981, #059669);
    --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
    --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
    --border-color: #e2e8f0;
    --text-dark: #1e293b;
    --text-muted: #64748b;
    --bg-light: #f8fafc;
    --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
    --transition: all 0.2s ease;
}

/* Page Header */
.page-header {
    background: var(--primary-gradient);
    padding: 25px 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-header h1 {
    color: white;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    font-size: 24px;
}

.page-header h1 i {
    background: rgba(255, 255, 255, 0.2);
    padding: 12px;
    border-radius: 12px;
    margin-right: 15px;
}

.page-header .btn-light {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    transition: var(--transition);
}

.page-header .btn-light:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
}

/* Selection Bar */
.selection-bar {
    background: white;
    border-radius: 15px;
    padding: 15px 20px;
    margin-bottom: 20px;
    border: 2px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    position: sticky;
    top: 0;
    z-index: 100;
    background: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.selection-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.selection-count {
    font-weight: 600;
    color: var(--primary-color);
    font-size: 16px;
}

.selection-actions {
    display: flex;
    gap: 10px;
}

/* Filters Section */
.filters-section {
    background: var(--bg-light);
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 25px;
    border: 2px solid var(--border-color);
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.filter-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-dark);
    font-size: 13px;
}

.filter-input {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    font-size: 14px;
    transition: var(--transition);
    background: white;
}

.filter-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.filter-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.btn-reset {
    background: #64748b;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 10px;
    cursor: pointer;
    transition: var(--transition);
}

.btn-reset:hover {
    background: #475569;
    transform: translateY(-2px);
}

/* Employee Cards */
.employees-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 80px;
    transition: opacity 0.3s ease;
}

.employees-container.loading {
    opacity: 0.6;
    pointer-events: none;
}

.employee-card {
    background: white;
    border-radius: 15px;
    border: 2px solid var(--border-color);
    overflow: hidden;
    transition: var(--transition);
    position: relative;
}

.employee-card:hover {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.employee-card.selected {
    border-color: var(--primary-color);
    background: #f8faff;
    box-shadow: 0 5px 20px rgba(67, 97, 238, 0.15);
}

.employee-card.finalized-card {
    background: var(--bg-light);
    opacity: 0.95;
}

.employee-card.finalized-card .card-header {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
}

.card-header {
    padding: 20px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-bottom: 2px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.employee-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.employee-checkbox {
    width: 20px;
    height: 20px;
    cursor: pointer;
    accent-color: var(--primary-color);
}

.employee-avatar {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: var(--primary-gradient);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 18px;
}

.employee-details h4 {
    margin: 0 0 5px 0;
    font-weight: 600;
    color: var(--text-dark);
}

.employee-details p {
    margin: 0;
    font-size: 13px;
    color: var(--text-muted);
}

.shift-info {
    font-size: 13px;
    color: #6b7280;
    margin-top: 4px;
}

.weekly-off-badge {
    background: #f3f4f6;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    display: inline-block;
}

.leave-detail-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 13px;
    margin-right: 6px;
    margin-top: 6px;
}

.leave-detail-badge.short-leave {
    background: #fef9c3;
    color: #92400e;
}

.leave-detail-badge.half-day {
    background: #dbeafe;
    color: #1e3a8a;
}

.leave-detail-badge.unapproved-leave {
    background: #fee2e2;
    color: #991b1b;
}

.leave-detail-badge.Casual {
    background: #fef3c7;
    color: #92400e;
}

.leave-detail-badge.Earned {
    background: #dbeafe;
    color: #1e3a8a;
}

.leave-detail-badge.Sick {
    background: #fee2e2;
    color: #991b1b;
}

.leave-detail-badge.Unpaid {
    background: #f3f4f6;
    color: #374151;
}

.leave-detail-badge.Maternity {
    background: #fce7f3;
    color: #be185d;
}

.leave-detail-badge.Other {
    background: #e5e7eb;
    color: #6b7280;
}

/* Leave Balance Compact */
.leave-balance-compact {
    margin-top: 8px;
}

.leave-balance-item {
    font-size: 13px;
    padding: 4px 6px;
    background: rgba(5, 150, 105, 0.08);
    border-radius: 4px;
    border-left: 2px solid var(--success-color);
}

.balance-numbers {
    color: #059669;
    font-weight: 500;
}

.status-badge {
    padding: 8px 15px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
}

.status-pending {
    background: #fef3c7;
    color: #d97706;
}

.status-finalized {
    background: #d1fae5;
    color: #059669;
}

.view-only-badge {
    background: #e0e7ff;
    color: #4338ca;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    margin-left: 10px;
}

.card-body {
    padding: 20px;
}

.attendance-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.stat-item {
    text-align: center;
    padding: 12px;
    background: var(--bg-light);
    border-radius: 10px;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 12px;
    color: var(--text-muted);
}

.stat-value.present {
    color: #059669;
}

.stat-value.absent {
    color: #dc2626;
}

.stat-value.leave {
    color: #d97706;
}

.stat-value.weekend {
    color: #6b7280;
}

.progress-bar-custom {
    height: 8px;
    background: var(--border-color);
    border-radius: 10px;
    overflow: hidden;
    margin: 15px 0;
}

.progress-fill {
    height: 100%;
    background: var(--primary-gradient);
    border-radius: 10px;
    transition: width 0.3s ease;
}

.card-footer {
    padding: 15px 20px;
    background: #fafbff;
    border-top: 2px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.btn-review {
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary-custom {
    background: var(--primary-gradient);
    color: white;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
}

.btn-success-custom {
    background: var(--success-gradient);
    color: white;
}

.btn-success-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
}

.btn-outline {
    background: transparent;
    border: 2px solid var(--border-color);
    color: #475569;
}

.btn-outline:hover {
    border-color: var(--primary-color);
    background: var(--bg-light);
}

.btn-info-custom {
    background: linear-gradient(135deg, #0ea5e9, #0284c7);
    color: white;
}

.btn-info-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(14, 165, 233, 0.3);
}

.period-badge {
    background: var(--warning-gradient);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 20px;
    font-weight: 500;
    display: inline-block;
    color: white;
}

/* Modal Styles */
.modal-content {
    border-radius: 15px;
    border: none;
}

.modal-header {
    background: var(--primary-gradient);
    color: white;
    border-radius: 15px 15px 0 0;
}

.modal-header .btn-close {
    color: white;
    filter: brightness(0) invert(1);
}

.daily-attendance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 10px;
    max-height: 500px;
    overflow-y: auto;
    padding: 10px;
}

.day-card {
    text-align: center;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid var(--border-color);
    transition: var(--transition);
    background: white;
    min-height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}

.day-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.day-date {
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 8px;
}

.day-status {
    font-size: 13px;
    padding: 4px 8px;
    border-radius: 20px;
    display: inline-block;
    margin-bottom: 5px;
}

.status-present {
    background: #d1fae5;
    color: #059669;
}

.status-absent {
    background: #fee2e2;
    color: #dc2626;
}

.status-leave {
    background: #fed7aa;
    color: #d97706;
}

.status-weekend {
    background: #f3f4f6;
    color: #6b7280;
}

.status-short_attendance {
    background: #fef3c7;
    color: #d97706;
}

.status-unapproved_leave {
    background: #fee2e2;
    color: #dc2626;
}

.day-select {
    width: 100%;
    padding: 5px;
    font-size: 12px;
    border-radius: 6px;
    border: 1px solid var(--border-color);
    margin-top: 5px;
}

.day-select:focus {
    outline: none;
    border-color: var(--primary-color);
}

/* Check Times Styling */
.check-times {
    margin-top: 8px;
    padding: 8px;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 6px;
    border: 1px solid var(--border-color);
}

.check-time-item {
    font-size: 13px;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.check-time-item:last-child {
    margin-bottom: 0;
}

.check-time-item i {
    width: 12px;
    font-size: 10px;
}

.check-time-item strong {
    font-weight: 600;
    color: #374151;
}

/* Fixed Bulk Actions Button */
.bulk-actions {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
    display: flex;
    gap: 10px;
}

.bulk-actions .btn {
    padding: 12px 24px;
    border-radius: 50px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    font-size: 16px;
    font-weight: 600;
}

/* Loader Styles */
.loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    backdrop-filter: blur(3px);
}

.loader-overlay.active {
    display: flex;
}

.loader-content {
    text-align: center;
    background: white;
    padding: 30px 40px;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid var(--border-color);
    border-top: 4px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 15px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loader-content p {
    margin: 0;
    color: var(--text-dark);
    font-weight: 500;
    font-size: 14px;
}

.loader-content .loader-text {
    color: var(--text-muted);
    font-size: 12px;
    margin-top: 5px;
}

/* Skeleton Loader for Cards */
.skeleton-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.skeleton-card {
    background: white;
    border-radius: 15px;
    border: 2px solid var(--border-color);
    overflow: hidden;
    animation: pulse 1.5s ease-in-out infinite;
}

.skeleton-header {
    padding: 20px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-bottom: 2px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.skeleton-avatar {
    width: 50px;
    height: 50px;
    background: var(--border-color);
    border-radius: 12px;
}

.skeleton-text {
    background: var(--border-color);
    height: 15px;
    border-radius: 5px;
    margin: 5px 0;
}

.skeleton-text.title {
    width: 200px;
    height: 20px;
}

.skeleton-text.subtitle {
    width: 150px;
}

.skeleton-body {
    padding: 20px;
}

.skeleton-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.skeleton-stat {
    background: var(--bg-light);
    padding: 12px;
    border-radius: 10px;
}

.skeleton-stat-value {
    background: var(--border-color);
    height: 24px;
    width: 60%;
    margin: 0 auto 5px;
    border-radius: 5px;
}

.skeleton-stat-label {
    background: var(--border-color);
    height: 12px;
    width: 80%;
    margin: 0 auto;
    border-radius: 5px;
}

.skeleton-progress {
    background: var(--border-color);
    height: 8px;
    border-radius: 10px;
    margin: 15px 0;
}

.skeleton-footer {
    padding: 15px 20px;
    background: #fafbff;
    border-top: 2px solid var(--border-color);
    display: flex;
    justify-content: flex-end;
}

.skeleton-button {
    background: var(--border-color);
    width: 150px;
    height: 40px;
    border-radius: 10px;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.6; }
    100% { opacity: 1; }
}

/* Month Badge Styling */
.month-badge {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.month-badge i {
    font-size: 10px;
}

.employee-details h4 {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 5px;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        text-align: center;
    }

    .card-header {
        flex-direction: column;
        text-align: center;
    }

    .employee-info {
        flex-direction: column;
    }

    .attendance-stats {
        grid-template-columns: repeat(2, 1fr);
    }

    .card-footer {
        flex-direction: column;
    }

    .btn-review {
        width: 100%;
        justify-content: center;
    }

    .bulk-actions {
        bottom: 20px;
        right: 20px;
    }

    .bulk-actions .btn {
        padding: 10px 20px;
        font-size: 14px;
    }

    .selection-bar {
        flex-direction: column;
        text-align: center;
        position: relative;
        top: auto;
    }

    .daily-attendance-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 8px;
    }

    .day-card {
        min-height: 110px;
        padding: 10px;
    }

    .check-times {
        padding: 6px;
    }

    .check-time-item {
        font-size: 10px;
    }

    .skeleton-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Print Styles */
@media print {
    .page-header .btn-light,
    .filters-section,
    .selection-bar,
    .bulk-actions,
    .btn-review,
    .btn-primary-custom,
    .btn-info-custom,
    .btn-success-custom,
    .btn-outline,
    .btn-reset,
    .modal,
    .modal-backdrop,
    .loader-overlay,
    button,
    .btn,
    [onclick],
    .card-footer .btn-review,
    .filter-actions,
    .period-badge::before,
    .page-header > div:first-child {
        display: none !important;
    }

    body {
        background: white;
        padding: 0;
        margin: 0;
    }

    .container-fluid {
        padding: 0 !important;
        margin: 0 !important;
        max-width: 100% !important;
    }

    .employees-container {
        margin: 0 !important;
        padding: 0 !important;
        gap: 15px !important;
    }

    .employee-card {
        break-inside: avoid;
        page-break-inside: avoid;
        border: 1px solid #ddd !important;
        margin-bottom: 15px !important;
        box-shadow: none !important;
    }

    .employee-card:hover {
        transform: none !important;
    }

    .card-header {
        background: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .status-badge {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .status-pending {
        background: #fef3c7 !important;
        color: #d97706 !important;
    }

    .status-finalized {
        background: #d1fae5 !important;
        color: #059669 !important;
    }

    .attendance-stats .stat-item {
        background: var(--bg-light) !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .progress-bar-custom {
        background: var(--border-color) !important;
    }

    .progress-fill {
        background: var(--primary-gradient) !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .page-header {
        background: var(--primary-gradient) !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        margin-bottom: 20px !important;
        padding: 15px !important;
    }

    .page-header h1 {
        color: white !important;
        font-size: 20px !important;
    }

    .period-badge {
        background: var(--warning-gradient) !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        color: white !important;
        margin: 10px 0 !important;
    }

    .py-4 {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    .mb-4 {
        margin-bottom: 15px !important;
    }

    .card-body {
        padding: 15px !important;
    }

    .card-footer {
        display: none !important;
    }

    .employee-details h4 {
        font-size: 14px !important;
    }

    .employee-details p {
        font-size: 11px !important;
    }

    .stat-value {
        font-size: 18px !important;
    }

    .stat-label {
        font-size: 10px !important;
    }

    .employee-card,
    .card-header,
    .stat-item {
        box-shadow: none !important;
    }

    * {
        color: #000 !important;
    }

    .fixed-bottom,
    .sticky-top {
        position: static !important;
    }
}
</style>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-calendar-check"></i>
            Review Attendance
        </h1>
        <div>
            <button type="button" class="btn btn-light" onclick="printAttendance()">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-section">
        <form method="GET" action="{{ route('attendance.review.index') }}" id="filterForm">
            <div class="filter-grid">
                <div class="filter-group">
                    <label><i class="fas fa-calendar-alt"></i> Year</label>
                    <select name="year" class="filter-input" onchange="this.form.submit()">
                        @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label><i class="fas fa-calendar-month"></i> Month</label>
                    <select name="month" class="filter-input" onchange="this.form.submit()">
                        @foreach($months as $monthNum => $monthName)
                        <option value="{{ $monthNum }}" {{ $selectedMonth == $monthNum ? 'selected' : '' }}>
                            {{ $monthName }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label><i class="fas fa-building"></i> Department</label>
                    <select name="department_id" class="filter-input" onchange="this.form.submit()">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                        <option value="{{ $dept->department_id }}"
                            {{ $departmentId == $dept->department_id ? 'selected' : '' }}>
                            {{ $dept->department }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label><i class="fas fa-user"></i> Employee</label>
                    <select name="employee_id" class="filter-input" onchange="this.form.submit()">
                        <option value="">All Employees</option>
                        @foreach($allEmployees as $emp)
                        <option value="{{ $emp->employee_id }}"
                            {{ $employeeId == $emp->employee_id ? 'selected' : '' }}>
                            {{ $emp->name }} ({{ $emp->employee_code }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label><i class="fas fa-filter"></i> Status</label>
                    <select name="review_status" class="filter-input" onchange="this.form.submit()">
                        <option value="all" {{ $reviewStatus == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ $reviewStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="finalized" {{ $reviewStatus == 'finalized' ? 'selected' : '' }}>Finalized</option>
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <a href="{{ route('attendance.review.index') }}" class="btn-reset">
                    <i class="fas fa-undo-alt"></i> Reset Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Period Indicator -->
    <div class="text-center mb-4">
        <div class="period-badge">
            <i class="fas fa-calendar-alt"></i>
            {{ Carbon\Carbon::create($selectedYear, $selectedMonth)->format('F Y') }}
        </div>
    </div>

    <!-- Selection Bar - Only show for pending employees -->
    @if($hasPendingEmployees && $reviewStatus != 'finalized')
    <div class="selection-bar" id="selectionBar">
        <div class="selection-info">
            <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()">
            <label for="selectAllCheckbox" class="mb-0 fw-bold">Select All Employees</label>
            <span class="selection-count" id="selectedCount">0 employees selected</span>
        </div>
        <div class="selection-actions">
            <button class="btn btn-outline" onclick="clearAllSelections()">
                <i class="fas fa-times"></i> Clear Selection
            </button>
        </div>
    </div>
    @endif

    <!-- Employees List -->
    <div class="employees-container">
        @forelse($reviewSummaries as $data)
        @php
        $employee = $data['employee'];
        $attendance = $data['attendance'];
        $review = $data['review'];
        $status = $data['review_status'];
        @endphp

        <div class="employee-card {{ $status == 'finalized' ? 'finalized-card' : '' }}"
            data-employee-id="{{ $employee->employee_id }}" data-status="{{ $status }}">
            <div class="card-header">
                <div class="employee-info">
                    @if($status == 'pending')
                    <input type="checkbox" class="employee-checkbox" data-employee-id="{{ $employee->employee_id }}"
                        onchange="updateSelection()">
                    @endif
                    <div class="employee-avatar">
                        {{ strtoupper(substr($employee->name, 0, 2)) }}
                    </div>
                    <div class="employee-details">
                        <h4>
                            {{ $employee->name }}
                            <span class="month-badge">
                                <i class="fas fa-calendar-alt"></i>
                                {{ Carbon\Carbon::create($selectedYear, $selectedMonth)->format('F Y') }}
                            </span>
                        </h4>
                        <p>
                            <i class="fas fa-building me-1"></i> {{ $employee->department_name ?? 'No Department' }} |
                            <i class="fas fa-id-card me-1"></i> {{ $employee->employee_code }}
                        </p>
                        @if($attendance['shift_info'])
                        <div class="shift-info">
                            <i class="fas fa-clock"></i> Shift: {{ $attendance['shift_info']['shift_name'] }} |
                            <i class="fas fa-calendar-week"></i> Weekly Off:
                            <span class="weekly-off-badge">{{ $attendance['shift_info']['weekly_offs'] }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                <div>
                    <span class="status-badge status-{{ $status }}">
                        <i class="fas {{ $status == 'pending' ? 'fa-clock' : 'fa-check-circle' }} me-1"></i>
                        {{ ucfirst($status) }}
                    </span>
                    @if($status == 'finalized')
                    <span class="view-only-badge">
                        <i class="fas fa-eye"></i> View Only
                    </span>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <div class="attendance-stats">
                    <div class="stat-item">
                        <div class="stat-value present">{{ $attendance['present_days'] }}</div>
                        <div class="stat-label">Present</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value absent">
                            {{ $attendance['absent_days'] + ($attendance['unapproved_leave_days'] ?? 0) }}
                        </div>
                        <div class="stat-label">Absent</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value leave">
                            {{ $attendance['leave_instances_count'] ?? $attendance['leave_days'] }}
                            <div class="stat-label">Leave</div>

                            <!-- Leave Balance Summary -->
                            @if(isset($attendance['leave_summary']) && !empty($attendance['leave_summary']))
                            <div class="leave-balance-compact">
                                @php
                                $leaveItems = [];
                                foreach($attendance['leave_summary'] as $leaveType => $data) {
                                $taken = $data['taken'];
                                $displayTaken = $taken == intval($taken) ? intval($taken) : number_format($taken, 1);
                                $leaveItems[] = $leaveType . ': ' . $displayTaken;
                                }
                                @endphp
                                <div class="leave-balance-item">
                                    <span class="balance-numbers">{{ implode(', ', $leaveItems) }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value weekend">{{ $attendance['weekend_days'] }}</div>
                        <div class="stat-label">Week Off</div>
                    </div>
                </div>
                <div class="progress-bar-custom">
                    <div class="progress-fill" style="width: {{ $attendance['attendance_percentage'] }}%"></div>
                </div>
                <div class="text-center">
                    <small class="text-muted">Attendance Rate: {{ $attendance['attendance_percentage'] }}%</small>
                </div>
            </div>

            <div class="card-footer">
                <div>
                    @if($status == 'finalized' && $review)
                    @if($review->finalize_notes)
                    <small class="text-muted">
                        <i class="fas fa-sticky-note"></i> Notes: {{ $review->finalize_notes }}
                    </small>
                    <br>
                    @endif
                    <small class="text-success">
                        <i class="fas fa-check-circle"></i> Finalized on
                        {{ \Carbon\Carbon::parse($review->finalized_at)->format('d M Y h:i A') }}
                    </small>
                    @endif
                </div>
                <div>
                    @if($status == 'pending')
                    <button class="btn-review btn-primary-custom"
                        onclick="openFinalizePage('{{ $employee->employee_id }}')">
                        <i class="fas fa-edit"></i> Review & Finalize
                    </button>
                    @endif
                    @if($status == 'finalized')
                    <button class="btn-review btn-success-custom me-2"
                        onclick="viewFinalizedAttendance('{{ $employee->employee_id }}')">
                        <i class="fas fa-eye"></i> View Attendance
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="fas fa-users display-1 text-muted mb-3"></i>
            <h4>No employees found</h4>
            <p class="text-muted">No employees match the selected filters for
                {{ Carbon\Carbon::create($selectedYear, $selectedMonth)->format('F Y') }}</p>
            <a href="{{ route('attendance.review.index') }}" class="btn btn-primary mt-3">
                <i class="fas fa-undo-alt"></i> Reset Filters
            </a>
        </div>
        @endforelse
    </div>
</div>

<!-- Loader Overlay -->
<div id="loaderOverlay" class="loader-overlay">
    <div class="loader-content">
        <div class="spinner"></div>
        <p>Loading attendance data...</p>
        <p class="loader-text">Please wait while we fetch the records</p>
    </div>
</div>

<!-- View Finalized Modal (Read-only) -->
<div class="modal fade" id="viewFinalizedModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>
                    Finalized Attendance - <span id="viewModalEmployeeName"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewFinalizedModalBody">
                <!-- Dynamic content -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Fixed Bulk Actions Button -->
@if($hasPendingEmployees && $reviewStatus != 'finalized')
<div class="bulk-actions">
    <button class="btn btn-success-custom" onclick="bulkFinalize()">
        <i class="fas fa-lock"></i> Finalize Selected (<span id="floatingSelectedCount">0</span>)
    </button>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ========== HELPER FUNCTIONS ==========

function getLeaveIcon(leaveType) {
    const icons = {
        'Short Leave': 'fa-hourglass-start',
        'Half Day': 'fa-sun',
        'Casual Leave': 'fa-coffee',
        'Sick Leave': 'fa-thermometer-half',
        'Earned Leave': 'fa-star',
        'Unpaid Leave': 'fa-money-bill-wave',
        'Maternity Leave': 'fa-baby'
    };
    return icons[leaveType] || 'fa-calendar-alt';
}

let currentYear = @json($selectedYear);
let currentMonth = @json($selectedMonth);

// Loader functions
function showLoader() {
    const loader = document.getElementById('loaderOverlay');
    if (loader) {
        loader.classList.add('active');
    }
    const container = document.querySelector('.employees-container');
    if (container) {
        container.classList.add('loading');
    }
}

function hideLoader() {
    const loader = document.getElementById('loaderOverlay');
    if (loader) {
        loader.classList.remove('active');
    }
    const container = document.querySelector('.employees-container');
    if (container) {
        container.classList.remove('loading');
    }
}

function updateSelection() {
    const checkboxes = document.querySelectorAll('.employee-checkbox:checked');
    const selectedCount = checkboxes.length;
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const allCheckboxes = document.querySelectorAll('.employee-checkbox');
    const totalCheckboxes = allCheckboxes.length;

    const selectedCountSpan = document.getElementById('selectedCount');
    const floatingCountSpan = document.getElementById('floatingSelectedCount');

    if (selectedCountSpan) selectedCountSpan.innerText = `${selectedCount} employee(s) selected`;
    if (floatingCountSpan) floatingCountSpan.innerText = selectedCount;

    if (selectAllCheckbox) {
        if (selectedCount === totalCheckboxes && totalCheckboxes > 0) {
            selectAllCheckbox.checked = true;
            selectAllCheckbox.indeterminate = false;
        } else if (selectedCount > 0 && selectedCount < totalCheckboxes) {
            selectAllCheckbox.indeterminate = true;
        } else {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    }

    document.querySelectorAll('.employee-card').forEach(card => {
        const checkbox = card.querySelector('.employee-checkbox');
        if (checkbox && checkbox.checked) {
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
    });
}

function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    if (!selectAllCheckbox) return;

    const isChecked = selectAllCheckbox.checked;
    const allCheckboxes = document.querySelectorAll('.employee-checkbox');

    allCheckboxes.forEach(checkbox => {
        checkbox.checked = isChecked;
    });

    updateSelection();
}

function clearAllSelections() {
    const allCheckboxes = document.querySelectorAll('.employee-checkbox');
    allCheckboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelection();
}

function openFinalizePage(employeeId) {
    window.location.href = `/attendance/review/${employeeId}?year=${currentYear}&month=${currentMonth}`;
}

function viewFinalizedAttendance(employeeId) {
    fetch(`/attendance/review/finalized-details?employee_id=${employeeId}&year=${currentYear}&month=${currentMonth}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showFinalizedAttendanceModal(data.data);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Error loading finalized attendance',
                    confirmButtonColor: '#4361ee'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Error loading finalized attendance',
                confirmButtonColor: '#4361ee'
            });
        });
}

function showFinalizedAttendanceModal(attendanceData) {
    let dailyAttendanceHtml = '';

    Object.entries(attendanceData.attendance_details).forEach(([date, details]) => {
        const formattedDate = new Date(date).toLocaleDateString('en-US', {
            weekday: 'short',
            month: 'short',
            day: 'numeric'
        });

        const isWeekend = details.status === 'weekend';
        const isShortLeaveOrHalfDay = details.leave_type && (details.leave_type === 'Short Leave' || details.leave_type === 'Half Day');
        const isApproved = details.is_approved === true;

        dailyAttendanceHtml += `
            <div class="day-card">
                <div class="day-date">${formattedDate}</div>
                <div class="day-status status-${details.status}">${details.status_text}</div>
                ${!isWeekend ? `
                    ${isShortLeaveOrHalfDay ? `
                    <div class="mt-2 p-2" style="background: #f8fafc; border-radius: 8px;">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" 
                                   ${isApproved ? 'checked' : ''} disabled>
                            <label class="form-check-label">
                                <strong>${details.leave_type}</strong> - 
                                <span class="${isApproved ? 'text-success' : 'text-danger'}">
                                    ${isApproved ? 'Approved' : 'Unapproved'}
                                </span>
                            </label>
                        </div>
                    </div>
                    ` : ''}
                    
                    <div class="dropdown mt-2">
                        <button class="btn btn-sm btn-outline-secondary w-100 dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 12px;">
                            <i class="fas fa-info-circle"></i> View Details
                        </button>
                        <div class="dropdown-menu p-3" style="min-width: 280px; font-size: 12px;">
                            <div class="check-times">
                                ${details.check_logs && details.check_logs.length > 0 ? details.check_logs.map(log => 
                                    `<div class="check-time-item mb-2">
                                        <i class="fas fa-${log.type === 'IN' ? 'sign-in-alt text-success' : 'sign-out-alt text-danger'}"></i> 
                                        <strong>${log.type === 'IN' ? 'Check-in' : 'Check-out'}:</strong> ${log.time}
                                    </div>`
                                ).join('') : '<div class="text-muted mb-2"><i class="fas fa-clock"></i> No check-in/out records</div>'}
                                
                                ${details.total_hours ? `
                                <div class="check-time-item mb-2">
                                    <i class="fas fa-clock text-info"></i> 
                                    <strong>Total Hours:</strong> ${details.total_hours}h
                                </div>` : ''}
                                
                                ${details.required_hours ? `
                                <div class="check-time-item mb-2">
                                    <i class="fas fa-hourglass-half text-warning"></i> 
                                    <strong>Required Hours:</strong> ${details.required_hours}h
                                </div>` : ''}
                            </div>
                        </div>
                    </div>
                ` : `
                    <input type="hidden" data-date="${date}" value="weekend">
                `}
            </div>
        `;
    });

    const modalContent = `
        <div class="mb-3 p-3 bg-light rounded">
            <div class="row">
                <div class="col-md-4">
                    <strong>Employee:</strong> ${attendanceData.employee_name}<br>
                    <strong>Department:</strong> ${attendanceData.department}
                </div>
                <div class="col-md-4">
                    <strong>Shift:</strong> ${attendanceData.shift_info?.shift_name || 'N/A'}<br>
                    <strong>Weekly Offs:</strong> ${attendanceData.shift_info?.weekly_offs || 'Sunday'}
                </div>
                <div class="col-md-4">
                    <strong>Finalized On:</strong> ${attendanceData.finalized_at}<br>
                    <strong>Finalized By:</strong> ${attendanceData.finalized_by || 'System'}
                </div>
            </div>
            ${attendanceData.finalize_notes ? `
            <div class="row mt-2">
                <div class="col-12">
                    <strong>Notes:</strong> ${attendanceData.finalize_notes}
                </div>
            </div>
            ` : ''}
            <div class="row mt-2">
                <div class="col-12">
                    <strong>Leave Breakdown:</strong>
                    ${attendanceData.leave_counts ? Object.entries(attendanceData.leave_counts).map(([type, count]) => 
                        `<span class="leave-detail-badge ${type.toLowerCase().replace(' ', '-')}"> ${type}: ${count}</span>`
                    ).join('') : ''}
                </div>
            </div>
        </div>
        
        <div class="daily-attendance-grid">
            ${dailyAttendanceHtml}
        </div>
        
        <div class="mt-3 p-3 bg-light rounded">
            <div class="row">
                <div class="col-md-3">
                    <strong>Present:</strong> ${attendanceData.present_days} days
                </div>
                <div class="col-md-3">
                    <strong>Absent:</strong> ${attendanceData.absent_days} days
                </div>
                <div class="col-md-3">
                    <strong>Leave:</strong> ${attendanceData.leave_days} days
                </div>
                <div class="col-md-3">
                    <strong>Working Days:</strong> ${attendanceData.working_days} days
                </div>
            </div>
            <div class="mt-2">
                <strong>Attendance Rate: ${attendanceData.attendance_percentage}%</strong>
                <div class="progress-bar-custom mt-2">
                    <div class="progress-fill" style="width: ${attendanceData.attendance_percentage}%"></div>
                </div>
            </div>
        </div>
    `;

    document.getElementById('viewFinalizedModalBody').innerHTML = modalContent;
    document.getElementById('viewModalEmployeeName').textContent = attendanceData.employee_name;

    const modal = new bootstrap.Modal(document.getElementById('viewFinalizedModal'));
    modal.show();
}

function bulkFinalize() {
    const selectedCheckboxes = document.querySelectorAll('.employee-checkbox:checked');

    if (selectedCheckboxes.length === 0) {
        Swal.fire({
            icon: 'info',
            title: 'No Selection',
            text: 'Please select at least one employee to finalize.',
            confirmButtonColor: '#4361ee'
        });
        return;
    }

    const employeeIds = Array.from(selectedCheckboxes).map(cb => cb.dataset.employeeId);

    Swal.fire({
        title: 'Finalize Selected Attendance',
        html: `
            <div class="text-left">
                <p>You are about to finalize attendance for <strong>${employeeIds.length}</strong> employee(s).</p>
                <p class="text-muted">This action will:</p>
                <ul class="text-muted small text-left">
                    <li>Lock attendance records for all selected employees</li>
                    <li>Apply deduction rules based on leave approval status</li>
                    <li>Mark them as ready for payroll processing</li>
                    <li>Cannot be undone after finalization</li>
                </ul>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#dc2626',
        confirmButtonText: 'Yes, Finalize Selected',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Finalizing...',
                text: 'Please wait while we finalize the attendance',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('/attendance/review/bulk', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        employee_ids: employeeIds,
                        year: currentYear,
                        month: currentMonth,
                        action: 'finalize_all'
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Finalized!',
                            text: `Successfully finalized ${data.finalized_count || employeeIds.length} employee(s)`,
                            confirmButtonColor: '#10b981',
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Error performing bulk finalize',
                            confirmButtonColor: '#4361ee'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Error performing bulk finalize',
                        confirmButtonColor: '#4361ee'
                    });
                });
        }
    });
}

// Print function for attendance
function printAttendance() {
    const originalTitle = document.title;
    document.title = 'Attendance Report - ' + currentYear + '-' + currentMonth;

    const printContent = document.querySelector('.employees-container').cloneNode(true);
    const pageHeader = document.querySelector('.page-header').cloneNode(true);
    const periodBadge = document.querySelector('.period-badge').cloneNode(true);

    const printWrapper = document.createElement('div');
    printWrapper.className = 'print-wrapper';
    printWrapper.style.padding = '20px';
    printWrapper.style.background = 'white';

    const titleSection = document.createElement('div');
    titleSection.style.textAlign = 'center';
    titleSection.style.marginBottom = '20px';
    titleSection.style.padding = '10px';
    titleSection.style.borderBottom = '2px solid #ddd';
    titleSection.innerHTML = `
        <h2 style="margin: 0; color: #333;">Attendance Report</h2>
        <p style="margin: 5px 0 0 0; color: #666;">Generated on: ${new Date().toLocaleString()}</p>
        <p style="margin: 5px 0 0 0; color: #666;">Period: ${periodBadge ? periodBadge.innerText : ''}</p>
    `;

    const cleanHeader = pageHeader.cloneNode(true);
    const buttonsToRemove = cleanHeader.querySelectorAll('.btn-light, button');
    buttonsToRemove.forEach(btn => btn.remove());

    const allButtons = printContent.querySelectorAll('.btn-review, .employee-checkbox, .card-footer .btn-review');
    allButtons.forEach(btn => btn.remove());

    const checkboxes = printContent.querySelectorAll('.employee-checkbox');
    checkboxes.forEach(cb => cb.remove());

    const selectedCards = printContent.querySelectorAll('.employee-card.selected');
    selectedCards.forEach(card => card.classList.remove('selected'));

    printWrapper.appendChild(titleSection);
    printWrapper.appendChild(cleanHeader);
    printWrapper.appendChild(printContent);

    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Attendance Report</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
            <style>
                body { font-family: Arial, Helvetica, sans-serif; padding: 20px; background: white; }
                .print-wrapper { max-width: 1200px; margin: 0 auto; }
                .employee-card { border: 1px solid #ddd; border-radius: 10px; margin-bottom: 20px; page-break-inside: avoid; break-inside: avoid; }
                .card-header { background: #f8f9fa; padding: 15px; border-bottom: 1px solid #ddd; display: flex; justify-content: space-between; align-items: center; }
                .employee-info { display: flex; align-items: center; gap: 15px; }
                .employee-avatar { width: 50px; height: 50px; background: linear-gradient(135deg, #4361ee, #3a0ca3); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; }
                .attendance-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; padding: 15px; }
                .stat-item { text-align: center; padding: 10px; background: #f8fafc; border-radius: 8px; }
                .stat-value { font-size: 24px; font-weight: bold; }
                .stat-value.present { color: #059669; }
                .stat-value.absent { color: #dc2626; }
                .stat-value.leave { color: #d97706; }
                .stat-value.weekend { color: #6b7280; }
                .stat-label { font-size: 12px; color: #666; }
                .progress-bar-custom { height: 8px; background: #e2e8f0; border-radius: 10px; margin: 0 15px 15px 15px; overflow: hidden; }
                .progress-fill { height: 100%; background: linear-gradient(135deg, #4361ee, #3a0ca3); border-radius: 10px; }
                .text-center { text-align: center; margin-bottom: 15px; }
                .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
                .status-pending { background: #fef3c7; color: #d97706; }
                .status-finalized { background: #d1fae5; color: #059669; }
                .period-badge { background: linear-gradient(135deg, #f59e0b, #d97706); padding: 8px 16px; border-radius: 20px; display: inline-block; color: white; }
                @media print {
                    body { padding: 0; margin: 0; }
                    .btn, button, .no-print { display: none !important; }
                    .employee-card { break-inside: avoid; page-break-inside: avoid; }
                }
            </style>
        </head>
        <body>
            ${printWrapper.outerHTML}
            <script>
                window.onload = function() {
                    window.print();
                    setTimeout(function() { window.close(); }, 1000);
                };
            <\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
    document.title = originalTitle;
}

// Initialize loader on filter changes
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            showLoader();
        });
    }

    const filterInputs = document.querySelectorAll('.filter-input');
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.form) {
                showLoader();
                this.form.submit();
            }
        });
    });

    const resetBtn = document.querySelector('.btn-reset');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            showLoader();
        });
    }

    window.addEventListener('load', function() {
        setTimeout(() => {
            hideLoader();
        }, 300);
    });

    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            hideLoader();
        }
    });

    updateSelection();
});

// Make functions available globally
window.viewFinalizedAttendance = viewFinalizedAttendance;
window.bulkFinalize = bulkFinalize;
window.updateSelection = updateSelection;
window.toggleSelectAll = toggleSelectAll;
window.clearAllSelections = clearAllSelections;
window.openFinalizePage = openFinalizePage;
window.printAttendance = printAttendance;
</script>
@endsection