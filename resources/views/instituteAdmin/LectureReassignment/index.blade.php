@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('title', 'Lecture Reassignment')
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
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --card-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        --hover-shadow: 0 15px 40px rgba(67, 97, 238, 0.15);
    }

    h1, h2, h3, h4, h5, h6 {
        font-weight: 600;
        line-height: 1.25;
    }

    /* Page Header */
    .page-header {
        background: var(--primary-gradient);
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 15px 35px rgba(67, 97, 238, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-header h1 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
        color: white;
        font-size: 1.75rem;
    }

    .page-header h1 span {
        background: rgba(255,255,255,0.2);
        padding: 10px;
        border-radius: 12px;
        font-size: 1.5rem;
    }

    .page-header p {
        opacity: 0.9;
        font-size: 1rem;
        margin: 0;
    }

    .header-btn {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 2px solid rgba(255,255,255,0.3);
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .header-btn:hover {
        background: white;
        color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .btn-primary {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
    }

    .btn-outline {
        background: transparent;
        border: 2px solid var(--border-color);
        color: var(--text-dark);
    }

    .btn-outline:hover {
        background: #f1f5f9;
        border-color: var(--primary-color);
    }

    .btn-warning {
        background: var(--warning-gradient);
        color: white;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: var(--text-dark);
        border: 2px solid var(--border-color);
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    /* Form Controls */
    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        font-size: 14px;
        color: var(--text-dark);
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        font-size: 14px;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    select.form-control {
        cursor: pointer;
        background: white;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 20px;
    }

    .badge-purple {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
    }

    .badge-blue {
        background: var(--info-gradient);
        color: white;
    }

    .badge-info {
        background: var(--info-gradient);
        color: white;
    }

    .badge-success {
        background: var(--success-gradient);
        color: white;
    }

    .badge-danger {
        background: var(--danger-gradient);
        color: white;
    }

    .badge-secondary {
        background: linear-gradient(135deg, #64748b, #475569);
        color: white;
    }

    /* Cards */
    .card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        border: 2px solid var(--border-color);
    }

    .card-header {
        padding: 18px 24px;
        border-bottom: 2px solid var(--border-color);
        background: white;
    }

    .card-body {
        padding: 24px;
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        padding: 24px;
        margin-bottom: 24px;
        border: 2px solid var(--border-color);
    }

    .filter-card h5 {
        color: var(--primary-color);
        font-weight: 700;
    }

    /* Employee Grid */
    .employees-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
        margin-top: 16px;
    }

    .employee-card {
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 16px;
        padding: 16px;
        transition: all 0.3s ease;
    }

    .employee-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--hover-shadow);
        border-color: var(--primary-color);
    }

    .employee-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .employee-avatar {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: var(--primary-gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }

    .employee-info {
        flex: 1;
    }

    .employee-name {
        font-weight: 600;
        margin-bottom: 4px;
        color: var(--text-dark);
    }

    .employee-code {
        font-size: 12px;
        color: var(--text-muted);
    }

    .employee-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-present {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .status-absent {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .status-leave {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    /* Schedule Card */
    .schedule-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        margin-top: 24px;
        scroll-margin-top: 80px;
        border: 2px solid var(--border-color);
    }

    .schedule-header {
        background: var(--primary-gradient);
        color: white;
        padding: 18px 24px;
    }

    .schedule-table {
        width: 100%;
        border-collapse: collapse;
    }

    .schedule-table th {
        text-align: left;
        padding: 14px 16px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        font-weight: 700;
        font-size: 13px;
        color: var(--primary-color);
        border-bottom: 2px solid var(--border-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .schedule-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border-color);
        font-size: 14px;
    }

    .schedule-table tr:hover {
        background: rgba(67, 97, 238, 0.02);
    }

    .time-slot {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #e0f2fe, #bae6fd);
        color: #0369a1;
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .modal-container {
        background: white;
        border-radius: 20px;
        max-width: 900px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        transform: translateY(-20px);
        transition: transform 0.3s ease;
        box-shadow: 0 25px 50px rgba(0,0,0,0.2);
    }

    .modal-overlay.active .modal-container {
        transform: translateY(0);
    }

    .modal-header {
        background: var(--primary-gradient);
        color: white;
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 20px 20px 0 0;
    }

    .modal-close {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        padding: 0;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.3s;
    }

    .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 24px;
    }

    .modal-footer {
        padding: 16px 24px;
        border-top: 2px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    /* Alert */
    .alert {
        padding: 14px 18px;
        border-radius: 14px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        border: none;
    }

    .alert-success {
        background: var(--success-gradient);
        color: white;
    }

    .alert-warning {
        background: var(--warning-gradient);
        color: white;
    }

    .alert-danger {
        background: var(--danger-gradient);
        color: white;
    }

    /* Loading Spinner */
    .spinner {
        display: inline-block;
        width: 40px;
        height: 40px;
        border: 3px solid var(--border-color);
        border-top-color: var(--primary-color);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Grid System */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin: -12px;
    }

    .col-md-4 { flex: 0 0 33.333%; padding: 12px; }
    .col-md-5 { flex: 0 0 41.666%; padding: 12px; }
    .col-md-2 { flex: 0 0 16.666%; padding: 12px; }
    .col-12 { flex: 0 0 100%; padding: 12px; }

    /* Utilities */
    .text-center { text-align: center; }
    .text-muted { color: var(--text-muted); }
    .fw-bold { font-weight: 600; }
    .mb-0 { margin-bottom: 0; }
    .mb-2 { margin-bottom: 8px; }
    .mb-3 { margin-bottom: 12px; }
    .mb-4 { margin-bottom: 24px; }
    .mt-1 { margin-top: 4px; }
    .mt-2 { margin-top: 8px; }
    .mt-3 { margin-top: 12px; }
    .me-2 { margin-right: 8px; }
    .w-100 { width: 100%; }
    .d-flex { display: flex; }
    .align-items-center { align-items: center; }
    .justify-content-between { justify-content: space-between; }
    .flex-column { flex-direction: column; }
    .py-5 { padding-top: 48px; padding-bottom: 48px; }
    .gap-2 { gap: 8px; }
    .p-0 { padding: 0; }

    .schedule-card { scroll-margin-top: 80px; }

    .schedule-highlight {
        animation: highlightPulse 0.6s ease-in-out 2;
    }

    @keyframes highlightPulse {
        0% { box-shadow: 0 0 0 0 rgba(67, 97, 238, 0.4); }
        50% { box-shadow: 0 0 0 12px rgba(67, 97, 238, 0.1); }
        100% { box-shadow: 0 0 0 0 rgba(67, 97, 238, 0); }
    }

    @media (max-width: 768px) {
        .col-md-4, .col-md-5, .col-md-2 { flex: 0 0 100%; }
        .employees-grid { grid-template-columns: 1fr; }
        .schedule-table { font-size: 12px; }
        .schedule-table th, .schedule-table td { padding: 10px; }
        .container-fluid { padding: 0 16px; }
        .page-header { flex-direction: column; text-align: center; }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1><span>📋</span> Lecture Reassignment</h1>
            <p>Reassign lectures to other available employees</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="header-btn" id="scrollToScheduleBtn" style="display: none;">
                📅 Jump to Schedule
            </button>
            <a href="{{ route('institute-admin.calendar.index') }}" class="header-btn">
                ← Back
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <h5 class="mb-3 fw-bold">🔍 Select Department & Date</h5>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Department <span style="color: #ef4444;">*</span></label>
                    <select class="form-control" id="department_id">
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->department_id }}">{{ $department->department }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Date <span style="color: #ef4444;">*</span></label>
                    <input type="date" class="form-control" id="date" value="{{ date('Y-m-d') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Employees Section -->
    <div id="employeesSection" style="display: none;">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold" style="color: var(--primary-color);">👥 Employees with Attendance Status</h5>
            </div>
            <div class="card-body">
                <div id="employeesGrid" class="employees-grid">
                    <div class="text-center py-5">
                        <div class="spinner"></div>
                        <p class="text-muted mt-2">Loading employees...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Section -->
    <div id="scheduleSection" style="display: none;">
        <div class="schedule-card" id="scheduleCard">
            <div class="schedule-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">📅 Lecture Schedule</h5>
                    <span id="selectedEmployeeInfo" class="badge" style="background: rgba(255,255,255,0.2); color: white;"></span>
                </div>
            </div>
            <div class="card-body p-0">
                <div style="overflow-x: auto;">
                    <table class="schedule-table">
                        <thead>
                            <tr><th>Subject Details</th><th>Time</th><th>Duration</th><th>Location</th><th>Status</th><th>Action</th></tr>
                        </thead>
                        <tbody id="scheduleTableBody">
                            <tr><td colspan="6" class="text-center py-5">📅 Select an employee to view schedule</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reassign Modal -->
<div id="reassignModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h5 class="mb-0 text-white">🔄 Reassign Lecture</h5>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="reassignForm">
                @csrf
                <input type="hidden" id="reassign_lecture_id" name="lecture_id">
                <input type="hidden" id="reassign_current_employee_id" name="current_employee_id">
                <input type="hidden" id="reassign_start_time" name="start_time">
                <input type="hidden" id="reassign_end_time" name="end_time">
                
                <div class="row">
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header" style="background: rgba(245, 158, 11, 0.1);">
                                <span style="color: #92400e; font-weight: 600;">📖 Original Lecture Details</span>
                            </div>
                            <div class="card-body" id="originalLectureDetails"></div>
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="d-flex flex-column h-100 justify-content-center">
                            <span style="font-size: 32px; color: var(--primary-color);">→</span>
                            <span class="badge" style="background: var(--info-gradient); color: white; margin-top: 8px;">Reassign To</span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header" style="background: rgba(16, 185, 129, 0.1);">
                                <span style="color: #166534; font-weight: 600;">👤 New Assignment</span>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">Select Employee <span style="color: #ef4444;">*</span></label>
                                    <select class="form-control" id="reassign_new_employee_id" name="new_employee_id">
                                        <option value="">-- Select Employee --</option>
                                    </select>
                                    <small class="text-muted">⏰ Showing employees available at the same time slot</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <div class="card">
                        <div class="card-header" style="background: rgba(67, 97, 238, 0.1);">
                            <span style="color: var(--primary-color); font-weight: 600;">💬 Reassignment Reason <span style="color: #ef4444;">*</span></span>
                        </div>
                        <div class="card-body">
                            <textarea class="form-control" id="reassign_reason" name="reason" rows="3" placeholder="Please provide a detailed reason for reassigning this lecture (minimum 10 characters)..."></textarea>
                        </div>
                    </div>
                </div>
                
                <div id="availabilityCheckResult" style="display: none;"></div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            <button type="button" class="btn btn-primary" id="confirmReassignBtn">Confirm Reassignment</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let currentEmployeeId = null;
let currentEmployeeName = null;
let autoLoadTimer = null;

$(document).ready(function() {
    loadEmployeesAutomatically();
    $('#department_id, #date').on('change', function() {
        loadEmployeesAutomatically();
        $('#scheduleSection').hide();
        $('#scrollToScheduleBtn').hide();
    });
    
    $('#scrollToScheduleBtn').on('click', function() {
        smoothScrollToSchedule();
    });
});

function smoothScrollToSchedule() {
    const scheduleElement = document.getElementById('scheduleCard');
    if (scheduleElement) {
        scheduleElement.scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'nearest' });
        scheduleElement.classList.add('schedule-highlight');
        setTimeout(() => { scheduleElement.classList.remove('schedule-highlight'); }, 1000);
    }
}

function loadEmployeesAutomatically() {
    const departmentId = $('#department_id').val();
    const date = $('#date').val();
    
    if (autoLoadTimer) clearTimeout(autoLoadTimer);
    
    if (!departmentId || !date) {
        $('#employeesSection').hide();
        $('#scheduleSection').hide();
        $('#scrollToScheduleBtn').hide();
        return;
    }
    
    $('#employeesSection').show();
    $('#employeesGrid').html(`<div class="text-center py-5"><div class="spinner"></div><p class="text-muted mt-2">Loading employees...</p></div>`);
    $('#scheduleSection').hide();
    $('#scrollToScheduleBtn').hide();
    
    autoLoadTimer = setTimeout(function() {
        $.ajax({
            url: '{{ route("institute.lecture-reassignment.employees") }}',
            method: 'POST',
            data: { department_id: departmentId, date: date, _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) displayEmployees(response.employees);
                else $('#employeesGrid').html(`<div class="text-center py-5"><p class="text-muted">❌ Failed to load employees.</p></div>`);
            },
            error: function() {
                $('#employeesGrid').html(`<div class="text-center py-5"><p class="text-danger">⚠️ Error loading employees.</p></div>`);
            }
        });
    }, 300);
}

function displayEmployees(employees) {
    if (employees.length === 0) {
        $('#employeesGrid').html(`<div class="text-center py-5"><p class="text-muted">👥 No employees found.</p></div>`);
        return;
    }
    
    let html = '';
    employees.forEach(emp => {
        let statusClass = '', statusIcon = '';
        switch(emp.status) {
            case 'present': statusClass = 'status-present'; statusIcon = '✅'; break;
            case 'absent': statusClass = 'status-absent'; statusIcon = '❌'; break;
            case 'on_leave': statusClass = 'status-leave'; statusIcon = '🏖️'; break;
        }
        
        html += `
            <div class="employee-card" data-employee-id="${emp.employee_id}" data-employee-name="${escapeHtml(emp.name)}">
                <div class="employee-header">
                    <div class="employee-avatar">${emp.name.charAt(0).toUpperCase()}</div>
                    <div class="employee-info">
                        <div class="employee-name">${escapeHtml(emp.name)}</div>
                        <div class="employee-code">${escapeHtml(emp.employee_code)}</div>
                    </div>
                    <div class="employee-status ${statusClass}">${statusIcon} ${emp.status_label}</div>
                </div>
                <button class="btn btn-primary w-100 view-schedule-btn" data-id="${emp.employee_id}" data-name="${escapeHtml(emp.name)}">📅 View Schedule</button>
            </div>
        `;
    });
    
    $('#employeesGrid').html(html);
    
    $('.view-schedule-btn').on('click', function() {
        const empId = $(this).data('id');
        const empName = $(this).data('name');
        viewSchedule(empId, empName);
    });
}

function viewSchedule(employeeId, employeeName) {
    currentEmployeeId = employeeId;
    currentEmployeeName = employeeName;
    
    Swal.fire({ 
        title: 'Loading Schedule...', 
        text: `Fetching schedule for ${employeeName}...`, 
        allowOutsideClick: false, 
        didOpen: () => Swal.showLoading() 
    });
    
    $.ajax({
        url: '{{ route("institute.lecture-reassignment.schedule") }}',
        method: 'POST',
        data: { employee_id: employeeId, date: $('#date').val(), _token: '{{ csrf_token() }}' },
        success: function(response) {
            Swal.close();
            if (response.success) {
                displaySchedule(response.lectures);
                $('#selectedEmployeeInfo').html(`👤 ${escapeHtml(employeeName)} | 📅 ${response.selected_date_formatted} | 📚 ${response.total_lectures} lectures`);
                $('#scheduleSection').show();
                $('#scrollToScheduleBtn').show();
                setTimeout(() => { smoothScrollToSchedule(); }, 300);
            } else {
                Swal.fire('Error', response.message || 'Failed to load schedule', 'error');
            }
        },
        error: function() {
            Swal.close();
            Swal.fire('Error', 'Failed to load schedule', 'error');
        }
    });
}

function displaySchedule(lectures) {
    if (lectures.length === 0) {
        $('#scheduleTableBody').html(`<tr><td colspan="6" class="text-center py-5">📭 No lectures scheduled for this date</td></tr>`);
        return;
    }
    
    window.currentLectures = lectures;
    
    let html = '';
    lectures.forEach(lecture => {
        let actionButton = '';
        let statusBadge = '';
        
        if (!lecture.is_cancelled && !lecture.is_reassigned_from_me && lecture.status === 'active') {
            actionButton = `<button class="btn btn-warning reassign-btn" data-lecture-id="${lecture.id}">🔄 Reassign</button>`;
        } else if (lecture.is_reassigned_from_me) {
            statusBadge = '<span class="badge badge-info">Reassigned</span>';
            actionButton = '<span class="text-muted">Already Reassigned</span>';
        } else if (lecture.is_cancelled) {
            statusBadge = '<span class="badge badge-danger">Cancelled</span>';
            actionButton = '<span class="text-muted">Cancelled</span>';
        } else {
            actionButton = '<span class="text-muted">N/A</span>';
        }
        
        html += `
            <tr data-lecture-id="${lecture.id}">
                <td>
                    <strong>${escapeHtml(lecture.subject_name)}</strong><br>
                    <small class="text-muted">🏢 ${escapeHtml(lecture.department)} | 📚 ${escapeHtml(lecture.course)}${lecture.section_name ? '<br>👥 Section: ' + escapeHtml(lecture.section_name) : ''}</small>
                    <div class="mt-1">
                        <span class="badge ${lecture.subject_type === 'sub_subject' ? 'badge-purple' : 'badge-blue'}">🏷️ ${lecture.subject_type_label}</span>
                        <span class="badge badge-secondary">📊 ${lecture.frequency_label}</span>
                        ${statusBadge}
                    </div>
                </td>
                <td><span class="time-slot">⏰ ${lecture.start_time_formatted} - ${lecture.end_time_formatted}</span></td>
                <td>${lecture.duration_formatted}</td>
                <td>${lecture.location ? '📍 ' + escapeHtml(lecture.location) : 'Not specified'}</td>
                <td><span class="badge badge-success">Active</span></td>
                <td>${actionButton}</td>
            </tr>
        `;
    });
    
    $('#scheduleTableBody').html(html);
    
    $('.reassign-btn').on('click', function() {
        const lectureId = $(this).data('lecture-id');
        const lecture = window.currentLectures.find(l => l.id == lectureId);
        if (lecture) openReassignModal(lecture);
    });
}

function openReassignModal(lectureData) {
    const selectedDate = $('#date').val();
    
    $('#reassignForm')[0].reset();
    $('#availabilityCheckResult').hide().html('');
    
    $('#reassign_lecture_id').val(lectureData.id);
    $('#reassign_current_employee_id').val(currentEmployeeId);
    $('#reassign_start_time').val(lectureData.start_time ? lectureData.start_time.substring(0, 5) : '');
    $('#reassign_end_time').val(lectureData.end_time ? lectureData.end_time.substring(0, 5) : '');
    
    $('#originalLectureDetails').html(`
        <div>
            <div class="mb-2"><strong>📖 Subject:</strong> ${escapeHtml(lectureData.subject_name)}</div>
            <div class="mb-2"><strong>📅 Date:</strong> ${selectedDate}</div>
            <div class="mb-2"><strong>⏰ Time:</strong> ${lectureData.start_time_formatted} - ${lectureData.end_time_formatted}</div>
            <div class="mb-2"><strong>📍 Location:</strong> ${lectureData.location || 'Not specified'}</div>
            <div class="mb-2"><strong>📊 Frequency:</strong> ${lectureData.frequency_label || 'N/A'}</div>
            <div class="mb-2"><strong>👤 Current Employee:</strong> ${escapeHtml(currentEmployeeName)}</div>
            ${lectureData.section_name ? `<div class="mb-2"><strong>👥 Section:</strong> ${escapeHtml(lectureData.section_name)}</div>` : ''}
        </div>
    `);
    
    loadAvailableEmployees(selectedDate, lectureData.start_time, lectureData.end_time);
    document.getElementById('reassignModal').classList.add('active');
}

function loadAvailableEmployees(date, startTime, endTime) {
    const departmentId = $('#department_id').val();
    let cleanStartTime = startTime && startTime.length > 5 ? startTime.substring(0, 5) : startTime;
    let cleanEndTime = endTime && endTime.length > 5 ? endTime.substring(0, 5) : endTime;
    
    Swal.fire({ title: 'Finding Available Employees...', text: 'Please wait...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
    
    $.ajax({
        url: '{{ route("institute.lecture-reassignment.available-employees") }}',
        method: 'POST',
        data: { date: date, start_time: cleanStartTime, end_time: cleanEndTime, department_id: departmentId, exclude_employee_id: currentEmployeeId, _token: '{{ csrf_token() }}' },
        success: function(response) {
            Swal.close();
            
            if (response.success) {
                let options = '<option value="">-- Select Employee --</option>';
                
                if (response.available_employees.length === 0) {
                    options += '<option value="" disabled>❌ No available employees found</option>';
                    $('#availabilityCheckResult').html(`<div class="alert alert-warning">⚠️ No employees available at ${cleanStartTime} - ${cleanEndTime}.</div>`).show();
                    $('#confirmReassignBtn').prop('disabled', true);
                } else {
                    $('#confirmReassignBtn').prop('disabled', false);
                    response.available_employees.forEach(emp => {
                        options += `<option value="${emp.employee_id}">${escapeHtml(emp.name)} ${emp.is_present ? '(✓ Present Today)' : ''}</option>`;
                    });
                    $('#availabilityCheckResult').html(`<div class="alert alert-success">✅ Found ${response.total_available} employee(s) available.</div>`).show();
                }
                $('#reassign_new_employee_id').html(options);
            } else {
                $('#availabilityCheckResult').html(`<div class="alert alert-danger">❌ ${response.message || 'Failed to load available employees'}</div>`).show();
                $('#confirmReassignBtn').prop('disabled', true);
            }
        },
        error: function() {
            Swal.close();
            $('#availabilityCheckResult').html(`<div class="alert alert-danger">❌ Failed to load available employees.</div>`).show();
            $('#confirmReassignBtn').prop('disabled', true);
        }
    });
}

$('#confirmReassignBtn').click(function() {
    const lectureId = $('#reassign_lecture_id').val();
    const newEmployeeId = $('#reassign_new_employee_id').val();
    const date = $('#date').val();
    const reason = $('#reassign_reason').val();
    const startTime = $('#reassign_start_time').val();
    const endTime = $('#reassign_end_time').val();
    
    if (!newEmployeeId) { Swal.fire('Error', 'Please select a new employee', 'error'); return; }
    if (!reason || reason.length < 10) { Swal.fire('Error', 'Please provide a detailed reason (minimum 10 characters)', 'error'); return; }
    
    const newEmployeeName = $('#reassign_new_employee_id option:selected').text();
    
    Swal.fire({
        title: 'Confirm Reassignment',
        html: `<div style="text-align:left;"><p>Are you sure?</p><hr><p><strong>Original Employee:</strong> ${currentEmployeeName}</p><p><strong>New Employee:</strong> ${newEmployeeName}</p><p><strong>Date:</strong> ${date}</p><p><strong>Time:</strong> ${startTime} - ${endTime}</p><p><strong>Reason:</strong> ${escapeHtml(reason)}</p></div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, reassign it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({ title: 'Reassigning...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            
            $.ajax({
                url: '{{ route("institute.lecture-reassignment.reassign") }}',
                method: 'POST',
                data: { lecture_id: lectureId, current_employee_id: currentEmployeeId, new_employee_id: newEmployeeId, date: date, start_time: startTime, end_time: endTime, reason: reason, _token: '{{ csrf_token() }}' },
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        Swal.fire({ title: 'Success!', html: `Lecture successfully reassigned!<br><br><strong>New employee:</strong> ${response.reassignment_details.new_employee}`, icon: 'success' }).then(() => {
                            closeModal();
                            if (currentEmployeeId) viewSchedule(currentEmployeeId, currentEmployeeName);
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    Swal.fire('Error', xhr.responseJSON?.message || 'Failed to reassign lecture', 'error');
                }
            });
        }
    });
});

function closeModal() {
    document.getElementById('reassignModal').classList.remove('active');
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text).replace(/[&<>"']/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        if (m === '"') return '&quot;';
        return '&#039;';
    });
}
</script>
@endsection