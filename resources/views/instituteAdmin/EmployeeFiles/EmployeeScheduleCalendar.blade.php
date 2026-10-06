@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-blue: #4361ee;
        --primary-dark: #3730a3;
        --accent-teal: #14b8a6;
        --accent-amber: #f59e0b;
        --light-bg: #fafbff;
        --card-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
        --hover-shadow: 0 20px 45px rgba(79, 70, 229, 0.12);
    }

    .dashboard-container {
        padding: 1.8rem 2rem;
        max-width: 1600px;
        margin: 0 auto;
    }

    .page-header {
        background: var(--primary-gradient);
        border-radius: 28px;
        padding: 1.8rem 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
    }

    .page-header h1 {
        font-size: 1.9rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.3rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .page-header p {
        color: rgba(255,255,255,0.85);
        margin-bottom: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 24px;
        padding: 1rem 1rem;
        transition: transform 0.2s ease, box-shadow 0.2s;
        box-shadow: var(--card-shadow);
        border: 1px solid rgba(0,0,0,0.03);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--hover-shadow);
    }

    .stat-icon {
        font-size: 1.6rem;
        margin-bottom: 0.3rem;
        display: inline-block;
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary-blue);
        line-height: 1.2;
    }

    .stat-label {
        color: #6b7280;
        font-weight: 500;
        margin-top: 5px;
        font-size: 0.7rem;
    }

    .filter-panel {
        background: white;
        border-radius: 28px;
        padding: 1.2rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--card-shadow);
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
        justify-content: space-between;
    }

    .filter-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
    }

    .filter-item {
        min-width: 160px;
    }

    .filter-item label {
        font-weight: 600;
        font-size: 0.7rem;
        color: #1e293b;
        margin-bottom: 5px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-item select, .filter-item input {
        border-radius: 16px;
        border: 1.5px solid #e2e8f0;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        background: white;
        width: 100%;
    }

    .btn-reset {
        background: #f1f5f9;
        border: none;
        border-radius: 40px;
        padding: 0.5rem 1.4rem;
        font-weight: 600;
        color: #334155;
        transition: 0.2s;
    }

    .btn-reset:hover {
        background: #e2e8f0;
    }

    .btn-apply {
        background: var(--primary-gradient);
        border: none;
        border-radius: 40px;
        padding: 0.5rem 1.4rem;
        font-weight: 600;
        color: white;
        transition: 0.2s;
    }

    .btn-apply:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .loading-overlay {
        position: fixed;
        inset: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.55);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        display: none;
    }

    .loading-spinner {
        width: 55px;
        height: 55px;
        border: 6px solid rgba(255, 255, 255, 0.25);
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .view-toggle {
        display: flex;
        gap: 10px;
        margin-bottom: 1.8rem;
        flex-wrap: wrap;
    }

    .view-btn {
        background: white;
        border: 1px solid #cbd5e1;
        border-radius: 40px;
        padding: 0.5rem 1.3rem;
        font-weight: 600;
        transition: all 0.2s;
        color: #334155;
        cursor: pointer;
        font-size: 0.85rem;
    }

    .view-btn.active {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        box-shadow: 0 6px 14px rgba(67, 97, 238, 0.25);
    }

    .view-btn i {
        margin-right: 6px;
    }

    .date-navigation {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .date-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-dark);
    }

    .nav-buttons {
        display: flex;
        gap: 10px;
    }

    .nav-btn {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 40px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .nav-btn:hover {
        background: #f1f5f9;
    }

    .timetable-grid-wrapper {
        background: white;
        border-radius: 28px;
        overflow-x: auto;
        box-shadow: var(--card-shadow);
        padding: 0.5rem;
    }

    .grid-timetable {
        min-width: 700px;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .grid-timetable th {
        background: #f8fafd;
        padding: 1rem 0.8rem;
        font-weight: 700;
        color: #1e293b;
        border-bottom: 2px solid #eef2ff;
        text-align: center;
        font-size: 0.8rem;
    }

    .grid-timetable td {
        border: 1px solid #edf2f7;
        vertical-align: top;
        padding: 0.6rem;
        background-color: white;
    }

    .time-slot-cell {
        background: #f9fafc;
        font-weight: 700;
        color: #2c3e66;
        text-align: center;
        vertical-align: middle;
        font-size: 0.75rem;
        white-space: nowrap;
    }

    .lecture-card, .duty-card {
        border-radius: 12px;
        padding: 8px 10px;
        margin-bottom: 8px;
        color: white;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }

    .lecture-card:hover, .duty-card:hover {
        transform: translateX(3px);
        filter: brightness(0.95);
    }

    .duty-card {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .lecture-card.online-lecture {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        border-left: 3px solid #a5f3fc;
    }

    .lecture-card.offline-lecture {
        background: linear-gradient(135deg, #64748b, #475569);
    }

    .lecture-card.has-override {
        background: linear-gradient(135deg, #f97316, #ea580c);
        border-left: 3px solid #ffedd5;
    }

    .card-title {
        font-weight: 700;
        font-size: 0.75rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .card-sub {
        font-size: 0.6rem;
        opacity: 0.9;
        display: flex;
        align-items: center;
        gap: 5px;
        flex-wrap: wrap;
        margin-top: 4px;
    }

    .badge-mode {
        background: rgba(0, 0, 0, 0.25);
        border-radius: 30px;
        padding: 2px 6px;
        font-size: 8px;
        font-weight: 600;
    }

    .empty-slot {
        color: #94a3b8;
        font-size: 0.7rem;
        text-align: center;
        padding: 12px 0;
        font-style: italic;
    }

    .month-view {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 12px;
        background: white;
        border-radius: 28px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
    }

    .month-day {
        background: #f8fafd;
        border-radius: 16px;
        padding: 10px;
        min-height: 120px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .month-day:hover {
        transform: translateY(-2px);
        box-shadow: var(--hover-shadow);
    }

    .month-day.empty {
        background: #f1f5f9;
        opacity: 0.5;
    }

    .month-day.current-month {
        background: white;
        border: 1px solid #e2e8f0;
    }

    .month-day.today {
        border: 2px solid #10b981;
        background: #ecfdf5;
    }

    .day-number {
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 8px;
        padding-bottom: 5px;
        border-bottom: 1px solid #e2e8f0;
    }

    .day-events {
        font-size: 0.7rem;
    }

    .day-event-item {
        padding: 3px 5px;
        margin-bottom: 3px;
        border-radius: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: white;
    }

    .day-event-item.lecture-online { background: #0ea5e9; }
    .day-event-item.lecture-offline { background: #64748b; }
    .day-event-item.lecture-override { background: #f97316; }
    .day-event-item.duty { background: #f59e0b; }

    .list-card-modern {
        background: white;
        border-radius: 28px;
        padding: 1rem;
        /*overflow-x: auto;*/
    }

    /*.list-table {*/
    /*    width: 100%;*/
    /*    border-collapse: collapse;*/
    /*}*/

    /*.list-table th {*/
    /*    background: #f1f5f9;*/
    /*    font-weight: 700;*/
    /*    padding: 12px 15px;*/
    /*    font-size: 0.7rem;*/
    /*    text-transform: uppercase;*/
    /*    letter-spacing: 0.5px;*/
    /*}*/

    /*.list-table td {*/
    /*    padding: 12px 15px;*/
    /*    vertical-align: middle;*/
    /*    font-size: 0.75rem;*/
    /*    border-bottom: 1px solid #eef2ff;*/
    /*}*/

    /*.list-table tr {*/
    /*    cursor: pointer;*/
    /*    transition: background 0.2s;*/
    /*}*/

    /*.list-table tr:hover {*/
    /*    background: #f8fafc;*/
    /*}*/

    .current-date-row {
        background: rgba(16, 185, 129, 0.08);
    }

    .current-date-badge {
        display: inline-block;
        background: #10b981;
        color: white;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 8px;
        font-weight: bold;
        margin-left: 6px;
    }

    .reassignment-alert {
        background: linear-gradient(135deg, #fef3c7, #fffbeb);
        border-left: 4px solid #f59e0b;
        border-radius: 12px;
        padding: 12px 20px;
        margin-bottom: 20px;
    }

    .modal-modern .modal-content {
        border-radius: 24px;
        border: none;
        overflow: hidden;
    }

    .modal-modern .modal-header {
        background: var(--primary-gradient);
        color: white;
        padding: 20px 25px;
        border: none;
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }
        
        .filter-panel {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-group {
            flex-direction: column;
        }
        
        .filter-item {
            width: 100%;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .view-toggle {
            justify-content: center;
        }
        
        .month-view {
            grid-template-columns: repeat(2, 1fr);
        }
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
            border-bottom: 1px solid #f1f5f9;
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

<div class="dashboard-container">
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1><i class="ri-calendar-schedule-fill me-3"></i>  Timetable</h1>
            <p>Welcome, {{ $employee->name ?? 'Employee' }}</p>
        </div>
        <div class="mt-2 mt-sm-0">
            <button class="btn btn-light rounded-pill shadow-sm d-none" id="exportScheduleBtn">
                <i class="ri-download-line me-2"></i> Export CSV
            </button>
        </div>
    </div>

    <div class="stats-grid d-none" id="statsGrid">
        <div class="stat-card"><div class="stat-icon">📚</div><div class="stat-number" id="totalLecturesStat">0</div><div class="stat-label">Total Lectures</div></div>
        <div class="stat-card"><div class="stat-icon">🌐</div><div class="stat-number" id="onlineLecturesStat">0</div><div class="stat-label">Online</div></div>
        <div class="stat-card"><div class="stat-icon">🏢</div><div class="stat-number" id="offlineLecturesStat">0</div><div class="stat-label">Offline</div></div>
        <div class="stat-card"><div class="stat-icon">⚡</div><div class="stat-number" id="overridesStat">0</div><div class="stat-label">Overrides</div></div>
        <div class="stat-card"><div class="stat-icon">📋</div><div class="stat-number" id="dutiesStat">0</div><div class="stat-label">Duties</div></div>
        <div class="stat-card"><div class="stat-icon">🔄</div><div class="stat-number" id="reassignedStat">0</div><div class="stat-label">Reassigned</div></div>
    </div>

    <div id="reassignmentSummary"></div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="filter-panel">
        <div class="filter-group">
            <div class="filter-item">
                <label><i class="ri-calendar-line"></i> Start Date</label>
                <input type="date" class="form-control" id="startDateFilter">
            </div>
            <div class="filter-item">
                <label><i class="ri-calendar-line"></i> End Date</label>
                <input type="date" class="form-control" id="endDateFilter">
            </div>
            <div class="filter-item">
                <label><i class="ri-wifi-line"></i> Lecture Mode</label>
                <select class="form-select" id="modeFilter">
                    <option value="">All Modes</option>
                    <option value="online">Online Only</option>
                    <option value="offline">Offline Only</option>
                </select>
            </div>
            <div class="filter-item">
                <label><i class="ri-repeat-line"></i> Show Reassigned Only</label>
                <select class="form-select" id="reassignmentFilter">
                    <option value="">All Lectures</option>
                    <option value="reassigned_to_me">Reassigned to Me</option>
                    <option value="reassigned_from_me">Reassigned by Me</option>
                </select>
            </div>
            <div class="filter-item">
                <button class="btn-apply" id="applyDateFilterBtn"><i class="ri-check-line"></i> Apply</button>
            </div>
        </div>
        <button class="btn-reset" id="resetFiltersBtn"><i class="ri-refresh-line"></i> Reset Filters</button>
    </div>

    <div class="view-toggle">
        <button class="view-btn active" data-view="day"><i class="ri-calendar-day-line"></i> Day View</button>
        <button class="view-btn" data-view="week"><i class="ri-calendar-week-line"></i> Week View</button>
        <button class="view-btn" data-view="month"><i class="ri-calendar-month-line"></i> Month View</button>
        <button class="view-btn" data-view="list"><i class="ri-list-check-2"></i> List View</button>
    </div>

    <div class="date-navigation" id="dateNavigation">
        <div class="date-title" id="dateRangeTitle">Today</div>
        <div class="nav-buttons">
            <button class="nav-btn" id="prevDateBtn"><i class="ri-arrow-left-line"></i> Previous</button>
            <button class="nav-btn" id="todayBtn"><i class="ri-home-line"></i> Today</button>
            <button class="nav-btn" id="nextDateBtn">Next <i class="ri-arrow-right-line"></i></button>
        </div>
    </div>

    <div id="weekView" class="timetable-grid-wrapper" style="display: none;">
        <table class="grid-timetable" id="weekTable">
            <thead id="weekHeader"></thead>
            <tbody id="weekBody"></tbody>
        </table>
    </div>

    <div id="dayView" class="timetable-grid-wrapper">
        <table class="grid-timetable" id="dayTable">
            <thead id="dayHeader"></thead>
            <tbody id="dayBody"></tbody>
        </table>
    </div>

    <div id="monthView" style="display: none;">
        <div class="month-view" id="monthCalendar"></div>
    </div>

    <div id="listView" style="display: none;">
        <div class="list-card-modern">
            <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                <table class="erp-table list-table">
                    <thead>
                        <tr>
                            <th class="sticky-main-2 sortable">Date</th>
                            <th class="sortable">Time</th>
                            <th class="sortable">Type</th>
                            <th class="sortable">Subject / Course</th>
                            <th class="sortable">Mode</th>
                            <th class="sortable">Location</th>
                            <th class="sortable">Status</th>
                        </tr>
                    </thead>
                    <tbody id="listBody"></tbody>
                </table>
            </div>
            
            <!-- Floating Horizontal Scrollbar -->
            <div class="table-scroll-top" id="tableScrollTop">
                <div class="table-scroll-inner"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-modern" id="eventDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Event Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="eventDetailBody">Loading...</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="printEventBtn"><i class="ri-printer-line"></i> Print</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
let rawAssignments = [];
let filteredEvents = [];
let currentView = 'day';
let currentDate = new Date();
let dynamicTimeSlots = [];
let dateRangeStart = null;
let dateRangeEnd = null;

function formatTime(timeStr) {
    if (!timeStr) return '--';
    // Convert 24-hour format to 12-hour AM/PM format
    let [hours, minutes] = timeStr.split(':');
    let hour = parseInt(hours);
    let ampm = hour >= 12 ? 'PM' : 'AM';
    hour = hour % 12 || 12;
    return `${hour.toString().padStart(2, '0')}:${minutes} ${ampm}`;
}

function formatTimeRange(startTime, endTime) {
    return `${formatTime(startTime)} - ${formatTime(endTime)}`;
}

function formatDateDisplay(date) {
    return date.toLocaleDateString('en-IN', { weekday: 'short', day: 'numeric', month: 'short' });
}

function isToday(dateStr) {
    if (!dateStr) return false;
    const today = new Date().toISOString().slice(0, 10);
    return dateStr === today;
}

function getCourseDisplay(event) {
    let parts = [];
    if (event.course_type) parts.push(event.course_type);
    if (event.branch_name) parts.push(event.branch_name);
    if (event.section_name) parts.push(event.section_name);
    return parts.join(' - ') || 'N/A';
}

function getModeBadge(lecture) {
    let mode = lecture.lecture_mode || 'offline';
    if (mode === 'online') {
        return '<span class="badge-mode"><i class="ri-wifi-line"></i> Online</span>';
    }
    return '<span class="badge-mode"><i class="ri-building-line"></i> Offline</span>';
}

function getEventColorClass(event) {
    if (event.event_type === 'duty') return 'duty-card';
    if (event.has_override) return 'lecture-card has-override';
    if (event.lecture_mode === 'online') return 'lecture-card online-lecture';
    return 'lecture-card offline-lecture';
}

function extractDynamicTimeSlots(events) {
    let slotsSet = new Set();
    events.forEach(ev => {
        if (ev.start_time) {
            slotsSet.add(ev.start_time);
        }
    });
    return Array.from(slotsSet).sort();
}

function showLoading() { 
    $('#loadingOverlay').css('display', 'flex'); 
}

function hideLoading() { 
    $('#loadingOverlay').css('display', 'none'); 
}

function applyFilters() {
    showLoading();
    setTimeout(() => {
        let modeFilter = $('#modeFilter').val();
        let reassignmentFilter = $('#reassignmentFilter').val();
        let startDateVal = $('#startDateFilter').val();
        let endDateVal = $('#endDateFilter').val();
        
        let filtered = rawAssignments.filter(ev => {
            let evMode = (ev.lecture_mode === 'online') ? 'online' : 'offline';
            if (modeFilter && evMode !== modeFilter) return false;
            
            if (reassignmentFilter === 'reassigned_to_me') {
                if (ev.event_type === 'duty') return false;
                return ev.reassignment_info && ev.reassignment_info.is_reassigned_to_me === true;
            }
            if (reassignmentFilter === 'reassigned_from_me') {
                if (ev.event_type === 'duty') return false;
                return ev.reassignment_info && ev.reassignment_info.is_reassigned_from_me === true;
            }
            
            // Date range filter
            if (startDateVal && ev.valid_from && ev.valid_from < startDateVal) return false;
            if (endDateVal && ev.valid_from && ev.valid_from > endDateVal) return false;
            
            return true;
        });
        
        filteredEvents = filtered;
        dynamicTimeSlots = extractDynamicTimeSlots(filteredEvents);
        updateStats();
        renderCurrentView();
        hideLoading();
    }, 400);
}

function updateStats() {
    let totalLect = filteredEvents.filter(e => e.event_type !== 'duty').length;
    let onlineCnt = filteredEvents.filter(e => e.event_type !== 'duty' && e.lecture_mode === 'online').length;
    let offlineCnt = totalLect - onlineCnt;
    let overrides = filteredEvents.filter(e => e.has_override === true).length;
    let dutiesCnt = filteredEvents.filter(e => e.event_type === 'duty').length;
    let reassignedCnt = filteredEvents.filter(e => e.reassignment_info && (e.reassignment_info.is_reassigned_to_me || e.reassignment_info.is_reassigned_from_me)).length;
    
    $('#totalLecturesStat').text(totalLect);
    $('#onlineLecturesStat').text(onlineCnt);
    $('#offlineLecturesStat').text(offlineCnt);
    $('#overridesStat').text(overrides);
    $('#dutiesStat').text(dutiesCnt);
    $('#reassignedStat').text(reassignedCnt);
    
    let reassignedToMe = filteredEvents.filter(e => e.reassignment_info && e.reassignment_info.is_reassigned_to_me).length;
    let reassignedFromMe = filteredEvents.filter(e => e.reassignment_info && e.reassignment_info.is_reassigned_from_me).length;
    
    let summaryHtml = '';
    if (reassignedToMe > 0) {
        summaryHtml += `<div class="reassignment-alert"><i class="ri-exchange-line me-2"></i> <strong>Notice:</strong> You have ${reassignedToMe} lecture(s) reassigned to you.</div>`;
    }
    if (reassignedFromMe > 0) {
        summaryHtml += `<div class="reassignment-alert" style="border-left-color: #f59e0b;"><i class="ri-exchange-line me-2"></i> <strong>Notice:</strong> You have ${reassignedFromMe} lecture(s) reassigned away from you.</div>`;
    }
    $('#reassignmentSummary').html(summaryHtml);
}

function getEventsForDate(dateStr) {
    return filteredEvents.filter(ev => ev.valid_from && ev.valid_from.slice(0, 10) === dateStr);
}

function renderEventCard(event) {
    let cardClass = getEventColorClass(event);
    let title = event.subject_display_name || event.title || 'Untitled';
    let courseInfo = getCourseDisplay(event);
    let location = event.location || (event.lecture_mode === 'online' ? 'Online' : 'Classroom');
    let modeHtml = (event.event_type !== 'duty') ? getModeBadge(event) : '';
    let overrideBadge = (event.has_override && event.event_type !== 'duty') ? 
        '<span class="badge-mode bg-warning text-dark ms-1"><i class="ri-flash-fill"></i></span>' : '';
    let timeDisplay = formatTimeRange(event.start_time, event.end_time);
    
    return `
        <div class="${cardClass}" onclick='showEventDetails(${JSON.stringify(event).replace(/"/g, '&quot;')})'>
            <div class="card-title"><i class="${event.event_type === 'duty' ? 'ri-briefcase-line' : 'ri-book-open-line'} me-1"></i> ${title.substring(0, 35)}</div>
            <div class="card-sub"><i class="ri-time-line"></i> ${timeDisplay}</div>
            <div class="card-sub"><i class="ri-graduation-cap-line"></i> ${courseInfo.substring(0, 30)}</div>
            <div class="card-sub">📍 ${location}</div>
            <div class="d-flex justify-content-between align-items-center mt-1">${modeHtml} ${overrideBadge}</div>
        </div>
    `;
}

function renderWeekView() {
    let startOfWeek = new Date(currentDate);
    let day = startOfWeek.getDay();
    let diff = day === 0 ? -6 : 1 - day;
    startOfWeek.setDate(currentDate.getDate() + diff);
    
    let weekDates = [];
    for (let i = 0; i < 7; i++) {
        let date = new Date(startOfWeek);
        date.setDate(startOfWeek.getDate() + i);
        weekDates.push(date);
    }
    
    $('#dateRangeTitle').text(`${formatDateDisplay(weekDates[0])} - ${formatDateDisplay(weekDates[6])}`);
    
    let headerHtml = '<tr><th>Time</th>';
    weekDates.forEach(date => {
        let dateStr = date.toISOString().slice(0, 10);
        let isTodayDate = isToday(dateStr);
        headerHtml += `<th class="${isTodayDate ? 'current-date-header' : ''}">${date.toLocaleDateString('en-IN', { weekday: 'short' })}<br><small>${date.getDate()}</small></th>`;
    });
    headerHtml += '</tr>';
    $('#weekHeader').html(headerHtml);
    
    let tbody = $('#weekBody');
    tbody.empty();
    
    if (dynamicTimeSlots.length === 0 && filteredEvents.length === 0) {
        tbody.html('<tr><td colspan="8" class="text-center py-4">No lectures scheduled for this week</td></tr>');
        return;
    }
    
    let slotsToUse = dynamicTimeSlots.length > 0 ? dynamicTimeSlots : ['09:00:00', '10:00:00', '11:00:00', '12:00:00', '13:00:00', '14:00:00', '15:00:00', '16:00:00'];
    
    for (let slot of slotsToUse) {
        let row = $('<tr>');
        row.append(`<td class="time-slot-cell"><strong>${formatTime(slot)}</strong></td>`);
        
        for (let date of weekDates) {
            let dateStr = date.toISOString().slice(0, 10);
            let dayEvents = getEventsForDate(dateStr);
            let slotEvents = dayEvents.filter(ev => ev.start_time === slot);
            let cellContent = $('<td class="p-2" style="vertical-align:top;">');
            
            if (slotEvents.length === 0) {
                cellContent.html('<div class="empty-slot">—</div>');
            } else {
                slotEvents.forEach(ev => {
                    cellContent.append(renderEventCard(ev));
                });
            }
            row.append(cellContent);
        }
        tbody.append(row);
    }
}

function renderDayView() {
    let dateStr = currentDate.toISOString().slice(0, 10);
    $('#dateRangeTitle').html(currentDate.toLocaleDateString('en-IN', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }));
    
    let headerHtml = `<tr><th>Time</th><th class="${isToday(dateStr) ? 'current-date-header' : ''}">${currentDate.toLocaleDateString('en-IN', { weekday: 'long' })}<br><small>${currentDate.getDate()}</small></th></tr>`;
    $('#dayHeader').html(headerHtml);
    
    let tbody = $('#dayBody');
    tbody.empty();
    
    let dayEvents = getEventsForDate(dateStr);
    
    let slotsToUse = dynamicTimeSlots.length > 0 ? dynamicTimeSlots : ['09:00:00', '10:00:00', '11:00:00', '12:00:00', '13:00:00', '14:00:00', '15:00:00', '16:00:00'];
    
    for (let slot of slotsToUse) {
        let row = $('<tr>');
        row.append(`<td class="time-slot-cell"><strong>${formatTime(slot)}</strong></td>`);
        let slotEvents = dayEvents.filter(ev => ev.start_time === slot);
        let cellContent = $('<td class="p-2" style="vertical-align:top;">');
        
        if (slotEvents.length === 0) {
            cellContent.html('<div class="empty-slot">— No lectures —</div>');
        } else {
            slotEvents.forEach(ev => {
                cellContent.append(renderEventCard(ev));
            });
        }
        row.append(cellContent);
        tbody.append(row);
    }
    
    if (dayEvents.length === 0) {
        tbody.html('<tr><td colspan="2" class="text-center py-4">No lectures scheduled for this day</td></tr>');
    }
}

function renderMonthView() {
    let year = currentDate.getFullYear();
    let month = currentDate.getMonth();
    let firstDay = new Date(year, month, 1);
    let lastDay = new Date(year, month + 1, 0);
    let startDayOfWeek = firstDay.getDay();
    let daysInMonth = lastDay.getDate();
    
    let calendar = $('#monthCalendar');
    calendar.empty();
    
    let dayHeaders = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    dayHeaders.forEach(day => {
        calendar.append(`<div class="month-day empty" style="background: none; border: none; min-height: auto;"><strong>${day}</strong></div>`);
    });
    
    let offset = (startDayOfWeek === 0 ? 6 : startDayOfWeek - 1);
    for (let i = 0; i < offset; i++) {
        calendar.append(`<div class="month-day empty"><div class="day-number"></div></div>`);
    }
    
    let todayStr = new Date().toISOString().slice(0, 10);
    for (let d = 1; d <= daysInMonth; d++) {
        let date = new Date(year, month, d);
        let dateStr = date.toISOString().slice(0, 10);
        let dayEvents = getEventsForDate(dateStr);
        let isTodayDate = dateStr === todayStr;
        
        let dayDiv = $(`<div class="month-day ${isTodayDate ? 'today' : 'current-month'}" data-date="${dateStr}">`);
        dayDiv.append(`<div class="day-number">${d}${isTodayDate ? ' <span class="current-date-badge">Today</span>' : ''}</div>`);
        
        let eventsHtml = '<div class="day-events">';
        dayEvents.slice(0, 3).forEach(ev => {
            let eventClass = ev.event_type === 'duty' ? 'duty' : 
                (ev.has_override ? 'lecture-override' : (ev.lecture_mode === 'online' ? 'lecture-online' : 'lecture-offline'));
            eventsHtml += `<div class="day-event-item ${eventClass}" title="${ev.subject_display_name || ev.title}">${formatTime(ev.start_time)} ${ev.subject_display_name || ev.title}</div>`;
        });
        if (dayEvents.length > 3) {
            eventsHtml += `<div class="text-muted small">+${dayEvents.length - 3} more</div>`;
        }
        eventsHtml += '</div>';
        dayDiv.append(eventsHtml);
        dayDiv.click(() => {
            currentDate = new Date(dateStr);
            switchView('day');
        });
        calendar.append(dayDiv);
    }
    
    $('#dateRangeTitle').html(currentDate.toLocaleDateString('en-IN', { month: 'long', year: 'numeric' }));
}

function renderListView() {
    let tbody = $('#listBody');
    tbody.empty();
    
    let sortedEvents = [...filteredEvents].sort((a, b) => {
        if (a.valid_from !== b.valid_from) return (a.valid_from || '').localeCompare(b.valid_from || '');
        return (a.start_time || '').localeCompare(b.start_time || '');
    });
    
    sortedEvents.forEach(ev => {
        let dateStr = ev.valid_from ? new Date(ev.valid_from).toLocaleDateString('en-IN') : 'N/A';
        let isTodayDate = isToday(ev.valid_from);
        let modeDisplay = (ev.event_type === 'duty') ? '—' : 
            (ev.lecture_mode === 'online' ? '<span class="badge bg-info">Online</span>' : '<span class="badge bg-secondary">Offline</span>');
        if (ev.has_override && ev.event_type !== 'duty') {
            modeDisplay += ' <span class="badge bg-warning text-dark">Override</span>';
        }
        
        let typeBadge = ev.event_type === 'duty' ? 
            '<span class="badge bg-warning">Duty</span>' : 
            '<span class="badge bg-primary">Lecture</span>';
        
        let reassignBadge = '';
        if (ev.reassignment_info) {
            if (ev.reassignment_info.is_reassigned_to_me) reassignBadge = ' <span class="badge bg-success">Reassigned to Me</span>';
            else if (ev.reassignment_info.is_reassigned_from_me) reassignBadge = ' <span class="badge bg-warning text-dark">Reassigned Away</span>';
        }
        
        let subjectLine = ev.subject_display_name || ev.title || '—';
        let courseLine = getCourseDisplay(ev);
        let timeDisplay = formatTimeRange(ev.start_time, ev.end_time);
        
        let row = $(`<tr class="${isTodayDate ? 'current-date-row' : ''}" onclick='showEventDetails(${JSON.stringify(ev).replace(/"/g, '&quot;')})'>`);
        row.html(`
            <td class="sticky-main-2"><strong>${dateStr}${isTodayDate ? ' <span class="current-date-badge">TODAY</span>' : ''}</strong></td>
            <td>${timeDisplay}</span></td>
            <td>${typeBadge}${reassignBadge}</span></td>
            <td><strong>${subjectLine}</strong><br><small class="text-muted">${courseLine}</small></td>
            <td>${modeDisplay}</span></td>
            <td>${ev.location || ev.venue || '—'}</span></td>
            <td><span class="badge ${ev.status === 'active' ? 'bg-success' : 'bg-secondary'}">${ev.status || 'Active'}</span></td>
        `);
        tbody.append(row);
    });
    
    if (sortedEvents.length === 0) {
        tbody.html('<tr><td colspan="7" class="text-center text-muted py-4">No lectures or duties found</td></tr>');
    }
    
    $('#dateRangeTitle').html('All Events');
}

function renderCurrentView() {
    if (currentView === 'week') {
        $('#weekView').show();
        $('#dayView').hide();
        $('#monthView').hide();
        $('#listView').hide();
        renderWeekView();
    } else if (currentView === 'day') {
        $('#weekView').hide();
        $('#dayView').show();
        $('#monthView').hide();
        $('#listView').hide();
        renderDayView();
    } else if (currentView === 'month') {
        $('#weekView').hide();
        $('#dayView').hide();
        $('#monthView').show();
        $('#listView').hide();
        renderMonthView();
    } else if (currentView === 'list') {
        $('#weekView').hide();
        $('#dayView').hide();
        $('#monthView').hide();
        $('#listView').show();
        renderListView();
    }
}

function switchView(view) {
    currentView = view;
    $('.view-btn').removeClass('active');
    $(`.view-btn[data-view="${view}"]`).addClass('active');
    renderCurrentView();
}

function prevPeriod() {
    if (currentView === 'day') {
        currentDate.setDate(currentDate.getDate() - 1);
    } else if (currentView === 'week') {
        currentDate.setDate(currentDate.getDate() - 7);
    } else if (currentView === 'month') {
        currentDate.setMonth(currentDate.getMonth() - 1);
    }
    renderCurrentView();
}

function nextPeriod() {
    if (currentView === 'day') {
        currentDate.setDate(currentDate.getDate() + 1);
    } else if (currentView === 'week') {
        currentDate.setDate(currentDate.getDate() + 7);
    } else if (currentView === 'month') {
        currentDate.setMonth(currentDate.getMonth() + 1);
    }
    renderCurrentView();
}

function goToToday() {
    currentDate = new Date();
    renderCurrentView();
}

function showEventDetails(event) {
    let isDuty = (event.event_type === 'duty');
    let dateStr = event.valid_from ? new Date(event.valid_from).toLocaleDateString('en-IN', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    }) : 'N/A';
    
    let courseInfo = getCourseDisplay(event);
    let timeDisplay = formatTimeRange(event.start_time, event.end_time);
    
    let modeHtml = '';
    if (!isDuty) {
        let mode = event.lecture_mode || 'offline';
        modeHtml = `
            <div class="alert ${mode === 'online' ? 'alert-info' : 'alert-secondary'} mt-3">
                <i class="ri-information-line"></i> <strong>Mode:</strong> ${mode.toUpperCase()}
                ${event.has_override ? '<span class="badge bg-warning text-dark ms-2">Override Active</span>' : ''}
            </div>
        `;
        if (mode === 'online' && event.meeting_link) {
            modeHtml += `
                <div class="alert alert-info">
                    <i class="ri-video-line"></i> <strong>Meeting Link:</strong> 
                    <a href="${event.meeting_link}" target="_blank">${event.meeting_link}</a>
                    ${event.meeting_id ? `<br><strong>Meeting ID:</strong> <code>${event.meeting_id}</code>` : ''}
                    ${event.meeting_password ? `<br><strong>Password:</strong> <code>${event.meeting_password}</code>` : ''}                 
                    ${event.meeting_instructions ? `<br><strong>Instructions:</strong><br>${event.meeting_instructions.replace(/\n/g, '<br>')}` : ''}
                </div>
            `;
        }
    }
    
    let reassignHtml = '';
    if (event.reassignment_info) {
        if (event.reassignment_info.is_reassigned_to_me) {
            reassignHtml = `<div class="alert alert-success"><i class="ri-exchange-line"></i> <strong>Reassigned to You</strong> from ${event.reassignment_info.reassigned_from_employee_name || 'another employee'}</div>`;
        } else if (event.reassignment_info.is_reassigned_from_me) {
            reassignHtml = `<div class="alert alert-warning"><i class="ri-exchange-line"></i> <strong>Reassigned Away</strong> to ${event.reassignment_info.reassigned_to_employee_name || 'another employee'}</div>`;
        }
    }
    
    let body = `
        <div class="row">
            <div class="col-12">
                <h4 class="fw-bold mb-2">${event.subject_display_name || event.title}</h4>
                <p class="text-muted">${isDuty ? 'Duty' : 'Lecture'}</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <p><strong><i class="ri-graduation-cap-line"></i> Course:</strong> ${courseInfo || 'N/A'}</p>
                <p><strong><i class="ri-building-line"></i> Department:</strong> ${event.department_name || 'N/A'}</p>
                <p><strong><i class="ri-map-pin-line"></i> Location:</strong> ${event.location || event.venue || 'Not specified'}</p>
            </div>
            <div class="col-md-6">
                <p><strong><i class="ri-calendar-line"></i> Date:</strong> ${dateStr}</p>
                <p><strong><i class="ri-time-line"></i> Time:</strong> ${timeDisplay}</p>
                ${!isDuty ? `<p><strong><i class="ri-repeat-line"></i> Frequency:</strong> ${event.frequency_label || event.frequency || 'Regular'}</p>` : ''}
                ${event.priority ? `<p><strong><i class="ri-flag-line"></i> Priority:</strong> ${event.priority.toUpperCase()}</p>` : ''}
            </div>
        </div>
        ${reassignHtml}
        ${modeHtml}
        ${event.remarks ? `<div class="alert alert-light"><i class="ri-chat-1-line"></i> <strong>Remarks:</strong> ${event.remarks}</div>` : ''}
    `;
    
    $('#eventDetailBody').html(body);
    new bootstrap.Modal(document.getElementById('eventDetailModal')).show();
}

function exportSchedule() {
    let dataToExport = filteredEvents.length ? filteredEvents : rawAssignments;
    let csvRows = [["Type", "Subject", "Course", "Department", "Date", "Start Time", "End Time", "Mode", "Location", "Has Override", "Reassigned"]];
    
    dataToExport.forEach(ev => {
        let reassigned = '';
        if (ev.reassignment_info) {
            if (ev.reassignment_info.is_reassigned_to_me) reassigned = 'Reassigned to Me';
            else if (ev.reassignment_info.is_reassigned_from_me) reassigned = 'Reassigned Away';
        }
        
        csvRows.push([
            ev.event_type === 'duty' ? 'Duty' : 'Lecture',
            ev.subject_display_name || ev.title || '',
            getCourseDisplay(ev),
            ev.department_name || '',
            ev.valid_from || '',
            ev.start_time || '',
            ev.end_time || '',
            ev.lecture_mode || 'offline',
            ev.location || '',
            ev.has_override ? 'Yes' : 'No',
            reassigned
        ]);
    });
    
    let csvContent = csvRows.map(row => row.map(cell => `"${String(cell).replace(/"/g, '""')}"`).join(",")).join("\n");
    let blob = new Blob([csvContent], {type: 'text/csv'});
    let link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'my_schedule.csv';
    link.click();
    URL.revokeObjectURL(link.href);
}

function resetFilters() {
    $('#modeFilter').val('');
    $('#reassignmentFilter').val('');
    $('#startDateFilter').val('');
    $('#endDateFilter').val('');
    applyFilters();
}

$(document).ready(function() {
    rawAssignments = @json($assignments);
    rawAssignments = rawAssignments.map(a => ({ 
        ...a, 
        event_type: a.event_type || 'lecture', 
        lecture_mode: a.lecture_mode || 'offline', 
        has_override: a.has_override || false 
    }));
    
    // Set currentDate to TODAY's date
    currentDate = new Date();
    
    applyFilters();
    
    $('.view-btn').click(function() {
        switchView($(this).data('view'));
    });
    
    $('#modeFilter, #reassignmentFilter').on('change', applyFilters);
    $('#applyDateFilterBtn').click(applyFilters);
    $('#resetFiltersBtn').click(resetFilters);
    $('#exportScheduleBtn').click(exportSchedule);
    $('#prevDateBtn').click(prevPeriod);
    $('#nextDateBtn').click(nextPeriod);
    $('#todayBtn').click(goToToday);
    $('#printEventBtn').click(function() {
        let printContent = $('#eventDetailBody').html();
        let printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html><head><title>Event Details</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
            </head><body><div class="container py-4">${printContent}</div></body></html>
        `);
        printWindow.document.close();
        printWindow.print();
    });
});
</script>
@endsection