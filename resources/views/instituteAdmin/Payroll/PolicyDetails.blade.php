@extends('instituteAdmin.Payroll.PayrollManagement')
@section('payroll-content')

<title>Payroll Policy Details | View Policy</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
        --primary-color: #4361ee;
        --secondary-color: #3a0ca3;
        --success-gradient: linear-gradient(135deg, #10b981, #059669);
        --success-color: #10b981;
        --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
        --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
        --info-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
        --border-color: #e2e8f0;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --bg-light: #f8fafc;
        --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        --transition: all 0.2s ease;
    }

    .policy-detail-container {
        max-width: 1400px;
        margin: 30px auto;
        padding: 0 24px;
    }

    /* Header Section */
    .detail-header {
        background: var(--primary-gradient);
        border-radius: 20px;
        padding: 25px 30px;
        margin-bottom: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.25);
    }

    .header-title h2 {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
        color: white;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-title h2 i {
        background: rgba(255, 255, 255, 0.2);
        padding: 12px;
        border-radius: 14px;
    }

    .header-title p {
        margin: 10px 0 0;
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.85rem;
    }

    .action-buttons-header {
        display: flex;
        gap: 12px;
    }

    .btn-outline-custom {
        border-radius: 40px;
        padding: 10px 24px;
        font-weight: 600;
        transition: var(--transition);
        cursor: pointer;
        border: none;
    }

    .btn-outline-secondary-custom {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        text-decoration: none;
    }

    .btn-outline-secondary-custom:hover {
        color: white;
    }

    /* Section Cards */
    .policy-section-card {
        background: white;
        border-radius: 20px;
        margin-bottom: 28px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border-color);
        transition: var(--transition);
    }

    .policy-section-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .section-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 18px 28px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-header i {
        font-size: 1.4rem;
        color: var(--primary-color);
        background: rgba(67, 97, 238, 0.1);
        padding: 10px;
        border-radius: 12px;
    }

    .section-header h4 {
        margin: 0;
        font-weight: 700;
        color: var(--text-dark);
        font-size: 1.2rem;
    }

    .section-body {
        padding: 24px 28px;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .info-item {
        background: var(--bg-light);
        border-radius: 16px;
        padding: 18px 22px;
        border-left: 4px solid var(--primary-color);
        transition: var(--transition);
    }

    .info-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .info-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: var(--text-muted);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-dark);
        word-break: break-word;
    }

    /* Badges */
    .badge-status {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 40px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .badge-active {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-draft {
        background: #fff3e3;
        color: #b45309;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #b91c1c;
    }

    .enabled-badge {
        background: #e6f0ff;
        color: #1e40af;
        border-radius: 30px;
        padding: 4px 14px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }

    .disabled-badge {
        background: #f1f3f6;
        color: #5f6c80;
        border-radius: 30px;
        padding: 4px 14px;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-block;
    }

    /* Contribution Blocks */
    .contribution-block {
        background: white;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        overflow: hidden;
        transition: var(--transition);
    }

    .contribution-block:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .contribution-title {
        background: var(--bg-light);
        padding: 14px 20px;
        border-bottom: 1px solid var(--border-color);
        font-weight: 700;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .contribution-title i {
        color: var(--primary-color);
        margin-right: 8px;
    }

    .contribution-row {
        padding: 14px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f1f5f9;
    }

    .contribution-row:last-child {
        border-bottom: none;
    }

    .contrib-label {
        font-weight: 600;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .contrib-value {
        font-weight: 600;
        color: var(--text-dark);
    }

    /* Items Grid */
    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 16px;
    }

    .item-card {
        background: var(--bg-light);
        border-radius: 14px;
        padding: 16px 20px;
        border: 1px solid var(--border-color);
        transition: var(--transition);
    }

    .item-card:hover {
        transform: translateY(-3px);
        border-color: var(--primary-color);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
    }

    .item-name {
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
        color: var(--text-dark);
    }

    .item-name i {
        width: 28px;
        color: var(--primary-color);
        font-size: 1rem;
    }

    .item-detail {
        font-size: 0.8rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* Slab Table */
    .slab-table-wrapper {
        overflow-x: auto;
        margin-top: 15px;
    }

    .slab-table {
        width: 100%;
        font-size: 0.8rem;
        border-collapse: collapse;
    }

    .slab-table th,
    .slab-table td {
        padding: 12px 15px;
        border-bottom: 1px solid var(--border-color);
        text-align: left;
    }

    .slab-table th {
        background: var(--bg-light);
        font-weight: 600;
        color: var(--text-dark);
    }

    .slab-table tr:hover td {
        background: #f8fafc;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .policy-detail-container {
            padding: 0 16px;
        }

        .detail-header {
            flex-direction: column;
            text-align: center;
            gap: 16px;
        }

        .header-title h2 {
            justify-content: center;
        }

        .section-body {
            padding: 18px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .items-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons-header {
            justify-content: center;
        }
    }

    /* Animation */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .policy-section-card {
        animation: fadeInUp 0.4s ease-out;
    }

    .policy-section-card:nth-child(1) { animation-delay: 0s; }
    .policy-section-card:nth-child(2) { animation-delay: 0.1s; }
    .policy-section-card:nth-child(3) { animation-delay: 0.2s; }
    .policy-section-card:nth-child(4) { animation-delay: 0.3s; }
</style>

<div class="policy-detail-container">
    <!-- Header Section -->
    <div class="detail-header">
        <div class="header-title">
            <h2>
                <i class="fas fa-file-contract"></i>
                Payroll Policy Details 
            </h2>
            <p>
                <i class="fas fa-id-card me-1"></i> Policy ID: {{ $policy->payroll_policy_id ?? 'N/A' }} ({{ ucfirst($policy->policy_employment_type ?? 'full-time') }} policy)
                | <i class="fas fa-clock me-1"></i> Last updated: {{ isset($policy->updated_at) ? $policy->updated_at->format('Y-m-d H:i:s') : 'N/A' }}
            </p>
        </div>
        <div class="action-buttons-header">
            <a href="{{ route('institute.payroll.policy.management') }}" class="btn-outline-custom btn-outline-secondary-custom">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if(isset($policy))
    <!-- Basic Information Section -->
    <div class="policy-section-card">
        <div class="section-header">
            <i class="fas fa-info-circle"></i>
            <h4>Basic Information</h4>
        </div>
        <div class="section-body">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-chart-line"></i> Policy Mode
                    </div>
                    <div class="info-value">{{ ucfirst($targetType ?? 'global') }}-wise</div>
                </div>
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-bullseye"></i> Target
                    </div>
                    <div class="info-value">{{ $targetName ?? 'All Employees' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-calendar-alt"></i> Financial Year
                    </div>
                    <div class="info-value">{{ $policy->financial_year ?? '—' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-flag-checkered"></i> Status
                    </div>
                    <div class="info-value">
                        <span class="badge-status {{ ($policy->status ?? 'active') === 'active' ? 'badge-active' : (($policy->status ?? 'active') === 'draft' ? 'badge-draft' : 'badge-inactive') }}">
                            <i class="fas {{ ($policy->status ?? 'active') === 'active' ? 'fa-check-circle' : (($policy->status ?? 'active') === 'draft' ? 'fa-pen-fancy' : 'fa-times-circle') }} me-1"></i>
                            {{ strtoupper($policy->status ?? 'ACTIVE') }}
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-flag-checkered"></i> Employment Type
                    </div>
                    <div class="info-value">
                        <span class="badge-status {{ ($policy->policy_employment_type ?? 'full-time') === 'full-time' ? 'badge-active' : 'badge-inactive' }}">
                            <i class="fas {{ ($policy->policy_employment_type ?? 'full-time') === 'full-time' ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                            {{ ucfirst($policy->policy_employment_type ?? 'full-time') }}
                        </span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <i class="fas fa-calendar-plus"></i> Created On
                    </div>
                    <div class="info-value">{{ isset($policy->created_at) ? $policy->created_at->format('Y-m-d') : '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statutory Contributions Section -->
    <div class="policy-section-card">
        <div class="section-header">
            <i class="fas fa-hand-holding-usd"></i>
            <h4>Statutory Deductions</h4>
        </div>
        <div class="section-body">
            <div class="info-grid">
                <!-- PF Block -->
                <div class="contribution-block">
                    <div class="contribution-title">
                        <span><i class="fas fa-landmark"></i> Provident Fund (PF)</span>
                        @if(($policy->enable_pf ?? 0) == 1)
                            <span class="enabled-badge"><i class="fas fa-check-circle"></i> Enabled</span>
                        @else
                            <span class="disabled-badge"><i class="fas fa-times-circle"></i> Disabled</span>
                        @endif
                    </div>
                    @if(($policy->enable_pf ?? 0) == 1)
                        <div class="contribution-row">
                            <span class="contrib-label"><i class="fas fa-money-bill-wave"></i>PF wage limit</span>
                            <span class="contrib-value">
                                {{ '₹' . ($policy->pf_wage_limit ?? 0) }}
                            </span>
                        </div>

                        <div class="contribution-row">
                            <span class="contrib-label"><i class="fas fa-user"></i> Employee Contribution</span>
                            <span class="contrib-value">
                                {{ ($policy->pf_employee_enabled ?? 0) == 1 ? ($policy->pf_employee_value ?? 0) . (($policy->pf_employee_type ?? 'percentage') === 'percentage' ? '%' : ' ₹') : 'Not enabled' }}
                            </span>
                        </div>
                        <div class="contribution-row">
                            <span class="contrib-label"><i class="fas fa-building"></i> Employer Contribution</span>
                            <span class="contrib-value">
                                {{ ($policy->pf_employer_enabled ?? 0) == 1 ? ($policy->pf_employer_value ?? 0) . (($policy->pf_employer_type ?? 'percentage') === 'percentage' ? '%' : ' ₹') : 'Not enabled' }}
                            </span>
                        </div>
                    @else
                        <div class="contribution-row">
                            <span class="contrib-label"><i class="fas fa-info-circle"></i> Configuration</span>
                            <span class="contrib-value">Not enabled for this policy</span>
                        </div>
                    @endif
                </div>

                <!-- ESI Block -->
                <div class="contribution-block">
                    <div class="contribution-title">
                        <span><i class="fas fa-heartbeat"></i> Employee State Insurance (ESI)</span>
                        @if(($policy->enable_esi ?? 0) == 1)
                            <span class="enabled-badge"><i class="fas fa-check-circle"></i> Enabled</span>
                        @else
                            <span class="disabled-badge"><i class="fas fa-times-circle"></i> Disabled</span>
                        @endif
                    </div>
                    @if(($policy->enable_esi ?? 0) == 1)
                        <div class="contribution-row">
                            <span class="contrib-label"><i class="fas fa-user"></i> Employee Contribution</span>
                            <span class="contrib-value">
                                {{ ($policy->esi_employee_enabled ?? 0) == 1 ? ($policy->esi_employee_value ?? 0) . (($policy->esi_employee_type ?? 'percentage') === 'percentage' ? '%' : ' ₹') : 'Not enabled' }}
                            </span>
                        </div>
                        <div class="contribution-row">
                            <span class="contrib-label"><i class="fas fa-building"></i> Employer Contribution</span>
                            <span class="contrib-value">
                                {{ ($policy->esi_employer_enabled ?? 0) == 1 ? ($policy->esi_employer_value ?? 0) . (($policy->esi_employer_type ?? 'percentage') === 'percentage' ? '%' : ' ₹') : 'Not enabled' }}
                            </span>
                        </div>
                    @else
                        <div class="contribution-row">
                            <span class="contrib-label"><i class="fas fa-info-circle"></i> Configuration</span>
                            <span class="contrib-value">Not enabled</span>
                        </div>
                    @endif
                </div>

                <!-- NPS Block -->
                <div class="contribution-block">
                    <div class="contribution-title">
                        <span><i class="fas fa-chart-line"></i> National Pension System (NPS)</span>
                        @if(($policy->enable_nps ?? 0) == 1)
                            <span class="enabled-badge"><i class="fas fa-check-circle"></i> Enabled</span>
                        @else
                            <span class="disabled-badge"><i class="fas fa-times-circle"></i> Disabled</span>
                        @endif
                    </div>
                    @if(($policy->enable_nps ?? 0) == 1)
                        <div class="contribution-row">
                            <span class="contrib-label"><i class="fas fa-user"></i> Employee Contribution</span>
                            <span class="contrib-value">
                                {{ ($policy->nps_employee_enabled ?? 0) == 1 ? ($policy->nps_employee_value ?? 0) . (($policy->nps_employee_type ?? 'percentage') === 'percentage' ? '%' : ' ₹') : 'Not enabled' }}
                            </span>
                        </div>
                        <div class="contribution-row">
                            <span class="contrib-label"><i class="fas fa-building"></i> Employer Contribution</span>
                            <span class="contrib-value">
                                {{ ($policy->nps_employer_enabled ?? 0) == 1 ? ($policy->nps_employer_value ?? 0) . (($policy->nps_employer_type ?? 'percentage') === 'percentage' ? '%' : ' ₹') : 'Not enabled' }}
                            </span>
                        </div>
                    @else
                        <div class="contribution-row">
                            <span class="contrib-label"><i class="fas fa-info-circle"></i> Configuration</span>
                            <span class="contrib-value">Not enabled</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tax Deductions Summary -->
            <div class="mt-4">
                <div class="items-grid">
                    @if(isset($taxDeduction))
                        <div class="item-card">
                            <div class="item-name">
                                <i class="fas fa-file-invoice"></i> Professional Tax (PT)
                            </div>
                            <div class="item-detail">
                                @if(($taxDeduction->pt_selected ?? 0) == 1)
                                    <span class="enabled-badge"><i class="fas fa-check-circle"></i> Enabled</span>
                                @else
                                    <span class="disabled-badge"><i class="fas fa-times-circle"></i> Disabled</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="item-card">
                            <div class="item-name">
                                <i class="fas fa-university"></i> Labor State Tax (LST)
                            </div>
                            <div class="item-detail">
                                @if(($taxDeduction->lst_selected ?? 0) == 1)
                                    <span class="enabled-badge"><i class="fas fa-check-circle"></i> Enabled</span>
                                @else
                                    <span class="disabled-badge"><i class="fas fa-times-circle"></i> Disabled</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="item-card">
                            <div class="item-name">
                                <i class="fas fa-receipt"></i> Tax Deducted at Source (TDS)
                            </div>
                            <div class="item-detail">
                                @if(($taxDeduction->tds_selected ?? 0) == 1)
                                    <span class="enabled-badge"><i class="fas fa-check-circle"></i> Enabled</span>
                                @else
                                    <span class="disabled-badge"><i class="fas fa-times-circle"></i> Disabled</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Allowances Section -->
    <div class="policy-section-card">
        <div class="section-header">
            <i class="fas fa-plus-circle"></i>
            <h4>Allowances</h4>
        </div>
        <div class="section-body">
            <div class="items-grid">
                @php
                    $allowanceMapping = [
                        'hra' => ['name' => 'House Rent Allowance (HRA)', 'icon' => 'home', 'color' => '#4361ee'],
                        'conveyance' => ['name' => 'Conveyance Allowance', 'icon' => 'car', 'color' => '#10b981'],
                        'medical' => ['name' => 'Medical Allowance', 'icon' => 'first-aid', 'color' => '#ef4444'],
                        'special' => ['name' => 'Special Allowance', 'icon' => 'star', 'color' => '#f59e0b'],
                        'lta' => ['name' => 'Leave Travel Allowance (LTA)', 'icon' => 'plane', 'color' => '#3b82f6'],
                        'education' => ['name' => 'Education Allowance', 'icon' => 'graduation-cap', 'color' => '#8b5cf6']
                    ];
                @endphp
                @if(isset($allowance))
                    @foreach($allowanceMapping as $key => $config)
                        @php
                            $selected = $allowance->{$key . '_selected'} ?? 0;
                            $type = $allowance->{$key . '_type'} ?? 'fixed';
                            $value = $allowance->{$key . '_value'} ?? 0;
                        @endphp
                        <div class="item-card">
                            <div class="item-name">
                                <i class="fas fa-{{ $config['icon'] }}" style="color: {{ $config['color'] }};"></i> 
                                {{ $config['name'] }}
                            </div>
                            <div class="item-detail">
                                @if($selected == 1)
                                    <span class="enabled-badge"><i class="fas fa-check-circle"></i> Enabled</span>
                                    <span class="ms-2">Type: <strong>{{ ucfirst($type) }}</strong></span>
                                    @if($value > 0)
                                        <span class="ms-2">Value: <strong>{{ $value }}{{ $type === 'percentage' ? '%' : '₹' }}</strong></span>
                                    @endif
                                @else
                                    <span class="disabled-badge"><i class="fas fa-times-circle"></i> Disabled</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    
                    @if(isset($allowance->custom_allowances) && is_array($allowance->custom_allowances))
                        @foreach($allowance->custom_allowances as $ca)
                            @php
                                $customAllowanceEnabled = (int)($ca['enabled'] ?? $ca['selected'] ?? $ca['is_enabled'] ?? 0);
                            @endphp
                            <div class="item-card">
                                <div class="item-name">
                                    <i class="fas fa-money-check-alt" style="color: #f59e0b;"></i> 
                                    {{ $ca['name'] ?? 'Custom Allowance' }}
                                </div>
                                <div class="item-detail">
                                    @if($customAllowanceEnabled === 1)
                                        <span class="enabled-badge"><i class="fas fa-check-circle"></i> Enabled</span>
                                    @else
                                        <span class="disabled-badge"><i class="fas fa-times-circle"></i> Disabled</span>
                                    @endif
                                    <span class="ms-2">Type: <strong>{{ $ca['type'] ?? 'fixed' }}</strong></span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @else
                    <div class="text-muted text-center py-4">
                        <i class="fas fa-info-circle"></i> No allowance data available
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Other Deductions Section -->
    <div class="policy-section-card">
        <div class="section-header">
            <i class="fas fa-minus-circle"></i>
            <h4>Other Deductions</h4>
        </div>
        <div class="section-body">
            <div class="items-grid">
                @if(isset($otherDeduction))
                    <div class="item-card">
                        <div class="item-name">
                            <i class="fas fa-shield-alt" style="color: #3b82f6;"></i> Insurance Premium
                        </div>
                        <div class="item-detail">
                            @if(($otherDeduction->insurance_selected ?? 0) == 1)
                                <span class="enabled-badge"><i class="fas fa-check-circle"></i> Enabled</span>
                                @if(($otherDeduction->insurance_value ?? 0) > 0)
                                    <span class="ms-2">Value: <strong>{{ $otherDeduction->insurance_value ?? 0 }}{{ ($otherDeduction->insurance_type ?? 'fixed') === 'percentage' ? '%' : '₹' }}</strong></span>
                                @endif
                            @else
                                <span class="disabled-badge"><i class="fas fa-times-circle"></i> Disabled</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="item-card">
                        <div class="item-name">
                            <i class="fas fa-hand-holding-usd" style="color: #f59e0b;"></i> Loan Deduction
                        </div>
                        <div class="item-detail">
                            @if(($otherDeduction->loan_selected ?? 0) == 1)
                                <span class="enabled-badge"><i class="fas fa-check-circle"></i> Enabled</span>
                            @else
                                <span class="disabled-badge"><i class="fas fa-times-circle"></i> Disabled</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="item-card">
                        <div class="item-name">
                            <i class="fas fa-money-bill-wave" style="color: #10b981;"></i> Advance Salary
                        </div>
                        <div class="item-detail">
                            @if(($otherDeduction->advance_selected ?? 0) == 1)
                                <span class="enabled-badge"><i class="fas fa-check-circle"></i> Enabled</span>
                            @else
                                <span class="disabled-badge"><i class="fas fa-times-circle"></i> Disabled</span>
                            @endif
                        </div>
                    </div>

                    @if(isset($otherDeduction->custom_deductions) && is_array($otherDeduction->custom_deductions))
                        @foreach($otherDeduction->custom_deductions as $cd)
                            @php
                                $customDeductionEnabled = (int)($cd['enabled'] ?? $cd['selected'] ?? $cd['is_enabled'] ?? 0);
                            @endphp
                            <div class="item-card">
                                <div class="item-name">
                                    <i class="fas fa-money-check-alt" style="color: #ef4444;"></i> 
                                    {{ $cd['name'] ?? 'Custom Deduction' }}
                                </div>
                                <div class="item-detail">
                                    @if($customDeductionEnabled === 1)
                                        <span class="enabled-badge"><i class="fas fa-check-circle"></i> Enabled</span>
                                    @else
                                        <span class="disabled-badge"><i class="fas fa-times-circle"></i> Disabled</span>
                                    @endif
                                    <span class="ms-2">Type: <strong>{{ $cd['type'] ?? 'fixed' }}</strong></span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @else
                    <div class="text-muted text-center py-4">
                        <i class="fas fa-info-circle"></i> No other deductions data available
                    </div>
                @endif
            </div>
        </div>
    </div>


    <!-- Bonus Overtime Section -->
    <div class="policy-section-card">
        <div class="section-header">
            <i class="fas fa-clock"></i>
            <h4>Bonus & Overtime</h4>
        </div>

        <div class="section-body">
            <div class="items-grid">

                {{-- Overtime --}}
                <div class="item-card">
                    <div class="item-name">
                        <i class="fas fa-business-time" style="color: #3b82f6;"></i>
                        Overtime
                    </div>

                    <div class="item-detail">
                        @if(($bonusOvertime->overtime_enabled ?? 0) == 1)
                            <span class="enabled-badge">
                                <i class="fas fa-check-circle"></i> Enabled
                            </span>
                        @else
                            <span class="disabled-badge">
                                <i class="fas fa-times-circle"></i> Disabled
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Bonuses --}}
                @if(
                    isset($bonusOvertime) &&
                    !empty($bonusOvertime->bonuses) &&
                    is_array($bonusOvertime->bonuses)
                )

                    @foreach($bonusOvertime->bonuses as $bonus)
                        <div class="item-card">
                            <div class="item-name">
                                <i class="fas fa-gift" style="color: #10b981;"></i>
                                {{ $bonus['name'] ?? 'Bonus' }}
                            </div>

                            <div class="item-detail">

                                @if(($bonus['enabled'] ?? 0) == 1)
                                    <span class="enabled-badge">
                                        <i class="fas fa-check-circle"></i> Enabled
                                    </span>
                                @else
                                    <span class="disabled-badge">
                                        <i class="fas fa-times-circle"></i> Disabled
                                    </span>
                                @endif

                                @if(!empty($bonus['month']))
                                    <span class="ms-2">
                                        Month:
                                        <strong>{{ $bonus['month'] }}</strong>
                                    </span>
                                @endif

                                @if(isset($bonus['value']))
                                    <span class="ms-2">
                                        Value:
                                        <strong>
                                            @if(($bonus['type'] ?? 'fixed') === 'percentage')
                                                {{ $bonus['value'] }}%
                                            @else
                                                ₹{{ number_format($bonus['value'], 2) }}
                                            @endif
                                        </strong>
                                    </span>
                                @endif

                                <span class="ms-2">
                                    Type:
                                    <strong>{{ ucfirst($bonus['type'] ?? 'fixed') }}</strong>
                                </span>

                            </div>
                        </div>
                    @endforeach

                @else

                    <div class="text-muted text-center py-4">
                        <i class="fas fa-info-circle"></i>
                        No bonus configuration available
                    </div>

                @endif

            </div>
        </div>
    </div>

    @endif
</div>

@endsection