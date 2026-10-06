@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'Exit Journey')

@section('content')

<style>
/* ============================================
   EMPLOYEE EXIT JOURNEY - ALL STEPS VISIBLE
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

.ej-profile-card {
    background: #fff;
    border: 1px solid var(--ej-border);
    border-radius: 1rem;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 20px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
}

.ej-profile-card .ej-avatar {
    width: 88px;
    height: 88px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--ej-primary-light);
    flex-shrink: 0;
}

.ej-profile-card .ej-avatar-fallback {
    width: 45px;
    height: 50px;
    border-radius: 50%;
    background: var(--ej-primary);
    color: #fff;
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
    color: var(--ej-text);
    margin-bottom: 6px;
}

.ej-profile-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 20px;
    font-size: 13px;
    color: var(--ej-text-muted);
}

.ej-profile-meta span strong {
    color: var(--ej-text);
    font-weight: 600;
}

/* Summary Cards */
.ej-summary-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
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

.ej-summary-card.status .ej-summary-icon {
    background: var(--ej-warning-light);
    color: var(--ej-warning);
}

.ej-summary-card.policy .ej-summary-icon {
    background: var(--ej-info-light);
    color: var(--ej-info);
}

.ej-summary-card.notice .ej-summary-icon {
    background: var(--ej-success-light);
    color: var(--ej-success);
}

.ej-summary-card.days .ej-summary-icon {
    background: var(--ej-primary-light);
    color: var(--ej-primary);
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

/* Stage Cards */
.ej-stage-card {
    background: #fff;
    border: 1px solid var(--ej-border);
    border-radius: 1rem;
    margin-bottom: 18px;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    transition: opacity 0.3s ease;
}

.ej-stage-card.locked {
    opacity: 0.7;
}

.ej-stage-card.locked .ej-stage-header {
    cursor: not-allowed;
}

.ej-stage-header {
    padding: 16px 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    background: var(--ej-bg-soft);
    border-bottom: 1px solid var(--ej-border);
}

.ej-stage-card.locked .ej-stage-header {
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

.ej-stage-number.primary {
    background: var(--ej-primary);
}

.ej-stage-number.info {
    background: var(--ej-info);
}

.ej-stage-number.warning {
    background: var(--ej-warning);
}

.ej-stage-number.success {
    background: var(--ej-success);
}

.ej-stage-number.danger {
    background: var(--ej-danger);
}

.ej-stage-number.completed {
    background: var(--ej-success);
}

.ej-stage-number.locked {
    background: #94a3b8;
}

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

.ej-badge.action-required {
    background: var(--ej-warning-light);
    color: var(--ej-warning);
}

.ej-badge.locked {
    background: #e2e8f0;
    color: #94a3b8;
}

.ej-stage-body {
    padding: 22px;
}

/* Info Grid */
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
    transition: box-shadow .2s ease, transform .2s ease;
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

.ej-info-chip .value .department-name {
    display: inline-block;
    background: var(--ej-primary-light);
    color: var(--ej-primary);
    padding: 2px 12px;
    border-radius: 4px;
    font-weight: 600;
}

/* Empty State */
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

/* Action Box - For Submit Resignation */
.ej-action-box {
    background: var(--ej-warning-light);
    border: 2px solid #fde68a;
    border-radius: 0.75rem;
    padding: 30px 20px;
    margin-bottom: 20px;
    text-align: center;
}

.ej-action-box .ej-action-icon {
    color: var(--ej-warning);
    font-size: 32px;
    margin-bottom: 12px;
}

.ej-action-box .ej-action-title {
    font-size: 18px;
    font-weight: 700;
    color: #78350f;
    margin-bottom: 8px;
}

.ej-action-box .ej-action-text {
    font-weight: 500;
    color: #78350f;
    margin-bottom: 20px;
}

.ej-action-box .btn {
    min-width: 220px;
    padding: 5px 13px;
    font-size: 16px;
    font-weight: 600;
}

.ej-action-box .btn i {
    margin-right: 10px;
}

/* Locked Action Box */
.ej-action-box.locked {
    background: #f1f5f9;
    border-color: #e2e8f0;
}

.ej-action-box.locked .ej-action-icon {
    color: #94a3b8;
}

.ej-action-box.locked .ej-action-title {
    color: #64748b;
}

.ej-action-box.locked .ej-action-text {
    color: #64748b;
}

/* Timeline for Notice Period */
.timeline-modern {
    position: relative;
    padding-left: 28px;
}

.timeline-modern::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, var(--ej-primary), var(--ej-primary-light));
    border-radius: 2px;
}

.timeline-item {
    position: relative;
    padding: 8px 0 8px 20px;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -20px;
    top: 14px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--ej-primary);
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px var(--ej-primary);
}

.timeline-item.completed::before {
    background: var(--ej-success);
    box-shadow: 0 0 0 2px var(--ej-success);
}

.timeline-item .date {
    font-size: 0.8rem;
    color: var(--ej-text-muted);
    font-weight: 500;
}

.timeline-item .title {
    font-weight: 600;
    color: var(--ej-text);
    font-size: 0.9rem;
}

.timeline-item .sub-label {
    font-size: 0.7rem;
    color: var(--ej-text-muted);
}

