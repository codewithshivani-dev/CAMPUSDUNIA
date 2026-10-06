@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('title', 'My Attendance')

@section('content')
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
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

.container-fluid {
    max-width: 1400px;
}

/* Page Header */
.page-header {
    border-radius: 20px;
    padding: 25px 30px;
    margin-bottom: 25px;
    background: var(--primary-gradient);
}

.page-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #fff;
}

.page-header h2 i {
    background: #fff;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Student Info Card */
.student-info-card {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 2px solid rgba(67, 97, 238, 0.1);
    margin-bottom: 25px;
}

.teacher-avatar {
    background: var(--primary-gradient) !important;
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
}

/* Stats Cards */
.stats-card {
    border-radius: 18px;
    padding: 22px;
    color: white;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    margin-bottom: 20px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: -30%;
    right: -30%;
    width: 150px;
    height: 150px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
}

.stats-card.today {
    background: var(--primary-gradient);
}

.stats-card.weekly {
    background: var(--success-gradient);
}

.stats-card.monthly {
    background: var(--info-gradient);
}

.stats-card.streak {
    background: var(--warning-gradient);
}

.stats-number {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 6px;
    position: relative;
    z-index: 1;
}

.stats-label {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    opacity: 0.9;
    font-weight: 600;
    position: relative;
    z-index: 1;
}

/* Date Navigation */
.date-navigation {
    background: white;
    border-radius: 18px;
    padding: 18px 22px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.06);
    border: 2px solid rgba(67, 97, 238, 0.1);
    margin-bottom: 25px;
}

.date-navigation h5 {
    color: var(--primary-color);
    font-weight: 700;
}

