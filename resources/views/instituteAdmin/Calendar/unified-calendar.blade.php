@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
}

.timetable-card {
    background: white;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    /*overflow-x: auto;*/
}

.time-header {
    /*background: #0d6efd !important;*/
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

.event-box.conflict-event {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border: 2px solid #fff;
}

/* Subject colors */
.subject-math {
    background: linear-gradient(135deg, #198754 0%, #157347 100%);
}

.subject-science {
    background: linear-gradient(135deg, #dc3545 0%, #bb2d3b 100%);
}

.subject-english {
    background: linear-gradient(135deg, #6f42c1 0%, #5e37a6 100%);
}

.subject-computer {
    background: linear-gradient(135deg, #fd7e14 0%, #e06e0f 100%);
}

.subject-default {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
}

/* Duty colors */
.duty-low {
    background: linear-gradient(135deg, #10b981 0%, #0e9f6e 100%);
}

.duty-medium {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.duty-high {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.duty-urgent {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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

.conflict-indicator {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 10px;
    padding: 2px 6px;
    font-size: 9px;
}

.empty-slot {
    padding: 10px;
    text-align: center;
    color: #999;
    background: #fafafa;
    border-radius: 6px;
    font-size: 12px;
}

.view-btn {
    min-width: 120px;
}

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
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
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

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

.event-item {
    padding: 5px;
    margin-bottom: 5px;
    border-radius: 4px;
    font-size: 11px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.lecture-item {
    background: #0d6efd;
    color: white;
}

.duty-item {
    background: #f59e0b;
    color: white;
}

.list-view-table {
    font-size: 13px;
}

.list-view-table td {
    vertical-align: middle;
    padding: 10px;
}

.filter-active {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.event-type-badge {
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.badge-lecture {
    background: #0d6efd;
    color: white;
}

.badge-duty {
    background: #f59e0b;
    color: white;
}

.frequency-badge {
    background: #6c757d;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
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

/* Current date highlighting */
.current-date-header {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    color: white;
    font-weight: bold;
    box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
}

.current-date-row {
    background: rgba(16, 185, 129, 0.1);
}

.current-date-card {
    border: 3px solid #10b981;
    background: #ecfdf5;
}

.current-date-card .card-header {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    color: white;
}

.current-date-badge {
    display: inline-block;
    background: #10b981;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: bold;
    margin-left: 5px;
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

    .time-cell,
    .day-header,
    .time-header {
        font-size: 11px;
        padding: 8px;
    }
}

/* Department badges for month view */
.dept-badge {
    background: #e9ecef;
    color: #495057;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 10px;
    display: inline-block;
    margin-right: 4px;
    margin-bottom: 4px;
}

.dept-badge i {
    font-size: 9px;
    margin-right: 3px;
}

.month-card {
    transition: all 0.3s ease;
}

.month-card .card-body {
    scrollbar-width: thin;
}

.month-card .card-body::-webkit-scrollbar {
    width: 3px;
}

.month-card .card-body::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.month-card .card-body::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

/* Multiple events box */
.event-box.multiple-event {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    cursor: pointer;
    position: relative;
    padding: 12px;
}

.event-box.multiple-event:hover {
    transform: scale(1.02);
    filter: brightness(0.95);
}

.multiple-event-actions {
    display: flex;
    align-items: flex-start;
    margin-left: 10px;
}

.multiple-event-actions .btn {
    min-width: 32px;
    padding: 4px 7px;
    font-size: 11px;
}

.multiple-event-badge {
    background: rgba(0, 0, 0, 0.3);
    border-radius: 20px;
    padding: 2px 8px;
    font-size: 10px;
    display: inline-block;
    margin-top: 5px;
}

.multiple-event-stats {
    display: flex;
    gap: 8px;
    margin-top: 5px;
    font-size: 10px;
}

.multiple-event-stats span {
    background: rgba(255, 255, 255, 0.2);
    padding: 2px 6px;
    border-radius: 10px;
}

/* Add to your existing styles */
.event-cell {
    padding: 8px !important;
    vertical-align: top;
    background-color: #fff;
}

.event-box {
    margin: 0;
    width: 100%;
}

.table-bordered td.event-cell {
    border: 1px solid #dee2e6;
}

/* Ensure multiple events display properly */
.event-box.multiple-event {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    cursor: pointer;
    padding: 8px;
}

/* Fix for empty slots */
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
        
        .list-view-table,
        .week-view-table{
            overflow-x: hidden;
        }

</style>

<div class="loading-overlay">
    <div class="loading-spinner"></div>
</div>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2><i class="fas fa-calendar-alt"></i> Timetable </h2>
        <div class="btn-group d-flex flex-wrap">
            <button class="btn btn-outline-primary view-btn" data-view="day" onclick="switchView('day')">
                <i class="fas fa-calendar-day"></i> Day View
            </button>
            <button class="btn btn-outline-primary view-btn" data-view="week" onclick="switchView('week')">
                <i class="fas fa-calendar-week"></i> Week View
            </button>
            <button class="btn btn-outline-primary view-btn" data-view="month" onclick="switchView('month')">
                <i class="fas fa-calendar-alt"></i> Month View
            </button>
            <button class="btn btn-outline-primary view-btn" data-view="list" onclick="switchView('list')">
                <i class="fas fa-list"></i> List View
            </button>
        </div>
    </div>

    <!-- Filters - same as before -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label for="departmentFilter"><i class="fas fa-building"></i> Department</label>
                    <select class="form-select" id="departmentFilter">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->department_id }}">{{ $department->department }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="facultyFilter"><i class="fas fa-chalkboard-user"></i>Employee</label>
                    <select class="form-select" id="facultyFilter">
                        <option value="">All Employees</option>
                        @foreach($employeelist as $employee)
                        <option value="{{ $employee->employee_id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="courseFilter"><i class="fas fa-graduation-cap"></i> Course/Class</label>
                    <select class="form-select" id="courseFilter">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->product_id }}">{{ $course->course_type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="subjectFilter"><i class="fas fa-book"></i> Subject</label>
                    <select class="form-select" id="subjectFilter">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->subject_id }}">{{ $subject->subject_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="startDate"><i class="fas fa-calendar"></i> Start Date</label>
                    <input type="date" class="form-control" id="startDate">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="endDate"><i class="fas fa-calendar"></i> End Date</label>
                    <input type="date" class="form-control" id="endDate">
                </div>

                <div class="col-md-2 mb-3">
                    <label for="priorityFilter"><i class="fas fa-flag"></i> Priority (Duties)</label>
                    <select class="form-select" id="priorityFilter">
                        <option value="">All Priorities</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>

                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button class="btn btn-secondary w-100" onclick="clearFilters()">
                        <i class="fas fa-times"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="stats-bar" id="statsBar" style="display: none;">
        <div><i class="fas fa-chalkboard-user"></i> <span id="lectureCount">0</span> Lectures</div>
        <div><i class="fas fa-tasks"></i> <span id="dutyCount">0</span> Duties</div>
        <div><i class="fas fa-clock"></i> <span id="timeSlotCount">0</span> Time Slots</div>
        <div><i class="fas fa-calendar-week"></i> <span id="dateRange"></span></div>
    </div>

    <!-- WEEK VIEW -->
    <div id="weekView" class="timetable-card" style="display: none;">
        <div class="table-responsive custom-table-wrapper week-view-table" id="tableWrapper">
            <table class="erp-table" id="weekTable"">
                <thead id="weekTableHeader"></thead>
                <tbody id="timetableBody">
                    <tr>
                        <td colspan="10" class="text-center py-5">Loading timetable...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Floating Horizontal Scrollbar -->
        <div class="table-scroll-top" id="tableScrollTop">
            <div class="table-scroll-inner"></div>
        </div>
    </div>

    <!-- DAY VIEW -->
    <div id="dayView" class="timetable-card" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div>
                <h4 id="dayViewTitle">Day View</h4>
                <p class="text-muted mb-0 d-none" id="dayViewSubtitle">Showing schedule for selected day</p>
            </div>
            <div class="btn-group btn-group-sm">
                <button class="btn btn-secondary" onclick="jumpToPreviousDay()"><i class="fas fa-arrow-left"></i>
                    Prev</button>
                <button class="btn btn-secondary" onclick="jumpToToday()"><i class="fas fa-calendar-day"></i>
                    Today</button>
                <button class="btn btn-secondary" onclick="jumpToNextDay()">Next <i
                        class="fas fa-arrow-right"></i></button>
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
            <div class="table-responsive custom-table-wrapper list-view-table" id="tableWrapper">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th class="sticky-main-2 sortable">Type</th>
                            <th class="sortable">Date</th>
                            <th class="sortable">Time</th>
                            <th class="sortable">Title/Subject</th>
                            <th class="sortable">Faculty/Employee</th>
                            <th class="sortable">Department</th>
                            <th class="sortable">Course</th>
                            <th class="sortable">Location</th>
                            <th class="sortable">Frequency</th>
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
                <h5 class="modal-title" id="detailsModalTitle">Event Details</h5>
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
let currentView = 'day';
let currentData = null;

@php
$subjectOptions = $subjects -> map(function($subject) {
    return [
        'subject_id' => $subject -> subject_id,
        'subject_name' => $subject -> subject_name,
        'course_detail_id' => $subject -> course_detail_id ?? null,
    ];
}) -> toArray();
@endphp

const allSubjects = @json($subjectOptions);

function updateSubjectFilterOptions(courseId = '') {
    const subjectSelect = document.getElementById('subjectFilter');
    if (!subjectSelect) return;

    subjectSelect.innerHTML = '<option value="">All Subjects</option>';

    const options = allSubjects
        .filter(subject => !courseId || subject.course_detail_id == courseId)
        .map(subject => ({
            value: subject.subject_id,
            label: subject.subject_name
        }));

    options.forEach(option => {
        const opt = document.createElement('option');
        opt.value = option.value;
        opt.textContent = option.label;
        subjectSelect.appendChild(opt);
    });
}

function onCourseFilterChange() {
    const courseId = document.getElementById('courseFilter').value;
    updateSubjectFilterOptions(courseId);
    document.getElementById('subjectFilter').value = '';
    loadTimetable();
}

function getDayHeaderHtml(dayInfo, isWeekView = false) {
    const today = new Date();
    const todayStr = today.getFullYear() + '-' +
        String(today.getMonth() + 1).padStart(2, '0') + '-' +
        String(today.getDate()).padStart(2, '0');

    const isToday = dayInfo.date === todayStr;
    const headerClass = isToday ? 'day-header current-date-header' : 'day-header';
    const weekendClass = dayInfo.is_weekend ? 'weekend' : '';

    let html = `<th class="${headerClass} ${weekendClass}">`;
    html += `${dayInfo.name}<br>`;
    html += `<small>${dayInfo.display_date}</small>`;
    if (isToday) {
        html += `<span class="current-date-badge">TODAY</span>`;
    }
    html += `</th>`;
    return html;
}

function isCurrentDate(dateStr) {
    const today = new Date();
    const todayStr = today.getFullYear() + '-' +
        String(today.getMonth() + 1).padStart(2, '0') + '-' +
        String(today.getDate()).padStart(2, '0');
    return dateStr === todayStr;
}

function setDefaultWeekDates() {
    const today = new Date();
    setWeekDatesFor(today);
}

function setWeekDatesFor(date) {
    const selected = new Date(date);
    const day = selected.getDay();
    const diff = day === 0 ? -6 : 1 - day;
    const startOfWeek = new Date(selected);
    startOfWeek.setDate(selected.getDate() + diff);
    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6);
    document.getElementById('startDate').value = formatDateInput(startOfWeek);
    document.getElementById('endDate').value = formatDateInput(endOfWeek);
}

function setDefaultDayDates() {
    const today = new Date();
    const todayValue = formatDateInput(today);
    document.getElementById('startDate').value = todayValue;
    document.getElementById('endDate').value = todayValue;
}

function formatDateInput(date) {
    return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
}

function jumpToPreviousDay() {
    const currentDate = new Date(document.getElementById('startDate').value);
    currentDate.setDate(currentDate.getDate() - 1);
    const nextDate = formatDateInput(currentDate);
    document.getElementById('startDate').value = nextDate;
    document.getElementById('endDate').value = nextDate;
    loadTimetable();
}

function jumpToNextDay() {
    const currentDate = new Date(document.getElementById('startDate').value);
    currentDate.setDate(currentDate.getDate() + 1);
    const nextDate = formatDateInput(currentDate);
    document.getElementById('startDate').value = nextDate;
    document.getElementById('endDate').value = nextDate;
    loadTimetable();
}

function jumpToToday() {
    const today = new Date();
    const todayValue = formatDateInput(today);
    document.getElementById('startDate').value = todayValue;
    document.getElementById('endDate').value = todayValue;
    loadTimetable();
}

function showLoading() {
    document.querySelector('.loading-overlay').style.display = 'flex';
}

function hideLoading() {
    document.querySelector('.loading-overlay').style.display = 'none';
}

function loadTimetable() {
    showLoading();
    const params = {
        start_date: document.getElementById('startDate').value,
        end_date: document.getElementById('endDate').value,
        department_id: document.getElementById('departmentFilter').value,
        course_id: document.getElementById('courseFilter').value,
        employee_id: document.getElementById('facultyFilter').value,
        priority: document.getElementById('priorityFilter').value,
        subject_id: document.getElementById('subjectFilter').value
    };

    $.ajax({
        url: '{{ route("institute-admin.timetable.data") }}',
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
    if (!currentData) return;
    const listData = currentData.listData || [];
    let lectureCount = listData.filter(i => i.type === 'lecture').length;
    let dutyCount = listData.filter(i => i.type === 'duty').length;
    document.getElementById('lectureCount').textContent = lectureCount;
    document.getElementById('dutyCount').textContent = dutyCount;
    document.getElementById('timeSlotCount').textContent = currentData.timeSlots ? currentData.timeSlots.length : 0;
    document.getElementById('dateRange').textContent =
        `${document.getElementById('startDate').value} to ${document.getElementById('endDate').value}`;
    document.getElementById('statsBar').style.display = 'flex';
}

function escapeJsString(value) {
    const text = String(value || '');
    return text
        .replace(/\\/g, '\\\\')
        .replace(/'/g, "\\'")
        .replace(/"/g, '&quot;')
        .replace(/\n/g, '\\n')
        .replace(/\r/g, '\\r');
}

function renderCurrentView() {
    if (currentView === 'day') renderDayView();
    else if (currentView === 'week') renderWeekView();
    else if (currentView === 'month') renderMonthView();
    else if (currentView === 'list') renderListView();
}

/**
 * Render event box content (UPDATED with override support)
 */
function renderEventBoxContent(event, date, timeKey) {
    if (!event) return '<div class="empty-slot">—</div>';

    // Handle multiple events
    if (event.has_multiple && event.events) {
        let multipleHtml =
            `<div class="event-box multiple-event" onclick="showMultipleEvents('${escapeJsString(date)}', '${escapeJsString(timeKey)}')">`;
        multipleHtml += `<div class="d-flex justify-content-between align-items-start">`;
        multipleHtml += `<div>`;
        multipleHtml +=
            `<div class="event-title">📚 ${event.lecture_count} Lecture${event.lecture_count > 1 ? 's' : ''}`;
        if (event.duty_count > 0) {
            multipleHtml += ` + ${event.duty_count} ${event.duty_count > 1 ? 'Duties' : 'Duty'}`;
        }
        multipleHtml += `</div>`;

        if (event.departments && event.departments.length > 0) {
            multipleHtml +=
                `<div class="event-faculty">🏢 ${event.departments.slice(0, 2).join(', ')}${event.departments.length > 2 ? ' +' + (event.departments.length - 2) : ''}</div>`;
        }

        const reassignedCount = (event.events || []).filter(e => e.type === 'lecture' && e.is_reassigned).length || 0;
        multipleHtml += `<div class="multiple-event-stats"><span>📖 ${event.lecture_count} Lectures</span>`;
        if (event.duty_count > 0) {
            multipleHtml += `<span>📋 ${event.duty_count} Duties</span>`;
        }
        if (reassignedCount > 0) {
            multipleHtml += `<span>🔄 ${reassignedCount} Reassigned</span>`;
        }
        multipleHtml += `</div>`;
        multipleHtml += `<div class="multiple-event-badge">🖱️ view all</div>`;
        multipleHtml += `</div>`;

        if (event.lecture_count > 0) {
            multipleHtml +=
                `<div class="multiple-event-actions"><button type="button" class="btn btn-sm btn-light" onclick="event.stopPropagation(); showMultipleEvents('${escapeJsString(date)}', '${escapeJsString(timeKey)}')" title="Edit lecture mode"><i class="fas fa-edit"></i></button></div>`;
        }

        multipleHtml += `</div>`;
        return multipleHtml;
    }

    // Handle single lecture event
    if (event.type === 'lecture') {
        const typeIcon = '📖';
        const title = event.subject || event.title;
        const faculty = event.faculty || 'N/A';
        const location = event.location || event.room || 'Not specified';

        // Mode icons and badges
        const isOnline = event.lecture_mode === 'online';
        const modeIcon = isOnline ? '<i class="fas fa-video"></i> ' : '<i class="fas fa-chalkboard"></i> ';
        const modeBadge = isOnline ?
            '<span class="badge bg-info" style="font-size: 9px; margin-left: 5px;">Online</span>' :
            '<span class="badge bg-secondary" style="font-size: 9px; margin-left: 5px;">Offline</span>';

        // Override badge if this lecture has a day-specific override
        let overrideBadge = '';
        if (event.has_override) {
            overrideBadge =
                `<span class="badge bg-warning text-dark" style="font-size: 9px; margin-left: 5px;" title="Override for this specific day"><i class="fas fa-calendar-day"></i> Override</span>`;
        }

        // Reassignment badge if this lecture has been reassigned
        let reassignBadge = '';
        if (event.is_reassigned) {
            let badgeClass = 'bg-secondary';
            let badgeText = 'Reassigned';

            const toName = event.reassigned_to_employee_name || null;
            const fromName = event.reassigned_from_employee_name || null;

            if (fromName && toName) {
                badgeText = `Reassigned from ${fromName} to ${toName}`;
                badgeClass = 'bg-success text-white';
            } else if (toName) {
                badgeText = `Reassigned to ${toName}`;
                badgeClass = 'bg-success text-white';
            } else if (fromName) {
                badgeText = `Reassigned from ${fromName}`;
                badgeClass = 'bg-warning text-dark';
            }

            reassignBadge =
                `<span class="badge ${badgeClass}" style="font-size: 9px; margin-left: 5px;">${badgeText}</span>`;
        }

        // Escape special characters for onclick
        const safeMeetingLink = escapeJsString(event.meeting_link);
        const safeMeetingPassword = escapeJsString(event.meeting_password);
        const safeMeetingInstructions = escapeJsString(event.meeting_instructions);
        const safeMeetingId = escapeJsString(event.meeting_id);

        // Get assignment_id (needed for override)
        const assignmentId = event.assignment_id ? `'${escapeJsString(event.assignment_id)}'` : 'null';
        const overrideId = event.override_id || 'null';

        const editButton = `<button class="btn btn-sm btn-light" onclick="event.stopPropagation(); editLectureModeForDay(
            ${event.id}, 
            ${assignmentId},
            '${escapeJsString(event.date)}', 
            '${escapeJsString(event.lecture_mode || '')}', 
            '${safeMeetingLink}', 
            '${safeMeetingPassword}', 
            '${safeMeetingInstructions}',
            '${safeMeetingId}'
        )" title="Edit lecture mode">
            <i class="fas fa-edit"></i>
        </button>`;

        let eventHtml =
            `<div class="event-box ${event.color_class}" onclick="showEventDetails('lecture', ${event.id}, '${escapeJsString(event.date)}')" style="cursor: pointer;">`;
        eventHtml += `<div class="d-flex justify-content-between align-items-start">`;
        eventHtml +=
            `<div class="event-title flex-grow-1">${typeIcon} ${modeIcon} ${title.substring(0, 35)} ${modeBadge} ${overrideBadge} ${reassignBadge}</div>`;
        eventHtml += editButton;
        eventHtml += `</div>`;
        eventHtml += `<div class="event-faculty">👨‍🏫 ${faculty}</div>`;
        eventHtml += `<div class="event-location">📍 ${location}</div>`;

        // Add meeting link if online
        if (isOnline && event.meeting_link) {
            eventHtml += `<div class="event-location mt-1">
                <i class="fas fa-link"></i> <a href="${event.meeting_link}" target="_blank" onclick="event.stopPropagation()" style="color: white; text-decoration: underline;">Join Meeting</a>
            </div>`;
        }

        const frequencyBadge = `<div class="event-badge">🔄 ${event.frequency || 'Regular'}</div>`;
        eventHtml += frequencyBadge;
        eventHtml += `</div>`;

        return eventHtml;
    }

    // Handle single duty event
    if (event.type === 'duty') {
        const typeIcon = '📋';
        const title = event.title;
        const faculty = event.faculty || 'N/A';
        const location = event.location || event.venue || 'Not specified';
        const priorityBadge = event.priority ? `<div class="event-badge">⚠️ ${event.priority.toUpperCase()}</div>` : '';
        const frequencyBadge = `<div class="event-badge">🔄 ${event.frequency || 'Regular'}</div>`;

        let eventHtml =
            `<div class="event-box ${event.color_class}" onclick="showEventDetails('duty', ${event.id}, '${escapeJsString(event.date)}')" style="cursor: pointer;">`;
        eventHtml += `<div class="event-title">${typeIcon} ${title.substring(0, 35)}</div>`;
        eventHtml += `<div class="event-faculty">👨‍🏫 ${faculty}</div>`;
        eventHtml += `<div class="event-location">📍 ${location}</div>`;
        eventHtml += priorityBadge + frequencyBadge;
        eventHtml += `</div>`;

        return eventHtml;
    }

    return '<div class="empty-slot">—</div>';
}

/**
 * Render Week View
 */
function renderWeekView() {
    if (!currentData || !currentData.timeSlots || currentData.timeSlots.length === 0) {
        document.getElementById('timetableBody').innerHTML =
            '<tr><td colspan="10" class="text-center py-5">No data found for the selected period</td></tr>';
        return;
    }

    const sortedTimeSlots = [...currentData.timeSlots].sort((a, b) => a.start_time.localeCompare(b.start_time));
    const dayOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    let headerHtml = '<tr><th class="sortable sticky-main-2 time-header">Time</th>';
    const days = currentData.days || {};
    for (const dayName of dayOrder) {
        const dayInfo = days[dayName];
        if (dayInfo) {
            headerHtml += getDayHeaderHtml(dayInfo, true);
        }
    }
    headerHtml += '</tr>';
    document.getElementById('weekTableHeader').innerHTML = headerHtml;

    const tbody = document.getElementById('timetableBody');
    tbody.innerHTML = '';

    sortedTimeSlots.forEach(slot => {
        let row = '<tr>';
        row += `<td class="sticky-main-2 time-cell"><strong>${slot.display}</strong></td>`;

        const days = currentData.days || {};
        const timetable = currentData.timetable || {};
        for (const dayName of dayOrder) {
            const dayInfo = days[dayName];
            if (dayInfo) {
                const date = dayInfo.date;
                const timeKey = slot.start_time + '-' + slot.end_time;
                const event = timetable[date] && timetable[date][timeKey] ? timetable[date][timeKey] : null;
                row += `<td class="event-cell">${renderEventBoxContent(event, date, timeKey)}</td>`;
            } else {
                row += '<td class="event-cell"><div class="empty-slot">—</div></td>';
            }
        }
        row += '</tr>';
        tbody.innerHTML += row;
    });
}

/**
 * Render Day View
 */
function renderDayView() {
    const dayViewBody = document.getElementById('dayViewBody');
    if (!currentData || !currentData.timeSlots || currentData.timeSlots.length === 0) {
        dayViewBody.innerHTML = '<div class="text-center py-5 text-muted">No data available for this day.</div>';
        return;
    }

    const selectedDate = document.getElementById('startDate').value || (() => {
        const today = new Date();
        return formatDateInput(today);
    })();

    const dayInfo = Object.values(currentData.days || {}).find(d => d.date === selectedDate) || {};
    const parsedDate = new Date(selectedDate);
    const dayName = dayInfo.name || ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][
        parsedDate.getDay()
    ];
    const displayDate = dayInfo.display_date || selectedDate;
    const currentDayClass = isCurrentDate(selectedDate) ? ' current-date-header' : '';

    document.getElementById('dayViewTitle').textContent = `${dayName} - ${displayDate}`;
    document.getElementById('dayViewSubtitle').textContent = `Showing schedule for ${selectedDate}`;

    const sortedTimeSlots = [...currentData.timeSlots].sort((a, b) => a.start_time.localeCompare(b.start_time));
    let html = '<div class="table-responsive custom-table-wrapper" id="tableWrapper">';
    html += '<table class="erp-table table table-bordered align-middle mb-0">';
    html += '<thead><tr><th class="sticky-main-2 time-header" style="width: 30%;">Time</th>';
    html += `<th class="day-header${currentDayClass}">${dayName}<br><small>${displayDate}</small></th>`;
    html += '</tr></thead><tbody>';

    const timetable = currentData.timetable || {};
    sortedTimeSlots.forEach(slot => {
        const timeKey = `${slot.start_time}-${slot.end_time}`;
        const event = timetable[selectedDate] && timetable[selectedDate][timeKey] ? timetable[selectedDate][
            timeKey
        ] : null;

        let row = '<tr>';
        row += `<td class="sticky-main-2 time-cell"><strong>${slot.display}</strong></td>`;
        row += `<td class="event-cell">${renderEventBoxContent(event, selectedDate, timeKey)}</td>`;
        row += '</tr>';
        html += row;
    });

    html += '</tbody></table></div>';
    dayViewBody.innerHTML = html;
}

/**
 * Render Month View
 */
function renderMonthView() {
    const calendar = document.getElementById('monthCalendar');

    if (!currentData || !currentData.monthData) {
        calendar.innerHTML = '<div class="col-12 text-center py-5">No data found for this month</div>';
        return;
    }

    calendar.innerHTML = '';

    const dates = Object.keys(currentData.monthData).sort();

    if (dates.length === 0) {
        calendar.innerHTML = '<div class="col-12 text-center py-5">No data found for this month</div>';
        return;
    }

    const firstDate = new Date(dates[0]);
    const firstDayOfMonth = firstDate.getDay();

    for (let i = 0; i < firstDayOfMonth; i++) {
        const emptyCol = document.createElement('div');
        emptyCol.className = 'col-md-2 mb-3';
        emptyCol.innerHTML = `
            <div class="card shadow-sm" style="background: #f8f9fa; opacity: 0.5;">
                <div class="card-header bg-secondary text-white">
                    <strong>&nbsp;</strong>
                </div>
                <div class="card-body" style="min-height: 180px;">
                    <small class="text-muted">—</small>
                </div>
            </div>
        `;
        calendar.appendChild(emptyCol);
    }

    dates.forEach(date => {
        const dayData = currentData.monthData[date];
        const lectureCount = dayData.lectures ? dayData.lectures.length : 0;
        const dutyCount = dayData.duties ? dayData.duties.length : 0;
        const totalCount = lectureCount + dutyCount;

        const col = document.createElement('div');
        col.className = 'col-md-2 mb-3';

        const departmentGroups = {};
        if (dayData.lectures && dayData.lectures.length > 0) {
            dayData.lectures.forEach(lecture => {
                const dept = lecture.department || 'Other';
                departmentGroups[dept] = (departmentGroups[dept] || 0) + 1;
            });
        }

        let summaryHtml = '';
        const deptEntries = Object.entries(departmentGroups);
        if (deptEntries.length > 0) {
            summaryHtml += '<div class="mb-2"><small class="text-muted">📚 Departments:</small></div>';
            deptEntries.slice(0, 3).forEach(([dept, count]) => {
                summaryHtml +=
                    `<div class="dept-badge mb-1"><i class="fas fa-building"></i> ${dept.substring(0, 20)} (${count})</div>`;
            });
            if (deptEntries.length > 3) {
                summaryHtml += `<div class="text-muted small">+ ${deptEntries.length - 3} more depts</div>`;
            }
        }

        const onlineLectures = dayData.lectures ? dayData.lectures.filter(l => l.lecture_mode === 'online')
            .length : 0;
        const overrideLectures = dayData.lectures ? dayData.lectures.filter(l => l.has_override === true)
            .length : 0;
        const reassignedLectures = dayData.lectures ? dayData.lectures.filter(l => l.is_reassigned === true)
            .length : 0;

        if (onlineLectures > 0) {
            summaryHtml +=
                `<div class="mt-1"><i class="fas fa-video text-info"></i> <span class="text-info">${onlineLectures} Online Lecture${onlineLectures > 1 ? 's' : ''}</span></div>`;
        }

        if (overrideLectures > 0) {
            summaryHtml +=
                `<div class="mt-1"><i class="fas fa-calendar-day text-warning"></i> <span class="text-warning">${overrideLectures} Override${overrideLectures > 1 ? 's' : ''}</span></div>`;
        }

        if (reassignedLectures > 0) {
            summaryHtml +=
                `<div class="mt-1"><i class="fas fa-exchange-alt text-secondary"></i> <span class="text-secondary">${reassignedLectures} Reassigned</span></div>`;
        }

        if (dutyCount > 0) {
            const highPriorityCount = dayData.duties ? dayData.duties.filter(d => d.priority === 'high' || d
                .priority === 'urgent').length : 0;
            summaryHtml +=
                `<div class="mt-2"><i class="fas fa-tasks text-warning"></i> <span class="text-warning">${dutyCount} Duty${dutyCount > 1 ? 'ies' : ''}</span>`;
            if (highPriorityCount > 0) {
                summaryHtml += ` <span class="badge bg-danger">${highPriorityCount} High Priority</span>`;
            }
            summaryHtml += `</div>`;
        }

        const parsedDate = new Date(date);
        const dayOfWeek = parsedDate.getDay();
        const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const dayName = dayNames[dayOfWeek];
        const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;
        const dayNumber = parsedDate.getDate();

        let cardHeaderClass = 'bg-primary';
        if (totalCount === 0) cardHeaderClass = 'bg-secondary';
        else if (dutyCount > 0 && lectureCount > 0) cardHeaderClass = 'bg-info';
        else if (dutyCount > 0) cardHeaderClass = 'bg-warning';
        else if (onlineLectures > 0) cardHeaderClass = 'bg-success';

        col.innerHTML = `
    <div class="card shadow-sm month-card ${isWeekend ? 'weekend' : ''} ${isCurrentDate(date) ? 'current-date-card' : ''}" 
         onclick="showFullDayDetails('${escapeJsString(date)}')">
        <div class="card-header ${cardHeaderClass} text-white">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <strong class="fs-5">${dayNumber}</strong>
                    <small class="d-block" style="font-size: 10px;">${dayName}</small>
                    ${isCurrentDate(date) ? '<span class="current-date-badge">TODAY</span>' : ''}
                </div>
                <span class="badge bg-light text-dark">${totalCount} event${totalCount !== 1 ? 's' : ''}</span>
            </div>
        </div>
        <div class="card-body" style="min-height: 180px; max-height: 180px; overflow-y: auto;">
            ${summaryHtml || '<small class="text-muted">No events scheduled</small>'}
        </div>
        <div class="card-footer bg-transparent text-center p-1">
            <small class="text-primary"><i class="fas fa-eye"></i> view details</small>
        </div>
    </div>
`;

        calendar.appendChild(col);
    });
}

function renderListView() {
    const tbody = document.getElementById('listViewBody');
    if (!currentData || !currentData.listData || !currentData.listData.length) {
        tbody.innerHTML = '<tr><td colspan="9" class="text-center py-5">No data found</td></tr>';
        return;
    }
    tbody.innerHTML = '';
    currentData.listData.forEach(item => {
        const row = document.createElement('tr');
        row.style.cursor = 'pointer';
        row.onclick = () => showEventDetails(item.type, item.id, escapeJsString(item.date));

        const isToday = isCurrentDate(item.date);
        if (isToday) {
            row.classList.add('current-date-row');
        }

        let modeBadge = '';
        let overrideBadge = '';
        let reassignBadge = '';
        if (item.type === 'lecture') {
            // Use the lecture_mode from the item (which already has override applied)
            const isOnline = item.lecture_mode === 'online';
            modeBadge = isOnline ?
                '<span class="badge bg-info ms-1">Online</span>' :
                '<span class="badge bg-secondary ms-1">Offline</span>';

            if (item.has_override) {
                overrideBadge =
                    '<span class="badge bg-warning text-dark ms-1"><i class="fas fa-calendar-day"></i> Override</span>';
            }
            if (item.is_reassigned) {
                const reassignmentText = item.reassigned_from_employee_name && item
                    .reassigned_to_employee_name ?
                    `Reassigned from ${item.reassigned_from_employee_name} to ${item.reassigned_to_employee_name}` :
                    (item.reassigned_to_employee_name ?
                        `Reassigned to ${item.reassigned_to_employee_name}` :
                        (item.reassigned_from_employee_name ?
                            `Reassigned from ${item.reassigned_from_employee_name}` :
                            'Reassigned'));
                const badgeClass = item.reassigned_from_employee_name && item.reassigned_to_employee_name ?
                    'bg-success text-white' : 'bg-secondary';
                reassignBadge =
                    `<span class="badge ${badgeClass} ms-1"><i class="fas fa-exchange-alt"></i> ${reassignmentText}</span>`;
            }
        }

        let meetingLinkHtml = '';
        if (item.type === 'lecture' && item.lecture_mode === 'online' && item.meeting_link) {
            meetingLinkHtml =
                `<br><small><a href="${item.meeting_link}" target="_blank" onclick="event.stopPropagation()"><i class="fas fa-link"></i> Join Meeting</a></small>`;
        }

        row.innerHTML = `
            <td class="sticky-main-2">${item.type === 'lecture' ? '<span class="event-type-badge badge-lecture">Lecture</span>' : '<span class="event-type-badge badge-duty">Duty</span>'}${modeBadge}${overrideBadge}${reassignBadge}</td>
            <td><strong>${item.date}${isToday ? ' <span class="current-date-badge">TODAY</span>' : ''}</strong></td>
            <td>${item.start_time} - ${item.end_time}</td>
            <td><strong>${item.title}</strong>${meetingLinkHtml}</td>
            <td>${item.faculty}<br><small class="text-muted">${item.designation || ''}</small>${item.shift_name ? `<br><small class="text-muted">Shift: ${item.shift_name}</small>` : ''}</td>
            <td>${item.department || 'N/A'}</td>
            <td>${item.course || item.priority || 'N/A'}</td>
            <td>${item.location || 'N/A'}</td>
            <td><span class="frequency-badge">${item.frequency || 'Regular'}</span></td>
        `;
        tbody.appendChild(row);
    });
}

/**
 * Edit Lecture Mode For Specific Day - NOW USING LECTURE MODE FROM employee_subject_lectures table
 * The currentMode parameter already comes from the lecture's stored mode (employee_subject_lectures.lecture_mode)
 */
let currentEditingLecture = null;

function editLectureModeForDay(lectureId, assignmentId, date, currentMode, meetingLink, meetingPassword,
    meetingInstructions, meetingId) {
    // Store for save/delete functions
    currentEditingLecture = {
        lectureId: lectureId,
        assignmentId: assignmentId,
        date: date,
        overrideId: null // Will be set if we fetch existing override
    };

    // First, check if there's an existing override for this lecture and date
    $.ajax({
        url: '{{ route("institute-admin.timetable.get-overrides") }}',
        method: 'GET',
        data: {
            lecture_id: lectureId
        },
        success: function(response) {
            if (response.success && response.data) {
                const existingOverride = response.data.find(o => o.override_date === date);
                if (existingOverride) {
                    currentEditingLecture.overrideId = existingOverride.id;
                    showOverrideModal(lectureId, assignmentId, date, existingOverride.lecture_mode,
                        existingOverride.meeting_link, existingOverride.meeting_password,
                        existingOverride.meeting_instructions, existingOverride.meeting_id,
                        true, existingOverride.id);
                } else {
                    // No existing override - use the lecture's default mode
                    showOverrideModal(lectureId, assignmentId, date, currentMode, meetingLink,
                        meetingPassword, meetingInstructions, meetingId, false, null);
                }
            } else {
                // No overrides found - use lecture's default mode
                showOverrideModal(lectureId, assignmentId, date, currentMode, meetingLink, meetingPassword,
                    meetingInstructions, meetingId, false, null);
            }
        },
        error: function() {
            // On error, still show modal with lecture's default mode
            showOverrideModal(lectureId, assignmentId, date, currentMode, meetingLink, meetingPassword,
                meetingInstructions, meetingId, false, null);
        }
    });
}

function showOverrideModal(lectureId, assignmentId, date, currentMode, meetingLink, meetingPassword,
    meetingInstructions, meetingId, hasOverride, overrideId) {
    if (overrideId) {
        currentEditingLecture.overrideId = overrideId;
    }

    // Escape values for HTML
    const escapedMeetingLink = (meetingLink || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    const escapedMeetingPassword = (meetingPassword || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g,
        '&gt;').replace(/"/g, '&quot;');
    const escapedMeetingInstructions = (meetingInstructions || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(
        />/g, '&gt;').replace(/"/g, '&quot;');
    const escapedMeetingId = (meetingId || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');

    // Default selected mode is the current mode from the lecture (employee_subject_lectures table)
    let defaultSelectedMode = (currentMode && currentMode !== '') ? currentMode : 'offline';
    const showOnlineFields = (defaultSelectedMode === 'online');

    const overrideBadge = hasOverride ?
        '<div class="alert alert-warning alert-sm mb-3 p-2" style="font-size: 12px;"><i class="fas fa-exclamation-triangle"></i> <strong>Override Active:</strong> This lecture has a custom mode for this specific day. Changes will update the override.</div>' :
        '<div class="alert alert-info alert-sm mb-3 p-2" style="font-size: 12px;"><i class="fas fa-info-circle"></i> <strong>Using Default Lecture Mode:</strong> This lecture uses the mode set in the lecture schedule. Changes will create an override for this day only.</div>';

    const modalHtml = `
        <div class="modal fade" id="lectureModeModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-calendar-day"></i> Class Mode Override
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="lectureModeForm">
                            <input type="hidden" name="lecture_id" value="${lectureId}">
                            <input type="hidden" name="assignment_id" value="${assignmentId}">
                            <input type="hidden" name="override_date" value="${date}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            
                            ${overrideBadge}
                            
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-calendar"></i> Date
                                </label>
                                <input type="text" class="form-control" value="${date}" disabled>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-chalkboard"></i> Lecture Mode for This Day
                                </label>
                                
                                <div class="alert alert-secondary alert-sm mb-2 p-2" style="font-size: 12px;">
                                    <i class="fas fa-info-circle"></i> 
                                    <strong>Default Lecture Mode:</strong> 
                                    <span class="badge ${currentMode === 'online' ? 'bg-info' : 'bg-secondary'} ms-1">${currentMode === 'online' ? 'ONLINE' : 'OFFLINE'}</span>
                                    <small class="d-block mt-1">This is the mode set in the lecture schedule. Changes below will create a day-specific override.</small>
                                </div>
                                
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="lecture_mode" 
                                           id="modeOffline" value="offline" ${defaultSelectedMode === 'offline' ? 'checked' : ''}>
                                    <label class="form-check-label" for="modeOffline">
                                        <i class="fas fa-building"></i> Offline (Physical Classroom)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="lecture_mode" 
                                           id="modeOnline" value="online" ${defaultSelectedMode === 'online' ? 'checked' : ''}>
                                    <label class="form-check-label" for="modeOnline">
                                        <i class="fas fa-video"></i> Online (Virtual Class)
                                    </label>
                                </div>
                            </div>
                            
                            <div id="onlineFields" style="display: ${showOnlineFields ? 'block' : 'none'}">
                                <div class="mb-3">
                                    <label for="meetingLink" class="form-label">
                                        <i class="fas fa-link"></i> Meeting Link 
                                        <span class="text-danger" id="meetingLinkRequired">*</span>
                                    </label>
                                    <input type="url" class="form-control" id="meetingLink" name="meeting_link" 
                                           placeholder="https://meet.google.com/xxx-xxxx-xxx" 
                                           value="${escapedMeetingLink}">
                                    <small class="text-muted">Google Meet, Zoom, Microsoft Teams, etc.</small>
                                </div>

                                 <div class="mb-3">
                                    <label for="meetingId" class="form-label">
                                        <i class="fas fa-id-badge"></i> Meeting ID (Optional)
                                    </label>
                                    <input type="text" class="form-control" id="meetingId" 
                                           name="meeting_id" value="${escapedMeetingId}" 
                                           placeholder="e.g. 123-456-789">
                                    <small class="text-muted">Optional meeting identifier or room code.</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="meetingPassword" class="form-label">
                                        <i class="fas fa-lock"></i> Meeting Password (Optional)
                                    </label>
                                    <input type="text" class="form-control" id="meetingPassword" 
                                           name="meeting_password" value="${escapedMeetingPassword}">
                                </div>

                               
                                
                                <div class="mb-3">
                                    <label for="meetingInstructions" class="form-label">
                                        <i class="fas fa-info-circle"></i> Meeting Instructions (Optional)
                                    </label>
                                    <textarea class="form-control" id="meetingInstructions" 
                                              name="meeting_instructions" rows="2">${escapedMeetingInstructions}</textarea>
                                    <small class="text-muted">e.g., "Please join 5 minutes early", "Keep your mic muted", etc.</small>
                                </div>
                            </div>
                            
                            <div class="mb-3 d-none">
                                <label for="remarks" class="form-label">
                                    <i class="fas fa-comment"></i> Remarks (Optional)
                                </label>
                                <textarea class="form-control" id="remarks" name="remarks" rows="2" 
                                          placeholder="Why is this override needed? (e.g., 'Teacher requested online class due to travel')"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        ${hasOverride ? `
                        <button type="button" class="btn btn-danger" onclick="deleteLectureModeOverride()">
                            <i class="fas fa-trash"></i> Remove Override
                        </button>
                        ` : ''}
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveLectureModeOverride()">
                            <i class="fas fa-save"></i> ${hasOverride ? 'Update Override' : 'Create Override'}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if any
    const existingModal = document.getElementById('lectureModeModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Add event listeners for radio buttons
    const radioOffline = document.getElementById('modeOffline');
    const radioOnline = document.getElementById('modeOnline');
    const onlineFields = document.getElementById('onlineFields');
    const meetingLinkRequired = document.getElementById('meetingLinkRequired');
    const meetingLinkInput = document.getElementById('meetingLink');

    function updateOnlineFieldsVisibility() {
        if (radioOnline && radioOnline.checked) {
            onlineFields.style.display = 'block';
            if (meetingLinkRequired) meetingLinkRequired.style.display = 'inline';
            if (meetingLinkInput) meetingLinkInput.required = true;
        } else {
            onlineFields.style.display = 'none';
            if (meetingLinkRequired) meetingLinkRequired.style.display = 'none';
            if (meetingLinkInput) meetingLinkInput.required = false;
        }
    }

    if (radioOffline && radioOnline && onlineFields) {
        radioOffline.addEventListener('change', updateOnlineFieldsVisibility);
        radioOnline.addEventListener('change', updateOnlineFieldsVisibility);
    }

    updateOnlineFieldsVisibility();

    const modal = new bootstrap.Modal(document.getElementById('lectureModeModal'));
    modal.show();
}

/**
 * Save Lecture Mode Override
 */
function saveLectureModeOverride() {
    const form = document.getElementById('lectureModeForm');
    if (!form) return;

    const formData = new FormData(form);
    const lectureMode = formData.get('lecture_mode');
    let meetingLink = formData.get('meeting_link');

    const meetingId = formData.get('meeting_id') ? formData.get('meeting_id').trim() : null;

    if (meetingLink) {
        meetingLink = meetingLink.trim();
    }

    if (lectureMode === 'online') {
        if (!meetingLink || meetingLink === '') {
            showError('Meeting link is required for online lectures');
            return;
        }

        try {
            new URL(meetingLink);
        } catch (e) {
            showError('Please enter a valid URL (e.g., https://meet.google.com/xxx-xxxx-xxx)');
            return;
        }
    }

    const data = {
        lecture_id: formData.get('lecture_id'),
        assignment_id: formData.get('assignment_id'),
        override_date: formData.get('override_date'),
        lecture_mode: lectureMode,
        meeting_link: (lectureMode === 'online' && meetingLink) ? meetingLink : null,
        meeting_id: (lectureMode === 'online' && meetingId) ? meetingId : null,
        meeting_password: (lectureMode === 'online' && formData.get('meeting_password')) ? formData.get(
            'meeting_password') : null,
       
        meeting_instructions: (lectureMode === 'online' && formData.get('meeting_instructions')) ? formData.get(
            'meeting_instructions') : null,
        remarks: formData.get('remarks'),
        _token: formData.get('_token')
    };

    showLoading();

    $.ajax({
        url: '{{ route("institute-admin.timetable.save-override") }}',
        method: 'POST',
        data: data,
        success: function(response) {
            if (response.success) {
                showSuccess(response.message || 'Override saved successfully');
                const modalEl = document.getElementById('lectureModeModal');
                if (modalEl) {
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                }
                window.location.reload();
            } else {
                showError(response.message || 'Failed to save override');
            }
            hideLoading();
        },
        error: function(xhr) {
            console.error('Error:', xhr);
            let errorMsg = 'Error saving override';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                const errors = xhr.responseJSON.errors;
                errorMsg = Object.values(errors).flat().join(', ');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            showError(errorMsg);
            hideLoading();
        }
    });
}

/**
 * Delete Lecture Mode Override
 */
function deleteLectureModeOverride() {
    if (!confirm(
            'Are you sure you want to remove this override? The lecture will revert to its default mode for this day.'
            )) {
        return;
    }

    const overrideId = currentEditingLecture && currentEditingLecture.overrideId;
    if (!overrideId || overrideId === 'null') {
        showError('No override found to delete');
        return;
    }

    showLoading();

    $.ajax({
        url: '{{ route("institute-admin.timetable.delete-override") }}',
        method: 'DELETE',
        data: {
            override_id: overrideId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                showSuccess(response.message);
                const modalEl = document.getElementById('lectureModeModal');
                if (modalEl) {
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                }
                window.location.reload();
            } else {
                showError(response.message || 'Failed to remove override');
            }
            hideLoading();
        },
        error: function(xhr) {
            console.error('Error:', xhr);
            showError('Error removing override');
            hideLoading();
        }
    });
}

/**
 * Show Multiple Events (UPDATED to use per-day override editing)
 */
function showMultipleEvents(date, timeKey) {
    if (!currentData || !currentData.timetable || !currentData.timetable[date] || !currentData.timetable[date][
        timeKey]) {
        showError('No events found');
        return;
    }

    const eventGroup = currentData.timetable[date][timeKey];
    if (!eventGroup.has_multiple || !eventGroup.events) {
        showError('Invalid event group');
        return;
    }

    const events = eventGroup.events;
    const parsedDate = new Date(date);
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
        'October', 'November', 'December'
    ];
    const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    let html = `
        <div class="mb-3">
            <h4>${monthNames[parsedDate.getMonth()]} ${parsedDate.getDate()}, ${parsedDate.getFullYear()}</h4>
            <h6 class="text-muted">${dayNames[parsedDate.getDay()]}</h6>
            <h5 class="text-primary">${events[0] && (events[0].start_time_formatted || events[0].start_time)} - ${events[0] && (events[0].end_time_formatted || events[0].end_time)}</h5>
        </div>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> ${events.length} events scheduled at this time
        </div>
    `;

    const lectures = events.filter(e => e.type === 'lecture');
    const duties = events.filter(e => e.type === 'duty');

    if (lectures.length > 0) {
        html += `
            <div class="mb-4">
                <h5 class="text-primary mb-3">
                    <i class="fas fa-book"></i> Lectures (${lectures.length})
                    <small class="text-muted ms-2">Click on any lecture to edit mode for this day</small>
                </h5>
                <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                    <table class="erp-table">
                        <thead>
                            <tr>
                                <th class="sticky-checkbox sortable">Mode</th>
                                <th class="sortable">Subject</th>
                                <th class="sortable">Faculty</th>
                                <th class="sortable">Department</th>
                                <th class="sortable">Course/Section</th>
                                <th class="sortable">Room</th>
                                <th class="sortable">Action</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

        lectures.forEach(lecture => {
            const courseSection = `${lecture.course || 'N/A'}${lecture.section ? ' - ' + lecture.section : ''}`;
            const isOnline = lecture.lecture_mode === 'online';
            const modeBadge = isOnline ?
                '<span class="badge bg-info">Online</span>' :
                '<span class="badge bg-secondary">Offline</span>';
            const overrideBadge = lecture.has_override ?
                '<span class="badge bg-warning text-dark ms-1"><i class="fas fa-calendar-day"></i> Override</span>' :
                '';

            const safeMeetingLink = escapeJsString(lecture.meeting_link);
            const safeMeetingPassword = escapeJsString(lecture.meeting_password);
            const safeMeetingInstructions = escapeJsString(lecture.meeting_instructions);
            const safeMeetingId = escapeJsString(lecture.meeting_id);
            const assignmentId = lecture.assignment_id ? `'${escapeJsString(lecture.assignment_id)}'` : 'null';
            const reassignmentText = lecture.is_reassigned ? (lecture.reassigned_from_employee_name && lecture
                .reassigned_to_employee_name ?
                `Reassigned from ${lecture.reassigned_from_employee_name} to ${lecture.reassigned_to_employee_name}` :
                (lecture.reassigned_to_employee_name ?
                    `Reassigned to ${lecture.reassigned_to_employee_name}` : (lecture
                        .reassigned_from_employee_name ?
                        `Reassigned from ${lecture.reassigned_from_employee_name}` : 'Reassigned'))) : '';
            const reassignmentBadge = reassignmentText ?
                `<span class="badge bg-secondary ms-1">${reassignmentText}</span>` : '';

            html += `
                <tr style="cursor: pointer;" onclick="editLectureModeForDay(${lecture.id}, ${assignmentId}, '${escapeJsString(lecture.date)}', '${escapeJsString(lecture.lecture_mode || '')}', '${safeMeetingLink}', '${safeMeetingPassword}', '${safeMeetingInstructions}', '${safeMeetingId}')">
                    <td class="sticky-checkbox">${modeBadge} ${overrideBadge} ${reassignmentBadge}</td>
                    <td><strong>${lecture.title || lecture.subject}</strong>
                        ${isOnline && lecture.meeting_link ? `<br><small><a href="${lecture.meeting_link}" target="_blank" onclick="event.stopPropagation()"><i class="fas fa-link"></i> Join Meeting</a></small>` : ''}
                    </td>
                    <td>${lecture.faculty}<br><small class="text-muted">${lecture.designation || ''}</small></td>
                    <td>${lecture.department || 'N/A'}</td>
                    <td>${courseSection}</td>
                    <td>${lecture.location || lecture.room || 'N/A'}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="event.stopPropagation(); editLectureModeForDay(${lecture.id}, ${assignmentId}, '${escapeJsString(lecture.date)}', '${escapeJsString(lecture.lecture_mode || '')}', '${safeMeetingLink}', '${safeMeetingPassword}', '${safeMeetingInstructions}', '${safeMeetingId}')">
                            <i class="fas fa-edit"></i> Edit 
                        </button>
                    </td>
                </tr>
            `;
        });

        html += `
                        </tbody>
                    </table>
                </div>
                <!-- Floating Horizontal Scrollbar -->
                <div class="table-scroll-top" id="tableScrollTop">
                    <div class="table-scroll-inner"></div>
                </div>
            </div>
        `;
    }

    if (duties.length > 0) {
        html += `
            <div class="mb-4">
                <h5 class="text-warning mb-3">
                    <i class="fas fa-tasks"></i> Duties (${duties.length})
                </h5>
                <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                    <table class="erp-table">
                        <thead>
                            <tr>
                                <th class="sortable">Title</th>
                                <th class="sortable">Employee</th>
                                <th class="sortable">Department</th>
                                <th class="sortable">Priority</th>
                                <th class="sortable">Location</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

        duties.forEach(duty => {
            let priorityBadge = '';
            if (duty.priority === 'low') priorityBadge = '<span class="badge bg-success">Low</span>';
            else if (duty.priority === 'medium') priorityBadge = '<span class="badge bg-primary">Medium</span>';
            else if (duty.priority === 'high') priorityBadge = '<span class="badge bg-warning">High</span>';
            else if (duty.priority === 'urgent') priorityBadge = '<span class="badge bg-danger">Urgent</span>';
            else priorityBadge = '<span class="badge bg-secondary">N/A</span>';

            html += `
                <tr style="cursor: pointer;" onclick="showEventDetails('duty', ${duty.id}, '${escapeJsString(duty.date)}')">
                    <td><strong>${duty.title}</strong></td>
                    <td>${duty.faculty}<br><small class="text-muted">${duty.designation || ''}</small></td>
                    <td>${duty.department || 'N/A'}</td>
                    <td>${priorityBadge}</td>
                    <td>${duty.location || duty.venue || 'N/A'}</td>
                </tr>
            `;
        });

        html += `
                        </tbody>
                    </table>
                </div>
                <!-- Floating Horizontal Scrollbar -->
                <div class="table-scroll-top" id="tableScrollTop">
                    <div class="table-scroll-inner"></div>
                </div>
            </div>
        `;
    }

    document.getElementById('detailsModalTitle').innerHTML =
        `<i class="fas fa-layer-group"></i> Multiple Events - ${events.length} Events`;
    document.getElementById('detailsModalBody').innerHTML = html;

    const modal = document.getElementById('detailsModal');
    const modalDialog = modal.querySelector('.modal-dialog');
    modalDialog.classList.add('modal-xl');

    const bsModal = showDetailsModal();

    modal.addEventListener('hidden.bs.modal', function() {
        modalDialog.classList.remove('modal-xl');
    }, {
        once: true
    });
}

// Update the showEventDetails function to show override info clearly
function showEventDetails(type, id, date) {
    if (!currentData) return;

    let eventData = null;

    // First try to find in listData
    if (currentData.listData) {
        eventData = currentData.listData.find(item => item.type === type && item.id == id);
    }

    // If not found, try to find in monthData
    if (!eventData && currentData.monthData) {
        const dayData = currentData.monthData[date];
        if (dayData) {
            if (type === 'lecture' && dayData.lectures) {
                eventData = dayData.lectures.find(l => l.id == id);
            } else if (type === 'duty' && dayData.duties) {
                eventData = dayData.duties.find(d => d.id == id);
            }
        }
    }

    // If still not found, try to find in timetable data
    if (!eventData && currentData.timetable) {
        for (const [dateKey, timeSlots] of Object.entries(currentData.timetable)) {
            for (const [timeKey, event] of Object.entries(timeSlots)) {
                if (event && !event.has_multiple && event.id == id && event.type === type) {
                    eventData = event;
                    break;
                }
                if (event && event.has_multiple && event.events) {
                    const found = event.events.find(e => e.id == id && e.type === type);
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
        console.error('Event not found:', type, id, date);
        showError('Event details not found');
        return;
    }

    let html = '';

    if (type === 'lecture') {
        // Get lecture mode (already has override applied if exists)
        const isOnline = eventData.lecture_mode === 'online';
        const modeBadge = isOnline ?
            '<span class="badge bg-info">Online Lecture</span>' :
            '<span class="badge bg-secondary">Offline Lecture</span>';

        // Determine if this is an override
        const hasOverride = eventData.has_override === true;
        const overrideReason = eventData.override_reason || eventData.remarks || 'No reason provided';

        // Build override info HTML
        let overrideInfoHtml = '';
        if (hasOverride) {
            overrideInfoHtml = `
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-calendar-day"></i> <strong>Day-Specific Override Active!</strong><br>
                    <small>This lecture is using a different mode today than its regular schedule.</small>
                    ${overrideReason !== 'No reason provided' ? `<br><small><strong>Reason:</strong> ${overrideReason}</small>` : ''}
                    <div class="mt-2">
                        <span class="badge bg-warning text-dark">Override Applied</span>
                        <span class="badge bg-info">Affects only: ${eventData.date || date}</span>
                    </div>
                </div>
            `;
        } else {
            overrideInfoHtml = `
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i> <strong>Using Default Schedule Mode</strong><br>
                    <small>This lecture is following its regular schedule today. Create a day-specific override if you need to change the mode for this day only.</small>
                </div>
            `;
        }

        let reassignmentInfoHtml = '';
        if (eventData.is_reassigned) {
            let reassignmentClass = 'alert-warning';
            let reassignmentTitle = 'Reassigned Lecture';
            let reassignmentDetail = '';

            if (eventData.employee_id && eventData.reassigned_to_employee_id && eventData.employee_id.toString() ===
                eventData.reassigned_to_employee_id.toString()) {
                reassignmentClass = 'alert-success';
                reassignmentTitle = 'Reassigned to Current Employee';
                reassignmentDetail = `from ${eventData.reassigned_from_employee_name || 'another employee'}`;
            } else if (eventData.reassigned_to_employee_name) {
                reassignmentDetail = `to ${eventData.reassigned_to_employee_name}`;
            } else if (eventData.reassigned_from_employee_name) {
                reassignmentClass = 'alert-success';
                reassignmentTitle = 'Reassigned from Another Employee';
                reassignmentDetail = `from ${eventData.reassigned_from_employee_name}`;
            }

            reassignmentInfoHtml = `
                <div class="alert ${reassignmentClass} mt-3">
                    <i class="fas fa-exchange-alt"></i> <strong>${reassignmentTitle}</strong>
                    ${reassignmentDetail ? `<br><small>${reassignmentDetail}</small>` : ''}
                    ${eventData.reassignment_date ? `<br><small><strong>Date:</strong> ${eventData.reassignment_date}</small>` : ''}
                    ${eventData.reassignment_reason ? `<br><small><strong>Reason:</strong> ${eventData.reassignment_reason}</small>` : ''}
                </div>
            `;
        }

        // Format course and section
        const courseSection = `${eventData.course || 'N/A'}${eventData.section ? ' - ' + eventData.section : ''}`;

        // Get shift info
        const shiftInfo = eventData.shift_name ?
            `${eventData.shift_name}${eventData.shift_timing ? ' (' + eventData.shift_timing + ')' : ''}` :
            'No shift assigned';

        // Format times
        const startTimeFormatted = eventData.start_time_formatted ||
            (eventData.start_time ? date('h:i A', new Date('2000-01-01T' + eventData.start_time)) : 'N/A');
        const endTimeFormatted = eventData.end_time_formatted ||
            (eventData.end_time ? date('h:i A', new Date('2000-01-01T' + eventData.end_time)) : 'N/A');

        html = `
            <div class="row">
                <div class="col-md-6">
                    <p><strong><i class="fas fa-book"></i> Subject:</strong> ${eventData.title || eventData.subject || 'N/A'}</p>
                    <p><strong><i class="fas fa-chalkboard-user"></i> Faculty:</strong> ${eventData.faculty || 'N/A'}</p>
                    <p><strong><i class="fas fa-id-card"></i> Employee ID:</strong> ${eventData.employee_id || 'N/A'}</p>
                    <p><strong><i class="fas fa-graduation-cap"></i> Designation:</strong> ${eventData.designation || 'N/A'}</p>
                    <p><strong><i class="fas fa-building"></i> Department:</strong> ${eventData.department || 'N/A'}</p>
                    <p><strong><i class="fas fa-clock"></i> Shift:</strong> ${shiftInfo}</p>
                </div>
                <div class="col-md-6">
                    <p><strong><i class="fas fa-video"></i> Today's Mode:</strong> ${modeBadge}</p>
                    ${hasOverride ? '<p><small><i class="fas fa-calendar-day"></i> <em>Override active for this specific day</em></small></p>' : ''}
                    <p><strong><i class="fas fa-clock"></i> Time:</strong> ${startTimeFormatted} - ${endTimeFormatted}</p>
                    <p><strong><i class="fas fa-calendar-day"></i> Date:</strong> ${eventData.date || date}</p>
                    <p><strong><i class="fas fa-location-dot"></i> Location/Room:</strong> ${eventData.location || eventData.room || 'Not specified'}</p>
                    <p><strong><i class="fas fa-school"></i> Course:</strong> ${courseSection}</p>
                    <p><strong><i class="fas fa-repeat"></i> Frequency:</strong> ${eventData.frequency || 'Regular'}</p>
                </div>
            </div>
            ${reassignmentInfoHtml}
            ${overrideInfoHtml}
        `;

        // Add meeting details if online
        if (isOnline && eventData.meeting_link) {
            html += `
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-video"></i> Meeting Details</h6>
                            <p><strong>Meeting Link:</strong> <a href="${eventData.meeting_link}" target="_blank" onclick="event.stopPropagation()">${eventData.meeting_link}</a></p>
                            ${eventData.meeting_id ? `<p><strong>Meeting ID:</strong> <code>${eventData.meeting_id}</code></p>` : ''}
                            ${eventData.meeting_password ? `<p><strong>Password:</strong> <code>${eventData.meeting_password}</code></p>` : ''}
                            ${eventData.meeting_instructions ? `<p><strong>Instructions:</strong><br>${eventData.meeting_instructions.replace(/\n/g, '<br>')}</p>` : ''}
                        </div>
                    </div>
                </div>
            `;
        }

        // Add description if exists
        if (eventData.description && eventData.description !== 'No description') {
            html += `
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <strong><i class="fas fa-align-left"></i> Description</strong>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">${eventData.description.replace(/\n/g, '<br>')}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Add action buttons
        const assignmentId = eventData.assignment_id ? `'${escapeJsString(eventData.assignment_id)}'` : 'null';
        const safeMeetingLink = escapeJsString(eventData.meeting_link);
        const safeMeetingPassword = escapeJsString(eventData.meeting_password);
        const safeMeetingInstructions = escapeJsString(eventData.meeting_instructions);
        const safeMeetingId = escapeJsString(eventData.meeting_id);

        html += `
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <div class="btn-group" role="group">
                        <button class="btn btn-primary" onclick="event.stopPropagation(); 
                            editLectureModeForDay(${eventData.id}, ${assignmentId}, '${escapeJsString(eventData.date || date)}', '${escapeJsString(eventData.lecture_mode || 'offline')}', '${safeMeetingLink}', '${safeMeetingPassword}', '${safeMeetingInstructions}', '${safeMeetingId}');
                            document.getElementById('detailsModal').modal('hide');
                        ">
                            <i class="fas fa-edit"></i> ${hasOverride ? 'Edit Override' : 'Create Override for This Day'}
                        </button>
                        ${hasOverride ? `
                        <button class="btn btn-danger" onclick="event.stopPropagation();
                            if(confirm('Remove override for this day? The lecture will revert to its default mode.')) {
                                deleteLectureModeOverrideById(${eventData.override_id});
                                document.getElementById('detailsModal').modal('hide');
                            }
                        ">
                            <i class="fas fa-trash"></i> Remove Override
                        </button>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;

    } else if (type === 'duty') {
        // Duty details
        let priorityClass = '';
        let priorityIcon = '';
        let priorityBadge = '';

        switch (eventData.priority) {
            case 'low':
                priorityClass = 'text-success';
                priorityIcon = '🟢';
                priorityBadge = '<span class="badge bg-success">Low Priority</span>';
                break;
            case 'medium':
                priorityClass = 'text-primary';
                priorityIcon = '🔵';
                priorityBadge = '<span class="badge bg-primary">Medium Priority</span>';
                break;
            case 'high':
                priorityClass = 'text-warning';
                priorityIcon = '🟠';
                priorityBadge = '<span class="badge bg-warning text-dark">High Priority</span>';
                break;
            case 'urgent':
                priorityClass = 'text-danger';
                priorityIcon = '🔴';
                priorityBadge = '<span class="badge bg-danger">Urgent Priority</span>';
                break;
            default:
                priorityClass = 'text-secondary';
                priorityIcon = '⚪';
                priorityBadge = '<span class="badge bg-secondary">Priority Not Set</span>';
        }

        // Get shift info
        const shiftInfo = eventData.shift_name ?
            `${eventData.shift_name}${eventData.shift_timing ? ' (' + eventData.shift_timing + ')' : ''}` :
            'No shift assigned';

        // Format times
        const startTimeFormatted = eventData.start_time_formatted ||
            (eventData.start_time ? date('h:i A', new Date('2000-01-01T' + eventData.start_time)) : 'N/A');
        const endTimeFormatted = eventData.end_time_formatted ||
            (eventData.end_time ? date('h:i A', new Date('2000-01-01T' + eventData.end_time)) : 'N/A');

        // Status badge
        let statusBadge = '';
        if (eventData.status) {
            switch (eventData.status) {
                case 'pending':
                    statusBadge = '<span class="badge bg-warning text-dark">Pending</span>';
                    break;
                case 'in-progress':
                    statusBadge = '<span class="badge bg-info">In Progress</span>';
                    break;
                case 'completed':
                    statusBadge = '<span class="badge bg-success">Completed</span>';
                    break;
                case 'cancelled':
                    statusBadge = '<span class="badge bg-danger">Cancelled</span>';
                    break;
                default:
                    statusBadge = `<span class="badge bg-secondary">${eventData.status}</span>`;
            }
        }

        html = `
            <div class="row">
                <div class="col-md-6">
                    <p><strong><i class="fas fa-tasks"></i> Title:</strong> ${eventData.title || 'N/A'}</p>
                    <p><strong><i class="fas fa-user"></i> Assigned To:</strong> ${eventData.faculty || 'N/A'}</p>
                    <p><strong><i class="fas fa-id-card"></i> Employee ID:</strong> ${eventData.employee_id || 'N/A'}</p>
                    <p><strong><i class="fas fa-graduation-cap"></i> Designation:</strong> ${eventData.designation || 'N/A'}</p>
                    <p><strong><i class="fas fa-building"></i> Department:</strong> ${eventData.department || 'N/A'}</p>
                    <p><strong><i class="fas fa-clock"></i> Shift:</strong> ${shiftInfo}</p>
                </div>
                <div class="col-md-6">
                    <p><strong><i class="fas fa-flag"></i> Priority:</strong> ${priorityIcon} ${priorityBadge}</p>
                    <p><strong><i class="fas fa-clock"></i> Time:</strong> ${startTimeFormatted} - ${endTimeFormatted}</p>
                    <p><strong><i class="fas fa-calendar-day"></i> Date:</strong> ${eventData.date || date}</p>
                    <p><strong><i class="fas fa-location-dot"></i> Location:</strong> ${eventData.location || 'Not specified'}</p>
                    ${eventData.venue ? `<p><strong><i class="fas fa-map-pin"></i> Venue:</strong> ${eventData.venue}</p>` : ''}
                    <p><strong><i class="fas fa-repeat"></i> Frequency:</strong> ${eventData.frequency || 'Once'}</p>
                    <p><strong><i class="fas fa-info-circle"></i> Status:</strong> ${statusBadge}</p>
                </div>
            </div>
        `;

        // Add description if exists
        if (eventData.description && eventData.description !== 'No description') {
            html += `
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <strong><i class="fas fa-align-left"></i> Description</strong>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">${eventData.description.replace(/\n/g, '<br>')}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Add instructions if exists
        if (eventData.instructions && eventData.instructions !== 'No instructions') {
            html += `
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <strong><i class="fas fa-clipboard-list"></i> Instructions</strong>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">${eventData.instructions.replace(/\n/g, '<br>')}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
    }

    // Set modal title and body
    const titleIcon = type === 'lecture' ? 'fa-book' : 'fa-tasks';
    const titleText = type === 'lecture' ? 'Lecture Details' : 'Duty Details';
    document.getElementById('detailsModalTitle').innerHTML = `<i class="fas ${titleIcon}"></i> ${titleText}`;
    document.getElementById('detailsModalBody').innerHTML = html;

    // Show modal
    showDetailsModal();
}

// Helper function to format time
function date(format, dateObj) {
    if (!dateObj) return 'N/A';
    const hours = dateObj.getHours();
    const minutes = dateObj.getMinutes();
    const ampm = hours >= 12 ? 'PM' : 'AM';
    const hour12 = hours % 12 || 12;
    const minuteStr = minutes.toString().padStart(2, '0');

    if (format === 'h:i A') {
        return `${hour12}:${minuteStr} ${ampm}`;
    }
    return `${hour12}:${minuteStr} ${ampm}`;
}

// Helper function to delete override by ID (called from modal)
function deleteLectureModeOverrideById(overrideId) {
    if (!overrideId || overrideId === 'null') {
        showError('No override found to delete');
        return;
    }

    showLoading();

    $.ajax({
        url: '{{ route("institute-admin.timetable.delete-override") }}',
        method: 'DELETE',
        data: {
            override_id: overrideId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                showSuccess(response.message);
                window.location.reload();
            } else {
                showError(response.message || 'Failed to remove override');
            }
            hideLoading();
        },
        error: function(xhr) {
            console.error('Error:', xhr);
            showError('Error removing override');
            hideLoading();
        }
    });
}

function showFullDayDetails(date) {
    if (!currentData || !currentData.monthData || !currentData.monthData[date]) {
        showError('No data found for this date');
        return;
    }

    const dayData = currentData.monthData[date];
    const parsedDate = new Date(date);
    const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
        'October', 'November', 'December'
    ];
    const dayName = dayNames[parsedDate.getDay()];
    const monthName = monthNames[parsedDate.getMonth()];
    const year = parsedDate.getFullYear();

    let html = `
        <div class="mb-3">
            <h6 class="text-muted">${dayName}</h6>
        </div>
    `;

    if (dayData.lectures && dayData.lectures.length > 0) {
        html += `
            <div class="mb-4">
                <h5 class="text-primary mb-3">
                    <i class="fas fa-book"></i> Lectures (${dayData.lectures.length})
                </h5>
                <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                    <table class="erp-table">
                        <thead>
                            <tr>
                                <th class="sortable">Time</th>
                                <th class="sortable">Mode</th>
                                <th class="sortable">Subject</th>
                                <th class="sortable">Faculty</th>
                                <th class="sortable">Department</th>
                                <th class="sortable">Course/Section</th>
                                <th class="sortable">Room</th>
                                <th class="sortable">Override</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

        const sortedLectures = [...dayData.lectures].sort((a, b) => {
            return (a.start_time || '').localeCompare(b.start_time || '');
        });

        sortedLectures.forEach(lecture => {
            const timeDisplay = lecture.start_time_formatted || lecture.start_time || 'N/A';
            const endTimeDisplay = lecture.end_time_formatted || lecture.end_time || 'N/A';
            const courseSection = `${lecture.course || 'N/A'}${lecture.section ? ' - ' + lecture.section : ''}`;
            const modeBadge = lecture.lecture_mode === 'online' ?
                '<span class="badge bg-info">Online</span>' :
                '<span class="badge bg-secondary">Offline</span>';
            const overrideBadge = lecture.has_override ?
                '<span class="badge bg-warning text-dark">Yes</span>' :
                '<span class="badge bg-secondary">No</span>';

            html += `
                <tr style="cursor: pointer;" onclick="showEventDetails('lecture', ${lecture.id}, '${escapeJsString(lecture.date)}')">
                    <td><span class="badge bg-primary">${timeDisplay} - ${endTimeDisplay}</span></td>
                    <td>${modeBadge}</td>
                    <td><strong>${lecture.title || lecture.subject}</strong>${lecture.lecture_mode === 'online' && lecture.meeting_link ? `<br><small><a href="${lecture.meeting_link}" target="_blank" onclick="event.stopPropagation()"><i class="fas fa-link"></i> Join</a></small>` : ''}</td>
                    <td>${lecture.faculty}<br><small class="text-muted">${lecture.designation || ''}</small></td>
                    <td>${lecture.department || 'N/A'}</td>
                    <td>${courseSection}</td>
                    <td>${lecture.location || lecture.room || 'N/A'}</td>
                    <td>${overrideBadge}</td>
                </tr>
            `;
        });

        html += `
                        </tbody>
                    </table>
                </div>
                <!-- Floating Horizontal Scrollbar -->
                <div class="table-scroll-top" id="tableScrollTop">
                    <div class="table-scroll-inner"></div>
                </div>
            </div>
        `;
    }

    if (dayData.duties && dayData.duties.length > 0) {
        html += `
            <div class="mb-4">
                <h5 class="text-warning mb-3">
                    <i class="fas fa-tasks"></i> Duties (${dayData.duties.length})
                </h5>
                <div class="table-responsive custom-table-wrapper" id="tableWrapper">
                    <table class="erp-table table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th class="sortable">Time</th>
                                <th class="sortable">Title</th>
                                <th class="sortable">Employee</th>
                                <th class="sortable">Department</th>
                                <th class="sortable">Priority</th>
                                <th class="sortable">Location</th>
                            </tr>
                        </thead>
                        <tbody>
        `;

        const sortedDuties = [...dayData.duties].sort((a, b) => {
            return (a.start_time || '').localeCompare(b.start_time || '');
        });

        sortedDuties.forEach(duty => {
            const timeDisplay = duty.start_time_formatted || duty.start_time || 'N/A';
            const endTimeDisplay = duty.end_time_formatted || duty.end_time || 'N/A';
            let priorityBadge = '';
            if (duty.priority === 'low') priorityBadge = '<span class="badge bg-success">Low</span>';
            else if (duty.priority === 'medium') priorityBadge = '<span class="badge bg-primary">Medium</span>';
            else if (duty.priority === 'high') priorityBadge = '<span class="badge bg-warning">High</span>';
            else if (duty.priority === 'urgent') priorityBadge = '<span class="badge bg-danger">Urgent</span>';
            else priorityBadge = '<span class="badge bg-secondary">N/A</span>';

            html += `
                <tr style="cursor: pointer;" onclick="showEventDetails('duty', ${duty.id}, '${escapeJsString(duty.date)}')">
                    <td><span class="badge bg-warning">${timeDisplay} - ${endTimeDisplay}</span></td>
                    <td><strong>${duty.title}</strong></td>
                    <td>${duty.faculty}<br><small class="text-muted">${duty.designation || ''}</small></td>
                    <td>${duty.department || 'N/A'}</td>
                    <td>${priorityBadge}</td>
                    <td>${duty.location || duty.venue || 'N/A'}</td>
                </tr>
            `;
        });

        html += `
                        </tbody>
                    </table>
                </div>
                <!-- Floating Horizontal Scrollbar -->
                <div class="table-scroll-top" id="tableScrollTop">
                    <div class="table-scroll-inner"></div>
                </div>
            </div>
        `;
    }

    if ((!dayData.lectures || dayData.lectures.length === 0) && (!dayData.duties || dayData.duties.length === 0)) {
        html += '<div class="alert alert-info text-center">No events scheduled for this day</div>';
    }

    const totalLectures = dayData.lectures ? dayData.lectures.length : 0;
    const totalDuties = dayData.duties ? dayData.duties.length : 0;
    const onlineLectures = dayData.lectures ? dayData.lectures.filter(l => l.lecture_mode === 'online').length : 0;
    const overrideCount = dayData.lectures ? dayData.lectures.filter(l => l.has_override === true).length : 0;
    const departments = new Set();
    if (dayData.lectures) {
        dayData.lectures.forEach(l => l.department && departments.add(l.department));
    }

    html += `
        <hr>
        <div class="row mt-3">
            <div class="col-md-3">
                <div class="alert alert-primary text-center">
                    <h5>${totalLectures}</h5>
                    <small>Lectures</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-info text-center">
                    <h5>${onlineLectures}</h5>
                    <small>Online Lectures</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-warning text-center">
                    <h5>${overrideCount}</h5>
                    <small>Overrides</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="alert alert-success text-center">
                    <h5>${departments.size}</h5>
                    <small>Departments</small>
                </div>
            </div>
        </div>
    `;

    document.getElementById('detailsModalTitle').innerHTML =
        `<i class="fas fa-calendar-day"></i> Schedule for ${monthName} ${parsedDate.getDate()}, ${year}`;
    document.getElementById('detailsModalBody').innerHTML = html;

    const modal = document.getElementById('detailsModal');
    const modalDialog = modal.querySelector('.modal-dialog');
    modalDialog.classList.add('modal-xl');

    const bsModal = showDetailsModal();

    modal.addEventListener('hidden.bs.modal', function() {
        modalDialog.classList.remove('modal-xl');
    }, {
        once: true
    });
}

function showConflicts(conflicts) {
    let html =
        '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Multiple events scheduled at the same time!</div>';
    conflicts.forEach(event => {
        html +=
            `<div class="card mb-2"><div class="card-header ${event.type === 'lecture' ? 'bg-primary' : 'bg-warning'} text-white">${event.type === 'lecture' ? '📖 Lecture' : '📋 Duty'}</div><div class="card-body"><p><strong>${event.type === 'lecture' ? 'Subject:' : 'Title:'}</strong> ${event.title}</p><p><strong>${event.type === 'lecture' ? 'Faculty:' : 'Employee:'}</strong> ${event.faculty}</p><p><strong>Time:</strong> ${event.start_time_formatted || event.start_time} - ${event.end_time_formatted || event.end_time}</p></div></div>`;
    });
    document.getElementById('detailsModalTitle').innerHTML =
        '<i class="fas fa-exclamation-triangle"></i> Schedule Conflicts';
    document.getElementById('detailsModalBody').innerHTML = html;
    showDetailsModal();
}

function switchView(view) {
    currentView = view;
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.classList.toggle('active', btn.getAttribute('data-view') === view);
    });

    if (view === 'day') {
        setDefaultDayDates();
    } else if (view === 'week') {
        const selectedDate = document.getElementById('startDate').value || formatDateInput(new Date());
        setWeekDatesFor(selectedDate);
    }

    document.getElementById('dayView').style.display = view === 'day' ? 'block' : 'none';
    document.getElementById('weekView').style.display = view === 'week' ? 'block' : 'none';
    document.getElementById('monthView').style.display = view === 'month' ? 'block' : 'none';
    document.getElementById('listView').style.display = view === 'list' ? 'block' : 'none';

    if (view === 'day' || view === 'week') {
        loadTimetable();
    } else if (currentData) {
        renderCurrentView();
    } else {
        loadTimetable();
    }
    
    if(view === 'list'){
        setTimeout(() => {
    
            const wrapper = document.querySelector('#listView #tableWrapper');
            const topScroll = document.querySelector('#listView #tableScrollTop');
            const scrollInner = topScroll?.querySelector('.table-scroll-inner');
    
            if(!wrapper || !topScroll || !scrollInner) return;
    
            const table = wrapper.querySelector('table');
            if(!table) return;
    
            scrollInner.style.width = table.scrollWidth + 'px';
    
            topScroll.scrollLeft = 0;
    
            topScroll.addEventListener('scroll', () => {
                wrapper.scrollLeft = topScroll.scrollLeft;
            });
    
            wrapper.addEventListener('scroll', () => {
                topScroll.scrollLeft = wrapper.scrollLeft;
            });
    
            console.log(
                'List View:',
                table.scrollWidth,
                wrapper.clientWidth
            );
    
        }, 300);
    }
}

function clearFilters() {
    document.getElementById('departmentFilter').value = '';
    document.getElementById('courseFilter').value = '';
    document.getElementById('facultyFilter').value = '';
    document.getElementById('priorityFilter').value = '';
    document.getElementById('subjectFilter').value = '';
    updateSubjectFilterOptions('');
    if (currentView === 'day') {
        setDefaultDayDates();
    } else {
        setDefaultWeekDates();
    }
    loadTimetable();
}

function showError(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3';
    alertDiv.style.zIndex = '10000';
    alertDiv.style.minWidth = '300px';
    alertDiv.innerHTML =
        `<i class="fas fa-exclamation-triangle"></i> ${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    document.body.appendChild(alertDiv);
    setTimeout(() => alertDiv.remove(), 5000);
}

function showSuccess(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
    alertDiv.style.zIndex = '10000';
    alertDiv.style.minWidth = '300px';
    alertDiv.innerHTML =
        `<i class="fas fa-check-circle"></i> ${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    document.body.appendChild(alertDiv);
    setTimeout(() => alertDiv.remove(), 3000);
}

function getDetailsModalInstance() {
    const modalEl = document.getElementById('detailsModal');
    let modalInstance = bootstrap.Modal.getInstance(modalEl);
    if (!modalInstance) {
        modalInstance = new bootstrap.Modal(modalEl);
    }
    return modalInstance;
}

function showDetailsModal() {
    const bsModal = getDetailsModalInstance();
    bsModal.show();
    return bsModal;
}

// Event listeners
document.getElementById('startDate').addEventListener('change', () => loadTimetable());
document.getElementById('endDate').addEventListener('change', () => loadTimetable());
document.getElementById('departmentFilter').addEventListener('change', () => loadTimetable());
document.getElementById('courseFilter').addEventListener('change', onCourseFilterChange);
document.getElementById('facultyFilter').addEventListener('change', () => loadTimetable());
document.getElementById('priorityFilter').addEventListener('change', () => loadTimetable());
document.getElementById('subjectFilter').addEventListener('change', () => loadTimetable());

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    setDefaultDayDates();
    updateSubjectFilterOptions('');
    loadTimetable();
    switchView('day');
});
</script>
@endsection