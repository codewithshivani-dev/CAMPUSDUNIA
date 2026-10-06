@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('styles')
<style>
    /* ===== STUDENT OVERRIDES ===== */
    
    /* Change logo color to student green */
    .logo-icon {
        background: linear-gradient(135deg, #10b981, #059669) !important;
    }
    .logo-text span { color: #10b981 !important; }

    /* Student badge in top bar */
    .student-badge-header {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-left: 12px;
    }
    .student-badge-header i { font-size: 12px; }

    /* Hide teacher actions */
    .teacher-only {
        display: none !important;
    }

    /* Student nav active color */
    .nav-tab.active {
        background: white;
        color: #10b981 !important;
        box-shadow: 0 2px 12px rgba(16, 185, 129, 0.15) !important;
    }

    /* Hide new plan button for students */
    .btn-new {
        display: none !important;
    }

    /* Student info banner */
    .student-info-banner {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border: 1px solid #10b981;
        border-radius: 16px;
        padding: 16px 24px;
        margin: 0 32px 24px 32px;
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .student-info-banner i {
        font-size: 28px;
        color: #059669;
    }
    .student-info-banner .content {
        flex: 1;
    }
    .student-info-banner .content h4 {
        font-size: 16px;
        font-weight: 600;
        color: #0a1e3c;
        margin: 0;
    }
    .student-info-banner .content p {
        font-size: 14px;
        color: #0b6e4f;
        margin: 2px 0 0 0;
    }
    .student-info-banner .readonly-badge {
        background: #10b981;
        color: white;
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }
    .student-info-banner .readonly-badge i { font-size: 12px; color: white; }

    .student-details-page {
        padding: 28px 32px;
        background: #f0f4f9;
        min-height: 100vh;
    }

    .detail-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }
    
    .back-link {
        color: #10b981;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: white;
        border-radius: 12px;
        border: 1px solid #e6ecf3;
        transition: all 0.2s;
    }
    .back-link:hover {
        background: #f0fdf4;
        border-color: #10b981;
    }
    .back-link i { font-size: 14px; }

    /* Student Header */
    .detail-heading {
        background: white;
        border: 1px solid #e6ecf3;
        border-radius: 24px;
        padding: 24px 28px;
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .student-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 24px;
        flex-shrink: 0;
    }
    .detail-heading .info h1 {
        color: #0a1e3c;
        font-size: 22px;
        margin: 0 0 4px;
    }
    .detail-heading .info .meta {
        color: #4b6a8b;
        font-size: 13px;
        margin: 0;
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }
    .detail-heading .info .meta i {
        color: #10b981;
        margin-right: 4px;
    }
    .detail-heading .context {
        margin-left: auto;
        color: #4b6a8b;
        font-size: 13px;
        text-align: right;
        background: #f8fafc;
        padding: 8px 16px;
        border-radius: 12px;
    }
    .detail-heading .context strong {
        display: block;
        color: #0a1e3c;
        font-size: 15px;
        margin-top: 2px;
    }

    /* Stats Cards */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }
    .stat-card {
        background: white;
        padding: 14px 18px;
        border-radius: 16px;
        border: 1px solid #e6ecf3;
        text-align: center;
    }
    .stat-card .number {
        font-size: 28px;
        font-weight: 700;
        display: block;
    }
    .stat-card .label {
        font-size: 12px;
        color: #8a9bb5;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .stat-card .number.green { color: #10b981; }
    .stat-card .number.red { color: #ef4444; }
    .stat-card .number.blue { color: #10b981; }

    /* Detail Card */
    .detail-card {
        background: white;
        border: 1px solid #e6ecf3;
        border-radius: 24px;
        overflow: hidden;
    }

    .detail-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 16px 24px;
        border-bottom: 1px solid #e6ecf3;
        flex-wrap: wrap;
        background: #fafcfe;
    }
    .detail-controls label {
        color: #4b6a8b;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .detail-controls select {
        padding: 8px 14px;
        border: 2px solid #e6ecf3;
        border-radius: 10px;
        color: #0a1e3c;
        background: white;
        font-weight: 500;
        cursor: pointer;
    }
    .detail-controls select:focus {
        outline: none;
        border-color: #10b981;
    }

    /* Table */
    .table-responsive {
        overflow-x: auto;
        padding: 0 4px 4px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        min-width: 750px;
    }
    th {
        background: #f8fafc;
        color: #4b6a8b;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 12px 16px;
        text-align: left;
        border-bottom: 2px solid #e6ecf3;
        position: sticky;
        top: 0;
        z-index: 5;
    }
    th i { color: #10b981; margin-right: 4px; }
    td {
        padding: 12px 16px;
        border-bottom: 1px solid #eff3f8;
        color: #0a1e3c;
        vertical-align: top;
    }
    tr:hover td {
        background: #f8fafc;
    }
    tr:last-child td {
        border-bottom: none;
    }

    /* Date Column */
    .date-cell {
        font-weight: 700;
        min-width: 70px;
        white-space: nowrap;
    }
    .date-cell .day-name {
        display: block;
        color: #8a9bb5;
        font-size: 11px;
        font-weight: 400;
        margin-top: 2px;
    }

    /* Presence Badge */
    .presence-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }
    .presence-badge.present {
        background: #d1fae5;
        color: #047857;
    }
    .presence-badge.absent {
        background: #fee2e2;
        color: #b91c1c;
    }
    .presence-badge.no-class {
        background: #f1f5f9;
        color: #64748b;
    }
    .presence-badge.na {
        background: #fef3c7;
        color: #92400e;
    }

    /* Materials Column */
    .materials-container {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .material-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 12px;
        background: #f8fafc;
        border-radius: 8px;
        border-left: 3px solid transparent;
        flex-wrap: wrap;
    }
    .material-row.type-video {
        border-left-color: #10b981;
        background: #f0fdf4;
    }
    .material-row.type-link {
        border-left-color: #10b981;
        background: #f0fdf4;
    }

    .material-row .mat-icon {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }
    .material-row .mat-icon.video {
        background: #d1fae5;
        color: #10b981;
    }
    .material-row .mat-icon.link {
        background: #d1fae5;
        color: #10b981;
    }

    .material-row .mat-name {
        flex: 1;
        font-weight: 500;
        font-size: 13px;
        color: #0a1e3c;
        min-width: 150px;
        text-decoration: none;
    }
    .material-row .mat-name:hover {
        color: #10b981;
        text-decoration: underline;
    }

    .material-row .mat-clicks {
        font-size: 12px;
        font-weight: 600;
        color: #4b6a8b;
        background: #e6ecf3;
        padding: 2px 12px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }
    .material-row .mat-clicks i {
        color: #10b981;
        font-size: 11px;
    }
    .material-row .mat-clicks.high {
        background: #d1fae5;
        color: #065f46;
    }
    .material-row .mat-clicks.high i {
        color: #10b981;
    }
    .material-row .mat-clicks.low {
        background: #fee2e2;
        color: #991b1b;
    }
    .material-row .mat-clicks.low i {
        color: #ef4444;
    }

    .material-row .mat-status {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 12px;
        border-radius: 12px;
        white-space: nowrap;
    }
    .material-row .mat-status.viewed {
        background: #d1fae5;
        color: #065f46;
    }
    .material-row .mat-status.not-viewed {
        background: #f1f5f9;
        color: #64748b;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #8a9bb5;
    }
    .empty-state .icon {
        font-size: 48px;
        margin-bottom: 12px;
        color: #10b981;
        opacity: 0.5;
    }
    .empty-state .sub {
        color: #b0c0d0;
        font-size: 14px;
    }

    /* Alert Warning */
    .alert-warning {
        margin-bottom: 20px;
        padding: 15px 20px;
        background: #fef3c7;
        border-radius: 12px;
        border-left: 4px solid #f59e0b;
        color: #92400e;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .alert-warning i {
        color: #f59e0b;
        font-size: 18px;
    }

    /* Responsive */
    @media (max-width: 700px) {
        .student-details-page {
            padding: 16px;
        }
        .detail-toolbar {
            flex-direction: column;
            align-items: stretch;
        }
        .detail-heading {
            flex-direction: column;
            align-items: flex-start;
            padding: 18px;
        }
        .detail-heading .context {
            margin-left: 0;
            text-align: left;
            width: 100%;
        }
        .detail-controls {
            flex-direction: column;
            align-items: stretch;
            padding: 14px 16px;
        }
        .stats-row {
            grid-template-columns: 1fr 1fr 1fr;
        }
        td {
            padding: 10px 12px;
        }
        .material-row {
            padding: 4px 8px;
            gap: 6px;
        }
        .material-row .mat-name {
            min-width: 100px;
            font-size: 12px;
        }
        .material-row .mat-clicks {
            font-size: 11px;
            padding: 1px 8px;
        }
        .student-info-banner { margin: 0 16px 16px 16px; padding: 14px 18px; }
    }

    @media (max-width: 480px) {
        .stats-row {
            grid-template-columns: 1fr 1fr;
        }
        .date-cell {
            min-width: 55px;
            font-size: 12px;
        }
        .presence-badge {
            font-size: 11px;
            padding: 3px 10px;
        }
        .student-info-banner { flex-direction: column; text-align: center; }
        .student-info-banner i { font-size: 24px; }
    }
</style>
@endsection

@php 
    $studentName = trim($student->first_name . ' ' . ($student->middle_name ?: '') . ' ' . $student->last_name);
    $initials = collect(explode(' ', $studentName))
        ->filter()
        ->map(fn ($name) => strtoupper(substr($name, 0, 1)))
        ->take(2)
        ->implode('');
@endphp

@section('content')
<!-- ===== TOP BAR WITH STUDENT OVERRIDE ===== -->
@php
    $plannerUser = auth()->user();
    $plannerIsAdmin = $plannerUser && $plannerUser->hasAnyRole(['admin', 'superadmin', 'super_admin', 'institute_admin']);
    $plannerEmployees = $plannerIsAdmin
        ? \App\Models\EmployeeDetails::where('institute_id', $plannerUser->institute_id)->orderBy('name')->get()
        : collect();
    $plannerDepartments = $plannerIsAdmin
        ? \App\Models\Departments::where('institute_id', $plannerUser->institute_id)->orderBy('department')->get()
        : collect();
    $plannerSelectedEmployee = $plannerEmployees->firstWhere('employee_id', request('employee_id'));
    $plannerSelectedDepartmentId = request('department_id') ?: optional($plannerSelectedEmployee)->department_id;
    $plannerContextQuery = request()->only(['department_id', 'employee_id']);
@endphp
<div class="top-bar" style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; background:#ffffff; padding:14px 28px; border-radius:60px; box-shadow:0 4px 20px rgba(37,99,235,0.08); margin:20px 32px 16px 32px; border:1px solid rgba(37,99,235,0.06);">
    <div class="logo-area" style="display:flex; align-items:center; gap:12px; flex:0 0 auto;">
        <div class="logo-icon" style="width:44px; height:44px; background:linear-gradient(135deg, #10b981, #059669); border-radius:14px; display:flex; align-items:center; justify-content:center; color:white; font-size:20px;">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <span class="logo-text" style="font-weight:800; font-size:22px; letter-spacing:-0.3px; color:#0a1e3c;">View<span style="color:#10b981;">Plans</span></span>
        <span class="student-badge-header"><i class="fas fa-user-graduate"></i> Student View</span>
    </div>
    <div class="nav-wrapper" style="display:flex; gap:8px; align-items:center; flex-wrap:nowrap; justify-content:flex-end; flex:1 1 auto; min-width:0;">
        @if($plannerIsAdmin)
            <label class="employee-selector" for="planner-employee" style="display:flex; align-items:center; gap:8px; padding:6px 12px; border:1px solid #dce4ed; border-radius:12px; background:#f8fafc; color:#4b6a8b; font-size:12px;">
                <i class="fas fa-user-tie" style="color:#10b981;"></i>
                <span>Department</span>
                <select id="planner-department" onchange="filterPlannerEmployees(this.value)" style="width:125px !important; padding:6px 8px; border:0; background:transparent; color:#0a1e3c; font:inherit; outline:none; cursor:pointer;">
                    <option value="">Select department</option>
                    @foreach($plannerDepartments as $plannerDepartment)
                        <option value="{{ $plannerDepartment->department_id }}" @if((string) $plannerSelectedDepartmentId === (string) $plannerDepartment->department_id) selected @endif>
                            {{ $plannerDepartment->department }}
                        </option>
                    @endforeach
                </select>
            </label>
            <label class="employee-selector" for="planner-employee" style="display:flex; align-items:center; gap:8px; padding:6px 12px; border:1px solid #dce4ed; border-radius:12px; background:#f8fafc; color:#4b6a8b; font-size:12px;">
                <i class="fas fa-user-tie" style="color:#10b981;"></i>
                <span>Employee</span>
                <select id="planner-employee" onchange="changePlannerEmployee(this.value)" disabled style="width:125px !important; padding:6px 8px; border:0; background:transparent; color:#0a1e3c; font:inherit; outline:none; cursor:pointer;">
                    <option value="">Select employee</option>
                    @foreach($plannerEmployees as $plannerEmployee)
                        @php
                            $plannerEmployeeName = $plannerEmployee->name;
                            if (!$plannerEmployeeName) {
                                $plannerEmployeeName = $plannerEmployee->employee_id;
                            }
                        @endphp
                        <option value="{{ $plannerEmployee->employee_id }}" data-department="{{ $plannerEmployee->department_id }}" {{ (string) request('employee_id') === (string) $plannerEmployee->employee_id ? 'selected' : '' }}>
                            {{ $plannerEmployeeName }}
                        </option>
                    @endforeach
                </select>
            </label>
        @endif
        <div class="nav-tabs" style="display:flex; background:#f1f5f9; padding:5px; border-radius:60px; gap:2px; flex:0 1 auto; min-width:0;">
            <a href="{{ route('student.lesson-planner.dashboard') }}" class="nav-tab" style="border:none; background:transparent; padding:8px 10px; border-radius:60px; font-weight:500; font-size:14px; color:#4b6a8b; cursor:pointer; transition:0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="{{ route('student.lesson-planner.plans') }}" class="nav-tab" style="border:none; background:transparent; padding:8px 10px; border-radius:60px; font-weight:500; font-size:14px; color:#4b6a8b; cursor:pointer; transition:0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
                <i class="fas fa-calendar-alt"></i> All Plans
            </a>
            <a href="{{ route('student.lesson-planner.coverage') }}" class="nav-tab" style="border:none; background:transparent; padding:8px 10px; border-radius:60px; font-weight:500; font-size:14px; color:#4b6a8b; cursor:pointer; transition:0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
                <i class="fas fa-chart-pie"></i> Coverage
            </a>
            <a href="{{ route('student.lesson-planner.performance') }}" class="nav-tab active" style="border:none; background:transparent; padding:8px 10px; border-radius:60px; font-weight:500; font-size:14px; color:#4b6a8b; cursor:pointer; transition:0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:8px; background:white; color:#10b981; box-shadow:0 2px 12px rgba(16,185,129,0.15);">
                <i class="fas fa-chart-line"></i> Performance
            </a>
        </div>
        <!-- No New Plan button for students -->
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function filterPlannerEmployees(departmentId) {
        const employeeSelect = document.getElementById('planner-employee');
        const selectedEmployeeId = @json(request('employee_id'));
        employeeSelect.disabled = !departmentId;

        Array.from(employeeSelect.options).forEach(option => {
            const isEmployee = option.value !== '';
            const matchesDepartment = option.dataset.department === departmentId;
            option.hidden = isEmployee && !matchesDepartment;
            if (isEmployee && !matchesDepartment) option.selected = false;
        });

        const selectedOption = selectedEmployeeId
            ? employeeSelect.querySelector(`option[value="${selectedEmployeeId}"]`)
            : null;
        employeeSelect.value = selectedOption && !selectedOption.hidden ? selectedEmployeeId : '';
    }

    function changePlannerEmployee(employeeId) {
        const url = new URL(window.location.href);
        if (employeeId) {
            url.searchParams.set('employee_id', employeeId);
            url.searchParams.set('department_id', document.getElementById('planner-department').value);
        } else {
            url.searchParams.delete('employee_id');
        }
        window.location.href = url.toString();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const departmentSelect = document.getElementById('planner-department');
        if (departmentSelect) filterPlannerEmployees(departmentSelect.value);
    });
</script>

<!-- Student Info Banner -->
<div class="student-info-banner">
    <i class="fas fa-info-circle"></i>
    <div class="content">
        <h4><i class="fas fa-graduation-cap"></i> Student View</h4>
        <p>You are viewing lesson plans in <strong>read-only</strong> mode. You can view all plans and track coverage but cannot create or edit plans.</p>
    </div>
    <span class="readonly-badge">
        <i class="fas fa-eye"></i> Read Only
    </span>
</div>

<div class="student-details-page">
    <!-- Toolbar -->
    <div class="detail-toolbar">
        <a class="back-link" href="{{ route('student.lesson-planner.performance') }}">
            <i class="fas fa-arrow-left"></i> Back to Performance
        </a>
    </div>

    <!-- Student Header -->
    <section class="detail-heading">
        <div class="student-avatar">{{ $initials ?: 'S' }}</div>
        <div class="info">
            <h1 id="student-name">{{ $studentName }}</h1>
            <p class="meta">
                <span><i class="fas fa-id-badge"></i> Reg. No.: {{ $student->registration_number ?: 'Not available' }}</span>
                @if($student->email)
                <span><i class="fas fa-envelope"></i> {{ $student->email }}</span>
                @endif
            </p>
        </div>
        <div class="context">
            Department & Course
            <strong>{{ $courseType ?: 'Not available' }}</strong>
            <div style="font-size: 11px; margin-top: 4px; color: #4b6a8b;">
                Subject: {{ $subject->subject_name ?? 'Not available' }}
            </div>
        </div>
    </section>

    <!-- Stats Cards -->
    <div class="stats-row">
        <div class="stat-card">
            <span class="number blue" id="total-days">0</span>
            <span class="label">Total Days</span>
        </div>
        <div class="stat-card">
            <span class="number green" id="present-count">0</span>
            <span class="label">Present</span>
        </div>
        <div class="stat-card">
            <span class="number red" id="absent-count">0</span>
            <span class="label">Absent</span>
        </div>
        <div class="stat-card">
            <span class="number blue" id="attendance-percent">0%</span>
            <span class="label">Attendance</span>
        </div>
        <div class="stat-card">
            <span class="number blue" id="total-materials">0</span>
            <span class="label">Total Materials</span>
        </div>
    </div>

    <!-- Warning for no assignment -->
    @if(isset($noAssignment) && $noAssignment)
    <div class="alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        <span>No active assignment found for this subject. Please contact your instructor.</span>
    </div>
    @endif

    <!-- Detail Card -->
    <section class="detail-card">
        <div class="detail-controls">
            <label>
                <i class="fas fa-calendar-alt"></i> Month
                <select id="month-filter">
                    @for ($month = 1; $month <= 12; $month++)
                        <option value="{{ $month }}" {{ $month == now()->month ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}
                        </option>
                    @endfor
                </select>
            </label>
            <div style="font-size:13px;color:#4b6a8b;">
                <i class="fas fa-info-circle"></i> 
                <span id="teaching-days-label">0 teaching days</span>
            </div>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="min-width:75px;"><i class="fas fa-calendar-day"></i> Date</th>
                        <th style="min-width:70px;"><i class="fas fa-clock"></i> Day</th>
                        <th style="min-width:180px;"><i class="fas fa-heading"></i> Topic</th>
                        <th style="min-width:90px;"><i class="fas fa-user-check"></i> Presence</th>
                        <th><i class="fas fa-file-alt"></i> Materials &amp; Clicks</th>
                    </tr>
                </thead>
                <tbody id="detail-body">
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="icon"><i class="fas fa-calendar-times"></i></div>
                                <div>No data available</div>
                                <div class="sub">Select a month to view student details</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
    // =============================================
    // CONFIGURATION
    // =============================================
    const studentMonths = @json($months);
    const hasNoAssignment = @json(isset($noAssignment) && $noAssignment);

    function renderStudentDetail() {
        const month = parseInt(document.getElementById('month-filter').value, 10);
        const days = studentMonths[month] || [];
        let present = 0, absent = 0, totalMaterials = 0, teachingDays = 0;
        let rows = '';

        // Handle empty state
        if (!days || days.length === 0 || hasNoAssignment) {
            document.getElementById('detail-body').innerHTML = `
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="icon"><i class="fas fa-calendar-times"></i></div>
                            <div>No details available</div>
                            <div class="sub">${hasNoAssignment ? 'No assignment found for this subject' : 'No classes scheduled for this month'}</div>
                        </div>
                    </td>
                </tr>
            `;
            document.getElementById('total-days').textContent = '0';
            document.getElementById('present-count').textContent = '0';
            document.getElementById('absent-count').textContent = '0';
            document.getElementById('attendance-percent').textContent = '0%';
            document.getElementById('total-materials').textContent = '0';
            document.getElementById('teaching-days-label').textContent = '0 teaching days';
            return;
        }

        days.forEach(day => {
            const materials = day.materials || [];
            const topicTitles = day.topics || [];
            const hasSchedule = day.scheduled === true;
            const status = hasSchedule
                ? (day.status === 'present' || day.status === 'absent' ? day.status : 'na')
                : 'no_class';

            if (hasSchedule) {
                teachingDays++;
                if (status === 'present') present++;
                else if (status === 'absent') absent++;
                totalMaterials += materials.length;
            }

            // Build materials HTML
            let materialsHtml = '<div class="materials-container">';
            if (materials.length) {
                materials.forEach(material => {
                    const typeClass = material.type === 'video' ? 'type-video' : 'type-link';
                    const icon = material.type === 'video' ? 'fa-video' : 'fa-link';
                    const iconClass = material.type === 'video' ? 'video' : 'link';
                    const statusClass = material.viewed ? 'viewed' : 'not-viewed';
                    const clickClass = material.clicks >= 4 ? 'high' : (material.clicks === 0 ? 'low' : '');
                    
                    materialsHtml += `
                        <div class="material-row ${typeClass}">
                            <span class="mat-icon ${iconClass}"><i class="fas ${icon}"></i></span>
                            <a class="mat-name" href="${material.url}" target="_blank" rel="noopener">${material.title}</a>
                            <span class="mat-clicks ${clickClass}"><i class="fas fa-mouse-pointer"></i> ${material.clicks} clicks</span>
                            <span class="mat-status ${statusClass}">${material.viewed ? 'Viewed' : 'Not Viewed'}</span>
                        </div>
                    `;
                });
            } else {
                materialsHtml += '<span style="color:#b0c0d0;font-size:12px;">—</span>';
            }
            materialsHtml += '</div>';

            // Status badge
            const statusMap = {
                present: ['present', 'fa-check-circle', 'Present'],
                absent: ['absent', 'fa-times-circle', 'Absent'],
                na: ['na', 'fa-question-circle', 'N/A'],
                no_class: ['no-class', 'fa-minus', 'No Class']
            };
            const statusData = statusMap[status] || statusMap.no_class;

            // Topics HTML
            const topicHtml = topicTitles.length
                ? topicTitles.map(title => `<div style="padding:2px 0;">${title}</div>`).join('')
                : (hasSchedule ? '<span style="color:#b45309;font-size:12px;">Topic not planned for this day</span>' : '<span style="color:#b0c0d0;font-size:12px;">—</span>');

            rows += `
                <tr>
                    <td class="date-cell">${day.day}<span class="day-name">${day.shortDay}</span></td>
                    <td>${day.shortDay}</td>
                    <td>${topicHtml}</td>
                    <td><span class="presence-badge ${statusData[0]}"><i class="fas ${statusData[1]}"></i> ${statusData[2]}</span></td>
                    <td>${materialsHtml}</td>
                </tr>
            `;
        });

        // Update table
        document.getElementById('detail-body').innerHTML = rows || `
            <tr>
                <td colspan="5">
                    <div class="empty-state">
                        <div class="icon"><i class="fas fa-calendar-times"></i></div>
                        <div>No classes for this month</div>
                    </div>
                </td>
            </tr>
        `;

        // Update stats
        const totalDays = present + absent;
        document.getElementById('total-days').textContent = totalDays;
        document.getElementById('present-count').textContent = present;
        document.getElementById('absent-count').textContent = absent;
        document.getElementById('attendance-percent').textContent = totalDays ? Math.round((present / totalDays) * 100) + '%' : '0%';
        document.getElementById('total-materials').textContent = totalMaterials;
        document.getElementById('teaching-days-label').textContent = `${teachingDays} teaching days`;
    }

    // =============================================
    // INITIALIZE PAGE
    // =============================================
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('month-filter').addEventListener('change', renderStudentDetail);
        renderStudentDetail();
    });
</script>
@endsection