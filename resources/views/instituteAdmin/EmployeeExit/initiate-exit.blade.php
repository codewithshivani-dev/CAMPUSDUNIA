{{-- resources/views/instituteAdmin/EmployeeExit/initiate-exit.blade.php --}}

@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'Initiate Employee Exit')

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
    --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
    --shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
    --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
    --radius: 12px;
    --radius-sm: 8px;
    --transition: all 0.2s ease;
}

.exit-wrapper {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 4px;
}

/* ===== HEADER ===== */
.exit-header-modern {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border-radius: var(--radius) var(--radius) 0 0;
    padding: 24px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    position: relative;
    overflow: hidden;
}

.exit-header-modern::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}

.exit-header-modern .header-left {
    display: flex;
    align-items: center;
    gap: 16px;
    z-index: 1;
}

.exit-header-modern .header-icon {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.15);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #fff;
    backdrop-filter: blur(4px);
}

.exit-header-modern h4 {
    color: #fff;
    font-weight: 700;
    font-size: 1.25rem;
    margin: 0;
    letter-spacing: -0.3px;
}

.exit-header-modern .subtitle {
    color: rgba(255,255,255,0.8);
    font-size: 0.85rem;
    margin: 0;
}

.exit-header-modern .btn-back {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.2);
    color: #fff;
    padding: 8px 18px;
    border-radius: var(--radius-sm);
    font-size: 0.875rem;
    font-weight: 500;
    transition: var(--transition);
    backdrop-filter: blur(4px);
    z-index: 1;
    text-decoration: none;
}

.exit-header-modern .btn-back:hover {
    background: rgba(255,255,255,0.25);
    color: #fff;
    transform: translateY(-1px);
}

/* ===== EMPLOYEE CARD ===== */
.employee-card-modern {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-top: none;
    padding: 24px 28px;
    box-shadow: var(--shadow-sm);
}

.employee-card-modern .avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 700;
    flex-shrink: 0;
}

.employee-card-modern .emp-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
}

.employee-card-modern .emp-code {
    font-size: 0.85rem;
    color: var(--gray-500);
}

.info-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 6px 20px;
    margin-top: 10px;
}

.info-grid-modern .info-item .label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--gray-500);
    font-weight: 600;
}

.info-grid-modern .info-item .value {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-800);
}

/* ===== STATUS BADGES ===== */
.badge-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 16px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}
.badge-draft { background: var(--gray-200); color: var(--gray-600); }
.badge-pending_approval { background: var(--warning-bg); color: #92400e; }
.badge-notice_period { background: #dbeafe; color: #1e40af; }
.badge-exited { background: var(--danger-bg); color: #991b1b; }
.badge-cancelled { background: var(--gray-200); color: var(--gray-600); }
.badge-approved { background: var(--success-bg); color: #065f46; }

/* ===== POLICY SELECTION CARD ===== */
.policy-selection-modern {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-top: none;
    padding: 20px 28px;
}

.policy-selection-modern .section-title {
    font-weight: 700;
    font-size: 1rem;
    color: var(--gray-800);
    margin-bottom: 16px;
}

.policy-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
}

.policy-card-option {
    border: 2px solid var(--gray-200);
    border-radius: var(--radius-sm);
    padding: 16px 20px;
    cursor: pointer;
    transition: var(--transition);
    position: relative;
    background: #fff;
}

.policy-card-option:hover {
    border-color: var(--primary-light);
    box-shadow: var(--shadow-md);
}

.policy-card-option.selected {
    border-color: var(--primary);
    background: var(--primary-bg);
    box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}

.policy-card-option .policy-radio {
    position: absolute;
    top: 12px;
    right: 12px;
}

.policy-card-option .policy-name {
    font-weight: 700;
    color: var(--gray-800);
    font-size: 0.95rem;
}

.policy-card-option .policy-code {
    font-size: 0.75rem;
    color: var(--gray-500);
}

.policy-card-option .policy-exit-type {
    display: inline-block;
    padding: 2px 12px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    margin-top: 4px;
}

.policy-card-option .policy-details {
    font-size: 0.8rem;
    color: var(--gray-600);
    margin-top: 8px;
}

.policy-card-option .policy-details .badge-item {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 10px;
    font-size: 0.65rem;
    font-weight: 500;
    background: var(--gray-100);
    color: var(--gray-600);
    margin: 2px 4px 2px 0;
}

/* ===== ACTIVE EXIT CARD ===== */
.active-exit-card {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-top: none;
    padding: 20px 28px;
}

.active-exit-card .status-header {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 16px;
}

/* ===== TASK SUMMARY ITEMS ===== */
.task-summary-item {
    transition: var(--transition);
}
.task-summary-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* ===== FORM SECTION ===== */
.form-section-modern {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-top: none;
    border-radius: 0 0 var(--radius) var(--radius);
    padding: 28px 32px;
    box-shadow: var(--shadow-sm);
}

.form-section-modern .section-title {
    font-weight: 700;
    font-size: 1rem;
    color: var(--gray-800);
    margin-bottom: 20px;
}

.form-group-modern .form-label {
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--gray-700);
    margin-bottom: 4px;
}

.form-group-modern .form-control {
    border: 1.5px solid var(--gray-200);
    border-radius: var(--radius-sm);
    padding: 10px 14px;
    font-size: 0.9rem;
    transition: var(--transition);
    background: #fff;
}

.form-group-modern .form-control:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
}

.form-group-modern .form-control:disabled {
    background: var(--gray-100);
    cursor: not-allowed;
}

/* ===== BUTTONS ===== */
.btn-primary-modern {
    background: #4f46e5;
    border: none;
    color: #fff;
    padding: 10px 28px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 0.9rem;
    transition: var(--transition);
}
.btn-primary-modern:hover {
    background: #4338ca;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.btn-danger-modern {
    background: #ef4444;
    border: none;
    color: #fff;
    padding: 10px 28px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 0.9rem;
    transition: var(--transition);
}
.btn-danger-modern:hover {
    background: #dc2626;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-success-modern {
    background: #22c55e;
    border: none;
    color: #fff;
    padding: 10px 28px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 0.9rem;
    transition: var(--transition);
}
.btn-success-modern:hover {
    background: #16a34a;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.btn-outline-modern {
    background: transparent;
    border: 1.5px solid var(--gray-200);
    color: var(--gray-600);
    padding: 10px 28px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 0.9rem;
    transition: var(--transition);
}
.btn-outline-modern:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-800);
}