.date-navigation .btn-outline-primary {
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
    border-radius: 25px;
    padding: 8px 16px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.date-navigation .btn-outline-primary:hover {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
}

.date-navigation .btn-primary {
    background: var(--primary-gradient);
    border: none;
    border-radius: 25px;
    padding: 8px 18px;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

.date-navigation .form-control-sm {
    border-radius: 25px;
    border: 2px solid rgba(67, 97, 238, 0.2);
    padding: 8px 14px;
}

/* Attendance Card */
.attendance-card {
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    margin-bottom: 25px;
    overflow: hidden;
    border: 2px solid rgba(67, 97, 238, 0.1);
    background: white;
}

.attendance-card-header {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    padding: 18px 25px;
    border-bottom: 2px solid rgba(67, 97, 238, 0.1);
}

.attendance-card-header h5 {
    color: var(--primary-color);
    font-weight: 700;
}

.attendance-card-body {
    padding: 25px;
}

/* Attendance Badge */
.attendance-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 16px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    gap: 6px;
}

.badge-present {
    background: var(--success-gradient);
    color: white;
    box-shadow: 0 3px 10px rgba(16, 185, 129, 0.3);
}

.badge-absent {
    background: var(--danger-gradient);
    color: white;
    box-shadow: 0 3px 10px rgba(239, 68, 68, 0.3);
}

.badge-leave {
    background: var(--warning-gradient);
    color: white;
    box-shadow: 0 3px 10px rgba(245, 158, 11, 0.3);
}

.badge-pending {
    background: linear-gradient(135deg, #64748b, #475569);
    color: white;
    box-shadow: 0 3px 10px rgba(100, 116, 139, 0.3);
}

/* Time Slot Header */
.time-slot-header {
    background: linear-gradient(135deg, #f8fafc, #eef2ff);
    padding: 14px 20px;
    border-radius: 12px;
    margin-bottom: 15px;
    border-left: 5px solid var(--primary-color);
}

.time-slot-header h6 {
    color: var(--primary-color);
    font-weight: 700;
}

/* Attendance Item */
.attendance-item {
    border: 2px solid rgba(67, 97, 238, 0.1);
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 15px;
    background: white;
    transition: all 0.3s ease;
}

.attendance-item:hover {
    box-shadow: 0 5px 20px rgba(67, 97, 238, 0.1);
    border-color: rgba(67, 97, 238, 0.3);
}

.attendance-item.present {
    border-left: 5px solid var(--success-color);
}

.attendance-item.absent {
    border-left: 5px solid #ef4444;
}

.attendance-item.leave {
    border-left: 5px solid #f59e0b;
}

/* Subject Icon */
.subject-icon {
    width: 45px;
    height: 45px;
    border-radius: 14px;
    background: var(--primary-gradient);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
}

.subject-icon.sub-subject {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
}

/* Calendar Day */
.calendar-day {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 3px;
    font-size: 0.85rem;
    font-weight: 600;
    transition: all 0.2s ease;
}

.calendar-day:hover {
    transform: scale(1.15);
}

.calendar-day.present {
    background: var(--success-gradient);
    color: white;
}

.calendar-day.absent {
    background: var(--danger-gradient);
    color: white;
}

.calendar-day.leave {
    background: var(--warning-gradient);
    color: white;
}

.calendar-day.no-class {
    background: #f1f5f9;
    color: #94a3b8;
}

.calendar-day.today {
    border: 3px solid var(--primary-color);
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
}

/* Recent Day */
.recent-day {
    text-align: center;
    padding: 12px;
    border-radius: 12px;
    background: white;
    box-shadow: 0 3px 10px rgba(0,0,0,0.06);
    border: 1px solid rgba(67, 97, 238, 0.08);
    transition: all 0.2s ease;
}

.recent-day:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(67, 97, 238, 0.1);
}

/* Modal */
.modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.modal-header {
    background: var(--primary-gradient);
    color: white;
    border: none;
    padding: 18px 24px;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.modal-title {
    font-weight: 700;
}

.modal-footer {
    border-top: 1px solid rgba(67, 97, 238, 0.1);
}

/* Button Styles */
.btn-primary {
    background: var(--primary-gradient);
    border: none;
    border-radius: 25px;
    padding: 10px 22px;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
}

.btn-outline-primary {
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
    border-radius: 20px;
    font-weight: 600;
}

.btn-outline-primary:hover {
    background: var(--primary-gradient);
    border-color: transparent;
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 50px 20px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 15px;
    border: 2px dashed rgba(67, 97, 238, 0.2);
}

.no-data-icon {
    font-size: 50px;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 20px;
    opacity: 0.5;
}

/* Loading Spinner */
.loading-spinner {
    width: 50px;
    height: 50px;
    border: 3px solid rgba(67, 97, 238, 0.1);
    border-top-color: var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Text Styles */
.text-muted {
    color: #fff!important;
}

.text-primary {
    color: var(--primary-color) !important;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 8px;
}

::-webkit-scrollbar-thumb {
    background: var(--primary-gradient);
    border-radius: 8px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--secondary-color);
}

/* Table Styles */
.table thead th {
    background: var(--primary-gradient);
    color: white;
    border: none;
    font-weight: 700;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 14px;
}

.table tbody tr {
    border-bottom: 1px solid rgba(67, 97, 238, 0.08);
    transition: background 0.3s ease;
}

.table tbody tr:hover {
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.04), rgba(58, 12, 163, 0.04));
}

.table tbody td {
    padding: 14px;
    vertical-align: middle;
}

/* Responsive */
@media (max-width: 768px) {
    .stats-number {
        font-size: 1.5rem;
    }
    
    .attendance-item {
        padding: 15px;
    }
    
    .page-header {
        padding: 20px;
    }
    
    .date-navigation .d-flex {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1">
                    <i class="fas fa-calendar-check me-2"></i>
                    Attendance
                </h2>
                <p class="text-muted mb-0">Track your attendance across all subjects</p>
            </div>
            <div>
                <button class="btn btn-primary" onclick="printAttendance()">
                    <i class="fas fa-print me-1"></i> Print Report
                </button>
            </div>
        </div>
    </div>

    <!-- Student Info -->
    <div class="student-info-card">
        <div class="row align-items-center">
            <div class="col-md-4  d-flex" style="gap:8px;">
                <div class="teacher-avatar text-center" style="font-size: 20px;align-content:center;color:#fff;padding:10px;border-radius: 15px;">
                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                </div>
                <div class="">
                        <h5 class="mb-0">{{ $student->first_name }} {{ $student->last_name }}</h5>
                        
                        <h6 class="mb-0" style="color: var(--primary-color);">{{ $student->registration_number }}</h6>
                    </div>
            </div>
            <div class="col-md-8">
                <div class="row text-end">
                    
                    
                    <div>
                        <p class="mb-1 text-muted"><strong>Date:</strong></p>
                        <h6 class="mb-0">{{ $date->format('l, F j, Y') }}</h6>
                    
                        <!-- <span id="overallStatus" class="fw-bold">Loading...</span>
                        <button class="btn btn-sm" onclick="refreshAttendance()">
                            <i class="fas fa-sync-alt"></i>
                        </button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card today">
                <div class="stats-number" id="todayTotal">0</div>
                <div class="stats-label">Today's Classes</div>
                <div class="mt-2" style="position: relative; z-index: 1;">
                    <small>Present: <span id="todayPresent">0</span> | 
                    Absent: <span id="todayAbsent">0</span></small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card weekly">
                <div class="stats-number" id="weeklyPercentage">0%</div>
                <div class="stats-label">Weekly Attendance</div>
                <div class="mt-2" style="position: relative; z-index: 1;">
                    <small>This Week</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card monthly">
                <div class="stats-number" id="monthlyPercentage">0%</div>
                <div class="stats-label">Monthly Attendance</div>
                <div class="mt-2" style="position: relative; z-index: 1;">
                    <small>{{ $date->format('F Y') }}</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card streak">
                <div class="stats-number" id="attendanceStreak">0</div>
                <div class="stats-label">Day Streak</div>
                <div class="mt-2" style="position: relative; z-index: 1;">
                    <small>Consecutive Present Days</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Navigation -->
    <div class="date-navigation">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Select Date</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary" onclick="previousDay()">
                    <i class="fas fa-chevron-left me-1"></i> Previous
                </button>
                <input type="date" id="datePicker" class="form-control form-control-sm" 
                       value="{{ $selectedDate }}" style="width: 150px;">
                <button class="btn btn-sm btn-outline-primary" onclick="nextDay()">
                    Next <i class="fas fa-chevron-right ms-1"></i>
                </button>
                <button class="btn btn-sm btn-primary" onclick="goToToday()">
                    <i class="fas fa-calendar-day me-1"></i> Today
                </button>
            </div>
        </div>
    </div>

    <!-- Recent Attendance -->
    <div class="attendance-card">
        <div class="attendance-card-header">
            <h5 class="mb-0">
                <i class="fas fa-chart-line me-2"></i>
                Recent Attendance (Last 7 Days)
            </h5>
        </div>
        <div class="attendance-card-body">
            <div id="recentAttendanceChart">
                <div class="d-flex justify-content-between flex-wrap" id="recentDaysList">
                    <!-- Recent days will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Attendance -->
    <div class="attendance-card">
        <div class="attendance-card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-day me-2"></i>
                    Attendance for <span id="selectedDateText">{{ $date->format('l, F j, Y') }}</span>
                </h5>
                <div class="text-muted">
                    <span id="totalClassesTodayText">0</span> Classes Today
                </div>
            </div>
        </div>
        <div class="attendance-card-body">
            <div id="attendanceLoading" class="text-center py-5">
                <div class="loading-spinner mx-auto mb-3"></div>
                <p class="text-muted">Loading attendance...</p>
            </div>
            
            <div id="attendanceList" style="display: none;"></div>
            
            <div id="noAttendanceMessage" class="empty-state" style="display: none;">
                <div class="no-data-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h4 class="mb-3">No Attendance Records</h4>
                <p class="text-muted mb-4">No attendance has been marked for you today.</p>
            </div>
        </div>
    </div>

    <!-- Monthly Calendar -->
    <div class="attendance-card">
        <div class="attendance-card-header">
            <h5 class="mb-0">
                <i class="fas fa-calendar me-2"></i>
                Monthly Attendance Calendar - {{ $date->format('F Y') }}
            </h5>
        </div>
        <div class="attendance-card-body">
            <div id="attendanceCalendar"></div>
        </div>
    </div>
</div>

<!-- Modal for Subject Attendance Details -->
<div class="modal fade" id="subjectAttendanceModal" tabindex="-1" aria-labelledby="subjectAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="subjectAttendanceModalLabel">
                    <i class="fas fa-history me-2"></i>
                    Attendance History
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="subjectAttendanceDetails"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    loadAttendanceData('{{ $selectedDate }}');
    loadStatistics();
    loadRecentAttendance();
    loadCalendar();
    
    $('#datePicker').change(function() {
        const selectedDate = $(this).val();
        loadAttendanceData(selectedDate);
    });
});

function loadAttendanceData(date) {
    $('#attendanceLoading').show();
    $('#attendanceList').hide();
    $('#noAttendanceMessage').hide();
    
    $.ajax({
        url: '{{ route("student.attendance.by-date") }}',
        method: 'GET',
        data: { date: date },
        success: function(response) {
            if (response.success) {
                displayAttendance(response.attendance);
                $('#selectedDateText').text(response.date);
            }
        },
        error: function(xhr) {
            console.error('Error loading attendance:', xhr);
            showError('Failed to load attendance data.');
        },
        complete: function() {
            $('#attendanceLoading').hide();
        }
    });
}

function loadStatistics() {
    $.ajax({
        url: '{{ route("student.attendance.statistics") }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                updateStatistics(response.statistics);
            }
        }
    });
}

