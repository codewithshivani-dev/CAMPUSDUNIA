@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
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
    --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    --hover-shadow: 0 15px 40px rgba(67, 97, 238, 0.12);
}

body {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.container-fluid {
    animation: fadeIn 0.5s ease;
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

/* Header Section */
.page-header {
    background: var(--primary-gradient);
    border-radius: 20px;
    padding: 20px 30px;
    margin-bottom: 30px;
    position: relative;
    overflow: hidden;
    box-shadow: var(--card-shadow);
}

.page-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 250px;
    height: 250px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 50%;
}

.page-header::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -5%;
    width: 180px;
    height: 180px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
}

.header-title h2 {
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
    margin: 0 0 0.5rem 0;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}

.header-title h2 i {
    margin-right: 10px;
}

.header-title p {
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
    font-size: 0.9rem;
}

.btn-outline-light {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    backdrop-filter: blur(10px);
    transition: all 0.3s;
    border-radius: 12px;
    padding: 10px 24px;
    font-weight: 600;
}

.btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    color: white;
}

.btn-outline-light i {
    margin-right: 8px;
}

/* Filter Section - Small search and reset buttons */
.filter-section {
    background: white;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: var(--card-shadow);
    border: 1px solid #e2e8f0;
    display: none;
}

.filter-section .form-label {
    font-weight: 600;
    font-size: 0.85rem;
    color: #475569;
    margin-bottom: 5px;
}

.filter-section .form-control,
.filter-section .form-select {
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    padding: 8px 15px;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.filter-section .form-control:focus,
.filter-section .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.filter-section .btn-primary {
    background: var(--primary-gradient);
    border: none;
    border-radius: 10px;
    padding: 8px 20px;
    font-weight: 600;
    font-size: 0.9rem;
}

.filter-section .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--accent-glow);
}

.filter-section .btn-secondary {
    border-radius: 10px;
    padding: 8px 16px;
    font-weight: 600;
    font-size: 0.9rem;
}

/* Current Shift Card */
#currentShiftSection {
    display: block;
}

/* Shift Schedule Section */
#shiftScheduleSection {
    display: none;
}

.schedule-header {
    background: white;
    border-radius: 16px;
    padding: 20px 25px;
    margin-bottom: 25px;
    box-shadow: var(--card-shadow);
    border: 1px solid #e2e8f0;
}

.schedule-header h4 {
    color: var(--primary-color);
    font-weight: 700;
    margin: 0;
}

.schedule-header .schedule-subtitle {
    color: #64748b;
    font-size: 0.9rem;
    margin-top: 5px;
}

.schedule-header .schedule-stats {
    color: #94a3b8;
    font-size: 0.85rem;
}

/* Shift Card */
.shift-card {
    border-radius: 24px;
    overflow: hidden;
    box-shadow: var(--card-shadow);
    transition: all 0.3s ease;
    border: none;
    background: white;
    margin-bottom: 20px;
}

.shift-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--hover-shadow);
}

