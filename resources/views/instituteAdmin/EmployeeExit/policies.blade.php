{{-- resources/views/instituteAdmin/EmployeeExit/policies.blade.php --}}

@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'Exit Policies')

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

.exit-policy-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--ep-ink);
}

.exit-policy-page .page-shell {
    background: var(--ep-bg);
    margin: -1.5rem -1rem;
    padding: 2rem 1.25rem 3rem;
}

/* Statistics Cards */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-card {
    background: var(--ep-surface);
    border: 1px solid var(--ep-border);
    border-radius: var(--ep-radius);
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.2s, box-shadow 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.stat-card .stat-icon {
    width: 48px;
    height: 48px;
    border-radius: var(--ep-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.stat-card .stat-icon.primary {
    background: var(--ep-primary-soft);
    color: var(--ep-primary);
}

.stat-card .stat-icon.success {
    background: var(--ep-success-soft);
    color: var(--ep-success);
}

.stat-card .stat-icon.warning {
    background: var(--ep-warning-soft);
    color: var(--ep-warning);
}

.stat-card .stat-icon.info {
    background: var(--ep-info-soft);
    color: var(--ep-info);
}

.stat-card .stat-icon.danger {
    background: var(--ep-danger-soft);
    color: var(--ep-danger);
}

.stat-card .stat-content {
    flex: 1;
    min-width: 0;
}

.stat-card .stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1.2;
    color: var(--ep-ink);
}

.stat-card .stat-label {
    font-size: 0.78rem;
    color: var(--ep-slate);
    font-weight: 500;
}

/* Filter Section */
.filter-section {
    background: var(--ep-surface);
    border: 1px solid var(--ep-border);
    border-radius: var(--ep-radius);
    padding: 1.25rem;
    margin-bottom: 1.5rem;
}

.filter-section .filter-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
    gap: 1rem;
    align-items: end;
}

@media (max-width: 992px) {
    .filter-section .filter-row {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 576px) {
    .filter-section .filter-row {
        grid-template-columns: 1fr;
    }
}

.filter-section .filter-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

/* Policy Table */
.policy-table-wrapper {
    background: var(--ep-surface);
    border: 1px solid var(--ep-border);
    border-radius: var(--ep-radius);
    overflow: hidden;
}

.policy-table-wrapper .table-header {
    padding: 1.25rem;
    border-bottom: 1px solid var(--ep-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.policy-table-wrapper .table-header .title {
    font-weight: 600;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.policy-table-wrapper .table-header .title .badge-count {
    background: var(--ep-primary-soft);
    color: var(--ep-primary);
    padding: 0.1rem 0.6rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
}

/* Policy Row */
.policy-row {
    background: #FFFFFF;
    transition: background 0.15s;
}

.policy-row:hover {
    background: #FAFBFC;
}

.policy-row td {
    padding: 0.75rem 1rem !important;
    border-bottom: 1px solid var(--ep-border) !important;
    vertical-align: middle;
}

/* Exit Type Chip */
.exit-type-single {
    display: inline-block;
    padding: 0.2rem 0.8rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 600;
    background: var(--ep-primary-soft);
    color: var(--ep-primary-dark);
    border: 1px solid var(--ep-primary);
}

.exit-type-Resignation {
    background: #FEE2E2;
    color: #991B1B;
    border-color: #991B1B;
}

.exit-type-Termination {
    background: #FEF3C7;
    color: #92400E;
    border-color: #92400E;
}

.exit-type-End-of-Contract {
    background: #D1FAE5;
    color: #065F46;
    border-color: #065F46;
}

.exit-type-Mutual-Agreement {
    background: #DBEAFE;
    color: #1E40AF;
    border-color: #1E40AF;
}

.exit-type-Retirement {
    background: #F3E8FF;
    color: #6B21A8;
    border-color: #6B21A8;
}

.exit-type-Other {
    background: #F1F5F9;
    color: #475569;
    border-color: #475569;
}

/* Policy Badges */
.policy-code-badge {
    font-family: 'Courier New', monospace;
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--ep-slate);
    background: #F1F4F9;
    padding: 0.1rem 0.6rem;
    border-radius: 4px;
    display: inline-block;
}

.policy-name {
    font-weight: 600;
    color: var(--ep-ink);
}

.policy-description {
    font-size: 0.8rem;
    color: var(--ep-slate);
    margin-top: 0.15rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.25rem 0.75rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 600;
}

.status-badge.active {
    background: #DCFCE7;
    color: #166534;
}

.status-badge.inactive {
    background: #FEE2E2;
    color: #991B1B;
}

/* Assignment Type Badge */
.assignment-badge {
    display: inline-block;
    padding: 0.15rem 0.6rem;
    border-radius: 4px;
    font-size: 0.6rem;
    font-weight: 500;
}

.assignment-badge.all {
    background: #DCFCE7;
    color: #166534;
}

.assignment-badge.department {
    background: #FEF3C7;
    color: #92400E;
}

.assignment-badge.individual {
    background: #EEEDFC;
    color: #4338CA;
}

.assignment-badge.unassigned {
    background: #F1F5F9;
    color: #94A3B8;
}

/* Requirement Chips */
.req-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 0.3rem;
}

.req-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.1rem 0.5rem;
    border-radius: 4px;
    font-size: 0.6rem;
    font-weight: 500;
}

.req-chip.interview {
    background: var(--ep-info-soft);
    color: var(--ep-info);
}

.req-chip.fnf {
    background: var(--ep-warning-soft);
    color: var(--ep-warning);
}

.req-chip.kt {
    background: var(--ep-success-soft);
    color: var(--ep-success);
}

/* Notice Period */
.notice-display {
    text-align: center;
}

.notice-display .days {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--ep-primary);
    display: block;
}

.notice-display .label {
    font-size: 0.6rem;
    color: var(--ep-slate);
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

/* Action Buttons */
.action-group {
    display: flex;
    gap: 0.3rem;
    flex-wrap: wrap;
}

.action-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    transition: all 0.15s;
    background: transparent;
    color: var(--ep-slate);
    text-decoration: none;
    cursor: pointer;
}

.action-btn:hover {
    background: var(--ep-primary-soft);
    color: var(--ep-primary);
    text-decoration: none;
}

.action-btn.view:hover {
    background: #E0F2FE;
    color: #0369A1;
}

.action-btn.edit:hover {
    background: var(--ep-warning-soft);
    color: var(--ep-warning);
}

.action-btn.toggle:hover {
    background: #E0E7FF;
    color: #4338CA;
}

.action-btn.delete:hover {
    background: var(--ep-danger-soft);
    color: var(--ep-danger);
}

/* Empty State */
.empty-state {
    padding: 4rem 2rem;
    text-align: center;
}

.empty-state .empty-icon {
    font-size: 4rem;
    color: var(--ep-border);
    margin-bottom: 1rem;
}

.empty-state h5 {
    font-weight: 600;
    color: var(--ep-ink);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--ep-slate);
    max-width: 400px;
    margin: 0 auto 1.5rem;
}