function updateStatistics(stats) {
    $('#todayTotal').text(stats.today.total);
    $('#todayPresent').text(stats.today.present);
    $('#todayAbsent').text(stats.today.absent);
    $('#weeklyPercentage').text(stats.weekly.percentage + '%');
    $('#monthlyPercentage').text(stats.monthly.percentage + '%');
    $('#attendanceStreak').text(stats.streak);
    
    let overallStatus = 'No Data';
    if (stats.today.total > 0) {
        if (stats.today.present === stats.today.total) overallStatus = 'Perfect';
        else if (stats.today.present === 0) overallStatus = 'All Absent';
        else overallStatus = 'Partially Present';
    }
    
    $('#overallStatus').text(overallStatus);
}

function loadRecentAttendance() {
    const recentAttendanceData = {!! json_encode($recentAttendance) !!};
    
    if (recentAttendanceData && recentAttendanceData.length > 0) {
        displayRecentAttendance(recentAttendanceData);
    } else {
        $('#recentDaysList').html(`
            <div class="col-12 text-center py-4">
                <i class="fas fa-calendar-times fa-2x text-muted mb-3"></i>
                <p class="text-muted">No recent attendance data available.</p>
            </div>
        `);
    }
}

function displayRecentAttendance(days) {
    let html = '<div class="d-flex justify-content-between flex-wrap">';
    
    days.forEach(day => {
        let statusClass = '';
        if (day.percentage >= 75) statusClass = 'present';
        else if (day.percentage >= 50) statusClass = 'leave';
        else if (day.total > 0) statusClass = 'absent';
        else statusClass = 'no-class';
        
        html += `
            <div class="recent-day m-1">
                <div class="day-name">${day.formatted_date}</div>
                <div class="calendar-day ${statusClass} mx-auto my-1">
                    ${day.total > 0 ? day.present : '-'}
                </div>
                <div class="day-percentage">${day.percentage}%</div>
            </div>
        `;
    });
    
    html += '</div>';
    $('#recentDaysList').html(html);
}

function displayAttendance(attendance) {
    let html = '';
    let totalClasses = 0;
    
    if (Object.keys(attendance).length === 0) {
        $('#noAttendanceMessage').show();
        $('#totalClassesTodayText').text('0');
        return;
    }
    
    for (const [timeSlot, classes] of Object.entries(attendance)) {
        totalClasses += classes.length;
        
        html += `
            <div class="time-slot-header">
                <h6 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    ${timeSlot}
                    <span>(${classes.length} class${classes.length > 1 ? 'es' : ''})</span>
                </h6>
            </div>
        `;
        
        classes.forEach(classItem => {
            let statusClass = '';
            let statusBadge = '';
            
            switch(classItem.status) {
                case 'Present':
                    statusClass = 'present';
                    statusBadge = '<span class="attendance-badge badge-present"><i class="fas fa-check-circle"></i> Present</span>';
                    break;
                case 'Absent':
                    statusClass = 'absent';
                    statusBadge = '<span class="attendance-badge badge-absent"><i class="fas fa-times-circle"></i> Absent</span>';
                    break;
                case 'Leave':
                    statusClass = 'leave';
                    statusBadge = '<span class="attendance-badge badge-leave"><i class="fas fa-umbrella-beach"></i> Leave</span>';
                    break;
                default:
                    statusClass = 'pending';
                    statusBadge = '<span class="attendance-badge badge-pending"><i class="fas fa-question-circle"></i> Pending</span>';
            }
            
            html += `
                <div class="attendance-item ${statusClass}">
                    <div class="row align-items-center">
                        <div class="col-md-1">
                            <div class="subject-icon ${classItem.subject_type === 'sub_subject' ? 'sub-subject' : ''}">
                                <i class="${classItem.subject_type === 'sub_subject' ? 'fas fa-layer-group' : 'fas fa-book'}"></i>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <h6 class="mb-1">${classItem.display_name}</h6>
                            <div class="text-muted small">
                                <i class="fas fa-graduation-cap me-1"></i>
                                ${classItem.course_type} - ${classItem.sub_type}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <div class="teacher-avatar me-2" style="width: 35px; height: 35px; font-size: 12px;">
                                    ${classItem.teacher_name ? classItem.teacher_name.substring(0, 2).toUpperCase() : 'NA'}
                                </div>
                                <div>
                                    <div class="small text-muted">Teacher</div>
                                    <div class="fw-bold">${classItem.teacher_name || 'Not specified'}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-end">
                            ${statusBadge}
                            <button class="btn btn-sm btn-outline-primary mt-2" 
                                    onclick="viewSubjectHistory('${classItem.subject_id}', '${classItem.sub_subject_id || ''}', '${classItem.subject_type}', '${classItem.display_name}')">
                                <i class="fas fa-history me-1"></i> History
                            </button>
                        </div>
                    </div>
                    ${classItem.remarks ? `
                        <div class="mt-3">
                            <strong>Remarks:</strong> ${classItem.remarks}
                        </div>
                    ` : ''}
                </div>
            `;
        });
    }
    
    $('#attendanceList').html(html).show();
    $('#totalClassesTodayText').text(totalClasses);
    $('#noAttendanceMessage').hide();
}

