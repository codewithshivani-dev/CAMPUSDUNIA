@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')
<style>
   :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-blue: #4361ee;
        --primary-dark: #3730a3;
        --online-color: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --offline-color: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        --light-bg: #fafbff;
        --card-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }

    body {
        background: #f5f7fb;
    }

    .page-header {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        border-radius: 24px;
        padding: 30px 35px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
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
        font-size: 2rem;
        font-weight: 700;
        color: white;
        margin-bottom: 8px;
    }

    .page-header p {
        color: rgba(255,255,255,0.9);
        margin-bottom: 0;
    }

    .info-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 25px;
        box-shadow: var(--card-shadow);
        border: 1px solid #eef2ff;
    }

    .info-card h5 {
        color: var(--primary-dark);
        font-weight: 700;
        margin-bottom: 15px;
    }

    .timetable-card {
        background: white;
        border-radius: 12px;
        padding: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        /*overflow-x: auto;*/
    }

    .time-header {
        background: #0d6efd !important;
        color: white;
        font-weight: bold;
        text-align: center;
        padding: 12px;
        font-size: 14px;
    }

    .day-header {
        background: #343a40 !important;
        color: white;
        font-weight: bold;
        text-align: center;
        padding: 12px;
        font-size: 14px;
    }

    .day-header.weekend {
        background: #dc3545 !important;
    }

    .time-cell {
        background: #f8f9fa;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
        font-size: 13px;
    }

    .event-box {
        border-radius: 8px;
        padding: 8px;
        color: white;
        font-size: 12px;
        min-height: 100px;
        transition: transform 0.2s;
        cursor: pointer;
        position: relative;
    }

    .event-box:hover {
        transform: scale(1.02);
        filter: brightness(0.95);
    }

    /* Mode-based colors */
    .event-box.online {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: 2px solid #fff;
    }

    .event-box.offline {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        border: 2px solid #fff;
    }

    .event-box.mixed {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }

    .event-title {
        font-weight: bold;
        font-size: 13px;
        margin-bottom: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .event-faculty {
        font-size: 10px;
        margin-bottom: 3px;
        opacity: 0.9;
    }

    .event-location {
        font-size: 9px;
        margin-bottom: 3px;
        opacity: 0.8;
    }

    .event-badge {
        font-size: 9px;
        margin-top: 5px;
        padding: 2px 5px;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 3px;
        display: inline-block;
    }

    .empty-slot {
        padding: 10px;
        text-align: center;
        color: #999;
        background: #fafafa;
        border-radius: 6px;
        font-size: 12px;
        min-height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .view-btn {
        min-width: 100px;
    }

    .view-btn.active {
        background: var(--primary-blue);
        color: white;
        border-color: var(--primary-blue);
    }

    .loading-overlay {
        position: fixed;
        inset: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        display: none;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #0d6efd;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }


    /* Month view styles */
    .month-card {
        cursor: pointer;
        transition: all 0.3s ease;
        height: 100%;
    }

    .month-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }

    .month-card.weekend {
        background: #fff3f3;
        border-left: 4px solid #dc3545;
    }

    .month-card.online-day .card-header {
        background: linear-gradient(135deg, #10b981, #059669) !important;
    }

    .month-card.offline-day .card-header {
        background: linear-gradient(135deg, #6b7280, #4b5563) !important;
    }

    .current-date-header {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: white;
        font-weight: bold;
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
    }

    .current-date-card {
        border: 3px solid #10b981;
        background: #ecfdf5;
    }

    .mode-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: bold;
        margin-left: 5px;
    }

    .mode-badge.online {
        background: #10b981;
        color: white;
    }

    .mode-badge.offline {
        background: #6b7280;
        color: white;
    }

    @media (max-width: 768px) {
        .event-box {
            min-height: 70px;
            font-size: 10px;
            padding: 5px;
        }
        .event-title {
            font-size: 11px;
        }
        .time-cell, .day-header, .time-header {
            font-size: 11px;
            padding: 8px;
        }
    }

    .stats-bar {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 10px 15px;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-active {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    /* Mode toggle tabs - FIXED */
    .mode-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        background: white;
        padding: 5px;
        border-radius: 50px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .mode-tab {
        flex: 1;
        padding: 10px 20px;
        border: none;
        background: transparent;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
        color: #6c757d;
    }

    /* All Classes tab - Blue when active */
    .mode-tab.all-tab.active {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
    }

    /* Online Classes tab - Green when active */
    .mode-tab.online-tab.active {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    /* Offline Classes tab - Gray when active */
    .mode-tab.offline-tab.active {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }
    
    /* Hover effects */
    .mode-tab.all-tab:hover:not(.active) {
        background: rgba(67, 97, 238, 0.1);
        color: #4361ee;
    }
    
    .mode-tab.online-tab:hover:not(.active) {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .mode-tab.offline-tab:hover:not(.active) {
        background: rgba(107, 114, 128, 0.1);
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

<div class="loading-overlay">
    <div class="loading-spinner"></div>
</div>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1>
                <i class="fas fa-calendar-alt me-3"></i>
                 Timetable
            </h1>
            <p>View your complete lecture schedule</p>
        </div>
        <div class="btn-group mt-3 mt-md-0">
            <button class="btn btn-light view-btn" data-view="day" onclick="switchView('day')">
                <i class="fas fa-calendar-day"></i> Day
            </button>
            <button class="btn btn-light view-btn" data-view="week" onclick="switchView('week')">
                <i class="fas fa-calendar-week"></i> Week
            </button>
            <button class="btn btn-light view-btn" data-view="month" onclick="switchView('month')">
                <i class="fas fa-calendar-alt"></i> Month
            </button>
            <button class="btn btn-light view-btn" data-view="list" onclick="switchView('list')">
                <i class="fas fa-list"></i> List
            </button>
        </div>
    </div>

    <!-- Student Info Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-6">
            <div class="info-card">
                <small class="text-muted">Student Name</small>
                <h5 class="mb-0">{{ $student->first_name }} {{ $student->last_name }}</h5>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="info-card">
                <small class="text-muted">Registration No.</small>
                <h5 class="mb-0">{{ $student->registration_number ?? 'N/A' }}</h5>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="info-card">
                <small class="text-muted">Course</small>
                <h5 class="mb-0">{{ $academicDetails->course_subtype ?? 'N/A' }}</h5>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="info-card">
                <small class="text-muted">Section</small>
                <h5 class="mb-0">{{ $sectionName ?? 'N/A' }}</h5>
            </div>
        </div>
    </div>

    <!-- Mode Tabs (Online/Offline) - FIXED with proper classes -->
    <div class="mode-tabs">
        <button class="mode-tab all-tab active" data-mode="all" onclick="filterByMode('all')">
            <i class="fas fa-globe"></i> All Classes
        </button>
        <button class="mode-tab online-tab" data-mode="online" onclick="filterByMode('online')">
            <i class="fas fa-video"></i> Online Classes
        </button>
        <button class="mode-tab offline-tab" data-mode="offline" onclick="filterByMode('offline')">
            <i class="fas fa-building"></i> Offline Classes
        </button>
    </div>

    <!-- Stats Bar -->
    <div class="stats-bar" id="statsBar" style="display: none;">
        <div><i class="fas fa-chalkboard-user"></i> <span id="lectureCount">0</span> Lectures</div>
        <div><i class="fas fa-video"></i> <span id="onlineCount">0</span> Online</div>
        <div><i class="fas fa-building"></i> <span id="offlineCount">0</span> Offline</div>
        <div><i class="fas fa-calendar-week"></i> <span id="dateRange"></span></div>
    </div>
    
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="startDate"><i class="fas fa-calendar"></i> Start Date</label>
                    <input type="date" class="form-control" id="startDate">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="endDate"><i class="fas fa-calendar"></i> End Date</label>
                    <input type="date" class="form-control" id="endDate">
                </div>
                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <button class="btn btn-secondary w-100" onclick="resetFilters()">
                        <i class="fas fa-sync-alt"></i> Reset to Current Week
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- WEEK VIEW -->
    <div id="weekView" class="timetable-card" style="display: none;">
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table table table-bordered align-middle mb-0" id="weekTable">
                <thead id="weekTableHeader"></thead>
                <tbody id="timetableBody">
                    <tr><td colspan="10" class="text-center py-5">Loading timetable...</td></tr>
                </tbody>
            </table>
        </div>
        
        <!-- Floating Horizontal Scrollbar -->
        <div class="table-scroll-top" id="tableScrollTop">
            <div class="table-scroll-inner"></div>
        </div>
    </div>

    <!-- DAY VIEW (Default view) -->
    <div id="dayView" class="timetable-card" style="display: block;">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h4 id="dayViewTitle">Day View</h4>
            </div>
            <div class="btn-group btn-group-sm">
                <button class="btn btn-secondary" onclick="jumpToPreviousDay()"><i class="fas fa-arrow-left"></i> Prev</button>
                <button class="btn btn-secondary" onclick="jumpToToday()"><i class="fas fa-calendar-day"></i> Today</button>
                <button class="btn btn-secondary" onclick="jumpToNextDay()">Next <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>
        <div id="dayViewBody">Loading day schedule...</div>
    </div>

    <!-- MONTH VIEW -->
    <div id="monthView" style="display: none;">
        <div class="row" id="monthCalendar">
            <div class="col-12 text-center py-5">Loading month view...</div>
        </div>
    </div>

    <!-- LIST VIEW -->
    <div id="listView" style="display: none;">
        <div class="timetable-card">
            <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                <table class="erp-table table table-hover">
                    <thead>
                        <tr>
                            <th class="sticky-main-2 sortable">Date</th>
                            <th class="sortable">Time</th>
                            <th class="sortable">Subject</th>
                            <th class="sortable">Faculty</th>
                            <th class="sortable">Mode</th>
                            <th class="sortable">Location</th>
                        </tr>
                    </thead>
                    <tbody id="listViewBody"></tbody>
                </table>
            </div>
            <!-- Floating Horizontal Scrollbar -->
            <div class="table-scroll-top" id="tableScrollTop">
                <div class="table-scroll-inner"></div>
            </div>
        </div>
    </div>
</div>

<!-- Event Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalTitle">Lecture Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailsModalBody">Loading...</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
let currentView = 'day'; // Changed to 'day' as default
let currentData = null;
let currentModeFilter = 'all'; // all, online, offline

function showLoading() { $('.loading-overlay').css('display', 'flex'); }
function hideLoading() { $('.loading-overlay').css('display', 'none'); }

// DEFINE formatDateInput FIRST (before any function that uses it)
function formatDateInput(date) {
    return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
}

function getCurrentDateStr() {
    let today = new Date();
    return formatDateInput(today);
}

function setDefaultDayDates() {
    let today = new Date();
    let dateValue = formatDateInput(today);
    document.getElementById('startDate').value = dateValue;
    document.getElementById('endDate').value = dateValue;
}

function setDefaultWeekDates() {
    let today = new Date();
    let currentDay = today.getDay();
    // Adjust to get Monday as first day of week (0 = Sunday, 1 = Monday)
    let diffToMonday = currentDay === 0 ? -6 : 1 - currentDay;
    let startOfWeek = new Date(today);
    startOfWeek.setDate(today.getDate() + diffToMonday);
    let endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6);
    
    let startDateValue = formatDateInput(startOfWeek);
    let endDateValue = formatDateInput(endOfWeek);
    
    document.getElementById('startDate').value = startDateValue;
    document.getElementById('endDate').value = endDateValue;
    
}

function setDefaultMonthDates() {
    let today = new Date();
    let startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    let endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    
    let startDateValue = formatDateInput(startOfMonth);
    let endDateValue = formatDateInput(endOfMonth);
    
    document.getElementById('startDate').value = startDateValue;
    document.getElementById('endDate').value = endDateValue;
    
}

function jumpToPreviousDay() {
    let currentDate = new Date(document.getElementById('startDate').value);
    currentDate.setDate(currentDate.getDate() - 1);
    let nextDate = formatDateInput(currentDate);
    document.getElementById('startDate').value = nextDate;
    document.getElementById('endDate').value = nextDate;
    loadTimetable();
}

function jumpToNextDay() {
    let currentDate = new Date(document.getElementById('startDate').value);
    currentDate.setDate(currentDate.getDate() + 1);
    let nextDate = formatDateInput(currentDate);
    document.getElementById('startDate').value = nextDate;
    document.getElementById('endDate').value = nextDate;
    loadTimetable();
}

function jumpToToday() {
    let today = new Date();
    let todayValue = formatDateInput(today);
    document.getElementById('startDate').value = todayValue;
    document.getElementById('endDate').value = todayValue;
    loadTimetable();
}

function filterByMode(mode) {
    showLoading();
    currentModeFilter = mode;
    
    // Update tab UI - FIXED: Remove active class from all tabs first, then add to specific tab
    document.querySelectorAll('.mode-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Add active class to the clicked tab based on its class
    if (mode === 'all') {
        document.querySelector('.mode-tab.all-tab').classList.add('active');
    } else if (mode === 'online') {
        document.querySelector('.mode-tab.online-tab').classList.add('active');
    } else if (mode === 'offline') {
        document.querySelector('.mode-tab.offline-tab').classList.add('active');
    }
    
    renderCurrentView();
    hideLoading();
}

function filterEventByMode(event) {
    if (currentModeFilter === 'all') return true;
    if (currentModeFilter === 'online') return event.lecture_mode === 'online';
    if (currentModeFilter === 'offline') return event.lecture_mode === 'offline';
    return true;
}

function loadTimetable() {
    showLoading();
    const params = {
        start_date: document.getElementById('startDate').value,
        end_date: document.getElementById('endDate').value
    };
    
    $.ajax({
        url: '{{ route("student.timetable.data") }}',
        data: params,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                currentData = response;
                updateStats();
                renderCurrentView();
            } else {
                showError('Failed to load timetable data');
            }
            hideLoading();
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            showError('Error loading timetable. Please try again.');
            hideLoading();
        }
    });
}

function updateStats() {
    if (!currentData || !currentData.listData) return;
    
    let allLectures = currentData.listData;
    let onlineCount = allLectures.filter(l => l.lecture_mode === 'online').length;
    let offlineCount = allLectures.filter(l => l.lecture_mode === 'offline').length;
    
    document.getElementById('lectureCount').textContent = allLectures.length;
    document.getElementById('onlineCount').textContent = onlineCount;
    document.getElementById('offlineCount').textContent = offlineCount;
    document.getElementById('dateRange').textContent = 
        `${document.getElementById('startDate').value} to ${document.getElementById('endDate').value}`;
    document.getElementById('statsBar').style.display = 'flex';
}

function escapeJsString(value) {
    let text = String(value || '');
    return text.replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;').replace(/\n/g, '\\n').replace(/\r/g, '\\r');
}

function isCurrentDate(dateStr) {
    let today = getCurrentDateStr();
    return dateStr === today;
}

function getDayHeaderHtml(dayInfo, isWeekView = false) {
    let todayStr = getCurrentDateStr();
    let isToday = dayInfo.date === todayStr;
    let headerClass = isToday ? 'day-header current-date-header' : 'day-header';
    let weekendClass = dayInfo.is_weekend ? 'weekend' : '';
    
    let html = `<th class="${headerClass} ${weekendClass}">`;
    html += `${dayInfo.name}<br>`;
    html += `<small>${dayInfo.display_date}</small>`;
    if (isToday) html += `<span class="current-date-badge">TODAY</span>`;
    html += `</th>`;
    return html;
}

function renderEventBoxContent(event, date, timeKey) {
   
    if (!event) return '<div class="empty-slot">No Class</div>';
    
    // Handle multiple events
    if (event.has_multiple && event.events) {
        let filteredEvents = event.events.filter(e => filterEventByMode(e));
        if (filteredEvents.length === 0) return '<div class="empty-slot">No Class</div>';
        
        let onlineCount = filteredEvents.filter(e => e.lecture_mode === 'online').length;
        let offlineCount = filteredEvents.filter(e => e.lecture_mode === 'offline').length;
        
        let multipleHtml = `<div class="event-box mixed" onclick="showMultipleEvents('${escapeJsString(date)}', '${escapeJsString(timeKey)}')">`;
        multipleHtml += `<div class="event-title">📚 ${filteredEvents.length} Class${filteredEvents.length > 1 ? 'es' : ''}</div>`;
        if (onlineCount > 0) multipleHtml += `<div class="event-badge">🟢 ${onlineCount} Online</div>`;
        if (offlineCount > 0) multipleHtml += `<div class="event-badge">⚫ ${offlineCount} Offline</div>`;
        multipleHtml += `<div class="multiple-event-badge mt-2">🖱️ view all</div>`;
        multipleHtml += `</div>`;
        return multipleHtml;
    }
    
    // Filter single event by mode
    if (!filterEventByMode(event)) return '<div class="empty-slot">No Class</div>';
    
    let isOnline = event.lecture_mode === 'online';
    let modeIcon = isOnline ? '<i class="fas fa-video"></i>' : '<i class="fas fa-chalkboard"></i>';
    let modeClass = isOnline ? 'online' : 'offline';
    let modeBadge = isOnline ? '<span class="mode-badge online">Online</span>' : '<span class="mode-badge offline">Offline</span>';
    
    // Add override badge if applicable
    let overrideBadge = '';
    if (event.has_override) {
        overrideBadge = '<span class="badge bg-warning text-dark ms-1" style="font-size: 8px;">Override</span>';
    }
    
    let eventHtml = `<div class="event-box ${modeClass}" onclick="showEventDetails('lecture', ${event.id}, '${escapeJsString(event.date)}')">`;
    eventHtml += `<div class="event-title">${modeIcon} ${event.title.substring(0, 35)} ${modeBadge} ${overrideBadge}</div>`;
    eventHtml += `<div class="event-faculty">👨‍🏫 ${event.faculty}</div>`;
    eventHtml += `<div class="event-location">📍 ${event.location}</div>`;
    eventHtml += `<div class="event-badge">🔄 ${event.frequency || 'Regular'}</div>`;
    
    if (isOnline && event.meeting_link) {
        eventHtml += `<div class="mt-1"><a href="${event.meeting_link}" target="_blank" onclick="event.stopPropagation()" style="color: white; text-decoration: underline; font-size: 10px;"><i class="fas fa-link"></i> Join Meeting</a></div>`;
    }
    if (isOnline && event.meeting_id) {
        eventHtml += `<div class="mt-1" style="font-size: 10px; color: rgba(255,255,255,0.85);"><strong>Meeting ID:</strong> ${event.meeting_id}</div>`;
    }
    
    eventHtml += `</div>`;
    return eventHtml;
}

function renderWeekView() {

    if (!currentData || !currentData.timeSlots || currentData.timeSlots.length === 0) {
        document.getElementById('timetableBody').innerHTML = '<tr><td colspan="10" class="text-center py-5">No data found for the selected period. Selected dates: ' + document.getElementById('startDate').value + ' to ' + document.getElementById('endDate').value + '<\/td><\/tr>';
        return;
    }

    let sortedTimeSlots = [...currentData.timeSlots].sort((a, b) => a.start_time.localeCompare(b.start_time));
    let dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    
    let headerHtml = '<tr><th class="time-header">Time<\/th>';
    let days = currentData.days || {};
    
    for (let dayName of dayOrder) {
        let dayInfo = days[dayName];
        if (dayInfo) {
            headerHtml += getDayHeaderHtml(dayInfo, true);
        } else {
            headerHtml += `<th class="day-header">${dayName}<br><small>No data<\/small><\/th>`;
        }
    }
    headerHtml += '<\/tr>';
    document.getElementById('weekTableHeader').innerHTML = headerHtml;

    let tbody = document.getElementById('timetableBody');
    tbody.innerHTML = '';

    for (let slot of sortedTimeSlots) {
        let row = '<tr>';
        row += `<td class="time-cell"><strong>${slot.display}<\/strong><\/td>`;
        
        for (let dayName of dayOrder) {
            let dayInfo = days[dayName];
            if (dayInfo) {
                let date = dayInfo.date;
                let timeKey = `${slot.start_time}-${slot.end_time}`;
                let event = (currentData.timetable[date] && currentData.timetable[date][timeKey]) ? currentData.timetable[date][timeKey] : null;
                row += `<td class="event-cell">${renderEventBoxContent(event, date, timeKey)}<\/td>`;
            } else {
                row += '<td class="event-cell"><div class="empty-slot">—<\/div><\/td>';
            }
        }
        row += '<\/tr>';
        tbody.innerHTML += row;
    }
}

function renderDayView() {
   
    let dayViewBody = document.getElementById('dayViewBody');
    if (!currentData || !currentData.timeSlots || currentData.timeSlots.length === 0) {
        dayViewBody.innerHTML = '<div class="text-center py-5 text-muted">No data available for this day.<\/div>';
        return;
    }

    let selectedDate = document.getElementById('startDate').value || getCurrentDateStr();
    let dayInfo = Object.values(currentData.days || {}).find(d => d.date === selectedDate) || {};
    let parsedDate = new Date(selectedDate);
    let dayName = dayInfo.name || ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][parsedDate.getDay()];
    let displayDate = dayInfo.display_date || selectedDate;
    let currentDayClass = isCurrentDate(selectedDate) ? ' current-date-header' : '';

    document.getElementById('dayViewTitle').innerHTML = `${dayName} - ${displayDate}`;

    let sortedTimeSlots = [...currentData.timeSlots].sort((a, b) => a.start_time.localeCompare(b.start_time));
    let html = '<div class="table-responsive"><table class="table table-bordered align-middle mb-0">';
    html += `<thead><tr><th class="time-header" style="width: 30%;">Time<\/th>`;
    html += `<th class="day-header${currentDayClass}">${dayName}<br><small>${displayDate}<\/small><\/th>`;
    html += '<\/tr><\/thead><tbody>';

    let timetable = currentData.timetable || {};
    for (let slot of sortedTimeSlots) {
        let timeKey = `${slot.start_time}-${slot.end_time}`;
        let event = (timetable[selectedDate] && timetable[selectedDate][timeKey]) ? timetable[selectedDate][timeKey] : null;
        
        html += '<tr>';
        html += `<td class="time-cell"><strong>${slot.display}<\/strong><\/td>`;
        html += `<td class="event-cell">${renderEventBoxContent(event, selectedDate, timeKey)}<\/td>`;
        html += '<\/tr>';
    }

    html += '<\/tbody><\/table><\/div>';
    dayViewBody.innerHTML = html;
}

function renderMonthView() {
    
    let calendar = document.getElementById('monthCalendar');
    
    if (!currentData || !currentData.monthData) {
        calendar.innerHTML = '<div class="col-12 text-center py-5">No data found for this month<\/div>';
        return;
    }

    calendar.innerHTML = '';
    let dates = Object.keys(currentData.monthData).sort();
    
    if (dates.length === 0) {
        calendar.innerHTML = '<div class="col-12 text-center py-5">No data found for this month<\/div>';
        return;
    }

    let firstDate = new Date(dates[0]);
    let firstDayOfMonth = firstDate.getDay();
    let dayOffset = firstDayOfMonth === 0 ? 6 : firstDayOfMonth - 1;
    
    for (let i = 0; i < dayOffset; i++) {
        let emptyCol = document.createElement('div');
        emptyCol.className = 'col-md-2 mb-3';
        emptyCol.innerHTML = `<div class="card shadow-sm" style="background: #f8f9fa; opacity: 0.5;">
            <div class="card-header bg-secondary text-white"><strong>&nbsp;<\/strong><\/div>
            <div class="card-body" style="min-height: 180px;"><small class="text-muted">—<\/small><\/div>
        <\/div>`;
        calendar.appendChild(emptyCol);
    }

    for (let date of dates) {
        let dayData = currentData.monthData[date];
        let lectures = dayData.lectures || [];
        
        // Filter by mode
        let filteredLectures = lectures.filter(l => filterEventByMode(l));
        let onlineCount = filteredLectures.filter(l => l.lecture_mode === 'online').length;
        let offlineCount = filteredLectures.filter(l => l.lecture_mode === 'offline').length;
        let totalCount = filteredLectures.length;
        
        let col = document.createElement('div');
        col.className = 'col-md-2 mb-3';
        
        let summaryHtml = '';
        if (filteredLectures.length > 0) {
            summaryHtml += `<div class="mb-2"><small>📚 Classes: ${totalCount}<\/small><\/div>`;
            if (onlineCount > 0) summaryHtml += `<div class="mb-1"><i class="fas fa-video text-success"><\/i> <span class="text-success">${onlineCount} Online<\/span><\/div>`;
            if (offlineCount > 0) summaryHtml += `<div class="mb-1"><i class="fas fa-building text-secondary"><\/i> <span class="text-secondary">${offlineCount} Offline<\/span><\/div>`;
            
            let topSubjects = filteredLectures.slice(0, 2).map(l => l.title.substring(0, 15));
            if (topSubjects.length) {
                summaryHtml += `<div class="mt-2"><small>📖 ${topSubjects.join(', ')}${filteredLectures.length > 2 ? '...' : ''}<\/small><\/div>`;
            }
        } else {
            summaryHtml = '<small class="text-muted">No classes scheduled<\/small>';
        }

        let parsedDate = new Date(date);
        let dayNumber = parsedDate.getDate();
        let dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        let dayName = dayNames[parsedDate.getDay()];
        let isWeekend = parsedDate.getDay() === 0 || parsedDate.getDay() === 6;
        
        let cardTypeClass = '';
        if (onlineCount > 0 && offlineCount === 0) cardTypeClass = 'online-day';
        else if (offlineCount > 0 && onlineCount === 0) cardTypeClass = 'offline-day';
        
        col.innerHTML = `
            <div class="card shadow-sm month-card ${isWeekend ? 'weekend' : ''} ${isCurrentDate(date) ? 'current-date-card' : ''} ${cardTypeClass}" 
                 onclick="showFullDayDetails('${escapeJsString(date)}')">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong class="fs-5">${dayNumber}<\/strong>
                            <small class="d-block" style="font-size: 10px;">${dayName}<\/small>
                        <\/div>
                        <span class="badge bg-light text-dark">${totalCount}<\/span>
                    <\/div>
                <\/div>
                <div class="card-body" style="min-height: 180px; max-height: 180px; overflow-y: auto;">
                    ${summaryHtml}
                <\/div>
                <div class="card-footer bg-transparent text-center p-1">
                    <small class="text-primary"><i class="fas fa-eye"><\/i> view details<\/small>
                <\/div>
            <\/div>
        `;
        calendar.appendChild(col);
    }
}

function renderListView() {
 
    let tbody = document.getElementById('listViewBody');
    if (!currentData || !currentData.listData || !currentData.listData.length) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-5">No data found<\/td><\/tr>';
        return;
    }
    
    tbody.innerHTML = '';
    let filteredEvents = currentData.listData.filter(e => filterEventByMode(e));
    
    filteredEvents.sort((a, b) => {
        let dateCompare = a.date.localeCompare(b.date);
        if (dateCompare === 0) return a.start_time.localeCompare(b.start_time);
        return dateCompare;
    });
    
    for (let item of filteredEvents) {
        let row = document.createElement('tr');
        row.style.cursor = 'pointer';
        row.onclick = () => showEventDetails('lecture', item.id, item.date);
        
        let isToday = isCurrentDate(item.date);
        if (isToday) row.classList.add('current-date-row');
        
        let modeBadge = item.lecture_mode === 'online' ? 
            '<span class="badge bg-success">Online<\/span>' : 
            '<span class="badge bg-secondary">Offline<\/span>';
        
        let overrideBadge = '';
        if (item.has_override) {
            overrideBadge = '<span class="badge bg-warning text-dark ms-1">Override<\/span>';
        }
        
        let meetingLinkHtml = '';
        if (item.lecture_mode === 'online' && item.meeting_link) {
            meetingLinkHtml = `<br><small><a href="${item.meeting_link}" target="_blank" onclick="event.stopPropagation()"><i class="fas fa-link"><\/i> Join<\/a><\/small>`;
        }
        
        row.innerHTML = `
            <td class="sticky-main-2"><strong>${item.date}${isToday ? ' <span class="badge bg-success">TODAY<\/span>' : ''}<\/strong><\/td>
            <td><span class="badge bg-primary">${item.start_time_formatted} - ${item.end_time_formatted}<\/span><\/td>
            <td><strong>${item.title}<\/strong>${meetingLinkHtml}${overrideBadge}<\/td>
            <td>${item.faculty}<br><small class="text-muted">${item.designation || ''}<\/small><\/td>
            <td>${modeBadge}<\/td>
            <td>${item.location || 'TBA'}<\/td>
        `;
        tbody.appendChild(row);
    }
}

function renderCurrentView() {
    if (currentView === 'day') renderDayView();
    else if (currentView === 'week') renderWeekView();
    else if (currentView === 'month') renderMonthView();
    else if (currentView === 'list') renderListView();
}

function switchView(view) {

    currentView = view;

    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.classList.toggle('active', btn.getAttribute('data-view') === view);
    });

    if (view === 'day') {
        setDefaultDayDates();
    } else if (view === 'week') {
        setDefaultWeekDates();
    } else if (view === 'month') {
        setDefaultMonthDates();
    }

    document.getElementById('dayView').style.display = view === 'day' ? 'block' : 'none';
    document.getElementById('weekView').style.display = view === 'week' ? 'block' : 'none';
    document.getElementById('monthView').style.display = view === 'month' ? 'block' : 'none';
    document.getElementById('listView').style.display = view === 'list' ? 'block' : 'none';

    loadTimetable();
    
    // Reinitialize floating scrollbar when switching timetable views
    setTimeout(function () {

        const activePane = document.getElementById(view + 'View');

        if (!activePane) return;

        const tableWrapper = activePane.querySelector('#tableWrapper');
        const topScroll = activePane.querySelector('#tableScrollTop');
        const scrollInner = activePane.querySelector('.table-scroll-inner');

        if (!tableWrapper || !topScroll || !scrollInner) return;

        const table = tableWrapper.querySelector('table');

        if (!table) return;

        scrollInner.style.width = table.scrollWidth + 'px';

        topScroll.onscroll = function () {
            tableWrapper.scrollLeft = topScroll.scrollLeft;
        };

        tableWrapper.onscroll = function () {
            topScroll.scrollLeft = tableWrapper.scrollLeft;
        };

    }, 300);

}

function resetFilters() {
    if (currentView === 'day') {
        setDefaultDayDates();
    } else if (currentView === 'week') {
        setDefaultWeekDates();
    } else if (currentView === 'month') {
        setDefaultMonthDates();
    }
    loadTimetable();
}

function showEventDetails(type, id, date) {
 
    if (!currentData) return;
    
    let eventData = null;
    if (currentData.listData) {
        eventData = currentData.listData.find(item => item.type === type && item.id == id);
    }
    
    if (!eventData && currentData.monthData) {
        let dayData = currentData.monthData[date];
        if (dayData && dayData.lectures) {
            eventData = dayData.lectures.find(l => l.id == id);
        }
    }
    
    if (!eventData && currentData.timetable) {
        for (let [dateKey, timeSlots] of Object.entries(currentData.timetable)) {
            for (let [timeKey, event] of Object.entries(timeSlots)) {
                if (event && !event.has_multiple && event.id == id && event.type === type) {
                    eventData = event;
                    break;
                }
                if (event && event.has_multiple && event.events) {
                    let found = event.events.find(e => e.id == id && e.type === type);
                    if (found) {
                        eventData = found;
                        break;
                    }
                }
            }
            if (eventData) break;
        }
    }
    
    if (!eventData) {
        showError('Event details not found');
        return;
    }
    
    let isOnline = eventData.lecture_mode === 'online';
    let modeBadge = isOnline ? 
        '<span class="badge bg-success mb-2">Online Lecture</span>' : 
        '<span class="badge bg-secondary mb-2">Offline Lecture</span>';
    
    let overrideBadge = '';
    if (eventData.has_override) {
        overrideBadge = '<span class="badge bg-warning text-dark mb-2 ms-2"><i class="fas fa-calendar-day"></i> Day-Specific Override</span>';
    }
    
    let html = `
        <div class="row">
            <div class="col-md-6">
                <p><strong><i class="fas fa-book"></i> Subject:</strong> ${eventData.title}</p>
                <p><strong><i class="fas fa-chalkboard-user"></i> Faculty:</strong> ${eventData.faculty}</p>
                <p><strong><i class="fas fa-building"></i> Department:</strong> ${eventData.department || 'N/A'}</p>
            </div>
            <div class="col-md-6">
                <div>
                    ${modeBadge}
                    ${overrideBadge}
                </div>
                <p class="mt-2"><strong><i class="fas fa-clock"></i> Time:</strong> ${eventData.start_time_formatted} - ${eventData.end_time_formatted}</p>
                <p><strong><i class="fas fa-calendar-day"></i> Date:</strong> ${eventData.date}</p>
                <p><strong><i class="fas fa-location-dot"></i> Location:</strong> ${eventData.location || 'Not specified'}</p>
            </div>
        </div>
    `;
    
    if (eventData.has_override && eventData.override_reason) {
        html += `
            <div class="alert alert-warning mt-3">
                <i class="fas fa-info-circle"></i> <strong>Override Reason:</strong> ${eventData.override_reason}
            </div>
        `;
    }
    
    if (isOnline && eventData.meeting_link) {
        html += `
            <div class="alert alert-info mt-3">
                <h6><i class="fas fa-video"></i> Meeting Details</h6>
                <p><strong>Meeting Link:</strong> <a href="${eventData.meeting_link}" target="_blank">${eventData.meeting_link}</a></p>
                ${eventData.meeting_id ? `<p><strong>Meeting ID:</strong> <code>${eventData.meeting_id}</code></p>` : ''}
                ${eventData.meeting_password ? `<p><strong>Password:</strong> <code>${eventData.meeting_password}</code></p>` : ''}   
                ${eventData.meeting_instructions ? `<p><strong>Instructions:</strong><br>${eventData.meeting_instructions}</p>` : ''}
            </div>
        `;
    }
    
    if (eventData.description && eventData.description !== 'No description') {
        html += `
            <div class="card mt-3">
                <div class="card-header bg-light"><strong><i class="fas fa-align-left"></i> Description</strong></div>
                <div class="card-body"><p class="mb-0">${eventData.description}</p></div>
            </div>
        `;
    }
    
    document.getElementById('detailsModalTitle').innerHTML = `<i class="fas fa-book"></i> ${eventData.title}`;
    document.getElementById('detailsModalBody').innerHTML = html;
    
    let modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    modal.show();
}

function showFullDayDetails(date) {
   
    if (!currentData || !currentData.monthData || !currentData.monthData[date]) {
        showError('No data found for this date');
        return;
    }

    let dayData = currentData.monthData[date];
    let lectures = (dayData.lectures || []).filter(l => filterEventByMode(l));
    let parsedDate = new Date(date);
    let dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    let monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    let dayName = dayNames[parsedDate.getDay()];
    
    let onlineCount = lectures.filter(l => l.lecture_mode === 'online').length;
    let offlineCount = lectures.filter(l => l.lecture_mode === 'offline').length;

    let html = `
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5>${dayName}, ${parsedDate.getDate()} ${monthNames[parsedDate.getMonth()]} ${parsedDate.getFullYear()}</h5>
                <div>
                    <span class="badge bg-success me-1"><i class="fas fa-video"></i> ${onlineCount} Online</span>
                    <span class="badge bg-secondary"><i class="fas fa-building"></i> ${offlineCount} Offline</span>
                </div>
            </div>
        </div>
    `;
    
    if (lectures.length === 0) {
        html += '<div class="alert alert-info text-center">No lectures scheduled for this day</div>';
    } else {
        lectures.sort((a, b) => a.start_time.localeCompare(b.start_time));
        
        html += `
            <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                <table class="erp-table table table-sm table-hover">
                    <thead>
                        <tr>
                            <th class="sticky-main-2 sortable">Time</th>
                            <th class="sortable">Mode</th>
                            <th class="sortable">Subject</th>
                            <th class="sortable">Faculty</th>
                            <th class="sortable">Location</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        for (let lecture of lectures) {
            let modeBadge = lecture.lecture_mode === 'online' ? 
                '<span class="badge bg-success">Online</span>' : 
                '<span class="badge bg-secondary">Offline</span>';
            
            let overrideBadge = '';
            if (lecture.has_override) {
                overrideBadge = '<span class="badge bg-warning text-dark ms-1">Override</span>';
            }
            
            let meetingLink = '';
            if (lecture.lecture_mode === 'online' && lecture.meeting_link) {
                meetingLink = `<br><small><a href="${lecture.meeting_link}" target="_blank" onclick="event.stopPropagation()"><i class="fas fa-link"></i> Join</a></small>`;
            }
            
            html += `
                <tr style="cursor: pointer;" onclick="showEventDetails('lecture', ${lecture.id}, '${escapeJsString(lecture.date)}')">
                    <td class="sticky-main-2"><span class="badge bg-primary">${lecture.start_time_formatted} - ${lecture.end_time_formatted}</span></td>
                    <td>${modeBadge} ${overrideBadge}</td>
                    <td><strong>${lecture.title}</strong>${meetingLink}</td>
                    <td>${lecture.faculty}<br><small class="text-muted">${lecture.designation || ''}</small></td>
                    <td>${lecture.location || 'TBA'}</td>
                </tr>
            `;
        }
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
    }
    
    document.getElementById('detailsModalTitle').innerHTML = `<i class="fas fa-calendar-day"></i> Schedule for ${parsedDate.getDate()} ${monthNames[parsedDate.getMonth()]}, ${parsedDate.getFullYear()}`;
    document.getElementById('detailsModalBody').innerHTML = html;
    
    let modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    modal.show();
}

function showError(message) {
    let alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3';
    alertDiv.style.zIndex = '10000';
    alertDiv.style.minWidth = '300px';
    alertDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    document.body.appendChild(alertDiv);
    setTimeout(() => alertDiv.remove(), 5000);
}

// Event listeners
document.getElementById('startDate').addEventListener('change', () => loadTimetable());
document.getElementById('endDate').addEventListener('change', () => loadTimetable());

// Initialize - Default to DAY view
document.addEventListener('DOMContentLoaded', () => {
    setDefaultDayDates();
    loadTimetable();
    switchView('day');
});
</script>
@endsection