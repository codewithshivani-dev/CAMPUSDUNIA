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

    .page-content {
        padding: 28px 32px;
        background: #f0f4f9;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }
    .page-header h1 {
        font-size: 28px;
        font-weight: 800;
        color: #0a1e3c;
        margin: 0;
    }
    .page-header h1 i { color: #10b981; margin-right: 12px; }
    .page-header p {
        color: #4b6a8b;
        font-size: 15px;
        margin: 4px 0 0 0;
    }
    .page-header .header-badge {
        background: #10b981;
        color: white;
        padding: 6px 20px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }
    .page-header .header-badge i { margin-right: 6px; }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-card {
        background: white;
        padding: 20px 22px;
        border-radius: 24px;
        border: 1px solid #eaf0f6;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        cursor: default;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #34d399);
        border-radius: 24px 24px 0 0;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.1);
    }
    .stat-label {
        font-size: 13px;
        font-weight: 500;
        color: #4b6a8b;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
    }
    .stat-label i { color: #10b981; font-size: 16px; }
    .stat-value {
        font-size: 30px;
        font-weight: 800;
        color: #0a1e3c;
    }
    .stat-value .small {
        font-size: 14px;
        font-weight: 400;
        color: #4b6a8b;
    }

    .filters {
        display: flex;
        flex-wrap: wrap;
        gap: 12px 20px;
        background: white;
        padding: 14px 24px;
        border-radius: 60px;
        border: 1px solid #eaf0f6;
        margin-bottom: 24px;
        align-items: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }
    .filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .filter-group label {
        font-size: 13px;
        font-weight: 500;
        color: #4b6a8b;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .filter-group label i { color: #10b981; }
    .filter-group select {
        padding: 8px 16px;
        border: 1.5px solid #dce4ed;
        border-radius: 40px;
        font-size: 13px;
        background: #fafcff;
        outline: none;
        transition: 0.2s;
        min-width: 130px;
    }
    .filter-group select:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.08);
    }

    .btn {
        border: none;
        padding: 8px 20px;
        border-radius: 60px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-primary { background: #10b981; color: white; }
    .btn-primary:hover { background: #059669; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
    .btn-secondary { background: #eef2f6; color: #1f334f; }
    .btn-secondary:hover { background: #d1d9e6; transform: translateY(-1px); }

    .card {
        background: white;
        border-radius: 24px;
        padding: 22px 24px 18px;
        border: 1px solid #eaf0f6;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.01);
    }
    .card-title {
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 16px;
        color: #0a1e3c;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-title i { color: #10b981; margin-right: 8px; }
    .card-title .badge {
        background: #eef2f6;
        padding: 3px 14px;
        border-radius: 20px;
        font-size: 12px;
        color: #4b6a8b;
        font-weight: 400;
    }

    .plan-coverage-card {
        background: #f8fafc;
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 16px;
        border: 1px solid #eaf0f6;
        transition: all 0.3s ease;
    }
    .plan-coverage-card:hover {
        border-color: #10b981;
        box-shadow: 0 2px 12px rgba(16, 185, 129, 0.06);
    }
    .plan-coverage-card:last-child { margin-bottom: 0; }

    .plan-coverage-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 12px;
    }

    .plan-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .plan-info .plan-title {
        font-weight: 600;
        color: #0a1e3c;
        font-size: 15px;
    }
    .plan-info .plan-title i { color: #10b981; margin-right: 6px; }
    .plan-info .class-badge {
        display: inline-block;
        background: #dbeafe;
        color: #1d4ed8;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    .plan-info .subject-tag {
        display: inline-block;
        background: #eef2f6;
        color: #4b6a8b;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 11px;
    }
    .plan-info .week-tag {
        display: inline-block;
        background: #fef3c7;
        color: #a16207;
        padding: 2px 10px;
        border-radius: 12px;
        font-size: 11px;
    }

    .plan-stats {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .plan-stats .stat-item {
        text-align: center;
    }
    .plan-stats .stat-item .number {
        font-size: 18px;
        font-weight: 700;
        color: #0a1e3c;
    }
    .plan-stats .stat-item .label {
        font-size: 10px;
        color: #8a9bb5;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .plan-stats .stat-item .number.covered { color: #10b981; }
    .plan-stats .stat-item .number.pending { color: #f59e0b; }
    .plan-stats .stat-item .number.total { color: #10b981; }

    .coverage-bar-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 160px;
    }
    .coverage-bar-wrapper .coverage-bar {
        flex: 1;
        height: 8px;
        background: #eef2f6;
        border-radius: 20px;
        overflow: hidden;
        min-width: 80px;
    }
    .coverage-bar-wrapper .coverage-fill {
        height: 100%;
        border-radius: 20px;
        transition: width 0.6s ease;
    }
    .coverage-bar-wrapper .coverage-fill.high { background: linear-gradient(90deg, #10b981, #34d399); }
    .coverage-bar-wrapper .coverage-fill.medium { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .coverage-bar-wrapper .coverage-fill.low { background: linear-gradient(90deg, #ef4444, #f87171); }
    .coverage-bar-wrapper .coverage-percent {
        font-weight: 700;
        font-size: 16px;
        min-width: 48px;
        text-align: right;
    }
    .coverage-bar-wrapper .coverage-percent.high { color: #10b981; }
    .coverage-bar-wrapper .coverage-percent.medium { color: #f59e0b; }
    .coverage-bar-wrapper .coverage-percent.low { color: #ef4444; }

    .status-badge {
        display: inline-block;
        padding: 3px 14px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
    }
    .status-badge.active { background: #d1fae5; color: #0b6e4f; }
    .status-badge.draft { background: #eef2f6; color: #4b6a8b; }
    .status-badge.review { background: #dbeafe; color: #1d4ed8; }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #8a9bb5;
    }
    .empty-state i {
        font-size: 48px;
        color: #dce4ed;
        margin-bottom: 12px;
        display: block;
    }
    .empty-state .title {
        font-size: 18px;
        font-weight: 600;
        color: #0a1e3c;
    }

    .btn-view-small {
        background: #10b981;
        color: white;
        border: none;
        padding: 4px 14px;
        border-radius: 16px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }
    .btn-view-small:hover {
        background: #059669;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* Loading spinner */
    .loading-spinner {
        text-align: center;
        padding: 40px;
        color: #8a9bb5;
    }
    .loading-spinner i {
        font-size: 32px;
        color: #10b981;
        margin-bottom: 10px;
        display: block;
    }

    @media (max-width: 820px) {
        .page-content { padding: 16px; }
        .filters {
            flex-direction: column;
            align-items: stretch;
            border-radius: 32px;
        }
        .plan-coverage-header {
            flex-direction: column;
            align-items: stretch;
        }
        .plan-stats {
            justify-content: space-around;
        }
        .coverage-bar-wrapper { min-width: auto; }
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .page-header { flex-direction: column; align-items: flex-start; }
        .student-info-banner { margin: 0 16px 16px 16px; padding: 14px 18px; }
    }

    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
        .student-info-banner { flex-direction: column; text-align: center; }
        .student-info-banner i { font-size: 24px; }
    }
</style>
@endsection

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
            <a href="{{ route('student.lesson-planner.coverage') }}" class="nav-tab active" style="border:none; background:transparent; padding:8px 10px; border-radius:60px; font-weight:500; font-size:14px; color:#4b6a8b; cursor:pointer; transition:0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:8px; background:white; color:#10b981; box-shadow:0 2px 12px rgba(16,185,129,0.15);">
                <i class="fas fa-chart-pie"></i> Coverage
            </a>
            <a href="{{ route('student.lesson-planner.performance') }}" class="nav-tab" style="border:none; background:transparent; padding:8px 10px; border-radius:60px; font-weight:500; font-size:14px; color:#4b6a8b; cursor:pointer; transition:0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
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

<div class="page-content">
    <div class="page-header">
        <div>
            <h1><i class="fas fa-chart-pie"></i> Coverage Tracker</h1>
            <p><i class="fas fa-info-circle" style="color:#10b981;"></i> Track your progress across all lesson plans</p>
        </div>
        <span class="header-badge">
            <i class="fas fa-eye"></i> View Only
        </span>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid" id="coverageStats">
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-chart-line"></i> Overall Coverage</div>
            <div class="stat-value" id="overallCoverage">0%</div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-check-circle" style="color:#10b981;"></i> Fully Covered</div>
            <div class="stat-value" id="fullyCovered">0</div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-circle" style="color:#f59e0b;"></i> Partially Covered</div>
            <div class="stat-value" id="partiallyCovered">0</div>
        </div>
        <div class="stat-card">
            <div class="stat-label"><i class="fas fa-times-circle" style="color:#ef4444;"></i> Not Covered</div>
            <div class="stat-value" id="notCovered">0</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters">
        <div class="filter-group">
            <label><i class="fas fa-users"></i> Class</label>
            <select id="coverage-class">
                <option value="">All Classes</option>
                <option value="KG/A">KG / A</option>
                <option value="Nursery/A">Nursery / A</option>
                <option value="Grade 1/A">Grade 1 / A</option>
                <option value="Grade 2/A">Grade 2 / A</option>
                <option value="Grade 3/A">Grade 3 / A</option>
                <option value="Grade 4/A">Grade 4 / A</option>
                <option value="Grade 5/A">Grade 5 / A</option>
            </select>
        </div>
        <div class="filter-group">
            <label><i class="fas fa-book"></i> Subject</label>
            <select id="coverage-subject">
                <option value="">All Subjects</option>
                <option value="English">English</option>
                <option value="Mathematics">Mathematics</option>
                <option value="Science">Science</option>
                <option value="Art">Art</option>
                <option value="Social Studies">Social Studies</option>
                <option value="Computer Science">Computer Science</option>
            </select>
        </div>
        <div class="filter-group">
            <label><i class="fas fa-filter"></i> Status</label>
            <select id="coverage-status">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="draft">Draft</option>
                <option value="review">Review</option>
            </select>
        </div>
        <button class="btn btn-primary" onclick="applyCoverageFilters()"><i class="fas fa-filter"></i> Apply</button>
        <button class="btn btn-secondary" onclick="clearCoverageFilters()"><i class="fas fa-undo"></i> Clear</button>
        <button class="btn btn-secondary" onclick="loadCoverageData()"><i class="fas fa-sync-alt"></i> Refresh</button>
    </div>

    <!-- Main Card -->
    <div class="card">
        <div class="card-title">
            <span><i class="fas fa-list-ul"></i> Coverage Details</span>
            <span class="badge" id="planCountBadge">0 plans</span>
        </div>
        <div id="coverageContainer">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                Loading coverage data...
            </div>
        </div>
    </div>
</div>

<script>
    let coveragePlansData = [];

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    async function loadCoverageData() {
        const container = document.getElementById('coverageContainer');
        container.innerHTML = `
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                Loading coverage data...
            </div>
        `;

        try {
            const response = await fetch('{{ route("student.lesson-planner.data") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin'
            });
            
            if (!response.ok) {
                throw new Error('Failed to fetch data');
            }
            
            const data = await response.json();
            coveragePlansData = Array.isArray(data) ? data : [];
            renderCoverageTable(coveragePlansData);
        } catch (error) {
            console.error('Failed to load coverage data:', error);
            coveragePlansData = [];
            renderCoverageTable([]);
        }
    }

    function renderCoverageTable(plans) {
        const container = document.getElementById('coverageContainer');
        
        if (!plans || plans.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <div class="title">No plans found</div>
                    <div style="font-size:13px; margin-top:4px; color:#4b6a8b;">Check back later for lesson plans</div>
                </div>
            `;
            updateStats([]);
            return;
        }

        updateStats(plans);

        let html = '';
        plans.forEach(p => {
            let allTopics = [];
            if (p.dailyTopics) {
                Object.keys(p.dailyTopics).forEach(date => {
                    if (p.dailyTopics[date] && Array.isArray(p.dailyTopics[date])) {
                        p.dailyTopics[date].forEach(topic => {
                            allTopics.push({ ...topic, date: date });
                        });
                    }
                });
            }
            if (allTopics.length === 0 && p.topics && Array.isArray(p.topics)) {
                allTopics = p.topics.map(t => ({ ...t, date: p.startDate || 'N/A' }));
            }

            const totalTopics = allTopics.length;
            const coveredTopics = allTopics.filter(t => t.covered || t.coverage_status === 'covered').length;
            const coveragePct = totalTopics > 0 ? Math.round((coveredTopics / totalTopics) * 100) : 0;
            const level = coveragePct >= 75 ? 'high' : (coveragePct >= 25 ? 'medium' : 'low');
            const statusClass = p.status || 'draft';
            const statusDisplay = statusClass.charAt(0).toUpperCase() + statusClass.slice(1);

            html += `
                <div class="plan-coverage-card">
                    <div class="plan-coverage-header">
                        <div class="plan-info">
                            <span class="plan-title"><i class="fas fa-file-alt"></i> ${p.title || 'Untitled Plan'}</span>
                            <span class="class-badge"><i class="fas fa-users"></i> ${p.class || 'N/A'}</span>
                            <span class="subject-tag"><i class="fas fa-book"></i> ${p.subject || 'N/A'}</span>
                            <span class="week-tag"><i class="fas fa-calendar-week"></i> ${p.week || 'Week 1'}</span>
                            <span class="status-badge ${statusClass}">${statusDisplay}</span>
                        </div>
                        <div class="plan-stats">
                            <div class="stat-item">
                                <div class="number covered">${coveredTopics}</div>
                                <div class="label">Covered</div>
                            </div>
                            <div class="stat-item">
                                <div class="number pending">${totalTopics - coveredTopics}</div>
                                <div class="label">Pending</div>
                            </div>
                            <div class="stat-item">
                                <div class="number total">${totalTopics}</div>
                                <div class="label">Total</div>
                            </div>
                            <div class="coverage-bar-wrapper">
                                <div class="coverage-bar">
                                    <div class="coverage-fill ${level}" style="width:${coveragePct}%;"></div>
                                </div>
                                <span class="coverage-percent ${level}">${coveragePct}%</span>
                            </div>
                            <a href="{{ route('student.lesson-planner.plan-detail', '') }}/${p.id}" class="btn-view-small">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    function updateStats(plans) {
        const total = plans.length;
        let totalTopics = 0;
        let coveredTopics = 0;
        
        plans.forEach(p => {
            let topics = [];
            if (p.dailyTopics) {
                Object.keys(p.dailyTopics).forEach(date => {
                    if (p.dailyTopics[date] && Array.isArray(p.dailyTopics[date])) {
                        topics = topics.concat(p.dailyTopics[date]);
                    }
                });
            }
            if (topics.length === 0 && p.topics && Array.isArray(p.topics)) {
                topics = p.topics;
            }
            totalTopics += topics.length;
            coveredTopics += topics.filter(t => t.covered || t.coverage_status === 'covered').length;
        });
        
        const avgCoverage = totalTopics > 0 ? Math.round((coveredTopics / totalTopics) * 100) : 0;
        
        const fullyCovered = plans.filter(p => {
            let topics = [];
            if (p.dailyTopics) {
                Object.keys(p.dailyTopics).forEach(date => {
                    if (p.dailyTopics[date] && Array.isArray(p.dailyTopics[date])) {
                        topics = topics.concat(p.dailyTopics[date]);
                    }
                });
            }
            if (topics.length === 0 && p.topics && Array.isArray(p.topics)) {
                topics = p.topics;
            }
            const covered = topics.filter(t => t.covered || t.coverage_status === 'covered').length;
            return topics.length > 0 && covered === topics.length;
        }).length;
        
        const partiallyCovered = plans.filter(p => {
            let topics = [];
            if (p.dailyTopics) {
                Object.keys(p.dailyTopics).forEach(date => {
                    if (p.dailyTopics[date] && Array.isArray(p.dailyTopics[date])) {
                        topics = topics.concat(p.dailyTopics[date]);
                    }
                });
            }
            if (topics.length === 0 && p.topics && Array.isArray(p.topics)) {
                topics = p.topics;
            }
            const covered = topics.filter(t => t.covered || t.coverage_status === 'covered').length;
            return topics.length > 0 && covered > 0 && covered < topics.length;
        }).length;
        
        const notCovered = plans.filter(p => {
            let topics = [];
            if (p.dailyTopics) {
                Object.keys(p.dailyTopics).forEach(date => {
                    if (p.dailyTopics[date] && Array.isArray(p.dailyTopics[date])) {
                        topics = topics.concat(p.dailyTopics[date]);
                    }
                });
            }
            if (topics.length === 0 && p.topics && Array.isArray(p.topics)) {
                topics = p.topics;
            }
            const covered = topics.filter(t => t.covered || t.coverage_status === 'covered').length;
            return topics.length === 0 || covered === 0;
        }).length;

        document.getElementById('overallCoverage').textContent = avgCoverage + '%';
        document.getElementById('fullyCovered').textContent = fullyCovered;
        document.getElementById('partiallyCovered').textContent = partiallyCovered;
        document.getElementById('notCovered').textContent = notCovered;
        document.getElementById('planCountBadge').textContent = total + ' plan' + (total > 1 ? 's' : '');
    }

    function applyCoverageFilters() {
        const cls = document.getElementById('coverage-class').value;
        const subject = document.getElementById('coverage-subject').value;
        const status = document.getElementById('coverage-status').value;

        let filtered = coveragePlansData;
        if (cls) filtered = filtered.filter(p => p.class === cls);
        if (subject) filtered = filtered.filter(p => p.subject === subject);
        if (status) filtered = filtered.filter(p => p.status === status);

        renderCoverageTable(filtered);
    }

    function clearCoverageFilters() {
        document.getElementById('coverage-class').value = '';
        document.getElementById('coverage-subject').value = '';
        document.getElementById('coverage-status').value = '';
        renderCoverageTable(coveragePlansData);
    }

    document.addEventListener('DOMContentLoaded', loadCoverageData);
    window.refreshStudentPlans = loadCoverageData;
    window.refreshLessonPlannerViews = loadCoverageData;
</script>
@endsection