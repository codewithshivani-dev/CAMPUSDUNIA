@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

<style>
.card {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    border: none;
}
.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px 12px 0 0 !important;
    padding: 20px;
}
.card-header h2 {
    font-size: 1.5rem;
    font-weight: 600;
}
.filter-container {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 15px;
    align-items: flex-end;
}
.filter-group {
    flex: 1;
    min-width: 180px;
}
.filter-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}
.filter-group select,
.filter-group input {
    width: 100%;
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
    background: #fff;
    font-size: 14px;
}
.filter-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    padding-top: 15px;
    border-top: 1px solid #eee;
    align-items: center;
}
.event-type-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}
.event-type-buttons .btn {
    font-size: 0.85rem;
    padding: 8px 16px;
    border-radius: 20px;
    transition: all 0.3s ease;
}
.event-type-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.event-type-buttons .btn i {
    margin-right: 6px;
}
.legend-container {
    background: white;
    border-radius: 12px;
    padding: 12px 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.legend-items {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
}
.legend-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
}
.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 4px;
}
.event-count-badge {
    background: #e9ecef;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.fc-event-holiday {
    background-color: #0d0666 !important;
    border-color: #0d0666 !important;
    color: white;
    cursor: pointer;
}

.fc-event-holiday:hover {
    color: black;
}

.fc-event-exam {
    background-color: #ef4444 !important;
    border-color: #ef4444 !important;
}

.fc-event-academic {
    background-color: #3b82f6 !important;
    border-color: #3b82f6 !important;
}

.fc-event-other {
    background-color: #78c0ff !important;
    border: none;
    border-color: #6c757d !important;
    cursor: pointer;
}

.fc-h-event .fc-event-title{
    font-size: 15px;
}

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.9);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
.loading-spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
#calendar {
    background: white;
    border-radius: 12px;
    padding: 20px;
    min-height: 600px;
}

/* Modal Styles */
.events-list-modal .modal-dialog {
    max-width: 90%;
    width: 1200px;
}
.events-list-container {
    max-height: 500px;
    overflow-y: auto;
}
.event-item {
    padding: 15px;
    border-left: 4px solid;
    margin-bottom: 10px;
    background: #f8fafc;
    border-radius: 12px;
    transition: all 0.3s ease;
    cursor: pointer;
}
.event-item:hover {
    background: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateX(5px);
}
.event-item.holiday { border-left-color: #0d0666; }
.event-item.academic { border-left-color: #3b82f6; }
.event-item.exam { border-left-color: #ef4444; }
.event-item.other { border-left-color: #6c757d; }

.events-table {
    width: 100%;
    border-collapse: collapse;
}
.events-table th {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    position: sticky;
    top: 0;
    z-index: 10;
}
.events-table td {
    padding: 10px 12px;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: middle;
}
.events-table tr:hover {
    background: #f1f5f9;
}

.filter-group-inline {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 15px;
    padding: 15px;
    background: #f8fafc;
    border-radius: 12px;
}
.filter-group-inline .filter-item {
    display: flex;
    align-items: center;
    gap: 8px;
}
.filter-group-inline label {
    margin: 0;
    font-weight: 600;
    font-size: 0.85rem;
}
.filter-group-inline select,
.filter-group-inline input {
    padding: 6px 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 0.85rem;
}
.search-input {
    padding: 6px 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
    width: 250px;
}
.modal-header-custom {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}
.btn-close-white {
    filter: brightness(0) invert(1);
}
@media (max-width: 768px) {
    .legend-items { gap: 8px; }
    .filter-group { min-width: 100%; }
    .filter-group-inline { flex-direction: column; align-items: stretch; }
    .filter-group-inline .filter-item { justify-content: space-between; }
}
</style>

<div class="loading-overlay">
    <div class="loading-spinner"></div>
</div>

<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="mb-0 text-white">
                <i class="fas fa-calendar-alt me-2"></i> Calendar
            </h2>
            <p class="text-white-50 mt-2 mb-0">
                Welcome, {{ $student->first_name }} {{ $student->last_name }}! Here are all events relevant to you.
            </p>
        </div>
    </div>

    <!-- Event Type Buttons -->
    <div class="legend-container">
        <div class="legend-items">
            <div class="legend-item"><span class="legend-color" style="background:#0d0666;"></span><span>Public Holidays</span></div>
            <div class="legend-item"><span class="legend-color" style="background:#ef4444;"></span><span>Exams</span></div>
            <div class="legend-item"><span class="legend-color" style="background:#3b82f6;"></span><span>Academic Events</span></div>
            <div class="legend-item"><span class="legend-color" style="background:#6c757d;"></span><span>Other Events</span></div>
            
            <div class="ms-auto">
                <span class="event-count-badge">
                    <i class="fas fa-calendar-alt"></i>
                    <span id="eventCount">0</span> Events
                </span>
            </div>
        </div>

        <!-- Event Type Buttons -->
        <div class="event-type-buttons">
            <button type="button" class="btn btn-outline-primary btn-event-type" id="holidayEventsBtn">
                <i class="fas fa-calendar-times"></i> Holidays
            </button>
            <button type="button" class="btn btn-outline-info btn-event-type" id="academicEventsBtn">
                <i class="fas fa-graduation-cap"></i> Academic
            </button>
            <button type="button" class="btn btn-outline-danger btn-event-type" id="examEventsBtn">
                <i class="fas fa-file-alt"></i> Exams
            </button>
            <button type="button" class="btn btn-outline-secondary btn-event-type" id="otherEventsBtn">
                <i class="fas fa-ellipsis-h"></i> Other
            </button>
        </div>
    </div>

    <!-- Quick Info Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-6">
            <div class="card bg-primary text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><small>Department</small><h5 class="mb-0">{{ $departmentName ?? 'N/A' }}</h5></div>
                        <i class="fas fa-building fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-info text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><small>Course</small><h5 class="mb-0">{{ $courseName ?? 'N/A' }}</h5></div>
                        <i class="fas fa-book fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-success text-white">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><small>Section</small><h5 class="mb-0">{{ $sectionName ?? 'N/A' }}</h5></div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card bg-warning text-dark">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div><small>Current Year</small><h5 class="mb-0" id="currentYearDisplay">{{ $currentYear }}</h5></div>
                        <i class="fas fa-calendar fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar -->
    <div class="card">
        <div class="card-body p-0 p-md-3">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- Holiday Events Modal -->
<div class="modal fade events-list-modal" id="holidayEventsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title"><i class="fas fa-calendar-times me-2"></i> Public Holidays</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="filter-group-inline">
                    <div class="filter-item">
                        <label>Year:</label>
                        <select id="holidayYearFilter" class="form-select">
                            <option value="{{ $currentYear - 1 }}">{{ $currentYear - 1 }}</option>
                            <option value="{{ $currentYear }}" selected>{{ $currentYear }}</option>
                            <option value="{{ $currentYear + 1 }}">{{ $currentYear + 1 }}</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Month:</label>
                        <select id="holidayMonthFilter" class="form-select">
                            <option value="">All Months</option>
                            @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Search:</label>
                        <input type="text" id="holidaySearchInput" class="search-input" placeholder="Search holidays...">
                    </div>
                    <div class="filter-item">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="resetHolidayFilters">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    </div>
                </div>
                <div class="events-list-container">
                    <table class="events-table" id="holidayEventsTable">
                        <thead><tr><th>#</th><th>Holiday Title</th><th>Start Date</th><th>End Date</th><th>Description</th></tr></thead>
                        <tbody id="holidayEventsTableBody"><tr><td colspan="5" class="text-center">Loading holidays...</td></tr></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="refreshHolidayEventsBtn"><i class="fas fa-refresh me-1"></i> Refresh</button>
            </div>
        </div>
    </div>
</div>

<!-- Academic Events Modal -->
<div class="modal fade events-list-modal" id="academicEventsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white;">
                <h5 class="modal-title"><i class="fas fa-graduation-cap me-2"></i> Academic Events</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="filter-group-inline">
                    <div class="filter-item"><label>Year:</label><select id="academicYearFilter" class="form-select"><option value="{{ $currentYear - 1 }}">{{ $currentYear - 1 }}</option><option value="{{ $currentYear }}" selected>{{ $currentYear }}</option><option value="{{ $currentYear + 1 }}">{{ $currentYear + 1 }}</option></select></div>
                    <div class="filter-item"><label>Month:</label><select id="academicMonthFilter" class="form-select"><option value="">All Months</option>@for($m=1;$m<=12;$m++)<option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>@endfor</select></div>
                    <div class="filter-item"><label>Search:</label><input type="text" id="academicSearchInput" class="search-input" placeholder="Search academic events..."></div>
                    <div class="filter-item"><button type="button" class="btn btn-outline-secondary btn-sm" id="resetAcademicFilters"><i class="fas fa-undo me-1"></i> Reset</button></div>
                </div>
                <div class="events-list-container">
                    <table class="events-table" id="academicEventsTable">
                        <thead><tr><th>#</th><th>Event Title</th><th>Start Date</th><th>End Date</th><th>Description</th></tr></thead>
                        <tbody id="academicEventsTableBody"><tr><td colspan="5" class="text-center">Loading academic events...</td></tr></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="refreshAcademicEventsBtn"><i class="fas fa-refresh me-1"></i> Refresh</button>
            </div>
        </div>
    </div>
</div>

<!-- Exam Events Modal -->
<div class="modal fade events-list-modal" id="examEventsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white;">
                <h5 class="modal-title"><i class="fas fa-file-alt me-2"></i> Exams</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="filter-group-inline">
                    <div class="filter-item"><label>Year:</label><select id="examYearFilter" class="form-select"><option value="{{ $currentYear - 1 }}">{{ $currentYear - 1 }}</option><option value="{{ $currentYear }}" selected>{{ $currentYear }}</option><option value="{{ $currentYear + 1 }}">{{ $currentYear + 1 }}</option></select></div>
                    <div class="filter-item"><label>Month:</label><select id="examMonthFilter" class="form-select"><option value="">All Months</option>@for($m=1;$m<=12;$m++)<option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>@endfor</select></div>
                    <div class="filter-item"><label>Search:</label><input type="text" id="examSearchInput" class="search-input" placeholder="Search exams..."></div>
                    <div class="filter-item"><button type="button" class="btn btn-outline-secondary btn-sm" id="resetExamFilters"><i class="fas fa-undo me-1"></i> Reset</button></div>
                </div>
                <div class="events-list-container">
                    <table class="events-table" id="examEventsTable">
                        <thead><tr><th>#</th><th>Exam Title</th><th>Exam Date</th><th>Subject</th><th>Time</th><th>Classroom</th></tr></thead>
                        <tbody id="examEventsTableBody"><tr><td colspan="6" class="text-center">Loading exams...</td></tr></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="refreshExamEventsBtn"><i class="fas fa-refresh me-1"></i> Refresh</button>
            </div>
        </div>
    </div>
</div>

<!-- Other Events Modal -->
<div class="modal fade events-list-modal" id="otherEventsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #6c757d, #4b5563); color: white;">
                <h5 class="modal-title"><i class="fas fa-ellipsis-h me-2"></i> Other Events</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="filter-group-inline">
                    <div class="filter-item"><label>Year:</label><select id="otherYearFilter" class="form-select"><option value="{{ $currentYear - 1 }}">{{ $currentYear - 1 }}</option><option value="{{ $currentYear }}" selected>{{ $currentYear }}</option><option value="{{ $currentYear + 1 }}">{{ $currentYear + 1 }}</option></select></div>
                    <div class="filter-item"><label>Month:</label><select id="otherMonthFilter" class="form-select"><option value="">All Months</option>@for($m=1;$m<=12;$m++)<option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>@endfor</select></div>
                    <div class="filter-item"><label>Search:</label><input type="text" id="otherSearchInput" class="search-input" placeholder="Search other events..."></div>
                    <div class="filter-item"><button type="button" class="btn btn-outline-secondary btn-sm" id="resetOtherFilters"><i class="fas fa-undo me-1"></i> Reset</button></div>
                </div>
                <div class="events-list-container">
                    <table class="events-table" id="otherEventsTable">
                        <thead><tr><th>#</th><th>Event Title</th><th>Start Date</th><th>End Date</th><th>Department</th><th>Description</th></tr></thead>
                        <tbody id="otherEventsTableBody"><tr><td colspan="6" class="text-center">Loading other events...</td></tr></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="refreshOtherEventsBtn"><i class="fas fa-refresh me-1"></i> Refresh</button>
            </div>
        </div>
    </div>
</div>

<!-- Event Details Modal -->
<div class="modal fade" id="viewEventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="eventModalBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const loadingOverlay = document.querySelector('.loading-overlay');
    const eventCountSpan = document.getElementById('eventCount');
    const currentYear = new Date().getFullYear();
    
    let calendar = null;
    let allEventsData = {
        holidays: [],
        academicEvents: [],
        exams: [],
        other: []
    };
    
    function showLoading() { if (loadingOverlay) loadingOverlay.style.display = 'flex'; }
    function hideLoading() { if (loadingOverlay) loadingOverlay.style.display = 'none'; }
    
    function formatDate(dateStr) {
        if (!dateStr) return 'N/A';
        return new Date(dateStr).toLocaleDateString('en-US', {
            year: 'numeric', month: 'short', day: 'numeric'
        });
    }
    
    function formatTime(dateStr) {
        if (!dateStr) return 'N/A';
        return new Date(dateStr).toLocaleTimeString('en-US', {
            hour: '2-digit', minute: '2-digit'
        });
    }
    
    function showEventDetailsInModal(event) {
        const props = event.extendedProps || {};
        const category = props.category;
        let html = '<div class="event-details">';
        
        if (category === 'holiday') {
            html += `<div style="background: linear-gradient(135deg, #0d0666, #1e1b4b); color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;"><h5 style="margin: 0;">🎉 ${event.title}</h5></div>`;
            html += `<div style="padding: 10px;"><p><strong>📅 Date:</strong> ${formatDate(event.start)}${event.end && event.end !== event.start ? ' - ' + formatDate(event.end) : ''}</p>`;
            html += `<p><strong>📝 Description:</strong> ${props.description || 'No description'}</p></div>`;
        } 
        else if (category === 'exam') {
            html += `<div style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;"><h5 style="margin: 0;">📝 ${props.exam_name || event.title}</h5></div>`;
            html += `<div style="padding: 10px;">`;
            html += `<p><strong>📚 Subject:</strong> ${props.subject_name || 'N/A'}</p>`;
            html += `<p><strong>🏫 Course:</strong> ${props.course_name || 'N/A'}</p>`;
            html += `<p><strong>📅 Exam Date:</strong> ${formatDate(event.start)}</p>`;
            html += `<p><strong>⏰ Exam Time:</strong> ${formatTime(event.start)} - ${formatTime(event.end)}</p>`;
            html += `<p><strong>🏠 Classroom:</strong> ${props.classroom_name || 'N/A'}</p>`;
            html += `<p><strong>📊 Total Marks:</strong> ${props.total_marks || 'N/A'}</p>`;
            html += `<p><strong>✓ Passing Marks:</strong> ${props.passing_marks || 'N/A'}</p></div>`;
        } 
        else if (category === 'academic') {
            html += `<div style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;"><h5 style="margin: 0;">🎓 ${event.title}</h5></div>`;
            html += `<div style="padding: 10px;"><p><strong>📅 Date:</strong> ${formatDate(event.start)}${event.end && event.end !== event.start ? ' - ' + formatDate(event.end) : ''}</p>`;
            html += `<p><strong>📝 Description:</strong> ${props.description || 'No description'}</p></div>`;
        } 
        else {
            html += `<div style="background: linear-gradient(135deg, #6c757d, #4b5563); color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;"><h5 style="margin: 0;">📌 ${event.title}</h5></div>`;
            html += `<div style="padding: 10px;"><p><strong>📅 Date:</strong> ${formatDate(event.start)}${event.end && event.end !== event.start ? ' - ' + formatDate(event.end) : ''}</p>`;
            html += `<p><strong>📝 Description:</strong> ${props.description || 'No description'}</p></div>`;
        }
        
        html += '</div>';
        document.getElementById('eventModalBody').innerHTML = html;
        new bootstrap.Modal(document.getElementById('viewEventModal')).show();
    }
    
    async function fetchAllEventsForYear(year) {
        showLoading();
        try {
            const response = await fetch('{{ route("student.calendar.events") }}?year=' + year);
            const data = await response.json();
            const events = data.events || [];
            
            allEventsData = {
                holidays: events.filter(e => e.extendedProps?.category === 'holiday'),
                academicEvents: events.filter(e => e.extendedProps?.category === 'academic'),
                exams: events.filter(e => e.extendedProps?.category === 'exam'),
                other: events.filter(e => !['holiday', 'academic', 'exam'].includes(e.extendedProps?.category))
            };
            return allEventsData;
        } catch (error) {
            console.error('Error fetching events:', error);
            return allEventsData;
        } finally {
            hideLoading();
        }
    }
    
    // Render functions
    function renderHolidayEventsTable() {
        const yearFilter = $('#holidayYearFilter').val();
        const monthFilter = $('#holidayMonthFilter').val();
        const searchFilter = $('#holidaySearchInput').val().toLowerCase();
        let events = [...allEventsData.holidays];
        if (yearFilter) events = events.filter(e => new Date(e.start).getFullYear() == yearFilter);
        if (monthFilter) events = events.filter(e => (new Date(e.start).getMonth() + 1) == monthFilter);
        if (searchFilter) events = events.filter(e => e.title.toLowerCase().includes(searchFilter) || (e.extendedProps?.description || '').toLowerCase().includes(searchFilter));
        events.sort((a, b) => new Date(a.start) - new Date(b.start));
        
        let html = '';
        if (events.length === 0) html = '<tr><td colspan="5" class="text-center py-5">No holiday events found.</td></tr>';
        else events.forEach((event, index) => {
            const props = event.extendedProps || {};
            html += `<tr class="event-item holiday" data-event-id="${event.id}" style="cursor:pointer;">`;
            html += `<td>${index + 1}</td><td><strong>${event.title}</strong></td><td>${formatDate(event.start)}</td>`;
            html += `<td>${event.end && event.end !== event.start ? formatDate(event.end) : formatDate(event.start)}</td>`;
            html += `<td>${(props.description || '').substring(0, 100)}</td></tr>`;
        });
        $('#holidayEventsTableBody').html(html);
        $('.event-item').off('click').on('click', function() {
            const id = $(this).data('event-id');
            const event = allEventsData.holidays.find(e => e.id === id) || allEventsData.academicEvents.find(e => e.id === id) || allEventsData.exams.find(e => e.id === id) || allEventsData.other.find(e => e.id === id);
            if (event) showEventDetailsInModal(event);
        });
    }
    
    function renderAcademicEventsTable() {
        const yearFilter = $('#academicYearFilter').val();
        const monthFilter = $('#academicMonthFilter').val();
        const searchFilter = $('#academicSearchInput').val().toLowerCase();
        let events = [...allEventsData.academicEvents];
        if (yearFilter) events = events.filter(e => new Date(e.start).getFullYear() == yearFilter);
        if (monthFilter) events = events.filter(e => (new Date(e.start).getMonth() + 1) == monthFilter);
        if (searchFilter) events = events.filter(e => e.title.toLowerCase().includes(searchFilter) || (e.extendedProps?.description || '').toLowerCase().includes(searchFilter));
        events.sort((a, b) => new Date(a.start) - new Date(b.start));
        
        let html = '';
        if (events.length === 0) html = '<tr><td colspan="5" class="text-center py-5">No academic events found.</td></tr>';
        else events.forEach((event, index) => {
            const props = event.extendedProps || {};
            html += `<tr class="event-item academic" data-event-id="${event.id}" style="cursor:pointer;">`;
            html += `<td>${index + 1}</td><td><strong>${event.title}</strong></td><td>${formatDate(event.start)}</td>`;
            html += `<td>${event.end && event.end !== event.start ? formatDate(event.end) : formatDate(event.start)}</td>`;
            html += `<td>${(props.description || '').substring(0, 100)}</td></tr>`;
        });
        $('#academicEventsTableBody').html(html);
        $('.event-item').off('click').on('click', function() {
            const id = $(this).data('event-id');
            const event = allEventsData.holidays.find(e => e.id === id) || allEventsData.academicEvents.find(e => e.id === id) || allEventsData.exams.find(e => e.id === id) || allEventsData.other.find(e => e.id === id);
            if (event) showEventDetailsInModal(event);
        });
    }
    
    function renderExamEventsTable() {
        const yearFilter = $('#examYearFilter').val();
        const monthFilter = $('#examMonthFilter').val();
        const searchFilter = $('#examSearchInput').val().toLowerCase();
        let events = [...allEventsData.exams];
        if (yearFilter) events = events.filter(e => new Date(e.start).getFullYear() == yearFilter);
        if (monthFilter) events = events.filter(e => (new Date(e.start).getMonth() + 1) == monthFilter);
        if (searchFilter) events = events.filter(e => e.title.toLowerCase().includes(searchFilter));
        events.sort((a, b) => new Date(a.start) - new Date(b.start));
        
        let html = '';
        if (events.length === 0) html = '<tr><td colspan="6" class="text-center py-5">No exam events found.</td></tr>';
        else events.forEach((event, index) => {
            const props = event.extendedProps || {};
            html += `<tr class="event-item exam" data-event-id="${event.id}" style="cursor:pointer;">`;
            html += `<td>${index + 1}</td><td><strong>${props.exam_name || event.title}</strong></td>`;
            html += `<td>${formatDate(event.start)}</td><td>${props.subject_name || 'N/A'}</td>`;
            html += `<td>${formatTime(event.start)} - ${formatTime(event.end)}</td><td>${props.classroom_name || 'N/A'}</td></tr>`;
        });
        $('#examEventsTableBody').html(html);
        $('.event-item').off('click').on('click', function() {
            const id = $(this).data('event-id');
            const event = allEventsData.exams.find(e => e.id === id);
            if (event) showEventDetailsInModal(event);
        });
    }
    
    function renderOtherEventsTable() {
        const yearFilter = $('#otherYearFilter').val();
        const monthFilter = $('#otherMonthFilter').val();
        const searchFilter = $('#otherSearchInput').val().toLowerCase();
        let events = [...allEventsData.other];
        if (yearFilter) events = events.filter(e => new Date(e.start).getFullYear() == yearFilter);
        if (monthFilter) events = events.filter(e => (new Date(e.start).getMonth() + 1) == monthFilter);
        if (searchFilter) events = events.filter(e => e.title.toLowerCase().includes(searchFilter) || (e.extendedProps?.description || '').toLowerCase().includes(searchFilter));
        events.sort((a, b) => new Date(a.start) - new Date(b.start));
        
        let html = '';
        if (events.length === 0) html = '<tr><td colspan="6" class="text-center py-5">No other events found.</td></tr>';
        else events.forEach((event, index) => {
            const props = event.extendedProps || {};
            html += `<tr class="event-item other" data-event-id="${event.id}" style="cursor:pointer;">`;
            html += `<td>${index + 1}</td><td><strong>${event.title}</strong></td><td>${formatDate(event.start)}</td>`;
            html += `<td>${event.end && event.end !== event.start ? formatDate(event.end) : formatDate(event.start)}</td>`;
            html += `<td>${props.department_name || 'All Departments'}</td><td>${(props.description || '').substring(0, 100)}</td></tr>`;
        });
        $('#otherEventsTableBody').html(html);
        $('.event-item').off('click').on('click', function() {
            const id = $(this).data('event-id');
            const event = allEventsData.other.find(e => e.id === id);
            if (event) showEventDetailsInModal(event);
        });
    }
    
    // Button click handlers
    $('#holidayEventsBtn').click(async function() {
        const year = $('#holidayYearFilter').val() || currentYear;
        showLoading();
        await fetchAllEventsForYear(year);
        renderHolidayEventsTable();
        hideLoading();
        $('#holidayEventsModal').modal('show');
    });
    
    $('#academicEventsBtn').click(async function() {
        const year = $('#academicYearFilter').val() || currentYear;
        showLoading();
        await fetchAllEventsForYear(year);
        renderAcademicEventsTable();
        hideLoading();
        $('#academicEventsModal').modal('show');
    });
    
    $('#examEventsBtn').click(async function() {
        const year = $('#examYearFilter').val() || currentYear;
        showLoading();
        await fetchAllEventsForYear(year);
        renderExamEventsTable();
        hideLoading();
        $('#examEventsModal').modal('show');
    });
    
    $('#otherEventsBtn').click(async function() {
        const year = $('#otherYearFilter').val() || currentYear;
        showLoading();
        await fetchAllEventsForYear(year);
        renderOtherEventsTable();
        hideLoading();
        $('#otherEventsModal').modal('show');
    });
    
    // Filter handlers
    $('#holidayYearFilter, #holidayMonthFilter, #holidaySearchInput').on('change keyup', async function() {
        const year = $('#holidayYearFilter').val();
        if ($(this).attr('id') === 'holidayYearFilter') { showLoading(); await fetchAllEventsForYear(year); hideLoading(); }
        renderHolidayEventsTable();
    });
    $('#resetHolidayFilters').click(function() { $('#holidayYearFilter').val(currentYear); $('#holidayMonthFilter').val(''); $('#holidaySearchInput').val(''); renderHolidayEventsTable(); });
    $('#refreshHolidayEventsBtn').click(async function() { const year = $('#holidayYearFilter').val(); showLoading(); await fetchAllEventsForYear(year); renderHolidayEventsTable(); hideLoading(); });
    
    $('#academicYearFilter, #academicMonthFilter, #academicSearchInput').on('change keyup', async function() {
        const year = $('#academicYearFilter').val();
        if ($(this).attr('id') === 'academicYearFilter') { showLoading(); await fetchAllEventsForYear(year); hideLoading(); }
        renderAcademicEventsTable();
    });
    $('#resetAcademicFilters').click(function() { $('#academicYearFilter').val(currentYear); $('#academicMonthFilter').val(''); $('#academicSearchInput').val(''); renderAcademicEventsTable(); });
    $('#refreshAcademicEventsBtn').click(async function() { const year = $('#academicYearFilter').val(); showLoading(); await fetchAllEventsForYear(year); renderAcademicEventsTable(); hideLoading(); });
    
    $('#examYearFilter, #examMonthFilter, #examSearchInput').on('change keyup', async function() {
        const year = $('#examYearFilter').val();
        if ($(this).attr('id') === 'examYearFilter') { showLoading(); await fetchAllEventsForYear(year); hideLoading(); }
        renderExamEventsTable();
    });
    $('#resetExamFilters').click(function() { $('#examYearFilter').val(currentYear); $('#examMonthFilter').val(''); $('#examSearchInput').val(''); renderExamEventsTable(); });
    $('#refreshExamEventsBtn').click(async function() { const year = $('#examYearFilter').val(); showLoading(); await fetchAllEventsForYear(year); renderExamEventsTable(); hideLoading(); });
    
    $('#otherYearFilter, #otherMonthFilter, #otherSearchInput').on('change keyup', async function() {
        const year = $('#otherYearFilter').val();
        if ($(this).attr('id') === 'otherYearFilter') { showLoading(); await fetchAllEventsForYear(year); hideLoading(); }
        renderOtherEventsTable();
    });
    $('#resetOtherFilters').click(function() { $('#otherYearFilter').val(currentYear); $('#otherMonthFilter').val(''); $('#otherSearchInput').val(''); renderOtherEventsTable(); });
    $('#refreshOtherEventsBtn').click(async function() { const year = $('#otherYearFilter').val(); showLoading(); await fetchAllEventsForYear(year); renderOtherEventsTable(); hideLoading(); });
    
    // Initialize Calendar
    function initCalendar() {
        if (!calendarEl) return;
        
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,listMonth' },
            buttonText: { today: 'Today', month: 'Month', week: 'Week', list: 'List' },
            height: 'auto',
            events: function(fetchInfo, successCallback, failureCallback) {
                showLoading();
                $.ajax({
                    url: '{{ route("student.calendar.events") }}',
                    data: { start: fetchInfo.startStr, end: fetchInfo.endStr },
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const events = response.events;
                            eventCountSpan.textContent = events.length;
                            const styledEvents = events.map(event => {
                                let className = '';
                                const category = event.extendedProps?.category;
                                switch(category) {
                                    case 'holiday': className = 'fc-event-holiday'; break;
                                    case 'exam': className = 'fc-event-exam'; break;
                                    case 'academic': className = 'fc-event-academic'; break;
                                    default: className = 'fc-event-other';
                                }
                                return { ...event, className: className };
                            });
                            successCallback(styledEvents);
                        } else successCallback([]);
                        hideLoading();
                    },
                    error: function(error) { console.error('Calendar error:', error); successCallback([]); hideLoading(); }
                });
            },
            eventClick: function(info) { showEventDetailsInModal(info.event); }
        });
        calendar.render();
    }
    
    initCalendar();
    fetchAllEventsForYear(currentYear);
});
</script>

@endsection