.btn-warning-modern {
    background: #f59e0b;
    border: none;
    color: #fff;
    padding: 10px 28px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 0.9rem;
    transition: var(--transition);
}
.btn-warning-modern:hover {
    background: #d97706;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

/* ===== AUTO-APPROVAL INDICATOR ===== */
.auto-approval-badge {
    background: #d1fae5;
    color: #065f46;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.auto-approval-badge i {
    font-size: 1rem;
}

/* ===== SUMMARY ALERT ===== */
.summary-alert-modern {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-sm);
    padding: 16px 20px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .exit-header-modern { padding: 18px 20px; }
    .employee-card-modern { padding: 18px 20px; }
    .form-section-modern { padding: 20px; }
    .policy-grid { grid-template-columns: 1fr; }
    .info-grid-modern { grid-template-columns: 1fr 1fr; }
    .active-exit-card { padding: 16px; }
}

@media (max-width: 576px) {
    .info-grid-modern { grid-template-columns: 1fr; }
    .exit-header-modern .header-left { flex-wrap: wrap; }
}

/* ===== ANIMATIONS ===== */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}
.fade-up { animation: fadeUp 0.35s ease forwards; }
.delay-1 { animation-delay: 0.05s; }
.delay-2 { animation-delay: 0.1s; }
.delay-3 { animation-delay: 0.15s; }
</style>