/* Countdown */
.countdown-card {
    background: linear-gradient(135deg, #f8faff 0%, #eef2ff 100%);
    border: 1px solid var(--ej-border);
    border-radius: 0.75rem;
    padding: 20px;
    text-align: center;
}

.countdown-number {
    font-size: 2.8rem;
    font-weight: 800;
    line-height: 1;
    color: var(--ej-primary);
    letter-spacing: -1px;
}

.countdown-number.overdue {
    color: var(--ej-danger);
}

.countdown-label {
    font-size: 0.85rem;
    color: var(--ej-text-muted);
    margin-top: 4px;
}

.countdown-progress {
    margin-top: 12px;
}

.countdown-progress .progress-track {
    height: 4px;
    background: var(--ej-border);
    border-radius: 2px;
    overflow: hidden;
}

.countdown-progress .progress-bar {
    height: 100%;
    border-radius: 2px;
    background: linear-gradient(90deg, var(--ej-primary), var(--ej-primary-light));
    transition: width 0.6s ease;
}

.countdown-progress .progress-bar.overdue {
    background: linear-gradient(90deg, var(--ej-danger), #f87171);
}

.countdown-progress .progress-text {
    font-size: 0.7rem;
    color: var(--ej-text-muted);
    margin-top: 4px;
}

/* Approval Items */
.approval-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
}

.approval-item {
    background: var(--ej-bg-soft);
    border-radius: 0.75rem;
    padding: 12px 16px;
    border: 1px solid var(--ej-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.approval-item .name {
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--ej-text);
}

.approval-item .role {
    font-size: 0.7rem;
    color: var(--ej-text-muted);
}

/* Responsive */
@media (max-width: 900px) {
    .ej-summary-row {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 768px) {
    .ej-profile-card {
        flex-direction: column;
        text-align: center;
    }

    .ej-profile-meta {
        justify-content: center;
    }

    .ej-summary-row {
        grid-template-columns: 1fr;
    }

    .ej-info-grid {
        grid-template-columns: 1fr;
    }

    .approval-grid {
        grid-template-columns: 1fr;
    }

    .ej-action-box .btn {
        min-width: 100%;
        width: 100%;
    }
}

/* Animations */
@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(12px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-up {
    animation: fadeUp 0.4s ease forwards;
}

.delay-1 {
    animation-delay: 0.05s;
}

.delay-2 {
    animation-delay: 0.1s;
}

.delay-3 {
    animation-delay: 0.15s;
}

.delay-4 {
    animation-delay: 0.2s;
}

/* Pulse animation for action button */
@keyframes pulse {
    0% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.03);
    }

    100% {
        transform: scale(1);
    }
}

.btn-pulse {
    animation: pulse 2s infinite;
}

/* Bounce animation for icon */
@keyframes bounce {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-5px);
    }
}

.icon-bounce {
    animation: bounce 1.5s infinite;
}

/* ============================================
   ASSIGNMENT PERSON CARD - CLEAR & DETAILED
   ============================================ */
.ej-assignee-card {
    background: #ffffff;
    border: 2px solid var(--ej-primary);
    border-radius: 0.75rem;
    padding: 16px 20px;
    margin-top: 12px;
    box-shadow: 0 2px 8px rgba(79, 70, 229, 0.08);
    transition: all 0.3s ease;
}

.ej-assignee-card:hover {
    box-shadow: 0 4px 16px rgba(79, 70, 229, 0.15);
    transform: translateY(-2px);
}

.ej-assignee-card .assignee-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
}

.ej-assignee-card .assignee-header .assignee-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: var(--ej-text-muted);
    font-weight: 700;
    background: var(--ej-primary-light);
    padding: 2px 12px;
    border-radius: 12px;
    color: var(--ej-primary);
}

.ej-assignee-card .assignee-main {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.ej-assignee-card .assignee-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--ej-primary), #7c3aed);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 700;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
}

.ej-assignee-card .assignee-info {
    flex: 1;
    min-width: 200px;
}

