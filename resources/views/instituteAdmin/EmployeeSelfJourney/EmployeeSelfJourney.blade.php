@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
/* ============================================
   EMPLOYEE JOURNEY - REDESIGNED HRMS STYLE
   (Same as Admin version for consistency)
   ============================================ */

:root {
    --ej-primary: #4f46e5;
    --ej-primary-light: #eef2ff;
    --ej-success: #16a34a;
    --ej-success-light: #f0fdf4;
    --ej-warning: #d97706;
    --ej-warning-light: #fffbeb;
    --ej-danger: #dc2626;
    --ej-danger-light: #fef2f2;
    --ej-info: #0891b2;
    --ej-info-light: #ecfeff;
    --ej-text: #1e293b;
    --ej-text-muted: #64748b;
    --ej-border: #e2e8f0;
    --ej-bg-soft: #f8fafc;
}

/* --- Page Header --- */
.ej-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.ej-page-header h3 {
    font-weight: 700;
    color: var(--ej-text);
}

/* --- Profile Card --- */
.ej-profile-card {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border: none;
    border-radius: 1rem;
    padding: 28px 32px;
    display: flex;
    align-items: center;
    gap: 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 20px rgba(79, 70, 229, 0.25);
    color: #fff;
}

.ej-profile-card .ej-avatar {
    width: 88px;
    height: 88px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255, 255, 255, 0.3);
    flex-shrink: 0;
}

.ej-profile-card .ej-avatar-fallback {
    width: 88px;
    height: 88px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    border: 3px solid rgba(255, 255, 255, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    font-weight: 700;
    flex-shrink: 0;
}

.ej-profile-card .ej-name {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 6px;
}

.ej-profile-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 20px;
    font-size: 13px;
    opacity: 0.9;
}

.ej-profile-meta span strong {
    color: #fff;
    font-weight: 600;
}

/* --- Individual Summary Cards --- */
.ej-summary-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}

.ej-summary-card {
    background: #fff;
    border: 1px solid var(--ej-border);
    border-radius: 0.875rem;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    transition: box-shadow .2s ease, transform .2s ease;
}

.ej-summary-card:hover {
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    transform: translateY(-2px);
}

.ej-summary-card .ej-summary-icon {
    width: 46px;
    height: 46px;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.ej-summary-card.type .ej-summary-icon {
    background: var(--ej-warning-light);
    color: var(--ej-warning);
}

.ej-summary-card.dept .ej-summary-icon {
    background: var(--ej-info-light);
    color: var(--ej-info);
}

.ej-summary-card.ctc .ej-summary-icon {
    background: var(--ej-success-light);
    color: var(--ej-success);
}

.ej-summary-card .ej-summary-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--ej-text-muted);
    font-weight: 600;
    margin-bottom: 3px;
}

.ej-summary-card .ej-summary-value {
    font-size: 16px;
    font-weight: 700;
    color: var(--ej-text);
}

/* --- Progress Wrapper --- */
.ej-progress-wrapper {
    background: #fff;
    border: 1px solid var(--ej-border);
    border-radius: 0.875rem;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.ej-progress-wrapper .ej-progress-label {
    font-weight: 600;
    color: var(--ej-text);
    font-size: 14px;
    white-space: nowrap;
}

.ej-progress-wrapper .ej-progress-pct {
    font-weight: 700;
    color: var(--ej-primary);
    font-size: 18px;
    white-space: nowrap;
}

.ej-progress-track {
    flex: 1;
    min-width: 160px;
    height: 8px;
    border-radius: 4px;
    background: var(--ej-border);
    overflow: hidden;
}

.ej-progress-fill {
    height: 100%;
    border-radius: 4px;
    background: linear-gradient(90deg, var(--ej-primary), #7c3aed);
    transition: width 0.5s ease;
}

/* --- Stage Cards --- */
.ej-stage-card {
    background: #fff;
    border: 1px solid var(--ej-border);
    border-radius: 1rem;
    margin-bottom: 18px;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
}

.ej-stage-header {
    padding: 16px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    background: var(--ej-bg-soft);
    border-bottom: 1px solid var(--ej-border);
    transition: background 0.2s ease;
}

.ej-stage-header:hover {
    background: #f1f5f9;
}

.ej-stage-header .ej-stage-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 15px;
    font-weight: 700;
    color: var(--ej-text);
}

.ej-stage-number {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}

.ej-stage-number.primary { background: var(--ej-primary); }
.ej-stage-number.info { background: var(--ej-info); }
.ej-stage-number.warning { background: var(--ej-warning); }
.ej-stage-number.success { background: var(--ej-success); }
.ej-stage-number.danger { background: var(--ej-danger); }
.ej-stage-number.secondary { background: #64748b; }

.ej-stage-header .ej-stage-right {
    display: flex;
    align-items: center;
    gap: 12px;
}

.ej-badge {
    font-size: 11px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    white-space: nowrap;
}

.ej-badge.completed {
    background: var(--ej-success-light);
    color: var(--ej-success);
}

.ej-badge.in-progress {
    background: var(--ej-primary-light);
    color: var(--ej-primary);
}

.ej-badge.pending {
    background: #f1f5f9;
    color: var(--ej-text-muted);
}

.ej-badge.assigned {
    background: var(--ej-success-light);
    color: var(--ej-success);
}

.ej-badge.not-assigned {
    background: var(--ej-danger-light);
    color: var(--ej-danger);
}

.ej-stage-body {
    padding: 22px;
}

.ej-stage-body.ej-hidden {
    display: none;
}

/* --- Tabs --- */
.ej-tabs {
    display: flex;
    gap: 4px;
    border-bottom: 2px solid var(--ej-border);
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.ej-tab {
    padding: 9px 20px;
    border: none;
    background: transparent;
    font-size: 13px;
    font-weight: 600;
    color: var(--ej-text-muted);
    cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    transition: all 0.2s ease;
}

.ej-tab:hover {
    color: var(--ej-text);
}

.ej-tab.active {
    color: var(--ej-primary);
    border-bottom-color: var(--ej-primary);
}

.ej-tab-content {
    display: none;
}

.ej-tab-content.active {
    display: block;
    animation: ejFade .25s ease;
}

@keyframes ejFade {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}

/* --- Info Grid --- */
.ej-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
    gap: 12px;
}

.ej-info-chip {
    background: var(--ej-bg-soft);
    border: 1px solid var(--ej-border);
    border-radius: 0.75rem;
    padding: 12px 16px;
    transition: box-shadow .2s ease, transform .2s ease, background .2s ease;
}

.ej-info-chip:hover {
    background: #fff;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
    transform: translateY(-2px);
}

.ej-info-chip .label {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--ej-text-muted);
    font-weight: 700;
    margin-bottom: 5px;
    display: block;
}

.ej-info-chip .value {
    font-weight: 700;
    font-size: 14px;
    color: var(--ej-text);
    line-height: 1.4;
}

/* --- Document Grid --- */
.ej-doc-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 14px;
}

.ej-doc-card {
    border: 1px solid var(--ej-border);
    border-radius: 0.75rem;
    padding: 16px 18px;
    background: #fff;
    transition: box-shadow .2s ease, transform .2s ease;
}

.ej-doc-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    transform: translateY(-2px);
}

