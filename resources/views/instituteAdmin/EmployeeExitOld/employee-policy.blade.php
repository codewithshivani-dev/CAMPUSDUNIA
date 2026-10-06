{{-- resources/views/instituteAdmin/EmployeeExit/employee-policy.blade.php --}}

@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('title', 'My Exit Policy')

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
    --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
}

.policy-page {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 4px;
}

/* ===== BACK BUTTON ===== */
.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: var(--gray-500);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    margin-bottom: 20px;
    padding: 8px 16px;
    background: #fff;
    border-radius: var(--radius-sm);
    border: 1px solid var(--gray-200);
}

.back-link:hover {
    color: var(--primary);
    border-color: var(--primary);
    text-decoration: none;
    box-shadow: var(--shadow-sm);
}

.back-link i {
    font-size: 0.8rem;
}

/* ===== HEADER ===== */
.policy-header {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    border-radius: var(--radius) var(--radius) 0 0;
    padding: 32px 36px;
    color: #fff;
    position: relative;
    overflow: hidden;
}

.policy-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
}

.policy-header::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -5%;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
}

.policy-header .header-content {
    display: flex;
    align-items: center;
    gap: 20px;
    z-index: 1;
    position: relative;
}

.policy-header .header-icon {
    width: 56px;
    height: 56px;
    background: rgba(255,255,255,0.15);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #fff;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.1);
}

.policy-header .header-text h4 {
    font-weight: 700;
    font-size: 1.4rem;
    margin: 0;
    letter-spacing: -0.3px;
}

.policy-header .header-text .subtitle {
    color: rgba(255,255,255,0.85);
    font-size: 0.9rem;
    margin: 0;
}

.policy-header .header-badge {
    margin-left: auto;
    background: rgba(255,255,255,0.15);
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid rgba(255,255,255,0.1);
    backdrop-filter: blur(4px);
}

/* ===== POLICY BODY ===== */
.policy-body {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-top: none;
    padding: 32px 36px;
    border-radius: 0 0 var(--radius) var(--radius);
    box-shadow: var(--shadow-sm);
}

/* ===== EMPLOYEE CARD ===== */
.employee-card {
    background: linear-gradient(135deg, var(--gray-50) 0%, #fff 100%);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-sm);
    padding: 16px 20px;
    margin-bottom: 28px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
}

.employee-card .emp-item {
    display: flex;
    flex-direction: column;
}

.employee-card .emp-item .label {
    font-size: 0.6rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--gray-500);
    font-weight: 600;
}

.employee-card .emp-item .value {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-top: 2px;
}

.employee-card .emp-item .value .badge {
    font-size: 0.65rem;
    padding: 2px 10px;
}

/* ===== SECTION ===== */
.policy-section {
    margin-bottom: 28px;
}

.policy-section:last-child {
    margin-bottom: 0;
}

.policy-section .section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
}

.policy-section .section-header .icon-box {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--primary-bg);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.policy-section .section-header h5 {
    font-weight: 600;
    font-size: 1rem;
    color: var(--gray-800);
    margin: 0;
}

.policy-section .section-header .count-badge {
    margin-left: auto;
    font-size: 0.65rem;
    padding: 2px 10px;
    background: var(--gray-100);
    color: var(--gray-600);
    border-radius: 12px;
}

/* ===== POLICY GRID ===== */
.policy-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}

.policy-grid .policy-item {
    background: var(--gray-50);
    border-radius: var(--radius-sm);
    padding: 14px 16px;
    text-align: center;
    border: 1px solid var(--gray-200);
    transition: all 0.2s ease;
}

.policy-grid .policy-item:hover {
    border-color: var(--primary);
    box-shadow: var(--shadow-sm);
}

.policy-grid .policy-item .label {
    font-size: 0.6rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    color: var(--gray-500);
    font-weight: 600;
}

.policy-grid .policy-item .value {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--gray-800);
    margin-top: 2px;
}

.policy-grid .policy-item .value .badge {
    font-size: 0.6rem;
    padding: 2px 10px;
}

/* ===== REQUIREMENTS GRID ===== */
.requirements-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.requirements-grid .req-card {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: var(--gray-50);
    border-radius: var(--radius-sm);
    border: 1px solid var(--gray-200);
    transition: all 0.2s ease;
}