.ej-assignee-card .assignee-info .assignee-name {
    font-weight: 700;
    color: var(--ej-text);
    font-size: 1.05rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.ej-assignee-card .assignee-info .assignee-name .assignee-badge {
    font-size: 0.6rem;
    font-weight: 600;
    padding: 2px 10px;
    border-radius: 12px;
    background: var(--ej-success-light);
    color: var(--ej-success);
}

.ej-assignee-card .assignee-info .assignee-details {
    display: flex;
    flex-wrap: wrap;
    gap: 6px 16px;
    margin-top: 4px;
}

.ej-assignee-card .assignee-info .assignee-details .detail-item {
    font-size: 0.8rem;
    color: var(--ej-text-muted);
    display: flex;
    align-items: center;
    gap: 4px;
}

.ej-assignee-card .assignee-info .assignee-details .detail-item i {
    color: var(--ej-primary);
    font-size: 0.7rem;
    width: 14px;
}

.ej-assignee-card .assignee-status-wrap {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 4px;
}

.ej-assignee-card .assignee-status-wrap .assignee-status {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 4px 16px;
    border-radius: 20px;
    white-space: nowrap;
}

.ej-assignee-card .assignee-status-wrap .assignee-status.pending {
    background: var(--ej-warning-light);
    color: var(--ej-warning);
}

.ej-assignee-card .assignee-status-wrap .assignee-status.in-progress {
    background: var(--ej-primary-light);
    color: var(--ej-primary);
}

.ej-assignee-card .assignee-status-wrap .assignee-status.completed {
    background: var(--ej-success-light);
    color: var(--ej-success);
}

.ej-assignee-card .assignee-status-wrap .assignee-status.not-assigned {
    background: #f1f5f9;
    color: #94a3b8;
}

.ej-assignee-card .assignee-instructions {
    margin-top: 12px;
    padding: 10px 14px;
    background: var(--ej-bg-soft);
    border-radius: 0.5rem;
    border-left: 3px solid var(--ej-primary);
    font-size: 0.85rem;
    color: var(--ej-text);
}

.ej-assignee-card .assignee-instructions i {
    color: var(--ej-primary);
    margin-right: 6px;
}

.ej-assignee-card .assignee-deadline-box {
    margin-top: 8px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: var(--ej-danger);
    background: var(--ej-danger-light);
    padding: 4px 14px;
    border-radius: 12px;
    font-weight: 600;
}

.ej-assignee-card .assignee-deadline-box i {
    color: var(--ej-danger);
}

/* Not Assigned State */
.ej-assignee-card.not-assigned {
    border-color: #e2e8f0;
    background: #fafafa;
}

.ej-assignee-card.not-assigned .assignee-avatar {
    background: #94a3b8;
    box-shadow: none;
}

.ej-assignee-card.not-assigned .assignee-name {
    color: var(--ej-text-muted);
}

.ej-assignee-card.not-assigned .assignee-instructions {
    border-left-color: #94a3b8;
    background: #f1f5f9;
}

/* Contact Link */
.ej-assignee-card .assignee-contact {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.75rem;
    color: var(--ej-primary);
    text-decoration: none;
    font-weight: 500;
    margin-top: 4px;
}

.ej-assignee-card .assignee-contact:hover {
    text-decoration: underline;
    color: var(--ej-primary-dark);
}

/* Responsive */
@media (max-width: 768px) {
    .ej-assignee-card .assignee-main {
        flex-direction: column;
        align-items: flex-start;
    }

    .ej-assignee-card .assignee-status-wrap {
        align-items: flex-start;
        width: 100%;
        margin-top: 8px;
    }

    .ej-assignee-card .assignee-info .assignee-details {
        flex-direction: column;
        gap: 2px;
    }
}
</style>

<div class="container-fluid">

    <!-- Page Header -->
    <div class="ej-page-header">
        <div>
            <h3 class="mb-0"><i class="fas fa-door-open text-primary"></i> Exit Process</h3>
            <p class="text-muted small mb-0">Complete exit process overview - track your progress</p>
        </div>
        @if(!$hasActiveExit && $hasPolicy)
        <a href="{{ route('employee.exit.resignation.form') }}" class="btn btn-primary">
            <i class="fas fa-pen me-2"></i> Submit Resignation
        </a>
        @endif
    </div>

    <!-- Profile Card -->
    <div class="ej-profile-card fade-up">
        @if($employee->profile_photo)
        <img src="{{ asset('image/' . $employee->profile_photo) }}" alt="{{ $employee->name }}" class="ej-avatar">
        @else
        <div class="ej-avatar-fallback">{{ strtoupper(substr($employee->name, 0, 1)) }}</div>
        @endif
        <div>
            <div class="ej-name">{{ $employee->name }}
                @if($hasActiveExit)
                @if($isOnHold)
                <span class="ej-badge in-progress"><i class="fas fa-pause-circle me-1"></i> ON HOLD</span>
                @elseif($isNoticePeriod)
                <span class="ej-badge in-progress"><i class="fas fa-hourglass-half me-1"></i> NOTICE PERIOD</span>
                @elseif($isExited)
                <span class="ej-badge completed"><i class="fas fa-check-circle me-1"></i> EXITED</span>
                @elseif($isApproved)
                <span class="ej-badge completed"><i class="fas fa-check-circle me-1"></i> APPROVED</span>
                @endif
                @else
                <span class="ej-badge pending"><i class="fas fa-clock me-1"></i> NOT STARTED</span>
                @endif
            </div>
            <div class="ej-profile-meta">
                <span><strong>Code:</strong> {{ $employee->employee_code }}</span>
                <span><strong>Designation:</strong> {{ $employee->designation }}</span>
                <span><strong>Department:</strong>
                    @if($employee->department)
                    {{ $employee->department->name }}
                    @else
                    <span class="text-muted">N/A</span>
                    @endif
                </span>
                <span><strong>DOJ:</strong>
                    {{ $employee->doj ? Carbon\Carbon::parse($employee->doj)->format('d M, Y') : 'N/A' }}</span>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    @if($hasPolicy)
    <div class="ej-summary-row fade-up delay-1">
        <div class="ej-summary-card status">
            <div class="ej-summary-icon"><i class="fas fa-info-circle"></i></div>
            <div>
                <div class="ej-summary-label">Exit Status</div>
                <div class="ej-summary-value">
                    @if($hasActiveExit)
                    {{ ucwords(str_replace('_', ' ', $activeExit->exit_status ?? 'N/A')) }}
                    @else
                    <span class="text-muted">Not Initiated</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="ej-summary-card policy">
            <div class="ej-summary-icon"><i class="fas fa-file-contract"></i></div>
            <div>
                <div class="ej-summary-label">Policy Name</div>
                <div class="ej-summary-value">{{ $exitPolicy->policy_name ?? 'No Policy' }}</div>
            </div>
        </div>
        <div class="ej-summary-card notice">
            <div class="ej-summary-icon"><i class="fas fa-clock"></i></div>
            <div>
                <div class="ej-summary-label">Notice Period Duration</div>
                <div class="ej-summary-value">{{ $exitPolicy->notice_period_days ?? 'N/A' }} Days</div>
            </div>
        </div>
        <div class="ej-summary-card days">
            <div class="ej-summary-icon"><i class="fas fa-calendar-day"></i></div>
            <div>
                <div class="ej-summary-label">Days Remaining</div>
                <div class="ej-summary-value">
                    @if($isNoticePeriod && $noticeDetails)
                    {{ $noticeDetails['days_remaining'] ?? 0 }}
                    @elseif($isOnHold)
                    <span class="text-warning">Pending</span>
                    @elseif($isExited)
                    <span class="text-success">Complete</span>
                    @else
                    —
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Exit Journey Stages - ALL STEPS VISIBLE -->
    <div class="fade-up delay-2">
        @if(count($exitJourney) > 0)
        @foreach($exitJourney as $stageKey => $stage)
        @php
        $stageNumber = $loop->iteration;
        $isLocked = $stage['is_locked'] ?? false;
        $statusClass = $stage['status'] === 'completed' ? 'completed' : ($stage['status'] === 'in-progress' ?
        'in-progress' : 'pending');

        if ($stage['is_first'] ?? false) {
        $statusLabel = $stage['status'] === 'completed' ? 'Completed' : 'Action Required';
        $statusClass = $stage['status'] === 'completed' ? 'completed' : 'action-required';
        } else {
        $statusLabel = $stage['status_label'] ?? ($stage['status'] === 'completed' ? 'Completed' : ($stage['status'] ===
        'in-progress' ? 'In Progress' : 'Pending'));
        }

        $stageIcon = $stage['icon'] ?? 'fas fa-circle';
        $numberColor = $stage['color'] ?? 'primary';
        $isCompleted = $stage['status'] === 'completed';
        $isFirst = $stage['is_first'] ?? false;

        // If locked, use locked styles
        if ($isLocked) {
        $statusClass = 'locked';
        $statusLabel = 'Locked';
        }
        @endphp
        <div class="ej-stage-card {{ $isLocked ? 'locked' : '' }}" id="stage-{{ $stageKey }}">
            <div class="ej-stage-header" onclick="ejToggleStage('ejBody-{{ $stageKey }}')">
                <div class="ej-stage-title">
                    <span
                        class="ej-stage-number {{ $isCompleted ? 'completed' : ($isLocked ? 'locked' : $numberColor) }}">{{ $stageNumber }}</span>
                    <i class="{{ $stageIcon }}"></i>
                    {{ $stage['title'] ?? 'Stage' }}
                    @if($stage['date'])
                    <span class="ej-badge pending"><i class="fas fa-calendar-alt me-1"></i>{{ $stage['date'] }}</span>
                    @endif
                </div>
                <div class="ej-stage-right">
                    <span class="ej-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    <i class="fas fa-chevron-down text-muted"></i>
                </div>
            </div>

            <div class="ej-stage-body {{ $loop->first ? '' : 'ej-hidden' }}" id="ejBody-{{ $stageKey }}"
                style="{{ $loop->first ? '' : 'display:none;' }}">

                <!-- Action Box for First Step - Show Submit Button -->
               <!-- In the stage body section, replace the existing action box code with: -->

                <!-- Action Box for First Step -->
                @if($isFirst)
                    @php
                        $isAdminInitiated = $stage['is_admin_initiated'] ?? false;
                        $exitType = $stage['exit_type'] ?? 'Resignation';
                    @endphp
                    
                    @if(!$isCompleted && !$isLocked && $hasPolicy)
                        @if($isAdminInitiated)
                            {{-- Admin initiated - show info message, not action button --}}
                            <div class="ej-action-box" style="background: var(--ej-primary-light); border-color: #a5b4fc;">
                                <div class="ej-action-icon" style="color: var(--ej-primary);"><i class="fas fa-user-cog"></i></div>
                                <div class="ej-action-title" style="color: var(--ej-primary);">Exit Process Initiated by Admin</div>
                                <div class="ej-action-text" style="color: var(--ej-primary);">
                                    Admin has initiated your <strong>{{ $exitType }}</strong> exit process. 
                                    @if($isOnHold)
                                        <span class="text-warning">Your request is currently ON HOLD pending admin approval.</span>
                                    @elseif($isNoticePeriod)
                                        <span class="text-success">Your notice period has started.</span>
                                    @else
                                        <span>Please check the status below for more details.</span>
                                    @endif
                                </div>
                                <a href="#" class="btn btn-secondary btn-pulse" style="pointer-events: none; opacity: 0.8;">
                                    <i class="fas fa-info-circle me-2"></i> Admin Initiated
                                </a>
                            </div>
                        @else
                            {{-- Employee initiated - show submit button --}}
                            <div class="ej-action-box">
                                <div class="ej-action-icon icon-bounce"><i class="fas fa-pen"></i></div>
                                <div class="ej-action-title">Ready to Submit Resignation?</div>
                                <div class="ej-action-text">Start your exit process by submitting your resignation. This is the first step in the process.</div>
                                <a href="{{ route('employee.exit.resignation.form') }}" class="btn btn-primary btn-pulse">
                                    <i class="fas fa-pen me-2"></i> Submit Resignation Now
                                </a>
                            </div>
                        @endif
                    @elseif($isCompleted)
                        @if($isAdminInitiated)
                            <div class="ej-action-box" style="background: var(--ej-info-light); border-color: #67e8f9;">
                                <div class="ej-action-icon" style="color: var(--ej-info);"><i class="fas fa-check-circle"></i></div>
                                <div class="ej-action-title" style="color: #0e7490;">Admin Initiated Exit Process</div>
                                <div class="ej-action-text" style="color: #0e7490;">
                                    <strong>{{ $exitType }}</strong> exit process has been initiated by admin on {{ $stage['date'] ?? 'N/A' }}.
                                    {{ $isExited ? 'Your exit is complete.' : 'The process is currently in progress.' }}
                                </div>
                            </div>
                        @else
                            <div class="ej-action-box" style="background: var(--ej-success-light); border-color: #86efac;">
                                <div class="ej-action-icon" style="color: var(--ej-success);"><i class="fas fa-check-circle"></i></div>
                                <div class="ej-action-title" style="color: #166534;">Resignation Submitted Successfully!</div>
                                <div class="ej-action-text" style="color: #166534;">Your resignation has been submitted. The approval process will begin shortly.</div>
                            </div>
                        @endif
                    @elseif($isLocked)
                        <div class="ej-action-box locked">
                            <div class="ej-action-icon"><i class="fas fa-lock"></i></div>
                            <div class="ej-action-title">Step Locked</div>
                            <div class="ej-action-text">This step will be available once you have an active exit policy assigned.</div>
                        </div>
                    @endif
                @endif

                <!-- Locked message for other locked steps -->
                @if($isLocked && !$isFirst)
                <div class="ej-action-box locked">
                    <div class="ej-action-icon"><i class="fas fa-lock"></i></div>
                    <div class="ej-action-title">Step Locked</div>
                    <div class="ej-action-text">This step will be available once the previous steps are completed.</div>
                </div>
                @endif

                @if(!in_array($stageKey, [
                'knowledge_transfer',
                'fnf',
                'exit_interview',
                'clearance'
                ]))
                @if(!empty($stage['data']))
                <div class="ej-info-grid">

                    @foreach($stage['data'] as $label => $value)

                    {{-- Approvers --}}
                    @if($label === 'approvers' && is_array($value) && count($value) > 0)

                    <div class="ej-info-chip" style="grid-column: 1 / -1;">
                        <span class="label">Approvers</span>
                        <span class="value">

                            @foreach($value as $approver)

                            <span class="badge bg-light text-dark me-1 mb-1"
                                style="padding:4px 10px;font-size:0.75rem;">

                                {{ is_object($approver)
                                        ? ($approver->approver_name ?? 'N/A')
                                        : ($approver['name'] ?? 'N/A')
                                    }}

                                @if(is_object($approver) && isset($approver->status))
                                @php $apStatus = $approver->status ?? 'Pending'; @endphp

                                <span class="badge
                                            {{ $apStatus === 'Approved'
                                                ? 'bg-success'
                                                : ($apStatus === 'Rejected'
                                                    ? 'bg-danger'
                                                    : 'bg-warning') }}" style="font-size:0.6rem;">
                                    {{ $apStatus }}
                                </span>

                                @elseif(is_array($approver) && isset($approver['status']))
                                @php $apStatus = $approver['status'] ?? 'Pending'; @endphp

                                <span class="badge
                                            {{ $apStatus === 'Approved'
                                                ? 'bg-success'
                                                : ($apStatus === 'Rejected'
                                                    ? 'bg-danger'
                                                    : 'bg-warning') }}" style="font-size:0.6rem;">
                                    {{ $apStatus }}
                                </span>
                                @endif

                            </span>

                            @endforeach

                        </span>
                    </div>

                    {{-- Requirements --}}
                    @elseif($label === 'requirements' && is_array($value) && count($value) > 0)

                    <div class="ej-info-chip" style="grid-column: 1 / -1;">
                        <span class="label">Requirements</span>
                        <span class="value">

                            @foreach($value as $req)
                            <span class="badge bg-primary text-white me-1 mb-1"
                                style="font-size:0.7rem;padding:4px 10px;">
                                {{ $req }}
                            </span>
                            @endforeach

                        </span>
                    </div>

                    {{-- FNF Items --}}
                    @elseif($label === 'items' && is_array($value) && count($value) > 0)

                    <div class="ej-info-chip" style="grid-column: 1 / -1;">
                        <span class="label">FNF Items</span>
                        <span class="value">

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

                            @foreach($value as $item)
                            <span class="badge bg-success text-white me-1 mb-1"
                                style="font-size:0.7rem;padding:4px 10px;">
                                {{ $fnfLabels[$item] ?? ucwords(str_replace('_', ' ', $item)) }}
                            </span>
                            @endforeach

                        </span>
                    </div>

                    {{-- Normal information --}}
                    @elseif(
                    $label !== 'approvers' &&
                    $label !== 'requirements' &&
                    $label !== 'items' &&


                    $label !== 'kt_duration' &&
                    $label !== 'kt_deadline' &&
                    $label !== 'expected_exit_date' &&
                    $label !== 'notice_period' &&
                    $label !== 'notice_duration' &&
                    $label !== 'clearance_duration' &&
                    $label !== 'clearance_deadline' &&
                    $label !== 'interview_duration' &&
                    $label !== 'interview_deadline' &&
                    $label !== 'fnf_duration' &&
                    $label !== 'fnf_deadline' &&
                    $label !== 'workflow' &&
                    $label !== 'status'
                    )

                    <div class="ej-info-chip">

                        <span class="label">
                            {{ ucwords(str_replace('_', ' ', $label)) }}
                        </span>

                        <span class="value">

                            @if($label === 'department' && !empty($value))

                            <span class="department-name">
                                {{ $value }}
                            </span>

                            @elseif(is_array($value))

                            {{ json_encode($value) }}

                            @else

                            {{ $value }}

                            @endif

                        </span>

                    </div>

                    @endif

                    @endforeach

                </div>
                @endif
                @endif

                @if($stage['description'])
                <div class="mt-3 text-muted"
                    style="font-size:0.85rem;background:var(--ej-bg-soft);padding:12px 16px;border-radius:0.75rem;">
                    <i class="fas fa-info-circle me-1"></i> {{ $stage['description'] }}
                </div>
                @endif

                <!-- ============================================ -->
                <!-- ASSIGNED PERSON DISPLAY - CLEAR & DETAILED  -->
                <!-- ============================================ -->
                @php
                $showAssignment = !($isFirst || $stageKey === 'approval' || $stageKey === 'notice_period' || $stageKey
                === 'resignation');
                $hasAssignment = isset($stage['assigned_to_name']) && $stage['assigned_to_name'] &&
                !empty($stage['assigned_to_name']);
                $isNotAssigned = isset($stage['assigned_to']) && $stage['assigned_to'] === null && $showAssignment;

                // Get assignment details
                $assigneeName = $stage['assigned_to_name'] ?? null;
                $assigneeRole = $stage['assigned_to_role'] ?? null;
                $assigneeDesignation = $stage['assigned_to_designation'] ?? null;
                $assigneeEmployeeCode = $stage['assigned_to_employee_code'] ?? null;
                $assigneeEmail = $stage['assigned_to_email'] ?? null;
                $taskDeadline = $stage['task_deadline'] ?? null;
                $taskInstructions = $stage['task_instructions'] ?? null;
                $taskStatus = $stage['status'] ?? 'pending';

                // Determine the display name (use designation if available, otherwise role)
                $displayRole = $assigneeDesignation ?? $assigneeRole ?? 'Team Member';

                // Task label for display
                $taskLabels = [
                'knowledge_transfer' => 'Knowledge Transfer (KT)',
                'exit_interview' => 'Exit Interview',
                'clearance' => 'Assets & Clearance',
                'fnf' => 'FNF Settlement'
                ];
                $taskDisplayName = $taskLabels[$stageKey] ?? ucwords(str_replace('_', ' ', $stageKey));
                // $stageKey is the array index; use the stage's key to identify Exit Interview.
                $journeyStepKey = $stage['key'] ?? $stageKey;
                $assignmentLabels = [
                    'exit_interview' => 'Interviewer',
                    'knowledge_transfer' => 'Knowledge Recipient',
                    'fnf' => 'Settlement Owner',
                ];
                $assignmentLabel = $assignmentLabels[$journeyStepKey] ?? 'Assigned Person';
                $assignmentIcon = $journeyStepKey === 'exit_interview' ? 'fas fa-user-tie' : 'fas fa-user-check';
                @endphp

                @if($showAssignment && !$isLocked)
                @if($hasAssignment)
                <div class="ej-assignee-card">
                    <!-- Header -->
                    <div class="assignee-header">
                        <span class="assignee-label">
                            <i class="{{ $assignmentIcon }} me-1"></i> {{ $assignmentLabel }}
                        </span>
                        <span style="font-size:0.7rem;color:var(--ej-text-muted);" class="d-none">
                            for {{ $taskDisplayName }}
                        </span>
                    </div>

                    <!-- Main Info -->
                    <div class="assignee-main">
                        <!-- Avatar -->
                        <div class="assignee-avatar">
                            {{ strtoupper(substr($assigneeName, 0, 1)) }}
                        </div>

                        <!-- Info -->
                        <div class="assignee-info">
                            <div class="assignee-name">
                                {{ $assigneeName }}
                                <span class="assignee-badge">
                                    <i class="fas fa-check-circle me-1"></i> Active
                                </span>
                            </div>
                            <div class="assignee-details">
                                <span class="detail-item">
                                    <i class="fas fa-briefcase"></i>
                                    {{ $displayRole }}
                                </span>
                                @if($assigneeEmployeeCode)
                                <span class="detail-item">
                                    <i class="fas fa-id-badge"></i>
                                    {{ $assigneeEmployeeCode }}
                                </span>
                                @endif
                                @if($assigneeEmail)
                                <span class="detail-item">
                                    <i class="fas fa-envelope"></i>
                                    <a href="mailto:{{ $assigneeEmail }}" class="assignee-contact">
                                        {{ $assigneeEmail }}
                                    </a>
                                </span>
                                @endif
                            </div>

                            <!-- Deadline -->
                            @if($taskDeadline)
                            <div class="assignee-deadline-box">
                                <i class="fas fa-calendar-alt"></i>
                                Deadline: {{ \Carbon\Carbon::parse($taskDeadline)->format('d M Y') }}
                                @if(\Carbon\Carbon::parse($taskDeadline)->isPast())
                                <span style="color:var(--ej-danger);font-weight:700;margin-left:4px;">(Overdue!)</span>
                                @endif
                            </div>
                            @endif

                            <!-- Instructions -->
                            @if($taskInstructions)
                            <div class="assignee-instructions">
                                <i class="fas fa-info-circle"></i>
                                {{ $taskInstructions }}
                            </div>
                            @endif
                        </div>

                        <!-- Status -->
                        <div class="assignee-status-wrap">
                            <span class="assignee-status {{ $taskStatus }}">
                                @if($taskStatus === 'completed')
                                <i class="fas fa-check-circle me-1"></i> Completed
                                @elseif($taskStatus === 'in-progress')
                                <i class="fas fa-spinner fa-spin me-1"></i> In Progress
                                @elseif($taskStatus === 'pending')
                                <i class="fas fa-clock me-1"></i> Pending
                                @else
                                {{ ucfirst($taskStatus) }}
                                @endif
                            </span>
                            @if($taskStatus !== 'completed')
                            <span style="font-size:0.65rem;color:var(--ej-text-muted);">
                                <i class="fas fa-arrow-right me-1"></i>
                                @if($taskStatus === 'in-progress')
                                In progress
                                @else
                                Awaiting completion
                                @endif
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                @elseif($isNotAssigned)
                <div class="ej-assignee-card not-assigned">
                    <div class="assignee-header">
                        <span class="assignee-label" style="background:#f1f5f9;color:#94a3b8;">
                            <i class="fas fa-user-clock me-1"></i> Not Assigned
                        </span>
                        <span style="font-size:0.7rem;color:var(--ej-text-muted);">
                            for {{ $taskDisplayName }}
                        </span>
                    </div>
                    <div class="assignee-main">
                        <div class="assignee-avatar" style="background:#94a3b8;">
                            <i class="fas fa-user" style="font-size:18px;"></i>
                        </div>
                        <div class="assignee-info">
                            <div class="assignee-name" style="color:var(--ej-text-muted);">
                                No one assigned yet
                            </div>
                            <div class="assignee-details">
                                <span class="detail-item">
                                    <i class="fas fa-clock"></i>
                                    Waiting for admin to assign a person
                                </span>
                            </div>
                            <div class="assignee-instructions"
                                style="border-left-color:#94a3b8;background:#f1f5f9;color:var(--ej-text-muted);">
                                <i class="fas fa-info-circle"></i>
                                This task will be assigned by the HR/Admin team shortly.
                                @if($stage['description'])
                                <br><br>
                                <strong>What to do:</strong> {{ $stage['description'] }}
                                @endif
                            </div>
                        </div>
                        <div class="assignee-status-wrap">
                            <span class="assignee-status not-assigned">
                                <i class="fas fa-hourglass-half me-1"></i> Not Assigned
                            </span>
                        </div>
                    </div>
                </div>
                @endif
                @endif

                <!-- Notice Period Details -->
                @if($stageKey === 'notice_period' && isset($stage['notice_details']) && $stage['notice_details'] &&
                !$isLocked)
                @php $noticeDetails = $stage['notice_details']; @endphp
                <div class="mt-4">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="countdown-card">
                                <div
                                    class="countdown-number {{ ($noticeDetails['is_overdue'] ?? false) ? 'overdue' : '' }}">
                                    {{ $noticeDetails['days_remaining'] ?? 0 }}
                                </div>
                                <div class="countdown-label">
                                    {{ ($noticeDetails['is_overdue'] ?? false) ? 'Days Overdue' : 'Days Remaining' }}
                                </div>
                                <div class="countdown-progress">
                                    <div class="progress-track">
                                        <div class="progress-bar {{ ($noticeDetails['is_overdue'] ?? false) ? 'overdue' : '' }}"
                                            style="width: {{ min(100, $noticeDetails['progress'] ?? 0) }}%;">
                                        </div>
                                    </div>
                                    <div class="progress-text">{{ number_format($noticeDetails['progress'] ?? 0, 1) }}%
                                        completed</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="timeline-modern">
                                <div class="timeline-item completed">
                                    <div class="date">
                                        @if($activeExit && $activeExit->approved_at)
                                        {{ Carbon\Carbon::parse($activeExit->approved_at)->format('d M Y') }}
                                        @else
                                        {{ $noticeDetails['start_date'] ?? 'N/A' }}
                                        @endif
                                    </div>
                                    <div class="title">✅ Notice Period Started</div>
                                    <div class="sub-label">Approved by Admin</div>
                                </div>
                                <div
                                    class="timeline-item {{ ($noticeDetails['is_overdue'] ?? false) ? 'active' : '' }}">
                                    <div
                                        class="date {{ ($noticeDetails['is_overdue'] ?? false) ? 'text-danger' : '' }}">
                                        {{ $noticeDetails['end_date'] ?? 'N/A' }}
                                        @if($noticeDetails['is_overdue'] ?? false)
                                        <span class="badge bg-danger ms-1 d-none"
                                            style="font-size:0.6rem;">Overdue</span>
                                        @endif
                                    </div>
                                    <div class="title">
                                        {{ ($noticeDetails['is_overdue'] ?? false) ? 'Overdue by '.abs($noticeDetails['days_remaining'] ?? 0).' days' : 'Expected Exit Date' }}
                                    </div>
                                    <div class="sub-label">Last Working Day</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Approval Progress -->
                @if($stageKey === 'approval' && isset($stage['data']['approvers']) && count($stage['data']['approvers'])
                > 0 && !$isLocked)
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="fas fa-user-check text-primary"></i>
                        <span style="font-weight:600;font-size:0.85rem;color:var(--ej-text);">Approval Progress</span>
                    </div>
                    <div class="approval-grid">
                        @foreach($stage['data']['approvers'] as $approval)
                        @php
                        $apStatus = is_object($approval) ? ($approval->status ?? 'Pending') : ($approval['status'] ??
                        'Pending');
                        $apName = is_object($approval) ? ($approval->approver_name ?? 'N/A') : ($approval['name'] ??
                        'N/A');
                        $apRole = is_object($approval) ? ($approval->approver_role ?? 'Approver') : ($approval['role']
                        ?? 'Approver');
                        @endphp
                        <div class="approval-item">
                            <div>
                                <div class="name">{{ $apName }}</div>
                                <div class="role">{{ $apRole }}</div>
                            </div>
                            <div>
                                @if($apStatus === 'Pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($apStatus === 'Approved')
                                <span class="badge bg-success">Approved</span>
                                @else
                                <span class="badge bg-danger">Rejected</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- ============================================ -->
                <!-- KNOWLEDGE TRANSFER / FNF / EXIT INTERVIEW / CLEARANCE -->
                <!-- No chip blocks here on purpose: all of duration, deadline, -->
                <!-- expected exit date, status, requirements/items/workflow -->
                <!-- would duplicate the single description sentence above -->
                <!-- (e.g. "Complete KT by 16 Sep, 2026 before your last -->
                <!-- working day (21 Sep, 2026)"). If you want any of this -->
                <!-- surfaced again, add it back as one small chip only, -->
                <!-- not both a chip grid and the description. -->
                <!-- ============================================ -->

            </div>
        </div>
        @endforeach
        @endif
    </div>

    <!-- No Policy State -->
    @if(!$hasPolicy)
    <div class="ej-stage-card fade-up delay-2" style="border-radius:1rem;">
        <div class="ej-empty" style="padding:50px 20px;">
            <i class="fas fa-file-contract"></i>
            <h5 style="font-weight:700;color:var(--ej-text);">No Exit Policy Assigned</h5>
            <p>
                @if($hasActiveExit && $isAdminInitiated)
                    An exit process has been initiated by admin, but no policy is assigned.
                    Please contact HR/Admin for assistance.
                @else
                    You don't have any exit policy assigned for resignation. 
                    Please contact the HR/Admin department to get your policy assigned.
                @endif
            </p>
            <div style="margin-top:16px;padding:12px 16px;background:#fef3c7;border-radius:8px;border:1px solid #fde68a;display:inline-block;text-align:left;">
                <span style="color:#78350f;font-size:0.85rem;">
                    <strong>Note:</strong> 
                    @if($hasActiveExit && $isAdminInitiated)
                        Please wait for HR/Admin to complete the process.
                    @else
                        You cannot submit a resignation until an exit policy is assigned to you.
                    @endif
                </span>
            </div>
        </div>
    </div>
    @endif

</div>

<script>
// STAGE TOGGLE
function ejToggleStage(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.style.display = (el.style.display === 'none') ? '' : 'none';
}

// Auto-open the first incomplete stage
document.addEventListener('DOMContentLoaded', function() {
    var stages = document.querySelectorAll('.ej-stage-card');
    var foundIncomplete = false;

    stages.forEach(function(stage, index) {
        var statusBadge = stage.querySelector('.ej-stage-right .ej-badge');
        if (!statusBadge) return;

        var status = statusBadge.textContent.trim();
        var body = stage.querySelector('.ej-stage-body');

        // Check if status is not completed
        var isCompleted = status === 'Completed' || status === 'Approved';
        var isLocked = status === 'Locked';

        if (!foundIncomplete && !isCompleted && !isLocked) {
            foundIncomplete = true;
            if (body) {
                body.style.display = '';
                body.classList.remove('ej-hidden');
            }
        } else {
            if (body && index > 0) {
                body.style.display = 'none';
                body.classList.add('ej-hidden');
            }
        }
    });

    // If all completed, open the last one
    if (!foundIncomplete) {
        var lastStage = stages[stages.length - 1];
        if (lastStage) {
            var lastBody = lastStage.querySelector('.ej-stage-body');
            if (lastBody) {
                lastBody.style.display = '';
                lastBody.classList.remove('ej-hidden');
            }
        }
    }
});
</script>

@endsection