.ej-doc-card .doc-top {
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.ej-doc-card .doc-icon {
    font-size: 24px;
    color: var(--ej-primary);
    margin-top: 2px;
    min-width: 32px;
    text-align: center;
}

.ej-doc-card .doc-name {
    font-weight: 600;
    font-size: 13px;
    color: var(--ej-text);
}

.ej-doc-card .doc-meta {
    font-size: 11px;
    color: var(--ej-text-muted);
    margin-top: 2px;
}

.ej-doc-card .doc-status {
    margin-top: 4px;
}

.ej-doc-card .doc-actions {
    display: flex;
    gap: 6px;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid var(--ej-border);
    flex-wrap: wrap;
}

.ej-doc-card .doc-actions .btn {
    font-size: 11px;
    padding: 3px 10px;
}

/* --- Document Reference IDs --- */
.ej-doc-refs {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin-top: 4px;
}

.ej-doc-refs .ej-badge {
    font-size: 10px;
    padding: 2px 10px;
}

.ej-doc-refs .ej-badge i {
    font-size: 10px;
    margin-right: 2px;
}

/* --- Empty State --- */
.ej-empty {
    text-align: center;
    padding: 30px 16px;
    color: var(--ej-text-muted);
}

.ej-empty i {
    font-size: 34px;
    color: #cbd5e1;
    margin-bottom: 10px;
    display: block;
}

.ej-empty p {
    margin: 0;
    font-size: 13px;
}

/* --- Table Styles --- */
.ej-table-wrap {
    border: 1px solid var(--ej-border);
    border-radius: 0.75rem;
    overflow: hidden;
    overflow-x: auto;
}

.ej-table {
    width: 100%;
    border-collapse: collapse;
}

.ej-table th {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: var(--ej-text-muted);
    background: var(--ej-bg-soft);
    padding: 10px 14px;
    text-align: left;
    border-bottom: 2px solid var(--ej-border);
}

.ej-table td {
    padding: 10px 14px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13px;
    color: var(--ej-text);
    vertical-align: middle;
}

.ej-table tr:last-child td {
    border-bottom: none;
}

.ej-table tr:hover td {
    background: var(--ej-bg-soft);
}

/* --- Sub-step accordion (for Joining overview) --- */
.ej-substep {
    border: 1px solid var(--ej-border);
    border-radius: 0.75rem;
    margin-bottom: 12px;
    overflow: hidden;
}

.ej-substep-header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 18px;
    background: var(--ej-bg-soft);
    cursor: pointer;
    transition: background 0.2s ease;
}

.ej-substep-header:hover {
    background: #f1f5f9;
}

.ej-substep-icon {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 16px;
    flex-shrink: 0;
}

.ej-substep-icon.c1 { background: var(--ej-primary); }
.ej-substep-icon.c2 { background: var(--ej-success); }
.ej-substep-icon.c3 { background: var(--ej-info); }
.ej-substep-icon.c4 { background: var(--ej-warning); }
.ej-substep-icon.c5 { background: #db2777; }
.ej-substep-icon.c6 { background: #10b981; }

.ej-substep-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--ej-text);
    flex: 1;
}

.ej-substep-chevron {
    color: var(--ej-text-muted);
    transition: transform .2s ease;
}

.ej-substep-body {
    padding: 18px;
    border-top: 1px solid var(--ej-border);
}

.ej-substep-body.ej-hidden {
    display: none;
}

/* --- Asset Allocation Styles --- */
.ej-asset-section {
    margin-bottom: 26px;
}

.ej-asset-section-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13.5px;
    font-weight: 700;
    color: var(--ej-text);
    margin-bottom: 14px;
}

.ej-asset-section-title i {
    color: var(--ej-primary);
    font-size: 15px;
}

.ej-eligible-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
}

.ej-eligible-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 14px;
    background: var(--ej-success-light);
    border-radius: 0.625rem;
    font-size: 13px;
    font-weight: 600;
    color: var(--ej-text);
}

.ej-eligible-item i {
    color: var(--ej-success);
    font-size: 14px;
    flex-shrink: 0;
}

.ej-process-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.ej-process-step {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 16px;
    background: var(--ej-bg-soft);
    border-radius: 0.625rem;
    font-size: 13px;
    color: var(--ej-text);
}

.ej-process-num {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--ej-primary);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    flex-shrink: 0;
}

.ej-asset-alert {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    background: var(--ej-warning-light);
    border: 1px solid #fde68a;
    border-radius: 0.75rem;
    margin-bottom: 24px;
}

.ej-asset-alert i {
    font-size: 20px;
    color: var(--ej-warning);
    flex-shrink: 0;
}

.ej-asset-alert .ej-asset-alert-title {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--ej-text);
    margin-bottom: 2px;
}

.ej-asset-alert .ej-asset-alert-text {
    font-size: 12.5px;
    color: var(--ej-text-muted);
}

.ej-policy-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.ej-policy-list .policy-row {
    display: flex;
    gap: 10px;
    padding: 10px 14px;
    background: var(--ej-bg-soft);
    border-radius: 0.5rem;
    font-size: 13px;
}

.ej-policy-list .policy-row i {
    color: var(--ej-primary);
    margin-top: 2px;
}

/* --- Exit Styles --- */
.ej-exit-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.ej-exit-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 16px;
    border-radius: 0.625rem;
    border-left: 3px solid var(--ej-border);
    background: var(--ej-bg-soft);
}

.ej-exit-item.completed {
    border-left-color: var(--ej-success);
    background: var(--ej-success-light);
}

.ej-exit-item.in-progress {
    border-left-color: var(--ej-primary);
    background: var(--ej-primary-light);
}

.ej-exit-item .exit-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e2e8f0;
    color: var(--ej-text-muted);
    font-size: 13px;
    flex-shrink: 0;
}

.ej-exit-item.completed .exit-icon {
    background: var(--ej-success);
    color: #fff;
}

.ej-exit-item.in-progress .exit-icon {
    background: var(--ej-primary);
    color: #fff;
}

.ej-exit-item .exit-title {
    font-weight: 600;
    font-size: 13.5px;
    color: var(--ej-text);
}

.ej-exit-item .exit-desc {
    font-size: 12px;
    color: var(--ej-text-muted);
}

.ej-exit-item .exit-date {
    font-size: 11px;
    color: var(--ej-text-muted);
    margin-top: 2px;
}

