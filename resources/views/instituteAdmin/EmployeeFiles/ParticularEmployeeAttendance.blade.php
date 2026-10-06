@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

<style>
/* Clean & Minimal Styles */
.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
}

/* Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #e9ecef;
}

.page-header h1 {
    font-size: 24px;
    font-weight: 600;
    margin: 0;
    color: #2c3e50;
}

.page-header h1 i {
    margin-right: 10px;
    color: #667eea;
}

/* Filter Bar */
.filter-bar {
    background: #fff;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    border: 1px solid #e9ecef;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.filter-controls {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.filter-controls select {
    padding: 8px 16px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    font-size: 14px;
    background: #fff;
    cursor: pointer;
}

.filter-controls select:focus {
    outline: none;
    border-color: #667eea;
}

.filter-controls button {
    padding: 8px 20px;
    background: #667eea;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
}

.filter-controls button:hover {
    background: #5a67d8;
}

.btn-reset {
    background: #6c757d !important;
}

.btn-reset:hover {
    background: #5a6268 !important;
}

.finalized-badge {
    background: #e8f5e9;
    color: #2e7d32;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.finalized-badge i {
    margin-right: 6px;
}

.finalized-badge.pending {
    background: #fff3e0;
    color: #e65100;
}

/* Stats Cards - Clean & Minimal */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 16px;
    margin-bottom: 32px;
}

.stat-card {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    border-color: #667eea;
}

.stat-card.active {
    border-color: #667eea;
    background: #f8f9ff;
}

.stat-number {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1.2;
}

.stat-label {
    font-size: 13px;
    color: #6c757d;
    margin-top: 6px;
}

/* Calendar - ENLARGED VERSION */
.calendar-container {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
}

.calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    background: #f8f9fa;
    border-bottom: 2px solid #e9ecef;
}

