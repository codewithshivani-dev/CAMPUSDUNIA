@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('styles')
<style>
    .page-content {
        padding: 28px 32px;
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

    .filters {
        display: flex;
        flex-wrap: wrap;
        gap: 16px 24px;
        background: white;
        padding: 16px 28px;
        border-radius: 60px;
        border: 1px solid #eaf0f6;
        margin-bottom: 28px;
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
    .filter-group label i { color: #2563eb; }
    .filter-group select {
        padding: 8px 16px;
        border-radius: 30px;
        border: 1.5px solid #eaf0f6;
        background: white;
        font-size: 13px;
        color: #0a1e3c;
        outline: none;
        cursor: pointer;
        transition: all 0.2s;
        min-width: 120px;
    }
    .filter-group select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .btn {
        padding: 8px 20px;
        border-radius: 30px;
        border: none;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-primary {
        background: #2563eb;
        color: white;
    }
    .btn-primary:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .btn-secondary {
        background: #eef2f6;
        color: #4b6a8b;
    }
    .btn-secondary:hover {
        background: #dde7f1;
        transform: translateY(-2px);
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

    .coverage-bar {
        width: 100%;
        height: 8px;
        background: #e6ecf3;
        border-radius: 20px;
        overflow: hidden;
    }
    .coverage-fill {
        height: 100%;
        border-radius: 20px;
        transition: width 0.8s ease;
    }
    .coverage-fill.high { background: linear-gradient(90deg, #10b981, #34d399); }
    .coverage-fill.medium { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .coverage-fill.low { background: linear-gradient(90deg, #ef4444, #f87171); }

    .stat-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 4px;
        font-size: 14px;
    }
    .stat-row .value {
        font-weight: 700;
    }

    .big-number {
        font-size: 48px;
        font-weight: 800;
        color: #0a1e3c;
        line-height: 1;
    }
    .big-number .percent { color: #2563eb; }
    .big-label {
        font-size: 12px;
        color: #4b6a8b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .report-item {
        margin-bottom: 16px;
    }
    .report-item:last-child {
        margin-bottom: 0;
    }
    .report-item .row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 4px;
    }
    .report-item .row .name {
        color: #4b6a8b;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .report-item .row .pct {
        font-weight: 700;
    }

    .print-btn {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        border: none;
        padding: 8px 24px;
        border-radius: 60px;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .print-btn:hover {
        background: linear-gradient(135deg, #1d4ed8, #1a3fb5);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
    }

    @media print {
        body * { visibility: hidden; }
        .content-wrapper, .content-wrapper * { visibility: visible; }
        .content-wrapper {
            position: absolute;
            left: 0;
            top: 0;
            margin: 0;
            padding: 20px;
        }
        .filters, .btn, .print-btn, .top-bar { display: none !important; }
        .page-content { padding: 0 !important; }
    }

    @media (max-width: 820px) {
        .page-content { padding: 16px; }
        .filters {
            flex-direction: column;
            align-items: stretch;
            border-radius: 32px;
        }
        .filter-group select {
            width: 100%;
            min-width: auto;
        }
        .content-grid { grid-template-columns: 1fr; }
        .big-number { font-size: 32px; }
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
<div class="top-bar" style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; background:#ffffff; padding:14px 28px; border-radius:60px; box-shadow:0 4px 20px rgba(37,99,235,0.08); margin:20px 32px 16px 32px; border:1px solid rgba(37,99,235,0.06);">
    <div class="logo-area" style="display:flex; align-items:center; gap:12px; flex:0 0 auto;">
        <div class="logo-icon" style="width:44px; height:44px; background:linear-gradient(135deg, #2563eb, #1d4ed8); border-radius:14px; display:flex; align-items:center; justify-content:center; color:white; font-size:20px;">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <span class="logo-text" style="font-weight:800; font-size:22px; letter-spacing:-0.3px; color:#0a1e3c;">Daily<span style="color:#2563eb;">Planner</span></span>
    </div>
    <div class="nav-wrapper" style="display:flex; gap:8px; align-items:center; flex-wrap:nowrap; justify-content:flex-end; flex:1 1 auto; min-width:0;">
        @if($plannerIsAdmin)
            <label class="employee-selector" for="planner-employee" style="display:flex; align-items:center; gap:8px; padding:6px 12px; border:1px solid #dce4ed; border-radius:12px; background:#f8fafc; color:#4b6a8b; font-size:12px;">
                <i class="fas fa-user-tie" style="color:#2563eb;"></i>
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
                <i class="fas fa-user-tie" style="color:#2563eb;"></i>
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
            <a href="{{ route('lesson-planner.dashboard', $plannerContextQuery) }}" class="nav-tab" style="border:none; background:transparent; padding:8px 10px; border-radius:60px; font-weight:500; font-size:14px; color:#4b6a8b; cursor:pointer; transition:0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="{{ route('lesson-planner.plans', $plannerContextQuery) }}" class="nav-tab" style="border:none; background:transparent; padding:8px 10px; border-radius:60px; font-weight:500; font-size:14px; color:#4b6a8b; cursor:pointer; transition:0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
                <i class="fas fa-calendar-alt"></i> Calendar
            </a>
            <a href="{{ route('lesson-planner.review', $plannerContextQuery) }}" class="nav-tab" style="border:none; background:transparent; padding:8px 10px; border-radius:60px; font-weight:500; font-size:14px; color:#4b6a8b; cursor:pointer; transition:0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
                <i class="fas fa-check-double"></i> Performance
            </a>
            <a href="{{ route('lesson-planner.reports', $plannerContextQuery) }}" class="nav-tab active" style="border:none; background:transparent; padding:8px 10px; border-radius:60px; font-weight:500; font-size:14px; color:#4b6a8b; cursor:pointer; transition:0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:8px; background:white; color:#2563eb; box-shadow:0 2px 12px rgba(37,99,235,0.12);">
                <i class="fas fa-file-alt"></i> Reports
            </a>
        </div>
        <a href="{{ route('lesson-planner.new', $plannerContextQuery) }}" class="btn-new" style="background:linear-gradient(135deg, #2563eb, #1d4ed8); border:none; color:white; padding:10px 18px; border-radius:60px; font-weight:600; font-size:14px; cursor:pointer; transition:0.2s; box-shadow:0 6px 20px rgba(37,99,235,0.3); text-decoration:none; display:inline-flex; align-items:center; gap:8px; flex:0 0 auto; white-space:nowrap;">
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

<!-- ===== REPORTS CONTENT ===== -->
<div class="page-content">
    <p class="page-subtitle">
        <i class="fas fa-file-alt"></i> View detailed coverage reports
    </p>

    <div class="filters">
        <div class="filter-group">
            <label><i class="fas fa-users"></i> Class</label>
            <select id="report-class">
                <option value="">All</option>
                @foreach ($reportClasses as $class)
                    <option value="{{ $class }}">{{ $class }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label><i class="fas fa-book"></i> Subject</label>
            <select id="report-subject">
                <option value="">All</option>
                @foreach ($reportSubjects as $subject)
                    <option value="{{ $subject }}">{{ $subject }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label><i class="fas fa-calendar-alt"></i> Month</label>
            <select id="report-month">
                <option value="">All</option>
                @foreach ($reportMonths as $month)
                    <option value="{{ $month }}">{{ $month }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary" onclick="applyReportFilters()"><i class="fas fa-filter"></i> Apply</button>
        <button class="print-btn" onclick="downloadReport('pdf')"><i class="fas fa-file-pdf"></i> Download PDF</button>
        <button class="btn btn-secondary" onclick="downloadReport('csv')"><i class="fas fa-file-csv"></i> Download CSV</button>
    </div>

    <div class="content-grid">
        <div class="card">
            <div style="margin-bottom:20px;">
                <div class="big-label"><i class="fas fa-chart-line"></i> Overall completion</div>
                <div class="big-number"><span id="report-overall">0</span><span class="percent">%</span></div>
            </div>

            <div class="report-item">
                <div class="row">
                    <span class="name"><i class="fas fa-check-circle" style="color:#10b981;"></i> Covered</span>
                    <span class="pct" style="color:#10b981;" id="report-covered">0</span>
                </div>
                <div class="coverage-bar">
                    <div class="coverage-fill high" id="covered-bar" style="width:0%;"></div>
                </div>
            </div>

            <div class="report-item">
                <div class="row">
                    <span class="name"><i class="fas fa-circle" style="color:#f59e0b;"></i> Partial</span>
                    <span class="pct" style="color:#f59e0b;" id="report-partial">0</span>
                </div>
                <div class="coverage-bar">
                    <div class="coverage-fill medium" id="partial-bar" style="width:0%;"></div>
                </div>
            </div>

            <div class="report-item">
                <div class="row">
                    <span class="name"><i class="fas fa-times-circle" style="color:#ef4444;"></i> Not Covered</span>
                    <span class="pct" style="color:#ef4444;" id="report-notcovered">0</span>
                </div>
                <div class="coverage-bar">
                    <div class="coverage-fill low" id="notcovered-bar" style="width:0%;"></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title"><i class="fas fa-users"></i> By Class</div>
            <div id="class-coverage-container"></div>

            <div class="card-title" style="margin-top:20px;"><i class="fas fa-book"></i> By Subject</div>
            <div id="subject-coverage-container"></div>
        </div>
    </div>
</div>

<script>
    const reportData = @json($reportData);

    function renderReports(plans) {
        const total = plans.reduce((sum, plan) => sum + plan.total_topics, 0);
        const covered = plans.reduce((sum, plan) => sum + plan.covered_topics, 0);
        const partial = plans.reduce((sum, plan) => sum + plan.partial_topics, 0);
        const notCovered = plans.reduce((sum, plan) => sum + plan.not_covered_topics, 0);
        const avg = total > 0 ? Math.round(((covered + (partial * 0.5)) / total) * 100) : 0;

        $('#report-overall').text(avg);
        $('#report-covered').text(Math.round((covered / (total || 1)) * 100) + '%');
        $('#report-partial').text(Math.round((partial / (total || 1)) * 100) + '%');
        $('#report-notcovered').text(Math.round((notCovered / (total || 1)) * 100) + '%');

        // Update bars (as percentage of total)
        const totalTopics = total || 1;
        $('#covered-bar').css('width', ((covered / totalTopics) * 100) + '%');
        $('#partial-bar').css('width', ((partial / totalTopics) * 100) + '%');
        $('#notcovered-bar').css('width', ((notCovered / totalTopics) * 100) + '%');

        // Class coverage
        const classData = {};
        plans.forEach(p => {
            if (!classData[p.class]) classData[p.class] = [];
            classData[p.class].push(p);
        });

        const classContainer = $('#class-coverage-container');
        const classKeys = Object.keys(classData);
        if (classKeys.length === 0) {
            classContainer.html('<div style="color:#8a9bb5;text-align:center;padding:10px;"><i class="fas fa-inbox"></i> No data</div>');
        } else {
            classContainer.html(classKeys.map(cls => {
                const classPlans = classData[cls];
                const classTopics = classPlans.reduce((sum, plan) => sum + plan.total_topics, 0);
                const avgClass = classTopics > 0
                    ? Math.round((classPlans.reduce((sum, plan) => sum + plan.covered_topics + (plan.partial_topics * 0.5), 0) / classTopics) * 100)
                    : 0;
                const level = avgClass >= 75 ? 'high' : (avgClass >= 25 ? 'medium' : 'low');
                const icon = avgClass >= 75 ? 'fa-check-circle' : (avgClass >= 25 ? 'fa-circle' : 'fa-times-circle');
                const iconColor = avgClass >= 75 ? '#10b981' : (avgClass >= 25 ? '#f59e0b' : '#ef4444');
                return `
                    <div class="report-item">
                        <div class="row">
                            <span class="name"><i class="fas ${icon}" style="color:${iconColor};"></i> ${cls}</span>
                            <span class="pct">${avgClass}%</span>
                        </div>
                        <div class="coverage-bar">
                            <div class="coverage-fill ${level}" style="width:${avgClass}%;"></div>
                        </div>
                    </div>
                `;
            }).join(''));
        }

        // Subject coverage
        const subjectData = {};
        plans.forEach(p => {
            if (!subjectData[p.subject]) subjectData[p.subject] = [];
            subjectData[p.subject].push(p);
        });

        const subjectContainer = $('#subject-coverage-container');
        const subjectKeys = Object.keys(subjectData);
        if (subjectKeys.length === 0) {
            subjectContainer.html('<div style="color:#8a9bb5;text-align:center;padding:10px;"><i class="fas fa-inbox"></i> No data</div>');
        } else {
            subjectContainer.html(subjectKeys.map(sub => {
                const subjectPlans = subjectData[sub];
                const subjectTopics = subjectPlans.reduce((sum, plan) => sum + plan.total_topics, 0);
                const avgSub = subjectTopics > 0
                    ? Math.round((subjectPlans.reduce((sum, plan) => sum + plan.covered_topics + (plan.partial_topics * 0.5), 0) / subjectTopics) * 100)
                    : 0;
                const level = avgSub >= 75 ? 'high' : (avgSub >= 25 ? 'medium' : 'low');
                const icon = avgSub >= 75 ? 'fa-check-circle' : (avgSub >= 25 ? 'fa-circle' : 'fa-times-circle');
                const iconColor = avgSub >= 75 ? '#10b981' : (avgSub >= 25 ? '#f59e0b' : '#ef4444');
                return `
                    <div class="report-item">
                        <div class="row">
                            <span class="name"><i class="fas ${icon}" style="color:${iconColor};"></i> ${sub}</span>
                            <span class="pct">${avgSub}%</span>
                        </div>
                        <div class="coverage-bar">
                            <div class="coverage-fill ${level}" style="width:${avgSub}%;"></div>
                        </div>
                    </div>
                `;
            }).join(''));
        }
    }

    function applyReportFilters() {
        const cls = $('#report-class').val();
        const subject = $('#report-subject').val();

        let filtered = reportData;
        if (cls) filtered = filtered.filter(p => p.class === cls);
        if (subject) filtered = filtered.filter(p => p.subject === subject);
        const month = $('#report-month').val();
        if (month) filtered = filtered.filter(p => p.month === month);

        renderReports(filtered);
    }

    function downloadReport(format) {
        const params = new URLSearchParams();
        const cls = $('#report-class').val();
        const subject = $('#report-subject').val();
        const month = $('#report-month').val();
        if (cls) params.set('class', cls);
        if (subject) params.set('subject', subject);
        if (month) params.set('month', month);
        window.location.href = `{{ url('/admin-lesson-planner/reports') }}/${format}?${params.toString()}`;
    }

    function initReports() {
        if (typeof $ === 'undefined') {
            setTimeout(initReports, 50);
            return;
        }
        $(document).ready(function() {
            renderReports(reportData);
        });
    }

    initReports();

    window.refreshLessonPlannerViews = function() {
        if (typeof renderReports === 'function') {
            renderReports(reportData);
        }
    };
</script>
@endsection