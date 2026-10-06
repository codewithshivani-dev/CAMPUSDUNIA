{{-- resources/views/instituteAdmin/EmployeeExit/view-policy.blade.php --}}

@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'View Policy - ' . $policy->policy_name)

@section('content')
<style>
:root {
    --ep-primary: #2A5C8A;
    --ep-primary-dark: #1D4266;
    --ep-primary-soft: #EAF2F9;
    --ep-success: #0E8074;
    --ep-success-soft: #E4F5F2;
    --ep-danger: #B42318;
    --ep-danger-soft: #FDEDEC;
    --ep-warning: #B45309;
    --ep-warning-soft: #FEF3E2;
    --ep-info: #4338CA;
    --ep-info-soft: #EEEDFC;
    --ep-border: #E4E7EC;
    --ep-bg: #F7F9FC;
    --ep-surface: #FFFFFF;
    --ep-slate: #475467;
    --ep-muted: #98A2B3;
    --ep-ink: #101828;
    --ep-radius: 12px;
}

.view-policy-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--ep-ink);
}

.view-policy-page .page-shell {
    background: var(--ep-bg);
    margin: -1.5rem -1rem;
    padding: 2rem 1.25rem 3rem;
}

/* Back Button */
.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--ep-slate);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    transition: color 0.15s;
}
.back-link:hover { color: var(--ep-primary); text-decoration: none; }

/* Policy Header */
.policy-header {
    background: var(--ep-surface);
    border: 1px solid var(--ep-border);
    border-radius: var(--ep-radius);
    padding: 1.5rem 2rem;
    margin-bottom: 1.5rem;
}
.policy-header .policy-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--ep-ink);
    margin-bottom: 0.25rem;
}
.policy-header .policy-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: center;
}
.policy-header .policy-meta .meta-item {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.85rem;
    color: var(--ep-slate);
}
.policy-header .policy-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

/* Info Cards */
.info-card {
    background: var(--ep-surface);
    border: 1px solid var(--ep-border);
    border-radius: var(--ep-radius);
    padding: 1.25rem;
    margin-bottom: 1.5rem;
}
.info-card .card-title {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--ep-slate);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--ep-border);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 0.75rem 1.5rem;
}
.info-grid .info-item {
    display: flex;
    justify-content: space-between;
    padding: 0.4rem 0;
    border-bottom: 1px solid #f1f4f9;
}
.info-grid .info-item .label { color: var(--ep-slate); font-size: 0.85rem; }
.info-grid .info-item .value { font-weight: 500; font-size: 0.85rem; text-align: right; }

