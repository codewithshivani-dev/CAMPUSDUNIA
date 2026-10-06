@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'Resignation & Exit Management')

@section('content')
<style>
:root {
    --primary: #4f46e5;
    --primary-light: #818cf8;
    --primary-dark: #3730a3;
    --primary-bg: #eef2ff;
    --success: #22c55e;
    --success-bg: #dcfce7;
    --danger: #ef4444;
    --danger-bg: #fee2e2;
    --warning: #f59e0b;
    --warning-bg: #fef3c7;
    --info: #3b82f6;
    --info-bg: #dbeafe;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --gray-900: #0f172a;
    --radius: 16px;
    --radius-sm: 10px;
    --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.exit-dashboard {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 4px;
}

/* ===== HEADER ===== */
.dashboard-header {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border-radius: var(--radius) var(--radius) 0 0;
    padding: 28px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    position: relative;
    overflow: hidden;
}

.dashboard-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
}

.dashboard-header .header-left {
    display: flex;
    align-items: center;
    gap: 16px;
    z-index: 1;
}

.dashboard-header .header-icon {
    width: 52px;
    height: 52px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #fff;
    backdrop-filter: blur(4px);
}

.dashboard-header h4 {
    color: #fff;
    font-weight: 700;
    font-size: 1.35rem;
    margin: 0;
    letter-spacing: -0.3px;
}

.dashboard-header .subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    margin: 0;
}

.dashboard-header .btn-submit {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fff;
    padding: 10px 24px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(4px);
    text-decoration: none;
    z-index: 1;
}

.dashboard-header .btn-submit:hover {
    background: rgba(255, 255, 255, 0.25);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.dashboard-header .btn-submit.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

/* ===== CARDS ===== */
.card-modern {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-top: none;
    padding: 24px 28px;
    box-shadow: var(--shadow-sm);
}

.card-modern:last-of-type {
    border-radius: 0 0 var(--radius) var(--radius);
}

.card-modern .card-title {
    font-weight: 700;
    font-size: 1rem;
    color: var(--gray-800);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.card-modern .card-title i {
    color: var(--primary);
}

/* ===== STATUS BADGES ===== */
.badge-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-pending_approval {
    background: var(--warning-bg);
    color: #92400e;
}

.badge-notice_period {
    background: var(--info-bg);
    color: #1e40af;
}

.badge-exited {
    background: var(--danger-bg);
    color: #991b1b;
}

.badge-approved {
    background: var(--success-bg);
    color: #166534;
}

.badge-cancelled {
    background: var(--gray-200);
    color: var(--gray-600);
}

.badge-rejected {
    background: var(--danger-bg);
    color: #991b1b;
}

/* ===== POLICY GRID ===== */
.policy-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 12px;
}

.policy-item {
    background: var(--gray-50);
    border-radius: var(--radius-sm);
    padding: 12px 16px;
    border: 1px solid var(--gray-200);
    text-align: center;
}

.policy-item .label {
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--gray-500);
    font-weight: 600;
}

.policy-item .value {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--gray-800);
    margin-top: 2px;
}

.policy-item .value .badge {
    font-size: 0.65rem;
    font-weight: 500;
}

/* ===== EXIT INFO ===== */
.exit-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px 24px;
}

.exit-info-item .label {
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--gray-500);
    font-weight: 600;
    display: block;
}

.exit-info-item .value {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--gray-800);
}

.exit-info-item .sub-text {
    font-size: 0.75rem;
    color: var(--gray-500);
    font-weight: 400;
    margin-top: 2px;
}

/* ===== TIMELINE ===== */
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
    background: linear-gradient(to bottom, var(--primary), var(--primary-light));
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
    background: var(--primary);
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px var(--primary);
}

.timeline-item.completed::before {
    background: var(--success);
    box-shadow: 0 0 0 2px var(--success);
}

.timeline-item .date {
    font-size: 0.8rem;
    color: var(--gray-500);
    font-weight: 500;
}

