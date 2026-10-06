@extends('instituteAdmin.instituteAdminLayout.instituteAdminLayout')

@section('content')

<title>Payroll Policy | View Policy</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    :root {
        --primary: #4361ee;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --secondary: #64748b;
        --info: #0ea5e9;
    }
    
    .detail-header {
        background: white;
        border-radius: 24px;
        padding: 20px 28px;
        margin-bottom: 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        border: 1px solid #eef2f9;
    }
    .header-title h2 {
        font-size: 1.65rem;
        font-weight: 700;
        margin: 0;
        color: #1e293b;
    }
    .header-title h2 i {
        color: #4f46e5;
        margin-right: 12px;
    }
    .header-title p {
        margin: 8px 0 0;
        color: #5b6e8c;
        font-size: 0.85rem;
    }
    .action-buttons-header {
        display: flex;
        gap: 12px;
    }
    .btn-outline-custom {
        border-radius: 40px;
        padding: 8px 20px;
        font-weight: 500;
    }
    
    .policy-section-card {
        background: white;
        border-radius: 24px;
        margin-bottom: 24px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #eef2f9;
    }
    .section-header {
        background: #fafcff;
        padding: 18px 28px;
        border-bottom: 1px solid #eef2f9;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .section-header i {
        font-size: 1.4rem;
        color: #4f46e5;
    }
    .section-header h4 {
        margin: 0;
        font-weight: 700;
        color: #0f172a;
        font-size: 1.2rem;
    }
    .section-body {
        padding: 24px 28px;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }
    .info-item {
        background: #f8fafc;
        border-radius: 18px;
        padding: 16px 20px;
        border-left: 4px solid #4f46e5;
        transition: all 0.2s;
    }
    .info-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #5b6e8c;
        margin-bottom: 6px;
    }
    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        word-break: break-word;
    }
    .badge-status {
        display: inline-block;
        padding: 5px 14px;
        border-radius: 40px;
        font-size: 0.7rem;
        font-weight: 700;
    }
    .badge-active { background: #dcfce7; color: #15803d; }
    .badge-draft { background: #fff3e3; color: #b45309; }
    .badge-inactive { background: #ffe4e2; color: #b91c1c; }
    
    .contribution-block {
        background: #fefefe;
        border-radius: 20px;
        border: 1px solid #edf2f7;
        margin-bottom: 20px;
        overflow: hidden;
    }
    .contribution-title {
        background: #f9fafb;
        padding: 14px 20px;
        border-bottom: 1px solid #eef2f9;
        font-weight: 700;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .contribution-row {
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f1f5f9;
    }
    .contribution-row:last-child { border-bottom: none; }
    .contrib-label { font-weight: 600; color: #475569; }
    .contrib-value { font-weight: 600; color: #0f172a; }
    .enabled-badge { background: #e6f0ff; color: #1e40af; border-radius: 30px; padding: 3px 12px; font-size: 0.7rem; font-weight: 600; display: inline-block; margin-left: 8px; }
    .disabled-badge { background: #f1f3f6; color: #5f6c80; border-radius: 30px; padding: 3px 12px; font-size: 0.7rem; font-weight: 600; display: inline-block; margin-left: 8px; }
    
    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 16px;
    }
    .item-card {
        background: #f8fafc;
        border-radius: 18px;
        padding: 14px 18px;
        border: 1px solid #eef2f8;
    }
    .item-name {
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }
    .item-name i { width: 24px; color: #4f46e5; }
    .item-detail { font-size: 0.8rem; color: #475569; }
    
    @media (max-width: 768px) {
        .section-body { padding: 18px; }
        .info-grid { grid-template-columns: 1fr; }
    }
</style>
<div class="container-fluid">
    <div class="policy-detail-container">
        <div class="detail-header">
            <div class="header-title">
                <h2><i class="fas fa-file-contract"></i>Payroll Policy</h2>
                <p>Policy ID: {{ $policy->payroll_policy_id ?? 'N/A' }} | Last updated: {{ isset($policy->updated_at) ? $policy->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
            </div>
        </div>
    
        @if(isset($policy) && $policy)
        <!-- Basic Information Section -->
        <div class="policy-section-card">
            <div class="section-header">
                <i class="fas fa-info-circle"></i>
                <h4>Basic Information</h4>
            </div>
            <div class="section-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Policy Mode</div>
                        <div class="info-value">
                            @if($policy->employee_id)
                                Individual-wise
                            @elseif($policy->department_id)
                                Department-wise
                            @else
                                Global
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Name</div>
                        <div class="info-value">{{ $employee->name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Financial Year</div>
                        <div class="info-value">{{ $policy->financial_year ?? '—' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span class="badge-status {{ ($policy->status ?? 'active') === 'active' ? 'badge-active' : (($policy->status ?? 'active') === 'draft' ? 'badge-draft' : 'badge-inactive') }}">
                                {{ strtoupper($policy->status ?? 'ACTIVE') }}
                            </span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Created On</div>
                        <div class="info-value">{{ isset($policy->created_at) ? $policy->created_at->format('Y-m-d') : '—' }}</div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Statutory Contributions -->
        <div class="policy-section-card">
            <div class="section-header">
                <i class="fas fa-hand-holding-usd"></i>
                <h4>Statutory Deductions</h4>
            </div>
            <div class="section-body">
                <div class="info-grid">
                    <!-- PF -->
                    <div class="contribution-block">
                        <div class="contribution-title">
                            <span><i class="fas fa-landmark"></i> Provident Fund (PF)</span>
                            @if(($policy->enable_pf ?? 0) == 1)
                                <span class="enabled-badge">Enabled</span>
                            @else
                                <span class="disabled-badge">Disabled</span>
                            @endif
                        </div>
                        @if(($policy->enable_pf ?? 0) == 1)
                            <div class="contribution-row">
                                <span class="contrib-label">Employee Contribution</span>
                                <span class="contrib-value">
                                    @if(($policy->pf_employee_enabled ?? 0) == 1)
                                        @if(($policy->pf_employee_type ?? 'percentage') === 'percentage')
                                            {{ number_format($policy->pf_employee_value ?? 0, 2) }}%
                                        @else
                                            ₹{{ number_format($policy->pf_employee_value ?? 0, 2) }}
                                        @endif
                                    @else
                                        Not enabled
                                    @endif
                                </span>
                            </div>
                            <div class="contribution-row">
                                <span class="contrib-label">Employer Contribution</span>
                                <span class="contrib-value">
                                    @if(($policy->pf_employer_enabled ?? 0) == 1)
                                        @if(($policy->pf_employer_type ?? 'percentage') === 'percentage')
                                            {{ number_format($policy->pf_employer_value ?? 0, 2) }}%
                                        @else
                                            ₹{{ number_format($policy->pf_employer_value ?? 0, 2) }}
                                        @endif
                                    @else
                                        Not enabled
                                    @endif
                                </span>
                            </div>
                        @else
                            <div class="contribution-row">
                                <span class="contrib-label">Configuration</span>
                                <span class="contrib-value">Not enabled for this policy</span>
                            </div>
                        @endif
                    </div>
    
                    <!-- ESI -->
                    <div class="contribution-block">
                        <div class="contribution-title">
                            <span><i class="fas fa-heartbeat"></i> Employee State Insurance (ESI)</span>
                            @if(($policy->enable_esi ?? 0) == 1)
                                <span class="enabled-badge">Enabled</span>
                            @else
                                <span class="disabled-badge">Disabled</span>
                            @endif
                        </div>
                        @if(($policy->enable_esi ?? 0) == 1)
                            <div class="contribution-row">
                                <span class="contrib-label">Employee Contribution</span>
                                <span class="contrib-value">
                                    @if(($policy->esi_employee_enabled ?? 0) == 1)
                                        @if(($policy->esi_employee_type ?? 'percentage') === 'percentage')
                                            {{ number_format($policy->esi_employee_value ?? 0, 2) }}%
                                        @else
                                            ₹{{ number_format($policy->esi_employee_value ?? 0, 2) }}
                                        @endif
                                    @else
                                        Not enabled
                                    @endif
                                </span>
                            </div>
                            <div class="contribution-row">
                                <span class="contrib-label">Employer Contribution</span>
                                <span class="contrib-value">
                                    @if(($policy->esi_employer_enabled ?? 0) == 1)
                                        @if(($policy->esi_employer_type ?? 'percentage') === 'percentage')
                                            {{ number_format($policy->esi_employer_value ?? 0, 2) }}%
                                        @else
                                            ₹{{ number_format($policy->esi_employer_value ?? 0, 2) }}
                                        @endif
                                    @else
                                        Not enabled
                                    @endif
                                </span>
                            </div>
                        @else
                            <div class="contribution-row">
                                <span class="contrib-label">Configuration</span>
                                <span class="contrib-value">Not enabled</span>
                            </div>
                        @endif
                    </div>
    
                    <!-- NPS -->
                    <div class="contribution-block">
                        <div class="contribution-title">
                            <span><i class="fas fa-chart-line"></i> National Pension System (NPS)</span>
                            @if(($policy->enable_nps ?? 0) == 1)
                                <span class="enabled-badge">Enabled</span>
                            @else
                                <span class="disabled-badge">Disabled</span>
                            @endif
                        </div>
                        @if(($policy->enable_nps ?? 0) == 1)
                            <div class="contribution-row">
                                <span class="contrib-label">Employee Contribution</span>
                                <span class="contrib-value">
                                    @if(($policy->nps_employee_enabled ?? 0) == 1)
                                        @if(($policy->nps_employee_type ?? 'percentage') === 'percentage')
                                            {{ number_format($policy->nps_employee_value ?? 0, 2) }}%
                                        @else
                                            ₹{{ number_format($policy->nps_employee_value ?? 0, 2) }}
                                        @endif
                                    @else
                                        Not enabled
                                    @endif
                                </span>
                            </div>
                            <div class="contribution-row">
                                <span class="contrib-label">Employer Contribution</span>
                                <span class="contrib-value">
                                    @if(($policy->nps_employer_enabled ?? 0) == 1)
                                        @if(($policy->nps_employer_type ?? 'percentage') === 'percentage')
                                            {{ number_format($policy->nps_employer_value ?? 0, 2) }}%
                                        @else
                                            ₹{{ number_format($policy->nps_employer_value ?? 0, 2) }}
                                        @endif
                                    @else
                                        Not enabled
                                    @endif
                                </span>
                            </div>
                        @else
                            <div class="contribution-row">
                                <span class="contrib-label">Configuration</span>
                                <span class="contrib-value">Not enabled</span>
                            </div>
                        @endif
                    </div>
                </div>
    
                <!-- Tax Deductions -->
                <div class="items-grid" style="margin-top: 20px;">
                    @if(isset($taxDeductions))
                        <div class="item-card">
                            <div class="item-name"><i class="fas fa-file-invoice"></i> Professional Tax (PT)</div>
                            <div class="item-detail">
                                @if(($taxDeductions->pt_selected ?? 0) == 1)
                                    <span class="enabled-badge">Enabled</span>
                                @else
                                    <span class="disabled-badge">Disabled</span>
                                @endif
                            </div>
                        </div>
                        <div class="item-card">
                            <div class="item-name"><i class="fas fa-university"></i> Labor State Tax (LST)</div>
                            <div class="item-detail">
                                @if(($taxDeductions->lst_selected ?? 0) == 1)
                                    <span class="enabled-badge">Enabled</span>
                                @else
                                    <span class="disabled-badge">Disabled</span>
                                @endif
                            </div>
                        </div>
                        <div class="item-card">
                            <div class="item-name"><i class="fas fa-receipt"></i> Tax Deducted at Source (TDS)</div>
                            <div class="item-detail">
                                @if(($taxDeductions->tds_selected ?? 0) == 1)
                                    <span class="enabled-badge">Enabled</span>
                                @else
                                    <span class="disabled-badge">Disabled</span>
                                @endif
                            </div>
                        </div>
                    @endif
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
                            'hra' => ['name' => 'House Rent Allowance (HRA)', 'icon' => 'home'],
                            'conveyance' => ['name' => 'Conveyance Allowance', 'icon' => 'car'],
                            'medical' => ['name' => 'Medical Allowance', 'icon' => 'first-aid'],
                            'special' => ['name' => 'Special Allowance', 'icon' => 'star'],
                            'lta' => ['name' => 'Leave Travel Allowance (LTA)', 'icon' => 'plane'],
                            'education' => ['name' => 'Education Allowance', 'icon' => 'graduation-cap']
                        ];
                    @endphp
                    @if(isset($allowances))
                        @foreach($allowanceMapping as $key => $config)
                            @php
                                $selected = $allowances->{$key . '_selected'} ?? 0;
                                $type = $allowances->{$key . '_type'} ?? 'fixed';
                                $value = $allowances->{$key . '_value'} ?? 0;
                                $valueMonthly = $allowances->{$key . '_value_monthly'} ?? 0;
                            @endphp
                            <div class="item-card">
                                <div class="item-name"><i class="fas fa-{{ $config['icon'] }}"></i> {{ $config['name'] }}</div>
                                <div class="item-detail">
                                    @if($selected == 1)
                                        <span class="enabled-badge">Enabled</span> &nbsp; Type: {{ ucfirst($type) }}
                                        @if($value > 0 || $valueMonthly > 0)
                                            <br><small>
                                                @if($type === 'percentage')
                                                    Value: {{ number_format($value, 2) }}%
                                                @else
                                                    Value: ₹{{ number_format($valueMonthly > 0 ? $valueMonthly : $value, 2) }}
                                                @endif
                                            </small>
                                        @endif
                                    @else
                                        <span class="disabled-badge">Disabled</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        
                        @if(isset($allowances->custom_allowances) && is_array($allowances->custom_allowances))
                            @foreach($allowances->custom_allowances as $ca)
                                @if(isset($ca['selected']) && $ca['selected'] == 1)
                                <div class="item-card">
                                    <div class="item-name"><i class="fas fa-money-check-alt"></i> {{ $ca['name'] ?? 'Custom Allowance' }}</div>
                                    <div class="item-detail">
                                        <span class="enabled-badge">Enabled</span>
                                        | Type: {{ $ca['type'] ?? 'fixed' }}
                                        @if(isset($ca['value']) && $ca['value'] > 0)
                                            <br><small>
                                                @if(($ca['type'] ?? 'fixed') === 'percentage')
                                                    Value: {{ number_format($ca['value'], 2) }}%
                                                @else
                                                    Value: ₹{{ number_format($ca['value_monthly'] ?? $ca['value'], 2) }}
                                                @endif
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @endif
                    @else
                        <div class="text-muted">No allowance data available</div>
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
                    @if(isset($otherDeductions))
                        <div class="item-card">
                            <div class="item-name"><i class="fas fa-shield-alt"></i> Insurance Premium</div>
                            <div class="item-detail">
                                @if(($otherDeductions->insurance_selected ?? 0) == 1)
                                    <span class="enabled-badge">Enabled</span>
                                    @if(($otherDeductions->insurance_value ?? 0) > 0)
                                        <br><small>
                                            @if(($otherDeductions->insurance_type ?? 'fixed') === 'percentage')
                                                Value: {{ number_format($otherDeductions->insurance_value, 2) }}%
                                            @else
                                                Value: ₹{{ number_format($otherDeductions->insurance_value, 2) }}
                                            @endif
                                        </small>
                                    @endif
                                @else
                                    <span class="disabled-badge">Disabled</span>
                                @endif
                            </div>
                        </div>
                        <div class="item-card">
                            <div class="item-name"><i class="fas fa-hand-holding-usd"></i> Loan Deduction</div>
                            <div class="item-detail">
                                @if(($otherDeductions->loan_selected ?? 0) == 1)
                                    <span class="enabled-badge">Enabled</span>
                                @else
                                    <span class="disabled-badge">Disabled</span>
                                @endif
                            </div>
                        </div>
                        <div class="item-card">
                            <div class="item-name"><i class="fas fa-money-bill-wave"></i> Advance Salary</div>
                            <div class="item-detail">
                                @if(($otherDeductions->advance_selected ?? 0) == 1)
                                    <span class="enabled-badge">Enabled</span>
                                @else
                                    <span class="disabled-badge">Disabled</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                
                @if(isset($otherDeductions->custom_deductions) && is_array($otherDeductions->custom_deductions))
                    @foreach($otherDeductions->custom_deductions as $cd)
                        @if(isset($cd['selected']) && $cd['selected'] == 1)
                        <div class="item-card mt-2">
                            <div class="item-name"><i class="fas fa-tags"></i> {{ $cd['name'] ?? 'Custom Deduction' }}</div>
                            <div class="item-detail">
                                <span class="enabled-badge">Enabled</span>
                                | Type: {{ $cd['type'] ?? 'fixed' }}
                            </div>
                        </div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    
        @else
        <div class="policy-section-card">
            <div class="section-body text-center py-5">
                <i class="fas fa-file-contract fa-4x text-muted mb-3"></i>
                <h5>No Payroll Policy Assigned</h5>
                <p class="text-muted">No payroll policy has been assigned to you yet. Please contact HR for more information.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection