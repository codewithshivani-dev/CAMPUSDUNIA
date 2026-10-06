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

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #10b981;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 20px;
        transition: 0.2s;
        padding: 8px 16px;
        background: white;
        border-radius: 12px;
        border: 1px solid #e6ecf3;
    }
    .back-link:hover {
        color: #059669;
        background: #f0fdf4;
        border-color: #10b981;
        transform: translateX(-4px);
    }

    .plan-detail-card {
        background: white;
        border-radius: 24px;
        padding: 28px 32px;
        border: 1px solid #eaf0f6;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.01);
    }

    .plan-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 16px;
        padding-bottom: 20px;
        border-bottom: 2px solid #eef2f6;
        margin-bottom: 20px;
    }
    .plan-header .title {
        font-size: 24px;
        font-weight: 700;
        color: #0a1e3c;
        margin: 0;
    }
    .plan-header .title i { color: #10b981; margin-right: 10px; }
    .plan-header .meta {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 8px;
    }
    .plan-header .meta span {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        color: #4b6a8b;
        background: #f8fafc;
        padding: 4px 14px;
        border-radius: 20px;
    }
    .plan-header .meta i { color: #10b981; }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px 24px;
        margin-bottom: 24px;
    }
    .info-grid .info-item {
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 12px;
    }
    .info-grid .info-item .label {
        font-size: 12px;
        color: #8a9bb5;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        font-weight: 600;
    }
    .info-grid .info-item .value {
        font-size: 15px;
        font-weight: 600;
        color: #0a1e3c;
        margin-top: 2px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #0a1e3c;
        margin: 24px 0 16px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .section-title i { color: #10b981; }
    .section-title .badge {
        font-size: 13px;
        font-weight: 400;
        color: #4b6a8b;
        background: #eef2f6;
        padding: 2px 12px;
        border-radius: 20px;
    }

    .topic-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .topic-item-detail {
        display: flex;
        align-items: center;
        padding: 10px 14px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #eaf0f6;
        gap: 12px;
        flex-wrap: wrap;
    }
    .topic-item-detail .num {
        font-weight: 700;
        color: #10b981;
        min-width: 30px;
        font-size: 14px;
    }
    .topic-item-detail .topic-title {
        flex: 1;
        font-weight: 500;
        color: #0a1e3c;
    }
    .topic-item-detail .topic-status {
        font-size: 12px;
        font-weight: 600;
        padding: 2px 12px;
        border-radius: 20px;
    }
    .topic-item-detail .topic-status.covered { background: #d1fae5; color: #0b6e4f; }
    .topic-item-detail .topic-status.pending { background: #fef3c7; color: #a16207; }
    .topic-item-detail .topic-status.not-covered { background: #fee2e2; color: #dc2626; }
    .topic-item-detail .topic-resources {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    .topic-item-detail .topic-resources a {
        color: #10b981;
        text-decoration: none;
        font-size: 12px;
        background: white;
        padding: 2px 10px;
        border-radius: 20px;
        border: 1px solid #dce4ed;
        transition: 0.2s;
    }
    .topic-item-detail .topic-resources a:hover {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }
    .topic-item-detail .topic-resources .resource-tag {
        background: #eef2f6;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 11px;
        color: #4b6a8b;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 16px;
        border-radius: 60px;
        font-size: 13px;
        font-weight: 600;
    }
    .status-badge.active { background: #d1fae5; color: #0b6e4f; }
    .status-badge.draft { background: #eef2f6; color: #4b6a8b; }
    .status-badge.review { background: #dbeafe; color: #1d4ed8; }

    .empty-state {
        text-align: center;
        padding: 30px;
        color: #8a9bb5;
    }
    .empty-state i {
        font-size: 40px;
        color: #dce4ed;
        margin-bottom: 10px;
        display: block;
    }

    .loading-spinner {
        text-align: center;
        padding: 60px;
        color: #8a9bb5;
    }
    .loading-spinner i {
        font-size: 40px;
        color: #10b981;
        margin-bottom: 12px;
        display: block;
    }

    @media (max-width: 820px) {
        .page-content { padding: 16px; }
        .plan-detail-card { padding: 20px; }
        .info-grid { grid-template-columns: 1fr; }
        .plan-header { flex-direction: column; }
        .topic-item-detail { flex-direction: column; align-items: flex-start; }
        .student-info-banner { margin: 0 16px 16px 16px; padding: 14px 18px; }
    }

    @media (max-width: 480px) {
        .student-info-banner { flex-direction: column; text-align: center; }
        .student-info-banner i { font-size: 24px; }
        .plan-header .title { font-size: 20px; }
        .plan-detail-card { padding: 16px; }
        .topic-item-detail .topic-title { font-size: 13px; }
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
            <a href="{{ route('student.lesson-planner.plans') }}" class="nav-tab active" style="border:none; background:transparent; padding:8px 10px; border-radius:60px; font-weight:500; font-size:14px; color:#4b6a8b; cursor:pointer; transition:0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:8px; background:white; color:#10b981; box-shadow:0 2px 12px rgba(16,185,129,0.15);">
                <i class="fas fa-calendar-alt"></i> All Plans
            </a>
            <a href="{{ route('student.lesson-planner.coverage') }}" class="nav-tab" style="border:none; background:transparent; padding:8px 10px; border-radius:60px; font-weight:500; font-size:14px; color:#4b6a8b; cursor:pointer; transition:0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
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
    <a href="{{ route('student.lesson-planner.plans') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to All Plans
    </a>

    <div class="plan-detail-card" id="planDetailContainer">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            Loading plan details...
        </div>
    </div>
</div>

<script>
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    async function loadPlanDetail() {
        const planId = window.location.pathname.split('/').pop();
        const container = document.getElementById('planDetailContainer');

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
            const plans = Array.isArray(data) ? data : [];
            const plan = plans.find(p => String(p.id) === String(planId));

            if (!plan) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-file-alt"></i>
                        <div style="font-size:18px; font-weight:600; color:#0a1e3c;">Plan not found</div>
                        <div style="font-size:14px; margin-top:4px;">The lesson plan you're looking for doesn't exist.</div>
                    </div>
                `;
                return;
            }

            const statusClass = plan.status || 'draft';
            const statusLabel = statusClass.charAt(0).toUpperCase() + statusClass.slice(1);

            // Collect all topics
            let allTopics = [];
            if (plan.dailyTopics) {
                Object.keys(plan.dailyTopics).forEach(date => {
                    if (plan.dailyTopics[date] && Array.isArray(plan.dailyTopics[date])) {
                        plan.dailyTopics[date].forEach(topic => {
                            allTopics.push({ ...topic, date: date });
                        });
                    }
                });
            }
            if (allTopics.length === 0 && plan.topics && Array.isArray(plan.topics)) {
                allTopics = plan.topics;
            }

            const totalTopics = allTopics.length;
            const coveredTopics = allTopics.filter(t => t.covered || t.coverage_status === 'covered').length;
            const coveragePct = totalTopics > 0 ? Math.round((coveredTopics / totalTopics) * 100) : 0;

            // Format date range
            const dates = Object.keys(plan.dailyTopics || {}).sort();
            const startDate = dates.length > 0 ? dates[0] : plan.startDate;
            const endDate = dates.length > 0 ? dates[dates.length - 1] : plan.endDate;

            const html = `
                <div class="plan-header">
                    <div>
                        <h1 class="title"><i class="fas fa-file-alt"></i> ${plan.title || 'Untitled Plan'}</h1>
                        <div class="meta">
                            <span><i class="fas fa-users"></i> ${plan.class || 'N/A'}</span>
                            <span><i class="fas fa-book"></i> ${plan.subject || 'N/A'}</span>
                            <span><i class="fas fa-calendar-week"></i> ${plan.week || 'Week 1'}</span>
                            <span><i class="fas fa-calendar-alt"></i> ${startDate ? formatDate(startDate) : 'N/A'} ${endDate && endDate !== startDate ? '→ ' + formatDate(endDate) : ''}</span>
                        </div>
                    </div>
                    <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                        <span class="status-badge ${statusClass}">${statusLabel}</span>
                        <span style="background: #10b981; color: white; padding: 4px 16px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                            ${coveragePct}% Coverage
                        </span>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="label"><i class="fas fa-graduation-cap"></i> Class</div>
                        <div class="value">${plan.class || 'N/A'}</div>
                    </div>
                    <div class="info-item">
                        <div class="label"><i class="fas fa-book"></i> Subject</div>
                        <div class="value">${plan.subject || 'N/A'}</div>
                    </div>
                    <div class="info-item">
                        <div class="label"><i class="fas fa-user"></i> Teacher</div>
                        <div class="value">${plan.teacher || 'N/A'}</div>
                    </div>
                    <div class="info-item">
                        <div class="label"><i class="fas fa-calendar-alt"></i> Duration</div>
                        <div class="value">${startDate ? formatDate(startDate) : 'N/A'} ${endDate && endDate !== startDate ? '— ' + formatDate(endDate) : ''}</div>
                    </div>
                    ${plan.objectives ? `
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <div class="label"><i class="fas fa-bullseye"></i> Learning Objectives</div>
                            <div class="value">${plan.objectives}</div>
                        </div>
                    ` : ''}
                </div>

                <div class="section-title">
                    <i class="fas fa-list"></i> Topics
                    <span class="badge">${totalTopics} topics • ${coveredTopics} covered</span>
                </div>

                ${allTopics.length === 0 ? `
                    <div class="empty-state">
                        <i class="fas fa-book-open"></i>
                        <div>No topics found for this plan</div>
                    </div>
                ` : `
                    <div class="topic-list">
                        ${allTopics.map((t, index) => {
                            const isCovered = !!(t.covered || t.coverage_status === 'covered');
                            const statusText = isCovered ? 'Covered' : 'Pending';
                            const statusClass = isCovered ? 'covered' : 'pending';
                            const hasVideo = t.video ? true : false;
                            const hasFiles = t.files && t.files.length > 0;

                            return `
                                <div class="topic-item-detail">
                                    <span class="num">#${t.number || index + 1}</span>
                                    <span class="topic-title">${t.title || 'Topic'}</span>
                                    <span class="topic-status ${statusClass}">${statusText}</span>
                                    <div class="topic-resources">
                                        ${hasVideo ? `<a href="${t.video}" target="_blank"><i class="fab fa-youtube"></i> Watch</a>` : ''}
                                        ${hasFiles ? t.files.map(f => `<a href="${f.url || f.path || '#'}" target="_blank"><i class="fas fa-file"></i> ${f.name || 'File'}</a>`).join('') : ''}
                                        ${t.resources && t.resources.length > 0 ? t.resources.map(r => `<span class="resource-tag">${r}</span>`).join('') : ''}
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                `}

                ${plan.methods && plan.methods.length > 0 ? `
                    <div class="section-title"><i class="fas fa-chalkboard-teacher"></i> Teaching Methods</div>
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        ${plan.methods.map(m => `<span style="background:#eef2f6;padding:4px 16px;border-radius:20px;font-size:13px;color:#4b6a8b;">${m}</span>`).join('')}
                    </div>
                ` : ''}

                ${plan.aids && plan.aids.length > 0 ? `
                    <div class="section-title"><i class="fas fa-tools"></i> Teaching Aids</div>
                    <div style="display:flex; gap:8px; flex-wrap:wrap;">
                        ${plan.aids.map(a => `<span style="background:#eef2f6;padding:4px 16px;border-radius:20px;font-size:13px;color:#4b6a8b;">${a}</span>`).join('')}
                    </div>
                ` : ''}

                ${plan.resources ? `
                    <div class="section-title"><i class="fas fa-box"></i> Resources Required</div>
                    <div style="background:#f8fafc;padding:12px 16px;border-radius:12px;color:#4b6a8b;font-size:14px;">${plan.resources}</div>
                ` : ''}
            `;

            container.innerHTML = html;

        } catch (error) {
            console.error('Error loading plan detail:', error);
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-circle" style="color:#ef4444;"></i>
                    <div style="font-size:18px; font-weight:600; color:#0a1e3c;">Error loading plan</div>
                    <div style="font-size:14px; margin-top:4px; color:#4b6a8b;">Please try again later</div>
                </div>
            `;
        }
    }

    function formatDate(dateStr) {
        const d = new Date(dateStr + 'T00:00:00');
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    document.addEventListener('DOMContentLoaded', loadPlanDetail);
</script>
@endsection