.requirements-grid .req-card:hover {
    border-color: var(--primary);
}

.requirements-grid .req-card .req-icon {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
}

.requirements-grid .req-card .req-icon.interview { background: var(--info-bg); color: var(--info); }
.requirements-grid .req-card .req-icon.fnf { background: var(--warning-bg); color: var(--warning); }
.requirements-grid .req-card .req-icon.kt { background: var(--success-bg); color: var(--success); }
.requirements-grid .req-card .req-icon.clearance { background: var(--primary-bg); color: var(--primary); }

.requirements-grid .req-card .req-info {
    flex: 1;
}

.requirements-grid .req-card .req-info .req-label {
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--gray-700);
}

.requirements-grid .req-card .req-info .req-meta {
    font-size: 0.7rem;
    color: var(--gray-500);
}

.requirements-grid .req-card .req-status {
    margin-left: auto;
}

.requirements-grid .req-card .req-status .badge {
    font-size: 0.55rem;
    padding: 2px 10px;
}

/* ===== ITEMS WRAPPER ===== */
.items-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.items-wrapper .tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 14px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 500;
}

.items-wrapper .tag i {
    font-size: 0.5rem;
}

.items-wrapper .tag.tag-fnf {
    background: var(--warning-bg);
    color: #92400e;
    border: 1px solid #fde68a;
}

.items-wrapper .tag.tag-kt {
    background: var(--success-bg);
    color: #166534;
    border: 1px solid #bbf7d0;
}

.items-wrapper .tag.tag-additional {
    background: var(--gray-100);
    color: var(--gray-700);
    border: 1px solid var(--gray-200);
}

.items-wrapper .tag.tag-override {
    background: var(--primary-bg);
    color: var(--primary-dark);
    border: 1px solid #c7d2fe;
}

.items-wrapper .tag .highlight {
    background: var(--success);
    color: #fff;
    font-size: 0.5rem;
    padding: 1px 6px;
    border-radius: 4px;
    margin-left: 4px;
}

/* ===== DESCRIPTION BOX ===== */
.description-box {
    background: var(--gray-50);
    border-radius: var(--radius-sm);
    padding: 14px 18px;
    border-left: 4px solid var(--primary);
    font-size: 0.9rem;
    color: var(--gray-700);
    line-height: 1.6;
}

/* ===== TERMS BOX ===== */
.terms-box {
    background: var(--warning-bg);
    border-radius: var(--radius-sm);
    padding: 14px 18px;
    border-left: 4px solid var(--warning);
    font-size: 0.9rem;
    color: #78350f;
    line-height: 1.6;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 50px 20px;
}

.empty-state .empty-icon {
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

/* ===== RESPONSIVE ===== */
@media (max-width: 992px) {
    .policy-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .policy-header {
        padding: 20px 24px;
    }
    .policy-body {
        padding: 20px 24px;
    }
    .policy-header .header-content {
        flex-wrap: wrap;
    }
    .policy-header .header-badge {
        margin-left: 0;
        width: 100%;
        text-align: center;
    }
    .requirements-grid {
        grid-template-columns: 1fr;
    }
    .employee-card {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 576px) {
    .policy-grid {
        grid-template-columns: 1fr;
    }
    .employee-card {
        grid-template-columns: 1fr;
    }
    .policy-header .header-icon {
        width: 44px;
        height: 44px;
        font-size: 18px;
    }
    .policy-header .header-text h4 {
        font-size: 1.1rem;
    }
}
</style>

<div class="policy-page">
    
    {{-- BACK BUTTON --}}
    <a href="{{ route('employee.exit.dashboard') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>

    {{-- HEADER --}}
    <div class="policy-header">
        <div class="header-content">
            <div class="header-icon">
                <i class="fas fa-file-contract"></i>
            </div>
            <div class="header-text">
                <h4>My Exit Policy</h4>
                <p class="subtitle">Your assigned exit policy details and requirements</p>
            </div>
            @if($exitPolicy)
            <div class="header-badge">
                <i class="fas fa-check-circle"></i> Active Policy
            </div>
            @endif
        </div>
    </div>

    {{-- POLICY BODY --}}
    <div class="policy-body">
        
        @if($exitPolicy)
            {{-- Employee Information --}}
            <div class="employee-card">
                <div class="emp-item">
                    <span class="label">Employee</span>
                    <span class="value">{{ $employee->name ?? 'N/A' }}</span>
                </div>
                <div class="emp-item">
                    <span class="label">Employee Code</span>
                    <span class="value">{{ $employee->employee_code ?? 'N/A' }}</span>
                </div>
                <div class="emp-item">
                    <span class="label">Department</span>
                    <span class="value">{{ $employee->department->department ?? 'N/A' }}</span>
                </div>
                <div class="emp-item">
                    <span class="label">Employment Type</span>
                    <span class="value">
                        <span class="badge bg-warning text-dark">{{ $employee->employment_type ?? 'N/A' }}</span>
                    </span>
                </div>
            </div>

            {{-- Policy Details --}}
            <div class="policy-section">
                <div class="section-header">
                    <div class="icon-box">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h5>Policy Details</h5>
                </div>
                <div class="policy-grid">
                    <div class="policy-item">
                        <div class="label">Policy Name</div>
                        <div class="value" style="font-size:0.85rem;">{{ $exitPolicy->policy_name ?? 'N/A' }}</div>
                    </div>
                    <div class="policy-item">
                        <div class="label">Policy Code</div>
                        <div class="value">
                            <span class="badge bg-secondary">{{ $exitPolicy->policy_code ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="policy-item">
                        <div class="label">Exit Type</div>
                        <div class="value">
                            <span class="badge bg-primary">{{ $exitPolicy->exit_type ?? 'Resignation' }}</span>
                        </div>
                    </div>
                    <div class="policy-item">
                        <div class="label">Notice Period</div>
                        <div class="value">{{ $exitPolicy->notice_period_days ?? $exitPolicy->default_notice_period ?? 'N/A' }} Days</div>
                    </div>
                </div>
            </div>

            {{-- Requirements --}}
            <div class="policy-section">
                <div class="section-header">
                    <div class="icon-box">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h5>Requirements</h5>
                </div>
                <div class="requirements-grid">
                    <div class="req-card">
                        <div class="req-icon interview">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="req-info">
                            <div class="req-label">Exit Interview</div>
                            <div class="req-meta">
                                @if($exitPolicy->exit_interview_required && $exitPolicy->interview_days)
                                    {{ $exitPolicy->interview_days }} days before exit
                                @else
                                    Not applicable
                                @endif
                            </div>
                        </div>
                        <div class="req-status">
                            <span class="badge {{ $exitPolicy->exit_interview_required ? 'bg-success' : 'bg-secondary' }}">
                                {{ $exitPolicy->exit_interview_required ? 'Required' : 'Not Required' }}
                            </span>
                        </div>
                    </div>

                    <div class="req-card">
                        <div class="req-icon fnf">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <div class="req-info">
                            <div class="req-label">FNF Settlement</div>
                            <div class="req-meta">
                                @if($exitPolicy->fnf_required && $exitPolicy->fnf_processing_days)
                                    {{ $exitPolicy->fnf_processing_days }} days processing
                                @else
                                    Not applicable
                                @endif
                            </div>
                        </div>
                        <div class="req-status">
                            <span class="badge {{ $exitPolicy->fnf_required ? 'bg-success' : 'bg-secondary' }}">
                                {{ $exitPolicy->fnf_required ? 'Required' : 'Not Required' }}
                            </span>
                        </div>
                    </div>

                    <div class="req-card">
                        <div class="req-icon kt">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="req-info">
                            <div class="req-label">Knowledge Transfer</div>
                            <div class="req-meta">
                                @if($exitPolicy->kt_required && $exitPolicy->kt_days)
                                    {{ $exitPolicy->kt_days }} days duration
                                @else
                                    Not applicable
                                @endif
                            </div>
                        </div>
                        <div class="req-status">
                            <span class="badge {{ $exitPolicy->kt_required ? 'bg-success' : 'bg-secondary' }}">
                                {{ $exitPolicy->kt_required ? 'Required' : 'Not Required' }}
                            </span>
                        </div>
                    </div>

                    <div class="req-card">
                        <div class="req-icon clearance">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <div class="req-info">
                            <div class="req-label">Clearance Workflow</div>
                            <div class="req-meta">
                                {{ ucfirst($exitPolicy->clearance_workflow ?? 'N/A') }}
                                @if($exitPolicy->clearance_days)
                                    ({{ $exitPolicy->clearance_days }} days)
                                @endif
                            </div>
                        </div>
                        <div class="req-status">
                            <span class="badge bg-info">{{ ucfirst($exitPolicy->clearance_workflow ?? 'N/A') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FNF Items --}}
            @if($exitPolicy->fnf_required && !empty($exitPolicy->fnf_items))
            <div class="policy-section">
                <div class="section-header">
                    <div class="icon-box">
                        <i class="fas fa-list"></i>
                    </div>
                    <h5>FNF Items</h5>
                    <span class="count-badge">{{ count($exitPolicy->fnf_items) }} items</span>
                </div>
                <div class="items-wrapper">
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
                        <span class="tag tag-fnf">
                            <i class="fas fa-check-circle"></i>
                            {{ $fnfLabels[$item] ?? ucfirst(str_replace('_', ' ', $item)) }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- KT Requirements --}}
            @if($exitPolicy->kt_required && !empty($exitPolicy->kt_requirements))
            <div class="policy-section">
                <div class="section-header">
                    <div class="icon-box">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h5>KT Requirements</h5>
                    <span class="count-badge">{{ count($exitPolicy->kt_requirements) }} items</span>
                </div>
                <div class="items-wrapper">
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
                    @foreach($exitPolicy->kt_requirements as $item)
                        <span class="tag tag-kt">
                            <i class="fas fa-check-circle"></i>
                            {{ $ktLabels[$item] ?? ucfirst(str_replace('_', ' ', $item)) }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Additional Requirements --}}
            @if(!empty($exitPolicy->additional_requirements))
            <div class="policy-section">
                <div class="section-header">
                    <div class="icon-box">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <h5>Additional Requirements</h5>
                    <span class="count-badge">{{ count($exitPolicy->additional_requirements) }} items</span>
                </div>
                <div class="items-wrapper">
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
                        <span class="tag tag-additional">
                            <i class="fas fa-check-circle"></i>
                            {{ $additionalLabels[$item] ?? ucfirst(str_replace('_', ' ', $item)) }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Employment Type Overrides --}}
            @if(!empty($exitPolicy->employment_notice_periods))
            <div class="policy-section">
                <div class="section-header">
                    <div class="icon-box">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h5>Employment Type Overrides</h5>
                </div>
                <div class="items-wrapper">
                    @foreach($exitPolicy->employment_notice_periods as $type => $days)
                        @php
                            $customDays = $exitPolicy->employment_custom_days[$type] ?? null;
                            $displayDays = ($days === 'custom' && $customDays) ? $customDays : $days;
                            $isYourType = ($type === $employee->employment_type);
                        @endphp
                        <span class="tag tag-override">
                            <strong>{{ $type }}</strong>: {{ $displayDays }} days
                            
                        </span>
                    @endforeach
                </div>
                <div style="margin-top: 10px; font-size: 0.8rem; color: var(--gray-500);">
                    <i class="fas fa-info-circle"></i> 
                    Your employment type: <strong>{{ $employee->employment_type ?? 'N/A' }}</strong>
                    @if($exitPolicy->notice_period_days)
                        <span class="text-success ms-2">
                            <i class="fas fa-check-circle"></i> Notice period: {{ $exitPolicy->notice_period_days }} days
                        </span>
                    @endif
                </div>
            </div>
            @endif

            {{-- Description --}}
            @if($exitPolicy->description)
            <div class="policy-section">
                <div class="section-header">
                    <div class="icon-box">
                        <i class="fas fa-align-left"></i>
                    </div>
                    <h5>Description</h5>
                </div>
                <div class="description-box">
                    {{ $exitPolicy->description }}
                </div>
            </div>
            @endif

            {{-- Terms & Conditions --}}
            @if($exitPolicy->terms_conditions)
            <div class="policy-section">
                <div class="section-header">
                    <div class="icon-box">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <h5>Terms & Conditions</h5>
                </div>
                <div class="terms-box">
                    {{ $exitPolicy->terms_conditions }}
                </div>
            </div>
            @endif

        @else
            {{-- No Policy --}}
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <h5>No Exit Policy Assigned</h5>
                <p>You don't have any exit policy assigned yet. Please contact HR for more information.</p>
            </div>
        @endif

    </div>
</div>
@endsection