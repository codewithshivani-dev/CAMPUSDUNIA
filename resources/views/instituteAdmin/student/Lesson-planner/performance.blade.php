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

    .performance-page { padding: 28px 32px; background: #f0f4f9; min-height: 100vh; }
    .performance-header { margin-bottom: 24px; }
    .performance-header h1 { margin: 0; color: #0a1e3c; font-size: 28px; font-weight: 800; }
    .performance-header h1 i { color: #10b981; margin-right: 10px; }
    .performance-header p { margin: 5px 0 0; color: #4b6a8b; font-size: 15px; }
    .student-details-card { display: flex; align-items: center; justify-content: space-between; gap: 24px; background: white; border: 1px solid #eaf0f6; border-radius: 24px; padding: 24px 28px; margin-bottom: 24px; box-shadow: 0 2px 10px rgba(0,0,0,.01); }
    .student-profile { display: flex; align-items: center; gap: 18px; min-width: 0; }
    .student-avatar { width: 64px; height: 64px; flex: 0 0 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #10b981, #34d399); color: white; font-size: 24px; font-weight: 700; }
    .student-name { color: #0a1e3c; font-size: 22px; font-weight: 700; margin: 0 0 4px; }
    .student-meta { display: flex; flex-wrap: wrap; gap: 14px; color: #4b6a8b; font-size: 13px; }
    .student-meta i, .student-context-label i { color: #10b981; margin-right: 5px; }
    .student-context { min-width: 250px; padding: 10px 16px; border-radius: 12px; background: #f8fafc; text-align: right; }
    .student-context-label { color: #4b6a8b; font-size: 12px; margin-bottom: 4px; }
    .student-context-value { color: #0a1e3c; font-size: 15px; font-weight: 700; }
    .details-button { display: inline-flex; align-items: center; gap: 7px; margin-top: 10px; padding: 8px 13px; border-radius: 10px; background: #10b981; color: white; text-decoration: none; font-size: 12px; font-weight: 600; }
    .details-button:hover { background: #059669; }
    .filter-card { background: white; border: 1px solid #eaf0f6; border-radius: 28px; padding: 24px 26px; margin-bottom: 24px; box-shadow: 0 2px 10px rgba(0,0,0,.01); }
    .filters { display: flex; flex-wrap: wrap; gap: 16px; align-items: end; }
    .filter-group { flex: 1; min-width: 180px; }
    .filter-group label { display: block; color: #4b6a8b; font-size: 13px; font-weight: 600; margin-bottom: 4px; }
    .filter-group label i { color: #10b981; margin-right: 4px; }
    .filter-group select { width: 100%; padding: 10px 14px; border: 2px solid #e6ecf3; border-radius: 12px; color: #0a1e3c; background: white; font-size: 14px; }
    .filter-group select:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15); }
    .filter-button, .reset-button { padding: 10px 18px; border-radius: 12px; font-weight: 600; font-size: 14px; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 7px; }
    .filter-button { border: 0; background: #10b981; color: white; }
    .filter-button:hover { background: #059669; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
    .reset-button { border: 2px solid #e6ecf3; color: #4b6a8b; background: transparent; }
    .reset-button:hover { background: #f8fafc; border-color: #10b981; }
    .summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .summary-card, .performance-card { background: white; border: 1px solid #eaf0f6; border-radius: 24px; box-shadow: 0 2px 10px rgba(0,0,0,.01); }
    .summary-card { padding: 20px 22px; position: relative; overflow: hidden; }
    .summary-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: #10b981; }
    .summary-label { color: #4b6a8b; font-size: 13px; display: flex; gap: 8px; align-items: center; }
    .summary-label i { color: #10b981; }
    .summary-value { color: #0a1e3c; font-size: 30px; font-weight: 800; margin-top: 3px; }
    .performance-card { padding: 22px 24px 18px; overflow: hidden; }
    .card-heading { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 16px; }
    .card-heading h2 { margin: 0; font-size: 17px; color: #0a1e3c; }
    .card-heading h2 i { color: #10b981; margin-right: 8px; }
    .year-badge { background: #d1fae5; color: #0b6e4f; padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .table-wrap { overflow-x: auto; border: 1px solid #eef2f7; border-radius: 16px; }
    table { width: 100%; min-width: 760px; border-collapse: collapse; font-size: 13px; }
    th { padding: 10px 12px; text-align: left; color: #4b6a8b; background: #f8fafc; font-size: 11px; text-transform: uppercase; letter-spacing: .3px; white-space: nowrap; border-bottom: 2px solid #e6ecf3; }
    th i { color: #10b981; margin-right: 4px; }
    td { padding: 13px 14px; border-top: 1px solid #eff3f8; color: #1f334f; vertical-align: middle; }
    td:not(:first-child), th:not(:first-child) { text-align: center; }
    .subject-name { color: #0a1e3c; font-weight: 700; text-align: left !important; }
    .metric { font-weight: 700; color: #0a1e3c; }
    .metric-sub { display: block; color: #8a9bb5; font-size: 11px; margin-top: 3px; }
    .performance-progress { width: 90px; height: 8px; background: #e6ecf3; border-radius: 10px; overflow: hidden; display: inline-block; margin-top: 5px; vertical-align: middle; }
    .performance-progress-fill { display: block; height: 100%; background: #10b981; border-radius: 10px; transition: width .4s ease; }
    .attendance { color: #10b981; }
    .details-button { display: inline-flex; align-items: center; gap: 6px; padding: 7px 11px; border-radius: 9px; background: #10b981; color: white; text-decoration: none; font-size: 12px; font-weight: 600; white-space: nowrap; }
    .details-button:hover { background: #059669; }
    .empty-state { padding: 54px 20px; text-align: center; color: #8a9bb5; }
    .empty-state i { color: #10b981; font-size: 48px; margin-bottom: 12px; }
    .empty-state p { margin: 4px 0 0; color: #b0c0d0; }
    @media (max-width: 820px) { 
        .performance-page { padding: 16px; } 
        .performance-card { padding: 16px 12px; } 
        .card-heading { align-items: flex-start; flex-direction: column; } 
        .student-details-card { align-items: flex-start; flex-direction: column; padding: 18px; } 
        .student-context { width: 100%; text-align: left; min-width: 0; } 
        .student-name { font-size: 19px; } 
        .student-info-banner { margin: 0 16px 16px 16px; padding: 14px 18px; }
        .filters { flex-direction: column; align-items: stretch; }
        .filter-group { min-width: auto; }
    }
    @media (max-width: 480px) {
        .summary-grid { grid-template-columns: 1fr 1fr; }
        .student-info-banner { flex-direction: column; text-align: center; }
        .student-info-banner i { font-size: 24px; }
        .student-avatar { width: 48px; height: 48px; flex: 0 0 48px; font-size: 18px; }
        .student-name { font-size: 17px; }
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

@php
    $totalPlans = collect($performanceData)->sum('plans');
    $totalTopics = collect($performanceData)->sum('topics');
    $coveredTopics = collect($performanceData)->sum('covered_topics');
    $totalMaterials = collect($performanceData)->sum('materials');
    $viewedMaterials = collect($performanceData)->sum('viewed_materials');
    $totalPresent = collect($performanceData)->sum('present');
    $totalAttendance = collect($performanceData)->sum('attendance_total');
    $coveragePercent = $totalTopics ? round(($coveredTopics / $totalTopics) * 100) : 0;
    $materialPercent = $totalMaterials ? round(($viewedMaterials / $totalMaterials) * 100) : 0;
    $attendancePercent = $totalAttendance ? round(($totalPresent / $totalAttendance) * 100) : 0;
@endphp

<div class="performance-page">
    <div class="performance-header">
        <h1><i class="fas fa-chart-line"></i>My Performance</h1>
        <p>Review your attendance, lesson progress, and learning-material activity.</p>
    </div>

    @if ($student)
        <div class="student-details-card">
            @php $studentName = trim($student->first_name . ' ' . ($student->middle_name ?: '') . ' ' . $student->last_name); @endphp
            <div class="student-profile">
                <div class="student-avatar">{{ collect(explode(' ', $studentName))->filter()->map(fn ($name) => strtoupper(substr($name, 0, 1)))->take(2)->implode('') }}</div>
                <div>
                    <h2 class="student-name">{{ $studentName }}</h2>
                    <div class="student-meta">
                        <span><i class="fas fa-id-badge"></i>Reg. No.: {{ $student->registration_number ?: 'Not available' }}</span>
                        @if ($student->email)<span><i class="fas fa-envelope"></i>{{ $student->email }}</span>@endif
                    </div>
                </div>
            </div>
            <div class="student-context">
                <div class="student-context-label">Department &amp; Course</div>
                <div class="student-context-value">{{ $departmentName ?: 'Not available' }} - {{ $className ?: 'Not available' }}</div>
                @if (!empty($teacherNames))<div class="student-context-label" style="margin-top: 5px;"><i class="fas fa-chalkboard-teacher"></i>Teacher: {{ implode(', ', $teacherNames) }}</div>@endif
            </div>
        </div>
    @endif

    <div class="summary-grid">
        <div class="summary-card"><div class="summary-label"><i class="fas fa-calendar-check"></i> Attendance</div><div class="summary-value">{{ $attendancePercent }}%</div></div>
        <div class="summary-card"><div class="summary-label"><i class="fas fa-book-open"></i> Topic coverage</div><div class="summary-value">{{ $coveragePercent }}%</div></div>
        <div class="summary-card"><div class="summary-label"><i class="fas fa-file-alt"></i> Materials viewed</div><div class="summary-value">{{ $materialPercent }}%</div></div>
        <div class="summary-card"><div class="summary-label"><i class="fas fa-layer-group"></i> Lesson plans</div><div class="summary-value">{{ $totalPlans }}</div></div>
    </div>
    
    <div class="filter-card">
        <form class="filters" method="GET" action="{{ route('student.lesson-planner.performance') }}">
            <div class="filter-group">
                <label for="performance-month"><i class="fas fa-calendar-alt"></i>Month</label>
                <select id="performance-month" name="month">
                    @for ($filterMonth = 1; $filterMonth <= 12; $filterMonth++)
                        <option value="{{ $filterMonth }}" {{ $filterMonth === $month ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $filterMonth, 1)) }} {{ $year }}</option>
                    @endfor
                </select>
            </div>
            <div class="filter-group">
                <label for="performance-subject"><i class="fas fa-book"></i>Subject</label>
                <select id="performance-subject" name="subject">
                    <option value="">All subjects</option>
                    @foreach ($subjects as $subjectId => $subjectName)
                        <option value="{{ $subjectId }}" {{ (string) $subjectFilter === (string) $subjectId ? 'selected' : '' }}>{{ $subjectName }}</option>
                    @endforeach
                </select>
            </div>
            <button class="filter-button" type="submit"><i class="fas fa-filter"></i> Apply filter</button>
            <a class="reset-button" href="{{ route('student.lesson-planner.performance') }}"><i class="fas fa-undo"></i> Reset</a>
        </form>
    </div>
    
    <div class="performance-card">
        <div class="card-heading">
            <h2><i class="fas fa-chart-bar"></i>Subject performance</h2>
            <span class="year-badge">{{ $year }}</span>
        </div>
        @if (empty($performanceData))
            <div class="empty-state"><i class="fas fa-chart-line"></i><div>No performance data is available yet.</div><p>Your subject results will appear here as lessons and attendance are recorded.</p></div>
        @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th><i class="fas fa-book"></i> Subject</th><th><i class="fas fa-user-check"></i> Attendance</th><th><i class="fas fa-list-check"></i> Topics covered</th><th><i class="fas fa-file-alt"></i> Materials viewed</th><th><i class="fas fa-layer-group"></i> Plans</th><th><i class="fas fa-cog"></i> Action</th></tr></thead>
                    <tbody>
                    @foreach ($performanceData as $subject)
                        @php
                            $subjectAttendance = $subject['attendance_total'] ? round(($subject['present'] / $subject['attendance_total']) * 100) : 0;
                            $subjectCoverage = $subject['topics'] ? round(($subject['covered_topics'] / $subject['topics']) * 100) : 0;
                            $subjectMaterials = $subject['materials'] ? round(($subject['viewed_materials'] / $subject['materials']) * 100) : 0;
                        @endphp
                        <tr>
                            <td class="subject-name">{{ $subject['subject'] }}</td>
                            <td><span class="metric attendance">{{ $subjectAttendance }}%</span><span class="metric-sub">{{ $subject['present'] }}/{{ $subject['attendance_total'] }} present</span></td>
                            <td><span class="metric">{{ $subjectCoverage }}%</span><span class="metric-sub">{{ $subject['covered_topics'] }}/{{ $subject['topics'] }}</span><span class="performance-progress" role="progressbar" aria-valuenow="{{ $subjectCoverage }}" aria-valuemin="0" aria-valuemax="100"><span class="performance-progress-fill" style="width: {{ $subjectCoverage }}%"></span></span></td>
                            <td><span class="metric">{{ $subjectMaterials }}%</span><span class="metric-sub">{{ $subject['viewed_materials'] }}/{{ $subject['materials'] }}</span><span class="performance-progress" role="progressbar" aria-valuenow="{{ $subjectMaterials }}" aria-valuemin="0" aria-valuemax="100"><span class="performance-progress-fill" style="width: {{ $subjectMaterials }}%"></span></span></td>
                            <td><span class="metric">{{ $subject['plans'] }}</span></td>
                            <td><a class="details-button" href="{{ route('student.lesson-planner.student-details', ['subject' => $subject['subject_id']]) }}"><i class="fas fa-eye"></i> View details</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection