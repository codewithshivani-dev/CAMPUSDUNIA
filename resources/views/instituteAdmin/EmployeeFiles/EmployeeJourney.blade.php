@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')
@section('content')

<style>
/* ============================================
   EMPLOYEE JOURNEY - REDESIGNED HRMS STYLE
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
    width: 88px;
    height: 88px;
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

/* --- Individual Summary Cards (separate, not one box) --- */
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

/* --- Timeline / Stage Cards --- */
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

/* --- Tabs (Overview / Documents only for Joining) --- */
.ej-tabs {
    display: flex;
    gap: 4px;
    border-bottom: 2px solid var(--ej-border);
    margin-bottom: 20px;
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
    from {
        opacity: 0;
        transform: translateY(6px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* --- Sub-step accordion inside Joining Overview --- */
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

.ej-substep-icon.c1 {
    background: var(--ej-primary);
}

.ej-substep-icon.c2 {
    background: var(--ej-success);
}

.ej-substep-icon.c3 {
    background: var(--ej-info);
}

.ej-substep-icon.c4 {
    background: var(--ej-warning);
}

.ej-substep-icon.c5 {
    background: #db2777;
}

.ej-substep-icon.c6 {
    background: #10b981;
}

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

/* --- Data Tables --- */
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

.ej-table-wrap {
    border: 1px solid var(--ej-border);
    border-radius: 0.75rem;
    overflow: hidden;
    overflow-x: auto;
}

/* --- Info grid (small key-value chips) --- */
/* --- Info grid (small key-value chips) --- */
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

/* --- Document Grid --- */
.ej-doc-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
    gap: 12px;
}

.ej-doc-card {
    border: 1px solid var(--ej-border);
    border-radius: 0.75rem;
    padding: 14px 16px;
    background: #fff;
}

.ej-doc-card .doc-top {
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.ej-doc-card .doc-icon {
    font-size: 22px;
    color: var(--ej-primary);
    margin-top: 2px;
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

/* --- Exit stage list (static) --- */
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

/* --- Asset static block --- */
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
}
/* --- Asset Allocation: Static Info Sections --- */
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

/* Eligible assets */
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

/* Allocation process (numbered steps) */
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
/* --- Compact status banner (replaces heavy empty-state when info follows) --- */
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
</style>

<div class="container-fluid">

    <!-- Page Header -->
    <div class="ej-page-header">
        <div>
            <h3 class="mb-0"><i class="fas fa-road text-primary"></i> Employee Journey</h3>
            <p class="text-muted small mb-0">Complete employee lifecycle from onboarding to exit</p>
        </div>
        <a href="{{ route('employees.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Back to Employees
        </a>
    </div>

    <!-- Profile Card -->
    <div class="ej-profile-card">
        @if($employee->profile_photo)
        <img src="{{ asset('image/' . $employee->profile_photo) }}" alt="{{ $employee->name }}" class="ej-avatar">
        @else
        <div class="ej-avatar-fallback">{{ strtoupper(substr($employee->name, 0, 1)) }}</div>
        @endif
        <div>
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
            </div>
        </div>
    </div>

    <!-- SEPARATE SUMMARY CARDS (3 individual cards, not one box) -->
    <div class="ej-summary-row">
        <div class="ej-summary-card type">
            <div class="ej-summary-icon"><i class="fas fa-user-tag"></i></div>
            <div>
                <div class="ej-summary-label">Employment Type</div>
                <div class="ej-summary-value">
                    @if($summaryData['employment_type'] === 'Probation-Period')
                    <span class="ej-badge pending">Probation</span>
                    @else
                    <span class="ej-badge completed">{{ $summaryData['employment_type'] }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="ej-summary-card dept">
            <div class="ej-summary-icon"><i class="fas fa-building"></i></div>
            <div>
                <div class="ej-summary-label">Department</div>
                <div class="ej-summary-value">{{ $summaryData['department'] }}</div>
            </div>
        </div>
        <div class="ej-summary-card ctc">
            <div class="ej-summary-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div>
                <div class="ej-summary-label">Current CTC</div>
                <div class="ej-summary-value">{{ $summaryData['ctc'] }}</div>
            </div>
        </div>
    </div>

    <!-- PROGRESS -->
    @php
    $totalStages = count($stages);
    $completedStages = 0;
    foreach ($stages as $stage) {
    if ($stage['status'] === 'completed') $completedStages++;
    }
    $progressPercentage = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;
    $progressStatus = $progressPercentage == 100 ? 'Completed' : ($progressPercentage > 0 ? 'In Progress' : 'Not
    Started');
    @endphp
    <div class="ej-progress-wrapper">
        <span class="ej-progress-label"><i class="fas fa-chart-line text-primary"></i> Overall Journey Progress</span>
        <span class="ej-progress-pct">{{ $progressPercentage }}%</span>
        <div class="ej-progress-track">
            <div class="ej-progress-fill" style="width: {{ $progressPercentage }}%;"></div>
        </div>
        <span
            class="ej-badge {{ $progressPercentage == 100 ? 'completed' : 'in-progress' }}">{{ $progressStatus }}</span>
        <small class="text-muted">{{ $completedStages }} of {{ $totalStages }} stages completed</small>
    </div>

    <!-- TIMELINE -->
    <div>
        @foreach($stages as $stageKey => $stage)
        @php
        $stageNumber = $loop->iteration;
        $statusClass = $stage['status'] === 'completed' ? 'completed' : ($stage['status'] === 'current' ? 'in-progress'
        : 'pending');
        $stageDate = $stage['date'] ?? null;
        $statusLabel = $stage['status_label'] ?? ($stage['status'] === 'completed' ? 'Completed' : ($stage['status'] ===
        'current' ? 'In Progress' : 'Pending'));
        $stageIcon = $stage['icon'] ?? 'fas fa-circle';
        $numberColor = $stage['color'] ?? 'primary';
        @endphp
        <div class="ej-stage-card" id="stage-{{ $stageKey }}">
            <div class="ej-stage-header" onclick="ejToggleStage('ejBody-{{ $stageKey }}')">
                <div class="ej-stage-title">
                    <span class="ej-stage-number {{ $numberColor }}">{{ $stageNumber }}</span>
                    <i class="{{ $stageIcon }}"></i>
                    {{ $stage['title'] ?? 'Stage' }}
                    @if($stageDate)
                    <span class="ej-badge pending"><i class="fas fa-calendar-alt me-1"></i>{{ $stageDate }}</span>
                    @endif
                </div>
                <div class="ej-stage-right">
                    <span class="ej-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    <i class="fas fa-chevron-down text-muted"></i>
                </div>
            </div>

            <div class="ej-stage-body {{ $loop->first ? '' : 'ej-hidden' }}" id="ejBody-{{ $stageKey }}"
                style="{{ $loop->first ? '' : 'display:none;' }}">

                {{-- ============ STAGE 1: ONBOARDING ============ --}}
                @if($stageKey === 'onboarding')
                @if(isset($stage['tabs']) && count($stage['tabs']) > 0)
                <div class="ej-tabs">
                    @php $ti = 0; @endphp
                    @foreach($stage['tabs'] as $tabKey => $tab)
                    <button class="ej-tab {{ $ti === 0 ? 'active' : '' }}" data-stage="{{ $stageKey }}"
                        data-tab="{{ $tabKey }}">
                        <i class="{{ $tab['icon'] ?? 'fas fa-circle' }}"></i> {{ $tab['title'] }}
                    </button>
                    @php $ti++; @endphp
                    @endforeach
                </div>
                @php $ti = 0; @endphp
                @foreach($stage['tabs'] as $tabKey => $tab)
                <div class="ej-tab-content {{ $ti === 0 ? 'active' : '' }}" data-stage="{{ $stageKey }}"
                    data-tab="{{ $tabKey }}">
                    @if(isset($tab['data']) && is_array($tab['data']))
                    <div class="ej-info-grid">
                        @foreach($tab['data'] as $label => $value)
                        <div class="ej-info-chip">
                            <span class="label">{{ $label }}</span>
                            <span class="value">{!! $value !!}</span>
                        </div>
                        @endforeach
                    </div>
                    @elseif(isset($tab['documents']) && count($tab['documents']) > 0)
                    @include('instituteAdmin.EmployeeFiles.partials.document-grid', ['documents' => $tab['documents']])
                    @else
                    <div class="ej-empty"><i class="fas fa-inbox"></i>
                        <p>No data available.</p>
                    </div>
                    @endif
                </div>
                @php $ti++; @endphp
                @endforeach
                @endif

                {{-- ============ STAGE 2: JOINING - Overview + Documents only ============ --}}
                @elseif($stageKey === 'joining')
                <div class="ej-tabs">
                    <button class="ej-tab active" data-stage="joining" data-tab="overview">
                        <i class="fas fa-list-check"></i> Overview
                    </button>
                    <button class="ej-tab" data-stage="joining" data-tab="documents">
                        <i class="fas fa-file-alt"></i> Documents
                    </button>
                </div>

                {{-- OVERVIEW TAB: 5 sub-steps, each with Assigned/Not Assigned badge --}}
                <div class="ej-tab-content active" data-stage="joining" data-tab="overview">
                    @php
                    $subSteps = [
                    ['key' => 'department', 'title' => 'A. Department', 'icon' => 'fas fa-building', 'cls' => 'c1'],
                    ['key' => 'team_members', 'title' => 'B. Team Members', 'icon' => 'fas fa-users', 'cls' => 'c2'],
                    ['key' => 'reporting_manager', 'title' => 'C. Reporting Manager', 'icon' => 'fas fa-users', 'cls' => 'c3'],
                    ['key' => 'salary_structure', 'title' => 'D. Salary Structure', 'icon' => 'fas fa-money-bill-wave',
                    'cls' => 'c4'],
                    ['key' => 'leave_quota', 'title' => 'E. Leave Quota', 'icon' => 'fas fa-calendar-alt', 'cls' =>
                    'c5'],
                    ['key' => 'reimbursement_policy', 'title' => 'F. Reimbursement Policy', 'icon' => 'fas fa-receipt',
                    'cls' => 'c6'],
                    ];
                    @endphp

                    @foreach($subSteps as $idx => $sub)
                    @php
                    $subTab = $stage['tabs'][$sub['key']] ?? null;
                    $data = $subTab['data'] ?? [];
                    $isAssigned = $data['is_assigned'] ?? false;
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
                        <div class="ej-substep-body {{ $idx === 0 ? '' : 'ej-hidden' }}"
                            id="ejSub-joining-{{ $sub['key'] }}">

                            {{-- A. DEPARTMENT - tabular history --}}
                            @if($sub['key'] === 'department')
                            @if($isAssigned)
                            <div class="ej-info-grid mb-3">
                                <div class="ej-info-chip"><span class="label"> Joining Date</span><span
                                        class="value">{{ $data['DOJ'] ?? 'N/A' }}</span></div>
                                <div class="ej-info-chip"><span class="label"> Category</span><span
                                        class="value">{{ $data['department_category'] ?? 'N/A' }}</span></div>
                                <div class="ej-info-chip"><span class="label"> Department</span><span
                                        class="value">{{ $data['current_department'] ?? 'N/A' }}</span></div>
                                <div class="ej-info-chip"><span class="label"> Designation</span><span
                                        class="value">{{ $data['current_designation'] ?? 'N/A' }}</span></div>

                            </div>
                            @if(!empty($data['history']))
                            <div class="ej-table-wrap">
                                <table class="ej-table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>From Dept</th>
                                            <th>To Dept</th>
                                            <th class="d-none">From Designation</th>
                                            <th class="d-none">To Designation</th>
                                            <th>Performed By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['history'] as $h)
                                        <tr>
                                            <td>{{ $h['date'] }}</td>
                                            <td>{{ $h['from_department'] }}</td>
                                            <td><strong>{{ $h['to_department'] }}</strong></td>
                                            <td class="d-none">{{ $h['from_designation'] }}</td>
                                            <td class="d-none"><strong>{{ $h['to_designation'] }}</strong></td>
                                            <td>{{ $h['performed_by'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            @else
                            <div class="ej-empty"><i class="fas fa-building"></i>
                                <p>{{ $data['message'] ?? 'No department assigned yet.' }}</p>
                            </div>
                            @endif

                            {{-- B. TEAM MEMBERS - tabular, matched by department id --}}
                            @elseif($sub['key'] === 'team_members')
                            @if($isAssigned && !empty($data['members']) && count($data['members']) > 0)
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
                                        @foreach($data['members'] as $member)
                                        <tr>
                                            <td>{{ $member->name ?? 'N/A' }}</td>
                                            <td>{{ $member->designation ?? 'N/A' }}</td>
                                            <td>{{ $member->employee_code ?? 'N/A' }}</td>
                                            <td>{{ isset($member->doj) ? Carbon\Carbon::parse($member->doj)->format('d M, Y') : 'N/A' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="ej-empty"><i class="fas fa-users"></i>
                                <p>{{ $data['message'] ?? 'No team members found.' }}</p>
                            </div>
                            @endif

                             {{-- B. Reporting Manager - tabular, matched by department id --}}
                            @elseif($sub['key'] === 'reporting_manager')
                            @if($isAssigned && !empty($data['members']) && count($data['members']) > 0)
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
                                        @foreach($data['members'] as $member)
                                        <tr>
                                            <td>{{ $member->name ?? 'N/A' }}</td>
                                            <td>{{ $member->designation ?? 'N/A' }}</td>
                                            <td>{{ $member->employee_code ?? 'N/A' }}</td>
                                            <td>{{ isset($member->doj) ? Carbon\Carbon::parse($member->doj)->format('d M, Y') : 'N/A' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="ej-empty"><i class="fas fa-users"></i>
                                <p>{{ $data['message'] ?? 'No Reporting Manager found.' }}</p>
                            </div>
                            @endif

                            {{-- C. SALARY STRUCTURE --}}
                            @elseif($sub['key'] === 'salary_structure')
                            @if($isAssigned)
                            @if(!empty($data['current']))
                            <div class="ej-info-grid mb-3">
                                <div class="ej-info-chip"><span class="label">Total CTC (Annual)</span><span
                                        class="value">₹{{ number_format($data['current']->total_ctc_annual ?? 0, 2) }}</span>
                                </div>
                                <div class="ej-info-chip"><span class="label">Basic (Monthly)</span><span
                                        class="value">₹{{ number_format($data['current']->basic_salary_monthly ?? 0, 2) }}</span>
                                </div>
                            </div>
                            @endif
                            @if(!empty($data['history']))
                            <div class="ej-table-wrap">
                                <table class="ej-table">
                                    <thead>
                                        <tr>
                                            <th>Salary Structure Id</th>
                                            <th>FY</th>
                                            <th>Basic (Monthly)</th>
                                            <th>Basic (Annual)</th>
                                            <th>Fixed CTC</th>
                                            <th>Total CTC</th>
                                            <th>Effective Date</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['history'] as $h)
                                        <tr>
                                            <td>{{ $h['id'] }}</td>
                                            <td>{{ $h['financial_year'] }}</td>
                                            <td>{{ $h['basic_monthly'] }}</td>
                                            <td>{{ $h['basic_annual'] }}</td>
                                            <td>{{ $h['fixed_ctc_annual'] }}</td>
                                            <td><strong>{{ $h['total_ctc_annual'] }}</strong></td>
                                            <td>{{ $h['effective_date'] }}</td>
                                            <td>{{ $h['change_type'] }}</td>
                                            <td>
                                                @if(!empty($h['is_active']))
                                                    <span class="ej-badge completed">
                                                        <i class="fas fa-check-circle me-1"></i> Active
                                                    </span>
                                                @else
                                                    <span class="ej-badge not-assigned">
                                                        <i class="fas fa-times-circle me-1"></i> Inactive
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            @else
                            <div class="ej-empty"><i class="fas fa-money-bill-wave"></i>
                                <p>{{ $data['message'] ?? 'No salary structure assigned yet.' }}</p>
                            </div>
                            @endif

                            {{-- D. LEAVE QUOTA --}}
                            @elseif($sub['key'] === 'leave_quota')
                            @if($isAssigned && !empty($data['quotas']) && count($data['quotas']) > 0)
                            <div class="ej-table-wrap mb-3">
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
                                        @foreach($data['quotas'] as $q)
                                        <tr>
                                            <td><strong>{{ $q['leave_type'] }}</strong></td>
                                            <td>{{ $q['total_allocated'] }}</td>
                                            <td>{{ $q['used'] }}</td>
                                            <td>{{ $q['remaining'] }}</td>
                                            <td>{{ $q['assigned_date'] }}</td>
                                            <td>
                                                <span
                                                    class="ej-badge {{ $q['assignment_type'] === 'department' ? 'in-progress' : 'completed' }}">
                                                    {{ ucfirst($q['assignment_type'] ?? 'Employee') }}
                                                </span>
                                            </td>
                                            <td>{{ $q['allocation_period'] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if(!empty($data['has_view_button']))
                            <button class="btn btn-primary btn-sm" onclick="viewLeaveQuota()">
                                <i class="fas fa-eye"></i> View
                            </button>
                            @endif
                            @else
                            <div class="ej-empty">
                                <i class="fas fa-calendar-alt"></i>
                                <p>{{ $data['message'] ?? 'No leave quota assigned yet.' }}</p>
                            </div>
                            @endif

                            {{-- E. REIMBURSEMENT POLICY --}}
                            @elseif($sub['key'] === 'reimbursement_policy')
                            @if(!empty($data['policy']))
                            <div class="ej-info-grid mb-3">
                                <div class="ej-info-chip"><span class="label">Policy</span><span
                                        class="value">{{ $data['policy']['name'] ?? 'N/A' }}</span></div>
                                <div class="ej-info-chip"><span class="label">Submission Deadline</span><span
                                        class="value">{{ $data['policy']['submission_deadline'] ?? 'N/A' }}</span></div>
                                <div class="ej-info-chip"><span class="label">Approval Process</span><span
                                        class="value">{{ $data['policy']['approval_process'] ?? 'N/A' }}</span></div>
                            </div>
                            @if(!empty($data['policy']['categories']))
                            <div class="ej-table-wrap">
                                <table class="ej-table">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Limit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['policy']['categories'] as $cat => $limit)
                                        <tr>
                                            <td>{{ $cat }}</td>
                                            <td>{{ $limit }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            @else
                            <div class="ej-empty"><i class="fas fa-receipt"></i>
                                <p>No reimbursement policy available.</p>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- DOCUMENTS TAB --}}
                <div class="ej-tab-content" data-stage="joining" data-tab="documents">
                    @php $docsTab = $stage['tabs']['documents'] ?? null; @endphp
                    @if($docsTab && !empty($docsTab['documents']))
                    @include('instituteAdmin.EmployeeFiles.partials.document-grid', ['documents' =>
                    $docsTab['documents']])
                    @else
                    <div class="ej-empty"><i class="fas fa-inbox"></i>
                        <p>{{ $docsTab['message'] ?? 'No joining documents available.' }}</p>
                    </div>
                    @endif
                </div>

                {{-- ============ STAGE 3: ASSET ALLOCATION - Static ============ --}}
                @elseif($stageKey === 'asset_allocation')

                @php
                $assetTab = $stage['tabs']['overview']['data'] ?? [];
                @endphp

                @if(!empty($assetTab['has_assets']))

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
                            @foreach($assetTab['allocations'] as $a)
                            <tr>
                                <td>{{ $a->asset->name ?? 'N/A' }}</td>
                                <td>{{ $a->asset->type ?? 'N/A' }}</td>
                                <td>{{ $a->asset->serial_number ?? 'N/A' }}</td>
                                <td>{{ $a->created_at ? Carbon\Carbon::parse($a->created_at)->format('d M, Y') : 'N/A' }}
                                </td>
                                <td>
                                    <span class="ej-badge completed">
                                        {{ ucfirst($a->status ?? 'Active') }}
                                    </span>
                                </td>
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
                        <div class="ej-asset-alert-text">{{ $assetTab['message'] ?? 'No assets allocated yet.' }} Reference details for this stage are shown below.</div>
                    </div>
                </div>

                @if(!empty($assetTab['overview']))
                <div class="ej-asset-section">
                    <div class="ej-asset-section-title">
                        <i class="fas fa-info-circle"></i> Overview
                    </div>
                    <div class="ej-info-grid">
                        @foreach($assetTab['overview'] as $label => $value)
                        <div class="ej-info-chip">
                            <span class="label">{{ $label }}</span>
                            <span class="value">{{ $value }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($assetTab['eligible_assets']))
                <div class="ej-asset-section">
                    <div class="ej-asset-section-title">
                        <i class="fas fa-laptop"></i> Eligible Assets
                    </div>
                    <div class="ej-eligible-grid">
                        @foreach($assetTab['eligible_assets'] as $asset)
                        <div class="ej-eligible-item">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ $asset }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($assetTab['allocation_process']))
                <div class="ej-asset-section">
                    <div class="ej-asset-section-title">
                        <i class="fas fa-project-diagram"></i> Allocation Process
                    </div>
                    <div class="ej-process-list">
                        @foreach($assetTab['allocation_process'] as $index => $step)
                        <div class="ej-process-step">
                            <span class="ej-process-num">{{ $index + 1 }}</span>
                            <span>{{ $step }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($assetTab['policies']))
                <div class="ej-asset-section">
                    <div class="ej-asset-section-title">
                        <i class="fas fa-shield-alt"></i> Asset Policies
                    </div>
                    <div class="ej-policy-list">
                        @foreach($assetTab['policies'] as $policy)
                        <div class="policy-row">
                            <i class="fas fa-check text-success"></i>
                            <span>{{ $policy }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($assetTab['return_policy']))
                <div class="ej-asset-section">
                    <div class="ej-asset-section-title">
                        <i class="fas fa-undo"></i> Return Policy
                    </div>
                    <div class="ej-policy-list">
                        @foreach($assetTab['return_policy'] as $policy)
                        <div class="policy-row">
                            <i class="fas fa-arrow-right text-warning"></i>
                            <span>{{ $policy }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @endif

                {{-- ============ STAGE 4: PROMOTIONS ============ --}}
                @elseif($stageKey === 'promotions')
                <div class="ej-tabs">
                    <button class="ej-tab active" data-stage="promotions" data-tab="history">
                        <i class="fas fa-history"></i> Promotion History
                    </button>
                    <button class="ej-tab" data-stage="promotions" data-tab="documents">
                        <i class="fas fa-file-alt"></i> Documents
                    </button>
                </div>
                @php $promoData = $stage['tabs']['history']['data'] ?? []; @endphp
                <div class="ej-tab-content active" data-stage="promotions" data-tab="history">
                    @if(!empty($promoData['promotions']))
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
                                    <th class="d-none">Documents</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($promoData['promotions'] as $p)
                                <tr>
                                    <td><span class="ej-badge in-progress">{{ $p['id'] }}</span></td>
                                    <td><span class="ej-badge in-progress">{{ $p['promotion_id'] }}</span></td>
                                    <td>
                                        <i class="{{ $p['icon'] ?? 'fas fa-edit' }} me-1"
                                            style="color: var(--ej-{{ $p['color'] ?? 'info' }}, #0891b2);"></i>
                                        {{ $p['type'] }}
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <i class="fas fa-arrow-right text-danger me-1" style="font-size: 10px;"></i>
                                            {{ $p['from'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-success">
                                            <i class="fas fa-arrow-right text-success me-1"
                                                style="font-size: 10px;"></i>
                                            {{ $p['to'] }}
                                        </strong>
                                    </td>
                                    <td>{{ $p['performed_by'] }}</td>
                                    <td>{{ $p['date'] }}</td>
                                    <td class="d-none">
                                        @if(!empty($p['documents']))
                                        @foreach($p['documents'] as $doc)
                                        <span class="ej-badge pending">
                                            <i class="fas fa-file me-1"></i>{{ $doc->name ?? 'Document' }}
                                        </span>
                                        @endforeach
                                        @else
                                        <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="ej-empty">
                        <i class="fas fa-inbox"></i>
                        <p>{{ $promoData['message'] ?? 'No promotions recorded yet.' }}</p>
                    </div>
                    @endif
                </div>
                <div class="ej-tab-content" data-stage="promotions" data-tab="documents">
                    @php $promoDocs = $stage['tabs']['documents'] ?? null; @endphp
                    @if($promoDocs && !empty($promoDocs['documents']))
                    @include('instituteAdmin.EmployeeFiles.partials.document-grid', ['documents' =>
                    $promoDocs['documents']])
                    @else
                    <div class="ej-empty">
                        <i class="fas fa-inbox"></i>
                        <p>{{ $promoDocs['message'] ?? 'No promotion documents available.' }}</p>
                    </div>
                    @endif
                </div>


                {{-- ============ STAGE 5: EXIT - Static ============ --}}
                @elseif($stageKey === 'exit')
                @php $exitData = $stage['tabs']['journey']['data'] ?? []; @endphp
                @if(!empty($exitData['stages']))
                <div class="ej-exit-list">
                    @foreach($exitData['stages'] as $es)
                    @php
                    $esStatus = $es['status'] ?? 'pending';
                    $esClass = $esStatus === 'completed' ? 'completed' : ($esStatus === 'in-progress' ? 'in-progress' :
                    '');
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
                        <span
                            class="ej-badge {{ $esClass ?: 'pending' }}">{{ $es['status_label'] ?? ucfirst($esStatus) }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="ej-empty"><i class="fas fa-inbox"></i>
                    <p>No exit journey data available.</p>
                </div>
                @endif
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Document Request Modal -->
<div class="modal fade" id="documentRequestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-alt me-2"></i> Request Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-file-pdf" style="font-size: 48px; color: #4f46e5;"></i>
                    <h5 id="modalDocumentName" class="mt-2">Document Name</h5>
                    <span class="badge bg-info text-white" id="modalDocumentType">Document</span>
                </div>
                <form id="documentRequestForm">
                    @csrf
                    <input type="hidden" name="template_id" id="modalTemplateId">
                    <input type="hidden" name="employee_id" id="modalEmployeeId">
                    <div class="mb-3">
                        <label for="request_reason" class="form-label">Reason for Request <span
                                class="text-muted">(Optional)</span></label>
                        <textarea class="form-control" id="request_reason" name="request_reason" rows="3"
                            placeholder="Please provide any additional information..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                        class="fas fa-times me-1"></i> Cancel</button>
                <button type="button" class="btn btn-primary" id="submitRequestBtn"><i
                        class="fas fa-paper-plane me-1"></i> Submit Request</button>
            </div>
        </div>
    </div>
</div>

<!-- Document View Modal -->
<div class="modal fade" id="documentViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-alt me-2"></i><span id="documentViewTitle">Document</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="documentViewContainer">
                <div class="text-center p-5" id="documentLoading">
                    <div class="spinner-border text-primary" role="status"><span
                            class="visually-hidden">Loading...</span></div>
                    <p class="mt-3 text-muted">Loading document...</p>
                </div>
                <div id="documentContent" style="display: none; min-height: 400px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                        class="fas fa-times me-1"></i> Close</button>
                <button type="button" class="btn btn-success" id="downloadFromViewBtn"
                    onclick="downloadCurrentDocument()"><i class="fas fa-download me-1"></i> Download</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// STAGE TOGGLE
function ejToggleStage(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.style.display = (el.style.display === 'none') ? '' : 'none';
}

// SUB-STEP TOGGLE (accordion inside Joining Overview)
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
        $stageBody.find('.ej-tab-content[data-stage="' + stage + '"][data-tab="' + tab + '"]').addClass(
            'active');
    });
});

// DOCUMENT FUNCTIONS
let currentDocumentData = {
    filePath: null,
    documentName: null
};

function openRequestModal(templateId, employeeId, documentName, documentType) {
    document.getElementById('modalTemplateId').value = templateId || '';
    document.getElementById('modalEmployeeId').value = employeeId || '';
    document.getElementById('modalDocumentName').textContent = documentName || 'Document';
    document.getElementById('modalDocumentType').textContent = documentType || 'Document';
    document.getElementById('request_reason').value = '';
    new bootstrap.Modal(document.getElementById('documentRequestModal')).show();
}

function viewDocument(filePath, documentName) {
    if (!filePath) {
        Swal.fire({
            icon: 'error',
            title: 'Document Not Found',
            text: 'The document file could not be found.'
        });
        return;
    }
    currentDocumentData.filePath = filePath;
    currentDocumentData.documentName = documentName || 'Document';

    document.getElementById('documentViewTitle').textContent = documentName || 'Document';
    document.getElementById('documentLoading').style.display = 'flex';
    document.getElementById('documentContent').style.display = 'none';

    const fileUrl = '{{ url("/image") }}/' + filePath;
    const container = document.getElementById('documentContent');
    container.innerHTML = `
        <div style="padding: 20px; text-align: center; background: #f8fafc; min-height: 400px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <i class="fas fa-file-pdf" style="font-size: 60px; color: #dc2626;"></i>
            <h5 class="mt-3">${documentName || 'Document'}</h5>
            <p class="text-muted">Click the download button to view or save this document.</p>
            <div class="mt-3">
                <button class="btn btn-success" onclick="downloadCurrentDocument()"><i class="fas fa-download me-1"></i> Download</button>
                <button class="btn btn-primary ms-2" onclick="window.open('${fileUrl}', '_blank')"><i class="fas fa-external-link-alt me-1"></i> Open in New Tab</button>
            </div>
        </div>`;
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

function viewLeaveQuota() {
    Swal.fire({
        icon: 'info',
        title: 'Leave Quota Details',
        text: 'Full leave quota details will be displayed here.',
        confirmButtonText: '<i class="fas fa-check me-1"></i> OK'
    }).then((result) => {
        if (result.isConfirmed) {
            window.open('/leaves/quota-management', '_blank');
        }
    });
}

document.getElementById('submitRequestBtn')?.addEventListener('click', function() {
    const templateId = document.getElementById('modalTemplateId').value;
    const employeeId = document.getElementById('modalEmployeeId').value;
    const reason = document.getElementById('request_reason').value.trim();

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
            const btn = this;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Submitting...';

            $.ajax({
                url: '{{ route("employee.document.request.store") }}',
                type: 'POST',
                data: {
                    template_id: templateId,
                    employee_id: employeeId,
                    request_reason: reason,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        bootstrap.Modal.getInstance(document.getElementById(
                            'documentRequestModal')).hide();
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
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            });
        }
    });
});
</script>
@endsection