function loadCalendar() {
    const monthlySummary = {!! json_encode($monthlySummary) !!};
    
    if (monthlySummary && monthlySummary.length > 0) {
        displayCalendar(monthlySummary);
    } else {
        $('#attendanceCalendar').html(`
            <div class="empty-state py-4">
                <i class="fas fa-calendar-alt fa-2x text-muted mb-3"></i>
                <p class="text-muted">No attendance data for this month.</p>
            </div>
        `);
    }
}

function displayCalendar(monthlySummary) {
    const daysInMonth = {{ $date->daysInMonth }};
    let calendarHtml = '<div class="d-flex flex-wrap">';
    
    const attendanceMap = {};
    monthlySummary.forEach(day => {
        const dateObj = new Date(day.date);
        const dayNumber = dateObj.getDate();
        attendanceMap[dayNumber] = {
            total: day.total_classes,
            present: day.present,
            percentage: day.total_classes > 0 ? Math.round((day.present / day.total_classes) * 100) : 0
        };
    });
    
    for (let day = 1; day <= daysInMonth; day++) {
        const attendance = attendanceMap[day];
        let statusClass = 'no-class';
        let tooltip = `Day ${day}: No classes`;
        
        if (attendance && attendance.total > 0) {
            if (attendance.percentage >= 75) statusClass = 'present';
            else if (attendance.percentage >= 50) statusClass = 'leave';
            else statusClass = 'absent';
            tooltip = `Day ${day}: ${attendance.present}/${attendance.total} (${attendance.percentage}%)`;
        }
        
        const isToday = day === {{ $date->day }};
        
        calendarHtml += `
            <div class="calendar-day ${statusClass} ${isToday ? 'today' : ''}" 
                 title="${tooltip}" 
                 data-bs-toggle="tooltip">
                ${day}
            </div>
        `;
    }
    
    calendarHtml += '</div>';
    $('#attendanceCalendar').html(calendarHtml);
    $('[data-bs-toggle="tooltip"]').tooltip();
}