/* Table footer */
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
}

/* Modal Styles */
.modal-content {
    border-radius: var(--ep-radius);
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.modal-header {
    border-bottom: 1px solid var(--ep-border);
    padding: 1.25rem 1.5rem;
    background: var(--ep-primary);
    color: white;
    border-radius: var(--ep-radius) var(--ep-radius) 0 0;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.modal-body {
    padding: 1.5rem;
    max-height: 75vh;
    overflow-y: auto;
}

.modal-footer {
    border-top: 1px solid var(--ep-border);
    padding: 1rem 1.5rem;
    background: var(--ep-bg);
    border-radius: 0 0 var(--ep-radius) var(--ep-radius);
}

.detail-section {
    margin-bottom: 1.5rem;
}

.detail-section:last-child {
    margin-bottom: 0;
}

.detail-section .section-title {
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--ep-slate);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--ep-border);
}

.detail-section .detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem 1rem;
}

.detail-section .detail-grid .detail-item {
    display: flex;
    justify-content: space-between;
    padding: 0.25rem 0;
    border-bottom: 1px solid #f1f4f9;
}

.detail-section .detail-grid .detail-item .label {
    color: var(--ep-slate);
    font-size: 0.82rem;
}

.detail-section .detail-grid .detail-item .value {
    font-weight: 500;
    font-size: 0.82rem;
    text-align: right;
}

.detail-section .detail-grid .detail-item .value .badge {
    font-size: 0.7rem;
}

@media (max-width: 576px) {
    .detail-section .detail-grid {
        grid-template-columns: 1fr;
    }
}