.timeline-item .title {
    font-weight: 600;
    color: var(--gray-800);
    font-size: 0.9rem;
}

.timeline-item .sub-label {
    font-size: 0.7rem;
    color: var(--gray-500);
}

/* ===== COUNTDOWN ===== */
.countdown-card {
    background: linear-gradient(135deg, #f8faff 0%, #eef2ff 100%);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-sm);
    padding: 20px;
    text-align: center;
}

.countdown-number {
    font-size: 2.8rem;
    font-weight: 800;
    line-height: 1;
    color: var(--primary);
    letter-spacing: -1px;
}

.countdown-number.overdue {
    color: var(--danger);
}

.countdown-label {
    font-size: 0.85rem;
    color: var(--gray-500);
    margin-top: 4px;
}

.countdown-progress {
    margin-top: 12px;
}

.countdown-progress .progress-track {
    height: 4px;
    background: var(--gray-200);
    border-radius: 2px;
    overflow: hidden;
}

.countdown-progress .progress-bar {
    height: 100%;
    border-radius: 2px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
    transition: width 0.6s ease;
}

.countdown-progress .progress-bar.overdue {
    background: linear-gradient(90deg, var(--danger), #f87171);
}

.countdown-progress .progress-text {
    font-size: 0.7rem;
    color: var(--gray-400);
    margin-top: 4px;
}

/* ===== APPROVAL ITEMS ===== */
.approval-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 12px;
}

.approval-item {
    background: var(--gray-50);
    border-radius: var(--radius-sm);
    padding: 12px 16px;
    border: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.approval-item .name {
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--gray-800);
}

.approval-item .role {
    font-size: 0.7rem;
    color: var(--gray-500);
}

.approval-item .badge {
    font-size: 0.6rem;
    padding: 2px 10px;
}

/* ===== HISTORY TABLE ===== */
.history-table {
    width: 100%;
    border-collapse: collapse;
}

.history-table th {
    text-align: left;
    padding: 10px 12px;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--gray-500);
    font-weight: 600;
    border-bottom: 2px solid var(--gray-200);
}

.history-table td {
    padding: 10px 12px;
    font-size: 0.9rem;
    color: var(--gray-700);
    border-bottom: 1px solid var(--gray-100);
}

.history-table tr:hover td {
    background: var(--gray-50);
}

.history-table tr:last-child td {
    border-bottom: none;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 50px 20px;
}

.empty-state .icon {
    font-size: 4rem;
    color: var(--gray-300);
    margin-bottom: 16px;
}

.empty-state h5 {
    font-weight: 700;
    color: var(--gray-700);
}

.empty-state p {
    color: var(--gray-500);
}

/* ===== NO POLICY STATE ===== */
.no-policy-state {
    text-align: center;
    padding: 30px 20px;
}

.no-policy-state .icon {
    font-size: 3.5rem;
    color: var(--gray-400);
    margin-bottom: 16px;
}

.no-policy-state h5 {
    font-weight: 700;
    color: var(--gray-700);
    margin-bottom: 8px;
}

.no-policy-state p {
    color: var(--gray-500);
    max-width: 500px;
    margin: 0 auto 16px;
}

.no-policy-state .contact-info {
    background: var(--primary-bg);
    border-radius: var(--radius-sm);
    padding: 16px 20px;
    display: inline-block;
    text-align: left;
    border: 1px solid #c7d2fe;
}

.no-policy-state .contact-info i {
    color: var(--primary);
    width: 20px;
}

.no-policy-state .contact-info .label {
    font-weight: 600;
    color: var(--gray-700);
}

/* ===== BUTTONS ===== */
.btn-primary-modern {
    background: var(--primary);
    border: none;
    color: #fff;
    padding: 10px 28px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary-modern:hover {
    background: var(--primary-dark);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
}

.btn-primary-modern.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .dashboard-header {
        padding: 20px;
    }

    .card-modern {
        padding: 18px 20px;
    }

    .exit-info-grid {
        grid-template-columns: 1fr;
    }

    .policy-grid {
        grid-template-columns: 1fr 1fr;
    }

    .approval-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .policy-grid {
        grid-template-columns: 1fr;
    }

    .dashboard-header .header-left {
        flex-wrap: wrap;
    }

    .dashboard-header .header-icon {
        width: 44px;
        height: 44px;
        font-size: 20px;
    }

    .dashboard-header h4 {
        font-size: 1.1rem;
    }
}