function viewSubjectHistory(subjectId, subSubjectId, subjectType, subjectName) {
    $('#subjectAttendanceModalLabel').html('<i class="fas fa-spinner fa-spin me-2"></i>Loading...');
    $('#subjectAttendanceDetails').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
    
    $.ajax({
        url: '{{ route("student.attendance.by-subject") }}',
        method: 'GET',
        data: {
            subject_id: subjectId,
            sub_subject_id: subSubjectId || null,
            subject_type: subjectType
        },
        success: function(response) {
            if (response.success) {
                displaySubjectHistory(response.subject_name, response.attendance_history);
            } else {
                $('#subjectAttendanceDetails').html(`
                    <div class="alert alert-danger">
                        Error: ${response.message || 'Failed to load attendance history.'}
                    </div>
                `);
            }
        },
        error: function(xhr) {
            console.error('AJAX Error:', xhr);
            $('#subjectAttendanceDetails').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error loading attendance history. Please try again.
                </div>
            `);
        }
    });
    
    $('#subjectAttendanceModal').modal('show');
}

function displaySubjectHistory(subjectName, attendanceHistory) {
    if (!attendanceHistory || !attendanceHistory.history || !attendanceHistory.summary) {
        $('#subjectAttendanceDetails').html(`
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                No attendance data available for this subject.
            </div>
        `);
        return;
    }
    
    const history = attendanceHistory.history;
    const summary = attendanceHistory.summary;
    
    let html = `
        <div class="mb-4">
            <h4>${subjectName}</h4>
            <p class="text-muted">Attendance History</p>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Classes</h6>
                        <h3 class="text-primary">${summary.total || 0}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h6>Present</h6>
                        <h3>${summary.present || 0}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h6>Absent</h6>
                        <h3>${summary.absent || 0}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h6>Attendance %</h6>
                        <h3>${summary.percentage || 0}%</h3>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    if (history.length > 0) {
        html += `
            <h5 class="mb-3">Attendance Records</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Teacher</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        history.forEach(record => {
            let statusClass = '';
            let statusText = record.status || 'N/A';
            
            switch(record.status) {
                case 'Present': statusClass = 'badge-present'; break;
                case 'Absent': statusClass = 'badge-absent'; break;
                case 'Leave': statusClass = 'badge-leave'; break;
                default: statusClass = 'badge-pending';
            }
            
            html += `
                <tr>
                    <td>${record.formatted_date || record.date || 'N/A'}</td>
                    <td>${record.day_name || 'N/A'}</td>
                    <td>${record.formatted_start_time || record.start_time || 'N/A'} - ${record.formatted_end_time || record.end_time || 'N/A'}</td>
                    <td><span class="attendance-badge ${statusClass}">${statusText}</span></td>
                    <td>${record.teacher_name || 'N/A'}</td>
                    <td>${record.remarks || '-'}</td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
    } else {
        html += `
            <div class="empty-state">
                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Attendance History</h5>
                <p class="text-muted">No attendance records found for this subject.</p>
            </div>
        `;
    }
    
    $('#subjectAttendanceModalLabel').html(`<i class="fas fa-history me-2"></i>${subjectName} - Attendance History`);
    $('#subjectAttendanceDetails').html(html);
}

function previousDay() {
    const currentDate = $('#datePicker').val();
    const previousDate = new Date(currentDate);
    previousDate.setDate(previousDate.getDate() - 1);
    const formattedDate = previousDate.toISOString().split('T')[0];
    $('#datePicker').val(formattedDate);
    loadAttendanceData(formattedDate);
}

function nextDay() {
    const currentDate = $('#datePicker').val();
    const nextDate = new Date(currentDate);
    nextDate.setDate(nextDate.getDate() + 1);
    const formattedDate = nextDate.toISOString().split('T')[0];
    $('#datePicker').val(formattedDate);
    loadAttendanceData(formattedDate);
}

function goToToday() {
    const today = new Date().toISOString().split('T')[0];
    $('#datePicker').val(today);
    loadAttendanceData(today);
}

function refreshAttendance() {
    const currentDate = $('#datePicker').val();
    loadAttendanceData(currentDate);
    loadStatistics();
}

function printAttendance() {
    window.print();
}

function showError(message) {
    $('#attendanceList').html(`
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            ${message}
        </div>
    `).show();
}
</script>
@endsection