<div class="exit-wrapper">
    {{-- HEADER --}}
    <div class="exit-header-modern fade-up">
        <div class="header-left">
            <div class="header-icon">
                <i class="fas fa-door-open"></i>
            </div>
            <div>
                <h4>Initiate Employee Exit</h4>
                <p class="subtitle">Review details and manage the offboarding process</p>
            </div>
        </div>
        <a href="{{ route('employees.index') }}" class="btn-back">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    {{-- EMPLOYEE INFORMATION --}}
    <div class="employee-card-modern fade-up delay-1">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="avatar">
                    {{ strtoupper(substr($employee->name, 0, 2)) }}
                </div>
            </div>
            <div class="col">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <h5 class="emp-name">{{ $employee->name }}</h5>
                    <span class="emp-code"><i class="far fa-id-card me-1"></i> {{ $employee->employee_code }}</span>
                    @if($hasActiveExit && $activeExit)
                        <span class="badge-status badge-{{ $activeExit->exit_status }}">
                            <i class="fas fa-circle" style="font-size: 6px;"></i>
                            {{ $activeExit->status_label }}
                        </span>
                    @endif
                </div>
                <div class="info-grid-modern">
                    <div class="info-item">
                        <div class="label">Department</div>
                        <div class="value">{{ $employee->department_name ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Designation</div>
                        <div class="value">{{ $employee->designation ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Employment Type</div>
                        <div class="value">
                            <span style="background:#dbeafe;color:#1e40af;padding:2px 12px;border-radius:12px;font-size:0.75rem;font-weight:600;">
                                {{ $employee->employment_type ?? '—' }}
                            </span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="label">Joining Date</div>
                        <div class="value">{{ $employee->doj ? \Carbon\Carbon::parse($employee->doj)->format('d M Y') : '—' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Email</div>
                        <div class="value" style="font-size:0.85rem;">{{ $employee->email ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="label">Mobile</div>
                        <div class="value">{{ $employee->mobile_number ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- PENDING RESIGNATION OVERRIDE SECTION                         --}}
    {{-- ============================================================ --}}
    @if(isset($hasPendingResignation) && $hasPendingResignation && isset($pendingResignationDetails))
    <div class="active-exit-card fade-up delay-1" style="border-left:4px solid #f59e0b;background:#fffbeb;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="mb-0" style="font-weight:700;color:#92400e;">
                    <i class="fas fa-clock text-warning me-2"></i>
                    Pending Resignation Detected
                </h6>
                <div class="d-flex flex-wrap gap-3 mt-1">
                    <small class="text-muted">
                        <i class="fas fa-calendar-alt me-1"></i> 
                        Submitted: {{ $pendingResignationDetails['submitted_at'] }}
                    </small>
                    <small class="text-muted">
                        <i class="fas fa-tag me-1"></i> 
                        Reason: {{ $pendingResignationDetails['exit_reason'] }}
                    </small>
                    @if($pendingResignationDetails['exit_notes'])
                        <small class="text-muted">
                            <i class="fas fa-sticky-note me-1"></i> 
                            {{ \Str::limit($pendingResignationDetails['exit_notes'], 50) }}
                        </small>
                    @endif
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-warning" style="font-size:0.75rem;padding:4px 12px;">
                    <i class="fas fa-user me-1"></i> Employee-Initiated
                </span>
                <span class="badge bg-warning text-dark" style="font-size:0.75rem;padding:4px 12px;">
                    <i class="fas fa-hourglass-half me-1"></i> Pending Approval
                </span>
            </div>
        </div>
        
        <div class="mt-3 p-3" style="background:#fef3c7;border-radius:8px;border:1px solid #fde68a;">
            <div class="d-flex align-items-start gap-3">
                <i class="fas fa-exclamation-triangle text-warning mt-1" style="font-size:1.2rem;"></i>
                <div>
                    <p class="mb-1" style="font-weight:600;color:#92400e;">
                        This employee has submitted a resignation that is pending approval.
                    </p>
                    <p class="mb-2" style="color:#78350f;font-size:0.9rem;">
                        As an admin, you can:
                    </p>
                    <ul style="color:#78350f;font-size:0.85rem;margin:0;padding-left:20px;">
                        <li>✅ <strong>Approve the resignation</strong> - The employee will enter the notice period.</li>
                        <li>✅ <strong>Override and initiate a different exit</strong> (Termination/End of Contract/etc.) - 
                            <span style="color:#dc2626;font-weight:500;">The pending resignation will be cancelled.</span>
                        </li>
                        <li>✅ <strong>Reject the resignation</strong> - The exit process will be cancelled.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Override confirmation checkbox --}}
        <div class="mt-3 pt-3 border-top">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="overridePendingResignation" 
                       style="border-color:#f59e0b;width:20px;height:20px;cursor:pointer;">
                <label class="form-check-label" for="overridePendingResignation" style="font-weight:600;color:#92400e;cursor:pointer;">
                    <i class="fas fa-trash-alt me-1" style="color:#dc2626;"></i>
                    I confirm to override the pending resignation and initiate a new exit process
                </label>
            </div>
            <small class="text-muted" style="font-size:0.75rem;">
                <i class="fas fa-info-circle me-1"></i> 
                Checking this will cancel the pending resignation and allow you to initiate a new exit.
            </small>
        </div>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- ACTIVE EXIT PROCESS (Shows for BOTH admin and self-initiated) --}}
    {{-- ============================================================ --}}
    @if($hasActiveExit && $activeExit)
    <div class="active-exit-card fade-up delay-2">
        <div class="status-header">
            <i class="fas fa-clock" style="color:#4f46e5;font-size:1.2rem;"></i>
            <span style="font-weight:600;color:var(--gray-700);">Active Exit Process:</span>
            <span class="badge-status badge-{{ $activeExit->exit_status }}">
                {{ $activeExit->status_label }}
            </span>
            @if(isset($activeExit->initiation_source) && $activeExit->initiation_source === 'admin')
                <span class="badge bg-secondary" style="font-size:0.7rem;">
                    <i class="fas fa-user-shield me-1"></i> Admin Initiated
                </span>
            @else
                <span class="badge bg-info" style="font-size:0.7rem;">
                    <i class="fas fa-user me-1"></i> Self Initiated (Resignation)
                </span>
            @endif
            <span class="badge" style="background:#dbeafe;color:#1e40af;font-size:0.7rem;">
                <i class="fas fa-file-contract me-1"></i> {{ $activeExit->exit_type ?? 'Resignation' }}
            </span>
            @if($activeExit->exit_reason)
                <span class="badge" style="background:#d1fae5;color:#065f46;font-size:0.7rem;">
                    <i class="fas fa-info-circle me-1"></i> {{ ucwords(str_replace('_', ' ', $activeExit->exit_reason)) }}
                </span>
            @endif
        </div>
        
        <div class="row g-3">
            <div class="col-md-6">
                <div class="d-flex flex-wrap gap-3">
                    <div>
                        <small class="text-muted d-block">Notice Period</small>
                        <strong>{{ $activeExit->notice_period_days ?? 'N/A' }} days</strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">Start Date</small>
                        <strong>{{ $activeExit->notice_start_date ? \Carbon\Carbon::parse($activeExit->notice_start_date)->format('d M Y') : 'N/A' }}</strong>
                    </div>
                    <div>
                        <small class="text-muted d-block">End Date</small>
                        <strong>{{ $activeExit->notice_end_date ? \Carbon\Carbon::parse($activeExit->notice_end_date)->format('d M Y') : 'N/A' }}</strong>
                    </div>
                    @if($activeExit->initiation_source === 'employee')
                        <div>
                            <small class="text-muted d-block">Resignation Date</small>
                            <strong>{{ $activeExit->created_at ? \Carbon\Carbon::parse($activeExit->created_at)->format('d M Y') : 'N/A' }}</strong>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                    @if($activeExit->exit_status === 'pending_approval')
                        <button onclick="approveExit({{ $activeExit->id }})" class="btn btn-success-modern">
                            <i class="fas fa-check-circle me-1"></i> Approve
                        </button>
                        <button onclick="rejectExit({{ $activeExit->id }})" class="btn btn-outline-modern" style="border-color:#ef4444;color:#ef4444;">
                            <i class="fas fa-times-circle me-1"></i> Reject
                        </button>
                    @elseif($activeExit->exit_status === 'notice_period' || $activeExit->exit_status === 'approved')
                        <button onclick="completeExitNow({{ $activeExit->id }})" class="btn btn-danger-modern">
                            <i class="fas fa-check-circle me-1"></i> Complete Exit Now
                        </button>
                        <button onclick="cancelExit({{ $activeExit->id }})" class="btn btn-warning-modern">
                            <i class="fas fa-times-circle me-1"></i> Cancel Process
                        </button>
                    @elseif($activeExit->exit_status === 'exited')
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Employee exited on {{ $activeExit->actual_exit_date ? \Carbon\Carbon::parse($activeExit->actual_exit_date)->format('d M Y') : 'N/A' }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        @if($activeExit->exit_notes)
        <div class="mt-2 pt-2 border-top">
            <small class="text-muted">Notes:</small>
            <span>{{ $activeExit->exit_notes }}</span>
        </div>
        @endif
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- TASK ASSIGNMENT SECTION (Shows for ALL active exits)         --}}
    {{-- ============================================================ --}}
    @if($hasActiveExit && $activeExit)
    <div class="active-exit-card fade-up delay-2" style="border-top:2px solid var(--primary);margin-top:-1px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="mb-0" style="font-weight:700;color:var(--gray-800);">
                    <i class="fas fa-tasks text-primary me-2"></i>
                    Exit Tasks & Assignments
                    @if($activeExit->initiation_source === 'employee')
                        <span class="badge bg-info ms-1" style="font-size:0.65rem;">
                            <i class="fas fa-user me-1"></i> Self-Initiated
                        </span>
                    @else
                        <span class="badge bg-secondary ms-1" style="font-size:0.65rem;">
                            <i class="fas fa-user-shield me-1"></i> Admin-Initiated
                        </span>
                    @endif
                </h6>
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Assign tasks to complete the exit process
                </small>
            </div>
            <a href="{{ route('exit.assign.tasks', $activeExit->id) }}" class="btn btn-primary-modern">
                <i class="fas fa-user-plus me-1"></i> 
                {{ $activeExit->exit_status === 'pending_approval' ? 'Assign Tasks (After Approval)' : 'Manage Tasks' }}
            </a>
        </div>
        
        {{-- Show current task assignments summary --}}
        @php
            $taskTypes = [
                'kt' => 'KT',
                'exit_interview' => 'Exit Interview', 
                'asset_clearance' => 'Asset Clearance', 
                'fnf' => 'FNF Settlement'
            ];
            $taskIcons = [
                'kt' => 'fa-chalkboard-teacher', 
                'exit_interview' => 'fa-comments', 
                'asset_clearance' => 'fa-laptop', 
                'fnf' => 'fa-file-invoice-dollar'
            ];
            $taskColors = [
                'kt' => '#4f46e5', 
                'exit_interview' => '#f59e0b', 
                'asset_clearance' => '#06b6d4', 
                'fnf' => '#ef4444'
            ];
            $taskBgColors = [
                'kt' => '#eef2ff', 
                'exit_interview' => '#fef3c7', 
                'asset_clearance' => '#cffafe', 
                'fnf' => '#fee2e2'
            ];
            
            $assignedTasks = App\Models\EmployeeExitTaskAssignment::where('exit_id', $activeExit->id)->get();
            $hasTasks = $assignedTasks->count() > 0;
        @endphp
        
        @if($hasTasks)
            <div class="row g-2 mt-2">
                @foreach($taskTypes as $key => $label)
                    @php
                        $task = $assignedTasks->where('task_type', $key)->first();
                        $statusClass = $task ? ($task->status === 'completed' ? 'success' : ($task->status === 'in-progress' ? 'warning' : 'secondary')) : 'secondary';
                        $statusLabel = $task ? ucfirst($task->status) : 'Not Assigned';
                    @endphp
                    <div class="col-md-3 col-6">
                        <div class="task-summary-item" style="background:{{ $taskBgColors[$key] ?? '#f8fafc' }};border-radius:8px;padding:10px 12px;border-left:3px solid {{ $taskColors[$key] ?? '#4f46e5' }};">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas {{ $taskIcons[$key] }}" style="color:{{ $taskColors[$key] ?? '#4f46e5' }};font-size:14px;"></i>
                                <span style="font-size:0.75rem;font-weight:600;flex:1;">{{ $label }}</span>
                                <span class="badge bg-{{ $statusClass }}" style="font-size:0.6rem;padding:2px 8px;">{{ $statusLabel }}</span>
                            </div>
                            @if($task && $task->assigned_to_name)
                                <div style="font-size:0.65rem;color:var(--gray-500);margin-top:2px;">
                                    <i class="fas fa-user me-1"></i> {{ $task->assigned_to_name }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info mt-2 mb-0" style="font-size:0.85rem;padding:10px 16px;">
                <i class="fas fa-info-circle me-2"></i>
                No tasks have been assigned yet. Click "Manage Tasks" to assign tasks for this exit.
            </div>
        @endif
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- NO ACTIVE EXIT - SHOW POLICY SELECTION                       --}}
    {{-- ============================================================ --}}
    @if(!$hasActiveExit)
        {{-- POLICY SELECTION --}}
        <div class="policy-selection-modern fade-up delay-2">
            <h6 class="section-title">
                <i class="fas fa-file-contract text-primary me-2"></i>
                Select Exit Policy <span class="text-danger">*</span>
                <span class="text-muted" style="font-size:0.75rem;font-weight:400;">
                    ({{ count($assignedPolicies) }} policy(s) available)
                </span>
                @if(count($assignedPolicies) == 0)
                    <span class="text-warning" style="font-size:0.75rem;">
                        <i class="fas fa-exclamation-triangle"></i> No policies available for admin initiation
                    </span>
                @endif
            </h6>

            @if(count($assignedPolicies) > 0)
                <div class="policy-grid" id="policyGrid">
                    @foreach($assignedPolicies as $exitType => $policyData)
                        @php
                            $policy = $policyData['policy'];
                            $assignment = $policyData['assignment'];
                            $assignmentType = $policyData['assignment_type'];
                            $assignmentLabels = [
                                'individual' => 'Individual',
                                'department' => 'Department',
                                'all_department' => 'All Departments'
                            ];
                            // Auto-determine reason from exit type
                            $exitTypeReasonMap = [
                                'Resignation' => 'resignation',
                                'Termination' => 'termination',
                                'End of Contract' => 'end_of_contract',
                                'Mutual Agreement' => 'mutual_separation',
                                'Retirement' => 'retirement',
                                'Other' => 'other'
                            ];
                            $autoReason = $exitTypeReasonMap[$exitType] ?? 'other';
                            $exitTypeLabels = [
                                'Resignation' => 'Resignation',
                                'Termination' => 'Termination',
                                'End of Contract' => 'End of Contract',
                                'Mutual Agreement' => 'Mutual Agreement',
                                'Retirement' => 'Retirement',
                                'Other' => 'Other'
                            ];
                            $reasonLabels = [
                                'resignation' => 'Resignation',
                                'termination' => 'Termination',
                                'end_of_contract' => 'End of Contract',
                                'mutual_separation' => 'Mutual Separation',
                                'retirement' => 'Retirement',
                                'other' => 'Other'
                            ];
                        @endphp
                        <div class="policy-card-option" 
                             data-policy-id="{{ $policy->id }}" 
                             data-exit-type="{{ $exitType }}"
                             data-auto-reason="{{ $autoReason }}"
                             data-notice-days="{{ $policy->default_notice_period ?? 30 }}"
                             onclick="selectPolicy(this)">
                            <div class="policy-radio">
                                <input type="radio" name="selected_policy" value="{{ $policy->id }}" 
                                       data-exit-type="{{ $exitType }}"
                                       data-auto-reason="{{ $autoReason }}"
                                       data-notice-days="{{ $policy->default_notice_period ?? 30 }}">
                            </div>
                            <div>
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="policy-name">{{ $policy->policy_name }}</div>
                                        <div class="policy-code">{{ $policy->policy_code }}</div>
                                    </div>
                                    <span class="policy-exit-type" style="background:#dbeafe;color:#1e40af;">
                                        {{ $exitTypeLabels[$exitType] ?? $exitType }}
                                    </span>
                                </div>
                                <div class="policy-details mt-2">
                                    <span class="badge-item">
                                        <i class="fas fa-user-tag me-1"></i> {{ $assignmentLabels[$assignmentType] ?? 'Unknown' }}
                                    </span>
                                    <span class="badge-item">
                                        <i class="far fa-calendar-alt me-1"></i> {{ $policy->default_notice_period ?? 30 }} days
                                    </span>
                                    <span class="badge-item" style="background:#d1fae5;color:#065f46;">
                                        <i class="fas fa-arrow-right me-1"></i> Reason: {{ $reasonLabels[$autoReason] ?? ucwords(str_replace('_', ' ', $autoReason)) }}
                                    </span>
                                    @if($policy->exit_interview_required)
                                        <span class="badge-item" style="background:#fef3c7;color:#92400e;">
                                            <i class="fas fa-comments me-1"></i> Interview
                                        </span>
                                    @endif
                                    @if($policy->fnf_required)
                                        <span class="badge-item" style="background:#fee2e2;color:#991b1b;">
                                            <i class="fas fa-file-invoice-dollar me-1"></i> FNF
                                        </span>
                                    @endif
                                    @if($policy->kt_required)
                                        <span class="badge-item" style="background:#d1fae5;color:#065f46;">
                                            <i class="fas fa-chalkboard-teacher me-1"></i> KT
                                        </span>
                                    @endif
                                    @if($policy->description)
                                        <div class="mt-1 text-muted" style="font-size:0.75rem;">
                                            {{ \Str::limit($policy->description, 100) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>No admin-initiated policies available.</strong>
                    <p class="mb-0 mt-1">Admin can only initiate exits for: Termination, End of Contract, Mutual Agreement, Retirement, or Other. Resignation must be initiated by the employee.</p>
                    <div class="mt-2">
                        <a href="{{ route('exit-policies.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Create Policy
                        </a>
                    </div>
                </div>
            @endif
        </div>

        {{-- EXIT FORM --}}
        <div class="form-section-modern fade-up delay-3" id="exitFormSection" style="{{ count($assignedPolicies) > 0 ? '' : 'opacity:0.5;pointer-events:none;' }}">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h6 class="section-title mb-0">
                    <i class="fas fa-pen-alt text-primary me-2"></i>Exit Details
                </h6>
                {{-- AUTO-APPROVAL INDICATOR --}}
                <div class="auto-approval-badge">
                    <i class="fas fa-check-circle"></i>
                    Admin Initiated - Auto Approved
                </div>
            </div>
            
            <form action="{{ route('employee.exit.process', $employee->employee_id) }}" method="POST" id="exitForm">
                @csrf
                <input type="hidden" name="policy_id" id="selectedPolicyId" value="">
                <input type="hidden" name="exit_type" id="selectedExitType" value="">
                <input type="hidden" name="exit_reason" id="selectedExitReason" value="">
                
                {{-- Override fields --}}
                @if(isset($hasPendingResignation) && $hasPendingResignation)
                    <input type="hidden" name="override_pending" id="overridePendingInput" value="0">
                    <input type="hidden" name="pending_exit_id" value="{{ $pendingResignation->id ?? '' }}">
                @endif
                
                {{-- AUTO-APPROVE: Admin initiated exits don't need approval --}}
                <input type="hidden" name="require_approval" value="0">

                <div class="row g-4">
                    {{-- Exit Type (Auto-filled from policy) --}}
                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label class="form-label">
                                <i class="fas fa-tag me-1"></i> Exit Type
                            </label>
                            <div class="form-control" style="background:#e9ecef;padding:10px 14px;border-radius:var(--radius-sm);font-weight:600;color:#1e293b;" id="exitTypeDisplay">
                                <i class="fas fa-lock me-2" style="color:#94a3b8;font-size:12px;"></i>
                                <span id="exitTypeText">Select a policy first</span>
                            </div>
                            <small class="text-muted" style="font-size:0.75rem;">
                                <i class="fas fa-info-circle me-1"></i> Exit type is automatically determined from the selected policy
                            </small>
                        </div>
                    </div>

                    {{-- Exit Reason (Auto-filled from policy) --}}
                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label class="form-label">
                                <i class="fas fa-question-circle me-1"></i> Exit Reason
                            </label>
                            <div class="form-control" style="background:#e9ecef;padding:10px 14px;border-radius:var(--radius-sm);font-weight:600;color:#1e293b;" id="exitReasonDisplay">
                                <i class="fas fa-lock me-2" style="color:#94a3b8;font-size:12px;"></i>
                                <span id="exitReasonText">Select a policy first</span>
                            </div>
                            <small class="text-muted" style="font-size:0.75rem;">
                                <i class="fas fa-info-circle me-1"></i> Exit reason is automatically determined from the selected policy
                            </small>
                        </div>
                    </div>

                    {{-- Notice Period (Auto-filled from policy) --}}
                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label class="form-label">
                                <i class="far fa-calendar-alt me-1"></i> Notice Period
                            </label>
                            <div class="form-control" style="background:#e9ecef;padding:10px 14px;border-radius:var(--radius-sm);font-weight:600;color:#1e293b;" id="noticePeriodDisplay">
                                <i class="fas fa-lock me-2" style="color:#94a3b8;font-size:12px;"></i>
                                <span id="noticePeriodText">Select a policy first</span>
                            </div>
                            <small class="text-muted" style="font-size:0.75rem;">
                                <i class="fas fa-info-circle me-1"></i> Notice period is automatically determined from the selected policy
                            </small>
                        </div>
                    </div>

                    {{-- Custom Start Date --}}
                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="custom_start_date" class="form-label">
                                <i class="far fa-calendar-plus me-1"></i> Custom Start Date 
                                <span class="text-muted" style="font-weight:400;">(Optional)</span>
                            </label>
                            <input type="date" name="custom_start_date" id="custom_start_date" 
                                class="form-control @error('custom_start_date') is-invalid @enderror" 
                                min="{{ date('Y-m-d') }}"
                                value="{{ old('custom_start_date') }}">
                            <small class="text-muted" style="font-size:0.7rem;">
                                <i class="fas fa-info-circle me-1"></i> 
                                Leave empty to start from today ({{ date('d M Y') }})
                            </small>
                            @error('custom_start_date')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Exit Notes --}}
                    <div class="col-12">
                        <div class="form-group-modern">
                            <label for="exit_notes" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i> Additional Notes
                            </label>
                            <textarea name="exit_notes" id="exit_notes" 
                                    class="form-control @error('exit_notes') is-invalid @enderror" 
                                    rows="3" 
                                    placeholder="Any additional notes or comments...">{{ old('exit_notes') }}</textarea>
                            @error('exit_notes')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Summary --}}
                <div class="summary-alert-modern mt-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-info-circle" style="color:#4f46e5;"></i>
                        <span style="font-weight:600;font-size:0.9rem;color:var(--gray-700);">Exit Summary</span>
                        <span class="auto-approval-badge ms-2" style="font-size:0.7rem;padding:2px 12px;">
                            <i class="fas fa-check-circle"></i> Auto-Approved
                        </span>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-3 col-6">
                            <div class="label" style="font-size:0.7rem;text-transform:uppercase;color:var(--gray-500);font-weight:600;">Policy</div>
                            <div class="value" style="font-weight:600;" id="summaryPolicy">Not selected</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="label" style="font-size:0.7rem;text-transform:uppercase;color:var(--gray-500);font-weight:600;">Exit Type</div>
                            <div class="value" style="font-weight:600;" id="summaryExitType">Not selected</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="label" style="font-size:0.7rem;text-transform:uppercase;color:var(--gray-500);font-weight:600;">Exit Reason</div>
                            <div class="value" style="font-weight:600;" id="summaryExitReason">Auto-selected from policy</div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="label" style="font-size:0.7rem;text-transform:uppercase;color:var(--gray-500);font-weight:600;">End Date</div>
                            <div class="value" style="font-weight:600;" id="summaryEndDate">—</div>
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="d-flex flex-wrap justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-modern">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary-modern" id="submitExitBtn" disabled>
                        <i class="fas fa-door-open me-1"></i> 
                        <span id="submitBtnText">Select a Policy First</span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- EXIT HISTORY --}}
    @if(isset($exitHistory) && $exitHistory->count() > 0)
    <div class="form-section-modern fade-up delay-3 mt-3" style="border-radius:var(--radius);">
        <h6 class="section-title"><i class="fas fa-history text-primary me-2"></i>Exit History</h6>
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>Exit Type</th>
                        <th>Status</th>
                        <th>Period</th>
                        <th>Reason</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exitHistory as $history)
                    <tr>
                        <td>{{ $history->exit_type ?? 'Resignation' }}</td>
                        <td><span class="badge-status badge-{{ $history->exit_status }}" style="font-size:0.7rem;padding:2px 12px;">{{ $history->status_label }}</span></td>
                        <td>{{ $history->notice_period_days ?? 'N/A' }} days</td>
                        <td>{{ ucwords(str_replace('_', ' ', $history->exit_reason ?? 'N/A')) }}</td>
                        <td>{{ $history->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

{{-- Hidden Forms --}}
<form id="approveExitForm" action="" method="POST" style="display:none;">@csrf</form>
<form id="completeExitForm" action="" method="POST" style="display:none;">@csrf</form>
<form id="cancelExitForm" action="" method="POST" style="display:none;">@csrf</form>
<form id="rejectExitForm" action="" method="POST" style="display:none;">@csrf</form>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // ========================================
    // POLICY SELECTION WITH AUTO REASON
    // ========================================
    window.selectPolicy = function(element) {
        // Remove selected class from all
        $('.policy-card-option').removeClass('selected');
        $(element).addClass('selected');
        
        // Get policy data
        var policyId = $(element).data('policy-id');
        var exitType = $(element).data('exit-type');
        var autoReason = $(element).data('auto-reason');
        var noticeDays = $(element).find('input[type="radio"]').data('notice-days') || 30;
        var policyName = $(element).find('.policy-name').text().trim();
        
        // Reason labels
        var reasonLabels = {
            'resignation': 'Resignation',
            'termination': 'Termination',
            'end_of_contract': 'End of Contract',
            'mutual_separation': 'Mutual Separation',
            'retirement': 'Retirement',
            'other': 'Other'
        };
        var reasonLabel = reasonLabels[autoReason] || autoReason;
        
        // Set hidden inputs
        $('#selectedPolicyId').val(policyId);
        $('#selectedExitType').val(exitType);
        $('#selectedExitReason').val(autoReason);
        
        // Update display fields
        $('#exitTypeText').text(exitType);
        $('#exitReasonText').text(reasonLabel);
        $('#noticePeriodText').text(noticeDays + ' Days');
        
        // Update summary
        $('#summaryPolicy').text(policyName);
        $('#summaryExitType').text(exitType);
        $('#summaryExitReason').text(reasonLabel);
        
        // Update end date
        updateSummary();
        
        // Check if override is required
        @if(isset($hasPendingResignation) && $hasPendingResignation)
            var hasOverride = $('#overridePendingInput').val() === '1';
            if (!hasOverride) {
                $('#submitExitBtn').prop('disabled', true);
                $('#submitBtnText').text('⚠️ Confirm override first');
                $('#submitExitBtn').removeClass('btn-danger-modern').addClass('btn-primary-modern');
            } else {
                $('#submitExitBtn').prop('disabled', false);
                $('#submitBtnText').text('Override & Initiate Exit');
                $('#submitExitBtn').removeClass('btn-primary-modern').addClass('btn-danger-modern');
            }
        @else
            // No pending resignation - enable submit
            $('#submitExitBtn').prop('disabled', false);
            $('#submitBtnText').text('Initiate Exit');
        @endif
        
        // Enable form section if it was disabled
        $('#exitFormSection').css('opacity', '1').css('pointer-events', 'auto');
    };

    // ========================================
    // UPDATE SUMMARY - DYNAMIC
    // ========================================
    function updateSummary() {
        var days = parseInt($('#noticePeriodText').text()) || 30;
        var startDateInput = $('#custom_start_date').val();
        var startDate = startDateInput ? new Date(startDateInput) : new Date();
        
        var endDate = new Date(startDate);
        endDate.setDate(endDate.getDate() + days);
        
        var formatDate = function(date) {
            var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            var d = date.getDate().toString().padStart(2, '0');
            var m = months[date.getMonth()];
            var y = date.getFullYear();
            return d + ' ' + m + ' ' + y;
        };
        
        $('#summaryEndDate').text(formatDate(endDate));
    }

    // Bind change events
    $('#custom_start_date').on('change', function() {
        updateSummary();
    });

    // Initial summary update
    updateSummary();

    // ========================================
    // OVERRIDE CHECKBOX HANDLER
    // ========================================
    @if(isset($hasPendingResignation) && $hasPendingResignation)
    $('#overridePendingResignation').on('change', function() {
        if ($(this).is(':checked')) {
            $('#overridePendingInput').val('1');
            
            // Check if policy is selected
            var policyId = $('#selectedPolicyId').val();
            if (policyId) {
                $('#submitExitBtn').prop('disabled', false);
                $('#submitBtnText').text('Override & Initiate Exit');
                $('#submitExitBtn').removeClass('btn-primary-modern').addClass('btn-danger-modern');
            } else {
                $('#submitBtnText').text('Select policy then override');
            }
            
            Swal.fire({
                icon: 'warning',
                title: '⚠️ Override Confirmation Required',
                html: `
                    <div style="text-align:left;padding:10px 0;">
                        <p style="font-weight:600;color:#92400e;">
                            You are about to override a pending resignation.
                        </p>
                        <p style="color:#78350f;">
                            This will:
                        </p>
                        <ul style="color:#78350f;text-align:left;">
                            <li>❌ <strong>Cancel</strong> the employee's pending resignation</li>
                            <li>✅ <strong>Initiate</strong> the selected exit type (Termination/End of Contract/etc.)</li>
                            <li>✅ <strong>Auto-approve</strong> the exit (no approval needed)</li>
                        </ul>
                        <p style="color:#dc2626;font-weight:600;margin-top:10px;">
                            This action cannot be undone!
                        </p>
                    </div>
                `,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'I Understand, Proceed'
            });
        } else {
            $('#overridePendingInput').val('0');
            $('#submitExitBtn').prop('disabled', true);
            $('#submitBtnText').text('Select a Policy First');
            $('#submitExitBtn').removeClass('btn-danger-modern').addClass('btn-primary-modern');
        }
    });
    @endif

    // ========================================
    // INITIATE EXIT - DOUBLE CONFIRMATION
    // ========================================
    $('#exitForm').on('submit', function(e) {
        e.preventDefault();
        
        var policyId = $('#selectedPolicyId').val();
        var exitType = $('#selectedExitType').val();
        var exitReason = $('#selectedExitReason').val();
        
        if (!policyId || !exitType) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please select a policy first.',
                confirmButtonColor: '#ef4444'
            });
            return false;
        }
        
        var form = $(this);
        var employeeName = '{{ $employee->name }}';
        var endDate = $('#summaryEndDate').text();
        var policyName = $('#summaryPolicy').text();
        var exitTypeLabel = $('#summaryExitType').text();
        var exitReasonLabel = $('#summaryExitReason').text();
        
        // Check if override is checked
        var isOverride = $('#overridePendingInput').val() === '1';
        var overrideText = isOverride ? '<span style="color:#dc2626;font-weight:700;">⚠️ Override Mode: Pending resignation will be cancelled</span><br>' : '';
        
        // FIRST CONFIRMATION
        Swal.fire({
            title: isOverride ? '⚠️ Override & Initiate Exit?' : '⚠️ Initiate Exit Process?',
            html: `
                <div style="text-align:left;padding:10px 0;">
                    ${overrideText}
                    <p style="font-weight:600;color:#1e293b;margin-bottom:8px;">
                        You are about to initiate the exit process for:
                    </p>
                    <p style="font-size:1.1rem;color:#4f46e5;font-weight:700;margin-bottom:12px;">
                        ${employeeName}
                    </p>
                    <div style="background:#f8fafc;padding:12px 16px;border-radius:8px;margin-bottom:10px;">
                        <div style="display:flex;justify-content:space-between;padding:4px 0;border-bottom:1px solid #e2e8f0;">
                            <span style="color:#64748b;">Policy:</span>
                            <span style="font-weight:600;color:#1e293b;">${policyName}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:4px 0;border-bottom:1px solid #e2e8f0;">
                            <span style="color:#64748b;">Exit Type:</span>
                            <span style="font-weight:600;color:#1e293b;">${exitTypeLabel}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:4px 0;border-bottom:1px solid #e2e8f0;">
                            <span style="color:#64748b;">Exit Reason:</span>
                            <span style="font-weight:600;color:#1e293b;">${exitReasonLabel}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:4px 0;">
                            <span style="color:#64748b;">Expected Exit Date:</span>
                            <span style="font-weight:600;color:#1e293b;">${endDate}</span>
                        </div>
                    </div>
                    <div style="background:#d1fae5;padding:8px 12px;border-radius:8px;margin-top:8px;">
                        <i class="fas fa-check-circle" style="color:#065f46;"></i>
                        <span style="color:#065f46;font-weight:500;">Admin-initiated exit will be auto-approved (no approval required)</span>
                    </div>
                    <p style="color:#64748b;font-size:0.9rem;margin-top:8px;">
                        <i class="fas fa-info-circle" style="color:#4f46e5;"></i>
                        The employee will remain active until the notice period ends.
                    </p>
                </div>
            `,
            icon: isOverride ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: isOverride ? '#dc2626' : '#4f46e5',
            cancelButtonColor: '#64748b',
            confirmButtonText: isOverride ? 'Yes, Override & Initiate' : 'Yes, Initiate Exit',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // SECOND CONFIRMATION
                Swal.fire({
                    title: isOverride ? '🔴 Final Override Confirmation' : '🔴 Final Confirmation Required',
                    html: `
                        <div style="padding:10px 0;">
                            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:12px;">
                                <p style="color:#991b1b;font-weight:600;margin-bottom:4px;">
                                    <i class="fas fa-exclamation-triangle" style="margin-right:8px;"></i>
                                    This action cannot be undone!
                                </p>
                                <p style="color:#7f1d1d;font-size:0.9rem;margin:0;">
                                    ${isOverride ? 'The pending resignation will be CANCELLED and a new exit will be initiated.' : 'The employee will be placed on notice period and will be auto-exited on ' + endDate + '.'}
                                </p>
                            </div>
                            <p style="color:#64748b;font-size:0.9rem;">
                                Please type <strong style="color:#dc2626;">"${isOverride ? 'OVERRIDE' : 'CONFIRM'}"</strong> to proceed:
                            </p>
                            <input type="text" id="confirmInput" class="form-control" 
                                   placeholder="Type ${isOverride ? 'OVERRIDE' : 'CONFIRM'}" 
                                   style="text-align:center;font-weight:600;border:2px solid #e2e8f0;border-radius:8px;padding:10px;margin-top:8px;">
                        </div>
                    `,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: isOverride ? '#dc2626' : '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: isOverride ? 'Yes, Finalize Override' : 'Yes, Finalize Exit',
                    cancelButtonText: 'Go Back',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        const input = Swal.getPopup().querySelector('#confirmInput');
                        const expected = isOverride ? 'OVERRIDE' : 'CONFIRM';
                        if (input.value !== expected) {
                            Swal.showValidationMessage('Please type "' + expected + '" to proceed');
                            return false;
                        }
                        return true;
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((finalResult) => {
                    if (finalResult.isConfirmed) {
                        var btn = $('#submitExitBtn');
                        btn.html('<span class="spinner-border spinner-border-sm me-2"></span> Processing...');
                        btn.prop('disabled', true);
                        form[0].submit();
                    }
                });
            }
        });
    });

    // ========================================
    // APPROVE EXIT
    // ========================================
    window.approveExit = function(id) {
        Swal.fire({
            title: 'Approve Exit Request?',
            html: `
                <p>This will start the notice period for <strong>{{ $employee->name }}</strong>.</p>
                <p class="text-muted small">The employee will remain active until the notice period ends.</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Approve',
            cancelButtonText: 'Cancel'
        }).then(r => {
            if (r.isConfirmed) {
                document.getElementById('approveExitForm').action = "{{ url('institute/admin/employee-exit/approve') }}/" + id;
                document.getElementById('approveExitForm').submit();
            }
        });
    };

    // ========================================
    // REJECT EXIT
    // ========================================
    window.rejectExit = function(id) {
        Swal.fire({
            title: 'Reject Exit Request?',
            html: `
                <p>Are you sure you want to <strong class="text-danger">REJECT</strong> this exit request?</p>
                <p class="text-muted small">The employee will remain active and the exit process will be cancelled.</p>
                <div class="mt-3">
                    <label for="rejectionReason" class="form-label">Rejection Reason <span class="text-muted">(Optional)</span>:</label>
                    <textarea id="rejectionReason" class="form-control" rows="3" 
                              placeholder="Please provide a reason for rejecting this exit request..."></textarea>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Reject',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const reason = document.getElementById('rejectionReason').value;
                return { reason: reason || 'No specific reason provided' };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Rejecting exit request...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                
                const form = document.getElementById('rejectExitForm');
                form.action = "{{ url('institute/admin/employee-exit/reject') }}/" + id;
                const oldInput = form.querySelector('input[name="rejection_reason"]');
                if (oldInput) oldInput.remove();
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'rejection_reason';
                input.value = result.value.reason;
                form.appendChild(input);
                form.submit();
            }
        });
    };

    // ========================================
    // COMPLETE EXIT NOW
    // ========================================
    window.completeExitNow = function(id) {
        var employeeName = '{{ $employee->name }}';
        
        Swal.fire({
            title: '⚠️ Complete Exit Immediately?',
            html: `
                <div style="text-align:left;padding:10px 0;">
                    <p style="font-weight:600;color:#1e293b;margin-bottom:8px;">
                        You are about to immediately complete the exit process for:
                    </p>
                    <p style="font-size:1.1rem;color:#ef4444;font-weight:700;margin-bottom:12px;">
                        ${employeeName}
                    </p>
                    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;">
                        <p style="color:#991b1b;font-weight:600;margin-bottom:4px;">
                            <i class="fas fa-exclamation-triangle" style="margin-right:8px;"></i>
                            This will immediately:
                        </p>
                        <ul style="color:#7f1d1d;margin:0;padding-left:20px;font-size:0.9rem;">
                            <li>Mark employee as <strong>EXITED</strong></li>
                            <li>Deactivate user account</li>
                            <li>Set exit date as today</li>
                        </ul>
                    </div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Complete Now',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: '🔴 Final Confirmation Required',
                    html: `
                        <div style="padding:10px 0;">
                            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:12px;">
                                <p style="color:#991b1b;font-weight:700;font-size:1.1rem;margin-bottom:4px;">
                                    <i class="fas fa-exclamation-circle" style="margin-right:8px;"></i>
                                    This action is IRREVERSIBLE!
                                </p>
                                <p style="color:#7f1d1d;font-size:0.9rem;margin:0;">
                                    The employee "${employeeName}" will be immediately exited from the system.
                                </p>
                            </div>
                            <p style="color:#64748b;font-size:0.9rem;">
                                Please type <strong style="color:#dc2626;">"EXIT"</strong> to confirm:
                            </p>
                            <input type="text" id="confirmExitInput" class="form-control" 
                                   placeholder="Type EXIT" 
                                   style="text-align:center;font-weight:600;border:2px solid #e2e8f0;border-radius:8px;padding:10px;margin-top:8px;">
                        </div>
                    `,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Permanently Exit',
                    cancelButtonText: 'Go Back',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        const input = Swal.getPopup().querySelector('#confirmExitInput');
                        if (input.value !== 'EXIT') {
                            Swal.showValidationMessage('Please type "EXIT" to proceed');
                            return false;
                        }
                        return true;
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((finalResult) => {
                    if (finalResult.isConfirmed) {
                        document.getElementById('completeExitForm').action = "{{ url('institute/admin/employee-exit/complete') }}/" + id;
                        document.getElementById('completeExitForm').submit();
                    }
                });
            }
        });
    };

    // ========================================
    // CANCEL EXIT
    // ========================================
    window.cancelExit = function(id) {
        Swal.fire({
            title: 'Cancel Exit Process?',
            html: `
                <p>This will cancel the exit process for <strong>{{ $employee->name }}</strong>.</p>
                <p class="text-muted small">The employee will remain active and the exit will be cancelled.</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Cancel',
            cancelButtonText: 'Keep Process'
        }).then(r => {
            if (r.isConfirmed) {
                document.getElementById('cancelExitForm').action = "{{ url('institute/admin/employee-exit/cancel') }}/" + id;
                document.getElementById('cancelExitForm').submit();
            }
        });
    };
});
</script>
@endsection