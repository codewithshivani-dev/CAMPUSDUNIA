@extends('instituteAdmin.Payroll.PayrollManagement')
@section('payroll-content')

<title>Salary Structure Details</title>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>

    
    .salary-detail-container {
        max-width: 1400px;
        margin: 30px auto;
        padding: 0 24px;
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
    
    .info-section-card {
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
    }
    
    .salary-breakdown {
        background: white;
        border-radius: 20px;
        border: 1px solid #eef2f8;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .breakdown-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 14px 20px;
        font-weight: 700;
        color: #1e293b;
        border-bottom: 1px solid #e2e8f0;
    }
    .breakdown-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 20px;
        border-bottom: 1px solid #f1f5f9;
    }
    .breakdown-row:last-child {
        border-bottom: none;
    }
    .breakdown-total {
        background: #f0fdf4;
        font-weight: 700;
        color: #15803d;
    }
    .breakdown-highlight {
        background: #eef2ff;
        font-weight: 700;
        color: #4f46e5;
    }
    
    .components-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 16px;
    }
    .component-card {
        background: #f8fafc;
        border-radius: 16px;
        padding: 14px 18px;
        border: 1px solid #eef2f8;
        transition: all 0.2s;
    }
    .component-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transform: translateY(-2px);
    }
    .component-name {
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }
    .component-name i { width: 24px; color: #4f46e5; }
    .component-detail { font-size: 0.8rem; color: #475569; margin-top: 6px; }
    .component-amount { font-weight: 700; color: #1e293b; }
    /* .statutory-card {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 1px solid #fbbf24;
    }
    .deduction-card {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border: 1px solid #f87171;
    } */
    .employer-card {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        margin:8px;
        border: 1px solid #10b981;
    }
    
    @media (max-width: 768px) {
        .section-body { padding: 18px; }
        .info-grid { grid-template-columns: 1fr; }
        .components-grid { grid-template-columns: 1fr; }
    }

    .badge-finalized { background: #dcfce7; color: #15803d; padding: 4px 12px; border-radius: 30px; font-size: 0.75rem; font-weight: 600; }
    .badge-draft { background: #fff3e3; color: #b45309; padding: 4px 12px; border-radius: 30px; font-size: 0.75rem; font-weight: 600; }
    .employer-card { background: #d1fae5; border-radius: 8px; margin-top: 8px; }
</style>

<div class="salary-detail-container">
    <div class="detail-header">
        <div class="header-title">
            <h2><i class="fas fa-file-invoice-dollar"></i> Salary Structure Details</h2>
            <p>Structure ID: {{ $structure->salary_structure_id ?? 'N/A' }} | Last updated: {{ isset($structure->updated_at) ? $structure->updated_at->format('Y-m-d H:i:s') : 'N/A' }}</p>
        </div>
        <div class="action-buttons-header">
            <a href="{{ route('institute.ctc.salary-management') }}" class="btn btn-outline-secondary btn-outline-custom">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    @if(isset($structure))
    <!-- Basic Information -->
    <div class="info-section-card">
        <div class="section-header">
            <i class="fas fa-info-circle"></i>
            <h4>Basic Information</h4>
        </div>
        <div class="section-body">
            <div class="info-grid">
                <div class="info-item"><div class="info-label">Employee</div><div class="info-value">{{ $employee->name ?? 'N/A' }}</div></div>
                <div class="info-item"><div class="info-label">Employee Code</div><div class="info-value">{{ $employee->employee_code ?? 'N/A' }}</div></div>
                <div class="info-item"><div class="info-label">Department</div><div class="info-value">{{ $department->department ?? 'N/A' }}</div></div>
                <div class="info-item"><div class="info-label">Employment Type</div><div class="info-value">{{ $structure->employment_type ?? 'N/A' }}</div></div>
                <div class="info-item"><div class="info-label">Financial Year</div><div class="info-value">{{ $structure->financial_year ?? 'N/A' }}</div></div>
                <div class="info-item"><div class="info-label">Created As</div><div class="info-value">{{ ($structure->structure_type ?? 'department') === 'employee' ? 'Employee Wise' : 'Department Wise' }}</div></div>
                <div class="info-item"><div class="info-label">Status</div><div class="info-value">
                    <span class="badge-status {{ $structure->status === 'active' ? 'badge-finalized' : 'badge-draft' }}">
                        {{ $structure->status === 'active' ? 'Active' : 'Inactive' }}
                    </span>
                </div></div>
                <div class="info-item"><div class="info-label">Created On</div><div class="info-value">{{ $structure->created_at->format('Y-m-d') ?? 'N/A' }}</div></div>
            </div>
        </div>
    </div>

    <!-- CTC Summary -->
    <div class="info-section-card">
        <div class="section-header">
            <i class="fas fa-chart-line"></i>
            <h4>Cost to Company (CTC)</h4>
        </div>
        <div class="section-body">
            <div class="components-grid">
                <div class="component-card">
                    <div class="component-name"><i class="fas fa-calculator"></i> Fixed CTC (Annual)</div>
                    <div class="component-amount">₹{{ number_format($structure->fixed_ctc_annual ?? 0, 2) }}</div>
                </div>
                <div class="component-card">
                    <div class="component-name"><i class="fas fa-chart-bar"></i> Variable CTC (Annual)</div>
                    <div class="component-amount">₹{{ number_format($structure->variable_ctc_annual ?? 0, 2) }}</div>
                </div>
                <div class="component-card" style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);">
                    <div class="component-name"><i class="fas fa-trophy"></i> Total Annual CTC</div>
                    <div class="component-amount" style="font-size: 1.2rem; color: #4f46e5;">₹{{ number_format(($structure->fixed_ctc_annual ?? 0) + ($structure->variable_ctc_annual ?? 0), 2) }}</div>
                </div>
                <!-- <div class="component-card">
                    <div class="component-name"><i class="fas fa-calendar-week"></i> Monthly CTC</div>
                    <div class="component-amount">₹{{ number_format((($structure->fixed_ctc_annual ?? 0) + ($structure->variable_ctc_annual ?? 0)) / 12, 2) }}</div>
                </div> -->
            </div>
        </div>
    </div>

    <!-- Basic Salary -->
    <div class="info-section-card">
        <div class="section-header">
            <i class="fas fa-percentage"></i>
            <h4>Basic Salary Configuration</h4>
        </div>
        <div class="section-body">
            <div class="components-grid">
                <div class="component-card">
                    <div class="component-name"><i class="fas fa-money-bill-wave"></i> Basic Salary Percentage</div>
                    <div class="component-amount">{{ $structure->basic_salary_percentage ?? 0 }}%</div>
                </div>
                <div class="component-card">
                    <div class="component-name"><i class="fas fa-money-bill-wave"></i> Basic Salary (Monthly)</div>
                    <div class="component-amount">₹{{ number_format($structure->basic_salary_monthly ?? 0, 2) }}</div>
                </div>
                <div class="component-card">
                    <div class="component-name"><i class="fas fa-calendar-alt"></i> Basic Salary (Annual)</div>
                    <div class="component-amount">₹{{ number_format($structure->basic_salary_annual ?? 0, 2) }}</div>
                </div>
                <!-- <div class="component-card">
                    <div class="component-name"><i class="fas fa-chart-pie"></i> Basic % of Fixed CTC</div>
                    <div class="component-amount">{{ $structure->basic_salary_percentage ?? 0 }}%</div>
                </div> -->
            </div>
        </div>
    </div>

        <!-- Allowances -->
        @if(isset($allowances))
        <div class="info-section-card">
            <div class="section-header">
                <i class="fas fa-plus-circle"></i>
                <h4>Allowances</h4>
            </div>
            <div class="section-body">
                <div class="components-grid">
                    @php
                        $allowanceTypes = [
                            'hra' => ['name' => 'HRA', 'icon' => 'home'],
                            'conveyance' => ['name' => 'Conveyance', 'icon' => 'car'],
                            'medical' => ['name' => 'Medical', 'icon' => 'first-aid'],
                            'special' => ['name' => 'Special', 'icon' => 'star'],
                            'lta' => ['name' => 'LTA', 'icon' => 'plane'],
                            'education' => ['name' => 'Education', 'icon' => 'graduation-cap']
                        ];
                    @endphp
                    @foreach($allowanceTypes as $key => $config)
                        {{-- Only display if selected and value > 0 --}}
                        @if((isset($allowances->{$key . '_selected'}) && $allowances->{$key . '_selected'} == 1) && 
                            (($allowances->{$key . '_value_monthly'} ?? 0) > 0 || ($allowances->{$key . '_value_annual'} ?? 0) > 0))
                        <div class="component-card">
                            <div class="component-name"><i class="fas fa-{{ $config['icon'] }}"></i> {{ $config['name'] }}</div>
                            <div class="component-detail">
                                Type: {{ ucfirst($allowances->{$key . '_type'} ?? 'fixed') }} | 
                                Value: {{ $allowances->{$key . '_value'} ?? 0 }}{{ ($allowances->{$key . '_type'} ?? 'fixed') === 'percentage' ? '%' : '₹' }}
                            </div>
                            <div class="component-amount mt-2">
                                Monthly: ₹{{ number_format($allowances->{$key . '_value_monthly'} ?? 0, 2) }} |
                                Annual: ₹{{ number_format($allowances->{$key . '_value_annual'} ?? 0, 2) }}
                            </div>
                        </div>
                        @endif
                    @endforeach
                    
                    {{-- Display Custom Allowances --}}
                    @if(isset($allowances->custom_allowances) && is_array($allowances->custom_allowances))
                        @foreach($allowances->custom_allowances as $ca)
                            @if((($ca['selected'] ?? 0) == 1 || ($ca['enabled'] ?? 0) == 1) && 
                                (($ca['value_monthly'] ?? 0) > 0 || ($ca['value_annual'] ?? 0) > 0))
                            <div class="component-card">
                                <div class="component-name"><i class="fas fa-plus-circle"></i> {{ $ca['name'] ?? 'Custom Allowance' }}</div>
                                <div class="component-detail">Type: {{ ucfirst($ca['type'] ?? 'fixed') }}</div>
                                <div class="component-amount mt-2">
                                    Monthly: ₹{{ number_format($ca['value_monthly'] ?? 0, 2) }} |
                                    Annual: ₹{{ number_format($ca['value_annual'] ?? 0, 2) }}
                                </div>
                                @if(isset($ca['description']))
                                <div class="component-detail"><small>{{ $ca['description'] }}</small></div>
                                @endif
                            </div>
                            @endif
                        @endforeach
                    @endif
                </div>
                
                {{-- Show message if no allowances are configured --}}
                @php
                    $hasAnyAllowance = false;
                    foreach($allowanceTypes as $key => $config) {
                        if((isset($allowances->{$key . '_selected'}) && $allowances->{$key . '_selected'} == 1) && 
                        (($allowances->{$key . '_value_monthly'} ?? 0) > 0 || ($allowances->{$key . '_value_annual'} ?? 0) > 0)) {
                            $hasAnyAllowance = true;
                            break;
                        }
                    }
                    if(isset($allowances->custom_allowances) && is_array($allowances->custom_allowances)) {
                        foreach($allowances->custom_allowances as $ca) {
                            if((($ca['selected'] ?? 0) == 1 || ($ca['enabled'] ?? 0) == 1) && 
                            (($ca['value_monthly'] ?? 0) > 0 || ($ca['value_annual'] ?? 0) > 0)) {
                                $hasAnyAllowance = true;
                                break;
                            }
                        }
                    }
                @endphp
                @if(!$hasAnyAllowance)
                    <div class="text-muted text-center py-3">
                        <i class="fas fa-info-circle"></i> No allowances configured for this salary structure.
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Statutory Deductions (PF, ESI, NPS) -->
        <div class="info-section-card">
            <div class="section-header">
                <i class="fas fa-landmark"></i>
                <h4>Statutory Deductions</h4>
            </div>
            <div class="section-body">
                <div class="components-grid">
                    <!-- PF (Provident Fund) -->
                    @php
                        $pfEmployeeMonthly = $preview->pf_employee_monthly ?? 0;
                        $pfEmployeeAnnual = $preview->pf_employee_annual ?? 0;
                        $pfEmployerMonthly = $preview->employer_pf_monthly ?? 0;
                        $pfEmployerAnnual = $preview->employer_pf_annual ?? 0;
                    @endphp
                    @if($pfEmployeeMonthly > 0 || $pfEmployerMonthly > 0)
                    <div class="component-card statutory-card">
                        <div class="component-name"><i class="fas fa-piggy-bank"></i> Provident Fund (PF)</div>
                        @if($pfEmployeeMonthly > 0)
                        <div class="component-detail">
                            <strong>Employee Contribution:</strong><br>
                            Monthly: ₹{{ number_format($pfEmployeeMonthly, 2) }} | 
                            Annual: ₹{{ number_format($pfEmployeeAnnual, 2) }}
                        </div>
                        @endif
                        @if($pfEmployerMonthly > 0)
                        <div class="component-detail">
                            <strong>Employer Contribution:</strong><br>
                            Monthly: ₹{{ number_format($pfEmployerMonthly, 2) }} | 
                            Annual: ₹{{ number_format($pfEmployerAnnual, 2) }}
                        </div>
                        @endif
                        <div class="component-amount mt-2">
                            Total PF: ₹{{ number_format($pfEmployeeMonthly + $pfEmployerMonthly, 2) }}/month
                        </div>
                    </div>
                    @endif

                    <!-- ESI (Employee State Insurance) -->
                    @php
                        $esiEmployeeMonthly = $preview->esi_employee_monthly ?? 0;
                        $esiEmployeeAnnual = $preview->esi_employee_annual ?? 0;
                        $esiEmployerMonthly = $preview->employer_esi_monthly ?? 0;
                        $esiEmployerAnnual = $preview->employer_esi_annual ?? 0;
                    @endphp
                    @if($esiEmployeeMonthly > 0 || $esiEmployerMonthly > 0)
                    <div class="component-card statutory-card">
                        <div class="component-name"><i class="fas fa-heartbeat"></i> Employee State Insurance (ESI)</div>
                        @if($esiEmployeeMonthly > 0)
                        <div class="component-detail">
                            <strong>Employee Contribution:</strong><br>
                            Monthly: ₹{{ number_format($esiEmployeeMonthly, 2) }} | 
                            Annual: ₹{{ number_format($esiEmployeeAnnual, 2) }}
                        </div>
                        @endif
                        @if($esiEmployerMonthly > 0)
                        <div class="component-detail">
                            <strong>Employer Contribution:</strong><br>
                            Monthly: ₹{{ number_format($esiEmployerMonthly, 2) }} | 
                            Annual: ₹{{ number_format($esiEmployerAnnual, 2) }}
                        </div>
                        @endif
                        <div class="component-amount mt-2">
                            Total ESI: ₹{{ number_format($esiEmployeeMonthly + $esiEmployerMonthly, 2) }}/month
                        </div>
                    </div>
                    @endif

                    <!-- NPS (National Pension System) -->
                    @php
                        $npsEmployeeMonthly = $preview->nps_employee_monthly ?? 0;
                        $npsEmployeeAnnual = $preview->nps_employee_annual ?? 0;
                        $npsEmployerMonthly = $preview->employer_nps_monthly ?? 0;
                        $npsEmployerAnnual = $preview->employer_nps_annual ?? 0;
                    @endphp
                    @if($npsEmployeeMonthly > 0 || $npsEmployerMonthly > 0)
                    <div class="component-card statutory-card">
                        <div class="component-name"><i class="fas fa-chart-line"></i> National Pension System (NPS)</div>
                        @if($npsEmployeeMonthly > 0)
                        <div class="component-detail">
                            <strong>Employee Contribution:</strong><br>
                            Monthly: ₹{{ number_format($npsEmployeeMonthly, 2) }} | 
                            Annual: ₹{{ number_format($npsEmployeeAnnual, 2) }}
                        </div>
                        @endif
                        @if($npsEmployerMonthly > 0)
                        <div class="component-detail">
                            <strong>Employer Contribution:</strong><br>
                            Monthly: ₹{{ number_format($npsEmployerMonthly, 2) }} | 
                            Annual: ₹{{ number_format($npsEmployerAnnual, 2) }}
                        </div>
                        @endif
                        <div class="component-amount mt-2">
                            Total NPS: ₹{{ number_format($npsEmployeeMonthly + $npsEmployerMonthly, 2) }}/month
                        </div>
                    </div>
                    @endif


                    <!-- Professional Tax (PT) -->
                    @if(isset($deductions->pt_selected) && $deductions->pt_selected == 1)
                    <div class="component-card deduction-card">
                        <div class="component-name"><i class="fas fa-file-invoice"></i> Professional Tax (PT)</div>
                        <div class="component-detail">
                            Type: {{ ucfirst($deductions->pt_type ?? 'fixed') }}
                            @if(($deductions->pt_type ?? 'fixed') === 'percentage')
                                ({{ $deductions->pt_value ?? 0 }}% of Gross)
                            @elseif(($deductions->pt_type ?? 'fixed') === 'slabs')
                                (Slab-based calculation)
                            @else
                                (Fixed: ₹{{ number_format($deductions->pt_value ?? 0, 2) }})
                            @endif
                        </div>
                        <div class="component-amount mt-2">
                            Monthly: ₹{{ number_format($deductions->pt_value_monthly ?? 0, 2) }} |
                            Annual: ₹{{ number_format($deductions->pt_value_annual ?? 0, 2) }}
                        </div>
                        @if(isset($deductions->pt_slabs) && is_array($deductions->pt_slabs) && count($deductions->pt_slabs) > 0)
                        <div class="component-detail mt-2">
                            <small><strong>Slabs:</strong> {{ count($deductions->pt_slabs) }} configured</small>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Labor State Tax (LST) -->
                    @if(isset($deductions->lst_selected) && $deductions->lst_selected == 1)
                    <div class="component-card deduction-card">
                        <div class="component-name"><i class="fas fa-university"></i> Labor State Tax (LST)</div>
                        <div class="component-detail">
                            Type: {{ ucfirst($deductions->lst_type ?? 'fixed') }}
                            @if(($deductions->lst_type ?? 'fixed') === 'percentage')
                                ({{ $deductions->lst_value ?? 0 }}% of Gross)
                            @elseif(($deductions->lst_type ?? 'fixed') === 'slabs')
                                (Slab-based calculation)
                            @else
                                (Fixed: ₹{{ number_format($deductions->lst_value ?? 0, 2) }})
                            @endif
                        </div>
                        <div class="component-amount mt-2">
                            Monthly: ₹{{ number_format($deductions->lst_value_monthly ?? 0, 2) }} |
                            Annual: ₹{{ number_format($deductions->lst_value_annual ?? 0, 2) }}
                        </div>
                        @if(isset($deductions->lst_slabs) && is_array($deductions->lst_slabs) && count($deductions->lst_slabs) > 0)
                        <div class="component-detail mt-2">
                            <small><strong>Slabs:</strong> {{ count($deductions->lst_slabs) }} configured</small>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- TDS (Tax Deducted at Source) -->
                    @if(isset($deductions->tds_selected) && $deductions->tds_selected == 1)
                    <div class="component-card deduction-card">
                        <div class="component-name"><i class="fas fa-receipt"></i> Tax Deducted at Source (TDS)</div>
                        <div class="component-detail">
                            Based on income tax slabs
                        </div>
                        <div class="component-amount mt-2">
                            Monthly: ₹{{ number_format($deductions->tds_value_monthly ?? 0, 2) }} |
                            Annual: ₹{{ number_format($deductions->tds_value_annual ?? 0, 2) }}
                        </div>
                        @if(isset($deductions->tds_slabs) && is_array($deductions->tds_slabs) && count($deductions->tds_slabs) > 0)
                        <div class="component-detail mt-2">
                            <small><strong>Slabs:</strong> {{ count($deductions->tds_slabs) }} configured</small>
                        </div>
                        @endif
                    </div>
                    @endif

                </div>

                @if(($pfEmployeeMonthly == 0 && $pfEmployerMonthly == 0) && 
                    ($esiEmployeeMonthly == 0 && $esiEmployerMonthly == 0) && 
                    ($npsEmployeeMonthly == 0 && $npsEmployerMonthly == 0))
                    <div class="text-muted text-center py-3">
                        <i class="fas fa-info-circle"></i> No statutory deductions configured for this salary structure.
                    </div>
                @endif

            </div>
        </div>

        <!-- Tax Deductions (PT, LST, TDS) -->
        <!-- Other Deductions -->
        @if(isset($deductions))
        <div class="info-section-card">
            <div class="section-header">
                <i class="fas fa-minus-circle"></i>
                <h4>Other Deductions</h4>
            </div>
            <div class="section-body">
                <div class="components-grid">
                    <!-- Insurance Premium -->
                    @if((isset($deductions->insurance_selected) && $deductions->insurance_selected == 1) && 
                        (($deductions->insurance_value_monthly ?? 0) > 0 || ($deductions->insurance_value_annual ?? 0) > 0))
                    <div class="component-card deduction-card">
                        <div class="component-name"><i class="fas fa-shield-alt"></i> Insurance Premium</div>
                        <div class="component-detail">
                            Type: {{ ucfirst($deductions->insurance_type ?? 'fixed') }}
                            @if(($deductions->insurance_type ?? 'fixed') === 'percentage')
                                ({{ $deductions->insurance_value ?? 0 }}% of Gross)
                            @else
                                (Fixed: ₹{{ number_format($deductions->insurance_value ?? 0, 2) }})
                            @endif
                        </div>
                        <div class="component-amount mt-2">
                            Monthly: ₹{{ number_format($deductions->insurance_value_monthly ?? 0, 2) }} |
                            Annual: ₹{{ number_format($deductions->insurance_value_annual ?? 0, 2) }}
                        </div>
                    </div>
                    @endif

                    <!-- Loan Deduction -->
                    @if(isset($deductions->loan_selected) && $deductions->loan_selected == 1 && 
                        (($deductions->loan_value_monthly ?? 0) > 0 || ($deductions->loan_value_annual ?? 0) > 0))
                    <div class="component-card deduction-card">
                        <div class="component-name"><i class="fas fa-hand-holding-usd"></i> Loan Deduction</div>
                        <div class="component-detail">
                            Type: {{ ucfirst($deductions->loan_type ?? 'fixed') }}
                            @if(($deductions->loan_type ?? 'fixed') === 'percentage')
                                ({{ $deductions->loan_value ?? 0 }}% of Gross)
                            @else
                                (Fixed: ₹{{ number_format($deductions->loan_value ?? 0, 2) }})
                            @endif
                        </div>
                        <div class="component-amount mt-2">
                            Monthly: ₹{{ number_format($deductions->loan_value_monthly ?? 0, 2) }} |
                            Annual: ₹{{ number_format($deductions->loan_value_annual ?? 0, 2) }}
                        </div>
                    </div>
                    @endif

                    <!-- Advance Salary -->
                    @if(isset($deductions->advance_selected) && $deductions->advance_selected == 1 && 
                        (($deductions->advance_value_monthly ?? 0) > 0 || ($deductions->advance_value_annual ?? 0) > 0))
                    <div class="component-card deduction-card">
                        <div class="component-name"><i class="fas fa-money-bill-wave"></i> Advance Salary</div>
                        <div class="component-detail">
                            Type: {{ ucfirst($deductions->advance_type ?? 'fixed') }}
                            @if(($deductions->advance_type ?? 'fixed') === 'percentage')
                                ({{ $deductions->advance_value ?? 0 }}% of Gross)
                            @else
                                (Fixed: ₹{{ number_format($deductions->advance_value ?? 0, 2) }})
                            @endif
                        </div>
                        <div class="component-amount mt-2">
                            Monthly: ₹{{ number_format($deductions->advance_value_monthly ?? 0, 2) }} |
                            Annual: ₹{{ number_format($deductions->advance_value_annual ?? 0, 2) }}
                        </div>
                    </div>
                    @endif

                    <!-- Custom Deductions -->
                    @if(isset($deductions->custom_deductions) && is_array($deductions->custom_deductions))
                        @foreach($deductions->custom_deductions as $cd)
                            @if((($cd['selected'] ?? 0) == 1 || ($cd['enabled'] ?? 0) == 1) && 
                                (($cd['value_monthly'] ?? 0) > 0 || ($cd['value_annual'] ?? 0) > 0))
                            <div class="component-card deduction-card">
                                <div class="component-name"><i class="fas fa-minus-circle"></i> {{ $cd['name'] ?? 'Custom Deduction' }}</div>
                                <div class="component-detail">
                                    Type: {{ ucfirst($cd['type'] ?? 'fixed') }}
                                    @if(($cd['type'] ?? 'fixed') === 'percentage')
                                        ({{ $cd['value'] ?? 0 }}% of Gross)
                                    @else
                                        (Fixed: ₹{{ number_format($cd['value'] ?? 0, 2) }})
                                    @endif
                                </div>
                                <div class="component-amount mt-2">
                                    Monthly: ₹{{ number_format($cd['value_monthly'] ?? 0, 2) }} |
                                    Annual: ₹{{ number_format($cd['value_annual'] ?? 0, 2) }}
                                </div>
                                @if(isset($cd['description']))
                                <div class="component-detail"><small>{{ $cd['description'] }}</small></div>
                                @endif
                            </div>
                            @endif
                        @endforeach
                    @endif
                </div>

                @php
                    $hasOtherDeductions = (isset($deductions->insurance_selected) && $deductions->insurance_selected == 1 && 
                                        (($deductions->insurance_value_monthly ?? 0) > 0 || ($deductions->insurance_value_annual ?? 0) > 0)) ||
                                        (isset($deductions->loan_selected) && $deductions->loan_selected == 1 && 
                                        (($deductions->loan_value_monthly ?? 0) > 0 || ($deductions->loan_value_annual ?? 0) > 0)) ||
                                        (isset($deductions->advance_selected) && $deductions->advance_selected == 1 && 
                                        (($deductions->advance_value_monthly ?? 0) > 0 || ($deductions->advance_value_annual ?? 0) > 0));
                    
                    if(!$hasOtherDeductions && isset($deductions->custom_deductions) && is_array($deductions->custom_deductions)) {
                        foreach($deductions->custom_deductions as $cd) {
                            if((($cd['selected'] ?? 0) == 1 || ($cd['enabled'] ?? 0) == 1) && 
                            (($cd['value_monthly'] ?? 0) > 0 || ($cd['value_annual'] ?? 0) > 0)) {
                                $hasOtherDeductions = true;
                                break;
                            }
                        }
                    }
                @endphp
                @if(!$hasOtherDeductions)
                    <div class="text-muted text-center py-3">
                        <i class="fas fa-info-circle"></i> No other deductions configured for this salary structure.
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Salary Preview -->
        @if(isset($preview))
        <div class="info-section-card">
            <div class="section-header">
                <i class="fas fa-eye"></i>
                <h4>Salary Preview</h4>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="salary-breakdown">
                            <div class="breakdown-header"><i class="fas fa-calendar-week"></i> Monthly Breakdown</div>
                            <div class="breakdown-row"><span>Basic Salary</span><strong>₹{{ number_format($preview->basic_salary_monthly ?? 0, 2) }}</strong></div>
                            <div class="breakdown-row"><span>Total Allowances</span><strong>₹{{ number_format($preview->total_allowances_monthly ?? 0, 2) }}</strong></div>
                            <!-- <div class="breakdown-row"><span>Total Bonus</span><strong>₹{{ number_format($preview->total_bonus_monthly ?? 0, 2) }}</strong></div>
                            <div class="breakdown-row"><span>Total Overtime</span><strong>₹{{ number_format($preview->total_overtime_monthly ?? 0, 2) }}</strong></div> -->
                            <div class="breakdown-row breakdown-total"><span>Gross Salary</span><strong>₹{{ number_format($preview->gross_salary_monthly ?? 0, 2) }}</strong></div>
                            
                            <!-- Employee Statutory Deductions -->
                            @if(($preview->pf_employee_monthly ?? 0) > 0)
                            <div class="breakdown-row"><span>PF (Employee)</span><strong>₹{{ number_format($preview->pf_employee_monthly ?? 0, 2) }}</strong></div>
                            @endif
                            @if(($preview->esi_employee_monthly ?? 0) > 0)
                            <div class="breakdown-row"><span>ESI (Employee)</span><strong>₹{{ number_format($preview->esi_employee_monthly ?? 0, 2) }}</strong></div>
                            @endif
                            @if(($preview->nps_employee_monthly ?? 0) > 0)
                            <div class="breakdown-row"><span>NPS (Employee)</span><strong>₹{{ number_format($preview->nps_employee_monthly ?? 0, 2) }}</strong></div>
                            @endif
                            
                            <!-- Tax Deductions -->
                            @if(($preview->pt_monthly ?? 0) > 0)
                            <div class="breakdown-row"><span>Professional Tax</span><strong>₹{{ number_format($preview->pt_monthly ?? 0, 2) }}</strong></div>
                            @endif
                            @if(($preview->lst_monthly ?? 0) > 0)
                            <div class="breakdown-row"><span>Labor State Tax</span><strong>₹{{ number_format($preview->lst_monthly ?? 0, 2) }}</strong></div>
                            @endif
                            @if(($preview->tds_monthly ?? 0) > 0)
                            <div class="breakdown-row"><span>TDS</span><strong>₹{{ number_format($preview->tds_monthly ?? 0, 2) }}</strong></div>
                            @endif
                            
                            <!-- Other Deductions -->
                            @if(($preview->insurance_premium_monthly ?? 0) > 0)
                            <div class="breakdown-row"><span>Insurance Premium</span><strong>₹{{ number_format($preview->insurance_premium_monthly ?? 0, 2) }}</strong></div>
                            @endif
                            @if(($preview->advance_salary_monthly ?? 0) > 0)
                            <div class="breakdown-row"><span>Advance Salary</span><strong>₹{{ number_format($preview->advance_salary_monthly ?? 0, 2) }}</strong></div>
                            @endif
                            
                            <div class="breakdown-row"><span>Total Deductions</span><strong>₹{{ number_format($preview->total_deductions_monthly ?? 0, 2) }}</strong></div>
                            <div class="breakdown-row breakdown-highlight"><span>Net Salary (Take Home)</span><strong>₹{{ number_format($preview->net_salary_monthly ?? 0, 2) }}</strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="salary-breakdown">
                            <div class="breakdown-header"><i class="fas fa-calendar-alt"></i> Annual Breakdown</div>
                            <div class="breakdown-row"><span>Basic Salary</span><strong>₹{{ number_format($preview->basic_salary_annual ?? 0, 2) }}</strong></div>
                            <div class="breakdown-row"><span>Total Allowances</span><strong>₹{{ number_format($preview->total_allowances_annual ?? 0, 2) }}</strong></div>
                            <!-- <div class="breakdown-row"><span>Total Bonus</span><strong>₹{{ number_format($preview->total_bonus_annual ?? 0, 2) }}</strong></div>
                            <div class="breakdown-row"><span>Total Overtime</span><strong>₹{{ number_format($preview->total_overtime_annual ?? 0, 2) }}</strong></div> -->
                            <div class="breakdown-row"><span>Variable CTC</span><strong>₹{{ number_format($structure->variable_ctc_annual ?? 0, 2) }}</strong></div>
                            <div class="breakdown-row breakdown-total"><span>Gross Salary</span><strong>₹{{ number_format($preview->gross_salary_annual ?? 0, 2) }}</strong></div>
                            
                            <!-- Employee Statutory Deductions Annual -->
                            @if(($preview->pf_employee_annual ?? 0) > 0)
                            <div class="breakdown-row"><span>PF (Employee)</span><strong>₹{{ number_format($preview->pf_employee_annual ?? 0, 2) }}</strong></div>
                            @endif
                            @if(($preview->esi_employee_annual ?? 0) > 0)
                            <div class="breakdown-row"><span>ESI (Employee)</span><strong>₹{{ number_format($preview->esi_employee_annual ?? 0, 2) }}</strong></div>
                            @endif
                            @if(($preview->nps_employee_annual ?? 0) > 0)
                            <div class="breakdown-row"><span>NPS (Employee)</span><strong>₹{{ number_format($preview->nps_employee_annual ?? 0, 2) }}</strong></div>
                            @endif
                            
                            <div class="breakdown-row"><span>Total Deductions</span><strong>₹{{ number_format($preview->total_deductions_annual ?? 0, 2) }}</strong></div>
                            <div class="breakdown-row breakdown-highlight"><span>Net Salary (Take Home)</span><strong>₹{{ number_format($preview->net_salary_annual ?? 0, 2) }}</strong></div>
                            
                            <div class="breakdown-row employer-card mt-2"><span>Employer PF</span><strong>₹{{ number_format($preview->employer_pf_annual ?? 0, 2) }}</strong></div>
                            <div class="breakdown-row employer-card"><span>Employer ESI</span><strong>₹{{ number_format($preview->employer_esi_annual ?? 0, 2) }}</strong></div>
                            <div class="breakdown-row employer-card"><span>Employer NPS</span><strong>₹{{ number_format($preview->employer_nps_annual ?? 0, 2) }}</strong></div>
                            <div class="breakdown-row breakdown-total"><span>Total Annual Cost to Company</span><strong>₹{{ number_format($preview->total_cost_annual ?? 0, 2) }}</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
</div>


@endsection
