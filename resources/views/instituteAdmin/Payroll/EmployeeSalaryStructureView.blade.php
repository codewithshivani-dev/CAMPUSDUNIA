@extends('instituteAdmin.Payroll.PayrollManagement')
@section('payroll-content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    /* ── Base ── */
    .employee-structure-shell {
        padding-bottom: 32px;
    }
    
    /* ── Page Header ── */
    .page-shell-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px 28px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        border: 1px solid #eef2f6;
        margin-bottom: 24px;
    }
    .page-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }
    .page-heading-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .page-heading-left .avatar-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #f0f4ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4f46e5;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .page-heading-text h2 {
        margin: 0;
        font-size: 1.4rem;
        font-weight: 600;
        color: #111827;
        letter-spacing: -0.01em;
    }
    .page-heading-text .subtitle {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 2px;
    }
    .page-heading-text .subtitle span {
        color: #6b7280;
        font-size: 0.85rem;
    }
    .page-heading-text .subtitle .badge-dept {
        background: #f3f4f6;
        color: #374151;
        padding: 1px 12px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .page-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }
    .btn-pill {
        padding: 7px 18px;
        border-radius: 30px;
        font-weight: 500;
        font-size: 0.82rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    .btn-pill-outline {
        background: transparent;
        border: 1.5px solid #e5e7eb;
        color: #4b5563;
    }
    .btn-pill-outline:hover {
        border-color: #9ca3af;
        background: #f9fafb;
    }
    .btn-pill-primary {
        background: #4f46e5;
        color: white;
    }
    .btn-pill-primary:hover {
        background: #4338ca;
        color: white;
    }
    .btn-pill-success {
        background: #10b981;
        color: white;
    }
    .btn-pill-success:hover {
        background: #059669;
        color: white;
    }

    /* ── Summary Grid ── */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }
    .summary-tile {
        background: white;
        border-radius: 14px;
        padding: 14px 18px;
        border: 1px solid #eef2f6;
        transition: all 0.2s;
    }
    .summary-tile:hover {
        border-color: #d1d5db;
    }
    .summary-tile .summary-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #9ca3af;
        font-weight: 600;
    }
    .summary-tile .summary-value {
        font-size: 1.2rem;
        font-weight: 600;
        color: #111827;
        margin-top: 2px;
    }

    /* ── Structure Cards ── */
    .structure-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #eef2f6;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
        margin-bottom: 24px;
        overflow: hidden;
        transition: all 0.2s;
    }
    .structure-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        border-color: #e5e7eb;
    }

    /* ── Card Header ── */
    .structure-card-header {
        background: #fafbfc;
        padding: 16px 24px;
        border-bottom: 1px solid #eef2f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    .structure-card-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }
    .structure-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .structure-card-title .year-badge {
        background: #f3f4f6;
        color: #374151;
        padding: 2px 12px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 12px 3px 8px;
        border-radius: 30px;
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .status-badge i {
        font-size: 0.5rem;
    }
    .status-badge.active {
        background: #ecfdf5;
        color: #065f46;
    }
    .status-badge.inactive {
        background: #fef2f2;
        color: #991b1b;
    }
    .structure-card-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .pill-meta {
        border-radius: 30px;
        padding: 2px 12px;
        font-size: 0.65rem;
        font-weight: 500;
        background: #f3f4f6;
        color: #4b5563;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .pill-meta i {
        font-size: 0.6rem;
        opacity: 0.6;
    }

    .structure-card-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        align-items: center;
    }
    .btn-sm-pill {
        padding: 3px 12px;
        border-radius: 30px;
        font-size: 0.72rem;
        font-weight: 500;
        border: 1.5px solid transparent;
        background: transparent;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
    }
    .btn-sm-pill-view {
        border-color: #a6a8aa;
        color: #4b5563;
    }
    .btn-sm-pill-view:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
    }
    .btn-sm-pill-edit {
        border-color: #e5e7eb;
        color: #4b5563;
    }
    .btn-sm-pill-edit:hover {
        background: #f3f4f6;
        border-color: #d1d5db;
    }
    .btn-sm-pill-danger {
        border-color: #fecaca;
        color: #991b1b;
    }
    .btn-sm-pill-danger:hover {
        background: #fef2f2;
        border-color: #fca5a5;
    }
    .btn-sm-pill-success-action {
        border-color: #a7f3d0;
        color: #065f46;
    }
    .btn-sm-pill-success-action:hover {
        background: #ecfdf5;
        border-color: #6ee7b7;
    }

    /* ── Card Body ── */
    .structure-card-body {
        padding: 20px 24px;
    }

    /* ── CTC Summary Card ── */
    .ctc-summary-card {
        background: #fafbfc;
        border-radius: 12px;
        padding: 16px 20px;
        border: 1px solid #eef2f6;
        margin-bottom: 20px;
    }
    .ctc-summary-card .ctc-title {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #9ca3af;
        font-weight: 600;
        margin-bottom: 12px;
    }
    .ctc-grid {
        /* display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 12px 24px; */
    }
    .ctc-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        border-bottom: 1px solid #eef2f6;
    }
    .ctc-item:last-child {
        border-bottom: none;
    }
    .ctc-item .label {
        font-size: 0.82rem;
        /* color: #6b7280; */
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .ctc-item .label i {
        font-size: 0.75rem;
        color: #9ca3af;
        width: 16px;
    }
    .ctc-item .value {
        font-size: 0.88rem;
        font-weight: 600;
        color: #111827;
    }
    .ctc-item .value.highlight {
        color: #4f46e5;
        font-weight: 700;
    }
    .ctc-item .value.success {
        color: #059669;
        font-weight: 700;
    }
    .ctc-item.emphasis {
        background: #f0f4ff;
        border-radius: 8px;
        padding: 8px 12px;
        margin: 4px 0;
        border-bottom: none;
    }
    .ctc-item.emphasis .label {
        font-weight: 600;
        color: #111827;
    }
    .ctc-item.emphasis .value {
        color: #4f46e5;
        font-weight: 700;
        font-size: 0.95rem;
    }
    .ctc-item.success-emphasis {
        background: #ecfdf5;
        border-radius: 8px;
        padding: 8px 12px;
        margin: 4px 0;
        border-bottom: none;
    }
    .ctc-item.success-emphasis .label {
        font-weight: 600;
        color: #065f46;
    }
    .ctc-item.success-emphasis .value {
        color: #059669;
        font-weight: 700;
        font-size: 0.95rem;
    }

    /* ── Breakdown Grid ── */
    .breakdown-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px 32px;
    }
    @media (max-width: 768px) {
        .breakdown-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
    }

    .breakdown-section {
        background: #ffffff;
        border-radius: 12px;
        padding: 14px 16px;
        border: 1px solid #eef2f6;
    }
    .breakdown-section .section-title {
        font-size: 0.7rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        color: #9ca3af !important;
        font-weight: 600 !important;
        margin-bottom: 10px !important;
        display: flex !important;
        align-items: center;
        gap: 6px ;
    }
    .breakdown-section .section-title i {
        font-size: 0.75re !important;
        color: #9ca3af !important;
    }
    .breakdown-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 5px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .breakdown-item:last-child {
        border-bottom: none;
    }
    .breakdown-item .label {
        font-size: 0.8rem;
        color: #6b7280;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .breakdown-item .label i {
        font-size: 0.7rem;
        color: #9ca3af;
        width: 16px;
    }
    .breakdown-item .value {
        font-size: 0.85rem;
        font-weight: 500;
        color: #111827;
    }
    .breakdown-item .value.danger {
        color: #dc2626;
    }
    .breakdown-item .value.success-text {
        color: #059669;
    }
    .breakdown-item .value .sub {
        font-weight: 400;
        color: #9ca3af;
        font-size: 0.65rem;
        margin-left: 3px;
    }
    .breakdown-item.total-row {
        background: #fafbfc;
        border-radius: 6px;
        padding: 6px 10px;
        margin-top: 4px;
        border-bottom: none;
    }
    .breakdown-item.total-row .label {
        font-weight: 600;
        color: #111827;
    }
    .breakdown-item.total-row .value {
        font-weight: 700;
    }

    /* ── Empty State ── */
    .empty-card {
        background: white;
        border-radius: 18px;
        border: 2px dashed #e5e7eb;
        padding: 50px 30px;
        text-align: center;
        color: #6b7280;
    }
    .empty-card i {
        color: #d1d5db;
        margin-bottom: 14px;
    }
    .empty-card h4 {
        color: #111827;
        margin-bottom: 6px;
        font-weight: 600;
    }

    /* ── Toast ── */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .toast-message {
        padding: 12px 18px;
        border-radius: 12px;
        color: white;
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        display: flex;
        align-items: center;
        gap: 10px;
        animation: slideIn 0.3s ease;
        font-weight: 500;
        font-size: 0.9rem;
    }
    .toast-message.success { background: #10b981; }
    .toast-message.error { background: #ef4444; }
    .toast-message .close-btn {
        background: none;
        border: none;
        color: rgba(255,255,255,0.8);
        font-size: 1.1rem;
        cursor: pointer;
        margin-left: auto;
        padding: 0 4px;
    }
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    /* ── Responsive ── */
    @media (max-width: 640px) {
        .page-shell-card {
            padding: 16px;
        }
        .page-heading {
            flex-direction: column;
            align-items: stretch;
        }
        .page-heading-left {
            gap: 10px;
        }
        .page-heading-left .avatar-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        .page-heading-text h2 {
            font-size: 1.2rem;
        }
        .summary-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .summary-tile {
            padding: 10px 14px;
        }
        .summary-tile .summary-value {
            font-size: 1rem;
        }
        .structure-card-header {
            flex-direction: column;
            align-items: stretch;
            padding: 14px 16px;
        }
        .structure-card-actions {
            justify-content: flex-start;
        }
        .structure-card-body {
            padding: 14px 16px;
        }
        .ctc-grid {
            grid-template-columns: 1fr;
            gap: 4px;
        }
        .breakdown-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
        .ctc-summary-card {
            padding: 12px 14px;
        }
        .breakdown-section {
            padding: 10px 12px;
        }
        .page-actions {
            justify-content: flex-start;
        }
        .btn-pill {
            padding: 6px 14px;
            font-size: 0.78rem;
        }
    }
</style>

<div class="employee-structure-shell">
    <!-- ===== PAGE HEADER ===== -->
    <div class="page-shell-card">
        <div class="page-heading">
            <div class="page-heading-left">
                <div class="avatar-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="page-heading-text">
                    <h2>Salary Structures</h2>
                    <div class="subtitle">
                        <span><strong>{{ $employee->name ?? 'Employee' }}</strong></span>
                        @if($employee->employee_code)
                            <span>({{ $employee->employee_code }})</span>
                        @endif
                        @if($department)
                            <span class="badge-dept">{{ $department->department ?? 'No Department' }}</span>
                        @endif
                        <span style="color:#9ca3af; font-size:0.75rem;">
                            <i class="far fa-calendar-alt"></i> 
                            {{ $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('d M Y') : '—' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="page-actions">
                <a href="{{ route('institute.ctc.salary.details', $employee->employee_id) }}" class="btn-pill btn-pill-outline">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <a href="{{ route('institute.ctc.salary.structures.download', $employee->employee_id) }}" class="btn-pill btn-pill-success">
                    <i class="fas fa-download"></i> Report
                </a>
                <a href="{{ route('institute.ctc.salary-structure.create') }}" class="btn-pill btn-pill-primary">
                    <i class="fas fa-plus"></i> New Structure
                </a>
            </div>
        </div>
    </div>

    <!-- ===== SUMMARY STATS ===== -->
    <div class="summary-grid">
        <div class="summary-tile">
            <div class="summary-label">Total Structures</div>
            <div class="summary-value">{{ $summary['total_structures'] ?? 0 }}</div>
        </div>
        <div class="summary-tile">
            <div class="summary-label">Active</div>
            <div class="summary-value">{{ $summary['active_structures'] ?? 0 }}</div>
        </div>
        <div class="summary-tile">
            <div class="summary-label">Inactive</div>
            <div class="summary-value">{{ $summary['inactive_structures'] ?? 0 }}</div>
        </div>
        <div class="summary-tile">
            <div class="summary-label">Total CTC (Annual)</div>
            <div class="summary-value">₹{{ number_format($summary['total_ctc'] ?? 0) }}</div>
        </div>
        <div class="summary-tile">
            <div class="summary-label">Latest Net Salary</div>
            <div class="summary-value">₹{{ number_format($summary['latest_net_monthly'] ?? 0) }}</div>
        </div>
    </div>

    <!-- ===== STRUCTURES LIST ===== -->
    @if(empty($structuredData))
        <div class="empty-card">
            <i class="fas fa-file-invoice fa-3x"></i>
            <h4>No Salary Structures Found</h4>
            <p class="text-muted" style="color:#9ca3af;">This employee does not have any salary structures yet.</p>
            <a href="{{ route('institute.ctc.salary-structure.create', ['employee_id' => $employee->employee_id]) }}" class="btn-pill btn-pill-primary" style="display:inline-flex; margin-top:14px;">
                <i class="fas fa-plus"></i> Create First Structure
            </a>
        </div>
    @else
        @foreach($structuredData as $data)
            @php
                $structure = $data['structure'];
                $preview = $data['preview'] ?? null;
                $stats = $data['statistics'] ?? [];
                $isActive = ($structure->status ?? 'active') === 'active';
                $statusText = ucfirst($structure->status ?? 'Active');
                $employmentType = ucfirst($structure->employment_type ?? 'Full-time');
                $financialYear = $structure->financial_year ?? 'Current Year';
            @endphp

            <div class="structure-card">
                <!-- Card Header -->
                <div class="structure-card-header">
                    <div class="structure-card-header-left">
                        <div class="structure-card-title">
                            <i class="fas fa-calendar-alt" style="color:#4f46e5; font-size:0.9rem;"></i>
                            <span>{{ $financialYear }}</span>
                            <span class="year-badge">{{ $employmentType }}</span>

                        </div>
                        <div class="structure-card-meta">
                            <span class="pill-meta">
                                <i class="far fa-clock"></i> 
                                {{ $structure->created_at ? \Carbon\Carbon::parse($structure->created_at)->format('d M Y') : '—' }}
                            </span>
                            @if($structure->payroll_policy_id)
                                <span class="pill-meta">
                                    <i class="fas fa-file-contract"></i> 
                                    Policy: {{ substr($structure->payroll_policy_id, 0, 8) }}
                                </span>
                            @endif
                            <span class="status-badge {{ $isActive ? 'active' : 'inactive' }}">
                                <i class="fas fa-circle"></i> {{ $statusText }}
                            </span>

                        </div>
                    </div>
                    <div class="structure-card-actions">
                        <a href="{{ route('institute.ctc.salary.details', $structure->salary_structure_id) }}" class="btn-sm-pill btn-sm-pill-view">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <!-- <a href="{{ route('institute.ctc.salary-structure.edit', $structure->salary_structure_id) }}" class="btn-sm-pill btn-sm-pill-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @if($isActive)
                            <button onclick="inactivateStructure('{{ $structure->salary_structure_id }}')" class="btn-sm-pill btn-sm-pill-danger">
                                <i class="fas fa-times-circle"></i> Inactivate
                            </button>
                        @else
                            <button onclick="activateStructure('{{ $structure->salary_structure_id }}')" class="btn-sm-pill btn-sm-pill-success-action">
                                <i class="fas fa-check-circle"></i> Activate
                            </button>
                        @endif -->
                    </div>
                </div>

                <!-- Card Body -->
                <div class="structure-card-body">
                    <!-- ===== CTC SUMMARY CARD ===== -->
                    <div class="ctc-summary-card">
                        <div class="ctc-title"><i class="fas fa-coins"></i> CTC Summary</div>
                        <div class="ctc-grid">
                            <div>
                                <div class="ctc-item">
                                    <span class="label"><i class="fas fa-bullseye"></i> Fixed CTC</span>
                                    <span class="value">₹{{ number_format($structure->fixed_ctc_annual ?? 0) }}</span>
                                </div>
                                <div class="ctc-item">
                                    <span class="label"><i class="fas fa-chart-line"></i> Variable CTC</span>
                                    <span class="value">₹{{ number_format($structure->variable_ctc_annual ?? 0) }}</span>
                                </div>
                                <div class="ctc-item">
                                    <span class="label"><i class="fas fa-user-check"></i> Basic (Monthly)</span>
                                    <span class="value">₹{{ number_format($structure->basic_salary_monthly ?? 0) }}</span>
                                </div>
                            </div>
                            <div>
                                <div class="ctc-item emphasis">
                                    <span class="label"><i class="fas fa-file-invoice"></i> <strong>Total CTC (Annual)</strong></span>
                                    <span class="value highlight">₹{{ number_format($structure->total_ctc_annual ?? 0) }}</span>
                                </div>
                                <div class="ctc-item">
                                    <span class="label"><i class="fas fa-wallet"></i> Net Salary (Monthly)</span>
                                    <span class="value">₹{{ number_format($stats['net_monthly'] ?? 0) }}</span>
                                </div>
                                <div class="ctc-item">
                                    <span class="label"><i class="fas fa-percent"></i> CTC Utilized</span>
                                    <span class="value">{{ $stats['ctc_utilized_percentage'] ?? 0 }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ===== BREAKDOWN GRID ===== -->
                    <div class="breakdown-grid">
                        <!-- Left: Gross & Earnings -->
                        <div class="breakdown-section">
                            <div class="section-title"><i class="fas fa-chart-pie"></i> Earnings & Gross</div>
                            
                            @if($preview)
                                <div class="breakdown-item">
                                    <span class="label"><i class="fas fa-arrow-up" style="color:#059669;"></i> Gross Monthly</span>
                                    <span class="value success-text">₹{{ number_format($preview->gross_salary_monthly ?? 0) }}</span>
                                </div>
                                @if(isset($preview->total_earnings_monthly))
                                <div class="breakdown-item">
                                    <span class="label"><i class="fas fa-plus-circle" style="color:#059669;"></i> Total Earnings</span>
                                    <span class="value">₹{{ number_format($preview->total_earnings_monthly ?? 0) }}</span>
                                </div>
                                @endif
                                <div class="breakdown-item total-row">
                                    <span class="label"><i class="fas fa-calendar-check"></i> <strong>Net Annual</strong></span>
                                    <span class="value success-text">₹{{ number_format($preview->net_salary_annual ?? 0) }}</span>
                                </div>
                            @else
                                <div class="breakdown-item">
                                    <span class="label"><i class="fas fa-arrow-up" style="color:#059669;"></i> Gross Monthly</span>
                                    <span class="value">—</span>
                                </div>
                                <div class="breakdown-item total-row">
                                    <span class="label"><i class="fas fa-calendar-check"></i> <strong>Net Annual</strong></span>
                                    <span class="value">—</span>
                                </div>
                            @endif
                        </div>

                        <!-- Right: Deductions & Cost -->
                        <div class="breakdown-section">
                            <div class="section-title"><i class="fas fa-minus-circle" style="color:#9ca3af;"></i> Deductions & Cost</div>
                            
                            @if($preview)
                                <div class="breakdown-item">
                                    <span class="label"><i class="fas fa-arrow-down" style="color:#dc2626;"></i> Total Deductions</span>
                                    <span class="value danger">₹{{ number_format($preview->total_deductions_monthly ?? 0) }}</span>
                                </div>
                                <div class="breakdown-item">
                                    <span class="label"><i class="fas fa-building"></i> Employer Cost</span>
                                    <span class="value">₹{{ number_format($preview->total_cost_monthly ?? 0) }}</span>
                                </div>
                                <div class="breakdown-item total-row">
                                    <span class="label"><i class="fas fa-wallet"></i> <strong>Net Monthly</strong></span>
                                    <span class="value success-text">₹{{ number_format($preview->net_salary_monthly ?? 0) }}</span>
                                </div>
                            @else
                                <div class="breakdown-item">
                                    <span class="label"><i class="fas fa-arrow-down" style="color:#dc2626;"></i> Total Deductions</span>
                                    <span class="value">—</span>
                                </div>
                                <div class="breakdown-item">
                                    <span class="label"><i class="fas fa-building"></i> Employer Cost</span>
                                    <span class="value">—</span>
                                </div>
                                <div class="breakdown-item total-row">
                                    <span class="label"><i class="fas fa-wallet"></i> <strong>Net Monthly</strong></span>
                                    <span class="value">—</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div><!-- /structure-card-body -->
            </div><!-- /structure-card -->
        @endforeach
    @endif
</div>

<!-- ===== ACTION CONFIRMATION MODAL ===== -->
<div class="modal fade" id="structureActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,0.12);">
            <div class="modal-header" style="border-bottom:1px solid #eef2f6; padding:16px 24px;">
                <h5 class="modal-title" id="actionModalTitle" style="font-weight:600; color:#111827; font-size:1rem;">Confirm Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="actionModalBody" style="padding:20px 24px;">
                <!-- Dynamic content -->
            </div>
            <div class="modal-footer" style="border-top:1px solid #eef2f6; padding:12px 24px;">
                <button type="button" class="btn-pill btn-pill-outline" data-bs-dismiss="modal" style="font-size:0.8rem;">Cancel</button>
                <button type="button" id="confirmActionBtn" class="btn-pill btn-pill-primary" style="font-size:0.8rem;">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ===== LOADING SPINNER ===== -->
<div id="loadingSpinner" class="text-center py-3" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<!-- ===== JAVASCRIPT ===== -->
<script>
    function inactivateStructure(structureId) {
        const modal = new bootstrap.Modal(document.getElementById('structureActionModal'));
        document.getElementById('actionModalTitle').textContent = 'Inactivate Salary Structure';
        document.getElementById('actionModalBody').innerHTML = `
            <div class="text-center mb-3">
                <i class="fas fa-exclamation-triangle" style="font-size:2.4rem; color:#f59e0b;"></i>
            </div>
            <p style="font-size:0.95rem; text-align:center; margin-bottom:4px;">
                Are you sure you want to <strong>inactivate</strong> this salary structure?
            </p>
            <p style="color:#9ca3af; text-align:center; font-size:0.8rem;">
                This will mark the structure as inactive and it won't be used for payroll calculations.
            </p>
            <div class="alert alert-warning mt-3" style="border-radius:8px; padding:10px 14px; font-size:0.8rem; background:#fffbeb; border:1px solid #fde68a; color:#92400e;">
                <i class="fas fa-info-circle"></i>
                <span>Only active structures can be used for payroll processing.</span>
            </div>
        `;
        document.getElementById('confirmActionBtn').onclick = function() {
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
            performStructureAction(structureId, 'inactivate', modal);
        };
        modal.show();
    }

    function activateStructure(structureId) {
        const modal = new bootstrap.Modal(document.getElementById('structureActionModal'));
        document.getElementById('actionModalTitle').textContent = 'Activate Salary Structure';
        document.getElementById('actionModalBody').innerHTML = `
            <div class="text-center mb-3">
                <i class="fas fa-check-circle" style="font-size:2.4rem; color:#10b981;"></i>
            </div>
            <p style="font-size:0.95rem; text-align:center; margin-bottom:4px;">
                Are you sure you want to <strong>activate</strong> this salary structure?
            </p>
            <p style="color:#9ca3af; text-align:center; font-size:0.8rem;">
                This will mark the structure as active and available for payroll calculations.
            </p>
            <div class="alert alert-info mt-3" style="border-radius:8px; padding:10px 14px; font-size:0.8rem; background:#eff6ff; border:1px solid #93c5fd; color:#1e40af;">
                <i class="fas fa-info-circle"></i>
                <span>Only one active structure per employee per financial year is allowed.</span>
            </div>
        `;
        document.getElementById('confirmActionBtn').onclick = function() {
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
            performStructureAction(structureId, 'activate', modal);
        };
        modal.show();
    }

    function performStructureAction(structureId, action, modal) {
        const loading = document.getElementById('loadingSpinner');
        loading.style.display = 'block';
        fetch(`/institute/admin/ctc-salary-configuration/salary-structure/${structureId}/${action}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            loading.style.display = 'none';
            modal.hide();
            if (data.success) {
                showToast('success', data.message || 'Structure updated successfully');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast('error', data.message || 'Failed to update structure');
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            modal.hide();
            showToast('error', 'An error occurred. Please try again.');
            console.error('Error:', error);
        });
    }

    function showToast(type, message) {
        const container = document.getElementById('toastContainer') || (() => {
            const c = document.createElement('div');
            c.id = 'toastContainer';
            c.className = 'toast-container';
            document.body.appendChild(c);
            return c;
        })();
        const toast = document.createElement('div');
        toast.className = `toast-message ${type}`;
        toast.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
            <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
        `;
        container.appendChild(toast);
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    }
</script>

@endsection