/* ===== ANIMATIONS ===== */
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
</style>

<div class="exit-dashboard">

    {{-- HEADER --}}
    <div class="dashboard-header fade-up">
        <div class="header-left">
            <div class="header-icon"><i class="fas fa-door-open"></i></div>
            <div>
                <h4>Resignation Portal</h4>
                <p class="subtitle">Track your resignation request and exit process</p>
            </div>
        </div>
        @if(!$hasActiveExit)
            @if($exitPolicy)
                <a href="{{ route('employee.exit.resignation.form') }}" class="btn-submit">
                    <i class="fas fa-pen me-2"></i> Submit Resignation
                </a>
            @else
                <span class="btn-submit disabled" style="cursor:not-allowed;opacity:0.5;">
                    <i class="fas fa-lock me-2"></i> Submit Resignation
                </span>
            @endif
        @endif
    </div>

    {{-- EXIT POLICY --}}
    <div class="card-modern fade-up delay-1 d-none">
        <div class="card-title">
            <i class="fas fa-file-contract"></i> Your Exit Policy
            @if($exitPolicy)
                <a href="{{ route('employee.exit.policy') }}" class="btn btn-sm btn-primary ms-auto" style="font-size:0.7rem;padding:2px 10px;">
                    <i class="fas fa-eye"></i> View Full Policy
                </a>
            @endif
        </div>

        @if($exitPolicy)
        <div class="policy-grid">
            <div class="policy-item">
                <div class="label">Notice Period</div>
                <div class="value">{{ $exitPolicy->notice_period_days ?? $exitPolicy->default_notice_period ?? 'N/A' }}
                    Days</div>
            </div>
            <div class="policy-item">
                <div class="label">Exit Type</div>
                <div class="value">
                    <span class="badge bg-primary">{{ $exitPolicy->exit_type ?? 'Resignation' }}</span>
                </div>
            </div>
            <div class="policy-item">
                <div class="label">Policy Code</div>
                <div class="value">
                    <span class="badge bg-secondary">{{ $exitPolicy->policy_code ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="policy-item">
                <div class="label">Clearance Workflow</div>
                <div class="value">
                    <span class="badge bg-info">{{ ucfirst($exitPolicy->clearance_workflow ?? 'N/A') }}</span>
                    <small class="d-block text-muted"
                        style="font-size:0.65rem;">{{ $exitPolicy->clearance_days ?? 'N/A' }} days</small>
                </div>
            </div>
        </div>

        {{-- Employment Type Overrides --}}
        @if(!empty($exitPolicy->employment_notice_periods))
        <div class="mt-3" style="background:var(--gray-50);padding:12px 16px;border-radius:var(--radius-sm);">
            <small class="text-muted d-block mb-2"><i class="fas fa-clock me-1"></i> Employment Type Overrides:</small>
            <div class="d-flex flex-wrap gap-2">
                @foreach($exitPolicy->employment_notice_periods as $type => $days)
                @php
                $customDays = $exitPolicy->employment_custom_days[$type] ?? null;
                $displayDays = ($days === 'custom' && $customDays) ? $customDays : $days;
                @endphp
                <span class="badge bg-light text-dark border" style="font-size:0.75rem;padding:4px 10px;">
                    {{ $type }}: {{ $displayDays }} days
                </span>
                @endforeach
            </div>
            <div class="mt-2">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> Your employment type:
                    <strong>{{ $employee->employment_type ?? 'N/A' }}</strong>
                    @if($exitPolicy->notice_period_days)
                    <span class="text-success ms-2">
                        <i class="fas fa-check-circle"></i> Notice period: {{ $exitPolicy->notice_period_days }} days
                    </span>
                    @endif
                </small>
            </div>
        </div>
        @endif

        {{-- Exit Requirements --}}
        <div class="mt-3"
            style="background:var(--primary-bg);padding:16px 20px;border-radius:var(--radius-sm);border-left:4px solid var(--primary);">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="fas fa-clipboard-list text-primary"></i>
                <span style="font-weight:600;font-size:0.95rem;color:var(--gray-800);">Exit Requirements</span>
            </div>

            <div class="row g-3">
                {{-- Exit Interview --}}
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2 p-2" style="background:#fff;border-radius:8px;">
                        <span class="badge {{ $exitPolicy->exit_interview_required ? 'bg-success' : 'bg-secondary' }}"
                            style="font-size:0.7rem;padding:4px 12px;">
                            {{ $exitPolicy->exit_interview_required ? 'Required' : 'Not Required' }}
                        </span>
                        <span style="font-size:0.85rem;">
                            <i class="fas fa-comments me-1"></i> Exit Interview
                            @if($exitPolicy->exit_interview_required && $exitPolicy->interview_days)
                            <small class="text-muted">({{ $exitPolicy->interview_days }} days)</small>
                            @endif
                        </span>
                    </div>
                </div>

                {{-- FNF --}}
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2 p-2" style="background:#fff;border-radius:8px;">
                        <span class="badge {{ $exitPolicy->fnf_required ? 'bg-success' : 'bg-secondary' }}"
                            style="font-size:0.7rem;padding:4px 12px;">
                            {{ $exitPolicy->fnf_required ? 'Required' : 'Not Required' }}
                        </span>
                        <span style="font-size:0.85rem;">
                            <i class="fas fa-file-invoice-dollar me-1"></i> FNF Settlement
                            @if($exitPolicy->fnf_required && $exitPolicy->fnf_processing_days)
                            <small class="text-muted">({{ $exitPolicy->fnf_processing_days }} days)</small>
                            @endif
                        </span>
                    </div>
                </div>

                {{-- KT --}}
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2 p-2" style="background:#fff;border-radius:8px;">
                        <span class="badge {{ $exitPolicy->kt_required ? 'bg-success' : 'bg-secondary' }}"
                            style="font-size:0.7rem;padding:4px 12px;">
                            {{ $exitPolicy->kt_required ? 'Required' : 'Not Required' }}
                        </span>
                        <span style="font-size:0.85rem;">
                            <i class="fas fa-chalkboard-teacher me-1"></i> Knowledge Transfer
                            @if($exitPolicy->kt_required && $exitPolicy->kt_days)
                            <small class="text-muted">({{ $exitPolicy->kt_days }} days)</small>
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Clearance --}}
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-2 p-2" style="background:#fff;border-radius:8px;">
                        <span class="badge bg-info" style="font-size:0.7rem;padding:4px 12px;">
                            {{ ucfirst($exitPolicy->clearance_workflow ?? 'N/A') }}
                        </span>
                        <span style="font-size:0.85rem;">
                            <i class="fas fa-check-double me-1"></i> Clearance
                            @if($exitPolicy->clearance_days)
                            <small class="text-muted">({{ $exitPolicy->clearance_days }} days)</small>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- FNF Items (if required) --}}
            @if($exitPolicy->fnf_required && !empty($exitPolicy->fnf_items))
            <div class="mt-3 pt-2 border-top" style="border-color:rgba(0,0,0,0.08);">
                <small class="text-muted d-block mb-2"><i class="fas fa-list me-1"></i> FNF Items:</small>
                <div class="d-flex flex-wrap gap-2">
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
                    @foreach($exitPolicy->fnf_items as $item)
                    <span class="badge bg-warning text-dark" style="font-size:0.7rem;padding:4px 12px;">
                        <i class="fas fa-check-circle me-1"></i>
                        {{ $fnfLabels[$item] ?? ucfirst(str_replace('_', ' ', $item)) }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- KT Requirements (if required) --}}
            @if($exitPolicy->kt_required && !empty($exitPolicy->kt_requirements))
            <div class="mt-3 pt-2 border-top" style="border-color:rgba(0,0,0,0.08);">
                <small class="text-muted d-block mb-2"><i class="fas fa-tasks me-1"></i> KT Handover
                    Requirements:</small>
                <div class="d-flex flex-wrap gap-2">
                    @php
                    $ktLabels = [
                    'documentation' => 'Documentation Handover',
                    'project_handover' => 'Project/Work Handover',
                    'code_handover' => 'Code/System Handover',
                    'client_handover' => 'Client/Stakeholder Handover',
                    'process_handover' => 'Process Handover',
                    'training' => 'Training & Support',
                    'knowledge_docs' => 'Knowledge Base Documentation'
                    ];
                    @endphp
                    @foreach($exitPolicy->kt_requirements as $item)
                    <span class="badge bg-success" style="font-size:0.7rem;padding:4px 12px;">
                        <i class="fas fa-check-circle me-1"></i>
                        {{ $ktLabels[$item] ?? ucfirst(str_replace('_', ' ', $item)) }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Additional Requirements --}}
            @if(!empty($exitPolicy->additional_requirements))
            <div class="mt-3 pt-2 border-top" style="border-color:rgba(0,0,0,0.08);">
                <small class="text-muted d-block mb-2"><i class="fas fa-plus-circle me-1"></i> Additional
                    Requirements:</small>
                <div class="d-flex flex-wrap gap-2">
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
                    @foreach($exitPolicy->additional_requirements as $item)
                    <span class="badge bg-secondary" style="font-size:0.7rem;padding:4px 12px;">
                        <i class="fas fa-check-circle me-1"></i>
                        {{ $additionalLabels[$item] ?? ucfirst(str_replace('_', ' ', $item)) }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Description --}}
        @if($exitPolicy->description)
        <div class="mt-3 text-muted"
            style="font-size:0.85rem;background:var(--gray-50);padding:12px 16px;border-radius:var(--radius-sm);">
            <i class="fas fa-info-circle me-1"></i> {{ $exitPolicy->description }}
        </div>
        @endif

        {{-- Terms & Conditions --}}
        @if($exitPolicy->terms_conditions)
        <div class="mt-3"
            style="background:#fef3c7;border:1px solid #fde68a;border-radius:var(--radius-sm);padding:12px 16px;">
            <div class="d-flex align-items-start gap-2">
                <i class="fas fa-file-contract text-warning mt-1"></i>
                <div>
                    <strong style="font-size:0.85rem;color:#92400e;">Terms & Conditions:</strong>
                    <p class="mb-0 mt-1" style="font-size:0.85rem;color:#78350f;white-space:pre-wrap;">
                        {{ $exitPolicy->terms_conditions }}</p>
                </div>
            </div>
        </div>
        @endif

        @else
        {{-- No Policy Assigned - Show Contact HR Message --}}
        <div class="no-policy-state">
            <div class="icon">
                <i class="fas fa-file-contract"></i>
            </div>
            <h5>No Exit Policy Assigned</h5>
            <p>You don't have any exit policy assigned for resignation. Please contact the HR/Admin department to get your policy assigned.</p>
            
           
            <div style="margin-top:16px;padding:12px 16px;background:#fef3c7;border-radius:8px;border:1px solid #fde68a;display:inline-block;text-align:left;">
                <i class="fas fa-info-circle" style="color:#92400e;"></i>
                <span style="color:#78350f;font-size:0.85rem;">
                    <strong>Note:</strong> You cannot submit a resignation until an exit policy is assigned to you.
                </span>
            </div>
        </div>
        @endif
    </div>

    {{-- ACTIVE EXIT --}}
    @if($hasActiveExit && $activeExit)
    <div class="card-modern fade-up delay-2">
        <div class="card-title">
            <i class="fas fa-clock"></i> Active Exit Process
            @if($isOnHold)
            <span class="badge-status badge-pending_approval"><i class="fas fa-pause-circle me-1"></i> ON HOLD</span>
            @elseif($isNoticePeriod)
            <span class="badge-status badge-notice_period"><i class="fas fa-hourglass-half me-1"></i> NOTICE
                PERIOD</span>
            @elseif($activeExit->exit_status === 'exited')
            <span class="badge-status badge-exited"><i class="fas fa-check-circle me-1"></i> EXITED</span>
            @endif
            @if(isset($isAdminInitiated) && $isAdminInitiated)
            <span class="badge bg-secondary" style="font-size:0.65rem;padding:2px 10px;">Admin Initiated</span>
            @else
            <span class="badge bg-info" style="font-size:0.65rem;padding:2px 10px;">Self Initiated</span>
            @endif
        </div>

        <div class="exit-info-grid">
            <div class="exit-info-item">
                <span class="label">Initiation Date</span>
                <div class="value">
                    @php
                    $initDate = null;
                    if($activeExit->initiated_at) $initDate = \Carbon\Carbon::parse($activeExit->initiated_at);
                    elseif($activeExit->resignation_date) $initDate =
                    \Carbon\Carbon::parse($activeExit->resignation_date);
                    elseif($activeExit->created_at) $initDate = \Carbon\Carbon::parse($activeExit->created_at);
                    @endphp
                    @if($initDate)
                    {{ $initDate->format('d M Y') }}
                    <span class="sub-text">{{ $initDate->format('g:i A') }}</span>
                    @else
                    <span class="text-muted">N/A</span>
                    @endif
                </div>
            </div>
            <div class="exit-info-item">
                <span class="label">Exit Reason</span>
                <div class="value">{{ ucwords(str_replace('_', ' ', $activeExit->exit_reason ?? 'N/A')) }}</div>
            </div>
            <div class="exit-info-item">
                <span class="label">Status</span>
                <div class="value">
                    @if($isOnHold)
                    <span class="text-warning fw-bold">ON HOLD</span>
                    <span class="sub-text">Pending Admin Approval</span>
                    @elseif($isNoticePeriod)
                    <span class="text-primary fw-bold">NOTICE PERIOD</span>
                    @if($noticeDetails)
                    <span class="sub-text">Started: {{ $noticeDetails['start_date'] ?? 'N/A' }}</span>
                    <span class="sub-text">Exit: {{ $noticeDetails['end_date'] ?? 'N/A' }}</span>
                    @endif
                    @elseif($activeExit->exit_status === 'exited')
                    <span class="text-danger fw-bold">EXITED</span>
                    <span class="sub-text">Process completed</span>
                    @endif
                </div>
            </div>
            @if($isNoticePeriod && $noticeDetails)
            <div class="exit-info-item">
                <span class="label">Notice Period</span>
                <div class="value">
                    {{ $noticeDetails['notice_days'] ?? $activeExit->notice_period_days ?? 30 }} Days
                    <span class="sub-text text-success">{{ $noticeDetails['days_remaining'] ?? 0 }} days
                        remaining</span>
                </div>
            </div>
            @endif
            @if($activeExit->approved_at)
            <div class="exit-info-item">
                <span class="label">Approved Date</span>
                <div class="value">
                    @php $approvedDate = \Carbon\Carbon::parse($activeExit->approved_at); @endphp
                    {{ $approvedDate->format('d M Y') }}
                    <span class="sub-text">{{ $approvedDate->format('g:i A') }}</span>
                </div>
            </div>
            @endif
        </div>

        @if($activeExit->exit_notes)
        <div class="mt-3 pt-3 border-top text-muted" style="font-size:0.85rem;">
            <span class="small text-uppercase text-muted">Notes</span>
            <div>{{ $activeExit->exit_notes }}</div>
        </div>
        @endif

        {{-- Approval Progress --}}
        @if($isOnHold && $activeExit->approvals->isNotEmpty())
        <div class="mt-4 pt-3 border-top">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="fas fa-user-check text-primary"></i>
                <span style="font-weight:600;font-size:0.85rem;color:var(--gray-700);">Approval Progress</span>
            </div>
            <div class="approval-grid">
                @foreach($activeExit->approvals as $approval)
                <div class="approval-item">
                    <div>
                        <div class="name">{{ $approval->approver_name }}</div>
                        <div class="role">{{ $approval->approver_role ?? 'Approver' }}</div>
                    </div>
                    <div>
                        @if($approval->status === 'Pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($approval->status === 'Approved')
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

        {{-- Notice Period Timeline --}}
        @if($isNoticePeriod && $noticeDetails)
        <div class="mt-4 pt-3 border-top">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="countdown-card">
                        <div class="countdown-number {{ ($noticeDetails['is_overdue'] ?? false) ? 'overdue' : '' }}">
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
                                @if($activeExit->approved_at)
                                {{ \Carbon\Carbon::parse($activeExit->approved_at)->format('d M Y') }}
                                <span
                                    style="font-size:0.7rem;color:var(--gray-400);">{{ \Carbon\Carbon::parse($activeExit->approved_at)->format('g:i A') }}</span>
                                @else
                                {{ $noticeDetails['start_date'] ?? 'N/A' }}
                                @endif
                            </div>
                            <div class="title">✅ Notice Period Started</div>
                            <div class="sub-label">Approved by Admin</div>
                        </div>
                        <div class="timeline-item {{ ($noticeDetails['is_overdue'] ?? false) ? 'active' : '' }}">
                            <div class="date {{ ($noticeDetails['is_overdue'] ?? false) ? 'text-danger' : '' }}">
                                {{ $noticeDetails['end_date'] ?? 'N/A' }}
                                @if($noticeDetails['is_overdue'] ?? false)
                                <span class="badge bg-danger ms-1" style="font-size:0.6rem;">Overdue</span>
                                @endif
                            </div>
                            <div class="title">
                                {{ ($noticeDetails['is_overdue'] ?? false) ? 'Overdue by '.abs($noticeDetails['days_remaining'] ?? 0).' days' : 'Expected Exit Date' }}
                            </div>
                            <div class="sub-label">Last Working Day</div>
                        </div>
                        @if($activeExit->exit_status === 'exited' && $activeExit->actual_exit_date)
                        <div class="timeline-item completed">
                            <div class="date text-success">
                                {{ \Carbon\Carbon::parse($activeExit->actual_exit_date)->format('d M Y') }}</div>
                            <div class="title text-success">Exit Completed</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- EXIT HISTORY --}}
    @if($exitHistory->isNotEmpty())
    <div class="card-modern fade-up delay-3">
        <div class="card-title">
            <i class="fas fa-history"></i> Exit History
            <span class="badge bg-secondary" style="font-size:0.65rem;padding:2px 10px;">{{ $exitHistory->count() }}
                records</span>
        </div>
        <div class="table-responsive">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reason</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exitHistory as $history)
                    <tr>
                        <td>
                            {{ $history->created_at->format('d M Y') }}
                            <small class="text-muted d-block">{{ $history->created_at->format('g:i A') }}</small>
                        </td>
                        <td>{{ ucwords(str_replace('_', ' ', $history->exit_reason ?? 'N/A')) }}</td>
                        <td>
                            <span class="badge-status badge-{{ $history->exit_status }}"
                                style="padding:2px 12px;font-size:0.7rem;">
                                {{ ucwords(str_replace('_', ' ', $history->exit_status)) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- NO ACTIVE EXIT --}}
    @if(!$hasActiveExit)
        @if($exitPolicy)
        <div class="card-modern fade-up delay-2" style="border-radius:0 0 var(--radius) var(--radius);">
            <div class="empty-state">
                <div class="icon"><i class="fas fa-door-open"></i></div>
                <h5>No Active Exit Process</h5>
                <p>You don't have any active resignation or exit process.</p>
                <a href="{{ route('employee.exit.resignation.form') }}" class="btn-primary-modern">
                    <i class="fas fa-pen me-2"></i> Submit Resignation
                </a>
            </div>
        </div>
        @endif
    @endif

</div>
@endsection