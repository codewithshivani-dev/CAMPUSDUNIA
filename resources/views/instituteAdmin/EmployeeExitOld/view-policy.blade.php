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

.back-link:hover {
    color: var(--ep-primary);
    text-decoration: none;
}

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

.policy-header .policy-meta .meta-item .badge {
    font-size: 0.75rem;
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

.info-card .card-title .badge {
    font-size: 0.65rem;
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

.info-grid .info-item .label {
    color: var(--ep-slate);
    font-size: 0.85rem;
}

.info-grid .info-item .value {
    font-weight: 500;
    font-size: 0.85rem;
    text-align: right;
}

/* Exit Type Badge */
.exit-type-badge {
    display: inline-block;
    padding: 0.25rem 0.9rem;
    border-radius: 999px;
    font-size: 0.8rem;
    font-weight: 600;
}

.exit-type-Resignation {
    background: #FEE2E2;
    color: #991B1B;
}

.exit-type-Termination {
    background: #FEF3C7;
    color: #92400E;
}

.exit-type-End-of-Contract {
    background: #D1FAE5;
    color: #065F46;
}

.exit-type-Mutual-Agreement {
    background: #DBEAFE;
    color: #1E40AF;
}

.exit-type-Retirement {
    background: #F3E8FF;
    color: #6B21A8;
}

.exit-type-Other {
    background: #F1F5F9;
    color: #475569;
}

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

.status-badge-large.active {
    background: #DCFCE7;
    color: #166534;
}

.status-badge-large.inactive {
    background: #FEE2E2;
    color: #991B1B;
}

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
.req-chips-large {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
}

.req-chip-large {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.2rem 0.7rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
}

.req-chip-large.interview {
    background: var(--ep-info-soft);
    color: var(--ep-info);
}

.req-chip-large.fnf {
    background: var(--ep-warning-soft);
    color: var(--ep-warning);
}

.req-chip-large.kt {
    background: var(--ep-success-soft);
    color: var(--ep-success);
}

.req-chip-large.additional {
    background: #F1F5F9;
    color: #475569;
}

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

.employee-row:hover {
    background: #FAFBFC;
}

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

.table-footer .pagination {
    margin: 0;
}

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

.table-footer .pagination .page-item.disabled .page-link {
    color: var(--ep-muted);
}

/* Responsive */
@media (max-width: 768px) {
    .policy-header .policy-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .policy-header .policy-actions {
        width: 100%;
        justify-content: flex-start;
        margin-top: 0.5rem;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .info-grid .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.1rem;
    }

    .info-grid .info-item .value {
        text-align: left;
    }

    .table-footer {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 0.75rem;
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
                                    <span class="policy-code-badge">
                                        {{ $policy->policy_code }}
                                    </span>
                                </div>

                                <div class="policy-meta mt-2">
                                    <span class="meta-item">
                                        <i class="fas fa-tag text-muted"></i>
                                        <span
                                            class="exit-type-badge {{ 'exit-type-' . str_replace(' ', '-', $policy->exit_type ?? 'Other') }}">
                                            {{ $policy->exit_type ?? 'N/A' }}
                                        </span>
                                    </span>

                                    <span class="meta-item">
                                        
                                        <span
                                            class="status-badge-large {{ $policy->is_active ? 'active' : 'inactive' }}">
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
                                    <a href="{{ route('exit-policies.edit', $policy->id) }}"
                                        class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit Policy
                                    </a>
                                    <a href="{{ route('exit-policies.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-list"></i> All Policies
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assignment Info -->
                    <div class="info-card">
                        <div class="card-title">
                            <i class="fas fa-user-check text-primary"></i> Assignment Information
                          
                        </div>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="label">Assignment Type</span>
                                <span class="value">
                                    @if($assignmentType === 'all')
                                    <span class="badge bg-success">All Departments</span>
                                    @elseif($assignmentType === 'department')
                                    <span class="badge bg-warning text-dark">Department Specific</span>
                                    @elseif($assignmentType === 'individual')
                                    <span class="badge bg-info">Individual Employees</span>
                                    @else
                                    <span class="badge bg-secondary">Unassigned</span>
                                    @endif
                                </span>
                            </div>

                            @if($assignmentType === 'department' && !empty($assignmentDepartments))
                            <div class="info-item">
                                <span class="label">Departments</span>
                                <span class="value">
                                    @foreach($assignmentDepartments as $deptId => $deptName)
                                    <span class="badge bg-light text-dark border me-1 mb-1">{{ $deptName }}</span>
                                    @endforeach
                                </span>
                            </div>
                            @endif

                            <div class="info-item">
                                <span class="label">Total Employees Covered</span>
                                <span class="value"><strong>{{ $assignedEmployees->total() }}</strong></span>
                            </div>

                            <div class="info-item">
                                <span class="label">Default Notice Period</span>
                                <span class="value">
                                    <div class="notice-display-large">
                                        <span class="days">{{ $policy->default_notice_period ?? 'N/A' }}</span>
                                        <span class="label">Days</span>
                                    </div>
                                </span>
                            </div>
                        </div>

                        @if(!empty($employmentNoticePeriods))
                        <div class="mt-3">
                            <small class="text-muted d-block mb-1"><i class="fas fa-clock"></i> Employment Type
                                Overrides:</small>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($employmentNoticePeriods as $type => $period)
                                @php
                                $customDays = $employmentCustomDays[$type] ?? null;
                                $displayDays = ($period === 'custom' && $customDays) ? $customDays : $period;
                                @endphp
                                <span class="override-item">
                                    <strong>{{ $type }}</strong>: {{ $displayDays }} days
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Policy Details -->
                    <div class="row">
                        <!-- Requirements -->
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="card-title">
                                    <i class="fas fa-clipboard-list text-primary"></i> Requirements
                                </div>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="label">Exit Interview</span>
                                        <span class="value">
                                            <span
                                                class="badge {{ $policy->exit_interview_required ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $policy->exit_interview_required ? 'Required' : 'Not Required' }}
                                            </span>
                                            @if($policy->exit_interview_required && $policy->interview_days)
                                            <small class="text-muted d-block">({{ $policy->interview_days }}
                                                days)</small>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">FNF (Full & Final)</span>
                                        <span class="value">
                                            <span
                                                class="badge {{ $policy->fnf_required ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $policy->fnf_required ? 'Required' : 'Not Required' }}
                                            </span>
                                            @if($policy->fnf_required && $policy->fnf_processing_days)
                                            <small class="text-muted d-block">({{ $policy->fnf_processing_days }} days
                                                processing)</small>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Knowledge Transfer (KT)</span>
                                        <span class="value">
                                            <span
                                                class="badge {{ $policy->kt_required ? 'bg-success' : 'bg-secondary' }}">
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
                                            <span
                                                class="badge bg-info">{{ ucfirst($policy->clearance_workflow ?? 'N/A') }}</span>
                                            <small class="text-muted d-block">({{ $policy->clearance_days ?? 'N/A' }}
                                                days)</small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="card-title">
                                    <i class="fas fa-chart-pie text-primary"></i> Quick Summary
                                </div>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <span class="label">Policy Code</span>
                                        <span class="value"><code>{{ $policy->policy_code }}</code></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Exit Type</span>
                                        <span class="value">{{ $policy->exit_type ?? 'N/A' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Status</span>
                                        <span class="value">
                                            <span class="badge {{ $policy->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $policy->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Assignments</span>
                                        <span class="value"><strong>{{ $assignedCount }}</strong> record(s)</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Employees Covered</span>
                                        <span class="value"><strong>{{ $assignedEmployees->total() }}</strong></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label">Last Updated</span>
                                        <span class="value">{{ $policy->updated_at->format('d M Y, h:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FNF Items -->
                    @if($policy->fnf_required && !empty($fnfItems))
                    <div class="info-card">
                        <div class="card-title">
                            <i class="fas fa-file-invoice-dollar text-warning"></i> FNF Items
                        </div>
                        <div class="req-chips-large">
                            @php
                            $fnfLabels = [
                            'salary_settlement' => 'Salary Settlement',
                            'leave_encashment' => 'Leave Encashment',
                            'bonus_settlement' => 'Bonus/Incentive',
                            'reimbursement' => 'Reimbursement',
                            'pf_settlement' => 'PF Settlement',
                            'esi_settlement' => 'ESI Settlement',
                            'gratuity' => 'Gratuity',
                            'other_dues' => 'Other Dues'
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

                    <!-- KT Requirements -->
                    @if($policy->kt_required && !empty($ktRequirements))
                    <div class="info-card">
                        <div class="card-title">
                            <i class="fas fa-chalkboard-teacher text-success"></i> KT Requirements
                        </div>
                        <div class="req-chips-large">
                            @php
                            $ktLabels = [
                            'documentation' => 'Documentation',
                            'project_handover' => 'Project Handover',
                            'code_handover' => 'Code Handover',
                            'client_handover' => 'Client Handover',
                            'process_handover' => 'Process Handover',
                            'training' => 'Training',
                            'knowledge_docs' => 'Knowledge Docs'
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

                    <!-- Additional Requirements -->
                    @if(!empty($additionalRequirements))
                    <div class="info-card">
                        <div class="card-title">
                            <i class="fas fa-plus-circle text-secondary"></i> Additional Requirements
                        </div>
                        <div class="req-chips-large">
                            @php
                            $additionalLabels = [
                            'asset_return' => 'Asset Return',
                            'access_revocation' => 'Access Revocation',
                            'id_card_return' => 'ID Card Return',
                            'visa_cancellation' => 'Visa Cancellation',
                            'exit_reentry_visa' => 'Exit Re-entry Visa',
                            'medical_certificate' => 'Medical Certificate',
                            'police_clearance' => 'Police Clearance',
                            'housing_handover' => 'Housing Handover'
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

                    <!-- Description & Terms -->
                    <div class="row">
                        @if($policy->description)
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="card-title">
                                    <i class="fas fa-align-left text-primary"></i> Description
                                </div>
                                <p class="mb-0" style="white-space: pre-wrap; line-height: 1.6;">
                                    {{ $policy->description }}</p>
                            </div>
                        </div>
                        @endif

                        @if($policy->terms_conditions)
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="card-title">
                                    <i class="fas fa-file-contract text-primary"></i> Terms & Conditions
                                </div>
                                <p class="mb-0" style="white-space: pre-wrap; line-height: 1.6;">
                                    {{ $policy->terms_conditions }}</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Assigned Employees -->
                    <div class="employees-table-wrapper mt-3">
                        <div class="table-header">
                            <div class="title">
                                <i class="fas fa-users text-primary"></i> Assigned Employees
                                <span class="badge-count">{{ $assignedEmployees->total() }}</span>
                            </div>
                            <div>
                                <span class="text-muted small">
                                    @if($assignmentType === 'all')
                                    <i class="fas fa-globe text-success"></i> All employees of this institute
                                    @elseif($assignmentType === 'department')
                                    <i class="fas fa-building text-warning"></i> Employees from selected departments
                                    @elseif($assignmentType === 'individual')
                                    <i class="fas fa-user text-info"></i> Individual employees
                                    @else
                                    <i class="fas fa-times text-muted"></i> No assignments
                                    @endif
                                </span>
                            </div>
                        </div>

                        @if($assignedEmployees->count() > 0)
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
                                            $deptName = \App\Models\Departments::where('department_id',
                                            $employee->department_id)->value('department');
                                            @endphp
                                            <span
                                                class="badge bg-light text-dark border">{{ $deptName ?? 'N/A' }}</span>
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

                        {{-- Pagination Links --}}
                        {{-- Pagination Links --}}
                        @if($assignedEmployees->hasPages())
                        <div class="table-footer">
                            <div>
                                <small class="text-muted">
                                    Showing {{ $assignedEmployees->firstItem() ?? 0 }} to
                                    {{ $assignedEmployees->lastItem() ?? 0 }} of {{ $assignedEmployees->total() }}
                                    employees
                                </small>
                            </div>
                            <div>
                                {{ $assignedEmployees->appends(request()->query())->links('pagination::default') }}
                            </div>
                        </div>
                        @else
                        <div class="table-footer">
                            <div>
                                <small class="text-muted">
                                    Showing all {{ $assignedEmployees->total() }} employees
                                </small>
                            </div>
                        </div>
                        @endif
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-users text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2 mb-0">No employees are currently assigned to this policy.</p>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection