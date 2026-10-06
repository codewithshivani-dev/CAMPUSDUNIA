@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('styles')
<style>
    .page-content {
        padding: 28px 32px;
        background: #f0f4f9;
        min-height: 100vh;
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

    /* Filter Section */
    .filter-card {
        background: white;
        border-radius: 28px;
        padding: 24px 26px;
        border: 1px solid #eaf0f6;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.01);
        margin-bottom: 24px;
    }
    .filter-section {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: flex-end;
    }
    .filter-group {
        flex: 1;
        min-width: 150px;
    }
    .filter-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #4b6a8b;
        margin-bottom: 4px;
    }
    .filter-group select {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e6ecf3;
        border-radius: 12px;
        font-size: 14px;
        background: white;
        color: #0a1e3c;
        transition: border 0.2s;
    }
    .filter-group select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .btn {
        padding: 10px 22px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-primary {
        background: #2563eb;
        color: white;
    }
    .btn-primary:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .btn-outline {
        background: transparent;
        color: #4b6a8b;
        border: 2px solid #e6ecf3;
    }
    .btn-outline:hover {
        background: #f8fafc;
        border-color: #2563eb;
    }

    /* Card */
    .card {
        background: white;
        border-radius: 28px;
        padding: 24px 26px 20px;
        border: 1px solid #eaf0f6;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.01);
        overflow: hidden;
    }
    .card-title {
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 18px;
        color: #0a1e3c;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    .card-title i { color: #2563eb; margin-right: 8px; }
    .card-title .count {
        font-size: 14px;
        color: #4b6a8b;
        font-weight: 400;
    }

    /* Table */
    .table-responsive {
        overflow-x: auto;
        max-height: 600px;
        overflow-y: auto;
        border-radius: 16px;
        border: 1px solid #eef2f7;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        min-width: 700px;
    }
    th {
        text-align: left;
        padding: 10px 12px;
        font-weight: 600;
        color: #4b6a8b;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border-bottom: 2px solid #e6ecf3;
        position: sticky;
        top: 0;
        background: #f8fafc;
        z-index: 10;
        white-space: nowrap;
    }
    th i { color: #2563eb; margin-right: 4px; }
    td {
        padding: 10px 12px;
        border-bottom: 1px solid #eff3f8;
        vertical-align: middle;
        white-space: nowrap;
    }
    tr:hover td {
        background: #f8fafc;
    }
    tr:last-child td {
        border-bottom: none;
    }

    .student-name-cell {
        font-weight: 600;
        color: #0a1e3c;
        min-width: 140px;
        cursor: pointer;
        transition: color 0.2s;
    }
    .student-name-cell:hover {
        color: #2563eb;
    }
    .student-name-cell .reg-no {
        font-weight: 400;
        font-size: 11px;
        color: #8a9bb5;
        display: block;
    }
    .student-name-cell .view-detail {
        font-size: 10px;
        color: #2563eb;
        font-weight: 500;
        display: block;
        margin-top: 2px;
    }

    .performance-cell {
        text-align: center;
    }
    .performance-cell .percent {
        font-size: 20px;
        font-weight: 700;
        display: block;
    }
    .performance-cell .sub-text {
        font-size: 11px;
        color: #8a9bb5;
        display: block;
    }

    .progress-bar-container {
        width: 100px;
        height: 8px;
        background: #e6ecf3;
        border-radius: 10px;
        overflow: hidden;
        display: inline-block;
        margin-top: 4px;
    }
    .progress-bar-container .fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.5s;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .status-excellent { background: #d1fae5; color: #065f46; }
    .status-good { background: #dbeafe; color: #1e40af; }
    .status-average { background: #fef3c7; color: #92400e; }
    .status-poor { background: #fee2e2; color: #991b1b; }

    /* ====================== */
    /* MODAL STYLES */
    /* ====================== */
    .student-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: fadeIn 0.25s ease;
    }
    .student-modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 28px;
        max-width: 950px;
        width: 100%;
        max-height: 92vh;
        overflow: hidden;
        animation: slideUp 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .modal-header {
        padding: 20px 28px;
        border-bottom: 2px solid #e6ecf3;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 28px 28px 0 0;
    }
    .modal-header .student-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .modal-header .student-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563eb, #60a5fa);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 20px;
        flex-shrink: 0;
    }
    .modal-header .student-details h3 {
        margin: 0;
        font-size: 20px;
        color: #0a1e3c;
    }
    .modal-header .student-details .sub {
        font-size: 13px;
        color: #4b6a8b;
        margin: 2px 0 0;
    }
    .modal-header .student-details .sub i {
        margin-right: 4px;
        color: #2563eb;
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 28px;
        color: #8a9bb5;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 8px;
        transition: background 0.2s;
        line-height: 1;
    }
    .modal-close:hover {
        background: #f1f5f9;
    }
    .student-detail-link {
        background: #2563eb;
        color: white;
        text-decoration: none;
        border-radius: 10px;
        padding: 9px 14px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-left: auto;
        margin-right: 8px;
    }
    .student-detail-link:hover {
        background: #1d4ed8;
    }

    /* Modal Filter */
    .modal-filter {
        padding: 14px 28px;
        background: white;
        border-bottom: 1px solid #eef2f7;
        display: flex;
        gap: 20px;
        align-items: center;
        flex-wrap: wrap;
        flex-shrink: 0;
    }
    .modal-filter label {
        font-size: 13px;
        font-weight: 600;
        color: #4b6a8b;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modal-filter select {
        padding: 8px 16px;
        border: 2px solid #e6ecf3;
        border-radius: 10px;
        font-size: 13px;
        background: white;
        color: #0a1e3c;
        font-weight: 500;
        cursor: pointer;
    }
    .modal-filter select:focus {
        outline: none;
        border-color: #2563eb;
    }
    .modal-filter .summary-stats {
        display: flex;
        gap: 16px;
        margin-left: auto;
        font-size: 13px;
        background: #f8fafc;
        padding: 6px 16px;
        border-radius: 12px;
    }
    .modal-filter .summary-stats span {
        display: flex;
        align-items: center;
        gap: 5px;
        font-weight: 500;
    }
    .modal-filter .summary-stats .present { color: #10b981; }
    .modal-filter .summary-stats .absent { color: #ef4444; }
    .modal-filter .summary-stats .blue { color: #2563eb; }

    /* Modal Body */
    .modal-body {
        padding: 16px 28px 28px;
        overflow-y: auto;
        flex: 1;
    }
    .modal-body .table-responsive {
        max-height: 400px;
        border: 1px solid #eef2f7;
        border-radius: 12px;
    }
    .modal-body table {
        min-width: 850px;
        font-size: 13px;
    }
    .modal-body th {
        background: #f8fafc;
        font-size: 11px;
        padding: 8px 10px;
        text-align: center;
    }
    .modal-body td {
        padding: 8px 10px;
        text-align: center;
        vertical-align: middle;
    }

    /* Date Column */
    .modal-body .date-cell {
        font-weight: 600;
        color: #0a1e3c;
        text-align: left;
        min-width: 70px;
    }
    .modal-body .date-cell .day-name {
        font-weight: 400;
        font-size: 11px;
        color: #8a9bb5;
        display: block;
    }

    /* Presence Badge */
    .presence-badge {
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .presence-badge.present {
        background: #d1fae5;
        color: #065f46;
    }
    .presence-badge.absent {
        background: #fee2e2;
        color: #991b1b;
    }
    .presence-badge.no-class {
        background: #f1f5f9;
        color: #8a9bb5;
    }

    /* Material Items inside modal */
    .material-items-container {
        display: flex;
        flex-direction: column;
        gap: 4px;
        align-items: flex-start;
    }
    .material-item-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 8px;
        background: #f8fafc;
        width: 100%;
        border-left: 3px solid transparent;
    }
    .material-item-row.video {
        border-left-color: #2563eb;
    }
    .material-item-row.link {
        border-left-color: #10b981;
    }

    .material-item-row .mat-icon {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
    }
    .material-item-row .mat-icon.video { background: #dbeafe; color: #2563eb; }
    .material-item-row .mat-icon.link { background: #dcfce7; color: #10b981; }

    .material-item-row .mat-name {
        flex: 1;
        text-align: left;
        font-weight: 500;
        color: #0a1e3c;
        font-size: 12px;
        text-decoration: none;
    }
    .material-item-row .mat-name:hover {
        color: #2563eb;
    }
    .material-item-row .mat-click-count {
        font-size: 11px;
        font-weight: 600;
        color: #4b6a8b;
        background: #e6ecf3;
        padding: 1px 10px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .material-item-row .mat-click-count i {
        color: #2563eb;
        font-size: 11px;
    }
    .material-item-row .mat-click-count.high {
        background: #d1fae5;
        color: #065f46;
    }
    .material-item-row .mat-click-count.high i {
        color: #10b981;
    }
    .material-item-row .mat-click-count.low {
        background: #fee2e2;
        color: #991b1b;
    }
    .material-item-row .mat-click-count.low i {
        color: #ef4444;
    }

    .material-item-row .mat-status {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 10px;
        border-radius: 12px;
    }
    .material-item-row .mat-status.viewed {
        background: #d1fae5;
        color: #065f46;
    }
    .material-item-row .mat-status.not-viewed {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Legend */
    .legend {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #e6ecf3;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #4b6a8b;
    }
    .legend-item .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }
    .legend-item .dot.green { background: #10b981; }
    .legend-item .dot.amber { background: #f59e0b; }
    .legend-item .dot.red { background: #ef4444; }
    .legend-item .dot.gray { background: #e6ecf3; }
    .legend-item .click-hint {
        color: #2563eb;
        font-weight: 500;
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #8a9bb5;
    }
    .empty-icon {
        font-size: 56px;
        margin-bottom: 12px;
        color: #10b981;
    }
    .empty-state .sub {
        color: #b0c0d0;
        font-size: 14px;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideUp {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    @media (max-width: 820px) {
        .page-content {
            padding: 16px;
        }
        .filter-section {
            flex-direction: column;
        }
        .filter-group {
            min-width: 100%;
        }
        .card {
            padding: 12px;
        }
        .table-responsive {
            max-height: 400px;
        }
        .student-name-cell {
            min-width: 100px;
            font-size: 12px;
        }
        .performance-cell .percent {
            font-size: 16px;
        }
        .modal-content {
            max-width: 100%;
            max-height: 95vh;
        }
        .modal-header {
            padding: 16px;
        }
        .modal-filter {
            padding: 12px 16px;
            flex-direction: column;
            align-items: stretch;
        }
        .modal-filter .summary-stats {
            margin-left: 0;
            justify-content: center;
        }
        .modal-body {
            padding: 12px 16px 16px;
        }
        .modal-body .table-responsive {
            max-height: 300px;
        }
        .modal-body table {
            min-width: 600px;
            font-size: 12px;
        }
        .material-item-row {
            font-size: 11px;
            padding: 3px 8px;
            flex-wrap: wrap;
        }
        .material-item-row .mat-name {
            font-size: 11px;
        }
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
            <a href="{{ route('lesson-planner.review', $plannerContextQuery) }}" class="nav-tab active" style="border:none; background:transparent; padding:8px 10px; border-radius:60px; font-weight:500; font-size:14px; color:#4b6a8b; cursor:pointer; transition:0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:8px; background:white; color:#2563eb; box-shadow:0 2px 12px rgba(37,99,235,0.12);">
                <i class="fas fa-check-double"></i> Performance
            </a>
            <a href="{{ route('lesson-planner.reports', $plannerContextQuery) }}" class="nav-tab" style="border:none; background:transparent; padding:8px 10px; border-radius:60px; font-weight:500; font-size:14px; color:#4b6a8b; cursor:pointer; transition:0.2s; text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
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

<!-- ===== REVIEW CONTENT ===== -->
<div class="page-content">
    <p class="page-subtitle">
        <i class="fas fa-chart-line"></i> Student Performance & Material Access Tracker
    </p>

    <!-- Filter Section -->
    <div class="filter-card">
        <div class="filter-section">
            <div class="filter-group">
                <label for="department-select"><i class="fas fa-building"></i> Department</label>
                <select id="department-select" onchange="onDepartmentChange()">
                    <option value="">-- Select Department --</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="course-select"><i class="fas fa-graduation-cap"></i> Course</label>
                <select id="course-select" onchange="loadStudentData()">
                    <option value="">-- Select Course --</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="month-select"><i class="fas fa-calendar-alt"></i> Month</label>
                <select id="month-select" onchange="loadStudentData()">
                    @for ($month = 1; $month <= 12; $month++)
                        <option value="{{ $month }}" {{ $month == now()->month ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}</option>
                    @endfor
                </select>
            </div>
            
            <div class="filter-group" style="flex:0 0 auto; min-width:auto;">
                <label>&nbsp;</label>
                <button class="btn btn-outline" onclick="resetFilters()">
                    <i class="fas fa-undo"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Student Table -->
    <div class="card">
        <div class="card-title">
                <span><i class="fas fa-users"></i> Student Performance - <span id="class-display">Select Department</span></span>
            <span class="count" id="record-count"><i class="fas fa-circle" style="color:#2563eb; font-size:10px;"></i> 0 students</span>
        </div>
        <div class="table-responsive" id="table-container">
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-search"></i></div>
                <div>Select a department and course to view student performance</div>
                <div class="sub">Click on any student name to view detailed daily presence and material access</div>
            </div>
        </div>
        
        <!-- Legend -->
        <div class="legend">
            <span class="legend-item"><span class="dot green"></span> Excellent (80%+)</span>
            <span class="legend-item"><span class="dot" style="background:#2563eb;"></span> Good (60-79%)</span>
            <span class="legend-item"><span class="dot amber"></span> Average (40-59%)</span>
            <span class="legend-item"><span class="dot red"></span> Poor (&lt;40%)</span>
            <span class="legend-item"><i class="fas fa-mouse-pointer click-hint"></i> <span class="click-hint">Click student name to open details</span></span>
        </div>
    </div>
</div>

<!-- Student Detail Modal -->
<div class="student-modal" id="student-modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="student-info">
                <div class="student-avatar" id="modal-avatar">MS</div>
                <div class="student-details">
                    <h3 id="modal-name">Muskaan Sharma</h3>
                    <p class="sub"><i class="fas fa-id-badge"></i> Reg. No.: <span id="modal-reg-no">10001</span> &nbsp;·&nbsp; <i class="fas fa-envelope"></i> <span id="modal-email">muskaan@example.com</span></p>
                </div>
            </div>
            <a class="student-detail-link" id="student-detail-link" href="#">
                <i class="fas fa-up-right-from-square"></i> Open page
            </a>
            <button class="modal-close" onclick="closeStudentModal()">&times;</button>
        </div>
        
        <div class="modal-filter">
            <label><i class="fas fa-calendar-alt"></i> Select Month:
                <select id="modal-month-filter" onchange="renderStudentDetail()">
                    <option value="1">January</option>
                </select>
            </label>
            <div class="summary-stats">
                <span class="present"><i class="fas fa-check-circle"></i> Present: <strong id="modal-present-count">0</strong></span>
                <span class="absent"><i class="fas fa-times-circle"></i> Absent: <strong id="modal-absent-count">0</strong></span>
                <span class="blue"><i class="fas fa-percent"></i> <strong id="modal-percent">0%</strong></span>
            </div>
        </div>
        
        <div class="modal-body" id="modal-body">
            <!-- Dynamic content -->
        </div>
    </div>
</div>

<script>
    const reviewData = @json($reviewData);
    const reviewYear = @json($year);
    let mockData = reviewData;
    let currentStudentData = null;

    function populateDepartments() {
        const departmentSelect = document.getElementById('department-select');
        Object.entries(reviewData).forEach(([departmentId, department]) => {
            const option = document.createElement('option');
            option.value = departmentId;
            option.textContent = department.department_name;
            departmentSelect.appendChild(option);
        });
    }

    function generateAttendanceData(departmentId, courseId) {
        return reviewData[departmentId].courses[courseId];
    }

    function onDepartmentChange() {
        const departmentId = document.getElementById('department-select').value;
        const courseSelect = document.getElementById('course-select');
        
        courseSelect.innerHTML = '<option value="">-- Select Course --</option>';
        
        if (departmentId && reviewData[departmentId]) {
            const courses = reviewData[departmentId].courses || {};
            Object.entries(courses).forEach(([courseId, data]) => {
                const option = document.createElement('option');
                option.value = courseId;
                option.textContent = `${data.course || 'Course'} - ${data.subject}`;
                courseSelect.appendChild(option);
            });
            courseSelect.disabled = false;
        } else {
            courseSelect.disabled = true;
        }
    }

    function resetFilters() {
        document.getElementById('department-select').value = '';
        document.getElementById('course-select').innerHTML = '<option value="">-- Select Course --</option>';
        document.getElementById('course-select').disabled = true;
        document.getElementById('month-select').value = new Date().getMonth() + 1;
        document.getElementById('table-container').innerHTML = `
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-search"></i></div>
                <div>Select a department and course to view student performance</div>
                <div class="sub">Click on any student name to view detailed daily presence and material access</div>
            </div>
        `;
        document.getElementById('class-display').textContent = 'Select Department';
        document.getElementById('record-count').textContent = '0 students';
    }

    function loadStudentData() {
        const departmentId = document.getElementById('department-select').value;
        const courseId = document.getElementById('course-select').value;
        const month = parseInt(document.getElementById('month-select').value, 10);
        
        if (!departmentId || !courseId) {
            alert('Please select both Department and Course');
            return;
        }

        const key = `${departmentId}-${courseId}`;
        
        if (!mockData[key]) {
            mockData[key] = generateAttendanceData(departmentId, courseId);
        }

        renderStudentTable(mockData[key], departmentId, courseId, month);
    }

    function renderStudentTable(data, departmentId, courseId, month) {
        const container = document.getElementById('table-container');
        const { students, months } = data;
        
        document.getElementById('class-display').textContent = `${reviewData[departmentId].department_name} - ${data.course || 'Course'} - ${data.subject} - ${new Date(reviewYear, month - 1, 1).toLocaleString('en-US', { month: 'long' })}`;
        document.getElementById('record-count').textContent = `${students.length} students`;

        const studentsWithPerformance = students.map(student => {
            let totalPresent = 0;
            let totalTeachingDays = 0;
            let totalMaterials = 0;
            let viewedMaterials = 0;
            
            const days = months[month] || [];
            days.forEach(day => {
                const dayData = student[`day_${month}_${day.day}`];
                if (dayData && Array.isArray(dayData.materials)) {
                    totalMaterials += dayData.materials.length;
                    viewedMaterials += dayData.materials.filter(material => material.viewed).length;
                }
                if (day.isWeekend || !day.classScheduled) return;
                totalTeachingDays++;
                if (dayData && dayData.status === 'present') {
                    totalPresent++;
                }
            });
            
            const attendancePercent = totalTeachingDays > 0 ? Math.round((totalPresent / totalTeachingDays) * 100) : 0;
            const studentPercent = totalMaterials > 0 ? Math.round((viewedMaterials / totalMaterials) * 100) : 0;
            return { ...student, attendancePercent, studentPercent, totalDays: totalTeachingDays, presentDays: totalPresent, totalMaterials, viewedMaterials };
        });

        studentsWithPerformance.sort((a, b) => b.attendancePercent - a.attendancePercent);

        let html = `<table>
            <thead>
                <tr>
                    <th style="min-width:160px;"><i class="fas fa-user"></i> Student</th>
                    <th style="min-width:100px;text-align:center;"><i class="fas fa-id-badge"></i> Reg. No.</th>
                    <th style="min-width:140px;text-align:center;"><i class="fas fa-user-check"></i> Attendance Performance</th>
                    <th style="min-width:140px;text-align:center;"><i class="fas fa-file-alt"></i> Student Performance</th>
                    <th style="min-width:100px;text-align:center;"><i class="fas fa-trophy"></i> Status</th>
                </tr>
            </thead>
            <tbody>
        `;

        studentsWithPerformance.forEach(student => {
            const attendancePercent = student.attendancePercent;
            const studentPercent = student.studentPercent;
            
            let statusClass = 'status-poor';
            let statusText = 'Poor';
            let statusIcon = 'fa-exclamation-triangle';
            if (attendancePercent >= 80) { statusClass = 'status-excellent'; statusText = 'Excellent'; statusIcon = 'fa-star'; }
            else if (attendancePercent >= 60) { statusClass = 'status-good'; statusText = 'Good'; statusIcon = 'fa-thumbs-up'; }
            else if (attendancePercent >= 40) { statusClass = 'status-average'; statusText = 'Average'; statusIcon = 'fa-minus'; }
            
            let color = '#ef4444';
            if (attendancePercent >= 80) color = '#10b981';
            else if (attendancePercent >= 60) color = '#2563eb';
            else if (attendancePercent >= 40) color = '#f59e0b';

            const studentColor = studentPercent >= 80 ? '#10b981' : (studentPercent >= 60 ? '#2563eb' : (studentPercent >= 40 ? '#f59e0b' : '#ef4444'));

            html += `
                <tr>
                    <td class="student-name-cell" onclick="openStudentDetail('${student.id}', '${departmentId}', '${courseId}')">
                        ${student.name}
                        <span class="reg-no">${student.regNo}</span>
                        <span class="view-detail"><i class="fas fa-chevron-right"></i> Click for details</span>
                    </td>
                    <td style="text-align:center;color:#4b6a8b;font-weight:500;">${student.regNo}</td>
                    <td class="performance-cell">
                        <span class="percent" style="color:${color};">${attendancePercent}%</span>
                        <span class="sub-text">${student.presentDays}/${student.totalDays} days present</span>
                        <div class="progress-bar-container">
                            <div class="fill" style="width:${attendancePercent}%;background:${color};"></div>
                        </div>
                    </td>
                    <td class="performance-cell">
                        <span class="percent" style="color:${studentColor};">${studentPercent}%</span>
                        <span class="sub-text">${student.viewedMaterials}/${student.totalMaterials} materials viewed</span>
                        <div class="progress-bar-container">
                            <div class="fill" style="width:${studentPercent}%;background:${studentColor};"></div>
                        </div>
                    </td>
                    <td class="performance-cell">
                        <span class="status-badge ${statusClass}">
                            <i class="fas ${statusIcon}"></i>
                            ${statusText}
                        </span>
                    </td>
                </tr>
            `;
        });

        html += `
            </tbody>
        </table>`;

        container.innerHTML = html;
    }

    // =============================================
    // STUDENT MODAL FUNCTIONS
    // =============================================
    function openStudentDetail(studentId, departmentId, courseId) {
        const employeeQuery = @json(request('employee_id'));
        const employeeParam = employeeQuery ? `&employee_id=${encodeURIComponent(employeeQuery)}` : '';
        const detailUrl = `{{ route('lesson-planner.studentDetail') }}?student_id=${encodeURIComponent(studentId)}&department=${encodeURIComponent(departmentId)}&course=${encodeURIComponent(courseId)}${employeeParam}`;
        window.location.href = detailUrl;
    }

    function openStudentModal(studentId, departmentId, courseId) {
        const key = `${departmentId}-${courseId}`;
        const data = mockData[key];
        if (!data) return;

        const student = data.students.find(s => s.id == studentId);
        if (!student) return;

        currentStudentData = {
            student: student,
            data: data,
            departmentId: departmentId,
            courseId: courseId
        };

        const initials = student.name.split(' ').map(n => n[0]).join('');
        document.getElementById('modal-avatar').textContent = initials;
        document.getElementById('modal-name').textContent = student.name;
        document.getElementById('modal-reg-no').textContent = student.regNo;
        document.getElementById('modal-email').textContent = student.email;
        const employeeQuery = @json(request('employee_id'));
        const employeeParam = employeeQuery ? `&employee_id=${encodeURIComponent(employeeQuery)}` : '';
        document.getElementById('student-detail-link').href = `{{ route('lesson-planner.studentDetail') }}?student_id=${encodeURIComponent(student.id)}&department=${encodeURIComponent(departmentId)}&course=${encodeURIComponent(courseId)}${employeeParam}`;

        document.getElementById('modal-month-filter').value = '1';

        document.getElementById('student-modal').classList.add('active');
        document.body.style.overflow = 'hidden';

        renderStudentDetail();
    }

    function closeStudentModal() {
        document.getElementById('student-modal').classList.remove('active');
        document.body.style.overflow = 'auto';
        currentStudentData = null;
    }

    document.getElementById('student-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeStudentModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeStudentModal();
        }
    });

    function renderStudentDetail() {
        if (!currentStudentData) return;

        const { student, data } = currentStudentData;
        const monthVal = parseInt(document.getElementById('modal-month-filter').value);
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const days = data.months[monthVal];

        if (!days) {
            document.getElementById('modal-body').innerHTML = `
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-calendar-times"></i></div>
                    <div>No data available for ${monthNames[monthVal - 1]} ${reviewYear}</div>
                </div>
            `;
            return;
        }

        // Calculate stats
        let presentCount = 0;
        let absentCount = 0;
        let totalTeachingDays = 0;

        const teachingDays = days.filter(d => !d.isWeekend && d.materials.length > 0);

        teachingDays.forEach(day => {
            totalTeachingDays++;
            const dayData = student[`day_${monthVal}_${day.day}`];
            if (dayData) {
                if (dayData.status === 'present') presentCount++;
                else absentCount++;
            }
        });

        const percent = totalTeachingDays > 0 ? Math.round((presentCount / totalTeachingDays) * 100) : 0;

        document.getElementById('modal-present-count').textContent = presentCount;
        document.getElementById('modal-absent-count').textContent = absentCount;
        document.getElementById('modal-percent').textContent = `${percent}%`;

        // Build table
        let html = `
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="min-width:70px;"><i class="fas fa-calendar-day"></i> Date</th>
                            <th style="min-width:60px;"><i class="fas fa-clock"></i> Day</th>
                            <th style="min-width:90px;"><i class="fas fa-user-check"></i> Presence</th>
                            <th style="min-width:350px;"><i class="fas fa-file-alt"></i> Materials & Clicks</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        if (teachingDays.length === 0) {
            html += `
                <tr>
                    <td colspan="4" style="text-align:center;padding:30px;color:#8a9bb5;">
                        <i class="fas fa-inbox" style="font-size:24px;display:block;margin-bottom:8px;"></i>
                        No classes for ${monthNames[monthVal - 1]} ${reviewYear}
                    </td>
                </tr>
            `;
        } else {
            teachingDays.forEach(day => {
                const dayData = student[`day_${monthVal}_${day.day}`];
                const dayName = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][day.dayOfWeek];
                
                // Presence status - only Present or Absent
                let presenceClass = 'no-class';
                let presenceText = '—';
                let presenceIcon = 'fa-minus';
                
                if (dayData) {
                    if (dayData.status === 'present') {
                        presenceClass = 'present';
                        presenceText = 'Present';
                        presenceIcon = 'fa-check-circle';
                    } else if (dayData.status === 'absent') {
                        presenceClass = 'absent';
                        presenceText = 'Absent';
                        presenceIcon = 'fa-times-circle';
                    }
                }

                html += `
                    <tr>
                        <td class="date-cell">
                            ${day.day}
                            <span class="day-name">${dayName}</span>
                        </td>
                        <td style="font-weight:500;color:#4b6a8b;">${day.shortDay}</td>
                        <td>
                            <span class="presence-badge ${presenceClass}">
                                <i class="fas ${presenceIcon}"></i> ${presenceText}
                            </span>
                        </td>
                        <td>
                            <div class="material-items-container">
                `;

                // Show all materials with click counts
                if (dayData && dayData.materials && dayData.materials.length > 0) {
                    dayData.materials.forEach(material => {
                        const isViewed = material.viewed;
                        const clicks = material.clicks || 0;
                        const iconClass = material.type === 'video' ? 'video' : 'link';
                        const icon = material.type === 'video' ? 'fa-video' : 'fa-link';
                        const statusClass = isViewed ? 'viewed' : 'not-viewed';
                        const statusText = isViewed ? 'Viewed' : 'Not Viewed';
                        
                        // Click count styling
                        let clickClass = '';
                        if (clicks >= 4) clickClass = 'high';
                        else if (clicks === 0) clickClass = 'low';

                        html += `
                            <div class="material-item-row ${iconClass}">
                                <span class="mat-icon ${iconClass}">
                                    <i class="fas ${icon}"></i>
                                </span>
                                <a class="mat-name" href="${material.url}" target="_blank" rel="noopener">${material.title}</a>
                                <span class="mat-click-count ${clickClass}">
                                    <i class="fas fa-mouse-pointer"></i> ${clicks} clicks
                                </span>
                                <span class="mat-status ${statusClass}">
                                    ${statusText}
                                </span>
                            </div>
                        `;
                    });
                } else {
                    html += `
                        <span style="font-size:12px;color:#b0c0d0;">No materials</span>
                    `;
                }

                html += `
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        html += `
                    </tbody>
                </table>
            </div>
        `;

        document.getElementById('modal-body').innerHTML = html;
    }

    function populateMonthFilter() {
        const monthFilter = document.getElementById('modal-month-filter');
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        monthFilter.innerHTML = monthNames.map((month, index) => `<option value="${index + 1}">${month} ${reviewYear}</option>`).join('');
    }

    $(document).ready(function() {
        populateDepartments();
        populateMonthFilter();
        resetFilters();
    });

    window.refreshLessonPlannerViews = function() {
        loadStudentData();
    };
</script>
@endsection