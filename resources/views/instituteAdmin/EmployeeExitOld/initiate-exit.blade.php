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

/* Base Container */
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

/* ===== POLICY CARD ===== */
.policy-card-modern {
    background: var(--primary-bg);
    border: 1px solid #c7d2fe;
    border-top: none;
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}

.policy-card-modern .policy-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.policy-tag-employee { background: #dbeafe; color: #1e40af; }
.policy-tag-department { background: #d1fae5; color: #065f46; }
.policy-tag-default { background: var(--gray-200); color: var(--gray-600); }

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

/* ===== NOTICE TIMELINE ===== */
.notice-timeline-modern {
    position: relative;
    padding-left: 28px;
}
.notice-timeline-modern::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 4px;
    bottom: 4px;
    width: 2px;
    background: linear-gradient(to bottom, #4f46e5, #818cf8);
    border-radius: 2px;
}
.timeline-item-modern {
    position: relative;
    padding: 8px 0 8px 20px;
}
.timeline-item-modern::before {
    content: '';
    position: absolute;
    left: -20px;
    top: 14px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #4f46e5;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #4f46e5;
}
.timeline-item-modern:last-child::before {
    background: #818cf8;
    box-shadow: 0 0 0 2px #818cf8;
}
.timeline-item-modern .date {
    font-weight: 700;
    color: var(--gray-800);
    font-size: 0.9rem;
}
.timeline-item-modern .label {
    color: var(--gray-500);
    font-size: 0.8rem;
}

/* ===== COUNTDOWN BOX ===== */
.countdown-box-modern {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-sm);
    padding: 18px 20px;
    text-align: center;
    height: 100%;
    transition: var(--transition);
}
.countdown-box-modern:hover {
    border-color: #4f46e5;
    box-shadow: var(--shadow-md);
}
.countdown-number {
    font-size: 2.5rem;
    font-weight: 800;
    line-height: 1;
    color: #4f46e5;
}
.countdown-number.overdue { color: #ef4444; }
.countdown-label {
    color: var(--gray-500);
    font-size: 0.8rem;
    margin-top: 2px;
}
.progress-modern {
    height: 4px;
    background: var(--gray-200);
    border-radius: 2px;
    overflow: hidden;
    margin-top: 8px;
}
.progress-modern .bar {
    height: 100%;
    border-radius: 2px;
    background: linear-gradient(90deg, #4f46e5, #818cf8);
    transition: width 0.6s ease;
}
.progress-modern .bar.overdue { background: linear-gradient(90deg, #ef4444, #f87171); }

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

.form-group-modern .form-control.is-invalid {
    border-color: #ef4444;
}

.form-group-modern .form-check-input:checked {
    background-color: #4f46e5;
    border-color: #4f46e5;
}

/* ===== SUMMARY ALERT ===== */
.summary-alert-modern {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-sm);
    padding: 16px 20px;
}
.summary-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 8px 20px;
}
.summary-grid-modern .item .label {
    font-size: 0.7rem;
    text-transform: uppercase;
    color: var(--gray-500);
    font-weight: 600;
    letter-spacing: 0.3px;
}
.summary-grid-modern .item .value {
    font-size: 1rem;
    font-weight: 700;
    color: var(--gray-800);
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

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .exit-header-modern { padding: 18px 20px; }
    .employee-card-modern { padding: 18px 20px; }
    .form-section-modern { padding: 20px; }
    .info-grid-modern { grid-template-columns: 1fr 1fr; }
    .summary-grid-modern { grid-template-columns: 1fr 1fr; }
}

@media (max-width: 576px) {
    .info-grid-modern { grid-template-columns: 1fr; }
    .summary-grid-modern { grid-template-columns: 1fr; }
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

    {{-- EXIT POLICY --}}
    <div class="policy-card-modern fade-up delay-2">
        <div class="d-flex align-items-center flex-wrap gap-2">
            <i class="fas fa-file-contract" style="color:#4f46e5;font-size:1.1rem;"></i>
            <span style="font-weight:600;color:var(--gray-700);font-size:0.9rem;">Applicable Policy:</span>
            
            @if($exitPolicy)
                <span class="policy-tag 
                    @if($exitPolicy->policy_type === 'employee_wise') policy-tag-employee
                    @elseif($exitPolicy->policy_type === 'department_wise') policy-tag-department
                    @else policy-tag-default @endif">
                    @if($exitPolicy->policy_type === 'employee_wise') <i class="fas fa-user"></i> Employee Wise
                    @elseif($exitPolicy->policy_type === 'department_wise') <i class="fas fa-building"></i> Department Wise
                    @else <i class="fas fa-globe"></i> Default @endif
                </span>
                
                @if($exitPolicy->employment_type && $exitPolicy->employment_type !== 'All')
                    <span class="policy-tag" style="background:#d1fae5;color:#065f46;">
                        <i class="fas fa-tag"></i> {{ $exitPolicy->employment_type }}
                    </span>
                @endif
                
                <span class="policy-tag" style="background:var(--gray-200);color:var(--gray-600);">
                    <i class="far fa-calendar-alt"></i> {{ $exitPolicy->notice_period_days }} Days Notice
                </span>
                
                @if($exitPolicy->description)
                    <span style="font-size:0.8rem;color:var(--gray-500);">
                        <i class="fas fa-info-circle"></i> {{ $exitPolicy->description }}
                    </span>
                @endif
            @else
                <span class="policy-tag" style="background:var(--warning-bg);color:#92400e;">
                    <i class="fas fa-exclamation-triangle"></i> No specific policy
                </span>
                <span style="font-size:0.85rem;color:var(--gray-500);">
                    Using default <strong>{{ $noticeDetails['notice_days'] }} days</strong> notice
                </span>
            @endif
        </div>
        
        @if($hasActiveExit && $activeExit)
            <span class="badge-status" style="background:#dbeafe;color:#1e40af;font-size:0.75rem;">
                <i class="fas fa-clock"></i> Active: {{ $activeExit->status_label }}
            </span>
        @endif
    </div>

    {{-- NO POLICY FOUND - WARNING MESSAGE --}}
    @if($policyMissing && !$hasActiveExit)
    <div class="form-section-modern fade-up delay-2" style="border-radius:0;background:#fffbeb;border-color:#fde68a;">
        <div class="d-flex align-items-start gap-3">
            <div style="background:#fef3c7;padding:8px 12px;border-radius:50%;">
                <i class="fas fa-exclamation-triangle" style="color:#d97706;font-size:1.5rem;"></i>
            </div>
            <div style="flex:1;">
                <h6 style="color:#92400e;font-weight:700;margin-bottom:4px;">
                    <i class="fas fa-file-contract me-2"></i>Exit Policy Required
                </h6>
                <p style="color:#78350f;margin-bottom:8px;font-size:0.9rem;">
                    {{ $missingPolicyMessage ?? 'No exit policy found for this employee. Please create one before initiating the exit process.' }}
                </p>
                <div class="d-flex flex-wrap gap-2 mt-2">
                    <a href="{{ $policyCreateRoute ?? route('exit-policies.create') }}" class="btn" style="background:#d97706;color:#fff;padding:8px 24px;border-radius:var(--radius-sm);font-weight:600;font-size:0.85rem;transition:var(--transition);">
                        <i class="fas fa-plus-circle me-1"></i> Create Exit Policy
                    </a>
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-modern" style="border-color:#d97706;color:#92400e;">
                        <i class="fas fa-arrow-left me-1"></i> Go Back
                    </a>
                </div>
                @if($missingPolicyType)
                    <small style="color:#78350f;display:block;margin-top:6px;font-size:0.75rem;">
                        <i class="fas fa-info-circle me-1"></i>
                        @if($missingPolicyType === 'department_wise')
                            A <strong>department-wise</strong> policy is needed for "{{ $employee->department_name ?? 'this department' }}".
                        @elseif($missingPolicyType === 'employee_wise')
                            An <strong>employee-wise</strong> policy is needed for "{{ $employee->name }}".
                        @elseif($missingPolicyType === 'employment_type')
                            A policy for employment type <strong>"{{ $employee->employment_type }}"</strong> is needed.
                        @else
                            A <strong>default</strong> exit policy is needed.
                        @endif
                    </small>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ACTIVE EXIT TIMELINE --}}
    @if($hasActiveExit && $activeExit)
    <div class="form-section-modern fade-up delay-3" style="border-radius:0;">
        <h6 class="section-title">
            <i class="fas fa-clock text-primary me-2"></i>Exit Timeline
            <span class="badge-status badge-{{ $activeExit->exit_status }} ms-2">
                {{ $activeExit->status_label }}
            </span>
            @if(isset($activeExit->initiation_source) && $activeExit->initiation_source === 'admin')
                <span class="badge bg-secondary ms-2" style="font-size:0.7rem;">
                    <i class="fas fa-user-shield me-1"></i> Admin Initiated
                </span>
            @else
                <span class="badge bg-info ms-2" style="font-size:0.7rem;">
                    <i class="fas fa-user me-1"></i> Self Initiated
                </span>
            @endif
        </h6>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="countdown-box-modern">
                    <div class="countdown-number {{ ($noticeDetails['is_overdue'] ?? false) ? 'overdue' : '' }}">
                        {{ $noticeDetails['days_remaining'] ?? 0 }}
                    </div>
                    <div class="countdown-label">
                        {{ ($noticeDetails['is_overdue'] ?? false) ? 'Days Overdue' : 'Days Remaining' }}
                    </div>
                    <div class="progress-modern">
                        <div class="bar {{ ($noticeDetails['is_overdue'] ?? false) ? 'overdue' : '' }}" 
                            style="width: {{ min(100, $noticeDetails['progress'] ?? 0) }}%;">
                        </div>
                    </div>
                    <span style="font-size:0.75rem;color:var(--gray-400);">
                        {{ number_format($noticeDetails['progress'] ?? 0, 1) }}% completed
                    </span>
                </div>
            </div>
            <div class="col-md-8">
                <div class="notice-timeline-modern">
                    <div class="timeline-item-modern">
                        <div class="date">{{ $noticeDetails['start_date'] ?? 'N/A' }}</div>
                        <div class="label">Notice period started</div>
                        @if(isset($noticeDetails['is_admin_initiated']) && $noticeDetails['is_admin_initiated'])
                            <div class="sub-label text-muted" style="font-size:0.7rem;">
                                <i class="fas fa-user-shield"></i> Initiated by Admin
                            </div>
                        @endif
                    </div>
                    <div class="timeline-item-modern">
                        <div class="date {{ ($noticeDetails['is_overdue'] ?? false) ? 'text-danger' : '' }}">
                            {{ $noticeDetails['end_date'] ?? 'N/A' }}
                            @if($noticeDetails['is_overdue'] ?? false)
                                <span class="badge bg-danger ms-2" style="font-size:0.65rem;">Overdue</span>
                            @endif
                        </div>
                        <div class="label">
                            {{ ($noticeDetails['is_overdue'] ?? false) ? 'Overdue by '.abs($noticeDetails['days_remaining'] ?? 0).' days' : 'Expected exit date' }}
                        </div>
                    </div>
                    @if($activeExit->exit_status === 'exited' && $activeExit->actual_exit_date)
                    <div class="timeline-item-modern" style="position:relative;">
                        <div class="date text-success">{{ \Carbon\Carbon::parse($activeExit->actual_exit_date)->format('d-m-Y') }}</div>
                        <div class="label text-success">Exit Completed</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-4 pt-3 border-top d-flex flex-wrap gap-2">
            @if($activeExit->exit_status === 'pending_approval')
                <button onclick="approveExit({{ $activeExit->id }})" class="btn btn-success-modern">
                    <i class="fas fa-check-circle me-1"></i> Approve Exit
                </button>
                <button onclick="rejectExit({{ $activeExit->id }})" class="btn btn-outline-modern" style="border-color:#ef4444;color:#ef4444;">
                    <i class="fas fa-times-circle me-1"></i> Reject
                </button>
            @elseif($activeExit->exit_status === 'notice_period')
                <button onclick="completeExitNow({{ $activeExit->id }})" class="btn btn-danger-modern">
                    <i class="fas fa-check-circle me-1"></i> Complete Exit Now
                </button>
                <button onclick="cancelExit({{ $activeExit->id }})" class="btn btn-outline-modern">
                    <i class="fas fa-times-circle me-1"></i> Cancel Process
                </button>
            @elseif($activeExit->exit_status === 'exited')
                <div class="alert alert-info mb-0 w-100">
                    <i class="fas fa-info-circle me-2"></i>
                    This employee has already been exited on 
                    {{ $activeExit->actual_exit_date ? \Carbon\Carbon::parse($activeExit->actual_exit_date)->format('d-m-Y') : 'N/A' }}
                </div>
            @endif
        </div>
    </div>
    @endif

    {{-- NEW EXIT FORM --}}
    @if(!$hasActiveExit || !$activeExit)
    <div class="form-section-modern fade-up delay-3">
        <h6 class="section-title"><i class="fas fa-pen-alt text-primary me-2"></i>Initiate New Exit Process</h6>
        
        <form action="{{ route('employee.exit.process', $employee->employee_id) }}" method="POST" id="exitForm">
            @csrf

            <div class="row g-4">
                {{-- Notice Period --}}
                <div class="col-md-6">
                    <div class="form-group-modern">
                        <label for="notice_period_days" class="form-label">
                            <i class="far fa-calendar-alt me-1"></i> Notice Period 
                            @if(!$exitPolicy)
                                <span class="text-danger">*</span>
                            @endif
                        </label>

                        @if($exitPolicy)
                            {{-- When policy exists, show as readonly with hidden input --}}
                            @php
                                $noticeDays = $noticeDetails['notice_days'] ?? 30;
                            @endphp
                            <input type="hidden" name="notice_period_days" value="{{ $noticeDays }}">
                            <div class="form-control" style="background:#e9ecef;padding:10px 14px;border-radius:var(--radius-sm);font-weight:600;color:#1e293b;">
                                <i class="fas fa-lock me-2" style="color:#94a3b8;font-size:12px;"></i>
                                {{ $noticeDays }} Days 
                               
                            </div>
                            <small class="text-muted" style="font-size:0.75rem;">
                                <i class="fas fa-info-circle me-1"></i> Notice period is locked from the exit policy
                            </small>
                        @else
                            {{-- When no policy, show as select --}}
                            @php
                                $noticeDays = $noticeDetails['notice_days'] ?? 30;
                            @endphp
                            <select name="notice_period_days" id="notice_period_days" 
                                    class="form-control @error('notice_period_days') is-invalid @enderror"
                                    @if($policyMissing) disabled @endif required>
                                <option value="">Select Notice Period</option>
                                <option value="15" {{ old('notice_period_days', $noticeDays) == 15 ? 'selected' : '' }}>15 Days</option>
                                <option value="30" {{ old('notice_period_days', $noticeDays) == 30 ? 'selected' : '' }}>30 Days</option>
                                <option value="45" {{ old('notice_period_days', $noticeDays) == 45 ? 'selected' : '' }}>45 Days</option>
                                <option value="60" {{ old('notice_period_days', $noticeDays) == 60 ? 'selected' : '' }}>60 Days</option>
                                <option value="90" {{ old('notice_period_days', $noticeDays) == 90 ? 'selected' : '' }}>90 Days</option>
                            </select>
                            <small class="text-muted" style="font-size:0.75rem;">
                                <i class="fas fa-info-circle me-1"></i> Select the notice period for this exit
                            </small>
                        @endif

                        @error('notice_period_days')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
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
                            value="{{ old('custom_start_date') }}"
                            @if($policyMissing) disabled @endif>
                        <small class="text-muted" style="font-size:0.7rem;">
                            <i class="fas fa-info-circle me-1"></i> 
                            Leave empty to start from today ({{ date('d M Y') }})
                        </small>
                        @error('custom_start_date')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Exit Reason --}}
                <div class="col-md-6">
                    <div class="form-group-modern">
                        <label for="exit_reason" class="form-label">
                            <i class="fas fa-question-circle me-1"></i> Exit Reason <span class="text-danger">*</span>
                        </label>
                        <select name="exit_reason" id="exit_reason" 
                                class="form-control @error('exit_reason') is-invalid @enderror" 
                                @if($policyMissing) disabled @endif required>
                            <option value="">Select reason</option>
                            <option value="resignation" {{ old('exit_reason') == 'resignation' ? 'selected' : '' }}>Resignation</option>
                            <option value="termination" {{ old('exit_reason') == 'termination' ? 'selected' : '' }}>Termination</option>
                            <option value="retirement" {{ old('exit_reason') == 'retirement' ? 'selected' : '' }}>Retirement</option>
                            <option value="end_of_contract" {{ old('exit_reason') == 'end_of_contract' ? 'selected' : '' }}>End of Contract</option>
                            <option value="mutual_separation" {{ old('exit_reason') == 'mutual_separation' ? 'selected' : '' }}>Mutual Separation</option>
                            <option value="other" {{ old('exit_reason') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('exit_reason')
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
                                rows="2" 
                                placeholder="Any additional notes or comments..."
                                @if($policyMissing) disabled @endif>{{ old('exit_notes') }}</textarea>
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
                </div>
                <div class="summary-grid-modern">
                    <div class="item">
                        <div class="label">Start Date</div>
                        <div class="value" id="summaryStartDate">
                            @php
                                $startDate = old('custom_start_date') ? \Carbon\Carbon::parse(old('custom_start_date')) : \Carbon\Carbon::now();
                            @endphp
                            {{ $startDate->format('d M Y') }}
                        </div>
                    </div>
                    <div class="item">
                        <div class="label">End Date</div>
                        <div class="value" id="summaryEndDate">
                            @php
                                $startDate = old('custom_start_date') ? \Carbon\Carbon::parse(old('custom_start_date')) : \Carbon\Carbon::now();
                                $noticeDays = old('notice_period_days', $noticeDetails['notice_days'] ?? 30);
                                $endDate = $startDate->copy()->addDays($noticeDays);
                            @endphp
                            {{ $endDate->format('d M Y') }}
                        </div>
                    </div>
                    <div class="item">
                        <div class="label">Notice Period</div>
                        <div class="value" id="summaryNoticeDays">
                            {{ old('notice_period_days', $noticeDetails['notice_days'] ?? 30) }} days
                        </div>
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="d-flex flex-wrap justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('employees.index') }}" class="btn btn-outline-modern">
                    <i class="fas fa-times me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary-modern" id="submitExitBtn" 
                        @if($policyMissing) disabled @endif>
                    <i class="fas fa-door-open me-1"></i> 
                    <span id="submitBtnText">Initiate Exit</span>
                </button>
                @if($policyMissing)
                    <span style="font-size:0.75rem;color:var(--gray-500);display:flex;align-items:center;">
                        <i class="fas fa-lock me-1"></i> Create a policy first
                    </span>
                @endif
            </div>
        </form>
    </div>
    @endif

</div>


{{-- Hidden Forms --}}
<form id="approveExitForm" action="" method="POST" style="display:none;">@csrf</form>
<form id="completeExitForm" action="" method="POST" style="display:none;">@csrf</form>
<form id="cancelExitForm" action="" method="POST" style="display:none;">@csrf</form>
<form id="initiateExitForm" action="" method="POST" style="display:none;">@csrf</form>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    
    // ========================================
    // UPDATE SUMMARY - DYNAMIC
    // ========================================
    function updateSummary() {
        // Check if notice period is locked from policy
        var isLocked = {{ $exitPolicy ? 'true' : 'false' }};
        var days;
        
        if (isLocked) {
            // Use policy notice days (locked)
            days = {{ $noticeDetails['notice_days'] ?? 30 }};
        } else {
            // Get from dropdown
            var selectedVal = $('#notice_period_days').val();
            days = parseInt(selectedVal) || 30;
        }
        
        // Get start date
        var startDateInput = $('#custom_start_date').val();
        var startDate = startDateInput ? new Date(startDateInput) : new Date();
        
        // Calculate end date
        var endDate = new Date(startDate);
        endDate.setDate(endDate.getDate() + days);
        
        // Format dates
        var formatDate = function(date) {
            var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            var d = date.getDate().toString().padStart(2, '0');
            var m = months[date.getMonth()];
            var y = date.getFullYear();
            return d + ' ' + m + ' ' + y;
        };
        
        // Update summary
        $('#summaryStartDate').text(formatDate(startDate));
        $('#summaryEndDate').text(formatDate(endDate));
        $('#summaryNoticeDays').text(days + ' days');
    }

    // Bind change events
    $('#notice_period_days, #custom_start_date').on('change', function() {
        updateSummary();
    });

    // Initial summary update
    updateSummary();

    // ========================================
    // INITIATE EXIT - DOUBLE CONFIRMATION
    // ========================================
    $('#exitForm').on('submit', function(e) {
        e.preventDefault();
        
        // Check if notice period is locked from policy
        var isLocked = {{ $exitPolicy ? 'true' : 'false' }};
        var noticeDays;
        
        if (isLocked) {
            noticeDays = {{ $noticeDetails['notice_days'] ?? 30 }};
        } else {
            noticeDays = $('#notice_period_days').val();
        }
        
        var exitReason = $('#exit_reason').val();
        
        if (!noticeDays || !exitReason) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please select both notice period and exit reason.',
                confirmButtonColor: '#ef4444'
            });
            return false;
        }
        
        var form = $(this);
        var employeeName = '{{ $employee->name }}';
        var endDate = $('#summaryEndDate').text();
        var noticeDaysText = $('#summaryNoticeDays').text();
        
        // FIRST CONFIRMATION
        Swal.fire({
            title: '⚠️ Initiate Exit Process?',
            html: `
                <div style="text-align:left;padding:10px 0;">
                    <p style="font-weight:600;color:#1e293b;margin-bottom:8px;">
                        You are about to initiate the exit process for:
                    </p>
                    <p style="font-size:1.1rem;color:#4f46e5;font-weight:700;margin-bottom:12px;">
                        {{ $employee->name }}
                    </p>
                    <div style="background:#f8fafc;padding:12px 16px;border-radius:8px;margin-bottom:10px;">
                        <div style="display:flex;justify-content:space-between;padding:4px 0;border-bottom:1px solid #e2e8f0;">
                            <span style="color:#64748b;">Notice Period:</span>
                            <span style="font-weight:600;color:#1e293b;">${noticeDaysText}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:4px 0;border-bottom:1px solid #e2e8f0;">
                            <span style="color:#64748b;">Expected Exit Date:</span>
                            <span style="font-weight:600;color:#1e293b;">${endDate}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:4px 0;">
                            <span style="color:#64748b;">Exit Reason:</span>
                            <span style="font-weight:600;color:#1e293b;">${$('#exit_reason option:selected').text()}</span>
                        </div>
                    </div>
                    <p style="color:#64748b;font-size:0.9rem;margin-top:8px;">
                        <i class="fas fa-info-circle" style="color:#4f46e5;"></i>
                        The employee will remain active until the notice period ends.
                    </p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Initiate Exit',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-primary-modern',
                cancelButton: 'btn btn-outline-modern'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // SECOND CONFIRMATION - Final Warning
                Swal.fire({
                    title: '🔴 Final Confirmation Required',
                    html: `
                        <div style="padding:10px 0;">
                            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:12px;">
                                <p style="color:#991b1b;font-weight:600;margin-bottom:4px;">
                                    <i class="fas fa-exclamation-triangle" style="margin-right:8px;"></i>
                                    This action cannot be undone!
                                </p>
                                <p style="color:#7f1d1d;font-size:0.9rem;margin:0;">
                                    The employee will be placed on notice period and will be auto-exited on ${endDate}.
                                </p>
                            </div>
                            <p style="color:#64748b;font-size:0.9rem;">
                                Please type <strong style="color:#dc2626;">"CONFIRM"</strong> to proceed:
                            </p>
                            <input type="text" id="confirmInput" class="form-control" 
                                   placeholder="Type CONFIRM" 
                                   style="text-align:center;font-weight:600;border:2px solid #e2e8f0;border-radius:8px;padding:10px;margin-top:8px;">
                        </div>
                    `,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Finalize Exit',
                    cancelButtonText: 'Go Back',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        const input = Swal.getPopup().querySelector('#confirmInput');
                        if (input.value !== 'CONFIRM') {
                            Swal.showValidationMessage('Please type "CONFIRM" to proceed');
                            return false;
                        }
                        return true;
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((finalResult) => {
                    if (finalResult.isConfirmed) {
                        // Submit the form
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
    // COMPLETE EXIT NOW - DOUBLE CONFIRMATION
    // ========================================
    window.completeExitNow = function(id) {
        var employeeName = '{{ $employee->name }}';
        
        // FIRST CONFIRMATION
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
                    <p style="color:#64748b;font-size:0.9rem;margin-top:12px;">
                        <i class="fas fa-info-circle" style="color:#4f46e5;"></i>
                        This bypasses the notice period and completes the exit immediately.
                    </p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Complete Now',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger-modern',
                cancelButton: 'btn btn-outline-modern'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // SECOND CONFIRMATION - Final Warning
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
                                    All access will be revoked permanently.
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
                        // Show processing state
                        Swal.fire({
                            title: 'Processing...',
                            html: 'Completing exit process for ' + employeeName + '...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Submit the form
                        document.getElementById('completeExitForm').action = "{{ url('institute/admin/employee-exit/complete') }}/" + id;
                        document.getElementById('completeExitForm').submit();
                    }
                });
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
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Create a form to submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ url('institute/admin/employee-exit/reject') }}/" + id;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'rejection_reason';
                reasonInput.value = result.value.reason;
                form.appendChild(reasonInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    };

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