.shift-card .card-header-custom {
    background: var(--primary-gradient);
    padding: 18px 25px;
    border-bottom: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.shift-card .card-header-custom h5 {
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0;
    color: white;
}

.shift-card .card-header-custom h5 i {
    margin-right: 10px;
}

.shift-card .card-header-custom .badge {
    font-size: 0.7rem;
    padding: 4px 12px;
}

.shift-card .card-body {
    padding: 25px;
}

/* Badge Styles */
.badge-priority {
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-priority-light {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.badge-active {
    background: var(--success-gradient);
    color: white;
}

.badge-inactive {
    background: linear-gradient(135deg, #64748b, #475569);
    color: white;
}

.badge-upcoming {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
}

.badge-completed {
    background: linear-gradient(135deg, #64748b, #475569);
    color: white;
}

/* WFH Status Badges */
.badge-wfh-approved {
    background: #10b981;
    color: white;
}

.badge-wfh-pending {
    background: #f59e0b;
    color: #1e293b;
}

.badge-wfh-rejected {
    background: #ef4444;
    color: white;
}

.badge-wfh {
    background: #fbbf24;
    color: #1e293b;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 0.7rem;
    font-weight: 600;
}

.badge-days {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 0.7rem;
}

/* Time Display */
.shift-time-display {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 20px;
    background: linear-gradient(135deg, #f8fafc, #ffffff);
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.shift-time-display .time-block {
    text-align: center;
    flex: 1;
}

.shift-time-display .time-block .label {
    font-size: 0.65rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #94a3b8;
    display: block;
    margin-bottom: 4px;
}

.shift-time-display .time-block .value {
    font-size: 1.3rem;
    font-weight: 800;
}

.shift-time-display .time-block .value.start {
    background: var(--success-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.shift-time-display .time-block .value.end {
    background: var(--danger-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.shift-time-display .time-arrow {
    color: #94a3b8;
    font-size: 1.5rem;
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.info-item {
    background: linear-gradient(135deg, #f8fafc, #ffffff);
    border-radius: 12px;
    padding: 12px 15px;
    text-align: center;
    border: 1px solid #e2e8f0;
}

.info-item .label {
    font-size: 0.6rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #94a3b8;
    display: block;
    margin-bottom: 4px;
}

.info-item .value {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
}

.info-item .value i {
    margin-right: 5px;
}

/* WFH Card */
.wfh-card {
    border-left: 4px solid #f59e0b;
}

.wfh-card .card-header-custom {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

/* .wfh-card-approved {
    border-left: 4px solid #10b981;
} */

.wfh-card-approved .card-header-custom {
    background: linear-gradient(135deg, #10b981, #059669);
}

/* .wfh-card-pending {
    border-left: 4px solid #f59e0b;
} */

.wfh-card-pending .card-header-custom {
    background: linear-gradient(135deg, #f59e0b, #d97706);
}

/* .wfh-card-rejected {
    border-left: 4px solid #ef4444;
} */

.wfh-card-rejected .card-header-custom {
    background: linear-gradient(135deg, #ef4444, #dc2626);
}

/* Combined WFH Info Box */
.wfh-info-box {
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 18px;
    border: 1px solid;
}

.wfh-info-box-approved {
    background: #ecfdf5;
    border-color: #10b981;
}

.wfh-info-box-pending {
    background: #fffbeb;
    border-color: #f59e0b;
}

.wfh-info-box-rejected {
    background: #fef2f2;
    border-color: #ef4444;
}

.wfh-info-box .wfh-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.wfh-info-box .wfh-header i {
    font-size: 1.5rem;
}

.wfh-info-box .wfh-header .wfh-title {
    font-weight: 700;
    font-size: 1rem;
    margin: 0;
}

.wfh-info-box .wfh-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px 20px;
    font-size: 0.85rem;
}

.wfh-info-box .wfh-details .detail-item {
    display: flex;
    justify-content: space-between;
    padding: 3px 0;
    border-bottom: 1px dashed #e2e8f0;
}

.wfh-info-box .wfh-details .detail-item:last-child {
    border-bottom: none;
}

.wfh-info-box .wfh-details .detail-item .label {
    color: #64748b;
    font-weight: 500;
}

.wfh-info-box .wfh-details .detail-item .value {
    color: #1e293b;
    font-weight: 600;
}

.wfh-info-box-approved .wfh-header i {
    color: #10b981;
}

.wfh-info-box-approved .wfh-header .wfh-title {
    color: #065f46;
}

.wfh-info-box-pending .wfh-header i {
    color: #f59e0b;
}

.wfh-info-box-pending .wfh-header .wfh-title {
    color: #92400e;
}

.wfh-info-box-rejected .wfh-header i {
    color: #ef4444;
}

.wfh-info-box-rejected .wfh-header .wfh-title {
    color: #991b1b;
}

@media (max-width: 768px) {
    .wfh-info-box .wfh-details {
        grid-template-columns: 1fr;
    }
}

/* Holiday Card */
.holiday-card {
    background: var(--primary-gradient) !important;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: var(--card-shadow);
    position: relative;
}

.holiday-card::before {
    content: '';
    position: absolute;
    top: -30%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
    border-radius: 50%;
}

.holiday-card .card-body {
    position: relative;
    z-index: 1;
    padding: 3rem;
}

.holiday-card i {
    font-size: 4rem;
    margin-bottom: 1rem;
    color: white !important;
}

.holiday-card h3 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: white !important;
}

.holiday-card p {
    color: rgba(255, 255, 255, 0.95) !important;
}

/* No Shifts Found */
.no-shifts-found {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 24px;
    box-shadow: var(--card-shadow);
}

.no-shifts-found i {
    font-size: 1rem;
    color: #cbd5e1;
    margin-bottom: 20px;
}

.no-shifts-found h4 {
    color: #475569;
    font-weight: 600;
    margin-bottom: 10px;
}

.no-shifts-found p {
    color: #94a3b8;
    max-width: 400px;
    margin: 0 auto 20px;
}

/* Period Alert */
.period-alert {
    background: linear-gradient(135deg, #eef2ff, #e0e7ff);
    border: none;
    border-radius: 12px;
    padding: 12px 18px;
    margin-top: 15px;
    border-left: 4px solid var(--primary-color);
}

.period-alert i {
    margin-right: 8px;
    color: var(--primary-color);
}

/* Modal Styling */
.modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
    box-shadow: var(--hover-shadow);
}

.modal-header {
    background: var(--primary-gradient);
    border-bottom: none;
    padding: 20px 25px;
}

.modal-header .modal-title {
    color: white;
    font-weight: 700;
}

.modal-close-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.modal-close-btn:hover {
    background: var(--danger-gradient);
    transform: rotate(90deg);
}

.modal-body {
    padding: 25px;
    background: linear-gradient(135deg, #f8fafc, #ffffff);
}

.modal-footer {
    border-top: 1px solid #e2e8f0;
    padding: 20px 25px;
    background: white;
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 0;
}

.timeline-item {
    position: relative;
    padding-left: 30px;
    margin-bottom: 20px;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: 0;
    top: 20px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
}

.timeline-item .card {
    border-radius: 16px;
    border: 1px solid #e2e8f0;
}

/* Spinner */
.spinner-border {
    border-width: 3px;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }

    .header-title h2 {
        font-size: 1.3rem;
    }

    .shift-time-display {
        flex-direction: column;
        gap: 10px;
    }

    .shift-time-display .time-arrow {
        transform: rotate(90deg);
    }

    .info-grid {
        grid-template-columns: 1fr 1fr;
    }

    .filter-section .row>div {
        margin-bottom: 10px;
    }

    .schedule-header {
        text-align: center;
    }

    .wfh-info-box .wfh-details {
        grid-template-columns: 1fr;
    }
}

/* ============================================ */
/* FIX: Compact Card Header - Prevent Extra Space */
/* ============================================ */

.shift-card .card-header-custom {
    padding: 12px 20px !important;
    min-height: auto !important;
    gap: 6px 12px !important;
}

.shift-card .card-header-custom .header-left {
    flex: 0 1 auto !important;
    min-width: auto !important;
}

.shift-card .card-header-custom .header-left h5 {
    font-size: 1rem !important;
    gap: 4px !important;
    margin: 0 !important;
    line-height: 1.3 !important;
}

.shift-card .card-header-custom .header-left small {
    font-size: 0.7rem !important;
    line-height: 1.2 !important;
}

.shift-card .card-header-custom .header-right {
    display: flex !important;
    align-items: center !important;
    gap: 4px !important;
    flex-wrap: nowrap !important;
    flex-shrink: 0 !important;
}

.shift-card .card-header-custom .badge-priority,
.shift-card .card-header-custom .badge {
    padding: 2px 8px !important;
    font-size: 0.6rem !important;
    line-height: 1.2 !important;
    min-height: 20px !important;
    border-radius: 20px !important;
    font-weight: 500 !important;
    letter-spacing: 0.3px !important;
}

.shift-card .card-header-custom .badge-priority i,
.shift-card .card-header-custom .badge i {
    font-size: 0.55rem !important;
    margin-right: 3px !important;
}

/* Specific fix for the "Fixed Working Hours" badge */
.shift-card .card-header-custom .badge-priority:last-child {
    background: rgba(255, 255, 255, 0.15) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
}

/* Remove extra margin/padding from badge wrapper if exists */
.shift-card .card-header-custom .badge-wrapper {
    display: inline-flex !important;
    align-items: center !important;
    gap: 4px !important;
}
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
        <div class="header-title">
            <h2 id="pageTitle">
                <i class="bi bi-person-badge"></i>
                My Shift - {{ \Carbon\Carbon::parse($today)->format('M d, Y') }}
            </h2>
            <p id="pageSubtitle">
                View your current shift
            </p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-outline-light" onclick="toggleFilter()">
                <i class="bi bi-funnel"></i> Filter
            </button>
            <button class="btn btn-outline-light" onclick="loadUpcomingShifts()">
                <i class="bi bi-calendar-event"></i> Upcoming
            </button>
            <button class="btn btn-outline-light" onclick="loadShiftHistory()">
                <i class="bi bi-clock-history"></i> History
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section" id="filterSection">
        <div class="row align-items-end">
            <div class="col-md-3">
                <label class="form-label">
                    <i class="bi bi-calendar3 me-1"></i> From Date
                </label>
                <input type="date" class="form-control" id="filterStartDate" value="{{ $startDate ?? date('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">
                    <i class="bi bi-calendar3 me-1"></i> To Date
                </label>
                <input type="date" class="form-control" id="filterEndDate"
                    value="{{ $endDate ?? date('Y-m-d', strtotime('+7 days')) }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">
                    <i class="bi bi-funnel me-1"></i> Status
                </label>
                <select class="form-select" id="filterStatus">
                    <option value="all">All Shifts</option>
                    <option value="active">Active</option>
                    <option value="upcoming">Upcoming</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" onclick="applyDateFilter()">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                    <button class="btn btn-secondary" onclick="resetFilter()">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="mainContent">
        <!-- Current Shift Section -->
        <div id="currentShiftSection">
            @if($assignmentType === 'holiday')
            <!-- Holiday/No Shift Card -->
            <div class="card holiday-card shadow-lg">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h3>No Active Shift</h3>
                    <p class="mb-0">There is no shift assigned to you for today.</p>
                </div>
            </div>
            @else
            <!-- Current Shift Card -->
            @php
            $wfhStatus = $shiftDetails['wfh_status'] ?? null;
            $wfhCardClass = '';
            $wfhStatusBadge = '';
            $wfhStatusIcon = 'clock';
            $wfhStatusLabel = 'Pending';
            $wfhBoxClass = 'wfh-info-box-pending';

            if ($wfhStatus) {
            $wfhCardClass = 'wfh-card-' . $wfhStatus;
            $wfhStatusBadge = 'badge-wfh-' . $wfhStatus;
            if ($wfhStatus === 'approved') {
            $wfhStatusIcon = 'check-circle-fill';
            $wfhStatusLabel = 'Approved';
            $wfhBoxClass = 'wfh-info-box-approved';
            } elseif ($wfhStatus === 'pending') {
            $wfhStatusIcon = 'clock-fill';
            $wfhStatusLabel = 'Pending';
            $wfhBoxClass = 'wfh-info-box-pending';
            } elseif ($wfhStatus === 'rejected') {
            $wfhStatusIcon = 'x-circle-fill';
            $wfhStatusLabel = 'Rejected';
            $wfhBoxClass = 'wfh-info-box-rejected';
            }
            }
            @endphp
            <div
                class="card shift-card {{ isset($shiftDetails['is_active']) && $shiftDetails['is_active'] ? '' : 'opacity-75' }} {{ $wfhCardClass }}">
                <div class="card-header-custom">
                    <div class="header-left">
                        <h5>
                            @if(isset($shiftDetails['is_wfh']) && $shiftDetails['is_wfh'])
                            <i class="bi bi-house-door"></i>
                            Shift Name -
                            {{ $shiftDetails['shift_name'] }}
                            @else
                            <i
                                class="bi bi-{{ $shiftDetails['assignment_type'] === 'individual' ? 'person-check' : 'building' }}"></i>
                                 Shift Name -
                            {{ $shiftDetails['shift_name'] }}
                            @endif
                        </h5>
                       <small style="
                                color: white;
                            ">
                            {{ $shiftDetails['assignment_type'] === 'individual' ? 'Individual Assignment' : 'Department Assignment' }}
                            @if(isset($shiftDetails['is_wfh']) && $shiftDetails['is_wfh'])
                            @if($wfhStatus)
                            <span class="badge {{ $wfhStatusBadge }} ms-2">
                                <i
                                    class="bi bi-{{ $wfhStatus === 'approved' ? 'check-circle' : ($wfhStatus === 'pending' ? 'clock' : 'x-circle') }} me-1"></i>
                                {{ ucfirst($wfhStatus) }}
                            </span>
                            @endif
                            @endif
                        </small>
                    </div>
                    <div class="header-right">
                        <span class="badge-priority badge-priority-light">
                            Priority: {{ ucfirst($shiftDetails['priority']) }}
                        </span>
                        <span
                            class="badge-priority {{ isset($shiftDetails['is_active']) && $shiftDetails['is_active'] ? 'badge-active' : 'badge-inactive' }}">
                            {{ isset($shiftDetails['is_active']) && $shiftDetails['is_active'] ? 'Active' : 'Inactive' }}
                        </span>
                        <span
                            class="badge-priority {{ !empty($shiftDetails['flexible_working_hours']) ? 'badge-upcoming' : 'badge-inactive' }}">
                            <i
                                class="bi bi-{{ !empty($shiftDetails['flexible_working_hours']) ? 'sliders2' : 'lock' }} me-1"></i>
                            {{ $shiftDetails['flexibility_label'] ?? 'Fixed Working Hours' }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($shiftDetails['is_wfh']) && $shiftDetails['is_wfh'] && $shiftDetails['wfh_details'])
                    <!-- Combined WFH Info Box -->
                    <div class="wfh-info-box {{ $wfhBoxClass }}">
                        <div class="wfh-header">
                            <i class="bi bi-{{ $wfhStatusIcon }}"></i>
                            <span class="wfh-title">Work From Home - {{ $wfhStatusLabel }}</span>
                            <span class="badge {{ $wfhStatusBadge }} ms-auto">
                                {{ ucfirst($wfhStatus ?? 'Pending') }}
                            </span>
                        </div>
                        <div class="wfh-details">
                            <div class="detail-item">
                                <span class="label">Request ID</span>
                                <span class="value">#{{ $shiftDetails['wfh_details']['request_id'] ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Request Date</span>
                                <span
                                    class="value">{{ isset($shiftDetails['wfh_details']['created_at']) ? \Carbon\Carbon::parse($shiftDetails['wfh_details']['created_at'])->format('M d, Y h:i A') : 'N/A' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="label">WFH Period</span>
                                <span
                                    class="value">{{ isset($shiftDetails['wfh_details']['start_date']) ? \Carbon\Carbon::parse($shiftDetails['wfh_details']['start_date'])->format('M d, Y') : 'N/A' }}
                                    →
                                    {{ isset($shiftDetails['wfh_details']['end_date']) ? \Carbon\Carbon::parse($shiftDetails['wfh_details']['end_date'])->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            @if($wfhStatus === 'approved' && isset($shiftDetails['wfh_details']['approved_at']))
                            <div class="detail-item">
                                <span class="label">Approved At</span>
                                <span
                                    class="value">{{ \Carbon\Carbon::parse($shiftDetails['wfh_details']['approved_at'])->format('M d, Y h:i A') }}</span>
                            </div>
                            @endif
                            @if($wfhStatus === 'rejected' && isset($shiftDetails['wfh_details']['rejection_reason']))
                            <div class="detail-item">
                                <span class="label">Rejection Reason</span>
                                <span
                                    class="value text-danger">{{ $shiftDetails['wfh_details']['rejection_reason'] }}</span>
                            </div>
                            @endif
                            <div class="detail-item">
                                <span class="label">Remarks</span>
                                <span class="value">{{ $shiftDetails['wfh_details']['reason'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Shift Timings -->
                    @if($shiftDetails['start_time'] && $shiftDetails['end_time'])
                    <div class="shift-time-display">
                        <div class="time-block">
                            <span class="label">START</span>
                            <span class="value start">
                                @php
                                try {
                                $startTime = is_string($shiftDetails['start_time']) ?
                                \Carbon\Carbon::createFromFormat('H:i:s', $shiftDetails['start_time']) :
                                \Carbon\Carbon::parse($shiftDetails['start_time']);
                                echo $startTime->format('h:i A');
                                } catch (\Exception $e) {
                                echo $shiftDetails['start_time'];
                                }
                                @endphp
                            </span>
                        </div>
                        <div class="time-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                        <div class="time-block">
                            <span class="label">END</span>
                            <span class="value end">
                                @php
                                try {
                                $endTime = is_string($shiftDetails['end_time']) ?
                                \Carbon\Carbon::createFromFormat('H:i:s', $shiftDetails['end_time']) :
                                \Carbon\Carbon::parse($shiftDetails['end_time']);
                                echo $endTime->format('h:i A');
                                } catch (\Exception $e) {
                                echo $shiftDetails['end_time'];
                                }
                                @endphp
                            </span>
                        </div>
                    </div>
                    @endif

                    <!-- Info Grid -->
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">Working hours</span>
                            <span class="value">{{ $shiftDetails['duration'] }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Break</span>
                            <span class="value">{{ $shiftDetails['break_minutes'] ?? 0 }} min</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Grace</span>
                            <span class="value">{{ $shiftDetails['grace_minutes'] ?? 0 }} min</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Timing Flexibility</span>
                            <span class="value">
                                <i
                                    class="bi bi-{{ !empty($shiftDetails['flexible_working_hours']) ? 'sliders2' : 'lock' }} me-1"></i>
                                {{ !empty($shiftDetails['flexible_working_hours']) ? 'Enabled' : 'Disabled' }}
                            </span>
                            <small class="d-block mt-1 text-muted">
                                {{ !empty($shiftDetails['flexible_working_hours'])
                                    ? 'Employees can work within the configured flexible timing for this shift.'
                                    : 'Employees must follow the fixed shift timing.' }}
                            </small>
                        </div>
                        <div class="info-item">

                            <span class="label">Weekly Off</span>
                            <span class="value">
                                @if(!empty($shiftDetails['weekly_off_days']))
                                {{ implode(', ', array_slice($shiftDetails['weekly_off_days'], 0, 2)) }}
                                @if(count($shiftDetails['weekly_off_days']) > 2)
                                <small class="text-muted">+{{ count($shiftDetails['weekly_off_days']) - 2 }}</small>
                                @endif
                                @else
                                None
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Shift Period -->
                    @if($shiftDetails['start_date'] || $shiftDetails['end_date'])
                    <div class="period-alert">
                        <i class="bi bi-calendar-range"></i>
                        <strong>Shift Period:</strong>
                        @if($shiftDetails['start_date'])
                        {{ \Carbon\Carbon::parse($shiftDetails['start_date'])->format('M d, Y') }}
                        @else
                        Immediate
                        @endif
                        @if($shiftDetails['end_date'])
                        → {{ \Carbon\Carbon::parse($shiftDetails['end_date'])->format('M d, Y') }}
                        @else
                        onwards
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Shift Schedule Section (Filtered Results) -->
        <div id="shiftScheduleSection">
            <div id="scheduleHeader"></div>
            <div id="scheduleResults"></div>
        </div>
    </div>
</div>

<!-- UPCOMING SHIFTS MODAL -->
<div class="modal fade" id="upcomingShiftsModal" tabindex="-1" aria-labelledby="upcomingShiftsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-calendar-event me-2"></i>Upcoming Shifts</h5>
                <button type="button" class="modal-close-btn" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="upcomingShiftsContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary"></div>
                        <p class="mt-2">Loading upcoming shifts...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- SHIFT HISTORY MODAL -->
<div class="modal fade" id="shiftHistoryModal" tabindex="-1" aria-labelledby="shiftHistoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-clock-history me-2"></i>Shift History</h5>
                <button type="button" class="modal-close-btn" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="shiftHistoryContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary"></div>
                        <p class="mt-2">Loading shift history...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Helper functions
function formatDate(dateStr) {
    if (!dateStr) return 'N/A';
    try {
        const date = new Date(dateStr.includes('T') ? dateStr : dateStr + 'T00:00:00');
        if (isNaN(date.getTime())) return dateStr;
        return date.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    } catch (e) {
        return dateStr;
    }
}

function formatDateTime(dateStr) {
    if (!dateStr) return 'N/A';
    try {
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return dateStr;
        return date.toLocaleString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (e) {
        return dateStr;
    }
}

function formatTime(timeStr) {
    if (!timeStr) return 'N/A';
    try {
        if (timeStr.includes(':')) {
            const parts = timeStr.split(':');
            if (parts.length >= 2) {
                const hours = parseInt(parts[0]);
                const minutes = parseInt(parts[1]);
                if (!isNaN(hours) && !isNaN(minutes)) {
                    const ampm = hours >= 12 ? 'PM' : 'AM';
                    const hour12 = hours % 12 || 12;
                    return `${hour12}:${String(minutes).padStart(2, '0')} ${ampm}`;
                }
            }
        }
        return timeStr;
    } catch (e) {
        return timeStr;
    }
}

function getWFHBadgeClass(status) {
    if (status === 'approved') return 'badge-wfh-approved';
    if (status === 'pending') return 'badge-wfh-pending';
    if (status === 'rejected') return 'badge-wfh-rejected';
    return 'badge-wfh-pending';
}

function getWFHIcon(status) {
    if (status === 'approved') return 'check-circle';
    if (status === 'pending') return 'clock';
    if (status === 'rejected') return 'x-circle';
    return 'clock';
}

function getWFHBoxClass(status) {
    if (status === 'approved') return 'wfh-info-box-approved';
    if (status === 'pending') return 'wfh-info-box-pending';
    if (status === 'rejected') return 'wfh-info-box-rejected';
    return 'wfh-info-box-pending';
}

function getWFHStatusLabel(status) {
    if (status === 'approved') return 'Approved';
    if (status === 'pending') return 'Pending';
    if (status === 'rejected') return 'Rejected';
    return 'Pending';
}

// Toggle filter section
function toggleFilter() {
    const filterSection = document.getElementById('filterSection');
    if (filterSection.style.display === 'block') {
        filterSection.style.display = 'none';
    } else {
        filterSection.style.display = 'block';
        const today = new Date().toISOString().split('T')[0];
        const nextWeek = new Date();
        nextWeek.setDate(nextWeek.getDate() + 7);
        const nextWeekStr = nextWeek.toISOString().split('T')[0];

        if (!document.getElementById('filterStartDate').value) {
            document.getElementById('filterStartDate').value = today;
        }
        if (!document.getElementById('filterEndDate').value) {
            document.getElementById('filterEndDate').value = nextWeekStr;
        }
    }
}

// Apply date filter
function applyDateFilter() {
    const startDate = document.getElementById('filterStartDate').value;
    const endDate = document.getElementById('filterEndDate').value;
    const status = document.getElementById('filterStatus').value;

    if (!startDate || !endDate) {
        alert('Please select both start and end dates');
        return;
    }

    if (new Date(startDate) > new Date(endDate)) {
        alert('Start date cannot be after end date');
        return;
    }
    const start = formatDate(startDate);
    const end = formatDate(endDate);

    document.getElementById("pageTitle").innerHTML =
        `<i class="bi bi-calendar-range"></i> My Shift`;

    document.getElementById("pageSubtitle").innerHTML =
        `${start} &nbsp;→&nbsp; ${end}`;

    document.getElementById('currentShiftSection').style.display = 'none';
    document.getElementById('shiftScheduleSection').style.display = 'block';

    loadFilteredShifts(startDate, endDate, status);
}

// Reset filter
function resetFilter() {
    const todayValue = new Date().toISOString().split('T')[0];
    const todayText = formatDate(todayValue);

    const nextWeek = new Date();
    nextWeek.setDate(nextWeek.getDate() + 7);

    document.getElementById("pageTitle").innerHTML =
        `<i class="bi bi-person-badge"></i> My Shift - ${todayText}`;

    document.getElementById("pageSubtitle").innerHTML =
        "View your current shift";

    document.getElementById('filterStartDate').value = todayValue;
    document.getElementById('filterEndDate').value = nextWeek.toISOString().split('T')[0];
    document.getElementById('filterStatus').value = 'all';

    document.getElementById('currentShiftSection').style.display = 'block';
    document.getElementById('shiftScheduleSection').style.display = 'none';
    document.getElementById('filterSection').style.display = 'none';
}

// Load filtered shifts
function loadFilteredShifts(startDate, endDate, status = 'all') {
    const header = document.getElementById('scheduleHeader');
    const results = document.getElementById('scheduleResults');

    results.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading shifts...</p>
        </div>
    `;

    const url = `/employee/shifts-by-date-range?start_date=${startDate}&end_date=${endDate}`;

    fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                let shifts = data.shifts || [];

                if (status !== 'all') {
                    shifts = shifts.filter(shift => {
                        if (status === 'active') return shift.is_active === true;
                        if (status === 'upcoming') return shift.is_upcoming === true;
                        if (status === 'completed') return shift.is_completed === true;
                        return true;
                    });
                }

                header.innerHTML = `
                <div class="schedule-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h4><i class="bi bi-calendar-range me-2"></i>My Shift</h4>
                            <div class="schedule-subtitle">
                                ${formatDate(startDate)} → ${formatDate(endDate)}
                            </div>
                        </div>
                        <div class="schedule-stats">
                            Showing ${shifts.length} ${shifts.length === 1 ? 'Shift' : 'Shifts'}
                        </div>
                    </div>
                </div>
            `;

                if (shifts.length === 0) {
                    results.innerHTML = `
                    <div class="no-shifts-found">
                        <i class="bi bi-calendar-x"></i>
                        <h4>No shifts found</h4>
                        <p>No shift assignments exist between<br>
                        <strong>${formatDate(startDate)}</strong> and <strong>${formatDate(endDate)}</strong></p>
                        <button class="btn btn-primary" onclick="resetFilter()">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                        </button>
                    </div>
                `;
                    return;
                }

                let html = '';

                shifts.forEach((shift) => {
                    const statusClass = shift.is_active ? '' :
                        (shift.is_upcoming ? 'upcoming' : 'completed');
                    const statusBadge = shift.is_active ? 'badge-active' :
                        (shift.is_upcoming ? 'badge-upcoming' : 'badge-completed');
                    const statusText = shift.is_active ? 'Active' :
                        (shift.is_upcoming ? 'Upcoming' : 'Completed');

                    const isWFH = shift.is_wfh === true;
                    const wfhStatus = shift.wfh_status || null;
                    const wfhReason = shift.wfh_reason || 'Work From Home';
                    const wfhApprovedAt = shift.wfh_approved_at || null;
                    const wfhStartDate = shift.wfh_start_date || null;
                    const wfhEndDate = shift.wfh_end_date || null;

                    const shiftIcon = isWFH ? 'bi bi-house-door' : 'bi bi-person-check';
                    let shiftTitle = shift.shift_name || 'Unnamed Shift';
                    if (isWFH && shiftTitle.includes(' (WFH)')) {
                        shiftTitle = shiftTitle.replace(' (WFH)', '');
                    }

                    // WFH badge
                    let wfhBadge = '';
                    if (isWFH && wfhStatus) {
                        const badgeClass = getWFHBadgeClass(wfhStatus);
                        const icon = getWFHIcon(wfhStatus);
                        wfhBadge = `<span class="badge ${badgeClass} ms-2">
                            <i class="bi bi-${icon} me-1"></i>${wfhStatus.charAt(0).toUpperCase() + wfhStatus.slice(1)}
                        </span>`;
                    } else if (isWFH) {
                        wfhBadge =
                            `<span class="badge badge-wfh ms-2"><i class="bi bi-house me-1"></i>WFH</span>`;
                    }

                    // Combined WFH Info Box HTML
                    let wfhInfoHtml = '';
                    if (isWFH && wfhStatus) {
                        const boxClass = getWFHBoxClass(wfhStatus);
                        const icon = getWFHIcon(wfhStatus);
                        const label = getWFHStatusLabel(wfhStatus);
                        const badgeClass = getWFHBadgeClass(wfhStatus);

                        wfhInfoHtml = `
                            <div class="wfh-info-box ${boxClass}">
                                <div class="wfh-header">
                                    <i class="bi bi-${icon}-fill"></i>
                                    <span class="wfh-title">Work From Home - ${label}</span>
                                    <span class="badge ${badgeClass} ms-auto">${label}</span>
                                </div>
                                <div class="wfh-details">
                                    ${shift.wfh_request_id ? `
                                    <div class="detail-item">
                                        <span class="label">Request ID</span>
                                        <span class="value">#${shift.wfh_request_id}</span>
                                    </div>
                                    ` : ''}
                                    ${wfhStartDate ? `
                                    <div class="detail-item">
                                        <span class="label">WFH Period</span>
                                        <span class="value">${formatDate(wfhStartDate)} → ${formatDate(wfhEndDate)}</span>
                                    </div>
                                    ` : ''}
                                    ${wfhStatus === 'approved' && wfhApprovedAt ? `
                                    <div class="detail-item">
                                        <span class="label">Approved At</span>
                                        <span class="value">${formatDateTime(wfhApprovedAt)}</span>
                                    </div>
                                    ` : ''}
                                    <div class="detail-item" style="grid-column: 1 / -1;">
                                        <span class="label">Reason</span>
                                        <span class="value">${wfhReason}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    // Card class based on WFH status
                    let cardClass = '';
                    if (isWFH && wfhStatus) {
                        cardClass = 'wfh-card-' + wfhStatus;
                    } else if (isWFH) {
                        cardClass = 'wfh-card';
                    }

                    html += `
        <div class="card shift-card ${statusClass} ${cardClass}">
            <div class="card-header-custom">
                <div>
                    <h5>
                        <i class="${shiftIcon}"></i>
                        ${shiftTitle}
                        ${wfhBadge}
                        <span class="badge ${statusBadge} ms-2">${statusText}</span>
                        ${shift.days_count > 1 ? `<span class="badge badge-days ms-2">${shift.days_count} Days</span>` : ''}
                    </h5>
                    <small style="color: rgba(255, 255, 255, 0.85);">
                        ${shift.assignment_type === 'individual' ? 'Individual Assignment' : 'Department Assignment'}
                    </small>
                </div>
                <div>
                    <span class="badge-priority badge-priority-light">
                        Priority: ${shift.priority || 'normal'}
                    </span>
                    <span class="badge-priority badge-priority-light ms-2">
                        <i class="bi bi-calendar3 me-1"></i>
                        ${formatDate(shift.shift_start_date)} → ${formatDate(shift.shift_end_date)}
                    </span>
                    <span class="badge-priority ${shift.flexible_working_hours ? 'badge-upcoming' : 'badge-inactive'} ms-2">
                        <i class="bi bi-${shift.flexible_working_hours ? 'sliders2' : 'lock'} me-1"></i>
                        ${shift.flexibility_label || (shift.flexible_working_hours ? 'Flexible Working Hours' : 'Fixed Working Hours')}
                    </span>
                </div>
            </div>

            <div class="card-body">
                <div class="shift-time-display">
                    <div class="time-block">
                        <span class="label">START</span>
                        <span class="value start">${formatTime(shift.start_time)}</span>
                    </div>
                    <div class="time-arrow">
                        <i class="bi bi-arrow-down" style="font-size: 1.2rem;"></i>
                    </div>
                    <div class="time-block">
                        <span class="label">END</span>
                        <span class="value end">${formatTime(shift.end_time)}</span>
                    </div>
                </div>

                ${wfhInfoHtml}

                <div class="info-grid">
                    <div class="info-item">
                        <span class="label">Duration</span>
                        <span class="value">${shift.duration || 'N/A'}</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Break</span>
                        <span class="value">${shift.break_minutes || 0} min</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Grace</span>
                        <span class="value">${shift.grace_minutes || 0} min</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Working Hours</span>
                        <span class="value">
                            <i class="bi bi-${shift.flexible_working_hours ? 'sliders2' : 'lock'} me-1"></i>
                            ${shift.flexibility_label || (shift.flexible_working_hours ? 'Flexible Working Hours' : 'Fixed Working Hours')}
                        </span>
                        <small class="d-block mt-1 text-muted">
                            ${shift.flexibility_message || (shift.flexible_working_hours ? 'Complete your working hours as required.' : 'Shift timing will be fixed.')}
                        </small>
                    </div>
                    <div class="info-item">
                        <span class="label">Weekly Off</span>
                        <span class="value">
                            ${shift.weekly_off_days && shift.weekly_off_days.length > 0 ? 
                                shift.weekly_off_days.join(', ') : 'None'}
                        </span>
                    </div>
                </div>

                <div class="period-alert">
                    <i class="bi bi-calendar-range"></i>
                    <strong>Shift Period:</strong>
                    ${formatDate(shift.shift_start_date)} 
                    ${shift.shift_end_date ? `→ ${formatDate(shift.shift_end_date)}` : 'onwards'}
                </div>
            </div>
        </div>
    `;
                });

                results.innerHTML = html;
            } else {
                results.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    ${data.message || 'Failed to load shifts'}
                </div>
            `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            results.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Error loading shifts. Please try again.
            </div>
        `;
        });
}

// Load upcoming shifts
window.loadUpcomingShifts = function() {
    const modal = new bootstrap.Modal(document.getElementById('upcomingShiftsModal'));
    const content = document.getElementById('upcomingShiftsContent');

    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2">Loading upcoming shifts...</p>
        </div>
    `;

    modal.show();

    fetch('/employee/upcoming-shifts')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let html = `
                    <div class="mb-3 p-3 bg-white rounded-3 border">
                        <h6 class="text-primary mb-2"><i class="bi bi-person-badge me-2"></i>Employee: ${data.employee.name}</h6>
                        <p class="text-muted mb-1"><i class="bi bi-upc-scan me-2"></i>Code: ${data.employee.employee_code}</p>
                    </div>
                `;

                if (data.upcoming && data.upcoming.length > 0) {
                    html += `
                        <div class="mb-3">
                            <span class="badge bg-info">${data.upcoming.length} upcoming shift(s)</span>
                        </div>
                        <div class="timeline">
                    `;

                    data.upcoming.forEach((item) => {
                        if (!item.shift) return;
                        const shift = item.shift;
                        const daysUntil = item.days_until || 'Soon';
                        const duration = calculateDuration(shift.start_time, shift.end_time);

                        html += `
                            <div class="timeline-item">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between flex-wrap gap-2">
                                            <div class="d-flex gap-2">
                                                <h6 class="mb-1 text-primary">
                                                    <i class="bi bi-${item.type === 'individual' ? 'person-check' : 'building'} me-1"></i>
                                                    ${shift.shift_name || 'Unnamed Shift'}
                                                </h6>
                                                <small class="text-muted">
                                                    ${item.type === 'individual' ? 'Individual' : 'Department'} Assignment
                                                    <span class="badge badge-upcoming">
                                                        <i class="bi bi-clock me-1"></i>Starts in ${daysUntil}
                                                    </span>
                                                </small>
                                            </div>
                                            <div class="d-flex flex-wrap gap-2">
                                                <span class="badge bg-light text-dark">
                                                    Priority: ${shift.priority || 'normal'}
                                                </span>
                                                <span class="badge ${shift.flexible_working_hours ? 'bg-warning text-dark' : 'bg-secondary'}">
                                                    <i class="bi bi-${shift.flexible_working_hours ? 'sliders2' : 'lock'} me-1"></i>
                                                    ${shift.flexibility_label || (shift.flexible_working_hours ? 'Flexible Working Hours' : 'Fixed Working Hours')}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mt-2">
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <div>
                                                    <small class="text-muted">Start:</small>
                                                    <strong>${formatTime(shift.start_time)}</strong>
                                                </div>
                                                <span class="text-muted">→</span>
                                                <div>
                                                    <small class="text-muted">End:</small>
                                                    <strong>${formatTime(shift.end_time)}</strong>
                                                </div>
                                                <span class="badge bg-light text-dark ms-2">
                                                    <i class="bi bi-clock me-1"></i>${duration}
                                                </span>
                                            </div>

                                            ${shift.break_minutes ? `
                                                <div class="d-flex gap-3 mb-2">
                                                    <small class="text-muted">
                                                        <i class="bi bi-cup-hot me-1"></i>
                                                        Break: ${shift.break_minutes} min
                                                    </small>
                                                    ${shift.grace_minutes ? `
                                                        <small class="text-muted">
                                                            <i class="bi bi-alarm me-1"></i>
                                                            Grace: ${shift.grace_minutes} min
                                                        </small>
                                                    ` : ''}
                                                </div>
                                            ` : ''}

                                            <div>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar-range me-1"></i>
                                                    Start: ${formatDate(shift.start_date)}
                                                    ${shift.end_date ? ` | End: ${formatDate(shift.end_date)}` : ''}
                                                </small>
                                            </div>

                                            <div>
                                                <small class="text-muted">
                                                    <i class="bi bi-info-circle me-1"></i>
                                                    ${shift.flexibility_message || (shift.flexible_working_hours ? 'Complete your working hours as required.' : 'Shift timing will be fixed.')}
                                                </small>
                                            </div>

                                            ${shift.weekly_off_days ? `
                                                <div>
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar-week me-1"></i>
                                                        Weekly off: ${Array.isArray(shift.weekly_off_days) ? 
                                                            shift.weekly_off_days.join(', ') : 
                                                            JSON.parse(shift.weekly_off_days || '[]').join(', ')}
                                                    </small>
                                                </div>
                                            ` : ''}

                                            <div>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar-check me-1"></i>
                                                    Assigned: ${formatDateTime(item.assigned_at)}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                } else {
                    html += `
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            No upcoming shifts found.
                        </div>
                    `;
                }

                content.innerHTML = html;
            } else {
                content.innerHTML = '<div class="alert alert-danger">Error loading upcoming shifts.</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML =
                '<div class="alert alert-danger">Error loading upcoming shifts. Please try again.</div>';
        });
};

// Load shift history
window.loadShiftHistory = function() {
    const modal = new bootstrap.Modal(document.getElementById('shiftHistoryModal'));
    const content = document.getElementById('shiftHistoryContent');

    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary"></div>
            <p class="mt-2">Loading shift history...</p>
        </div>
    `;

    modal.show();

    fetch('/employee/shift-history')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let html = `
                    <div class="mb-3 p-3 bg-white rounded-3 border">
                        <h6 class="text-primary mb-2"><i class="bi bi-person-badge me-2"></i>Employee: ${data.employee.name}</h6>
                        <p class="text-muted mb-1"><i class="bi bi-upc-scan me-2"></i>Code: ${data.employee.employee_code}</p>
                    </div>
                `;

                if (data.history && data.history.length > 0) {
                    const completedCount = data.history.filter(item => item.status === 'completed').length;
                    html += `
                        <div class="mb-3">
                            <span class="badge bg-secondary">${completedCount} completed shift(s)</span>
                            <span class="badge bg-warning text-dark ms-1">${data.history.length - completedCount} active/other</span>
                        </div>
                        <div class="timeline">
                    `;

                    data.history.forEach((item) => {
                        if (!item.shift) return;
                        const shift = item.shift;

                        let badgeClass = 'bg-secondary';
                        let statusLabel = item.status || 'unknown';

                        if (item.status === 'active') {
                            badgeClass = 'bg-success';
                        } else if (item.status === 'upcoming') {
                            badgeClass = 'bg-info';
                        } else if (item.status === 'completed') {
                            badgeClass = 'bg-secondary';
                        } else if (item.status === 'inactive') {
                            badgeClass = 'bg-warning text-dark';
                        }

                        const duration = calculateDuration(shift.start_time, shift.end_time);

                        html += `
                            <div class="timeline-item">
                                <div class="card mb-3 ${item.status === 'completed' ? 'opacity-75' : ''}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between flex-wrap gap-2">
                                            <div class="d-flex gap-2">
                                                <h6 class="mb-1 text-primary">
                                                    <i class="bi bi-${item.type === 'individual' ? 'person-check' : 'building'} me-1"></i>
                                                    ${shift.shift_name || 'Unnamed Shift'}
                                                </h6>
                                                <small class="text-muted">
                                                    ${item.type === 'individual' ? 'Individual' : 'Department'} Assignment
                                                </small>
                                            </div>
                                            <div class="d-flex flex-wrap gap-2">
                                                <span class="badge ${badgeClass}">
                                                    ${statusLabel}
                                                </span>
                                                <span class="badge bg-light text-dark">
                                                    Priority: ${shift.priority || 'normal'}
                                                </span>
                                                <span class="badge ${shift.flexible_working_hours ? 'bg-warning text-dark' : 'bg-secondary'}">
                                                    <i class="bi bi-${shift.flexible_working_hours ? 'sliders2' : 'lock'} me-1"></i>
                                                    ${shift.flexibility_label || (shift.flexible_working_hours ? 'Flexible Working Hours' : 'Fixed Working Hours')}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mt-2">
                                            <div class="d-flex align-items-center gap-3 mb-2">
                                                <div>
                                                    <small class="text-muted">Start:</small>
                                                    <strong>${formatTime(shift.start_time)}</strong>
                                                </div>
                                                <span class="text-muted">→</span>
                                                <div>
                                                    <small class="text-muted">End:</small>
                                                    <strong>${formatTime(shift.end_time)}</strong>
                                                </div>
                                                <span class="badge bg-light text-dark ms-2">
                                                    <i class="bi bi-clock me-1"></i>${duration}
                                                </span>
                                            </div>

                                            ${shift.break_minutes ? `
                                                <div class="d-flex gap-3 mb-2">
                                                    <small class="text-muted">
                                                        <i class="bi bi-cup-hot me-1"></i>
                                                        Break: ${shift.break_minutes} min
                                                    </small>
                                                    ${shift.grace_minutes ? `
                                                        <small class="text-muted">
                                                            <i class="bi bi-alarm me-1"></i>
                                                            Grace: ${shift.grace_minutes} min
                                                        </small>
                                                    ` : ''}
                                                </div>
                                            ` : ''}

                                            <div>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar-range me-1"></i>
                                                    Start: ${formatDate(shift.start_date)}
                                                    ${shift.end_date ? ` | End: ${formatDate(shift.end_date)}` : ''}
                                                </small>
                                            </div>

                                            <div>
                                                <small class="text-muted">
                                                    <i class="bi bi-info-circle me-1"></i>
                                                    ${shift.flexibility_message || (shift.flexible_working_hours ? 'Complete your working hours as required.' : 'Shift timing will be fixed.')}
                                                </small>
                                            </div>

                                            ${shift.weekly_off_days ? `
                                                <div>
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar-week me-1"></i>
                                                        Weekly off: ${Array.isArray(shift.weekly_off_days) ? 
                                                            shift.weekly_off_days.join(', ') : 
                                                            JSON.parse(shift.weekly_off_days || '[]').join(', ')}
                                                    </small>
                                                </div>
                                            ` : ''}

                                            <div>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar-check me-1"></i>
                                                    Assigned: ${formatDateTime(item.assigned_at)}
                                                    ${item.ended_at ? ` | Ended: ${formatDateTime(item.ended_at)}` : ''}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                } else {
                    html += `
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            No shift history found.
                        </div>
                    `;
                }

                content.innerHTML = html;
            } else {
                content.innerHTML = '<div class="alert alert-danger">Error loading shift history.</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML =
                '<div class="alert alert-danger">Error loading shift history. Please try again.</div>';
        });
};

// Calculate duration
function calculateDuration(startTime, endTime) {
    if (!startTime || !endTime) return 'N/A';
    try {
        const start = new Date('1970-01-01T' + startTime);
        const end = new Date('1970-01-01T' + endTime);
        const diffMs = end - start;
        const diffHrs = Math.floor(diffMs / (1000 * 60 * 60));
        const diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
        let duration = diffHrs + 'h';
        if (diffMins > 0) duration += ' ' + diffMins + 'm';
        return duration;
    } catch (e) {
        return 'N/A';
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const nextWeek = new Date();
    nextWeek.setDate(nextWeek.getDate() + 7);

    const startInput = document.getElementById('filterStartDate');
    const endInput = document.getElementById('filterEndDate');

    if (!startInput.value) startInput.value = today;
    if (!endInput.value) endInput.value = nextWeek.toISOString().split('T')[0];
});
</script>
@endsection