/* Exit Type Badge */
.exit-type-badge {
    display: inline-block;
    padding: 0.25rem 0.9rem;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 600;
}
.exit-type-Resignation { background: #FEE2E2; color: #991B1B; }
.exit-type-Termination { background: #FEF3C7; color: #92400E; }
.exit-type-End-of-Contract { background: #D1FAE5; color: #065F46; }
.exit-type-Mutual-Agreement { background: #DBEAFE; color: #1E40AF; }
.exit-type-Retirement { background: #F3E8FF; color: #6B21A8; }
.exit-type-Other { background: #F1F5F9; color: #475569; }

/* Status Badge */
.status-badge-large {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.3rem 1rem;
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 600;
}
.status-badge-large.active { background: #DCFCE7; color: #166534; }
.status-badge-large.inactive { background: #FEE2E2; color: #991B1B; }

/* Policy Code Badge */
.policy-code-badge {
    font-family: 'Courier New', monospace;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--ep-slate);
    background: #F1F4F9;
    padding: 0.15rem 0.8rem;
    border-radius: 4px;
    display: inline-block;
}

/* Requirement Chips */
.req-chips-large { display: flex; flex-wrap: wrap; gap: 0.4rem; }
.req-chip-large {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.2rem 0.7rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}
.req-chip-large.interview { background: var(--ep-info-soft); color: var(--ep-info); }
.req-chip-large.fnf { background: var(--ep-warning-soft); color: var(--ep-warning); }
.req-chip-large.kt { background: var(--ep-success-soft); color: var(--ep-success); }
.req-chip-large.additional { background: #F1F5F9; color: #475569; }

/* Employees Table */
.employees-table-wrapper {
    background: var(--ep-surface);
    border: 1px solid var(--ep-border);
    border-radius: var(--ep-radius);
    overflow: hidden;
}
.employees-table-wrapper .table-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--ep-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.employees-table-wrapper .table-header .title {
    font-weight: 600;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.employees-table-wrapper .table-header .title .badge-count {
    background: var(--ep-primary-soft);
    color: var(--ep-primary);
    padding: 0.1rem 0.6rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.employee-row td {
    padding: 0.6rem 1rem !important;
    border-bottom: 1px solid var(--ep-border) !important;
    vertical-align: middle;
}
.employee-row:hover { background: #FAFBFC; }

.employee-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--ep-primary-soft);
    color: var(--ep-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.75rem;
    flex-shrink: 0;
}

/* Notice Period Display */
.notice-display-large {
    text-align: center;
    padding: 0.25rem 0.75rem;
    background: var(--ep-primary-soft);
    border-radius: 8px;
    display: inline-block;
}
.notice-display-large .days {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--ep-primary);
    display: block;
}
.notice-display-large .label {
    font-size: 0.6rem;
    color: var(--ep-slate);
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

/* Employment Type Overrides */
.override-item {
    display: inline-block;
    padding: 0.15rem 0.6rem;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 500;
    background: #F1F5F9;
    color: #475569;
    border: 1px solid #E2E8F0;
}

/* Table Footer with Pagination */
.table-footer {
    padding: 0.75rem 1.25rem;
    border-top: 1px solid var(--ep-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: var(--ep-slate);
    background: var(--ep-bg);
}
.table-footer .pagination { margin: 0; }
.table-footer .pagination .page-link {
    padding: 0.3rem 0.7rem;
    font-size: 0.8rem;
    border-radius: 4px;
    color: var(--ep-primary);
    border-color: var(--ep-border);
}
.table-footer .pagination .page-link:hover {
    background-color: var(--ep-primary-soft);
    color: var(--ep-primary-dark);
}
.table-footer .pagination .page-item.active .page-link {
    background-color: var(--ep-primary);
    border-color: var(--ep-primary);
    color: #fff;
}
.table-footer .pagination .page-item.disabled .page-link { color: var(--ep-muted); }

/* ================================
   Department Tabs
   ================================ */
.dept-tabs-container {
    border-bottom: 1px solid var(--ep-border);
    background: #FAFBFC;
    padding: 0 1rem;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.dept-tabs {
    border-bottom: none;
    flex-wrap: nowrap;
    gap: 0.25rem;
    padding: 0.5rem 0 0;
}
.dept-tabs .nav-item { flex-shrink: 0; }
.dept-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: var(--ep-slate);
    font-weight: 500;
    font-size: 0.85rem;
    padding: 0.6rem 1rem;
    border-radius: 0;
    white-space: nowrap;
    transition: all 0.2s;
    background: transparent;
}
.dept-tabs .nav-link:hover {
    color: var(--ep-primary);
    border-bottom-color: var(--ep-primary-soft);
    background: var(--ep-primary-soft);
}
.dept-tabs .nav-link.active {
    color: var(--ep-primary);
    border-bottom-color: var(--ep-primary);
    background: var(--ep-primary-soft);
    font-weight: 600;
    box-shadow: inset 0 -3px 0 var(--ep-primary), 0 1px 3px rgba(42, 92, 138, .12);
    position: relative;
}
.dept-tabs .nav-link.active::after {
    content: 'Selected';
    display: block;
    margin-top: 0.15rem;
    color: var(--ep-primary-dark);
    font-size: 0.62rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.dept-tabs .nav-link.active .badge {
    background-color: var(--ep-primary-dark) !important;
    color: #fff;
}
.dept-tabs .nav-link .badge {
    font-size: 0.65rem;
    padding: 0.2rem 0.5rem;
}

.dept-tab-content { padding: 0; }
.dept-tab-content .tab-pane { animation: fadeIn 0.2s ease-in; }
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Department Info Bar */
.dept-info-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: #F8FAFC;
    border-bottom: 1px solid var(--ep-border);
}
.dept-info-left h6 {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--ep-ink);
}
.dept-info-left small { font-size: 0.75rem; }
.dept-info-right .badge {
    font-size: 0.7rem;
    padding: 0.35rem 0.75rem;
}

/* ================================
   Department Panel (inside each tab)
   ================================ */
.dept-panel {
    padding: 1.25rem;
    background: #FBFCFD;
}
.dept-panel .panel-section {
    background: #FFFFFF;
    border: 1px solid var(--ep-border);
    border-radius: var(--ep-radius);
    padding: 1.25rem;
    margin-bottom: 1rem;
}
.dept-panel .panel-section:last-child { margin-bottom: 0; }
.dept-panel .panel-section .section-title {
    font-weight: 600;
    font-size: 0.8rem;
    color: var(--ep-slate);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.85rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--ep-border);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Dept stats strip */
.dept-stats-strip {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1rem;
}
.dept-stat {
    background: #FFFFFF;
    border: 1px solid var(--ep-border);
    border-radius: 10px;
    padding: 0.85rem 1rem;
    text-align: center;
}
.dept-stat .value {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--ep-primary);
    display: block;
    line-height: 1.2;
}
.dept-stat .label {
    font-size: 0.7rem;
    color: var(--ep-slate);
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-top: 0.2rem;
    display: block;
}
.dept-stat.success .value { color: var(--ep-success); }
.dept-stat.warning .value { color: var(--ep-warning); }
.dept-stat.info    .value { color: var(--ep-info); }

/* Two column layout for policy info inside tab */
.dept-info-columns {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .policy-header .policy-meta { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
    .policy-header .policy-actions { width: 100%; justify-content: flex-start; margin-top: 0.5rem; }
    .info-grid { grid-template-columns: 1fr; }
    .info-grid .info-item { flex-direction: column; align-items: flex-start; gap: 0.1rem; }
    .info-grid .info-item .value { text-align: left; }
    .table-footer { flex-direction: column; align-items: center; text-align: center; gap: 0.75rem; }

    .dept-tabs-container { padding: 0 0.5rem; }
    .dept-tabs .nav-link { font-size: 0.78rem; padding: 0.5rem 0.75rem; }
    .dept-tabs .nav-link .badge { font-size: 0.6rem; padding: 0.15rem 0.4rem; }
    .dept-info-bar { flex-direction: column; align-items: flex-start; padding: 0.75rem 1rem; }
    .dept-panel { padding: 0.75rem; }
    .dept-panel .panel-section { padding: 1rem; }
    .dept-info-columns { grid-template-columns: 1fr; }
}

@media (max-width: 576px) {
    .dept-tabs .nav-link { font-size: 0.72rem; padding: 0.4rem 0.6rem; }
    .dept-tabs .nav-link i.fa-building { display: none; }
}

.table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
</style>

<div class="view-policy-page">
    <div class="page-shell">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <!-- Back Button -->
                    <div class="mb-3">
                        <a href="{{ route('exit-policies.index') }}" class="back-link">
                            <i class="fas fa-arrow-left"></i> Back to Policies
                        </a>
                    </div>

                    <!-- Policy Header -->
                    <div class="policy-header">
                        <div class="row align-items-start">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <h1 class="policy-title">{{ $policy->policy_name }}</h1>
                                    <span class="policy-code-badge">{{ $policy->policy_code }}</span>
                                </div>

                                <div class="policy-meta mt-2">
                                    <span class="meta-item">
                                        <i class="fas fa-tag text-muted"></i>
                                        <span class="exit-type-badge {{ 'exit-type-' . str_replace(' ', '-', $policy->exit_type ?? 'Other') }}">
                                            {{ $policy->exit_type ?? 'N/A' }}
                                        </span>
                                    </span>

                                    <span class="meta-item">
                                        <span class="status-badge-large {{ $policy->is_active ? 'active' : 'inactive' }}">
                                            <i class="fas fa-circle" style="font-size:0.4rem;"></i>
                                            {{ $policy->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </span>

                                    <span class="meta-item d-none d-md-inline-flex">
                                        <i class="fas fa-users text-muted"></i>
                                        <span>{{ $assignedCount }} assignment(s)</span>
                                    </span>

                                    <span class="meta-item">
                                        <i class="fas fa-calendar-alt text-muted"></i>
                                        <span>Created: {{ $policy->created_at->format('d M Y') }}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <div class="policy-actions">
                                    <a href="{{ route('exit-policies.edit', $policy->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit Policy
                                    </a>
                                    <a href="{{ route('exit-policies.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-list"></i> All Policies
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- =====================================================
                         DEPARTMENT TABS (each tab shows full policy info)
                         ===================================================== -->
                    @if($departmentEmployeeGroups->isNotEmpty())

                        <div class="employees-table-wrapper">
                            <div class="table-header">
                                <div class="title">
                                    <i class="fas fa-building text-primary"></i> Policy Coverage by Department
                                    <span class="badge-count">{{ $departmentEmployeeGroups->count() }} dept(s)</span>
                                </div>
                                <div>
                                    <span class="text-muted small">
                                        <i class="fas fa-info-circle"></i>
                                        Select a department tab to view its full policy details &amp; employees
                                    </span>
                                </div>
                            </div>

                            {{-- Tabs --}}
                            <div class="dept-tabs-container">
                                <ul class="nav nav-tabs dept-tabs" id="departmentTabs" role="tablist">
                                    @foreach($departmentEmployeeGroups as $index => $departmentGroup)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                                                    id="dept-tab-{{ $departmentGroup['department']->department_id }}"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#dept-pane-{{ $departmentGroup['department']->department_id }}"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="dept-pane-{{ $departmentGroup['department']->department_id }}"
                                                    aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                                <i class="fas fa-building me-1"></i>
                                                {{ $departmentGroup['department']->department }}
                                                <span class="badge rounded-pill bg-primary ms-1">{{ $departmentGroup['employee_count'] }}</span>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Tab Content --}}
                            <div class="tab-content dept-tab-content" id="departmentTabsContent">
                                @foreach($departmentEmployeeGroups as $index => $departmentGroup)
                                    @php
                                        $dept = $departmentGroup['department'];
                                        $deptEmployees = $departmentGroup['employees'];
                                    @endphp
                                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                         id="dept-pane-{{ $dept->department_id }}"
                                         role="tabpanel"
                                         aria-labelledby="dept-tab-{{ $dept->department_id }}">

                                        {{-- Department Info Bar --}}
                                        <div class="dept-info-bar">
                                            <div class="dept-info-left">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-building text-warning me-1"></i>
                                                    {{ $dept->department }}
                                                </h6>
                                                <small class="text-muted">
                                                    {{ $departmentGroup['employee_count'] }} employee(s) covered under this policy
                                                </small>
                                            </div>
                                            <div class="dept-info-right">
                                                <span class="badge bg-light text-dark border">
                                                    <i class="fas fa-id-card me-1"></i>
                                                    Dept Code: {{ $dept->department_id ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Full policy panel for this department --}}
                                        <div class="dept-panel">

                                            {{-- Quick stats strip --}}
                                            <div class="dept-stats-strip">
                                                <div class="dept-stat">
                                                    <span class="value">{{ $policy->default_notice_period ?? 'N/A' }}</span>
                                                    <span class="label">Notice Days</span>
                                                </div>
                                                <div class="dept-stat success">
                                                    <span class="value">{{ $departmentGroup['employee_count'] }}</span>
                                                    <span class="label">Employees</span>
                                                </div>
                                                <div class="dept-stat warning">
                                                    <span class="value">{{ $policy->fnf_required ? ($policy->fnf_processing_days ?? '—') : '—' }}</span>
                                                    <span class="label">FNF Days</span>
                                                </div>
                                                <div class="dept-stat info">
                                                    <span class="value">{{ $policy->kt_required ? ($policy->kt_days ?? '—') : '—' }}</span>
                                                    <span class="label">KT Days</span>
                                                </div>
                                                <div class="dept-stat">
                                                    <span class="value">{{ $policy->clearance_days ?? '—' }}</span>
                                                    <span class="label">Clearance Days</span>
                                                </div>
                                            </div>

                                            {{-- Two column layout --}}
                                            <div class="dept-info-columns">

                                                {{-- LEFT: Policy Details --}}
                                                <div>
                                                    {{-- Requirements --}}
                                                    <div class="panel-section">
                                                        <div class="section-title">
                                                            <i class="fas fa-clipboard-list text-primary"></i>
                                                            Requirements — {{ $dept->department }}
                                                        </div>
                                                        <div class="info-grid">
                                                            <div class="info-item">
                                                                <span class="label">Exit Interview</span>
                                                                <span class="value">
                                                                    <span class="badge {{ $policy->exit_interview_required ? 'bg-success' : 'bg-secondary' }}">
                                                                        {{ $policy->exit_interview_required ? 'Required' : 'Not Required' }}
                                                                    </span>
                                                                    @if($policy->exit_interview_required && $policy->interview_days)
                                                                        <small class="text-muted d-block">({{ $policy->interview_days }} days)</small>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            <div class="info-item">
                                                                <span class="label">FNF (Full &amp; Final)</span>
                                                                <span class="value">
                                                                    <span class="badge {{ $policy->fnf_required ? 'bg-success' : 'bg-secondary' }}">
                                                                        {{ $policy->fnf_required ? 'Required' : 'Not Required' }}
                                                                    </span>
                                                                    @if($policy->fnf_required && $policy->fnf_processing_days)
                                                                        <small class="text-muted d-block">({{ $policy->fnf_processing_days }} days)</small>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            <div class="info-item">
                                                                <span class="label">Knowledge Transfer</span>
                                                                <span class="value">
                                                                    <span class="badge {{ $policy->kt_required ? 'bg-success' : 'bg-secondary' }}">
                                                                        {{ $policy->kt_required ? 'Required' : 'Not Required' }}
                                                                    </span>
                                                                    @if($policy->kt_required && $policy->kt_days)
                                                                        <small class="text-muted d-block">({{ $policy->kt_days }} days)</small>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            <div class="info-item">
                                                                <span class="label">Clearance Workflow</span>
                                                                <span class="value">
                                                                    <span class="badge bg-info">{{ ucfirst($policy->clearance_workflow ?? 'N/A') }}</span>
                                                                    <small class="text-muted d-block">({{ $policy->clearance_days ?? 'N/A' }} days)</small>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Notice Period Overrides --}}
                                                    @if(!empty($employmentNoticePeriods))
                                                    <div class="panel-section">
                                                        <div class="section-title">
                                                            <i class="fas fa-clock text-primary"></i>
                                                            Notice Period by Employment Type
                                                        </div>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @foreach($employmentNoticePeriods as $type => $period)
                                                                @php
                                                                    $customDays = $employmentCustomDays[$type] ?? null;
                                                                    $displayDays = ($period === 'custom' && $customDays)
                                                                        ? $customDays
                                                                        : (($period === null || $period === '') ? $policy->default_notice_period : $period);
                                                                @endphp
                                                                <span class="override-item">
                                                                    <strong>{{ $type }}</strong>: {{ $displayDays }} days
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @endif

                                                    {{-- FNF Items --}}
                                                    @if($policy->fnf_required && !empty($fnfItems))
                                                    <div class="panel-section">
                                                        <div class="section-title">
                                                            <i class="fas fa-file-invoice-dollar text-warning"></i>
                                                            FNF Items
                                                        </div>
                                                        <div class="req-chips-large">
                                                            @php
                                                            $fnfLabels = [
                                                                'salary_settlement' => 'Salary Settlement',
                                                                'leave_encashment'  => 'Leave Encashment',
                                                                'bonus_settlement'  => 'Bonus/Incentive',
                                                                'reimbursement'     => 'Reimbursement',
                                                                'pf_settlement'     => 'PF Settlement',
                                                                'esi_settlement'    => 'ESI Settlement',
                                                                'gratuity'          => 'Gratuity',
                                                                'other_dues'        => 'Other Dues',
                                                            ];
                                                            @endphp
                                                            @foreach($fnfItems as $item)
                                                                <span class="req-chip-large fnf">
                                                                    <i class="fas fa-check-circle"></i>
                                                                    {{ $fnfLabels[$item] ?? ucfirst(str_replace('_', ' ', $item)) }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>

                                                {{-- RIGHT: KT + Additional + Description --}}
                                                <div>
                                                    {{-- KT Requirements --}}
                                                    @if($policy->kt_required && !empty($ktRequirements))
                                                    <div class="panel-section">
                                                        <div class="section-title">
                                                            <i class="fas fa-chalkboard-teacher text-success"></i>
                                                            KT Requirements
                                                        </div>
                                                        <div class="req-chips-large">
                                                            @php
                                                            $ktLabels = [
                                                                'documentation'    => 'Documentation',
                                                                'project_handover' => 'Project Handover',
                                                                'code_handover'    => 'Code Handover',
                                                                'client_handover'  => 'Client Handover',
                                                                'process_handover' => 'Process Handover',
                                                                'training'         => 'Training',
                                                                'knowledge_docs'   => 'Knowledge Docs',
                                                            ];
                                                            @endphp
                                                            @foreach($ktRequirements as $item)
                                                                <span class="req-chip-large kt">
                                                                    <i class="fas fa-check-circle"></i>
                                                                    {{ $ktLabels[$item] ?? ucfirst(str_replace('_', ' ', $item)) }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @endif

                                                    {{-- Additional Requirements --}}
                                                    @if(!empty($additionalRequirements))
                                                    <div class="panel-section">
                                                        <div class="section-title">
                                                            <i class="fas fa-plus-circle text-secondary"></i>
                                                            Additional Requirements
                                                        </div>
                                                        <div class="req-chips-large">
                                                            @php
                                                            $additionalLabels = [
                                                                'asset_return'       => 'Asset Return',
                                                                'access_revocation'  => 'Access Revocation',
                                                                'id_card_return'     => 'ID Card Return',
                                                                'visa_cancellation'  => 'Visa Cancellation',
                                                                'exit_reentry_visa'  => 'Exit Re-entry Visa',
                                                                'medical_certificate'=> 'Medical Certificate',
                                                                'police_clearance'   => 'Police Clearance',
                                                                'housing_handover'   => 'Housing Handover',
                                                            ];
                                                            @endphp
                                                            @foreach($additionalRequirements as $item)
                                                                <span class="req-chip-large additional">
                                                                    <i class="fas fa-check-circle"></i>
                                                                    {{ $additionalLabels[$item] ?? ucfirst(str_replace('_', ' ', $item)) }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @endif

                                                    {{-- Description --}}
                                                    @if($policy->description)
                                                    <div class="panel-section">
                                                        <div class="section-title">
                                                            <i class="fas fa-align-left text-primary"></i>
                                                            Description
                                                        </div>
                                                        <p class="mb-0" style="white-space: pre-wrap; line-height: 1.6; font-size: 0.85rem;">
                                                            {{ $policy->description }}
                                                        </p>
                                                    </div>
                                                    @endif

                                                    {{-- Terms --}}
                                                    @if($policy->terms_conditions)
                                                    <div class="panel-section">
                                                        <div class="section-title">
                                                            <i class="fas fa-file-contract text-primary"></i>
                                                            Terms &amp; Conditions
                                                        </div>
                                                        <p class="mb-0" style="white-space: pre-wrap; line-height: 1.6; font-size: 0.85rem;">
                                                            {{ $policy->terms_conditions }}
                                                        </p>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Employees Table for this department --}}
                                            <div class="panel-section">
                                                <div class="section-title">
                                                    <i class="fas fa-users text-primary"></i>
                                                    Employees in {{ $dept->department }}
                                                    <span class="badge bg-primary ms-1" style="font-size:0.65rem;">
                                                        {{ $departmentGroup['employee_count'] }}
                                                    </span>
                                                </div>
                                                @if($deptEmployees->isNotEmpty())
                                                    <div class="table-scroll">
                                                        <table class="table table-striped mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 50px;">#</th>
                                                                    <th style="min-width: 180px;">Employee</th>
                                                                    <th style="min-width: 130px;">Employee Code</th>
                                                                    <th style="min-width: 150px;">Designation</th>
                                                                    <th style="min-width: 100px;">Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($deptEmployees as $empIndex => $employee)
                                                                    <tr class="employee-row">
                                                                        <td>{{ $empIndex + 1 }}</td>
                                                                        <td>
                                                                            <div class="d-flex align-items-center gap-2">
                                                                                <div class="employee-avatar">
                                                                                    {{ strtoupper(substr($employee->name ?? 'U', 0, 1)) }}
                                                                                </div>
                                                                                <span>{{ $employee->name ?? 'Unknown' }}</span>
                                                                            </div>
                                                                        </td>
                                                                        <td><code>{{ $employee->employee_code ?? 'N/A' }}</code></td>
                                                                        <td>{{ $employee->designation ?? 'N/A' }}</td>
                                                                        <td>
                                                                            <span class="badge bg-success" style="font-size:0.65rem;">Active</span>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="text-center py-4">
                                                        <i class="fas fa-users text-muted" style="font-size: 2rem;"></i>
                                                        <p class="text-muted mt-2 mb-0">No active employees in this department.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    @elseif($assignedEmployees->count() > 0)

                        {{-- Individual Employees — flat layout (no tabs) --}}
                        <div class="info-card">
                            <div class="card-title">
                                <i class="fas fa-user-check text-info"></i> Individual Assignment — Policy Details
                            </div>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="label">Exit Interview</span>
                                    <span class="value">
                                        <span class="badge {{ $policy->exit_interview_required ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $policy->exit_interview_required ? 'Required' : 'Not Required' }}
                                        </span>
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="label">FNF</span>
                                    <span class="value">
                                        <span class="badge {{ $policy->fnf_required ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $policy->fnf_required ? 'Required' : 'Not Required' }}
                                        </span>
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Knowledge Transfer</span>
                                    <span class="value">
                                        <span class="badge {{ $policy->kt_required ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $policy->kt_required ? 'Required' : 'Not Required' }}
                                        </span>
                                    </span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Notice Period</span>
                                    <span class="value"><strong>{{ $policy->default_notice_period ?? 'N/A' }}</strong> days</span>
                                </div>
                            </div>
                            @if(!empty($employmentNoticePeriods))
                            <div class="panel-section mt-3 mb-0">
                                <div class="section-title">
                                    <i class="fas fa-clock text-primary"></i>
                                    Notice Period by Employment Type
                                </div>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($employmentNoticePeriods as $type => $period)
                                        @php
                                            $customDays = $employmentCustomDays[$type] ?? null;
                                            $displayDays = ($period === 'custom' && $customDays)
                                                ? $customDays
                                                : (($period === null || $period === '') ? $policy->default_notice_period : $period);
                                        @endphp
                                        <span class="override-item">
                                            <strong>{{ $type }}</strong>: {{ $displayDays }} days
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="employees-table-wrapper">
                            <div class="table-header">
                                <div class="title">
                                    <i class="fas fa-users text-primary"></i> Assigned Employees
                                    <span class="badge-count">{{ $assignedEmployeeTotal }}</span>
                                </div>
                            </div>
                            <div class="table-scroll">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th style="min-width: 180px;">Employee</th>
                                            <th style="min-width: 130px;">Employee Code</th>
                                            <th style="min-width: 150px;">Department</th>
                                            <th style="min-width: 120px;">Designation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assignedEmployees as $index => $employee)
                                        <tr class="employee-row">
                                            <td>{{ $assignedEmployees->firstItem() + $index }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="employee-avatar">
                                                        {{ strtoupper(substr($employee->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                    <span>{{ $employee->name ?? 'Unknown' }}</span>
                                                </div>
                                            </td>
                                            <td><code>{{ $employee->employee_code ?? 'N/A' }}</code></td>
                                            <td>
                                                @if($employee->department_id)
                                                    @php
                                                        $deptName = \App\Models\Departments::where('department_id', $employee->department_id)->value('department');
                                                    @endphp
                                                    <span class="badge bg-light text-dark border">{{ $deptName ?? 'N/A' }}</span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>{{ $employee->designation ?? 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($assignedEmployees->hasPages())
                            <div class="table-footer">
                                <div>
                                    <small class="text-muted">
                                        Showing {{ $assignedEmployees->firstItem() ?? 0 }} to
                                        {{ $assignedEmployees->lastItem() ?? 0 }} of {{ $assignedEmployees->total() }} employees
                                    </small>
                                </div>
                                <div>
                                    {{ $assignedEmployees->appends(request()->query())->links('pagination::default') }}
                                </div>
                            </div>
                            @endif
                        </div>

                    @else
                        <div class="info-card text-center py-5">
                            <i class="fas fa-users text-muted" style="font-size: 2.5rem;"></i>
                            <p class="text-muted mt-3 mb-0">No employees are currently assigned to this policy.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Persist active tab
    const tabButtons = document.querySelectorAll('#departmentTabs .nav-link');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function(e) {
            sessionStorage.setItem('activeDeptTab', e.target.getAttribute('data-bs-target'));
        });
    });

    const savedTab = sessionStorage.getItem('activeDeptTab');
    if (savedTab) {
        const tabButton = document.querySelector(`#departmentTabs .nav-link[data-bs-target="${savedTab}"]`);
        if (tabButton) new bootstrap.Tab(tabButton).show();
    }
});
</script>
@endsection