.calendar-header div {
    padding: 14px 8px;
    text-align: center;
    font-weight: 700;
    font-size: 15px;
    color: #2c3e50;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}

.calendar-day {
    min-height: 160px;
    padding: 12px 10px;
    border-right: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
    position: relative;
    cursor: pointer;
    transition: all 0.2s;
    background: #fff;
}

.calendar-day:hover {
    background: #f8f9fa;
    z-index: 1;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.calendar-day.today {
    background: #f0fdf4;
    border: 2px solid #22c55e;
}

.day-number {
    font-size: 18px;
    font-weight: 700;
    color: #1a1a2e;
    display: inline-block;
    margin-bottom: 6px;
    padding: 2px 8px;
    border-radius: 6px;
}

.today .day-number {
    background: #22c55e;
    color: white;
    padding: 2px 10px;
}

/* ============================================
   WFH BADGE STYLES - Work From Home
   ============================================ */
.wfh-badge {
    display: inline-block;
    font-size: 9px;
    font-weight: 700;
    padding: 2px 10px;
    border-radius: 12px;
    margin-top: 3px;
    margin-bottom: 3px;
    letter-spacing: 0.3px;
    border: 1px solid;
}

/* WFH - Approved Status */
.wfh-badge-approved {
    background: #d1fae5;
    color: #065f46;
    border-color: #10b981;
}

.wfh-badge-approved i {
    color: #10b981;
}

/* WFH - Pending Status */
.wfh-badge-pending {
    background: #fef3c7;
    color: #92400e;
    border-color: #f59e0b;
}

.wfh-badge-pending i {
    color: #f59e0b;
}

/* WFH - Rejected Status */
.wfh-badge-rejected {
    background: #fee2e2;
    color: #991b1b;
    border-color: #ef4444;
}

.wfh-badge-rejected i {
    color: #ef4444;
}

/* WFH - Default (when status unknown) */
.wfh-badge-default {
    background: #e0e7ff;
    color: #3730a3;
    border-color: #6366f1;
}

.wfh-badge-default i {
    color: #6366f1;
}

/* WFH Tooltip / Info icon */
.wfh-info-icon {
    display: inline-block;
    font-size: 8px;
    margin-left: 2px;
    cursor: help;
    opacity: 0.7;
}

/* ============================================
   END WFH BADGE STYLES
   ============================================ */

/* Shift Info Styling - Enlarged */
.shift-info {
    font-size: 11px !important;
    color: #2c3e50;
    margin-bottom: 6px;
    background: #f0f4ff;
    border-radius: 6px;
    padding: 5px 8px;
    line-height: 1.4;
}

.shift-info .shift-time {
    font-weight: 700;
    color: #1a1a2e;
    font-size: 11px;
}

.shift-info .shift-name {
    font-size: 10px;
    color: #495057;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.shift-info .shift-type {
    font-size: 9px;
    color: #6c757d;
    background: #e9ecef;
    border-radius: 12px;
    padding: 1px 8px;
    display: inline-block;
    margin-top: 2px;
    font-weight: 500;
}

/* Upcoming shift display */
.status-upcoming .shift-info {
    background: #f8f9fa;
    border-left-color: #adb5bd;
    opacity: 0.7;
}

/* Status Indicators - Enlarged */
.status-present { background: #f0fdf4; }
.status-absent { background: #fef2f2; }
.status-leave { background: #fffbeb; }
.status-weekend { background: #f8fafc; }
.status-upcoming { 
    background: #f8f9fa; 
    opacity: 0.7;
}

.status-upcoming .day-number {
    color: #94a3b8;
}

.attendance-info {
    font-size: 11px;
    margin-top: 4px;
}

.check-time {
    color: #16a34a;
    font-weight: 600;
    font-size: 10px;
}

.check-time.out {
    color: #2563eb;
}

.total-hours {
    color: #475569;
    font-size: 10px;
    margin-top: 2px;
    font-weight: 500;
}

.leave-badge {
    display: inline-block;
    background: #fef3c7;
    color: #92400e;
    padding: 2px 10px;
    border-radius: 12px;
    font-size: 10px;
    margin-top: 2px;
    font-weight: 600;
}

.late-badge {
    color: #dc2626;
    font-size: 10px;
    margin-top: 2px;
    font-weight: 500;
}

.upcoming-label {
    color: #94a3b8;
    font-size: 10px;
    font-style: italic;
    font-weight: 500;
}

.quota-warning {
    color: #dc2626;
    font-size: 9px;
    margin-top: 2px;
}

/* Modal Styles */
.modal-content {
    border-radius: 12px;
    border: none;
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
    background: #fff;
    padding: 16px 20px;
}

.modal-header .modal-title {
    font-weight: 600;
    color: #2c3e50;
}

.modal-body {
    padding: 20px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    color: #6c757d;
    font-size: 13px;
}

.detail-value {
    font-weight: 500;
    color: #2c3e50;
    font-size: 13px;
}

.status-badge-modal {
    display: inline-block;
    padding: 4px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.status-present-badge { background: #e8f5e9; color: #2e7d32; }
.status-absent-badge { background: #ffebee; color: #c62828; }
.status-leave-badge { background: #fff3e0; color: #e65100; }
.status-weekend-badge { background: #f5f5f5; color: #616161; }

/* ============================================
   MODAL WFH SECTION - Work From Home
   ============================================ */
.modal-wfh-section {
    border-radius: 10px;
    padding: 14px 16px;
    margin-bottom: 14px;
    border-left: 4px solid;
}

.modal-wfh-section.wfh-approved {
    background: #ecfdf5;
    border-color: #10b981;
}

.modal-wfh-section.wfh-pending {
    background: #fffbeb;
    border-color: #f59e0b;
}

.modal-wfh-section.wfh-rejected {
    background: #fef2f2;
    border-color: #ef4444;
}

.modal-wfh-section .wfh-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
}

.modal-wfh-section .wfh-header i {
    font-size: 18px;
}

.modal-wfh-section .wfh-header .wfh-title {
    font-weight: 700;
    font-size: 14px;
    margin: 0;
}

.modal-wfh-section .wfh-detail {
    display: flex;
    justify-content: space-between;
    padding: 3px 0;
    font-size: 13px;
}

.modal-wfh-section .wfh-detail .label {
    color: #64748b;
}

.modal-wfh-section .wfh-detail .value {
    font-weight: 500;
    color: #1e293b;
}

.modal-wfh-approved .wfh-header i { color: #10b981; }
.modal-wfh-approved .wfh-header .wfh-title { color: #065f46; }

.modal-wfh-pending .wfh-header i { color: #f59e0b; }
.modal-wfh-pending .wfh-header .wfh-title { color: #92400e; }

.modal-wfh-rejected .wfh-header i { color: #ef4444; }
.modal-wfh-rejected .wfh-header .wfh-title { color: #991b1b; }

.modal-wfh-section .wfh-status-badge {
    font-size: 11px;
    padding: 2px 12px;
    border-radius: 20px;
    font-weight: 600;
    margin-left: auto;
}

.wfh-status-approved {
    background: #d1fae5;
    color: #065f46;
}

.wfh-status-pending {
    background: #fef3c7;
    color: #92400e;
}

.wfh-status-rejected {
    background: #fee2e2;
    color: #991b1b;
}

/* ============================================
   END MODAL WFH SECTION
   ============================================ */

.modal-shift-info {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px 15px;
    margin-bottom: 12px;
    border-left: 3px solid #667eea;
}

.modal-shift-info .shift-label {
    font-size: 11px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.modal-shift-info .shift-value {
    font-weight: 600;
    color: #2c3e50;
    font-size: 14px;
}

.modal-shift-info .shift-timing {
    font-size: 13px;
    color: #6c757d;
    margin-top: 4px;
}

.modal-shift-info .shift-assignment {
    font-size: 11px;
    color: #868e96;
    margin-top: 2px;
}

.modal-shift-info .shift-assignment .badge {
    font-size: 10px;
    padding: 2px 8px;
}

/* Print */
@media print {
    .filter-bar, .stats-grid, .btn, .modal, .no-print {
        display: none !important;
    }
    .calendar-container {
        border: none;
    }
    .calendar-day {
        break-inside: avoid;
        border: 1px solid #ddd !important;
    }
}

/* Responsive */
@media (max-width: 1024px) {
    .calendar-day {
        min-height: 140px;
        padding: 10px 8px;
    }
    .day-number {
        font-size: 16px;
    }
}

@media (max-width: 768px) {
    .calendar-day {
        min-height: 120px;
        padding: 8px 6px;
    }
    .day-number {
        font-size: 14px;
    }
    .shift-info {
        font-size: 9px !important;
        padding: 3px 6px;
    }
    .shift-info .shift-time {
        font-size: 9px;
    }
    .shift-info .shift-name {
        font-size: 8px;
    }
    .attendance-info {
        font-size: 9px;
    }
    .check-time {
        font-size: 8px;
    }
    .stat-number {
        font-size: 22px;
    }
    .filter-bar {
        flex-direction: column;
        align-items: stretch;
    }
    .filter-controls {
        flex-wrap: wrap;
    }
    .calendar-header div {
        font-size: 12px;
        padding: 10px 4px;
    }
    .wfh-badge {
        font-size: 7px;
        padding: 1px 6px;
    }
}

@media (max-width: 480px) {
    .calendar-day {
        min-height: 100px;
        padding: 6px 4px;
    }
    .day-number {
        font-size: 13px;
    }
    .shift-info {
        display: none;
    }
    .stats-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }
    .stat-card {
        padding: 10px;
    }
    .stat-number {
        font-size: 18px;
    }
    .calendar-header div {
        font-size: 10px;
        padding: 8px 2px;
    }
    .wfh-badge {
        font-size: 6px;
        padding: 1px 4px;
    }
}
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <h1>
            <i class="fas fa-calendar-alt"></i>
            My Attendance
        </h1>
        <div class="no-print">
            <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="filter-controls">
            <select name="month" id="monthSelect">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::createFromDate(null, $m, 1)->format('F') }}
                    </option>
                @endfor
            </select>

            <select name="year" id="yearSelect">
                @for($y = now()->year; $y >= now()->year - 3; $y--)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>

            <button type="button" class="btn-reset" onclick="resetFilters()">
                <i class="fas fa-undo-alt"></i> Reset
            </button>
        </div>

        @if(isset($isFinalized) && $isFinalized)
        <div class="finalized-badge">
            <i class="fas fa-check-circle"></i> Finalized on {{ $finalizedDate ?? 'N/A' }}
        </div>
        @else
        <div class="finalized-badge pending">
            <i class="fas fa-clock"></i> Pending Finalization
        </div>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid" id="statsGrid">
        <div class="stat-card" data-status="present">
            <div class="stat-number">{{ $totalPresent ?? 0 }}</div>
            <div class="stat-label">Present</div>
        </div>
        <div class="stat-card" data-status="absent">
            <div class="stat-number">{{ $totalAbsent ?? 0 }}</div>
            <div class="stat-label">Absent</div>
        </div>
        <div class="stat-card" data-status="leave">
            <div class="stat-number">{{ $totalLeave ?? 0 }}</div>
            <div class="stat-label">Leave</div>
        </div>
        <div class="stat-card" data-status="weekend">
            <div class="stat-number">{{ $totalWeekend ?? 0 }}</div>
            <div class="stat-label">Week Off</div>
        </div>
        @if(isset($totalShortAttendance) && $totalShortAttendance > 0)
        <div class="stat-card" data-status="short_attendance">
            <div class="stat-number">{{ $totalShortAttendance }}</div>
            <div class="stat-label">Short Attendance</div>
        </div>
        @endif
        <div class="stat-card" data-status="all">
            <div class="stat-number">{{ ($totalPresent ?? 0) + ($totalAbsent ?? 0) + ($totalLeave ?? 0) + ($totalWeekend ?? 0) }}</div>
            <div class="stat-label">Total Days</div>
        </div>
    </div>

    <!-- Calendar -->
    @php
    use Carbon\Carbon;
    $startOfMonth = Carbon::createFromDate($year, $month, 1);
    $firstDayOfWeek = $startOfMonth->dayOfWeek;
    $daysInMonth = $startOfMonth->daysInMonth;
    $today = Carbon::today();
    
    function formatHours($hours) {
        if (!$hours) return null;
        $hrs = floor($hours);
        $mins = round(($hours - $hrs) * 60);
        if ($mins == 60) {
            $hrs++;
            $mins = 0;
        }
        if ($hrs > 0 && $mins > 0) {
            return $hrs . 'h ' . $mins . 'm';
        } elseif ($hrs > 0) {
            return $hrs . 'h';
        } else {
            return $mins . 'm';
        }
    }
    
    // Helper to get WFH status class
    function getWfhBadgeClass($status) {
        if ($status === 'approved' || $status === 'Approved') return 'wfh-badge-approved';
        if ($status === 'pending' || $status === 'Pending') return 'wfh-badge-pending';
        if ($status === 'rejected' || $status === 'Rejected') return 'wfh-badge-rejected';
        return 'wfh-badge-default';
    }
    
    function getWfhIcon($status) {
        if ($status === 'approved' || $status === 'Approved') return 'fas fa-check-circle';
        if ($status === 'pending' || $status === 'Pending') return 'fas fa-clock';
        if ($status === 'rejected' || $status === 'Rejected') return 'fas fa-times-circle';
        return 'fas fa-home';
    }
    
    function getWfhLabel($status) {
        if ($status === 'approved' || $status === 'Approved') return 'Approved';
        if ($status === 'pending' || $status === 'Pending') return 'Pending';
        if ($status === 'rejected' || $status === 'Rejected') return 'Rejected';
        return 'Work From Home';
    }
    @endphp

    <div class="calendar-container">
        <div class="calendar-header">
            <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
        </div>
        <div class="calendar-grid" id="calendarGrid">
            @for($i = 0; $i < $firstDayOfWeek; $i++)
                <div class="calendar-day" style="background: #fafbfc;"></div>
            @endfor

            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $dateObj = Carbon::createFromDate($year, $month, $day);
                    $dateString = $dateObj->toDateString();
                    $attendance = $detailedAttendance[$dateString] ?? null;
                    $isToday = $dateString == $today->toDateString();
                    $isFuture = $dateObj->gt($today);
                    $status = $attendance['status'] ?? ($isFuture ? 'upcoming' : 'absent');
                    
                    $statusClass = match($status) {
                        'present' => 'status-present',
                        'absent' => 'status-absent',
                        'leave' => 'status-leave',
                        'weekend' => 'status-weekend',
                        'upcoming' => 'status-upcoming',
                        default => ''
                    };
                    
                    $formattedHours = isset($attendance['total_hours']) ? formatHours($attendance['total_hours']) : null;
                    
                    // Check if shift exists for this day
                    $hasShift = isset($attendance['shift']) && $attendance['shift'];
                    
                    // Check WFH status
                    $isWFH = isset($attendance['is_wfh']) && $attendance['is_wfh'] === true;
                    $wfhStatus = $attendance['wfh_status'] ?? null;
                    $wfhStatusBadge = $attendance['wfh_status_badge'] ?? null;
                    $wfhReason = $attendance['wfh_reason'] ?? null;
                    $wfhRequestId = $attendance['wfh_request_id'] ?? null;
                   
                    // Get WFH badge class
                    $wfhBadgeClass = $isWFH ? getWfhBadgeClass($wfhStatusBadge) : '';
                    $wfhIcon = $isWFH ? getWfhIcon($wfhStatusBadge) : '';
                    $wfhLabel = $isWFH ? getWfhLabel($wfhStatusBadge) : '';
                @endphp

                <div class="calendar-day {{ $statusClass }} {{ $isToday ? 'today' : '' }}" 
                    data-date="{{ $dateString }}"
                    data-status="{{ $status }}"
                    data-wfh="{{ $isWFH ? 'true' : 'false' }}"
                    data-wfh-status="{{ $wfhStatusBadge ?? '' }}">
                    
                    <div class="day-number">{{ $day }}</div>

                    <!-- WFH Badge - Work From Home -->
                    @if($isWFH)
                        <div class="wfh-badge {{ $wfhBadgeClass }}" title="Work From Home - {{ $wfhLabel }}">
                            <i class="{{ $wfhIcon }}"></i> Work From Home
                            @if($wfhStatus)
                                <span style="font-weight: 400; opacity: 0.8;">· {{ $wfhStatus }}</span>
                            @endif
                            @if($wfhReason)
                                <span class="wfh-info-icon" title="Reason: {{ $wfhReason }}">ⓘ</span>
                            @endif
                        </div>
                    @endif

                    <!-- Shift Display -->
                    @if($hasShift)
                        <div class="shift-info">
                            <div class="shift-time">
                                <i class="fas fa-clock" style="font-size: 8px;"></i>
                                {{ $attendance['shift']['start_time'] ?? '' }} - {{ $attendance['shift']['end_time'] ?? '' }}
                            </div>
                            <div class="shift-name">{{ $attendance['shift']['shift_name'] ?? '' }}</div>
                            @if(isset($attendance['shift']['assignment_type']) && $attendance['shift']['assignment_type'] != 'none')
                                <span class="shift-type">{{ ucfirst($attendance['shift']['assignment_type']) }}</span>
                            @endif
                        </div>
                    @endif

                    <!-- Attendance Info -->
                    @if($status == 'upcoming')
                        <div class="attendance-info">
                            <div class="upcoming-label">
                                <i class="fas fa-calendar-alt"></i> Upcoming
                            </div>
                        </div>
                    @elseif($status == 'present' && isset($attendance['check_in']))
                        <div class="attendance-info">
                            @if(isset($attendance['check_in']))
                            <div class="check-time">
                                <i class="fas fa-sign-in-alt" style="font-size: 9px;"></i> {{ $attendance['check_in'] }}
                            </div>
                            @endif
                            @if(isset($attendance['check_out']))
                            <div class="check-time out">
                                <i class="fas fa-sign-out-alt" style="font-size: 9px;"></i> {{ $attendance['check_out'] }}
                            </div>
                            @endif
                            @if($formattedHours)
                            <div class="total-hours">
                                <i class="far fa-clock"></i> {{ $formattedHours }}
                            </div>
                            @endif
                            @if(isset($attendance['leave_type']))
                            <div class="leave-badge">{{ $attendance['leave_type'] }}</div>
                            @endif
                            @if(isset($attendance['late_minutes']) && $attendance['late_minutes'] > 0)
                            <div class="late-badge">Late {{ $attendance['late_minutes'] }} min</div>
                            @endif
                        </div>
                    @elseif($status == 'leave')
                        <div class="attendance-info">
                            <div class="leave-badge">
                                <i class="fas fa-calendar-week"></i> {{ $attendance['leave_type'] ?? 'Leave' }}
                            </div>
                        </div>
                    @elseif($status == 'absent')
                        <div class="attendance-info">
                            <div class="total-hours" style="color: #dc2626; font-weight: 600;">
                                <i class="fas fa-times-circle"></i> Absent
                            </div>
                        </div>
                    @elseif($status == 'weekend')
                        <div class="attendance-info">
                            <div class="total-hours" style="color: #64748b; font-weight: 600;">
                                <i class="fas fa-calendar-alt"></i> Week Off
                            </div>
                        </div>
                    @endif
                </div>
            @endfor
        </div>
    </div>
</div>

<!-- Modal - Attendance Details -->
<div class="modal fade" id="attendanceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attendance Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Dynamic content -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
// ============================================
// AUTO FILTER - Month/Year Change
// ============================================
document.getElementById('monthSelect')?.addEventListener('change', function() {
    filterAttendance();
});

document.getElementById('yearSelect')?.addEventListener('change', function() {
    filterAttendance();
});

function filterAttendance() {
    const month = document.getElementById('monthSelect').value;
    const year = document.getElementById('yearSelect').value;
    window.location.href = `{{ route('employee.myAttendance') }}?month=${month}&year=${year}`;
}

function resetFilters() {
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth() + 1;
    const currentYear = currentDate.getFullYear();
    
    document.getElementById('monthSelect').value = currentMonth;
    document.getElementById('yearSelect').value = currentYear;
    
    filterAttendance();
}

// ============================================
// STAT CARDS - Filter Days
// ============================================
let activeFilter = 'all';

document.querySelectorAll('.stat-card').forEach(card => {
    card.addEventListener('click', function() {
        const status = this.dataset.status;
        
        document.querySelectorAll('.stat-card').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        
        activeFilter = status;
        
        const days = document.querySelectorAll('.calendar-day');
        let found = false;
        
        days.forEach(day => {
            const dayStatus = day.dataset.status;
            
            if (status === 'all') {
                day.style.opacity = '1';
                day.style.backgroundColor = '';
            } else if (dayStatus === status) {
                day.style.opacity = '1';
                day.style.backgroundColor = '#f8f9ff';
                if (!found) {
                    day.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    found = true;
                }
            } else if (dayStatus && dayStatus !== 'weekend') {
                day.style.opacity = '0.4';
            } else {
                day.style.opacity = '0.4';
            }
        });
        
        if (status !== 'all') {
            setTimeout(() => {
                if (activeFilter === status) {
                    days.forEach(day => {
                        day.style.opacity = '1';
                        day.style.backgroundColor = '';
                    });
                }
            }, 3000);
        }
    });
});

// ============================================
// MODAL FUNCTIONALITY
// ============================================
let modalInstance = null;

document.addEventListener('DOMContentLoaded', function() {
    modalInstance = new bootstrap.Modal(document.getElementById('attendanceModal'));
    
    document.querySelectorAll('.calendar-day').forEach(day => {
        if (day.dataset.date) {
            day.addEventListener('click', function(e) {
                const date = this.dataset.date;
                const attendance = @json($detailedAttendance);
                showAttendanceDetails(date, attendance[date] || null);
            });
        }
    });
});

function showAttendanceDetails(date, attendance) {
    const dateObj = new Date(date);
    const formattedDate = dateObj.toLocaleDateString('en-US', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
    
    let statusBadgeClass = 'status-absent-badge';
    let statusText = 'Absent';
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const isFutureDate = dateObj > today;
    
    // Check WFH status
    const isWFH = attendance && attendance.is_wfh === true;
    const wfhDetails = attendance ? attendance.wfh_details : null;
    const wfhStatus = attendance ? attendance.wfh_status : null;
    const wfhStatusBadge = attendance ? attendance.wfh_status_badge : null;
    const wfhReason = attendance ? attendance.wfh_reason : null;
    const wfhDetailsRequestId = attendance && attendance.wfh_details ? (attendance.wfh_details.request_id || null) : null;
    const wfhRequestId = attendance ? (attendance.wfh_request_id || wfhDetailsRequestId || null) : null;
    const wfhApprovedAt = attendance ? attendance.wfh_approved_at : null;
    const wfhRejectedAt = attendance ? attendance.wfh_rejected_at : null;
    const wfhRejectionReason = attendance ? attendance.wfh_rejection_reason : null;
    
    // Parse attendance status
    if (attendance) {
        switch(attendance.status) {
            case 'present': 
                statusBadgeClass = 'status-present-badge';
                if (attendance.leave_type) {
                    statusText = attendance.status_text || 'Present + ' + attendance.leave_type;
                } else {
                    statusText = 'Present';
                }
                break;
            case 'absent': 
                statusBadgeClass = 'status-absent-badge'; 
                statusText = 'Absent'; 
                break;
            case 'leave': 
                statusBadgeClass = 'status-leave-badge'; 
                statusText = attendance.leave_type ?? 'Leave'; 
                break;
            case 'weekend': 
                statusBadgeClass = 'status-weekend-badge'; 
                statusText = 'Weekly Off'; 
                break;
            case 'short_attendance':
                statusBadgeClass = 'status-leave-badge';
                statusText = 'Short Attendance';
                break;
            case 'upcoming':
                statusBadgeClass = 'status-weekend-badge';
                statusText = 'Upcoming Day';
                break;
        }
    }
    
    // Build WFH HTML for Modal
    let wfhHtml = '';
    if (isWFH && wfhDetails) {
        // Determine WFH status class and icon
        let wfhSectionClass = 'wfh-pending';
        let wfhStatusLabel = 'Pending';
        let wfhStatusBadgeClass = 'wfh-status-pending';
        let wfhIconClass = 'fas fa-clock';
        let wfhIconColor = '#f59e0b';
        
        if (wfhStatusBadge === 'approved' || wfhStatusBadge === 'Approved') {
            wfhSectionClass = 'wfh-approved';
            wfhStatusLabel = 'Approved';
            wfhStatusBadgeClass = 'wfh-status-approved';
            wfhIconClass = 'fas fa-check-circle';
            wfhIconColor = '#10b981';
        } else if (wfhStatusBadge === 'rejected' || wfhStatusBadge === 'Rejected') {
            wfhSectionClass = 'wfh-rejected';
            wfhStatusLabel = 'Rejected';
            wfhStatusBadgeClass = 'wfh-status-rejected';
            wfhIconClass = 'fas fa-times-circle';
            wfhIconColor = '#ef4444';
        }
        
        // Get dates
        const wfhStartDate = wfhDetails.start_date ? formatDate(wfhDetails.start_date) : 'N/A';
        const wfhEndDate = wfhDetails.end_date ? formatDate(wfhDetails.end_date) : 'N/A';
        const wfhCreatedAt = wfhDetails.created_at ? formatDateTime(wfhDetails.created_at) : 'N/A';
        const wfhApprovedAtFormatted = wfhApprovedAt ? formatDateTime(wfhApprovedAt) : null;
        const wfhRejectedAtFormatted = wfhRejectedAt ? formatDateTime(wfhRejectedAt) : null;
        
        wfhHtml = `
            <div class="modal-wfh-section wfh-${wfhSectionClass}">
                <div class="wfh-header">
                    <i class="${wfhIconClass}" style="color: ${wfhIconColor};"></i>
                    <span class="wfh-title">Work From Home</span>
                    <span class="wfh-status-badge ${wfhStatusBadgeClass}">${wfhStatusLabel}</span>
                </div>
                <div class="wfh-detail">
                    <span class="label">Request ID</span>
                    <span class="value">#${wfhRequestId || 'N/A'}</span>
                </div>
                <div class="wfh-detail">
                    <span class="label">Request Date</span>
                    <span class="value">${wfhCreatedAt}</span>
                </div>
                <div class="wfh-detail">
                    <span class="label">WFH Period</span>
                    <span class="value">${wfhStartDate} → ${wfhEndDate}</span>
                </div>
                ${wfhApprovedAtFormatted ? `
                    <div class="wfh-detail">
                        <span class="label">Approved At</span>
                        <span class="value" style="color: #10b981;">${wfhApprovedAtFormatted}</span>
                    </div>
                ` : ''}
                ${wfhRejectedAtFormatted ? `
                    <div class="wfh-detail">
                        <span class="label">Rejected At</span>
                        <span class="value" style="color: #ef4444;">${wfhRejectedAtFormatted}</span>
                    </div>
                ` : ''}
                ${wfhRejectionReason ? `
                    <div class="wfh-detail">
                        <span class="label">Rejection Reason</span>
                        <span class="value" style="color: #ef4444;">${wfhRejectionReason}</span>
                    </div>
                ` : ''}
                <div class="wfh-detail">
                    <span class="label">Reason</span>
                    <span class="value">${wfhReason || 'N/A'}</span>
                </div>
            </div>
        `;
    }
    
    // Build Shift HTML for Modal
    let shiftHtml = '';
    if (attendance && attendance.shift) {
        const shift = attendance.shift;
        const assignmentType = shift.assignment_type ? 
            shift.assignment_type.charAt(0).toUpperCase() + shift.assignment_type.slice(1) : 'N/A';
        
        let assignmentBadgeClass = 'bg-secondary';
        if (shift.assignment_type === 'individual') assignmentBadgeClass = 'bg-primary';
        else if (shift.assignment_type === 'department') assignmentBadgeClass = 'bg-info';
        else if (shift.assignment_type === 'default') assignmentBadgeClass = 'bg-secondary';
        
        shiftHtml = `
            <div class="modal-shift-info">
                <div class="shift-label"><i class="fas fa-clock"></i> Shift Details</div>
                <div class="shift-value">${shift.shift_name || 'N/A'}</div>
                <div class="shift-timing">
                    <i class="far fa-clock"></i> ${shift.start_time || ''} - ${shift.end_time || ''}
                    ${shift.working_hours ? ' (' + shift.working_hours + ' hrs)' : ''}
                </div>
                <div class="shift-assignment">
                    <span class="badge ${assignmentBadgeClass}">${assignmentType}</span>
                    ${shift.grace_minutes ? ' | Grace: ' + shift.grace_minutes + ' min' : ''}
                </div>
            </div>
        `;
    }
    
    // Build Details HTML
    let detailsHtml = `
        <div class="text-center mb-3">
            <div class="status-badge-modal ${statusBadgeClass}">${statusText}</div>
        </div>
        ${wfhHtml}
        ${shiftHtml}
        <div class="detail-row">
            <span class="detail-label">Day</span>
            <span class="detail-value">${dateObj.toLocaleDateString('en-US', { weekday: 'long' })}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Date</span>
            <span class="detail-value">${formattedDate}</span>
        </div>
    `;
    
    // Add Attendance Details (if not upcoming/weekend)
    if (isFutureDate && !attendance) {
        detailsHtml += `<div class="text-center text-muted py-3">This is a future date. Attendance will be recorded when the day arrives.</div>`;
    } else if (attendance && attendance.status !== 'weekend' && attendance.status !== 'upcoming') {
        
        if (attendance.check_in) {
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Check In</span>
                    <span class="detail-value">${attendance.check_in}</span>
                </div>
            `;
        }
        
        if (attendance.check_out) {
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Check Out</span>
                    <span class="detail-value">${attendance.check_out}</span>
                </div>
            `;
        }
        
        if (attendance.total_hours) {
            const formattedTotal = formatHoursForDisplay(attendance.total_hours);
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Total Hours</span>
                    <span class="detail-value">${formattedTotal}</span>
                </div>
            `;
        }
        
        if (attendance.required_hours) {
            const formattedRequired = formatHoursForDisplay(attendance.required_hours);
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Required Hours</span>
                    <span class="detail-value">${formattedRequired}</span>
                </div>
            `;
        }
        
        if (attendance.short_hours && attendance.short_hours > 0) {
            const shortByFormatted = formatHoursForDisplay(attendance.short_hours);
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Short By</span>
                    <span class="detail-value text-warning">${shortByFormatted}</span>
                </div>
            `;
        }
        
        if (attendance.late_minutes && attendance.late_minutes > 0) {
            const lateHours = Math.floor(attendance.late_minutes / 60);
            const lateMins = attendance.late_minutes % 60;
            let lateDisplay = '';
            if (lateHours > 0 && lateMins > 0) {
                lateDisplay = `${lateHours} hour${lateHours > 1 ? 's' : ''} ${lateMins} minute${lateMins > 1 ? 's' : ''}`;
            } else if (lateHours > 0) {
                lateDisplay = `${lateHours} hour${lateHours > 1 ? 's' : ''}`;
            } else {
                lateDisplay = `${lateMins} minute${lateMins > 1 ? 's' : ''}`;
            }
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Late By</span>
                    <span class="detail-value text-warning">${lateDisplay}</span>
                </div>
            `;
        }
        
        if (attendance.grace_status && attendance.grace_status !== 'No check-in record') {
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Grace Period</span>
                    <span class="detail-value">${attendance.grace_status}</span>
                </div>
            `;
        }
        
        if (attendance.leave_type) {
            const quotaStatus = attendance.within_quota !== undefined ? 
                (attendance.within_quota ? 'Within Quota ✓' : 'Exceeds Quota ⚠️') : '';
            const quotaClass = attendance.within_quota ? 'text-success' : 'text-danger';
            
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Leave Type</span>
                    <span class="detail-value">${attendance.leave_type}</span>
                </div>
            `;
            
            if (quotaStatus) {
                detailsHtml += `
                    <div class="detail-row">
                        <span class="detail-label">Quota Status</span>
                        <span class="detail-value ${quotaClass}">${quotaStatus}</span>
                    </div>
                `;
            }
            
            if (!attendance.within_quota && attendance.days_to_deduct) {
                detailsHtml += `
                    <div class="detail-row">
                        <span class="detail-label">Salary Impact</span>
                        <span class="detail-value text-danger">${attendance.days_to_deduct} day(s) will be deducted</span>
                    </div>
                `;
            }
            
            if (attendance.is_approved !== undefined) {
                const approvalStatus = attendance.is_approved ? 'Approved ✓' : 'Unapproved ⚠️';
                const approvalClass = attendance.is_approved ? 'text-success' : 'text-danger';
                detailsHtml += `
                    <div class="detail-row">
                        <span class="detail-label">Approval Status</span>
                        <span class="detail-value ${approvalClass}">${approvalStatus}</span>
                    </div>
                `;
            }
        }
        
        if (attendance.deduction_percentage && attendance.deduction_percentage > 0) {
            detailsHtml += `
                <div class="detail-row">
                    <span class="detail-label">Deduction</span>
                    <span class="detail-value text-danger">${attendance.deduction_percentage}% of daily salary</span>
                </div>
            `;
        }
    }
    
    if (!attendance || Object.keys(attendance).length === 0) {
        if (isFutureDate) {
            detailsHtml += `<div class="text-center text-muted py-3">This is a future date. No attendance record yet.</div>`;
        } else {
            detailsHtml += `<div class="text-center text-muted py-3">No attendance record found for this day</div>`;
        }
    }
    
    document.getElementById('modalBody').innerHTML = `
        <div class="text-center mb-3">
            <h6 class="text-muted">${formattedDate}</h6>
        </div>
        ${detailsHtml}
    `;
    
    modalInstance.show();
}

// ============================================
// HELPER FUNCTIONS
// ============================================
function formatHoursForDisplay(hours) {
    if (!hours) return null;
    var hrs = Math.floor(hours);
    var mins = Math.round((hours - hrs) * 60);
    if (mins == 60) {
        hrs++;
        mins = 0;
    }
    if (hrs > 0 && mins > 0) {
        return hrs + ' hours ' + mins + ' minutes';
    } else if (hrs > 0) {
        return hrs + ' hours';
    } else {
        return mins + ' minutes';
    }
}

function formatDate(dateStr) {
    if (!dateStr) return 'N/A';
    try {
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return 'N/A';
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    } catch (e) {
        return dateStr;
    }
}

function formatDateTime(dateStr) {
    if (!dateStr) return 'N/A';
    try {
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return 'N/A';
        return date.toLocaleString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (e) {
        return dateStr;
    }
}

function ucfirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}
</script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection