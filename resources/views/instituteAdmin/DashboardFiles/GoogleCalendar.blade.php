@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

<style>
.dashboard-container {
    display: flex;
    flex-direction: row;
}

.main-content {
    flex: 1;
}

.filter-container {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
    border-radius: 4px;
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

#calendar {
    max-width: 100%;
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    min-height: 700px;
}

/* Legend Styles */
.legend-container {
    background: white;
    border-radius: 8px;
    padding: 15px 20px;
    margin-bottom: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.legend-items {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    align-items: center;
}

.legend-item {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.legend-color {
    width: 20px;
    height: 20px;
    border-radius: 4px;
}

.btn-add-event {
    margin-left: auto;
}

/* Event Colors */
.fc-event-holiday {
    background-color: #188711 !important;
    border-color: #188711 !important;
}

.fc-event-birthday {
    background: linear-gradient(135deg, #484dec, #7618be) !important;
    border-color: #484dec !important;
}

.fc-event-exam {
    background-color: #ef4444 !important;
    border-color: #ef4444 !important;
}

.fc-event-academic {
    background-color: #3b82f6 !important;
    border-color: #3b82f6 !important;
}

.fc-event-meeting {
    background-color: #ffc107 !important;
    border-color: #ffc107 !important;
    color: #000 !important;
}

.fc-event-other {
    background-color: #6c757d !important;
    border-color: #6c757d !important;
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #007bff;
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

/* Modal Styles */
.modal-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.modal-title {
    color: #020202;
    font-weight: 600;
}

.event-details {
    padding: 10px;
}

.event-details h5 {
    color: #007bff;
    margin-bottom: 15px;
}

.event-details hr {
    margin: 10px 0;
}

.event-details p {
    margin-bottom: 10px;
}

.event-details strong {
    display: inline-block;
    min-width: 120px;
    color: #555;
}

/* Auto-sync notification */
.auto-sync-toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #28a745;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    z-index: 10000;
    animation: slideIn 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Time fields container - hidden by default */
.time-fields-container {
    display: none;
}

.time-fields-container.show {
    display: block;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }

    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Event Type Buttons */
.event-type-buttons .btn {
    font-size: 0.85rem;
    padding: 8px 16px;
    border-radius: 20px;
    transition: all 0.3s ease;
}

.event-type-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.event-type-buttons .btn i {
    margin-right: 6px;
}

/* Events List Modal Styles */
/*.events-list-modal .modal-dialog {*/
/*    max-width: 90%;*/
/*    width: 1200px;*/
/*}*/

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
}

.event-item:hover {
    background: white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transform: translateX(5px);
}

.event-item.holiday {
    border-left-color: #0d0666;
}

.event-item.meeting {
    border-left-color: #ffc107;
}

.event-item.academic {
    border-left-color: #3b82f6;
}

.event-item.birthday {
    border-left-color: #ec4898;
}

.event-item.exam {
    border-left-color: #ef4444;
}

.event-item.other {
    border-left-color: #6c757d;
}

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

.event-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.badge-holiday {
    background: #0d0666;
    color: white;
}

.badge-meeting {
    background: #ffc107;
    color: #000;
}

.badge-academic {
    background: #3b82f6;
    color: white;
}

.badge-birthday {
    background: #ec4898;
    color: white;
}

.badge-exam {
    background: #ef4444;
    color: white;
}

.badge-other {
    background: #6c757d;
    color: white;
}

.export-buttons {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.btn-export {
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-excel {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border: none;
}

.btn-csv {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: white;
    border: none;
}

.btn-pdf {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border: none;
}

.btn-print {
    background: linear-gradient(135deg, #6b7280, #4b5563);
    color: white;
    border: none;
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

.filter-group-inline select {
    padding: 6px 25px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 0.85rem;
}

.summary-stats {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.stat-card {
    background: linear-gradient(135deg, #f8fafc, #ffffff);
    padding: 12px 20px;
    border-radius: 12px;
    text-align: center;
    flex: 1;
    min-width: 100px;
    border: 1px solid #e5e7eb;
}

.stat-card .stat-number {
    font-size: 1.5rem;
    font-weight: 800;
    display: block;
}

.stat-card .stat-label {
    font-size: 0.75rem;
    color: #6b7280;
}

.stat-card.holiday .stat-number {
    color: #0d0666;
}

.stat-card.meeting .stat-number {
    color: #ffc107;
}

.stat-card.academic .stat-number {
    color: #3b82f6;
}

.stat-card.birthday .stat-number {
    color: #ec4898;
}

.stat-card.exam .stat-number {
    color: #ef4444;
}

.search-input {
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
    width: 250px;
}

.btn-event-type:hover{
    color: white !important;
    font-size: .83em;
    font-weight: 700;
}
</style>

<div class="loading-overlay">
    <div class="loading-spinner"></div>
</div>

<div class="dashboard-container">
    <main class="main-content">
        <!-- Legend and Add Event Button -->
        <div class="legend-container">
            <div class="legend-items">
                <div class="legend-item">
                    <span class="legend-color" style="background-color: #0d0666;"></span>
                    <span>Public Holidays</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background: linear-gradient(135deg, #484dec, #7618be) !important;"></span>
                    <span>Birthdays</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background-color: #ef4444;"></span>
                    <span>Exams</span>
                </div>
                <div class="legend-item d-none">
                    <span class="legend-color" style="background-color: #3b82f6;"></span>
                    <span>Academic Events</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background-color: #ffc107;"></span>
                    <span>Meetings</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background-color: #6c757d;"></span>
                    <span>Other Events</span>
                </div>

                <div class="legend-item">
                    <span class="legend-color" style="background-color: transparent; border: 1px solid #ddd;"></span>
                    <span>👥 Both | 🎓 Students | 💼 Employees</span>
                </div>

                <button type="button" class="btn btn-primary btn-add-event" data-bs-toggle="modal"
                    data-bs-target="#eventModal">
                    <i class="fas fa-plus"></i> Add New Event
                </button>

                <!-- Event Type Buttons -->
                <div class="event-type-buttons" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;">
                    <button type="button" class="btn btn-outline-primary btn-event-type" id="holidayEventsBtn">
                        <i class="fas fa-calendar-times"></i> Holidays
                    </button>
                    <button type="button" class="btn btn-outline-warning btn-event-type" id="meetingEventsBtn">
                        <i class="fas fa-users"></i> Meetings
                    </button>
                    <button type="button" class="btn btn-outline-info btn-event-type" id="academicEventsBtn">
                        <i class="fas fa-graduation-cap"></i> Academic
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-event-type" id="examEventsBtn">
                        <i class="fas fa-file-alt"></i> Exams
                    </button>
                    <button type="button" class="btn btn-outline-success btn-event-type" id="birthdayEventsBtn">
                        <i class="fas fa-birthday-cake"></i> Birthdays
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-event-type" id="otherEventsBtn">
                        <i class="fas fa-ellipsis-h"></i> Other
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-container">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="filterCategory">Event Category</label>
                    <select id="filterCategory" class="form-control">
                        <option value="">All Events</option>
                        <option value="holiday">Public Holidays</option>
                        <option value="birthday">Birthdays</option>
                        <option value="exam">Exams</option>
                        <option value="academic">Academic Events</option>
                        <option value="meeting">Meetings</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterDepartment">Department</label>
                    <select id="filterDepartment" class="form-control">
                        <option value="">All Departments</option>
                        <option value="overall">Overall Events Only</option>
                        @foreach($categories as $category)
                        @if($category->departments && $category->departments->count() > 0)
                        <optgroup label="{{ $category->category_name }}">
                            @foreach($category->departments as $department)
                            <option value="{{ $department->department_id }}">{{ $department->department }}</option>
                            @endforeach
                        </optgroup>
                        @endif
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterDesignation">Designation</label>
                    <select id="filterDesignation" class="form-control">
                        <option value="">All Designations</option>
                        @foreach($employeelist->pluck('designation')->unique() as $designation)
                        @if($designation)
                        <option value="{{ $designation }}">{{ $designation }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="filter-row">
                <div class="filter-group">
                    <label for="filterAudience">Target Audience</label>
                    <select id="filterAudience" class="form-control">
                        <option value="">All Audiences</option>
                        <option value="students">🎓 Students Only</option>
                        <option value="employees">💼 Employees Only</option>
                        <option value="both">👥 Both</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="filterYear">Academic Year</label>
                    <select id="filterYear" class="form-control">
                        <option value="{{ $currentYear }}">{{ $currentYear }} - {{ $currentYear + 1 }}</option>
                        <option value="{{ $currentYear - 1 }}">{{ $currentYear - 1 }} - {{ $currentYear }}</option>
                        <option value="{{ $currentYear + 1 }}">{{ $currentYear + 1 }} - {{ $currentYear + 2 }}</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>&nbsp;</label>
                    <div class="form-control-plaintext text-muted small">

                    </div>
                </div>
            </div>

            <div class="filter-actions">
                <button id="filterClearBtn" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Clear Filters
                </button>
                <span class="event-count-badge">
                    <i class="fas fa-calendar-alt"></i>
                    <span id="eventCount">0</span> Events
                </span>
            </div>
        </div>

        <!-- Calendar -->
        <h2 class="mb-3">Calendar {{ $academicYear->year_name ?? date('Y') }}</h2>
        <div id="calendar"></div>
    </main>
</div>

<!-- Holiday Events Modal -->
<div class="modal fade events-list-modal" id="holidayEventsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center"
                style="background: linear-gradient(135deg, #0d0666, #1e1b4b); color: white;">
                <div>
                    <h5 class="modal-title mb-0"><i class="fas fa-calendar-times me-2"></i> Public Holidays</h5>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-info btn-sm" id="openManageHolidaysBtn">
                        <i class="fas fa-cog me-1"></i> Manage Holidays
                    </button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body">
                <!-- Filters inside modal -->
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
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Search:</label>
                        <input type="text" id="holidaySearchInput" class="search-input"
                            placeholder="Search holidays...">
                    </div>
                    <div class="filter-item">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="resetHolidayFilters">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Events Table -->
                <div class="events-list-container">
                    <table class="events-table" id="holidayEventsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Holiday Title</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody id="holidayEventsTableBody">
                            <tr>
                                <td colspan="5" class="text-center">Loading holidays...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="refreshHolidayEventsBtn">
                    <i class="fas fa-refresh me-1"></i> Refresh
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Meeting Events Modal -->
<div class="modal fade events-list-modal" id="meetingEventsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #ffc107, #d97706); color: black;">
                <h5 class="modal-title"><i class="fas fa-users me-2"></i> Meetings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Filters inside modal -->
                <div class="filter-group-inline">
                    <div class="filter-item">
                        <label>Year:</label>
                        <select id="meetingYearFilter" class="form-select">
                            <option value="{{ $currentYear - 1 }}">{{ $currentYear - 1 }}</option>
                            <option value="{{ $currentYear }}" selected>{{ $currentYear }}</option>
                            <option value="{{ $currentYear + 1 }}">{{ $currentYear + 1 }}</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Month:</label>
                        <select id="meetingMonthFilter" class="form-select">
                            <option value="">All Months</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Search:</label>
                        <input type="text" id="meetingSearchInput" class="search-input"
                            placeholder="Search meetings...">
                    </div>
                    <div class="filter-item">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="resetMeetingFilters">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Events Table -->
                <div class="events-list-container">
                    <table class="events-table" id="meetingEventsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Meeting Title</th>
                                <th>Start Date</th>
                                <th>Start Time</th>
                                <th>End Date</th>
                                <th>End Time</th>
                                <th>Department</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody id="meetingEventsTableBody">
                            <tr>
                                <td colspan="8" class="text-center">Loading meetings...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="refreshMeetingEventsBtn">
                    <i class="fas fa-refresh me-1"></i> Refresh
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Academic Events Modal -->
<div class="modal fade events-list-modal" id="academicEventsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white;">
                <h5 class="modal-title"><i class="fas fa-graduation-cap me-2"></i> Academic Events</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Filters inside modal -->
                <div class="filter-group-inline">
                    <div class="filter-item">
                        <label>Year:</label>
                        <select id="academicYearFilter" class="form-select">
                            <option value="{{ $currentYear - 1 }}">{{ $currentYear - 1 }}</option>
                            <option value="{{ $currentYear }}" selected>{{ $currentYear }}</option>
                            <option value="{{ $currentYear + 1 }}">{{ $currentYear + 1 }}</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Month:</label>
                        <select id="academicMonthFilter" class="form-select">
                            <option value="">All Months</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Search:</label>
                        <input type="text" id="academicSearchInput" class="search-input"
                            placeholder="Search academic events...">
                    </div>
                    <div class="filter-item">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="resetAcademicFilters">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Events Table -->
                <div class="events-list-container">
                    <table class="events-table" id="academicEventsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Event Title</th>
                                <th>Start Date</th>
                                <th>Start Time</th>
                                <th>End Date</th>
                                <th>End Time</th>
                                <th>Department</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody id="academicEventsTableBody">
                            <tr>
                                <td colspan="8" class="text-center">Loading academic events...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="refreshAcademicEventsBtn">
                    <i class="fas fa-refresh me-1"></i> Refresh
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Exam Events Modal -->
<div class="modal fade events-list-modal" id="examEventsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white;">
                <h5 class="modal-title"><i class="fas fa-file-alt me-2"></i> Exams</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Filters inside modal -->
                <div class="filter-group-inline">
                    <div class="filter-item">
                        <label>Year:</label>
                        <select id="examYearFilter" class="form-select">
                            <option value="{{ $currentYear - 1 }}">{{ $currentYear - 1 }}</option>
                            <option value="{{ $currentYear }}" selected>{{ $currentYear }}</option>
                            <option value="{{ $currentYear + 1 }}">{{ $currentYear + 1 }}</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Month:</label>
                        <select id="examMonthFilter" class="form-select">
                            <option value="">All Months</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Search:</label>
                        <input type="text" id="examSearchInput" class="search-input" placeholder="Search exams...">
                    </div>
                    <div class="filter-item">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="resetExamFilters">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Events Table -->
                <div class="events-list-container">
                    <table class="events-table" id="examEventsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Exam Title</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="examEventsTableBody">
                            <tr>
                                <td colspan="7" class="text-center">Loading exams...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="refreshExamEventsBtn">
                    <i class="fas fa-refresh me-1"></i> Refresh
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Birthday Events Modal -->
<div class="modal fade events-list-modal" id="birthdayEventsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #ec4898, #be185d); color: white;">
                <h5 class="modal-title"><i class="fas fa-birthday-cake me-2"></i> Birthdays</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Filters inside modal -->
                <div class="filter-group-inline">
                    <div class="filter-item">
                        <label>Year:</label>
                        <select id="birthdayYearFilter" class="form-select">
                            <option value="{{ $currentYear - 1 }}">{{ $currentYear - 1 }}</option>
                            <option value="{{ $currentYear }}" selected>{{ $currentYear }}</option>
                            <option value="{{ $currentYear + 1 }}">{{ $currentYear + 1 }}</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Month:</label>
                        <select id="birthdayMonthFilter" class="form-select">
                            <option value="">All Months</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Search:</label>
                        <input type="text" id="birthdaySearchInput" class="search-input"
                            placeholder="Search birthdays...">
                    </div>
                    <div class="filter-item">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="resetBirthdayFilters">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Events Table -->
                <div class="events-list-container">
                    <table class="events-table" id="birthdayEventsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Designation</th>
                                <th>Birth Date</th>
                                <th>Age</th>
                            </tr>
                        </thead>
                        <tbody id="birthdayEventsTableBody">
                            <tr>
                                <td colspan="5" class="text-center">Loading birthdays...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="refreshBirthdayEventsBtn">
                    <i class="fas fa-refresh me-1"></i> Refresh
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Other Events Modal -->
<div class="modal fade events-list-modal" id="otherEventsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #6c757d, #4b5563); color: white;">
                <h5 class="modal-title"><i class="fas fa-ellipsis-h me-2"></i> Other Events</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Filters inside modal -->
                <div class="filter-group-inline">
                    <div class="filter-item">
                        <label>Year:</label>
                        <select id="otherYearFilter" class="form-select">
                            <option value="{{ $currentYear - 1 }}">{{ $currentYear - 1 }}</option>
                            <option value="{{ $currentYear }}" selected>{{ $currentYear }}</option>
                            <option value="{{ $currentYear + 1 }}">{{ $currentYear + 1 }}</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Month:</label>
                        <select id="otherMonthFilter" class="form-select">
                            <option value="">All Months</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label>Search:</label>
                        <input type="text" id="otherSearchInput" class="search-input"
                            placeholder="Search other events...">
                    </div>
                    <div class="filter-item">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="resetOtherFilters">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Events Table -->
                <div class="events-list-container">
                    <table class="events-table" id="otherEventsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Event Title</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Department</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody id="otherEventsTableBody">
                            <tr>
                                <td colspan="6" class="text-center">Loading other events...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="refreshOtherEventsBtn">
                    <i class="fas fa-refresh me-1"></i> Refresh
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="eventForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="eventId" name="event_id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                        <!-- Add this inside the event form modal, after the Event Type field -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Target Audience *</label>
                                    <select class="form-control" id="target_audience" name="target_audience" required>
                                        <option value="">-- Select Audience --</option>
                                        <option value="students">🎓 Students Only</option>
                                        <option value="employees">💼 Employees Only</option>
                                        <option value="both">👥 Both (Students & Employees)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Event Type *</label>
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="holiday">Holiday</option>
                                        <option value="event">Event</option>
                                        <option value="meeting">Meeting</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Color</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color" id="color" name="color"
                                        value="#0d0666" title="Choose event color">
                                    <span class="input-group-text">#0d0666</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Scope *</label>
                                <select class="form-control" id="scope" name="scope" required>
                                    <option value="">-- Select Scope --</option>
                                    <option value="overall">Overall (All Departments)</option>
                                    <option value="department_wise">Department-wise</option>
                                </select>
                            </div>
                        </div>
                    </div>



                    <!-- Department Selection (hidden by default) -->
                    <div id="departmentFields" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Department Category *</label>
                                    <select class="form-control" id="department_category_id"
                                        name="department_category_id">
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->department_category_id }}">
                                            {{ $category->category_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Department *</label>
                                    <select class="form-control" id="department_id" name="department_id">
                                        <option value="">-- Select Department --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Start Date *</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">End Date *</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                        </div>
                    </div>

                    <!-- Time fields - hidden by default, shown only for meetings -->
                    <div id="timeFields" class="time-fields-container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" class="form-control" id="start_time" name="start_time">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">End Time</label>
                                    <input type="time" class="form-control" id="end_time" name="end_time">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                            placeholder="Optional description for the event"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_recurring"
                                        name="is_recurring">
                                    <label class="form-check-label">Recurring Event</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <select class="form-control" id="recurring_type" name="recurring_type" disabled>
                                    <option value="none">No Recurrence</option>
                                    <option value="yearly">Yearly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="weekly">Weekly</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Event</button>
                </div>
            </form>
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
            <div class="modal-body" id="eventModalBody">
                <!-- Dynamic content -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Holiday Management Modal -->
<div class="modal fade" id="holidayManagementModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                <h5 class="modal-title"><i class="fas fa-calendar-alt me-2"></i> Manage Holidays</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Management Actions -->
                <div class="management-actions" style="margin-bottom: 20px;">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Filter by Status</label>
                            <select id="manageStatusFilter" class="form-control">
                                <option value="">All</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Year</label>
                            <input type="text" class="form-control" value="{{ $currentYear }}" disabled>
                        </div>
                        <div class="col-md-3">
                            <label>Search</label>
                            <input type="text" id="manageSearchInput" class="form-control"
                                placeholder="Search holidays...">
                        </div>
                        <div class="col-md-3">
                            <label>Bulk Actions</label>
                            <div class="d-flex gap-2">
                                <button class="btn btn-success btn-sm" id="bulkActivateBtn">
                                    <i class="fas fa-check-circle"></i> Activate Selected
                                </button>
                                <button class="btn btn-warning btn-sm" id="bulkDeactivateBtn">
                                    <i class="fas fa-ban"></i> Deactivate Selected
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button class="btn btn-info d-none" id="resyncHolidaysBtn">
                                <i class="fas fa-sync-alt"></i> Resync Holidays from API
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Holidays Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="holidaysTable">
                        <thead>
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="selectAllHolidays">
                                </th>
                                <th>Title</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="holidaysTableBody">
                            <tr>
                                <td colspan="6" class="text-center">Loading holidays...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div id="holidaysPagination" class="mt-3 d-flex justify-content-end"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="refreshHolidaysBtn">
                    <i class="fas fa-refresh me-1"></i> Refresh
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Holiday Modal -->
<div class="modal fade" id="editHolidayModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editHolidayForm">
                <div class="modal-body">
                    <input type="hidden" id="editHolidayId" name="holiday_id">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" class="form-control" id="editTitle" name="title" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Start Date *</label>
                                <input type="date" class="form-control" id="editStartDate" name="start_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">End Date *</label>
                                <input type="date" class="form-control" id="editEndDate" name="end_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color" id="editColor" name="color"
                                value="#0d0666">
                            <span class="input-group-text" id="editColorText">#0d0666</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Scripts -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
    integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const calendarEl = document.getElementById('calendar');
    const loadingOverlay = document.querySelector('.loading-overlay');
    const filterCategory = document.getElementById('filterCategory');
    const filterDepartment = document.getElementById('filterDepartment');
    const filterDesignation = document.getElementById('filterDesignation');
    const filterYear = document.getElementById('filterYear');
    const filterClearBtn = document.getElementById('filterClearBtn');
    const eventCountSpan = document.getElementById('eventCount');

    // Set current year
    const currentYear = new Date().getFullYear();

    // Store all events data for the list view
    let allEventsData = {
        holidays: [],
        meetings: [],
        academicEvents: [],
        birthdays: [],
        exams: [],
        other: []
    };

    let currentActiveTab = 'all';
    let calendar = null;

    // Event type change handler for meeting time fields
    const eventTypeSelect = document.getElementById('type');
    const timeFields = document.getElementById('timeFields');

    function toggleTimeFields() {
        const eventType = eventTypeSelect.value;
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        if (eventType === 'meeting' || eventType === 'event') {
            timeFields.classList.add('show');
            if (startTimeInput) startTimeInput.required = true;
            if (endTimeInput) endTimeInput.required = true;
        } else {
            timeFields.classList.remove('show');
            if (startTimeInput) {
                startTimeInput.value = '';
                startTimeInput.required = false;
            }
            if (endTimeInput) {
                endTimeInput.value = '';
                endTimeInput.required = false;
            }
        }
    }

    const scopeSelect = document.getElementById('scope');
    const departmentFields = document.getElementById('departmentFields');
    const departmentCategorySelect = document.getElementById('department_category_id');
    const departmentSelect = document.getElementById('department_id');

    @php
    $departmentData = $categories -> map(function($category) {
        return [
            'id' => (string) $category -> department_category_id,
            'name' => $category -> category_name,
            'departments' => $category -> departments -> map(function($department) {
                return [
                    'id' => (string) $department -> department_id,
                    'name' => $department -> department
                ];
            }) -> toArray()
        ];
    }) -> toArray();
    @endphp

    const departmentData = @json($departmentData);

    // console.log('Department Data loaded:', departmentData);

    function updateDepartmentFields() {
        if (!scopeSelect || !departmentFields || !departmentCategorySelect || !departmentSelect) return;

        if (scopeSelect.value === 'department_wise') {
            departmentFields.style.display = 'block';
            departmentCategorySelect.required = true;
            departmentSelect.required = true;
        } else {
            departmentFields.style.display = 'none';
            departmentCategorySelect.required = false;
            departmentSelect.required = false;
            departmentCategorySelect.value = '';
            departmentSelect.innerHTML = '<option value="">-- Select Department --</option>';
        }
    }

    function populateDepartmentOptions() {
        if (!departmentCategorySelect || !departmentSelect) return;

        const categoryId = departmentCategorySelect.value;
        console.log('Selected Category ID:', categoryId);
        console.log('Available Categories:', departmentData);

        const category = departmentData.find(cat => String(cat.id) === String(categoryId));
        let options = '<option value="">-- Select Department --</option>';

        if (category && category.departments) {
            console.log('Found category:', category);
            category.departments.forEach(dept => {
                options += `<option value="${dept.id}">${dept.name}</option>`;
            });
        } else {
            console.log('No category found for ID:', categoryId);
        }

        departmentSelect.innerHTML = options;
    }

    if (scopeSelect) {
        // Set initial state
        updateDepartmentFields();

        // Add change handler
        scopeSelect.addEventListener('change', function() {
            console.log('Scope changed to:', this.value);
            updateDepartmentFields();

            // If scope is overall, clear department selections
            if (this.value === 'overall') {
                $('#department_category_id').val('');
                $('#department_id').html('<option value="">-- Select Department --</option>');
            }
        });
    }

    if (departmentCategorySelect) {
        departmentCategorySelect.addEventListener('change', populateDepartmentOptions);
    }

    if (eventTypeSelect) {
        toggleTimeFields();
        eventTypeSelect.addEventListener('change', toggleTimeFields);
    }

    // Helper Functions
    function showLoading() {
        if (loadingOverlay) loadingOverlay.style.display = 'flex';
    }

    function hideLoading() {
        if (loadingOverlay) loadingOverlay.style.display = 'none';
    }

    function refreshCalendar() {
        if (calendar) calendar.refetchEvents();
    }

    function formatDate(dateStr) {
        if (!dateStr) return 'N/A';
        return new Date(dateStr).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    function formatTime(dateStr) {
        if (!dateStr) return 'N/A';
        return new Date(dateStr).toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function showAlert(message, type) {
        $('.custom-alert').remove();
        const bgColor = type === 'success' ? '#28a745' : (type === 'error' ? '#dc3545' : '#17a2b8');
        const icon = type === 'success' ? 'check-circle' : (type === 'error' ? 'exclamation-circle' :
            'info-circle');
        const alertEl = $(`
            <div class="custom-alert" style="position:fixed; top:20px; right:20px; z-index:9999; min-width:300px; background:${bgColor}; color:white; padding:12px 20px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.15); animation:slideIn 0.3s ease;">
                <i class="fas fa-${icon} me-2"></i>${message}
                <button type="button" class="btn-close btn-close-white" style="float:right; margin-top:-2px;" data-bs-dismiss="alert"></button>
            </div>
        `);
        $('body').append(alertEl);
        setTimeout(() => alertEl.fadeOut('slow', function() {
            $(this).remove();
        }), 5000);
    }

    function showAutoSyncNotification(message, type) {
        const toast = document.createElement('div');
        toast.className = 'auto-sync-toast';
        toast.style.backgroundColor = type === 'success' ? '#28a745' : '#dc3545';
        toast.style.color = 'white';
        toast.innerHTML = `<i class="fas fa-check-circle me-2"></i>${message}`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    function getEventTypeLabel(type) {
        const labels = {
            'holiday': 'Public Holiday',
            'meeting': 'Meeting',
            'academic': 'Academic Event',
            'birthday': 'Birthday',
            'exam': 'Exam',
            'other': 'Other'
        };
        return labels[type] || type;
    }

    function showEventDetailsInModal(event) {
    const props = event.extendedProps || {};
    const category = props.category || event.type;
    let html = '<div class="event-details">';
    
    // Helper function for audience labels and styling
    const audienceLabels = {
        'students': { label: '🎓 Students Only', color: '#3b82f6', icon: '🎓' },
        'employees': { label: '💼 Employees Only', color: '#10b981', icon: '💼' },
        'both': { label: '👥 Both (Students & Employees)', color: '#8b5cf6', icon: '👥' }
    };
    
    // Get audience info from props
    const targetAudience = props.target_audience || 'both';
    const audienceInfo = audienceLabels[targetAudience] || audienceLabels['both'];
    
    // Helper function to get event type badge
    const getEventTypeBadge = (type) => {
        const badges = {
            'holiday': '<span class="badge" style="background: #0d0666;">🏖️ Holiday</span>',
            'meeting': '<span class="badge" style="background: #ffc107; color: #000;">📅 Meeting</span>',
            'event': '<span class="badge" style="background: #28a745;">📌 Event</span>',
            'exam': '<span class="badge" style="background: #ef4444;">📝 Exam</span>',
            'academic': '<span class="badge" style="background: #3b82f6;">🎓 Academic</span>',
            'birthday': '<span class="badge" style="background: #ec4898;">🎂 Birthday</span>',
            'other': '<span class="badge" style="background: #6c757d;">📋 Other</span>'
        };
        return badges[type] || badges['other'];
    };
    
    if (category === 'holiday') {
        html += `
            <div style="background: linear-gradient(135deg, #0b910a, #25a516); color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <h5 style="margin: 0; color: white;">🎉 ${event.title}</h5>
                <small style="color: #c7d2fe;">${getEventTypeBadge('holiday')}</small>
            </div>
            <div style="padding: 10px;">
                <div style="background: ${audienceInfo.color}20; padding: 10px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid ${audienceInfo.color};">
                    <p style="margin: 0;"><strong>🎯 Target Audience:</strong> ${audienceInfo.label}</p>
                </div>
                <p><strong>📅 Date:</strong> ${formatDate(event.start)}${event.end && event.end !== event.start ? ' - ' + formatDate(event.end) : ''}</p>
                <p><strong>🌍 Scope:</strong> ${props.scope === 'department_wise' ? '🏢 Department Wise' : '🌐 Overall'}</p>
                ${props.department_name ? `<p><strong>🏢 Department:</strong> ${props.department_name}</p>` : ''}
                <p><strong>📝 Description:</strong> ${props.description || 'No description'}</p>
            </div>
        `;
    } 
    else if (category === 'birthday') {
        html += `
            <div style="background: linear-gradient(135deg, #484dec, #7618be); color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <h5 style="margin: 0; color: white;">🎂 ${props.employee_name || event.title}</h5>
            </div>
            <div style="padding: 10px;">
                <div class="d-none" style="background: ${audienceInfo.color}20; padding: 10px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid ${audienceInfo.color};">
                    <p style="margin: 0;"><strong>🎯 Target Audience:</strong> ${audienceInfo.label}</p>
                </div>
                <p><strong>📅 Date:</strong> ${formatDate(event.start)}</p>
                <p><strong>💼 Designation:</strong> ${props.designation || 'N/A'}</p>
                <p><strong>🎂 Age:</strong> ${props.age || 'N/A'} years</p>
            </div>
        `;
    } 
    else if (category === 'exam') {
        // Calculate duration in hours and minutes
        let durationText = '';
        if (props.duration_minutes) {
            const hours = Math.floor(props.duration_minutes / 60);
            const minutes = props.duration_minutes % 60;
            durationText = `${hours}h ${minutes}m`;
        }
        
        // Format the class/course name properly
        let className = props.course_name || 'N/A';
        // Check if we have section info
        if (props.section_id && props.section_id !== 'null' && props.section_id !== '') {
            className += ` (Section: ${props.section_id})`;
        }
        
        html += `
            <div style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <h5 style="margin: 0; color: white;">📝 ${props.exam_name || event.title}</h5>
                <small style="color: #fecaca;">${getEventTypeBadge('exam')}</small>
            </div>
            <div style="padding: 10px;">
                <div style="background: ${audienceInfo.color}20; padding: 10px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid ${audienceInfo.color};">
                    <p style="margin: 0;"><strong>🎯 Target Audience:</strong> ${audienceInfo.label}</p>
                </div>
                <p><strong>📚 Subject:</strong> ${props.subject_name || 'N/A'}</p>
                <p><strong>🏫 Class/Course:</strong> ${className}</p>
                <p><strong>📅 Exam Date:</strong> ${formatDate(event.start)}</p>
                <p><strong>⏰ Exam Time:</strong> ${formatTime(event.start)} - ${formatTime(event.end)}</p>
                ${durationText ? `<p><strong>⏱️ Duration:</strong> ${durationText}</p>` : ''}
                <p><strong>🏠 Classroom:</strong> ${props.classroom_name || 'N/A'}</p>
                <p><strong>📊 Total Marks:</strong> ${props.total_marks || 'N/A'}</p>
                <p><strong>✓ Passing Marks:</strong> ${props.passing_marks || 'N/A'}</p>
                <p><strong>📋 Status:</strong> ${props.is_published == 1 ? '<span class="badge bg-success">Published</span>' : '<span class="badge bg-warning">Draft</span>'}</p>
                <p><strong>📝 Description:</strong> ${props.description || 'No description available'}</p>
            </div>
        `;
    } 
    else if (category === 'meeting') {
        const meetingStart = props.start_time || formatTime(event.start);
        const meetingEnd = props.end_time || (event.end ? formatTime(event.end) : '');
        html += `
            <div style="background: linear-gradient(135deg, #ffc107, #d97706); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <h5 style="margin: 0;">📅 ${event.title}</h5>
                <small>${getEventTypeBadge('meeting')}</small>
            </div>
            <div style="padding: 10px;">
                <div style="background: ${audienceInfo.color}20; padding: 10px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid ${audienceInfo.color};">
                    <p style="margin: 0;"><strong>🎯 Target Audience:</strong> ${audienceInfo.label}</p>
                </div>
                <p><strong>📅 Date:</strong> ${formatDate(event.start)}${event.end && event.end !== event.start ? ' - ' + formatDate(event.end) : ''}</p>
                <p><strong>⏰ Time:</strong> ${meetingStart}${meetingEnd ? ' - ' + meetingEnd : ''}</p>
                <p><strong>🌍 Scope:</strong> ${props.scope === 'department_wise' ? '🏢 Department Wise' : '🌐 Overall'}</p>
                ${props.department_name ? `<p><strong>🏢 Department:</strong> ${props.department_name}</p>` : ''}
                <p><strong>📝 Description:</strong> ${props.description || 'No description'}</p>
            </div>
        `;
    } 
    else if (category === 'academic') {
        html += `
            <div style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <h5 style="margin: 0; color: white;">🎓 ${event.title}</h5>
                <small style="color: #bfdbfe;">${getEventTypeBadge('academic')}</small>
            </div>
            <div style="padding: 10px;">
                <div style="background: ${audienceInfo.color}20; padding: 10px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid ${audienceInfo.color};">
                    <p style="margin: 0;"><strong>🎯 Target Audience:</strong> ${audienceInfo.label}</p>
                </div>
                <p><strong>📅 Date:</strong> ${formatDate(event.start)}${event.end && event.end !== event.start ? ' - ' + formatDate(event.end) : ''}</p>
                <p><strong>📝 Description:</strong> ${props.description || 'No description'}</p>
            </div>
        `;
    } 
    else {
        html += `
            <div style="background: linear-gradient(135deg, #6c757d, #4b5563); color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <h5 style="margin: 0; color: white;">📌 ${event.title}</h5>
                <small style="color: #cbd5e1;">${getEventTypeBadge(props.type || 'other')}</small>
            </div>
            <div style="padding: 10px;">
                <div style="background: ${audienceInfo.color}20; padding: 10px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid ${audienceInfo.color};">
                    <p style="margin: 0;"><strong>🎯 Target Audience:</strong> ${audienceInfo.label}</p>
                </div>
                <p><strong>📅 Date:</strong> ${formatDate(event.start)}${event.end && event.end !== event.start ? ' - ' + formatDate(event.end) : ''}</p>
                ${props.start_time ? `<p><strong>⏰ Time:</strong> ${props.start_time}${props.end_time ? ' - ' + props.end_time : ''}</p>` : ''}
                <p><strong>🌍 Scope:</strong> ${props.scope === 'department_wise' ? '🏢 Department Wise' : '🌐 Overall'}</p>
                ${props.department_name ? `<p><strong>🏢 Department:</strong> ${props.department_name}</p>` : ''}
                <p><strong>📝 Description:</strong> ${props.description || 'No description'}</p>
            </div>
        `;
    }
    
    html += '</div>';
    $('#eventModalBody').html(html);
    new bootstrap.Modal(document.getElementById('viewEventModal')).show();
}
    // Function to fetch events for specific year only
    async function fetchAllEventsForYear(year) {
        showLoading();
        try {
            const startDate = `${year}-01-01`;
            const endDate = `${year}-12-31`;

            const response = await fetch(
                '{{ route("institute-admin.google-calendar.events") }}?start_date=' + startDate +
                '&end_date=' + endDate);
            const data = await response.json();

            const events = data.events || [];

            // Categorize events
            allEventsData = {
                holidays: events.filter(e => e.extendedProps?.category === 'holiday'),
                meetings: events.filter(e => e.extendedProps?.category === 'meeting'),
                academicEvents: events.filter(e => e.extendedProps?.category === 'academic'),
                birthdays: events.filter(e => e.extendedProps?.category === 'birthday'),
                exams: events.filter(e => e.extendedProps?.category === 'exam'),
                other: events.filter(e => !['holiday', 'meeting', 'academic', 'birthday', 'exam']
                    .includes(e.extendedProps?.category))
            };

            return allEventsData;
        } catch (error) {
            console.error('Error fetching events:', error);
            return allEventsData;
        } finally {
            hideLoading();
        }
    }

    // Function to update event stats with tabs
    function updateEventStatsWithTabs(events) {
        const stats = {
            total: events.length,
            holiday: events.filter(e => e.type === 'holiday').length,
            meeting: events.filter(e => e.type === 'meeting').length,
            academic: events.filter(e => e.type === 'academic').length,
            birthday: events.filter(e => e.type === 'birthday').length,
            exam: events.filter(e => e.type === 'exam').length,
            other: events.filter(e => e.type === 'other').length
        };

        const html = `
            <div class="stat-card total">
                <span class="stat-number">${stats.total}</span>
                <span class="stat-label">Total Events</span>
            </div>
            <div class="stat-card holiday">
                <span class="stat-number">${stats.holiday}</span>
                <span class="stat-label">Holidays</span>
            </div>
            <div class="stat-card meeting">
                <span class="stat-number">${stats.meeting}</span>
                <span class="stat-label">Meetings</span>
            </div>
            <div class="stat-card academic">
                <span class="stat-number">${stats.academic}</span>
                <span class="stat-label">Academic</span>
            </div>
            <div class="stat-card birthday">
                <span class="stat-number">${stats.birthday}</span>
                <span class="stat-label">Birthdays</span>
            </div>
            <div class="stat-card exam">
                <span class="stat-number">${stats.exam}</span>
                <span class="stat-label">Exams</span>
            </div>
        `;

        $('#eventStats').html(html);
    }

    // Separate render functions for each modal
    function renderHolidayEventsTable() {
        const yearFilter = $('#holidayYearFilter').val();
        const monthFilter = $('#holidayMonthFilter').val();
        const searchFilter = $('#holidaySearchInput').val().toLowerCase();

        let events = allEventsData.holidays.map(e => ({
            ...e,
            type: 'holiday'
        }));

        // Apply year filter first
        if (yearFilter) {
            events = events.filter(event => {
                const eventDate = new Date(event.start);
                const eventYear = eventDate.getFullYear();
                return eventYear == yearFilter;
            });
        }

        // Apply month filter
        if (monthFilter) {
            events = events.filter(event => {
                const eventDate = new Date(event.start);
                const eventMonth = eventDate.getMonth() + 1;
                return eventMonth == monthFilter;
            });
        }

        // Apply search filter
        if (searchFilter) {
            events = events.filter(event => {
                return event.title.toLowerCase().includes(searchFilter) ||
                    (event.extendedProps?.description || '').toLowerCase().includes(searchFilter);
            });
        }

        // Sort by date
        events.sort((a, b) => new Date(a.start) - new Date(b.start));

        // Render table
        let html = '';
        if (events.length === 0) {
            html = '<tr><td colspan="5" class="text-center py-5">No holiday events found.</td></tr>';
        } else {
            events.forEach((event, index) => {
                const props = event.extendedProps || {};
                // Use actual database dates instead of FullCalendar modified dates
                const startDate = props.start_date ? formatDate(props.start_date) : formatDate(event
                    .start);
                const endDate = props.end_date ? formatDate(props.end_date) : startDate;
                const description = props.description || (props.title || event.title) ||
                    'No description';

                html += `
                    <tr class="event-item holiday" data-event-id="${event.id}" data-event-type="holiday" style="cursor:pointer;">
                        <td>${index + 1}</td>
                        <td><strong>${event.title || 'Untitled'}</strong></td>
                        <td>${startDate}</td>
                        <td>${endDate}</td>
                        <td>${description.substring(0, 100)}${description.length > 100 ? '...' : ''}</td>
                    </tr>
                `;
            });
        }

        $('#holidayEventsTableBody').html(html);

        // Add click event to view event details
        $('#holidayEventsTableBody .event-item').click(function() {
            const eventId = $(this).data('event-id');
            const event = allEventsData.holidays.find(e => e.id === eventId);
            if (event) showEventDetailsInModal(event);
        });
    }

    function renderMeetingEventsTable() {
        const yearFilter = $('#meetingYearFilter').val();
        const monthFilter = $('#meetingMonthFilter').val();
        const searchFilter = $('#meetingSearchInput').val().toLowerCase();

        let events = allEventsData.meetings.map(e => ({
            ...e,
            type: 'meeting'
        }));

        // Apply year filter first
        if (yearFilter) {
            events = events.filter(event => {
                const eventDate = new Date(event.start);
                const eventYear = eventDate.getFullYear();
                return eventYear == yearFilter;
            });
        }

        // Apply month filter
        if (monthFilter) {
            events = events.filter(event => {
                const eventDate = new Date(event.start);
                const eventMonth = eventDate.getMonth() + 1;
                return eventMonth == monthFilter;
            });
        }

        // Apply search filter
        if (searchFilter) {
            events = events.filter(event => {
                return event.title.toLowerCase().includes(searchFilter) ||
                    (event.extendedProps?.description || '').toLowerCase().includes(searchFilter);
            });
        }

        // Sort by date
        events.sort((a, b) => new Date(a.start) - new Date(b.start));

        // Render table
        let html = '';
        if (events.length === 0) {
            html = '<tr><td colspan="8" class="text-center py-5">No meeting events found.</td></tr>';
        } else {
            events.forEach((event, index) => {
                const props = event.extendedProps || {};
                const startDate = formatDate(event.start);
                const startTime = formatTime(event.start);
                const endDate = event.end ? formatDate(event.end) : startDate;
                const endTime = event.end ? formatTime(event.end) : startTime;
                const department = props.department_name || props.department || props.branch_name ||
                    'All Departments';
                const description = props.description || (props.title || event.title) ||
                    'No description';

                html += `
                    <tr class="event-item meeting" data-event-id="${event.id}" data-event-type="meeting" style="cursor:pointer;">
                        <td>${index + 1}</td>
                        <td><strong>${event.title || 'Untitled'}</strong></td>
                        <td>${startDate}</td>
                        <td>${endDate}</td>
                         <td>${startTime}</td>
                        <td>${endTime}</td>
                        <td>${department}</td>
                        <td>${description.substring(0, 100)}${description.length > 100 ? '...' : ''}</td>
                    </tr>
                `;
            });
        }

        $('#meetingEventsTableBody').html(html);

        // Add click event to view event details
        $('#meetingEventsTableBody .event-item').click(function() {
            const eventId = $(this).data('event-id');
            const event = allEventsData.meetings.find(e => e.id === eventId);
            if (event) showEventDetailsInModal(event);
        });
    }

    function renderAcademicEventsTable() {
        const yearFilter = $('#academicYearFilter').val();
        const monthFilter = $('#academicMonthFilter').val();
        const searchFilter = $('#academicSearchInput').val().toLowerCase();

        let events = allEventsData.academicEvents.map(e => ({
            ...e,
            type: 'academic'
        }));

        // Apply year filter first
        if (yearFilter) {
            events = events.filter(event => {
                const eventDate = new Date(event.start);
                const eventYear = eventDate.getFullYear();
                return eventYear == yearFilter;
            });
        }

        // Apply month filter
        if (monthFilter) {
            events = events.filter(event => {
                const eventDate = new Date(event.start);
                const eventMonth = eventDate.getMonth() + 1;
                return eventMonth == monthFilter;
            });
        }

        // Apply search filter
        if (searchFilter) {
            events = events.filter(event => {
                return event.title.toLowerCase().includes(searchFilter) ||
                    (event.extendedProps?.description || '').toLowerCase().includes(searchFilter);
            });
        }

        // Sort by date
        events.sort((a, b) => new Date(a.start) - new Date(b.start));

        // Render table
        let html = '';
        if (events.length === 0) {
            html = '<tr><td colspan="8" class="text-center py-5">No academic events found.</td></tr>';
        } else {
            events.forEach((event, index) => {
                const props = event.extendedProps || {};
                const startDate = formatDate(event.start);
                const startTime = formatTime(event.start);
                const endDate = event.end ? formatDate(event.end) : startDate;
                const endTime = event.end ? formatTime(event.end) : startTime;
                const department = props.department_name || props.department || props.branch_name ||
                    'All Departments';
                const description = props.description || (props.title || event.title) ||
                    'No description';

                html += `
                    <tr class="event-item academic" data-event-id="${event.id}" data-event-type="academic" style="cursor:pointer;">
                        <td>${index + 1}</td>
                        <td><strong>${event.title || 'Untitled'}</strong></td>
                        <td>${startDate}</td>
                        <td>${endDate}</td>
                        <td>${startTime}</td>
                        <td>${endTime}</td>
                        <td>${department}</td>
                        <td>${description.substring(0, 100)}${description.length > 100 ? '...' : ''}</td>
                    </tr>
                `;
            });
        }

        $('#academicEventsTableBody').html(html);

        // Add click event to view event details
        $('#academicEventsTableBody .event-item').click(function() {
            const eventId = $(this).data('event-id');
            const event = allEventsData.academicEvents.find(e => e.id === eventId);
            if (event) showEventDetailsInModal(event);
        });
    }

    function renderExamEventsTable() {
        const yearFilter = $('#examYearFilter').val();
        const monthFilter = $('#examMonthFilter').val();
        const searchFilter = $('#examSearchInput').val().toLowerCase();

        let events = allEventsData.exams.map(e => ({
            ...e,
            type: 'exam'
        }));

        // Apply year filter first
        if (yearFilter) {
            events = events.filter(event => {
                const eventDate = new Date(event.start);
                const eventYear = eventDate.getFullYear();
                return eventYear == yearFilter;
            });
        }

        // Apply month filter
        if (monthFilter) {
            events = events.filter(event => {
                const eventDate = new Date(event.start);
                const eventMonth = eventDate.getMonth() + 1;
                return eventMonth == monthFilter;
            });
        }

        // Apply search filter
        if (searchFilter) {
            events = events.filter(event => {
                return event.title.toLowerCase().includes(searchFilter) ||
                    (event.extendedProps?.description || '').toLowerCase().includes(searchFilter);
            });
        }

        // Sort by date
        events.sort((a, b) => new Date(a.start) - new Date(b.start));

        // Render table
        let html = '';
        if (events.length === 0) {
            html = '<tr><td colspan="7" class="text-center py-5">No exam events found.</td></tr>';
        } else {
            events.forEach((event, index) => {
                const props = event.extendedProps || {};
                const startDate = formatDate(event.start);
                const endDate = event.end ? formatDate(event.end) : startDate;

                // Combine course and stream for class name
                const course = props.course_name || props.course || '';
                const stream = props.stream_name || props.stream || '';
                const className = course && stream ? `${course}(${stream})` : (course || stream ||
                    'N/A');

                const subjectName = props.subject_name || props.subject || 'N/A';
                const status = (props.is_published == 1) ?
                    '<span class="badge bg-success">Published</span>' :
                    '<span class="badge bg-warning">Draft</span>';

                html += `
                    <tr class="event-item exam" data-event-id="${event.id}" data-event-type="exam" style="cursor:pointer;">
                        <td>${index + 1}</td>
                        <td><strong>${event.title || 'Untitled'}</strong></td>
                        <td>${startDate}</td>
                        <td>${endDate}</td>
                        <td>${className}</td>
                        <td>${subjectName}</td>
                        <td>${status}</td>
                    </tr>
                `;
            });
        }

        $('#examEventsTableBody').html(html);

        // Add click event to view event details
        $('#examEventsTableBody .event-item').click(function() {
            const eventId = $(this).data('event-id');
            const event = allEventsData.exams.find(e => e.id === eventId);
            if (event) showEventDetailsInModal(event);
        });
    }

    function renderBirthdayEventsTable() {
        const yearFilter = $('#birthdayYearFilter').val();
        const monthFilter = $('#birthdayMonthFilter').val();
        const searchFilter = $('#birthdaySearchInput').val().toLowerCase();

        let events = allEventsData.birthdays.map(e => ({
            ...e,
            type: 'birthday'
        }));

        // Apply year filter first
        if (yearFilter) {
            events = events.filter(event => {
                const eventDate = new Date(event.start);
                const eventYear = eventDate.getFullYear();
                return eventYear == yearFilter;
            });
        }

        // Apply month filter
        if (monthFilter) {
            events = events.filter(event => {
                const eventDate = new Date(event.start);
                const eventMonth = eventDate.getMonth() + 1;
                return eventMonth == monthFilter;
            });
        }

        // Apply search filter
        if (searchFilter) {
            events = events.filter(event => {
                return event.title.toLowerCase().includes(searchFilter) ||
                    (event.extendedProps?.employee_name || '').toLowerCase().includes(searchFilter);
            });
        }

        // Sort by date
        events.sort((a, b) => new Date(a.start) - new Date(b.start));

        // Render table
        let html = '';
        if (events.length === 0) {
            html = '<tr><td colspan="5" class="text-center py-5">No birthday events found.</td></tr>';
        } else {
            events.forEach((event, index) => {
                const props = event.extendedProps || {};
                const employeeName = props.employee_name || event.title || 'Unknown';
                const designation = props.designation || 'N/A';
                const birthDate = formatDate(event.start);
                const age = props.age || 'N/A';

                html += `
                    <tr class="event-item birthday" data-event-id="${event.id}" data-event-type="birthday" style="cursor:pointer;">
                        <td>${index + 1}</td>
                        <td><strong>${employeeName}</strong></td>
                        <td>${designation}</td>
                        <td>${birthDate}</td>
                        <td>${age}</td>
                    </tr>
                `;
            });
        }

        $('#birthdayEventsTableBody').html(html);

        // Add click event to view event details
        $('#birthdayEventsTableBody .event-item').click(function() {
            const eventId = $(this).data('event-id');
            const event = allEventsData.birthdays.find(e => e.id === eventId);
            if (event) showEventDetailsInModal(event);
        });
    }

    function renderOtherEventsTable() {
        const yearFilter = $('#otherYearFilter').val();
        const monthFilter = $('#otherMonthFilter').val();
        const searchFilter = $('#otherSearchInput').val().toLowerCase();

        let events = allEventsData.other.map(e => ({
            ...e,
            type: 'other'
        }));

        // Apply year filter first
        if (yearFilter) {
            events = events.filter(event => {
                const eventDate = new Date(event.start);
                const eventYear = eventDate.getFullYear();
                return eventYear == yearFilter;
            });
        }

        // Apply month filter
        if (monthFilter) {
            events = events.filter(event => {
                const eventDate = new Date(event.start);
                const eventMonth = eventDate.getMonth() + 1;
                return eventMonth == monthFilter;
            });
        }

        // Apply search filter
        if (searchFilter) {
            events = events.filter(event => {
                return event.title.toLowerCase().includes(searchFilter) ||
                    (event.extendedProps?.description || '').toLowerCase().includes(searchFilter);
            });
        }

        // Sort by date
        events.sort((a, b) => new Date(a.start) - new Date(b.start));

        // Render table
        let html = '';
        if (events.length === 0) {
            html = '<tr><td colspan="6" class="text-center py-5">No other events found.</td></tr>';
        } else {
            events.forEach((event, index) => {
                const props = event.extendedProps || {};
                const startDate = formatDate(event.start);
                const endDate = event.end ? formatDate(event.end) : startDate;
                const department = props.department_name || props.department || props.branch_name ||
                    'All Departments';
                const description = props.description || (props.title || event.title) ||
                    'No description';

                html += `
                    <tr class="event-item other" data-event-id="${event.id}" data-event-type="other" style="cursor:pointer;">
                        <td>${index + 1}</td>
                        <td><strong>${event.title || 'Untitled'}</strong></td>
                        <td>${startDate}</td>
                        <td>${endDate}</td>
                        <td>${department}</td>
                        <td>${description.substring(0, 100)}${description.length > 100 ? '...' : ''}</td>
                    </tr>
                `;
            });
        }

        $('#otherEventsTableBody').html(html);

        // Add click event to view event details
        $('#otherEventsTableBody .event-item').click(function() {
            const eventId = $(this).data('event-id');
            const event = allEventsData.other.find(e => e.id === eventId);
            if (event) showEventDetailsInModal(event);
        });
    }

    // Auto-sync holidays function
    async function autoSyncHolidays() {
        const year = currentYear;
        try {
            const response = await fetch('{{ route("google.calendar.sync.holidays") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    year: year
                })
            });
            const data = await response.json();
            if (data.success && data.data && data.data.newly_added > 0) {
                showAutoSyncNotification(`📅 ${data.data.newly_added} new holidays added for ${year}`,
                    'success');
                refreshCalendar();
            }
        } catch (error) {
            console.error('Auto-sync failed:', error);
        }
    }

    // Initialize Calendar
    function initCalendar() {
        if (!calendarEl) return;

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            buttonText: {
                today: 'Today',
                month: 'Month',
                week: 'Week',
                list: 'List'
            },
            height: 'auto',
            events: function(fetchInfo, successCallback, failureCallback) {
                showLoading();
                const params = {
                    category: filterCategory ? filterCategory.value : '',
                    department_id: filterDepartment ? filterDepartment.value : '',
                    designation: filterDesignation ? filterDesignation.value : '',
                    calendar_start: fetchInfo.startStr,
                    calendar_end: fetchInfo.endStr
                };

                $.ajax({
                    url: '{{ route("institute-admin.google-calendar.events") }}',
                    data: params,
                    method: 'GET',
                    success: function(response) {
                        let events = response.events || [];
                        if (eventCountSpan) eventCountSpan.textContent = events.length;

                        const styledEvents = events.map(event => {
                            let className = '';
                            const props = event.extendedProps || {};
                            switch (props.category) {
                                case 'holiday':
                                    className = 'fc-event-holiday';
                                    break;
                                case 'birthday':
                                    className = 'fc-event-birthday';
                                    break;
                                case 'exam':
                                    className = 'fc-event-exam';
                                    break;
                                case 'academic':
                                    className = 'fc-event-academic';
                                    break;
                                case 'meeting':
                                    className = 'fc-event-meeting';
                                    break;
                                default:
                                    className = 'fc-event-other';
                            }
                            return {
                                ...event,
                                className: className
                            };
                        });
                        successCallback(styledEvents);
                        hideLoading();
                    },
                    error: function(error) {
                        console.error('Calendar events error:', error);
                        successCallback([]);
                        hideLoading();
                    }
                });
            },
           eventClick: function(info) {
        const event = info.event;
        // Call the same showEventDetailsInModal function that's used everywhere else
        showEventDetailsInModal(event);
    }
            });

        calendar.render();
    }

    // Holiday Management Functions
    let currentHolidaysPage = 1;
    let holidaysData = [];

    async function loadHolidays(page = 1) {
        currentHolidaysPage = page;
        const status = $('#manageStatusFilter').val();
        const year = currentYear;
        const search = $('#manageSearchInput').val();

        showLoading();
        try {
            const response = await fetch(
                `{{ route("institute-admin.holidays.list") }}?page=${page}&status=${status}&year=${year}&search=${encodeURIComponent(search)}`
            );
            const data = await response.json();

            if (data.success) {
                holidaysData = data.holidays.data;
                renderHolidaysTable(holidaysData);
                renderHolidaysPagination(data.holidays);
            }
        } catch (error) {
            console.error('Error loading holidays:', error);
            showAlert('Failed to load holidays', 'error');
        } finally {
            hideLoading();
        }
    }

    function renderHolidaysTable(holidays) {
        let html = '';
        holidays.forEach(holiday => {
            const statusClass = holiday.status === 'active' ? 'success' : 'secondary';
            const statusText = holiday.status === 'active' ? 'Active' : 'Inactive';
            html += `
                <tr>
                    <td><input type="checkbox" class="holiday-checkbox" data-id="${holiday.holiday_event_id}"></td>
                    <td><strong>${holiday.title}</strong></td>
                    <td>${formatDate(holiday.start_date)}</td>
                    <td>${formatDate(holiday.end_date)}</td>
                    <td>
                        <span class="badge bg-${statusClass}">${statusText}</span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary edit-holiday" data-id="${holiday.holiday_event_id}" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-${holiday.status === 'active' ? 'warning' : 'success'} toggle-holiday" data-id="${holiday.holiday_event_id}" data-status="${holiday.status}">
                            <i class="fas fa-${holiday.status === 'active' ? 'ban' : 'check-circle'}"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        if (holidays.length === 0) {
            html =
                '<tr><td colspan="6" class="text-center py-5">No holidays found. Try syncing from API or add manually.</td></tr>';
        }

        $('#holidaysTableBody').html(html);

        // Bind events
        $('.edit-holiday').click(function() {
            editHoliday($(this).data('id'));
        });
        $('.toggle-holiday').click(function() {
            toggleHoliday($(this).data('id'), $(this).data('status'));
        });
        $('.delete-holiday').click(function() {
            deleteHoliday($(this).data('id'));
        });
    }

    function renderHolidaysPagination(pagination) {
        let html = '';
        if (pagination.last_page > 1) {
            html += '<nav><ul class="pagination">';
            html += `<li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${pagination.current_page - 1}">&laquo;</a>
                     </li>`;
            for (let i = 1; i <= pagination.last_page; i++) {
                if (i >= pagination.current_page - 2 && i <= pagination.current_page + 2) {
                    html += `<li class="page-item ${i === pagination.current_page ? 'active' : ''}">
                                <a class="page-link" href="#" data-page="${i}">${i}</a>
                             </li>`;
                }
            }
            html += `<li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${pagination.current_page + 1}">&raquo;</a>
                     </li>`;
            html += '</ul></nav>';
        }
        $('#holidaysPagination').html(html);

        $('.page-link').click(function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (page && !isNaN(page)) {
                loadHolidays(page);
            }
        });
    }

    async function toggleHoliday(id, currentStatus) {
        const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        const action = newStatus === 'active' ? 'activate' : 'deactivate';

        if (confirm(`Are you sure you want to ${action} this holiday?`)) {
            showLoading();
            try {
                const response = await fetch(`{{ route("institute-admin.holidays.toggle", "") }}/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await response.json();
                if (data.success) {
                    showAlert(data.message, 'success');
                    loadHolidays(currentHolidaysPage);
                    refreshCalendar();
                } else {
                    showAlert(data.message || 'Failed to update holiday', 'error');
                }
            } catch (error) {
                showAlert('Error updating holiday', 'error');
            } finally {
                hideLoading();
            }
        }
    }

    async function deleteHoliday(id) {
        if (confirm('Are you sure you want to delete this holiday? This action cannot be undone.')) {
            showLoading();
            try {
                const response = await fetch(`{{ route("institute-admin.holidays.delete", "") }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await response.json();
                if (data.success) {
                    showAlert(data.message, 'success');
                    loadHolidays(currentHolidaysPage);
                    refreshCalendar();
                } else {
                    showAlert(data.message || 'Failed to delete holiday', 'error');
                }
            } catch (error) {
                showAlert('Error deleting holiday', 'error');
            } finally {
                hideLoading();
            }
        }
    }

    async function editHoliday(id) {
        const holiday = holidaysData.find(h => h.holiday_event_id === id);
        if (holiday) {
            $('#editHolidayId').val(holiday.holiday_event_id);
            $('#editTitle').val(holiday.title);
            $('#editStartDate').val(holiday.start_date);
            $('#editEndDate').val(holiday.end_date);
            $('#editColor').val(holiday.color || '#0d0666');
            $('#editColorText').text(holiday.color || '#0d0666');
            $('#editDescription').val(holiday.description || '');
            $('#editHolidayModal').modal('show');
        }
    }

    $('#editHolidayForm').submit(async function(e) {
        e.preventDefault();
        const id = $('#editHolidayId').val();
        const data = {
            title: $('#editTitle').val(),
            start_date: $('#editStartDate').val(),
            end_date: $('#editEndDate').val(),
            color: $('#editColor').val(),
            description: $('#editDescription').val()
        };

        showLoading();
        try {
            const response = await fetch(`{{ route("institute-admin.holidays.edit", "") }}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.success) {
                $('#editHolidayModal').modal('hide');
                showAlert(result.message, 'success');
                loadHolidays(currentHolidaysPage);
                refreshCalendar();
            } else {
                showAlert(result.message || 'Failed to update holiday', 'error');
            }
        } catch (error) {
            showAlert('Error updating holiday', 'error');
        } finally {
            hideLoading();
        }
    });

    // Bulk actions
    async function bulkUpdateHolidays(status) {
        const selectedIds = $('.holiday-checkbox:checked').map(function() {
            return $(this).data('id');
        }).get();

        if (selectedIds.length === 0) {
            showAlert('Please select at least one holiday', 'warning');
            return;
        }

        if (confirm(
                `Are you sure you want to ${status === 'active' ? 'activate' : 'deactivate'} ${selectedIds.length} holiday(s)?`
            )) {
            showLoading();
            try {
                const response = await fetch('{{ route("institute-admin.holidays.bulk-update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        holiday_ids: selectedIds,
                        status: status
                    })
                });
                const data = await response.json();
                if (data.success) {
                    showAlert(data.message, 'success');
                    loadHolidays(currentHolidaysPage);
                    refreshCalendar();
                    $('#selectAllHolidays').prop('checked', false);
                } else {
                    showAlert(data.message || 'Failed to update holidays', 'error');
                }
            } catch (error) {
                showAlert('Error updating holidays', 'error');
            } finally {
                hideLoading();
            }
        }
    }

    // Resync holidays
    async function resyncHolidays() {
        if (confirm(
                'This will fetch new holidays from the API and update the current year list. Continue?')) {
            showLoading();
            try {
                const year = currentYear;
                const response = await fetch('{{ route("institute-admin.holidays.resync") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        year: year,
                        deactivate_old: true
                    })
                });
                const data = await response.json();
                if (data.success) {
                    showAlert(data.message, 'success');
                    loadHolidays(1);
                    refreshCalendar();
                } else {
                    showAlert(data.message || 'Failed to resync holidays', 'error');
                }
            } catch (error) {
                showAlert('Error resyncing holidays', 'error');
            } finally {
                hideLoading();
            }
        }
    }

    // Event Listeners for filters
    if (filterCategory) filterCategory.addEventListener('change', () => refreshCalendar());
    if (filterDepartment) filterDepartment.addEventListener('change', () => refreshCalendar());
    if (filterDesignation) filterDesignation.addEventListener('change', () => refreshCalendar());
    if (filterYear) filterYear.addEventListener('change', () => {
        refreshCalendar();
        autoSyncHolidays();
    });
    if (filterClearBtn) {
        filterClearBtn.addEventListener('click', () => {
            if (filterCategory) filterCategory.value = '';
            if (filterDepartment) filterDepartment.value = '';
            if (filterDesignation) filterDesignation.value = '';
            refreshCalendar();
            showAlert('Filters cleared', 'info');
        });
    }

    // Event type buttons - open respective modals
    $('#holidayEventsBtn').click(async function() {
        const year = $('#holidayYearFilter').val() || currentYear;
        showLoading();
        await fetchAllEventsForYear(year);
        renderHolidayEventsTable();
        hideLoading();
        $('#holidayEventsModal').modal('show');
    });

    $('#meetingEventsBtn').click(async function() {
        const year = $('#meetingYearFilter').val() || currentYear;
        showLoading();
        await fetchAllEventsForYear(year);
        renderMeetingEventsTable();
        hideLoading();
        $('#meetingEventsModal').modal('show');
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

    $('#birthdayEventsBtn').click(async function() {
        const year = $('#birthdayYearFilter').val() || currentYear;
        showLoading();
        await fetchAllEventsForYear(year);
        renderBirthdayEventsTable();
        hideLoading();
        $('#birthdayEventsModal').modal('show');
    });

    $('#otherEventsBtn').click(async function() {
        const year = $('#otherYearFilter').val() || currentYear;
        showLoading();
        await fetchAllEventsForYear(year);
        renderOtherEventsTable();
        hideLoading();
        $('#otherEventsModal').modal('show');
    });

    // Filter and search handlers for each modal
    // Holiday Events
    $('#holidayYearFilter, #holidayMonthFilter, #holidaySearchInput').on('change keyup', async function() {
        const year = $('#holidayYearFilter').val();
        if ($(this).attr('id') === 'holidayYearFilter') {
            showLoading();
            await fetchAllEventsForYear(year);
            hideLoading();
        }
        renderHolidayEventsTable();
    });
    $('#resetHolidayFilters').click(function() {
        $('#holidayYearFilter').val('{{ $currentYear }}');
        $('#holidayMonthFilter').val('');
        $('#holidaySearchInput').val('');
        renderHolidayEventsTable();
    });
    $('#refreshHolidayEventsBtn').click(async function() {
        const year = $('#holidayYearFilter').val();
        showLoading();
        await fetchAllEventsForYear(year);
        renderHolidayEventsTable();
        hideLoading();
        showAlert('Holiday events refreshed!', 'success');
    });

    // Meeting Events
    $('#meetingYearFilter, #meetingMonthFilter, #meetingSearchInput').on('change keyup', async function() {
        const year = $('#meetingYearFilter').val();
        if ($(this).attr('id') === 'meetingYearFilter') {
            showLoading();
            await fetchAllEventsForYear(year);
            hideLoading();
        }
        renderMeetingEventsTable();
    });
    $('#resetMeetingFilters').click(function() {
        $('#meetingYearFilter').val('{{ $currentYear }}');
        $('#meetingMonthFilter').val('');
        $('#meetingSearchInput').val('');
        renderMeetingEventsTable();
    });
    $('#refreshMeetingEventsBtn').click(async function() {
        const year = $('#meetingYearFilter').val();
        showLoading();
        await fetchAllEventsForYear(year);
        renderMeetingEventsTable();
        hideLoading();
        showAlert('Meeting events refreshed!', 'success');
    });

    // Academic Events
    $('#academicYearFilter, #academicMonthFilter, #academicSearchInput').on('change keyup', async function() {
        const year = $('#academicYearFilter').val();
        if ($(this).attr('id') === 'academicYearFilter') {
            showLoading();
            await fetchAllEventsForYear(year);
            hideLoading();
        }
        renderAcademicEventsTable();
    });
    $('#resetAcademicFilters').click(function() {
        $('#academicYearFilter').val('{{ $currentYear }}');
        $('#academicMonthFilter').val('');
        $('#academicSearchInput').val('');
        renderAcademicEventsTable();
    });
    $('#refreshAcademicEventsBtn').click(async function() {
        const year = $('#academicYearFilter').val();
        showLoading();
        await fetchAllEventsForYear(year);
        renderAcademicEventsTable();
        hideLoading();
        showAlert('Academic events refreshed!', 'success');
    });

    // Exam Events
    $('#examYearFilter, #examMonthFilter, #examSearchInput').on('change keyup', async function() {
        const year = $('#examYearFilter').val();
        if ($(this).attr('id') === 'examYearFilter') {
            showLoading();
            await fetchAllEventsForYear(year);
            hideLoading();
        }
        renderExamEventsTable();
    });
    $('#resetExamFilters').click(function() {
        $('#examYearFilter').val('{{ $currentYear }}');
        $('#examMonthFilter').val('');
        $('#examSearchInput').val('');
        renderExamEventsTable();
    });
    $('#refreshExamEventsBtn').click(async function() {
        const year = $('#examYearFilter').val();
        showLoading();
        await fetchAllEventsForYear(year);
        renderExamEventsTable();
        hideLoading();
        showAlert('Exam events refreshed!', 'success');
    });

    // Birthday Events
    $('#birthdayYearFilter, #birthdayMonthFilter, #birthdaySearchInput').on('change keyup', async function() {
        const year = $('#birthdayYearFilter').val();
        if ($(this).attr('id') === 'birthdayYearFilter') {
            showLoading();
            await fetchAllEventsForYear(year);
            hideLoading();
        }
        renderBirthdayEventsTable();
    });
    $('#resetBirthdayFilters').click(function() {
        $('#birthdayYearFilter').val('{{ $currentYear }}');
        $('#birthdayMonthFilter').val('');
        $('#birthdaySearchInput').val('');
        renderBirthdayEventsTable();
    });
    $('#refreshBirthdayEventsBtn').click(async function() {
        const year = $('#birthdayYearFilter').val();
        showLoading();
        await fetchAllEventsForYear(year);
        renderBirthdayEventsTable();
        hideLoading();
        showAlert('Birthday events refreshed!', 'success');
    });

    // Other Events
    $('#otherYearFilter, #otherMonthFilter, #otherSearchInput').on('change keyup', async function() {
        const year = $('#otherYearFilter').val();
        if ($(this).attr('id') === 'otherYearFilter') {
            showLoading();
            await fetchAllEventsForYear(year);
            hideLoading();
        }
        renderOtherEventsTable();
    });
    $('#resetOtherFilters').click(function() {
        $('#otherYearFilter').val('{{ $currentYear }}');
        $('#otherMonthFilter').val('');
        $('#otherSearchInput').val('');
        renderOtherEventsTable();
    });
    $('#refreshOtherEventsBtn').click(async function() {
        const year = $('#otherYearFilter').val();
        showLoading();
        await fetchAllEventsForYear(year);
        renderOtherEventsTable();
        hideLoading();
        showAlert('Other events refreshed!', 'success');
    });

    // Add target audience filter
    const filterAudience = document.getElementById('filterAudience');
    if (filterAudience) {
        filterAudience.addEventListener('change', () => refreshCalendar());
    }

    // In the filterClearBtn click handler, add:
    if (filterAudience) filterAudience.value = '';

    // Form submission for adding events
    $('#eventForm').submit(function(e) {
        e.preventDefault();

        // Get form data
        const scope = $('#scope').val();
        const targetAudience = $('#target_audience').val();

        if (!scope) {
            showAlert('Please select a scope (Overall or Department-wise)', 'error');
            return;
        }

        if (!targetAudience) { // Add validation
            showAlert('Please select target audience (Students/Employees/Both)', 'error');
            return;
        }

        // Build form data
        const formData = {
            title: $('#title').val(),
            type: $('#type').val(),
            target_audience: targetAudience,
            scope: scope,
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            start_time: $('#start_time').val() || null,
            end_time: $('#end_time').val() || null,
            color: $('#color').val(),
            description: $('#description').val(),
            is_recurring: $('#is_recurring').is(':checked') ? 1 : 0,
            recurring_type: $('#recurring_type').val()
        };

        // Validate required fields
        if (!formData.title) {
            showAlert('Please enter an event title', 'error');
            return;
        }

        if (!formData.type) {
            showAlert('Please select an event type', 'error');
            return;
        }

        if (!formData.start_date) {
            showAlert('Please select a start date', 'error');
            return;
        }

        if (!formData.end_date) {
            showAlert('Please select an end date', 'error');
            return;
        }

        // Add department fields only if scope is department_wise
        if (scope === 'department_wise') {
            const categoryId = $('#department_category_id').val();
            const departmentId = $('#department_id').val();

            if (!categoryId || !departmentId) {
                showAlert('Please select both department category and department', 'error');
                return;
            }

            formData.department_category_id = categoryId;
            formData.department_id = departmentId;

            console.log('Department Wise Event Data:', {
                category_id: categoryId,
                department_id: departmentId,
                category_name: $('#department_category_id option:selected').text(),
                department_name: $('#department_id option:selected').text()
            });
        } else {
            // For overall scope, explicitly set these to null
            formData.department_category_id = null;
            formData.department_id = null;
        }

        console.log('Submitting event data:', formData);

        showLoading();
        $.ajax({
            url: '{{ route("institute-admin.holiday-events.store") }}',
            method: 'POST',
            data: JSON.stringify(formData), // Stringify the data
            contentType: 'application/json', // Set content type
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#eventModal').modal('hide');
                    showAlert('Event saved successfully!', 'success');
                    refreshCalendar();
                    $('#eventForm')[0].reset();
                    if (eventTypeSelect && timeFields) toggleTimeFields();
                    // Reset department fields
                    $('#department_category_id').val('');
                    $('#department_id').html(
                        '<option value="">-- Select Department --</option>');
                    $('#departmentFields').hide();
                    // Reset scope to default
                    $('#scope').val('');
                } else {
                    showAlert(response.message || 'Failed to save event', 'error');
                }
                hideLoading();
            },
            error: function(xhr) {
                console.error('Error response:', xhr);
                let errorMsg = 'Error saving event';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg += ': ' + JSON.stringify(xhr.responseJSON.errors);
                }
                showAlert(errorMsg, 'error');
                hideLoading();
            }
        });
    });

    // Event listeners for holiday management
    $('#selectAllHolidays').change(function() {
        $('.holiday-checkbox').prop('checked', $(this).is(':checked'));
    });

    $('#bulkActivateBtn').click(() => bulkUpdateHolidays('active'));
    $('#bulkDeactivateBtn').click(() => bulkUpdateHolidays('inactive'));
    $('#resyncHolidaysBtn').click(resyncHolidays);
    $('#refreshHolidaysBtn').click(() => loadHolidays(currentHolidaysPage));
    $('#manageStatusFilter, #manageSearchInput').on('change keyup', () => loadHolidays(1));

    // Manage Holidays button inside the holiday modal opens the management modal
    $('#openManageHolidaysBtn').click(() => {
        loadHolidays(1);
        $('#holidayManagementModal').modal('show');
    });

    // Color picker update
    $('#editColor').on('input', function() {
        $('#editColorText').text($(this).val());
    });

    // Set default dates for event form
    const today = new Date().toISOString().split('T')[0];
    if ($('#start_date').length) $('#start_date').val(today);
    if ($('#end_date').length) $('#end_date').val(today);

    // Initialize everything
    initCalendar();

    // Run auto-sync on page load
    setTimeout(() => {
        autoSyncHolidays();
    }, 1000);
});
</script>

@endsection