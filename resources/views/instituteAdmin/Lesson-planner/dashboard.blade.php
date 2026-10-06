@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('styles')
<style>
    /* ===== GLOBAL RESET & BASE ===== */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background: #f0f4f9;
        color: #0b1a33;
    }

    /* ===== TOP BAR ===== */
    .top-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        padding: 14px 28px;
        border-radius: 60px;
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.08);
        margin: 20px 32px 16px 32px;
        border: 1px solid rgba(37, 99, 235, 0.06);
    }

    .logo-area {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 0 0 auto;
    }
    .logo-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }
    .logo-text {
        font-weight: 800;
        font-size: 22px;
        letter-spacing: -0.3px;
        color: #0a1e3c;
    }
    .logo-text span { color: #2563eb; }

    .nav-wrapper {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: nowrap;
        justify-content: flex-end;
        flex: 1 1 auto;
        min-width: 0;
    }

    .employee-selector {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border: 1px solid #dce4ed;
        border-radius: 12px;
        background: #f8fafc;
        color: #4b6a8b;
        font-size: 12px;
    }
    .employee-selector i { color: #2563eb; }
    .employee-selector select {
        width: 125px !important;
        padding: 6px 8px;
        border: 0;
        background: transparent;
        color: #0a1e3c;
        font: inherit;
        outline: none;
        cursor: pointer;
    }

    .nav-tabs {
        display: flex;
        background: #f1f5f9;
        padding: 5px;
        border-radius: 60px;
        gap: 2px;
        flex: 0 1 auto;
        min-width: 0;
    }

    .nav-tab {
        border: none;
        background: transparent;
        padding: 8px 10px;
        border-radius: 60px;
        font-weight: 500;
        font-size: 14px;
        color: #4b6a8b;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .nav-tab i { font-size: 14px; }
    .nav-tab:hover {
        background: rgba(255, 255, 255, 0.8);
        color: #0a1e3c;
    }
    .nav-tab.active {
        background: white;
        color: #2563eb;
        box-shadow: 0 2px 12px rgba(37, 99, 235, 0.12);
    }

    .btn-new {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border: none;
        color: white;
        padding: 10px 18px;
        border-radius: 60px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex: 0 0 auto;
        white-space: nowrap;
    }
    .btn-new i { font-size: 14px; }
    .btn-new:hover {
        background: linear-gradient(135deg, #1d4ed8, #1a3fb5);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
    }

    /* ===== CONTENT WRAPPER ===== */
    .content-wrapper {
        margin: 0 32px 32px 32px;
        border-radius: 28px;
        overflow: hidden;
        background: #f0f4f9;
        min-height: calc(100vh - 160px);
    }

    /* ===== MODAL STYLES ===== */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(10, 30, 60, 0.5);
        backdrop-filter: blur(8px);
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .modal.active { display: flex; }
    .modal-content {
        background: white;
        border-radius: 32px;
        padding: 32px 36px;
        max-width: 960px;
        width: 94%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        animation: modalSlideIn 0.3s ease;
    }
    @keyframes modalSlideIn {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 24px;
        color: #0a1e3c;
    }
    .modal-header i { color: #2563eb; margin-right: 10px; }
    .close-btn {
        background: none;
        border: none;
        font-size: 32px;
        cursor: pointer;
        color: #8a9bb5;
        line-height: 1;
        transition: 0.2s;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .close-btn:hover { 
        color: #0a1e3c;
        background: #f1f5f9;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px 24px;
    }
    .form-group { margin-bottom: 8px; }
    .form-label {
        display: block;
        font-weight: 500;
        font-size: 14px;
        margin-bottom: 6px;
        color: #0a1e3c;
    }
    .form-label i { color: #2563eb; margin-right: 6px; width: 18px; }
    .form-required { color: #ef4444; }

    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 20px;
        margin-top: 4px;
    }
    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
    }
    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #2563eb;
        cursor: pointer;
    }
    textarea { min-height: 70px; resize: vertical; width: 100%; }

    select, input[type="date"], input[type="text"], input[type="url"], input[type="file"], input[type="number"], textarea {
        padding: 10px 16px;
        border: 1.5px solid #dce4ed;
        border-radius: 40px;
        font-size: 14px;
        background: #fafcff;
        font-family: inherit;
        outline: none;
        transition: 0.2s;
        width: 100%;
    }
    select:focus, input:focus, textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
        background: white;
    }
    input[type="file"] {
        padding: 8px 12px;
        background: #fafcff;
    }

    .success-message {
        background: #d1fae5;
        color: #0b6e4f;
        padding: 14px 22px;
        border-radius: 60px;
        display: none;
        font-weight: 500;
        margin-bottom: 20px;
    }
    .success-message.show { display: block; }

    .action-buttons {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .btn {
        border: none;
        padding: 8px 20px;
        border-radius: 60px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: 0.2s;
        background: #eef2f6;
        color: #1f334f;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn i { font-size: 14px; }
    .btn-primary {
        background: #2563eb;
        color: white;
    }
    .btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); }
    .btn-success {
        background: #10b981;
        color: white;
    }
    .btn-success:hover { background: #059669; transform: translateY(-1px); }
    .btn-danger {
        background: #ef4444;
        color: white;
    }
    .btn-danger:hover { background: #dc2626; transform: translateY(-1px); }
    .btn-secondary {
        background: #e6ecf3;
        color: #1f334f;
    }
    .btn-secondary:hover { background: #d1d9e6; transform: translateY(-1px); }
    .btn-warning {
        background: #f59e0b;
        color: white;
    }
    .btn-warning:hover { background: #d97706; transform: translateY(-1px); }
    .btn-small { padding: 6px 14px; font-size: 12px; }

    .topic-row {
        border: 1.5px solid #dce4ed;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        background: #fafcff;
        transition: 0.2s;
    }
    .topic-row:hover {
        border-color: #2563eb;
        box-shadow: 0 2px 12px rgba(37, 99, 235, 0.06);
    }
    .topic-row .topic-fields {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .topic-row .topic-fields-full {
        grid-column: 1 / -1;
    }

    .topic-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        border-bottom: 1px solid #eef2f6;
        flex-wrap: wrap;
        gap: 8px;
    }
    .topic-item:last-child { border-bottom: none; }
    .topic-number {
        font-weight: 600;
        color: #2563eb;
        min-width: 30px;
    }
    .topic-title { flex: 1; margin: 0 12px; min-width: 100px; }
    .topic-resources {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }
    .topic-resources a {
        color: #2563eb;
        text-decoration: none;
        font-size: 13px;
    }
    .topic-resources .file-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #eef2f6;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 12px;
        color: #3b4e6b;
    }
    .topic-video {
        width: 160px;
        height: 90px;
        border-radius: 8px;
        overflow: hidden;
    }
    .topic-video iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .coverage-badge {
        display: inline-block;
        padding: 2px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }
    .coverage-badge.covered { background: #d1fae5; color: #0b6e4f; }
    .coverage-badge.partial { background: #fef3c7; color: #a16207; }
    .coverage-badge.not-covered { background: #fee2e2; color: #dc2626; }

    .status-badge {
        display: inline-block;
        padding: 4px 16px;
        border-radius: 60px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-draft { background: #eef2f6; color: #4b6a8b; }
    .status-active { background: #d1fae5; color: #0b6e4f; }
    .status-review { background: #fef3c7; color: #a16207; }

    /* ===== PAGE CONTENT ===== */
    .page-content {
        padding: 28px 32px;
        background: #f0f4f9;
    }

    .page-subtitle {
        color: #4b6a8b;
        font-size: 15px;
        margin-bottom: 28px;
        font-weight: 400;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .page-subtitle i { color: #2563eb; }

    .dashboard-filters {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 14px;
        background: #fff;
        padding: 16px 20px;
        border: 1px solid #eaf0f6;
        border-radius: 18px;
        margin-bottom: 24px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .02);
    }
    .dashboard-filter-group {
        flex: 1 1 200px;
    }
    .dashboard-filter-group label {
        display: block;
        color: #4b6a8b;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }
    .dashboard-filter-group label i { color: #2563eb; margin-right: 5px; }
    .dashboard-filter-group select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #dbe5f0;
        border-radius: 10px;
        background: #f8fafc;
        color: #0a1e3c;
        outline: none;
        cursor: pointer;
    }
    .dashboard-filter-group select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
    }
    .dashboard-filter-btn {
        padding: 10px 18px;
        border: 0;
        border-radius: 10px;
        background: #2563eb;
        color: #fff;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .dashboard-filter-btn:hover { background: #1d4ed8; }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        padding: 22px 24px;
        border-radius: 28px;
        border: 1px solid #eaf0f6;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #2563eb, #60a5fa);
        border-radius: 28px 28px 0 0;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.1);
    }
    .stat-label {
        font-size: 13px;
        font-weight: 500;
        color: #4b6a8b;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
    }
    .stat-label i { color: #2563eb; font-size: 16px; }
    .stat-value {
        font-size: 36px;
        font-weight: 800;
        color: #0a1e3c;
    }
    .stat-value .small {
        font-size: 14px;
        font-weight: 400;
        color: #4b6a8b;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    .card {
        background: white;
        border-radius: 28px;
        padding: 24px 26px 20px;
        border: 1px solid #eaf0f6;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.01);
    }
    .card-title {
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 18px;
        color: #0a1e3c;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-title i { color: #2563eb; margin-right: 8px; }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    th {
        text-align: left;
        padding: 10px 6px 10px 0;
        font-weight: 600;
        color: #4b6a8b;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border-bottom: 2px solid #e6ecf3;
    }
    td {
        padding: 12px 6px 12px 0;
        border-bottom: 1px solid #eff3f8;
    }
    tr:last-child td {
        border-bottom: none;
    }
    tr:hover td {
        background: #f8fafc;
    }
    .plans-table-scroll {
        max-height: 285px;
        overflow-y: auto;
        border: 1px solid #eef2f7;
        border-radius: 12px;
        scrollbar-width: thin;
        scrollbar-color: #9bb5d2 #eef2f7;
    }
    .plans-table-scroll::-webkit-scrollbar { width: 8px; }
    .plans-table-scroll::-webkit-scrollbar-track { background: #eef2f7; border-radius: 8px; }
    .plans-table-scroll::-webkit-scrollbar-thumb { background: #9bb5d2; border-radius: 8px; }
    .plans-table-scroll table { border: 0; }
    .plans-table-scroll th {
        position: sticky;
        top: 0;
        z-index: 1;
        background: #fff;
    }

    .coverage-ring {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: conic-gradient(#2563eb 0% 29%, #e6ecf3 29% 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.15);
        transition: background 0.6s ease;
    }
    .coverage-ring-inner {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 800;
        color: #2563eb;
    }
    .coverage-ring-inner .label {
        font-size: 10px;
        font-weight: 400;
        color: #4b6a8b;
        text-transform: uppercase;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 820px) {
        .top-bar {
            flex-direction: column;
            align-items: stretch;
            gap: 14px;
            border-radius: 32px;
            padding: 16px 20px;
            margin: 12px 16px;
        }
        .nav-wrapper {
            flex-direction: column;
            align-items: stretch;
        }
        .nav-tabs { 
            overflow-x: auto; 
            flex-wrap: nowrap;
        }
        .nav-tab { 
            white-space: nowrap;
            padding: 6px 14px;
            font-size: 13px;
        }
        .content-wrapper { margin: 0 16px 16px 16px; }
        .page-content { padding: 16px; }
        .content-grid { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .dashboard-filter-btn { width: 100%; }
        .form-grid { grid-template-columns: 1fr; }
        .topic-row .topic-fields { grid-template-columns: 1fr; }
        .modal-content { padding: 20px; }
    }

    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
        .stat-value { font-size: 28px; }
        .dashboard-filters {
            flex-direction: column;
            align-items: stretch;
        }
        .dashboard-filter-group { flex: 1 1 auto; }
    }
</style>
@endsection

@section('content')
<!-- ===== TOP BAR ===== -->
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
<div class="top-bar">
    <div class="logo-area">
        <div class="logo-icon"><i class="fas fa-chalkboard-teacher"></i></div>
        <span class="logo-text">Daily<span>Planner</span></span>
    </div>
    <div class="nav-wrapper">
        @if($plannerIsAdmin)
            <label class="employee-selector" for="planner-employee">
                <i class="fas fa-user-tie"></i>
                <span>Department</span>
                <select id="planner-department" onchange="filterPlannerEmployees(this.value)">
                    <option value="">Select department</option>
                    @foreach($plannerDepartments as $plannerDepartment)
                        <option value="{{ $plannerDepartment->department_id }}" @if((string) $plannerSelectedDepartmentId === (string) $plannerDepartment->department_id) selected @endif>
                            {{ $plannerDepartment->department }}
                        </option>
                    @endforeach
                </select>
            </label>
            <label class="employee-selector" for="planner-employee">
                <i class="fas fa-user-tie"></i>
                <span>Employee</span>
                <select id="planner-employee" onchange="changePlannerEmployee(this.value)" disabled>
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
        <div class="nav-tabs">
            <a href="{{ route('lesson-planner.dashboard', $plannerContextQuery) }}" class="nav-tab active">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="{{ route('lesson-planner.plans', $plannerContextQuery) }}" class="nav-tab">
                <i class="fas fa-calendar-alt"></i> Calendar
            </a>
            <a href="{{ route('lesson-planner.review', $plannerContextQuery) }}" class="nav-tab">
                <i class="fas fa-check-double"></i> Performance
            </a>
            <a href="{{ route('lesson-planner.reports', $plannerContextQuery) }}" class="nav-tab">
                <i class="fas fa-file-alt"></i> Reports
            </a>
        </div>
        <a href="{{ route('lesson-planner.new', $plannerContextQuery) }}" class="btn-new">
            <i class="fas fa-plus-circle"></i> New Plan
        </a>
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

<!-- ===== DASHBOARD CONTENT ===== -->
<div class="page-content">
    <p class="page-subtitle">
        <i class="fas fa-chart-line"></i> Plan the week ahead — objectives, methods, syllabus topics — submit for approval, then track coverage.
    </p>

    <!-- Stats Grid -->
    <div class="stats-grid" id="stats-container">
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-book"></i> Total Plans</div>
            <div class="stat-value" id="total-plans">
                <span style="font-size:18px; color:#4b6a8b;">-</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-clock"></i> Pending</div>
            <div class="stat-value" id="pending-plans">
                <span style="font-size:18px; color:#4b6a8b;">-</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-check-circle" style="color:#10b981;"></i> Completed</div>
            <div class="stat-value" id="completed-plans">
                <span style="font-size:18px; color:#4b6a8b;">-</span>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loading-spinner" style="display:none; text-align:center; padding:20px;">
        <i class="fas fa-spinner fa-spin" style="font-size:24px; color:#2563eb;"></i>
        <p style="margin-top:10px; color:#4b6a8b;">Loading dashboard...</p>
    </div>

    <!-- Error Alert -->
    <div id="error-alert" style="display:none; background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:16px; border-radius:12px; margin-bottom:20px;">
        <i class="fas fa-exclamation-circle"></i> <span id="error-message"></span>
        <button onclick="document.getElementById('error-alert').style.display='none';" style="float:right; background:none; border:none; color:#991b1b; cursor:pointer; font-size:20px;">&times;</button>
    </div>

    <!-- Dashboard Filters -->
    <div class="dashboard-filters">
        <div class="dashboard-filter-group">
            <label for="dashboard-class"><i class="fas fa-users"></i> Class</label>
            <select id="dashboard-class">
                <option value="">All Classes</option>
            </select>
        </div>
        <div class="dashboard-filter-group">
            <label for="dashboard-subject"><i class="fas fa-book"></i> Subject</label>
            <select id="dashboard-subject">
                <option value="">All Subjects</option>
            </select>
        </div>
        <div class="dashboard-filter-group">
            <label for="dashboard-month"><i class="fas fa-calendar-alt"></i> Month</label>
            <select id="dashboard-month">
                <option value="">All Months</option>
            </select>
        </div>
        <button class="dashboard-filter-btn" type="button" onclick="applyDashboardFilters()">
            <i class="fas fa-filter"></i> Apply Filter
        </button>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <div class="card">
            <div class="card-title">
                <span><i class="fas fa-list-ul"></i> All lesson plans</span>
                <span style="font-size:13px; color:#2563eb; font-weight:500; cursor:pointer;" onclick="refreshDashboard();">
                    <i class="fas fa-sync-alt"></i> Refresh
                </span>
            </div>
            <div class="plans-table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Plan</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Topics</th>
                        </tr>
                    </thead>
                    <tbody id="recent-plans-table">
                        <tr>
                            <td colspan="4" style="text-align:center; padding:20px; color:#8a9bb5;">
                                <i class="fas fa-inbox"></i> Loading...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-title">
                <span><i class="fas fa-chart-pie"></i> Syllabus coverage</span>
            </div>
            <div style="text-align:center;padding:10px 0;">
                <div class="coverage-ring" id="coverage-ring">
                    <div class="coverage-ring-inner">
                        <span id="coverage-percentage">-</span>
                        <span class="label">Coverage</span>
                    </div>
                </div>
                <div style="color:#4b6a8b;font-size:13px; margin-top:4px;">
                    <i class="fas fa-check-circle" style="color:#10b981;"></i> topics covered vs planned
                </div>
                <div style="margin-top:12px;display:flex;justify-content:center;gap:20px;font-size:13px;flex-wrap:wrap;">
                    <span><span style="display:inline-block;width:10px;height:10px;background:#10b981;border-radius:50%;margin-right:4px;"></span> <span id="covered-count">-</span> covered</span>
                    <span><span style="display:inline-block;width:10px;height:10px;background:#f59e0b;border-radius:50%;margin-right:4px;"></span> <span id="partial-count">-</span> partial</span>
                    <span><span style="display:inline-block;width:10px;height:10px;background:#ef4444;border-radius:50%;margin-right:4px;"></span> <span id="notcovered-count">-</span> not covered</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== SHARED MODAL ===== -->
@include('instituteAdmin.Lesson-planner.partials.modal')

<script>
    const dashboardConfig = {
        apiUrl: '{{ route("lesson-planner.calendar.data") }}',
        employeeId: @json(request('employee_id')),
        statusColors: {
            'draft': { bg: '#fef3c7', text: '#92400e', icon: 'fas fa-pencil-alt' },
            'active': { bg: '#dcfce7', text: '#166534', icon: 'fas fa-check-circle' },
            'review': { bg: '#dbeafe', text: '#1e40af', icon: 'fas fa-eye' }
        },
        debugMode: true
    };

    // Store for dashboard state
    const dashboardState = {
        plans: [],
        allPlans: [],
        stats: { total: 0, pending: 0, completed: 0 },
        coverage: { total: 0, covered: 0, partial: 0, notCovered: 0, percentage: 0 },
        isLoading: false,
        error: null
    };

    function debug(message, data = null) {
        if (dashboardConfig.debugMode) {
            console.log(`[LessonPlanner Dashboard] ${message}`, data || '');
        }
    }

    async function fetchPlansData(classFilter = '', subjectFilter = '', monthFilter = '') {
        dashboardState.isLoading = true;
        showLoadingState(true);
        hideError();

        const params = new URLSearchParams();
        if (classFilter) params.set('class', classFilter);
        if (subjectFilter) params.set('subject', subjectFilter);
        if (monthFilter) params.set('month', monthFilter);
        if (dashboardConfig.employeeId) params.set('employee_id', dashboardConfig.employeeId);
        const requestUrl = params.toString() ? `${dashboardConfig.apiUrl}?${params}` : dashboardConfig.apiUrl;
        debug('Starting fetch from API:', requestUrl);

        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta?.getAttribute('content') || '';
            
            debug('CSRF Token found:', csrfToken ? 'Yes' : 'No');

            const response = await fetch(requestUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin'
            });

            debug('API Response Status:', response.status);
            debug('API Response OK:', response.ok);

            if (!response.ok) {
                const responseText = await response.text();
                debug('API Error Response:', responseText);
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const plans = await response.json();
            debug('Plans Data Received:', plans);
            debug('Plans Count:', Array.isArray(plans) ? plans.length : 'Not an array');

            dashboardState.plans = Array.isArray(plans) ? plans : [];

            if (!classFilter && !subjectFilter && !monthFilter) {
                dashboardState.allPlans = dashboardState.plans;
                populateDashboardFilters(dashboardState.allPlans);
            }

            if (dashboardState.plans.length === 0) {
                debug('No plans found in response. This may be expected if no plans exist in the database.');
            }

            calculateStats();
            calculateCoverage();
            renderDashboard();

        } catch (error) {
            console.error('[LessonPlanner Dashboard] Failed to fetch plans:', error);
            debug('Error Details:', error.message);
            
            showError(`Unable to load lesson plans: ${error.message}`);
            
            calculateStats();
            calculateCoverage();
            renderDashboard();
        } finally {
            dashboardState.isLoading = false;
            showLoadingState(false);
        }
    }

    function calculateStats() {
        const stats = {
            total: dashboardState.plans.length,
            pending: dashboardState.plans.filter(p => p.status === 'draft' || p.status === 'review').length,
            completed: dashboardState.plans.filter(p => p.status === 'active').length,
        };

        dashboardState.stats = stats;
    }

    function calculateCoverage() {
        const plans = dashboardState.plans;
        let totalTopics = 0;
        let coveredTopics = 0;
        let partialTopics = 0;

        plans.forEach(plan => {
            let planTopics = [];
            
            if (plan.dailyTopics && typeof plan.dailyTopics === 'object') {
                Object.values(plan.dailyTopics).forEach(dayTopics => {
                    if (Array.isArray(dayTopics)) {
                        planTopics = planTopics.concat(dayTopics);
                    }
                });
            } else if (plan.topics && Array.isArray(plan.topics)) {
                planTopics = plan.topics;
            }

            planTopics.forEach(topic => {
                totalTopics++;
                const status = topic.coverage_status || (topic.covered ? 'covered' : 'not_covered');
                if (status === 'covered') {
                    coveredTopics++;
                } else if (status === 'partial') {
                    partialTopics++;
                }
            });
        });

        const notCoveredTopics = totalTopics - coveredTopics - partialTopics;
        const percentage = totalTopics > 0 ? Math.round(((coveredTopics + (partialTopics * 0.5)) / totalTopics) * 100) : 0;

        dashboardState.coverage = {
            total: totalTopics,
            covered: coveredTopics,
            partial: partialTopics,
            notCovered: notCoveredTopics,
            percentage: percentage
        };

        debug('Coverage calculated:', dashboardState.coverage);
    }

    function renderStats() {
        const { stats } = dashboardState;

        updateElement('#total-plans', String(stats.total));
        updateElement('#pending-plans', String(stats.pending));
        updateElement('#completed-plans', String(stats.completed));
    }

    function renderRecentPlans() {
        const tbody = document.getElementById('recent-plans-table');
        const recentPlans = dashboardState.plans.slice(0, 10);

        if (recentPlans.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" style="text-align:center;padding:20px;color:#8a9bb5;">
                        <i class="fas fa-inbox"></i> No lesson plans yet
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = recentPlans.map(plan => {
            const statusConfig = dashboardConfig.statusColors[plan.status] || dashboardConfig.statusColors['draft'];
            const subjectName = plan.subject || 'N/A';
            const topicsCount = plan.periods || 0;

            return `
                <tr>
                    <td>
                        <strong style="color:#0a1e3c;">${escapeHtml(plan.title)}</strong>
                        <div style="font-size:12px; color:#4b6a8b; margin-top:2px;">${escapeHtml(plan.month || 'Monthly')}</div>
                    </td>
                    <td style="color:#4b6a8b;">${escapeHtml(subjectName)}</td>
                    <td>
                        <span style="display:inline-block; background:${statusConfig.bg}; color:${statusConfig.text}; padding:4px 10px; border-radius:12px; font-size:12px; font-weight:500;">
                            <i class="${statusConfig.icon}"></i> ${formatStatus(plan.status)}
                        </span>
                    </td>
                    <td style="color:#4b6a8b;">${topicsCount} topic${topicsCount !== 1 ? 's' : ''}</td>
                </tr>
            `;
        }).join('');
    }

    function renderCoverage() {
        const { coverage } = dashboardState;

        updateElement('#coverage-percentage', String(coverage.percentage) + '%');
        updateElement('#covered-count', String(coverage.covered));
        updateElement('#partial-count', String(coverage.partial));
        updateElement('#notcovered-count', String(coverage.notCovered));

        const ring = document.getElementById('coverage-ring');
        if (ring) {
            ring.style.background = `conic-gradient(#2563eb 0% ${coverage.percentage}%, #e6ecf3 ${coverage.percentage}% 100%)`;
        }
    }

    function renderDashboard() {
        renderStats();
        renderRecentPlans();
        renderCoverage();
    }

    function populateDashboardFilters(plans) {
        const options = [
            { id: 'dashboard-class', key: 'class', label: 'All Classes' },
            { id: 'dashboard-subject', key: 'subject', label: 'All Subjects' },
            { id: 'dashboard-month', key: 'month', label: 'All Months' }
        ];

        options.forEach(({ id, key, label }) => {
            const select = document.getElementById(id);
            const values = [...new Set(plans.map(plan => plan[key]).filter(Boolean))].sort();
            select.innerHTML = `<option value="">${label}</option>` + values
                .map(value => `<option value="${escapeHtml(String(value))}">${escapeHtml(String(value))}</option>`)
                .join('');
        });
    }

    function applyDashboardFilters() {
        const classFilter = document.getElementById('dashboard-class').value;
        const subjectFilter = document.getElementById('dashboard-subject').value;
        const monthFilter = document.getElementById('dashboard-month').value;
        fetchPlansData(classFilter, subjectFilter, monthFilter);
    }

    function updateElement(selector, text) {
        const element = document.querySelector(selector);
        if (element) {
            element.textContent = text;
        }
    }

    function formatStatus(status) {
        const statusMap = {
            'draft': 'Draft',
            'active': 'Active',
            'review': 'Review'
        };
        return statusMap[status] || status.charAt(0).toUpperCase() + status.slice(1);
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    function showLoadingState(show) {
        const spinner = document.getElementById('loading-spinner');
        if (spinner) {
            spinner.style.display = show ? 'block' : 'none';
        }
    }

    function showError(message) {
        const alert = document.getElementById('error-alert');
        const messageEl = document.getElementById('error-message');
        if (alert && messageEl) {
            messageEl.textContent = message;
            alert.style.display = 'block';
        }
        dashboardState.error = message;
    }

    function hideError() {
        const alert = document.getElementById('error-alert');
        if (alert) {
            alert.style.display = 'none';
        }
        dashboardState.error = null;
    }

    function refreshDashboard() {
        fetchPlansData();
    }

    function initDashboard() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                fetchPlansData();
            });
        } else {
            fetchPlansData();
        }
    }

    window.refreshLessonPlannerViews = function() {
        refreshDashboard();
    };

    window.dashboardDebug = {
        getState: () => dashboardState,
        getStats: () => dashboardState.stats,
        getCoverage: () => dashboardState.coverage,
        getPlans: () => dashboardState.plans,
        testAPI: async () => {
            console.log('Testing API endpoint:', dashboardConfig.apiUrl);
            try {
                const response = await fetch(dashboardConfig.apiUrl, {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                });
                const data = await response.json();
                console.log('API Response:', data);
                return data;
            } catch (e) {
                console.error('API Test Failed:', e);
                return null;
            }
        },
        generateTestData: async () => {
            console.log('Generating test data...');
            try {
                const response = await fetch('{{ route("lesson-planner.test-data.generate") }}', {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                });
                const data = await response.json();
                console.log('Test Data Generated:', data);
                setTimeout(() => fetchPlansData(), 500);
                return data;
            } catch (e) {
                console.error('Failed to generate test data:', e);
                return null;
            }
        },
        clearTestData: async () => {
            console.log('Clearing test data...');
            try {
                const response = await fetch('{{ route("lesson-planner.test-data.clear") }}', {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                });
                const data = await response.json();
                console.log('Test Data Cleared:', data);
                setTimeout(() => fetchPlansData(), 500);
                return data;
            } catch (e) {
                console.error('Failed to clear test data:', e);
                return null;
            }
        },
        refresh: () => {
            console.log('Manually refreshing dashboard...');
            fetchPlansData();
        },
        toggleDebug: (enable) => {
            dashboardConfig.debugMode = enable;
            console.log('Debug mode:', enable ? 'ON' : 'OFF');
        }
    };

    initDashboard();

    console.log('%c📚 Lesson Planner Dashboard - Debug Help', 'color: #2563eb; font-size: 16px; font-weight: bold;');
    console.log('%cUse these commands in the console:', 'color: #4b6a8b; font-weight: bold;');
    console.log('  dashboardDebug.testAPI()           - Test the API endpoint');
    console.log('  dashboardDebug.generateTestData()  - Create 5 sample lesson plans');
    console.log('  dashboardDebug.clearTestData()     - Delete all test data');
    console.log('  dashboardDebug.refresh()           - Manually refresh the dashboard');
    console.log('  dashboardDebug.getState()          - View current dashboard state');
    console.log('  dashboardDebug.getCoverage()       - View coverage calculations');
    console.log('  dashboardDebug.toggleDebug(true)   - Enable/disable debug logging');
</script>
@endsection