/* Toast */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

/* Responsive */
@media (max-width: 768px) {
    .stat-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .table-policies {
        font-size: 0.8rem;
    }

    .action-group {
        gap: 0.15rem;
    }

    .action-btn {
        width: 28px;
        height: 28px;
        font-size: 0.7rem;
    }
}

@media (max-width: 576px) {
    .stat-grid {
        grid-template-columns: 1fr;
    }
}

.table-scroll {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
</style>

<div class="exit-policy-page">
    <div class="page-shell">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <!-- Page Header -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="mb-0 fw-bold" style="color: var(--ep-ink);">
                                <i class="fas fa-door-open text-primary me-2"></i>Exit Policies
                            </h3>
                            <p class="text-muted small mb-0">Manage employee exit policies, notice periods, and offboarding requirements</p>
                        </div>
                        <div class="mt-2 mt-sm-0">
                            <a href="{{ route('exit-policies.create') }}" class="btn btn-primary" style="background: var(--ep-primary); border-color: var(--ep-primary);">
                                <i class="fas fa-plus-circle"></i> Create New Policy
                            </a>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="stat-grid">
                        <div class="stat-card">
                            <div class="stat-icon primary"><i class="fas fa-file-alt"></i></div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $statistics['total'] ?? 0 }}</div>
                                <div class="stat-label">Total Policies</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon success"><i class="fas fa-check-circle"></i></div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $statistics['active'] ?? 0 }}</div>
                                <div class="stat-label">Active Policies</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon warning"><i class="fas fa-pause-circle"></i></div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $statistics['inactive'] ?? 0 }}</div>
                                <div class="stat-label">Inactive Policies</div>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon info"><i class="fas fa-users"></i></div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $statistics['assigned'] ?? 0 }}</div>
                                <div class="stat-label">Assigned Policies</div>
                            </div>
                        </div>
                        <div class="stat-card d-none">
                            <div class="stat-icon danger"><i class="fas fa-user-check"></i></div>
                            <div class="stat-content">
                                <div class="stat-number">{{ $statistics['employees_covered'] ?? 0 }}</div>
                                <div class="stat-label">Employees Covered</div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Section -->
                    <div class="filter-section">
                        <form action="{{ route('exit-policies.index') }}" method="GET" id="filterForm">
                            <div class="filter-row">
                                <div class="form-group mb-0">
                                    <label class="small fw-bold text-muted">Search</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, code, description..." value="{{ request('search') }}">
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="small fw-bold text-muted">Exit Type</label>
                                    <select name="exit_type" class="form-select">
                                        <option value="">All Types</option>
                                        <option value="Resignation" {{ request('exit_type') == 'Resignation' ? 'selected' : '' }}>Resignation</option>
                                        <option value="Termination" {{ request('exit_type') == 'Termination' ? 'selected' : '' }}>Termination</option>
                                        <option value="End of Contract" {{ request('exit_type') == 'End of Contract' ? 'selected' : '' }}>End of Contract</option>
                                        <option value="Mutual Agreement" {{ request('exit_type') == 'Mutual Agreement' ? 'selected' : '' }}>Mutual Agreement</option>
                                        <option value="Retirement" {{ request('exit_type') == 'Retirement' ? 'selected' : '' }}>Retirement</option>
                                        <option value="Other" {{ request('exit_type') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="small fw-bold text-muted">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="small fw-bold text-muted">Assignment Type</label>
                                    <select name="assignment" class="form-select">
                                        <option value="">All</option>
                                        <option value="all" {{ request('assignment') == 'all' ? 'selected' : '' }}>All Departments</option>
                                        <option value="department" {{ request('assignment') == 'department' ? 'selected' : '' }}>Department</option>
                                        <option value="individual" {{ request('assignment') == 'individual' ? 'selected' : '' }}>Individual</option>
                                        <option class="d-none" value="unassigned" {{ request('assignment') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                    
                                    </select>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="small fw-bold text-muted">&nbsp;</label>
                                    <div class="filter-actions">
                                        <button type="submit" class="btn btn-primary btn-sm" style="background: var(--ep-primary); border-color: var(--ep-primary);">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <a href="{{ route('exit-policies.index') }}" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-undo"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Policies Table -->
                    <div class="policy-table-wrapper">
                        <div class="table-header">
                            <div class="title">
                                <i class="fas fa-list text-primary"></i> Policy List
                                <span class="badge-count">{{ $policies->total() }}</span>
                            </div>
                            <div>
                                <span class="text-muted small">
                                    Showing {{ $policies->firstItem() ?? 0 }} - {{ $policies->lastItem() ?? 0 }} of {{ $policies->total() }}
                                </span>
                            </div>
                        </div>

                        @if($policies->count() > 0)
                        <div class="table-scroll">
                            <table class="table table-policies">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th style="min-width: 200px;">Policy</th>
                                        <th style="min-width: 130px;">Exit Type</th>
                                        <th style="min-width: 130px;">Assignment</th>
                                        <th style="min-width: 100px;">Notice Period</th>
                                        <th style="min-width: 120px;">Steps</th>
                                        <th style="min-width: 90px;">Status</th>
                                        <th style="min-width: 160px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($policies as $index => $policy)
                                    @php
                                        // Get assignment info
                                        $assignments = $policy->assignments;
                                        $assignedCount = $assignments->count();
                                        
                                        $assignmentType = 'unassigned';
                                        $assignmentLabel = 'Unassigned';
                                        $assignmentClass = 'unassigned';
                                        
                                        if ($assignedCount > 0) {
                                            $firstAssignment = $assignments->first();
                                            if ($firstAssignment->assignment_type === 'all_department' || $firstAssignment->assignment_type === 'all_fallback') {
                                                $assignmentType = 'all';
                                                $assignmentLabel = 'All Departments';
                                                $assignmentClass = 'all';
                                            } elseif ($firstAssignment->assignment_type === 'department' || $firstAssignment->assignment_type === 'department_only') {
                                                $assignmentType = 'department';
                                                $deptName = $firstAssignment->department ? $firstAssignment->department->department : 'Department';
                                                $assignmentLabel = $deptName . ($assignedCount > 1 ? ' (+' . ($assignedCount - 1) . ')' : '');
                                                $assignmentClass = 'department';
                                            } elseif ($firstAssignment->assignment_type === 'individual' || $firstAssignment->assignment_type === 'individual_only') {
                                                $assignmentType = 'individual';
                                                $empName = $firstAssignment->employee ? $firstAssignment->employee->name : 'Employee';
                                                $assignmentLabel = $empName . ($assignedCount > 1 ? ' (+' . ($assignedCount - 1) . ')' : '');
                                                $assignmentClass = 'individual';
                                            } else {
                                                $assignmentLabel = ucfirst($firstAssignment->assignment_type);
                                            }
                                        }
                                    @endphp
                                    <tr class="policy-row">
                                        <td>{{ $policies->firstItem() + $index }}</td>
                                        <td>
                                            <div>
                                                <span class="policy-name">{{ $policy->policy_name }}</span>
                                                <span class="policy-code-badge">{{ $policy->policy_code }}</span>
                                               
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $exitType = $policy->exit_type ?? 'N/A';
                                                $exitTypeClass = 'exit-type-' . str_replace(' ', '-', $exitType);
                                            @endphp
                                            <span class="exit-type-single {{ $exitTypeClass }}">
                                                {{ $exitType }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="assignment-badge {{ $assignmentClass }}">
                                                @if($assignmentType === 'all')
                                                    <i class="fas fa-globe"></i>
                                                @elseif($assignmentType === 'department')
                                                    <i class="fas fa-building"></i>
                                                @elseif($assignmentType === 'individual')
                                                    <i class="fas fa-user"></i>
                                                @else
                                                    <i class="fas fa-times"></i>
                                                @endif
                                                {{ $assignmentType }}
                                            </span>
                                            @if($assignedCount > 1 && $assignmentType !== 'all')
                                                <span class="badge bg-secondary rounded-pill" style="font-size:0.6rem;">+{{ $assignedCount - 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="notice-display">
                                                <span class="days">{{ $policy->default_notice_period ?? 'N/A' }}</span>
                                                <span class="label">Days</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="req-chips">
                                                @if($policy->exit_interview_required)
                                                <span class="req-chip interview" style="font-size:0.55rem;"><i class="fas fa-comments"></i> Interview</span>
                                                @endif
                                                @if($policy->fnf_required)
                                                <span class="req-chip fnf" style="font-size:0.55rem;"><i class="fas fa-file-invoice-dollar"></i> FNF</span>
                                                @endif
                                                @if($policy->kt_required)
                                                <span class="req-chip kt" style="font-size:0.55rem;"><i class="fas fa-chalkboard-teacher"></i> KT</span>
                                                @endif
                                                @if(!$policy->exit_interview_required && !$policy->fnf_required && !$policy->kt_required)
                                                <span class="text-muted small" style="font-size:0.6rem;">None</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge {{ $policy->is_active ? 'active' : 'inactive' }}" style="font-size:0.6rem; padding:0.15rem 0.5rem;">
                                                <i class="fas fa-circle" style="font-size:0.3rem;"></i>
                                                {{ $policy->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-group">
                                                <a href="{{ route('exit-policies.show', $policy->id) }}" class="action-btn view" title="View Details" data-bs-toggle="tooltip" style="width:28px; height:28px; font-size:0.7rem; text-decoration:none;">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('exit-policies.edit', $policy->id) }}" class="action-btn edit" title="Edit Policy" data-bs-toggle="tooltip" style="width:28px; height:28px; font-size:0.7rem;">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <!-- <button type="button" class="action-btn toggle toggle-status" data-id="{{ $policy->id }}" data-active="{{ $policy->is_active ? '1' : '0' }}" title="{{ $policy->is_active ? 'Deactivate' : 'Activate' }}" data-bs-toggle="tooltip" style="width:28px; height:28px; font-size:0.7rem;">
                                                    <i class="fas {{ $policy->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                                </button>
                                                <button type="button" class="action-btn delete delete-policy" data-id="{{ $policy->id }}" data-name="{{ $policy->policy_name }}" title="Delete Policy" data-bs-toggle="tooltip" style="width:28px; height:28px; font-size:0.7rem;">
                                                    <i class="fas fa-trash"></i>
                                                </button> -->
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($policies->hasPages())
                        <div class="table-footer">
                            <div>
                                <small class="text-muted">
                                    Showing {{ $policies->firstItem() ?? 0 }} to {{ $policies->lastItem() ?? 0 }} of {{ $policies->total() }} entries
                                </small>
                            </div>
                            <div>
                                {{ $policies->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>
                        @endif
                        @else
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-file-alt"></i></div>
                            <h5>No Policies Found</h5>
                            <p>You haven't created any exit policies yet. Click the button below to create your first policy.</p>
                            <a href="{{ route('exit-policies.create') }}" class="btn btn-primary" style="background: var(--ep-primary); border-color: var(--ep-primary);">
                                <i class="fas fa-plus"></i> Create Your First Policy
                            </a>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-trash-alt text-danger me-2"></i>Delete Policy
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-3">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    <h6 class="mt-3">Are you sure you want to delete this policy?</h6>
                    <p>You are about to delete <strong id="deletePolicyName"></strong>.</p>
                    <div class="alert alert-danger">
                        <i class="fas fa-info-circle"></i> This action cannot be undone. All assignments for this policy will also be removed.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Policy
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Toast Container -->
<div class="toast-container">
    @if(session('success'))
    <div class="toast align-items-center text-white bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="toast align-items-center text-white bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Initialize Bootstrap 5 tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-submit filter on select change
    $('#filterForm select').on('change', function() {
        $('#filterForm').submit();
    });

    // Debounced search
    var searchTimeout;
    $('input[name="search"]').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            $('#filterForm').submit();
        }, 500);
    });

    // Toggle Status
    $('.toggle-status').on('click', function() {
        var id = $(this).data('id');
        var isActive = $(this).data('active') == 1;
        var action = isActive ? 'deactivate' : 'activate';

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to " + action + " this policy",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: isActive ? '#dc3545' : '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, ' + action + ' it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('exit-policies.toggle-status', '') }}/" + id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to toggle policy status: ' + (xhr.responseJSON?.message || 'Unknown error')
                        });
                    }
                });
            }
        });
    });

    // Delete Policy
    $('.delete-policy').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');

        $('#deletePolicyName').text(name);
        $('#deleteForm').attr('action', "{{ route('exit-policies.destroy', '') }}/" + id);
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    });

    // Auto-hide toasts after 5 seconds
    setTimeout(function() {
        $('.toast').each(function() {
            $(this).removeClass('show');
        });
    }, 5000);
});
</script>
@endsection