/* --- Responsive --- */
@media (max-width: 900px) {
    .ej-summary-row {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .ej-profile-card {
        flex-direction: column;
        text-align: center;
        padding: 20px;
    }

    .ej-profile-meta {
        justify-content: center;
    }

    .ej-info-grid {
        grid-template-columns: 1fr;
    }

    .ej-doc-grid {
        grid-template-columns: 1fr;
    }

    .ej-tabs {
        flex-wrap: nowrap;
        overflow-x: auto;
    }

    .ej-tab {
        font-size: 12px;
        padding: 8px 14px;
        white-space: nowrap;
    }

    .ej-stage-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .ej-stage-right {
        width: 100%;
        justify-content: space-between;
    }
}

/* --- Modal Styles --- */
.ej-modal .modal-content {
    border-radius: 1rem;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
}

.ej-modal .modal-header {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: #fff;
    border-radius: 1rem 1rem 0 0;
    padding: 20px 24px;
}

.ej-modal .modal-header .btn-close {
    border-radius: 50%;
    padding: 8px;
    opacity: 1;
}

.ej-modal .modal-body {
    padding: 24px;
}

.ej-modal .modal-footer {
    padding: 16px 24px;
    border-top: 1px solid var(--ej-border);
}

.ej-doc-icon-large {
    font-size: 60px;
    color: var(--ej-primary);
    margin-bottom: 12px;
}

.ej-doc-name-large {
    font-size: 20px;
    font-weight: 700;
    color: var(--ej-text);
}

.ej-doc-type-badge {
    display: inline-block;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

/* --- Probation info within profile --- */
.ej-probation-info {
    background: rgba(255, 255, 255, 0.12);
    border-radius: 0.75rem;
    padding: 12px 16px;
    margin-top: 12px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 8px 20px;
}

.ej-probation-info .label {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.7;
    display: block;
}

.ej-probation-info .value {
    font-size: 13px;
    font-weight: 600;
}
</style>

<div class="container-fluid">

    <!-- Page Header -->
    <div class="ej-page-header">
        <div>
            <h3 class="mb-0"><i class="fas fa-road text-primary"></i> My Journey</h3>
            <p class="text-muted small mb-0">Complete employee lifecycle from onboarding to exit</p>
        </div>
        <a href="{{ route('employee.dashboard') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Profile Card -->
    <div class="ej-profile-card">
        @if($employee->profile_photo)
        <img src="{{ asset('image/' . $employee->profile_photo) }}" alt="{{ $employee->name }}" class="ej-avatar">
        @else
        <div class="ej-avatar-fallback">{{ strtoupper(substr($employee->name, 0, 1)) }}</div>
        @endif
        <div style="flex:1;">
            <div class="ej-name">{{ $employee->name }}
                <span class="ej-badge {{ $employee->status == 'active' ? 'completed' : 'not-assigned' }}">
                    {{ ucfirst($employee->status) }}
                </span>
            </div>
            <div class="ej-profile-meta">
                <span><strong>Code:</strong> {{ $employee->employee_code }}</span>
                <span><strong>Designation:</strong> {{ $employee->designation }}</span>
                <span><strong>Department:</strong> {{ $employee->department_name ?? 'N/A' }}</span>
                <span><strong>DOJ:</strong> {{ Carbon\Carbon::parse($employee->doj)->format('d M, Y') }}</span>
                <span><strong>Email:</strong> {{ $employee->email ?? 'N/A' }}</span>
            </div>

            @if($isOnProbation)
            <div class="ej-probation-info">
                <div>
                    <span class="label">Probation End Date</span>
                    <span class="value">
                        @if($probationEndDate)
                        {{ $probationEndDate->format('d M, Y') }}
                        @if($probationStatus == 'overdue')
                        <span class="ej-badge not-assigned ms-1">Overdue</span>
                        @elseif($probationStatus == 'ending_soon')
                        <span class="ej-badge in-progress ms-1">Ending Soon</span>
                        @elseif($probationStatus == 'active')
                        <span class="ej-badge pending ms-1">In Progress</span>
                        @endif
                        @else
                        N/A
                        @endif
                    </span>
                </div>
                <div>
                    <span class="label">Probation Period</span>
                    <span class="value">{{ $employee->probation_days ?? 'N/A' }} days</span>
                </div>
                <div>
                    <span class="label">Days Remaining</span>
                    <span class="value">
                        @if($probationDaysDelta !== null)
                        @if($probationDaysDelta > 0)
                        {{ $probationDaysDelta }} days
                        @elseif($probationDaysDelta == 0)
                        <span class="ej-badge in-progress">Ends Today</span>
                        @else
                        <span class="ej-badge not-assigned">{{ abs($probationDaysDelta) }} days overdue</span>
                        @endif
                        @else
                        N/A
                        @endif
                    </span>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- SEPARATE SUMMARY CARDS -->
    @php
    $employmentType = $employee->employment_type ?? 'N/A';
    $employmentDisplay = $employmentType === 'Probation-Period' ? 'Probation' : $employmentType;
    $ctcDisplay = $currentSalaryStructure && isset($currentSalaryStructure->total_ctc_annual) 
        ? '₹' . number_format($currentSalaryStructure->total_ctc_annual, 2) 
        : 'N/A';
    @endphp
    <div class="ej-summary-row">
        <div class="ej-summary-card type">
            <div class="ej-summary-icon"><i class="fas fa-user-tag"></i></div>
            <div>
                <div class="ej-summary-label">Employment Type</div>
                <div class="ej-summary-value">
                    @if($employmentType === 'Probation-Period')
                    <span class="ej-badge pending">Probation</span>
                    @else
                    <span class="ej-badge completed">{{ $employmentDisplay }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="ej-summary-card dept">
            <div class="ej-summary-icon"><i class="fas fa-building"></i></div>
            <div>
                <div class="ej-summary-label">Department</div>
                <div class="ej-summary-value">{{ $employee->department_name ?? 'N/A' }}</div>
            </div>
        </div>
        <div class="ej-summary-card ctc">
            <div class="ej-summary-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div>
                <div class="ej-summary-label">Current CTC</div>
                <div class="ej-summary-value">{{ $ctcDisplay }}</div>
            </div>
        </div>
    </div>

    <!-- PROGRESS -->
    @php
    $totalStages = 5;
    $completedStages = 0;
    $stageStatusMap = [
        'onboarding' => $employee->created_at ? 'completed' : 'pending',
        'joining' => $employee->doj ? 'completed' : 'pending',
        'asset_allocation' => 'pending',
        'promotions' => \App\Models\EmployeeProbationLog::where('employee_id', $employee->id)->exists() ? 'completed' : 'pending',
        'exit' => $employee->status === 'inactive' ? 'completed' : 'pending'
    ];
    foreach ($stageStatusMap as $status) {
        if ($status === 'completed') $completedStages++;
    }
    $progressPercentage = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;
    $progressStatus = $progressPercentage == 100 ? 'Completed' : ($progressPercentage > 0 ? 'In Progress' : 'Not Started');
    @endphp
    <div class="ej-progress-wrapper">
        <span class="ej-progress-label"><i class="fas fa-chart-line text-primary"></i> Overall Journey Progress</span>
        <span class="ej-progress-pct">{{ $progressPercentage }}%</span>
        <div class="ej-progress-track">
            <div class="ej-progress-fill" style="width: {{ $progressPercentage }}%;"></div>
        </div>
        <span class="ej-badge {{ $progressPercentage == 100 ? 'completed' : 'in-progress' }}">{{ $progressStatus }}</span>
        <small class="text-muted">{{ $completedStages }} of {{ $totalStages }} stages completed</small>
    </div>

    <!-- ============================================ -->
    <!-- STAGE 1: ONBOARDING -->
    <!-- ============================================ -->
    <div class="ej-stage-card" id="stage-onboarding">
        <div class="ej-stage-header" onclick="ejToggleStage('ejBody-onboarding')">
            <div class="ej-stage-title">
                <span class="ej-stage-number primary">1</span>
                <i class="fas fa-user-plus"></i>
                Onboarding
                @if($employee->created_at)
                <span class="ej-badge pending"><i class="fas fa-calendar-alt me-1"></i>{{ Carbon\Carbon::parse($employee->created_at)->format('d M, Y') }}</span>
                @endif
            </div>
            <div class="ej-stage-right">
                <span class="ej-badge {{ $employee->created_at ? 'completed' : 'pending' }}">
                    {{ $employee->created_at ? 'Completed' : 'Pending' }}
                </span>
                <i class="fas fa-chevron-down text-muted"></i>
            </div>
        </div>

        <div class="ej-stage-body" id="ejBody-onboarding">
            <div class="ej-tabs">
                <button class="ej-tab active" data-stage="onboarding" data-tab="overview">
                    <i class="fas fa-info-circle"></i> Overview
                </button>
                <button class="ej-tab" data-stage="onboarding" data-tab="documents">
                    <i class="fas fa-file-alt"></i> Documents
                </button>
            </div>

            <!-- Overview Tab - Shows: Onboarded Date, Employee Code, Status -->
            <div class="ej-tab-content active" data-stage="onboarding" data-tab="overview">
                @php
                $onboardingData = [
                    'Onboarded Date' => $employee->created_at ? Carbon\Carbon::parse($employee->created_at)->format('d M, Y') : 'N/A',
                    'Employee Code' => $employee->employee_code ?? 'N/A',
                    'Status' => $employee->status ? ucfirst($employee->status) : 'N/A',
                ];
                @endphp
                <div class="ej-info-grid">
                    @foreach($onboardingData as $label => $value)
                    <div class="ej-info-chip">
                        <span class="label">{{ $label }}</span>
                        <span class="value">{!! $value !!}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Documents Tab -->
            <div class="ej-tab-content" data-stage="onboarding" data-tab="documents">
                @php
                $onboardingDocs = $documents['Onboarding'] ?? [];
                @endphp
                @if(count($onboardingDocs) > 0)
                <div class="ej-doc-grid">
                    @foreach($onboardingDocs as $doc)
                    <div class="ej-doc-card" data-template-id="{{ $doc->id }}">
                        <div class="doc-top">
                            <div class="doc-icon text-{{ $doc->color ?? 'secondary' }}">
                                <i class="{{ $doc->icon ?? 'fas fa-file' }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="doc-name">{{ $doc->name }}</div>
                                <div class="doc-meta">
                                    <i class="fas fa-calendar-alt"></i> {{ $doc->date }}
                                    <span class="mx-1">|</span>
                                    <i class="fas fa-file"></i> {{ $doc->type }}
                                    @if(isset($doc->size) && $doc->size != '---')
                                    <span class="mx-1">|</span>
                                    <i class="fas fa-weight"></i> {{ $doc->size }}
                                    @endif
                                </div>

                                <!-- Document Reference IDs -->
                                <div class="ej-doc-refs">
                                    @if(isset($doc->generated_letter_id) && $doc->generated_letter_id)
                                    <span class="ej-badge info">
                                        <i class="fas fa-hashtag"></i> ID: {{ $doc->generated_letter_id }}
                                    </span>
                                    @endif
                                    @if(isset($doc->reference_id) && $doc->reference_id)
                                    <span class="ej-badge pending">
                                        <i class="fas fa-tag"></i> Ref: {{ $doc->reference_id }}
                                    </span>
                                    @endif
                                    @if(isset($doc->document_request_id) && $doc->document_request_id && isset($doc->status) && $doc->status != 'generated')
                                    <span class="ej-badge in-progress">
                                        <i class="fas fa-clock"></i> Req #{{ $doc->document_request_id }}
                                    </span>
                                    @endif
                                </div>

                                @if(isset($doc->status))
                                <div class="doc-status mt-1">
                                    <span class="ej-badge {{ $doc->status == 'generated' ? 'completed' : ($doc->status == 'pending' ? 'pending' : ($doc->status == 'approved' ? 'assigned' : ($doc->status == 'rejected' ? 'not-assigned' : 'pending'))) }}">
                                        {{ $doc->status == 'pending' ? 'In Process' : ucfirst($doc->status) }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="doc-actions">
                            @if(isset($doc->status) && $doc->status == 'generated')
                                @if(isset($doc->file_path) && ($doc->file_exists ?? false))
                                <button class="btn btn-success btn-sm" onclick="viewDocument('{{ $doc->file_path }}', '{{ addslashes($doc->name) }}')">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="downloadDocument('{{ $doc->file_path }}', '{{ addslashes($doc->name) }}')">
                                    <i class="fas fa-download"></i> Download
                                </button>
                                @else
                                <span class="text-muted small"><i class="fas fa-exclamation-triangle text-warning"></i> File not found</span>
                                @endif
                            @elseif(isset($doc->status) && in_array($doc->status, ['pending', 'approved', 'processing']))
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="fas fa-clock"></i> {{ $doc->status == 'pending' ? 'In Process' : ucfirst($doc->status) }}
                                </button>
                                @if($doc->status == 'pending' && isset($doc->document_request_id))
                                <button class="btn btn-outline-danger btn-sm" onclick="cancelRequest('{{ $doc->document_request_id }}', '{{ $employee->employee_id }}')">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                                @endif
                            @else
                                <button class="btn btn-warning btn-sm text-dark" onclick="openRequestModal(
                                    '{{ $doc->id }}',
                                    '{{ $employee->employee_id }}',
                                    '{{ addslashes($doc->name) }}',
                                    '{{ $doc->type ?? 'Document' }}'
                                )">
                                    <i class="fas fa-paper-plane"></i> Request
                                </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="ej-empty">
                    <i class="fas fa-inbox"></i>
                    <p>No onboarding documents available.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- STAGE 2: JOINING (with Sub-steps) -->
    <!-- ============================================ -->
    <div class="ej-stage-card" id="stage-joining">
        <div class="ej-stage-header" onclick="ejToggleStage('ejBody-joining')">
            <div class="ej-stage-title">
                <span class="ej-stage-number info">2</span>
                <i class="fas fa-handshake"></i>
                Joining
                @if($employee->doj)
                <span class="ej-badge pending"><i class="fas fa-calendar-alt me-1"></i>{{ Carbon\Carbon::parse($employee->doj)->format('d M, Y') }}</span>
                @endif
            </div>
            <div class="ej-stage-right">
                <span class="ej-badge {{ $employee->doj ? 'completed' : 'pending' }}">
                    {{ $employee->doj ? 'Completed' : 'Pending' }}
                </span>
                <i class="fas fa-chevron-down text-muted"></i>
            </div>
        </div>

        <div class="ej-stage-body" id="ejBody-joining">
            <div class="ej-tabs">
                <button class="ej-tab active" data-stage="joining" data-tab="overview">
                    <i class="fas fa-list-check"></i> Overview
                </button>
                <button class="ej-tab" data-stage="joining" data-tab="documents">
                    <i class="fas fa-file-alt"></i> Documents
                </button>
            </div>

            <!-- OVERVIEW TAB: 6 Sub-steps -->
            <div class="ej-tab-content active" data-stage="joining" data-tab="overview">
                @php
                $joiningSubSteps = [
                    ['key' => 'department', 'title' => 'A. Department', 'icon' => 'fas fa-building', 'cls' => 'c1'],
                    ['key' => 'team_members', 'title' => 'B. Team Members', 'icon' => 'fas fa-users', 'cls' => 'c2'],
                    ['key' => 'reporting_manager', 'title' => 'C. Reporting Manager', 'icon' => 'fas fa-user-tie', 'cls' => 'c3'],
                    ['key' => 'salary_structure', 'title' => 'D. Salary Structure', 'icon' => 'fas fa-money-bill-wave', 'cls' => 'c4'],
                    ['key' => 'leave_quota', 'title' => 'E. Leave Quota', 'icon' => 'fas fa-calendar-alt', 'cls' => 'c5'],
                    ['key' => 'reimbursement_policy', 'title' => 'F. Reimbursement Policy', 'icon' => 'fas fa-receipt', 'cls' => 'c6'],
                ];
                @endphp

                @foreach($joiningSubSteps as $idx => $sub)
                @php
                $subData = $joiningData[$sub['key']] ?? [];
                $isAssigned = $subData['is_assigned'] ?? false;
                @endphp
                <div class="ej-substep">
                    <div class="ej-substep-header" onclick="ejToggleSub('ejSub-joining-{{ $sub['key'] }}', this)">
                        <div class="ej-substep-icon {{ $sub['cls'] }}"><i class="{{ $sub['icon'] }}"></i></div>
                        <div class="ej-substep-title">{{ $sub['title'] }}</div>
                        <span class="ej-badge {{ $isAssigned ? 'assigned' : 'not-assigned' }}">
                            <i class="fas {{ $isAssigned ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                            {{ $isAssigned ? 'Assigned' : 'Not Assigned' }}
                        </span>
                        <i class="fas fa-chevron-down ej-substep-chevron"></i>
                    </div>
                    <div class="ej-substep-body {{ $idx === 0 ? '' : 'ej-hidden' }}" id="ejSub-joining-{{ $sub['key'] }}">

                        {{-- A. DEPARTMENT --}}
                        @if($sub['key'] === 'department')
                        @if($isAssigned && isset($subData['current_department']))
                        <div class="ej-info-grid mb-3">
                            <div class="ej-info-chip"><span class="label">Joining Date</span><span class="value">{{ $subData['DOJ'] ?? 'N/A' }}</span></div>
                            <div class="ej-info-chip"><span class="label">Category</span><span class="value">{{ $subData['department_category'] ?? 'N/A' }}</span></div>
                            <div class="ej-info-chip"><span class="label">Department</span><span class="value">{{ $subData['current_department'] ?? 'N/A' }}</span></div>
                            <div class="ej-info-chip"><span class="label">Designation</span><span class="value">{{ $subData['current_designation'] ?? 'N/A' }}</span></div>
                        </div>
                        @if(!empty($subData['history']))
                        <div class="ej-table-wrap">
                            <table class="ej-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>From Dept</th>
                                        <th>To Dept</th>
                                        <th>Performed By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subData['history'] as $h)
                                    <tr>
                                        <td>{{ $h['date'] }}</td>
                                        <td>{{ $h['from_department'] }}</td>
                                        <td><strong>{{ $h['to_department'] }}</strong></td>
                                        <td>{{ $h['performed_by'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                        @else
                        <div class="ej-empty"><i class="fas fa-building"></i><p>{{ $subData['message'] ?? 'No department assigned yet.' }}</p></div>
                        @endif

                        {{-- B. TEAM MEMBERS --}}
                        @elseif($sub['key'] === 'team_members')
                        @if($isAssigned && !empty($subData['members']) && count($subData['members']) > 0)
                        <div class="ej-table-wrap">
                            <table class="ej-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Designation</th>
                                        <th>Employee Code</th>
                                        <th>Date of Joining</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subData['members'] as $member)
                                    <tr>
                                        <td>{{ $member->name ?? 'N/A' }}</td>
                                        <td>{{ $member->designation ?? 'N/A' }}</td>
                                        <td>{{ $member->employee_code ?? 'N/A' }}</td>
                                        <td>{{ isset($member->doj) ? Carbon\Carbon::parse($member->doj)->format('d M, Y') : 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="ej-empty"><i class="fas fa-users"></i><p>{{ $subData['message'] ?? 'No team members found.' }}</p></div>
                        @endif

                        {{-- C. REPORTING MANAGER --}}
                        @elseif($sub['key'] === 'reporting_manager')
                        @if($isAssigned && !empty($subData['manager']))
                        <div class="ej-info-grid">
                            <div class="ej-info-chip"><span class="label">Name</span><span class="value">{{ $subData['manager']->name ?? 'N/A' }}</span></div>
                            <div class="ej-info-chip"><span class="label">Designation</span><span class="value">{{ $subData['manager']->designation ?? 'N/A' }}</span></div>
                            <div class="ej-info-chip"><span class="label">Employee Code</span><span class="value">{{ $subData['manager']->employee_code ?? 'N/A' }}</span></div>
                            <div class="ej-info-chip"><span class="label">DOJ</span><span class="value">{{ isset($subData['manager']->doj) ? Carbon\Carbon::parse($subData['manager']->doj)->format('d M, Y') : 'N/A' }}</span></div>
                        </div>
                        @else
                        <div class="ej-empty"><i class="fas fa-user-tie"></i><p>{{ $subData['message'] ?? 'No reporting manager assigned yet.' }}</p></div>
                        @endif

                        {{-- D. SALARY STRUCTURE --}}
                        @elseif($sub['key'] === 'salary_structure')
                        @if($isAssigned && !empty($subData['current']))
                        <div class="ej-info-grid mb-3">
                            <div class="ej-info-chip"><span class="label">Total CTC (Annual)</span><span class="value">₹{{ number_format($subData['current']->total_ctc_annual ?? 0, 2) }}</span></div>
                            <div class="ej-info-chip"><span class="label">Basic (Monthly)</span><span class="value">₹{{ number_format($subData['current']->basic_salary_monthly ?? 0, 2) }}</span></div>
                            <div class="ej-info-chip"><span class="label">Financial Year</span><span class="value">{{ $subData['current']->financial_year ?? 'N/A' }}</span></div>
                        </div>
                        @if(!empty($subData['history']))
                        <div class="ej-table-wrap">
                            <table class="ej-table">
                                <thead>
                                    <tr>
                                        <th>Salary Structure Id</th>
                                        <th>FY</th>
                                        <th>Basic (Monthly)</th>
                                        <th>Total CTC</th>
                                        <th>Effective Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subData['history'] as $h)
                                    <tr>
                                        <td>{{ $h['id'] }}</td>
                                        <td>{{ $h['financial_year'] }}</td>
                                        <td>{{ $h['basic_monthly'] }}</td>
                                        <td><strong>{{ $h['total_ctc_annual'] }}</strong></td>
                                        <td>{{ $h['effective_date'] }}</td>
                                        <td>
                                            <span class="ej-badge {{ !empty($h['is_active']) ? 'completed' : 'not-assigned' }}">
                                                {{ !empty($h['is_active']) ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                        @else
                        <div class="ej-empty"><i class="fas fa-money-bill-wave"></i><p>{{ $subData['message'] ?? 'No salary structure assigned yet.' }}</p></div>
                        @endif

                        {{-- E. LEAVE QUOTA --}}
                        @elseif($sub['key'] === 'leave_quota')
                        @if($isAssigned && !empty($subData['quotas']) && count($subData['quotas']) > 0)
                        <div class="ej-table-wrap">
                            <table class="ej-table">
                                <thead>
                                    <tr>
                                        <th>Leave Type</th>
                                        <th>Total Allocated</th>
                                        <th>Used</th>
                                        <th>Remaining</th>
                                        <th>Assigned Date</th>
                                        <th>Assignment Type</th>
                                        <th>Allocation Period</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subData['quotas'] as $q)
                                    <tr>
                                        <td><strong>{{ $q['leave_type'] }}</strong></td>
                                        <td>{{ $q['total_allocated'] }}</td>
                                        <td>{{ $q['used'] }}</td>
                                        <td>{{ $q['remaining'] }}</td>
                                        <td>{{ $q['assigned_date'] }}</td>
                                        <td><span class="ej-badge {{ $q['assignment_type'] === 'department' ? 'in-progress' : 'completed' }}">{{ ucfirst($q['assignment_type'] ?? 'Employee') }}</span></td>
                                        <td>{{ $q['allocation_period'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="ej-empty"><i class="fas fa-calendar-alt"></i><p>{{ $subData['message'] ?? 'No leave quota assigned yet.' }}</p></div>
                        @endif

                        {{-- F. REIMBURSEMENT POLICY --}}
                        @elseif($sub['key'] === 'reimbursement_policy')
                        @if(!empty($subData['policy']))
                        <div class="ej-info-grid mb-3">
                            <div class="ej-info-chip"><span class="label">Policy</span><span class="value">{{ $subData['policy']['name'] ?? 'N/A' }}</span></div>
                            <div class="ej-info-chip"><span class="label">Submission Deadline</span><span class="value">{{ $subData['policy']['submission_deadline'] ?? 'N/A' }}</span></div>
                            <div class="ej-info-chip"><span class="label">Approval Process</span><span class="value">{{ $subData['policy']['approval_process'] ?? 'N/A' }}</span></div>
                        </div>
                        @if(!empty($subData['policy']['categories']))
                        <div class="ej-table-wrap">
                            <table class="ej-table">
                                <thead>
                                    <tr><th>Category</th><th>Limit</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($subData['policy']['categories'] as $cat => $limit)
                                    <tr><td>{{ $cat }}</td><td>{{ $limit }}</td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                        @else
                        <div class="ej-empty"><i class="fas fa-receipt"></i><p>No reimbursement policy available.</p></div>
                        @endif
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- DOCUMENTS TAB for Joining -->
            <div class="ej-tab-content" data-stage="joining" data-tab="documents">
                @php
                $joiningDocs = $documents['Joining'] ?? [];
                @endphp
                @if(count($joiningDocs) > 0)
                <div class="ej-doc-grid">
                    @foreach($joiningDocs as $doc)
                    <div class="ej-doc-card" data-template-id="{{ $doc->id }}">
                        <div class="doc-top">
                            <div class="doc-icon text-{{ $doc->color ?? 'secondary' }}">
                                <i class="{{ $doc->icon ?? 'fas fa-file' }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="doc-name">{{ $doc->name }}</div>
                                <div class="doc-meta">
                                    <i class="fas fa-calendar-alt"></i> {{ $doc->date }}
                                    <span class="mx-1">|</span>
                                    <i class="fas fa-file"></i> {{ $doc->type }}
                                    @if(isset($doc->size) && $doc->size != '---')
                                    <span class="mx-1">|</span>
                                    <i class="fas fa-weight"></i> {{ $doc->size }}
                                    @endif
                                </div>

                                <!-- Document Reference IDs -->
                                <div class="ej-doc-refs">
                                    @if(isset($doc->generated_letter_id) && $doc->generated_letter_id)
                                    <span class="ej-badge info">
                                        <i class="fas fa-hashtag"></i> ID: {{ $doc->generated_letter_id }}
                                    </span>
                                    @endif
                                    @if(isset($doc->reference_id) && $doc->reference_id)
                                    <span class="ej-badge pending">
                                        <i class="fas fa-tag"></i> Ref: {{ $doc->reference_id }}
                                    </span>
                                    @endif
                                    @if(isset($doc->document_request_id) && $doc->document_request_id && isset($doc->status) && $doc->status != 'generated')
                                    <span class="ej-badge in-progress">
                                        <i class="fas fa-clock"></i> Req #{{ $doc->document_request_id }}
                                    </span>
                                    @endif
                                </div>

                                @if(isset($doc->status))
                                <div class="doc-status mt-1">
                                    <span class="ej-badge {{ $doc->status == 'generated' ? 'completed' : ($doc->status == 'pending' ? 'pending' : ($doc->status == 'approved' ? 'assigned' : ($doc->status == 'rejected' ? 'not-assigned' : 'pending'))) }}">
                                        {{ $doc->status == 'pending' ? 'In Process' : ucfirst($doc->status) }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="doc-actions">
                            @if(isset($doc->status) && $doc->status == 'generated')
                                @if(isset($doc->file_path) && ($doc->file_exists ?? false))
                                <button class="btn btn-success btn-sm" onclick="viewDocument('{{ $doc->file_path }}', '{{ addslashes($doc->name) }}')">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="downloadDocument('{{ $doc->file_path }}', '{{ addslashes($doc->name) }}')">
                                    <i class="fas fa-download"></i> Download
                                </button>
                                @else
                                <span class="text-muted small"><i class="fas fa-exclamation-triangle text-warning"></i> File not found</span>
                                @endif
                            @elseif(isset($doc->status) && in_array($doc->status, ['pending', 'approved', 'processing']))
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="fas fa-clock"></i> {{ $doc->status == 'pending' ? 'In Process' : ucfirst($doc->status) }}
                                </button>
                                @if($doc->status == 'pending' && isset($doc->document_request_id))
                                <button class="btn btn-outline-danger btn-sm" onclick="cancelRequest('{{ $doc->document_request_id }}', '{{ $employee->employee_id }}')">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                                @endif
                            @else
                                <button class="btn btn-warning btn-sm text-dark" onclick="openRequestModal(
                                    '{{ $doc->id }}',
                                    '{{ $employee->employee_id }}',
                                    '{{ addslashes($doc->name) }}',
                                    '{{ $doc->type ?? 'Document' }}'
                                )">
                                    <i class="fas fa-paper-plane"></i> Request
                                </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="ej-empty">
                    <i class="fas fa-inbox"></i>
                    <p>No joining documents available.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- STAGE 3: ASSET ALLOCATION -->
    <!-- ============================================ -->
    <div class="ej-stage-card" id="stage-asset_allocation">
        <div class="ej-stage-header" onclick="ejToggleStage('ejBody-asset_allocation')">
            <div class="ej-stage-title">
                <span class="ej-stage-number warning">3</span>
                <i class="fas fa-laptop"></i>
                Asset Allocation
            </div>
            <div class="ej-stage-right">
                <span class="ej-badge pending">Pending</span>
                <i class="fas fa-chevron-down text-muted"></i>
            </div>
        </div>

        <div class="ej-stage-body ej-hidden" id="ejBody-asset_allocation" style="display:none;">
            @php $assetData = $assetAllocationData ?? []; @endphp

            @if(!empty($assetData['has_assets']) && !empty($assetData['allocations']) && count($assetData['allocations']) > 0)
            <div class="ej-table-wrap">
                <table class="ej-table">
                    <thead>
                        <tr>
                            <th>Asset Name</th>
                            <th>Type</th>
                            <th>Serial Number</th>
                            <th>Allocation Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assetData['allocations'] as $a)
                        <tr>
                            <td>{{ $a->asset->name ?? 'N/A' }}</td>
                            <td>{{ $a->asset->type ?? 'N/A' }}</td>
                            <td>{{ $a->asset->serial_number ?? 'N/A' }}</td>
                            <td>{{ $a->created_at ? Carbon\Carbon::parse($a->created_at)->format('d M, Y') : 'N/A' }}</td>
                            <td><span class="ej-badge completed">{{ ucfirst($a->status ?? 'Active') }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="ej-asset-alert">
                <i class="fas fa-laptop"></i>
                <div>
                    <div class="ej-asset-alert-title">No Assets Assigned Yet</div>
                    <div class="ej-asset-alert-text">{{ $assetData['message'] ?? 'No assets have been allocated to you yet.' }}</div>
                </div>
            </div>

            @if(!empty($assetData['overview']))
            <div class="ej-asset-section">
                <div class="ej-asset-section-title"><i class="fas fa-info-circle"></i> Overview</div>
                <div class="ej-info-grid">
                    @foreach($assetData['overview'] as $label => $value)
                    <div class="ej-info-chip"><span class="label">{{ $label }}</span><span class="value">{{ $value }}</span></div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(!empty($assetData['eligible_assets']))
            <div class="ej-asset-section">
                <div class="ej-asset-section-title"><i class="fas fa-laptop"></i> Eligible Assets</div>
                <div class="ej-eligible-grid">
                    @foreach($assetData['eligible_assets'] as $asset)
                    <div class="ej-eligible-item"><i class="fas fa-check-circle"></i><span>{{ $asset }}</span></div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(!empty($assetData['allocation_process']))
            <div class="ej-asset-section">
                <div class="ej-asset-section-title"><i class="fas fa-project-diagram"></i> Allocation Process</div>
                <div class="ej-process-list">
                    @foreach($assetData['allocation_process'] as $index => $step)
                    <div class="ej-process-step"><span class="ej-process-num">{{ $index + 1 }}</span><span>{{ $step }}</span></div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(!empty($assetData['policies']))
            <div class="ej-asset-section">
                <div class="ej-asset-section-title"><i class="fas fa-shield-alt"></i> Asset Policies</div>
                <div class="ej-policy-list">
                    @foreach($assetData['policies'] as $policy)
                    <div class="policy-row"><i class="fas fa-check text-success"></i><span>{{ $policy }}</span></div>
                    @endforeach
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>

    <!-- ============================================ -->
    <!-- STAGE 4: PROMOTIONS -->
    <!-- ============================================ -->
    <div class="ej-stage-card" id="stage-promotions">
        <div class="ej-stage-header" onclick="ejToggleStage('ejBody-promotions')">
            <div class="ej-stage-title">
                <span class="ej-stage-number success">4</span>
                <i class="fas fa-arrow-up"></i>
                Promotions
            </div>
            <div class="ej-stage-right">
                <span class="ej-badge {{ !empty($promotionData['promotions']) ? 'completed' : 'pending' }}">
                    {{ !empty($promotionData['promotions']) ? 'Completed' : 'Pending' }}
                </span>
                <i class="fas fa-chevron-down text-muted"></i>
            </div>
        </div>

        <div class="ej-stage-body ej-hidden" id="ejBody-promotions" style="display:none;">
            <div class="ej-tabs">
                <button class="ej-tab active" data-stage="promotions" data-tab="history">
                    <i class="fas fa-history"></i> Promotion History
                </button>
                <button class="ej-tab" data-stage="promotions" data-tab="documents">
                    <i class="fas fa-file-alt"></i> Documents
                </button>
            </div>

            <div class="ej-tab-content active" data-stage="promotions" data-tab="history">
                @php $promoData = $promotionData ?? []; @endphp
                @if(!empty($promoData['promotions']) && count($promoData['promotions']) > 0)
                <div class="ej-table-wrap">
                    <table class="ej-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Promotion ID</th>
                                <th>Type</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Performed By</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($promoData['promotions'] as $p)
                            <tr>
                                <td><span class="ej-badge in-progress">{{ $p['id'] }}</span></td>
                                <td><span class="ej-badge in-progress">{{ $p['promotion_id'] }}</span></td>
                                <td>
                                    <i class="{{ $p['icon'] ?? 'fas fa-edit' }} me-1" style="color: var(--ej-{{ $p['color'] ?? 'info' }}, #0891b2);"></i>
                                    {{ $p['type'] }}
                                </td>
                                <td><span class="text-muted"><i class="fas fa-arrow-right text-danger me-1" style="font-size:10px;"></i>{{ $p['from'] }}</span></td>
                                <td><strong class="text-success"><i class="fas fa-arrow-right text-success me-1" style="font-size:10px;"></i>{{ $p['to'] }}</strong></td>
                                <td>{{ $p['performed_by'] }}</td>
                                <td>{{ $p['date'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="ej-empty"><i class="fas fa-inbox"></i><p>{{ $promoData['message'] ?? 'No promotions recorded yet.' }}</p></div>
                @endif
            </div>

            <div class="ej-tab-content" data-stage="promotions" data-tab="documents">
                @php $promoDocs = $documents['Promotion and Performance'] ?? []; @endphp
                @if(count($promoDocs) > 0)
                <div class="ej-doc-grid">
                    @foreach($promoDocs as $doc)
                    <div class="ej-doc-card" data-template-id="{{ $doc->id }}">
                        <div class="doc-top">
                            <div class="doc-icon text-{{ $doc->color ?? 'secondary' }}">
                                <i class="{{ $doc->icon ?? 'fas fa-file' }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="doc-name">{{ $doc->name }}</div>
                                <div class="doc-meta">
                                    <i class="fas fa-calendar-alt"></i> {{ $doc->date }}
                                    <span class="mx-1">|</span>
                                    <i class="fas fa-file"></i> {{ $doc->type }}
                                    @if(isset($doc->size) && $doc->size != '---')
                                    <span class="mx-1">|</span>
                                    <i class="fas fa-weight"></i> {{ $doc->size }}
                                    @endif
                                </div>

                                <!-- Document Reference IDs -->
                                <div class="ej-doc-refs">
                                    @if(isset($doc->generated_letter_id) && $doc->generated_letter_id)
                                    <span class="ej-badge info">
                                        <i class="fas fa-hashtag"></i> ID: {{ $doc->generated_letter_id }}
                                    </span>
                                    @endif
                                    @if(isset($doc->reference_id) && $doc->reference_id)
                                    <span class="ej-badge pending">
                                        <i class="fas fa-tag"></i> Ref: {{ $doc->reference_id }}
                                    </span>
                                    @endif
                                    @if(isset($doc->document_request_id) && $doc->document_request_id && isset($doc->status) && $doc->status != 'generated')
                                    <span class="ej-badge in-progress">
                                        <i class="fas fa-clock"></i> Req #{{ $doc->document_request_id }}
                                    </span>
                                    @endif
                                </div>

                                @if(isset($doc->status))
                                <div class="doc-status mt-1">
                                    <span class="ej-badge {{ $doc->status == 'generated' ? 'completed' : ($doc->status == 'pending' ? 'pending' : ($doc->status == 'approved' ? 'assigned' : ($doc->status == 'rejected' ? 'not-assigned' : 'pending'))) }}">
                                        {{ $doc->status == 'pending' ? 'In Process' : ucfirst($doc->status) }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="doc-actions">
                            @if(isset($doc->status) && $doc->status == 'generated')
                                @if(isset($doc->file_path) && ($doc->file_exists ?? false))
                                <button class="btn btn-success btn-sm" onclick="viewDocument('{{ $doc->file_path }}', '{{ addslashes($doc->name) }}')">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="downloadDocument('{{ $doc->file_path }}', '{{ addslashes($doc->name) }}')">
                                    <i class="fas fa-download"></i> Download
                                </button>
                                @else
                                <span class="text-muted small"><i class="fas fa-exclamation-triangle text-warning"></i> File not found</span>
                                @endif
                            @elseif(isset($doc->status) && in_array($doc->status, ['pending', 'approved', 'processing']))
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="fas fa-clock"></i> {{ $doc->status == 'pending' ? 'In Process' : ucfirst($doc->status) }}
                                </button>
                                @if($doc->status == 'pending' && isset($doc->document_request_id))
                                <button class="btn btn-outline-danger btn-sm" onclick="cancelRequest('{{ $doc->document_request_id }}', '{{ $employee->employee_id }}')">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                                @endif
                            @else
                                <button class="btn btn-warning btn-sm text-dark" onclick="openRequestModal(
                                    '{{ $doc->id }}',
                                    '{{ $employee->employee_id }}',
                                    '{{ addslashes($doc->name) }}',
                                    '{{ $doc->type ?? 'Document' }}'
                                )">
                                    <i class="fas fa-paper-plane"></i> Request
                                </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="ej-empty"><i class="fas fa-inbox"></i><p>No promotion documents available.</p></div>
                @endif
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- STAGE 5: EXIT -->
    <!-- ============================================ -->
    <div class="ej-stage-card" id="stage-exit">
        <div class="ej-stage-header" onclick="ejToggleStage('ejBody-exit')">
            <div class="ej-stage-title">
                <span class="ej-stage-number danger">5</span>
                <i class="fas fa-sign-out-alt"></i>
                Exit
                @if($employee->exit_date)
                <span class="ej-badge pending"><i class="fas fa-calendar-alt me-1"></i>{{ Carbon\Carbon::parse($employee->exit_date)->format('d M, Y') }}</span>
                @endif
            </div>
            <div class="ej-stage-right">
                <span class="ej-badge {{ $employee->status === 'inactive' ? 'completed' : 'pending' }}">
                    {{ $employee->status === 'inactive' ? 'Completed' : 'Pending' }}
                </span>
                <i class="fas fa-chevron-down text-muted"></i>
            </div>
        </div>

        <div class="ej-stage-body ej-hidden" id="ejBody-exit" style="display:none;">
            @php $exitData = $exitDetails ?? []; @endphp

            @if(!empty($exitData['stages']))
            <div class="ej-exit-list">
                @foreach($exitData['stages'] as $es)
                @php
                $esStatus = $es['status'] ?? 'pending';
                $esClass = $esStatus === 'completed' ? 'completed' : ($esStatus === 'in-progress' ? 'in-progress' : '');
                @endphp
                <div class="ej-exit-item {{ $esClass }}">
                    <div class="exit-icon"><i class="{{ $es['icon'] ?? 'fas fa-circle' }}"></i></div>
                    <div class="flex-grow-1">
                        <div class="exit-title">{{ $es['title'] ?? 'Stage' }}</div>
                        @if(!empty($es['description']))
                        <div class="exit-desc">{{ $es['description'] }}</div>
                        @endif
                        @if(!empty($es['date']))
                        <div class="exit-date"><i class="fas fa-calendar-alt me-1"></i>{{ $es['date'] }}</div>
                        @endif
                    </div>
                    <span class="ej-badge {{ $esClass ?: 'pending' }}">{{ $es['status_label'] ?? ucfirst($esStatus) }}</span>
                </div>
                @endforeach
            </div>
            @else
            <div class="ej-empty"><i class="fas fa-inbox"></i><p>No exit journey data available.</p></div>
            @endif
        </div>
    </div>

</div>

<!-- Document Request Modal -->
<div class="modal fade ej-modal" id="documentRequestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-alt me-2"></i> Request Document</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="ej-doc-icon-large"><i class="fas fa-file-pdf"></i></div>
                    <h4 class="ej-doc-name-large" id="modalDocumentName">Document Name</h4>
                    <span class="ej-doc-type-badge bg-info text-white" id="modalDocumentType">Document</span>
                </div>
                <form id="documentRequestForm">
                    @csrf
                    <input type="hidden" name="template_id" id="modalTemplateId">
                    <input type="hidden" name="employee_id" id="modalEmployeeId">
                    <div class="mb-3">
                        <label for="request_reason" class="form-label"><i class="fas fa-comment me-1"></i> Reason for Request <span class="text-muted">(Optional)</span></label>
                        <textarea class="form-control" id="request_reason" name="request_reason" rows="3" placeholder="Please provide any additional information..."></textarea>
                    </div>
                    <div class="alert alert-info"><i class="fas fa-info-circle me-2"></i>Once submitted, your request will be sent to HR for approval.</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i> Cancel</button>
                <button type="button" class="btn btn-primary" id="submitRequestBtn"><i class="fas fa-paper-plane me-1"></i> Submit Request</button>
            </div>
        </div>
    </div>
</div>

<!-- Document View Modal -->
<div class="modal fade ej-modal" id="documentViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-alt me-2"></i><span id="documentViewTitle">Document</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="documentViewContainer">
                <div class="text-center p-5" id="documentLoading">
                    <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
                    <p class="mt-3 text-muted">Loading document...</p>
                </div>
                <div id="documentContent" style="display: none; min-height: 400px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i> Close</button>
                <button type="button" class="btn btn-success" id="downloadFromViewBtn" onclick="downloadCurrentDocument()"><i class="fas fa-download me-1"></i> Download</button>
                <button type="button" class="btn btn-primary" onclick="printCurrentDocument()"><i class="fas fa-print me-1"></i> Print</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ============================================
// EMPLOYEE JOURNEY - COMPLETE JAVASCRIPT
// ============================================

let currentRequest = {
    templateId: null,
    employeeId: null,
    documentName: null
};

let currentDocumentData = {
    filePath: null,
    fileName: null,
    documentName: null,
    fileType: null
};

// STAGE TOGGLE
function ejToggleStage(id) {
    var el = document.getElementById(id);
    if (!el) return;
    if (el.style.display === 'none') {
        el.style.display = '';
        el.classList.remove('ej-hidden');
    } else {
        el.style.display = 'none';
        el.classList.add('ej-hidden');
    }
}

// SUB-STEP TOGGLE
function ejToggleSub(id, headerEl) {
    var el = document.getElementById(id);
    if (!el) return;
    el.classList.toggle('ej-hidden');
    var chevron = headerEl.querySelector('.ej-substep-chevron');
    if (chevron) chevron.style.transform = el.classList.contains('ej-hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
}

// TAB SWITCHING
$(document).ready(function() {
    $('.ej-tab').on('click', function() {
        var stage = $(this).data('stage');
        var tab = $(this).data('tab');
        var $stageBody = $(this).closest('.ej-stage-body');

        $stageBody.find('.ej-tab').removeClass('active');
        $(this).addClass('active');

        $stageBody.find('.ej-tab-content').removeClass('active');
        $stageBody.find('.ej-tab-content[data-stage="' + stage + '"][data-tab="' + tab + '"]').addClass('active');
    });
});

// DOCUMENT REQUEST FUNCTIONS
function openRequestModal(templateId, employeeId, documentName, documentType) {
    currentRequest.templateId = templateId;
    currentRequest.employeeId = employeeId;
    currentRequest.documentName = documentName;

    document.getElementById('modalTemplateId').value = templateId;
    document.getElementById('modalEmployeeId').value = employeeId;
    document.getElementById('modalDocumentName').textContent = documentName || 'Document';
    document.getElementById('modalDocumentType').textContent = documentType || 'Document';
    document.getElementById('request_reason').value = '';

    const modal = new bootstrap.Modal(document.getElementById('documentRequestModal'));
    modal.show();
}

function submitDocumentRequest() {
    const templateId = document.getElementById('modalTemplateId').value;
    const employeeId = document.getElementById('modalEmployeeId').value;
    const reason = document.getElementById('request_reason').value.trim();

    const submitBtn = document.getElementById('submitRequestBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Submitting...';

    $.ajax({
        url: '{{ route("employee.document.request.store") }}',
        type: 'POST',
        data: {
            template_id: templateId,
            employee_id: employeeId,
            request_reason: reason || null,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                bootstrap.Modal.getInstance(document.getElementById('documentRequestModal')).hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Request Submitted!',
                    text: 'Your document request has been submitted successfully.',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
                setTimeout(() => location.reload(), 3000);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Request Failed',
                    text: response.message || 'Failed to submit request.'
                });
            }
        },
        error: function(xhr) {
            let errorMessage = 'An error occurred. Please try again.';
            if (xhr.responseJSON?.message) errorMessage = xhr.responseJSON.message;
            Swal.fire({
                icon: 'error',
                title: 'Request Failed',
                text: errorMessage
            });
        },
        complete: function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
}

function cancelRequest(requestId, employeeId) {
    Swal.fire({
        title: 'Cancel Request?',
        text: 'Are you sure you want to cancel this document request?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-times me-1"></i> Yes, Cancel Request',
        cancelButtonText: '<i class="fas fa-arrow-left me-1"></i> No, Keep It'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/employee/document-requests/' + requestId + '/cancel',
                type: 'PUT',
                data: {
                    employee_id: employeeId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Request Cancelled',
                            text: 'Your document request has been cancelled successfully.',
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });
                        setTimeout(() => location.reload(), 2000);
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cancellation Failed',
                        text: xhr.responseJSON?.message || 'Failed to cancel request.'
                    });
                }
            });
        }
    });
}

// DOCUMENT VIEW FUNCTIONS
function viewDocument(filePath, documentName) {
    if (!filePath) {
        Swal.fire({
            icon: 'error',
            title: 'Document Not Found',
            text: 'The document file could not be found.'
        });
        return;
    }

    document.getElementById('documentViewTitle').textContent = documentName || 'Document';
    currentDocumentData.filePath = filePath;
    currentDocumentData.documentName = documentName || 'Document';
    currentDocumentData.fileName = filePath.split('/').pop() || 'document';

    document.getElementById('documentLoading').style.display = 'flex';
    document.getElementById('documentContent').style.display = 'none';

    const fileExtension = filePath.split('.').pop().toLowerCase();
    currentDocumentData.fileType = fileExtension;
    const fileUrl = '{{ url("/image") }}/' + filePath;

    const container = document.getElementById('documentContent');

    if (['jpg','jpeg','png','gif','bmp','webp','svg'].includes(fileExtension)) {
        container.innerHTML = `
            <div style="padding:20px;text-align:center;background:#f8fafc;min-height:400px;display:flex;align-items:center;justify-content:center;">
                <img src="${fileUrl}" alt="${documentName}" style="max-width:100%;max-height:70vh;border-radius:4px;box-shadow:0 2px 10px rgba(0,0,0,0.1);" onerror="this.style.display='none';this.parentElement.innerHTML='<div style=\\'text-align:center;padding:40px;\\'><i class=\\'fas fa-image\\' style=\\'font-size:60px;color:#dc3545;\\'></i><h5 class=\\'mt-3 text-danger\\'>Image Failed to Load</h5><button class=\\'btn btn-success mt-3\\' onclick=\\'downloadCurrentDocument()\\'><i class=\\'fas fa-download me-1\\'></i> Download</button></div>'">
            </div>
        `;
    } else if (fileExtension === 'pdf') {
        container.innerHTML = `
            <div style="padding:0;height:75vh;background:#f8fafc;">
                <object data="${fileUrl}" type="application/pdf" style="width:100%;height:100%;border:none;">
                    <div style="text-align:center;padding:40px;">
                        <i class="fas fa-file-pdf" style="font-size:60px;color:#dc3545;"></i>
                        <h5 class="mt-3">PDF Viewer Unavailable</h5>
                        <button class="btn btn-success mt-3" onclick="downloadCurrentDocument()"><i class="fas fa-download me-1"></i> Download</button>
                    </div>
                </object>
            </div>
        `;
    } else {
        container.innerHTML = `
            <div style="padding:40px;text-align:center;background:#f8fafc;min-height:400px;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                <i class="fas fa-file" style="font-size:60px;color:#6c757d;"></i>
                <h5 class="mt-3">${documentName}</h5>
                <p class="text-muted">This file type cannot be previewed directly.</p>
                <div class="mt-3">
                    <button class="btn btn-success me-2" onclick="downloadCurrentDocument()"><i class="fas fa-download me-1"></i> Download</button>
                    <button class="btn btn-primary" onclick="window.open('${fileUrl}', '_blank')"><i class="fas fa-external-link-alt me-1"></i> Open in New Tab</button>
                </div>
            </div>
        `;
    }

    document.getElementById('documentLoading').style.display = 'none';
    document.getElementById('documentContent').style.display = 'block';

    new bootstrap.Modal(document.getElementById('documentViewModal')).show();
}

function downloadDocument(filePath, documentName) {
    if (!filePath) {
        Swal.fire({
            icon: 'error',
            title: 'Download Failed',
            text: 'Document file path is missing.'
        });
        return;
    }

    const downloadUrl = '{{ url("/image") }}/' + filePath;
    const link = document.createElement('a');
    link.href = downloadUrl;
    link.download = documentName || filePath.split('/').pop() || 'document';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function downloadCurrentDocument() {
    if (currentDocumentData.filePath) {
        downloadDocument(currentDocumentData.filePath, currentDocumentData.documentName);
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Download Failed',
            text: 'No document is currently selected.'
        });
    }
}

function printCurrentDocument() {
    const filePath = currentDocumentData.filePath;
    if (!filePath) {
        Swal.fire({
            icon: 'error',
            title: 'Print Failed',
            text: 'No document is currently selected.'
        });
        return;
    }

    const fileUrl = '{{ url("/image") }}/' + filePath;
    window.open(fileUrl, '_blank');

    Swal.fire({
        icon: 'info',
        title: 'Document Opened',
        text: 'The document has been opened in a new tab. You can print it from there.',
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false
    });
}

// EVENT HANDLERS
document.getElementById('submitRequestBtn')?.addEventListener('click', function() {
    const templateId = document.getElementById('modalTemplateId').value;
    const employeeId = document.getElementById('modalEmployeeId').value;

    if (!templateId || !employeeId) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Missing required information.'
        });
        return;
    }

    Swal.fire({
        title: 'Confirm Request',
        text: 'Are you sure you want to submit this document request?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check me-1"></i> Yes, Submit'
    }).then((result) => {
        if (result.isConfirmed) {
            submitDocumentRequest();
        }
    });
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = bootstrap.Modal.getInstance(document.getElementById('documentRequestModal'));
        if (modal) modal.hide();
        const viewModal = bootstrap.Modal.getInstance(document.getElementById('documentViewModal'));
        if (viewModal) viewModal.hide();
    }
});

// EXPOSE FUNCTIONS
window.ejToggleStage = ejToggleStage;
window.ejToggleSub = ejToggleSub;
window.openRequestModal = openRequestModal;
window.viewDocument = viewDocument;
window.downloadDocument = downloadDocument;
window.downloadCurrentDocument = downloadCurrentDocument;
window.printCurrentDocument = printCurrentDocument;
window.cancelRequest = cancelRequest;
window.submitDocumentRequest = submitDocumentRequest;
</script>
@endsection