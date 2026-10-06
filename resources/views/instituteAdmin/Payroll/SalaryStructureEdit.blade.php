@extends('instituteAdmin.Payroll.PayrollManagement')

@section('payroll-content')
<style>
    .edit-salary-container {
        padding: 20px;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .edit-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        margin-bottom: 25px;
        overflow: hidden;
    }

    .edit-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }

    .edit-card-header h3 {
        margin: 0;
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .edit-card-body {
        padding: 25px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .info-item label {
        font-weight: 600;
        color: #4a5568;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-item span {
        font-size: 16px;
        color: #2d3748;
        font-weight: 500;
    }

    .input-group-custom {
        margin-bottom: 20px;
    }

    .input-group-custom label {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        font-weight: 600;
        color: #4a5568;
    }

    .input-group-custom input, .input-group-custom select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s;
    }

    .input-group-custom input:focus, .input-group-custom select:focus {
        border-color: #667eea;
        outline: none;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    }

    .input-group-custom input[readonly] {
        background: #f8f9fa;
        cursor: not-allowed;
    }

    .input-with-suffix {
        position: relative;
    }

    .input-with-suffix input {
        width: 100%;
        padding-right: 40px;
    }

    .input-with-suffix .suffix {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #718096;
        font-weight: 500;
    }

    .allowance-item, .deduction-item, .statutory-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #e2e8f0;
    }

    .allowance-header, .deduction-header, .statutory-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e2e8f0;
    }

    .allowance-name, .deduction-name, .statutory-name {
        font-weight: 600;
        color: #2d3748;
        font-size: 16px;
    }

    .allowance-type, .deduction-type, .statutory-type {
        font-size: 12px;
        padding: 3px 8px;
        border-radius: 4px;
        background: #e6fffa;
        color: #0d9488;
    }

    .allowance-controls, .deduction-controls {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 15px;
        align-items: center;
    }

    .amount-display {
        display: flex;
        gap: 20px;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px dashed #e2e8f0;
        font-size: 13px;
        color: #718096;
    }

    .preview-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1.5fr;
        gap: 25px;
    }

    .preview-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
    }

    .preview-section h4 {
        margin: 0 0 15px 0;
        padding-bottom: 10px;
        border-bottom: 2px solid #e2e8f0;
        color: #2d3748;
    }

    .preview-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px dashed #e2e8f0;
    }

    .preview-total {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-top: 2px solid #667eea;
        margin-top: 10px;
        font-weight: 600;
    }

    .highlight {
        color: #48bb78;
        font-size: 18px;
        font-weight: bold;
    }

    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .btn {
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102,126,234,0.3);
    }

    .btn-secondary {
        background: #e2e8f0;
        color: #4a5568;
    }

    .btn-secondary:hover {
        background: #cbd5e1;
    }

    .btn-success {
        background: #48bb78;
        color: white;
    }

    .btn-success:hover {
        background: #38a169;
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        display: none;
    }

    .loading-spinner {
        background: white;
        padding: 20px 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .percentage-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .percentage-group input {
        flex: 1;
    }

    .percentage-group span {
        min-width: 60px;
        color: #718096;
    }

    @media (max-width: 992px) {
        .preview-grid {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="edit-salary-container">
    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <span>Saving changes...</span>
        </div>
    </div>

    <!-- Header Info -->
    <div class="edit-card">
        <div class="edit-card-header">
            <h3>
                <i class="fas fa-edit"></i>
                Edit Salary Structure
            </h3>
            <div>
                <span class="badge" style="background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 20px;">
                    ID: {{ $structure->salary_structure_id }}
                </span>
            </div>
        </div>
        <div class="edit-card-body">
            <div class="info-grid">
                <div class="info-item">
                    <label><i class="fas fa-user"></i> Employee</label>
                    <span>{{ $employee->name ?? 'N/A' }} ({{ $employee->employee_code ?? 'N/A' }})</span>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-building"></i> Department</label>
                    <span>{{ $department->department ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-calendar"></i> Financial Year</label>
                    <span>{{ $structure->financial_year }}</span>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-tag"></i> Structure Type</label>
                    <span>{{ ucfirst($structure->structure_type ?? 'Department') }}</span>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-check-circle"></i> Status</label>
                    <span class="badge {{ $structure->finalized_at ? 'bg-success' : 'bg-warning' }}">
                        {{ $structure->finalized_at ? 'Finalized' : 'Draft' }}
                    </span>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-clock"></i> Created</label>
                    <span>{{ $structure->created_at->format('d M Y, h:i A') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Basic Salary & CTC Section -->
    <div class="edit-card">
        <div class="edit-card-header" style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);">
            <h3><i class="fas fa-chart-line"></i> Basic Salary & CTC</h3>
        </div>
        <div class="edit-card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group-custom">
                        <label><i class="fas fa-rupee-sign"></i> Basic Salary (Monthly)</label>
                        <div class="input-with-suffix">
                            <input type="number" id="basicSalaryMonthly" class="form-control" value="{{ $structure->basic_salary_monthly }}" step="100" min="0">
                            <span class="suffix">₹</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group-custom">
                        <label><i class="fas fa-chart-line"></i> Basic Salary (Annual)</label>
                        <div class="input-with-suffix">
                            <input type="text" id="basicSalaryAnnual" class="form-control" readonly value="{{ number_format($structure->basic_salary_annual, 2) }}">
                            <span class="suffix">₹</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group-custom">
                        <label><i class="fas fa-percent"></i> Basic % of CTC</label>
                        <div class="input-with-suffix">
                            <input type="text" id="basicPercentage" class="form-control" readonly value="{{ number_format($structure->basic_salary_percentage ?? 0, 2) }}">
                            <span class="suffix">%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="input-group-custom">
                        <label><i class="fas fa-chart-line"></i> Fixed CTC (Annual)</label>
                        <div class="input-with-suffix">
                            <input type="number" id="fixedCTCAnnual" class="form-control" value="{{ $structure->fixed_ctc_annual }}" step="1000" min="0">
                            <span class="suffix">₹</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group-custom">
                        <label><i class="fas fa-chart-line"></i> Variable CTC (Annual)</label>
                        <div class="input-with-suffix">
                            <input type="number" id="variableCTCAnnual" class="form-control" value="{{ $structure->variable_ctc_annual ?? 0 }}" step="1000" min="0">
                            <span class="suffix">₹</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group-custom">
                        <label><i class="fas fa-chart-line"></i> Total CTC (Annual)</label>
                        <div class="input-with-suffix">
                            <input type="text" id="totalCTCAnnual" class="form-control" readonly value="{{ number_format($structure->total_ctc_annual ?? 0, 2) }}">
                            <span class="suffix">₹</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="input-group-custom">
                        <label><i class="fas fa-chart-line"></i> Monthly Fixed</label>
                        <div class="input-with-suffix">
                            <input type="text" id="monthlyFixed" class="form-control" readonly value="{{ number_format($structure->monthly_fixed ?? 0, 2) }}">
                            <span class="suffix">₹</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group-custom">
                        <label><i class="fas fa-chart-line"></i> Monthly Variable</label>
                        <div class="input-with-suffix">
                            <input type="text" id="monthlyVariable" class="form-control" readonly value="{{ number_format($structure->monthly_variable ?? 0, 2) }}">
                            <span class="suffix">₹</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group-custom">
                        <label><i class="fas fa-chart-line"></i> Total Monthly CTC</label>
                        <div class="input-with-suffix">
                            <input type="text" id="totalMonthlyCTC" class="form-control" readonly value="0">
                            <span class="suffix">₹</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Allowances Section -->
    <div class="edit-card">
        <div class="edit-card-header" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <h3><i class="fas fa-gift"></i> Allowances</h3>
        </div>
        <div class="edit-card-body">
            <div id="allowancesContainer">
                @php
                    $allowanceFields = [
                        'hra' => ['name' => 'HRA', 'selected' => $allowances->hra_selected ?? false, 'value' => $allowances->hra_value ?? 0, 'monthly' => $allowances->hra_value_monthly ?? 0, 'annual' => $allowances->hra_value_annual ?? 0, 'type' => $allowances->hra_type ?? 'fixed'],
                        'conveyance' => ['name' => 'Conveyance', 'selected' => $allowances->conveyance_selected ?? false, 'value' => $allowances->conveyance_value ?? 0, 'monthly' => $allowances->conveyance_value_monthly ?? 0, 'annual' => $allowances->conveyance_value_annual ?? 0, 'type' => $allowances->conveyance_type ?? 'fixed'],
                        'medical' => ['name' => 'Medical', 'selected' => $allowances->medical_selected ?? false, 'value' => $allowances->medical_value ?? 0, 'monthly' => $allowances->medical_value_monthly ?? 0, 'annual' => $allowances->medical_value_annual ?? 0, 'type' => $allowances->medical_type ?? 'fixed'],
                        'special' => ['name' => 'Special Allowance', 'selected' => $allowances->special_selected ?? false, 'value' => $allowances->special_value ?? 0, 'monthly' => $allowances->special_value_monthly ?? 0, 'annual' => $allowances->special_value_annual ?? 0, 'type' => $allowances->special_type ?? 'fixed'],
                        'lta' => ['name' => 'LTA', 'selected' => $allowances->lta_selected ?? false, 'value' => $allowances->lta_value ?? 0, 'monthly' => $allowances->lta_value_monthly ?? 0, 'annual' => $allowances->lta_value_annual ?? 0, 'type' => $allowances->lta_type ?? 'fixed'],
                        'education' => ['name' => 'Education', 'selected' => $allowances->education_selected ?? false, 'value' => $allowances->education_value ?? 0, 'monthly' => $allowances->education_value_monthly ?? 0, 'annual' => $allowances->education_value_annual ?? 0, 'type' => $allowances->education_type ?? 'fixed'],
                    ];
                @endphp
                
                @foreach($allowanceFields as $key => $field)
                    @if($field['selected'])
                    <div class="allowance-item" data-allowance="{{ $key }}" data-type="{{ $field['type'] }}">
                        <div class="allowance-header">
                            <span class="allowance-name">{{ $field['name'] }}</span>
                            <span class="allowance-type">{{ ucfirst($field['type']) }}</span>
                        </div>
                        <div class="allowance-controls">
                            <div class="input-group-custom" style="margin-bottom: 0;">
                                <label>{{ $field['type'] === 'percentage' ? 'Percentage (%)' : 'Amount (Monthly)' }}</label>
                                <div class="percentage-group">
                                    <input type="number" class="allowance-input" data-type="{{ $key }}" data-calc-type="{{ $field['type'] }}" value="{{ $field['type'] === 'percentage' ? ($field['value'] ?? 0) : $field['value'] }}" step="{{ $field['type'] === 'percentage' ? '0.01' : '100' }}" min="0">
                                    <span>{{ $field['type'] === 'percentage' ? '%' : '₹' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="amount-display">
                            <span>Monthly: ₹{{ number_format($field['monthly'], 2) }}</span>
                            <span>Annual: ₹{{ number_format($field['annual'], 2) }}</span>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Statutory Deductions Section -->
    <div class="edit-card">
        <div class="edit-card-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
            <h3><i class="fas fa-balance-scale"></i> Statutory Deductions</h3>
        </div>
        <div class="edit-card-body">
            <div id="statutoryContainer">
                @php
                    $statutoryFields = [
                        'pf' => ['name' => 'Provident Fund (PF)', 'employee' => $deductions->pf_employee_monthly ?? 0, 'employer' => $deductions->employer_pf_monthly ?? 0],
                        'esi' => ['name' => 'ESI', 'employee' => $deductions->esi_employee_monthly ?? 0, 'employer' => $deductions->employer_esi_monthly ?? 0],
                        'nps' => ['name' => 'NPS', 'employee' => $deductions->nps_employee_monthly ?? 0, 'employer' => $deductions->employer_nps_monthly ?? 0],
                    ];
                @endphp
                
                @foreach($statutoryFields as $key => $field)
                    @if(($field['employee'] ?? 0) > 0 || ($field['employer'] ?? 0) > 0)
                    <div class="statutory-item">
                        <div class="statutory-header">
                            <span class="statutory-name">{{ $field['name'] }}</span>
                            <span class="statutory-type">Statutory</span>
                        </div>
                        <div class="amount-display">
                            @if(($field['employee'] ?? 0) > 0)
                            <span>Employee Monthly: ₹{{ number_format($field['employee'], 2) }}</span>
                            @endif
                            @if(($field['employer'] ?? 0) > 0)
                            <span>Employer Monthly: ₹{{ number_format($field['employer'], 2) }}</span>
                            @endif
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Tax Deductions Section -->
    <div class="edit-card">
        <div class="edit-card-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
            <h3><i class="fas fa-file-invoice-dollar"></i> Tax & Other Deductions</h3>
        </div>
        <div class="edit-card-body">
            <div id="taxDeductionsContainer">
                @php
                    $taxFields = [
                        'pt' => ['name' => 'Professional Tax', 'selected' => $deductions->pt_selected ?? false, 'value' => $deductions->pt_value ?? 0, 'monthly' => $deductions->pt_value_monthly ?? 0, 'annual' => $deductions->pt_value_annual ?? 0, 'type' => $deductions->pt_type ?? 'fixed'],
                        'lst' => ['name' => 'Labor State Tax', 'selected' => $deductions->lst_selected ?? false, 'value' => $deductions->lst_value ?? 0, 'monthly' => $deductions->lst_value_monthly ?? 0, 'annual' => $deductions->lst_value_annual ?? 0, 'type' => $deductions->lst_type ?? 'fixed'],
                        'tds' => ['name' => 'TDS', 'selected' => $deductions->tds_selected ?? false, 'value' => $deductions->tds_value ?? 0, 'monthly' => $deductions->tds_value_monthly ?? 0, 'annual' => $deductions->tds_value_annual ?? 0],
                        'insurance' => ['name' => 'Insurance Premium', 'selected' => $deductions->insurance_selected ?? false, 'value' => $deductions->insurance_value ?? 0, 'monthly' => $deductions->insurance_value_monthly ?? 0, 'annual' => $deductions->insurance_value_annual ?? 0, 'type' => $deductions->insurance_type ?? 'fixed'],
                    ];
                @endphp
                
                @foreach($taxFields as $key => $field)
                    @if(isset($field['selected']) && $field['selected'])
                    <div class="deduction-item" data-deduction="{{ $key }}" data-type="{{ $field['type'] ?? 'fixed' }}">
                        <div class="deduction-header">
                            <span class="deduction-name">{{ $field['name'] }}</span>
                            <span class="deduction-type">{{ ucfirst($field['type'] ?? 'fixed') }}</span>
                        </div>
                        <div class="deduction-controls">
                            <div class="input-group-custom" style="margin-bottom: 0;">
                                <label>{{ ($field['type'] ?? 'fixed') === 'percentage' ? 'Percentage (%)' : 'Amount (Monthly)' }}</label>
                                <div class="percentage-group">
                                    <input type="number" class="deduction-input" data-type="{{ $key }}" data-calc-type="{{ $field['type'] ?? 'fixed' }}" value="{{ $field['value'] }}" step="{{ ($field['type'] ?? 'fixed') === 'percentage' ? '0.01' : '100' }}" min="0">
                                    <span>{{ ($field['type'] ?? 'fixed') === 'percentage' ? '%' : '₹' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="amount-display">
                            <span>Monthly: ₹{{ number_format($field['monthly'], 2) }}</span>
                            <span>Annual: ₹{{ number_format($field['annual'], 2) }}</span>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Salary Preview Section -->
    <div class="edit-card">
        <div class="edit-card-header" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
            <h3><i class="fas fa-eye"></i> Salary Preview</h3>
            <button class="btn btn-sm btn-light" onclick="calculateAll()">
                <i class="fas fa-sync-alt"></i> Recalculate
            </button>
        </div>
        <div class="edit-card-body">
            <div class="preview-grid">
                <div class="preview-section">
                    <h4>Earnings (Monthly)</h4>
                    <div id="earningsPreview">
                        <div class="preview-row">
                            <span>Basic Salary</span>
                            <span id="previewBasicMonthly">₹{{ number_format($structure->basic_salary_monthly, 2) }}</span>
                        </div>
                        @foreach($allowanceFields as $key => $field)
                            @if($field['selected'])
                            <div class="preview-row">
                                <span>{{ $field['name'] }}</span>
                                <span id="preview{{ ucfirst($key) }}Monthly">₹{{ number_format($field['monthly'], 2) }}</span>
                            </div>
                            @endif
                        @endforeach
                        <div class="preview-total">
                            <span>Total Earnings</span>
                            <span id="totalEarningsMonthly">₹{{ number_format($preview->total_earnings_monthly ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="preview-section">
                    <h4>Deductions (Monthly)</h4>
                    <div id="deductionsPreview">
                        @if(($deductions->pf_employee_monthly ?? 0) > 0)
                        <div class="preview-row">
                            <span>PF (Employee)</span>
                            <span id="previewPFMonthly">₹{{ number_format($deductions->pf_employee_monthly ?? 0, 2) }}</span>
                        </div>
                        @endif
                        @if(($deductions->esi_employee_monthly ?? 0) > 0)
                        <div class="preview-row">
                            <span>ESI (Employee)</span>
                            <span id="previewESIMonthly">₹{{ number_format($deductions->esi_employee_monthly ?? 0, 2) }}</span>
                        </div>
                        @endif
                        @if(($deductions->nps_employee_monthly ?? 0) > 0)
                        <div class="preview-row">
                            <span>NPS (Employee)</span>
                            <span id="previewNPSMonthly">₹{{ number_format($deductions->nps_employee_monthly ?? 0, 2) }}</span>
                        </div>
                        @endif
                        @if(($deductions->pt_value_monthly ?? 0) > 0)
                        <div class="preview-row">
                            <span>Professional Tax</span>
                            <span id="previewPTMonthly">₹{{ number_format($deductions->pt_value_monthly ?? 0, 2) }}</span>
                        </div>
                        @endif
                        @if(($deductions->lst_value_monthly ?? 0) > 0)
                        <div class="preview-row">
                            <span>Labor State Tax</span>
                            <span id="previewLSTMonthly">₹{{ number_format($deductions->lst_value_monthly ?? 0, 2) }}</span>
                        </div>
                        @endif
                        @if(($deductions->tds_value_monthly ?? 0) > 0)
                        <div class="preview-row">
                            <span>TDS</span>
                            <span id="previewTDSMonthly">₹{{ number_format($deductions->tds_value_monthly ?? 0, 2) }}</span>
                        </div>
                        @endif
                        @if(($deductions->insurance_value_monthly ?? 0) > 0)
                        <div class="preview-row">
                            <span>Insurance Premium</span>
                            <span id="previewInsuranceMonthly">₹{{ number_format($deductions->insurance_value_monthly ?? 0, 2) }}</span>
                        </div>
                        @endif
                        <div class="preview-total">
                            <span>Total Deductions</span>
                            <span id="totalDeductionsMonthly">₹{{ number_format($preview->total_deductions_monthly ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="preview-section">
                    <h4>Summary (Monthly)</h4>
                    <div class="preview-row">
                        <span>Gross Salary</span>
                        <span id="grossSalaryMonthly">₹{{ number_format($preview->gross_salary_monthly ?? 0, 2) }}</span>
                    </div>
                    <div class="preview-row">
                        <span>Net Salary</span>
                        <span id="netSalaryMonthly" class="highlight">₹{{ number_format($preview->net_salary_monthly ?? 0, 2) }}</span>
                    </div>
                    <div class="preview-row">
                        <span>Employer PF</span>
                        <span id="employerPFMonthly">₹{{ number_format($deductions->employer_pf_monthly ?? 0, 2) }}</span>
                    </div>
                    <div class="preview-row">
                        <span>Employer ESI</span>
                        <span id="employerESIMonthly">₹{{ number_format($deductions->employer_esi_monthly ?? 0, 2) }}</span>
                    </div>
                    <div class="preview-row">
                        <span>Employer NPS</span>
                        <span id="employerNPSMonthly">₹{{ number_format($deductions->employer_nps_monthly ?? 0, 2) }}</span>
                    </div>
                    <div class="preview-total">
                        <span>Total Monthly Cost</span>
                        <span id="totalCostMonthly">₹{{ number_format($preview->total_cost_monthly ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Annual Preview -->
            <div class="mt-4">
                <button class="btn btn-sm btn-outline-secondary" type="button" onclick="toggleAnnualPreview()">
                    <i class="fas fa-chart-line"></i> Show/Hide Annual Preview
                </button>
                <div id="annualPreview" style="display: none; margin-top: 20px;">
                    <div class="preview-grid">
                        <div class="preview-section">
                            <h4>Earnings (Annual)</h4>
                            <div id="earningsPreviewAnnual">
                                <div class="preview-row">
                                    <span>Basic Salary</span>
                                    <span id="previewBasicAnnual">₹{{ number_format($structure->basic_salary_annual, 2) }}</span>
                                </div>
                                @foreach($allowanceFields as $key => $field)
                                    @if($field['selected'])
                                    <div class="preview-row">
                                        <span>{{ $field['name'] }}</span>
                                        <span id="preview{{ ucfirst($key) }}Annual">₹{{ number_format($field['annual'], 2) }}</span>
                                    </div>
                                    @endif
                                @endforeach
                                <div class="preview-total">
                                    <span>Total Earnings</span>
                                    <span id="totalEarningsAnnual">₹{{ number_format($preview->total_earnings_annual ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="preview-section">
                            <h4>Deductions (Annual)</h4>
                            <div id="deductionsPreviewAnnual">
                                @if(($deductions->pf_employee_annual ?? 0) > 0)
                                <div class="preview-row">
                                    <span>PF (Employee)</span>
                                    <span id="previewPFAnnual">₹{{ number_format($deductions->pf_employee_annual ?? 0, 2) }}</span>
                                </div>
                                @endif
                                @if(($deductions->esi_employee_annual ?? 0) > 0)
                                <div class="preview-row">
                                    <span>ESI (Employee)</span>
                                    <span id="previewESIAnnual">₹{{ number_format($deductions->esi_employee_annual ?? 0, 2) }}</span>
                                </div>
                                @endif
                                @if(($deductions->nps_employee_annual ?? 0) > 0)
                                <div class="preview-row">
                                    <span>NPS (Employee)</span>
                                    <span id="previewNPSAnnual">₹{{ number_format($deductions->nps_employee_annual ?? 0, 2) }}</span>
                                </div>
                                @endif
                                @if(($deductions->pt_value_annual ?? 0) > 0)
                                <div class="preview-row">
                                    <span>Professional Tax</span>
                                    <span id="previewPTAnnual">₹{{ number_format($deductions->pt_value_annual ?? 0, 2) }}</span>
                                </div>
                                @endif
                                @if(($deductions->lst_value_annual ?? 0) > 0)
                                <div class="preview-row">
                                    <span>Labor State Tax</span>
                                    <span id="previewLSTAnnual">₹{{ number_format($deductions->lst_value_annual ?? 0, 2) }}</span>
                                </div>
                                @endif
                                @if(($deductions->tds_value_annual ?? 0) > 0)
                                <div class="preview-row">
                                    <span>TDS</span>
                                    <span id="previewTDSAnnual">₹{{ number_format($deductions->tds_value_annual ?? 0, 2) }}</span>
                                </div>
                                @endif
                                <div class="preview-total">
                                    <span>Total Deductions</span>
                                    <span id="totalDeductionsAnnual">₹{{ number_format($preview->total_deductions_annual ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="preview-section">
                            <h4>Summary (Annual)</h4>
                            <div class="preview-row">
                                <span>Gross Salary</span>
                                <span id="grossSalaryAnnual">₹{{ number_format($preview->gross_salary_annual ?? 0, 2) }}</span>
                            </div>
                            <div class="preview-row">
                                <span>Net Salary</span>
                                <span id="netSalaryAnnual" class="highlight">₹{{ number_format($preview->net_salary_annual ?? 0, 2) }}</span>
                            </div>
                            <div class="preview-row">
                                <span>Variable CTC</span>
                                <span id="variableCTCAnnual">₹{{ number_format($structure->variable_ctc_annual ?? 0, 2) }}</span>
                            </div>
                            <div class="preview-total">
                                <span>Total Annual Cost</span>
                                <span id="totalCostAnnual">₹{{ number_format($preview->total_cost_annual ?? 0, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('institute.payroll.salary.management') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <button class="btn btn-primary" onclick="saveDraft()">
            <i class="fas fa-save"></i> Save Draft
        </button>
        <button class="btn btn-success" onclick="saveAllChanges()">
            <i class="fas fa-check-circle"></i> Save All Changes
        </button>
    </div>
</div>

<script>
// Store the salary structure ID
const salaryStructureId = '{{ $structure->salary_structure_id }}';
let calculationTimeout = null;
const livePolicyData = {
    statutory: @json($policy),
    allowances: @json($policyAllowances),
    taxDeductions: @json($policyTaxDeductions),
    otherDeductions: @json($policyOtherDeductions),
};

// Helper functions
function num(val) {
    let parsed = parseFloat(val);
    return isNaN(parsed) ? 0 : parsed;
}

function formatCurrency(amount) {
    let numAmount = num(amount);
    return '₹' + numAmount.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

// Toggle annual preview
function toggleAnnualPreview() {
    const preview = document.getElementById('annualPreview');
    if (preview.style.display === 'none') {
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
}

function getCurrentPolicyData() {
    const policyData = JSON.parse(JSON.stringify(livePolicyData || {}));

    document.querySelectorAll('.allowance-input').forEach(input => {
        const type = input.dataset.type;
        if (type && policyData.allowances) {
            policyData.allowances[`${type}_value`] = num(input.value);
        }
    });

    document.querySelectorAll('.deduction-input').forEach(input => {
        const type = input.dataset.type;
        if (!type) return;

        if (['pt', 'lst', 'tds'].includes(type) && policyData.taxDeductions) {
            policyData.taxDeductions[`${type}_value`] = num(input.value);
        }

        if (['insurance', 'advance'].includes(type) && policyData.otherDeductions) {
            policyData.otherDeductions[`${type}_value`] = num(input.value);
        }
    });

    return policyData;
}

function updateAmountDisplay(selector, monthly, annual) {
    const display = document.querySelector(selector);
    if (display) {
        display.innerHTML = `<span>Monthly: ${formatCurrency(monthly)}</span><span>Annual: ${formatCurrency(annual)}</span>`;
    }
}

function applyLivePreview(data) {
    const basicMonthly = num(document.getElementById('basicSalaryMonthly').value);
    const basicAnnual = basicMonthly * 12;
    const variableAnnual = num(document.getElementById('variableCTCAnnual').value);
    const fixedAnnual = num(data?.ctc?.fixed || 0);
    const totalAnnual = num(data?.ctc?.total || 0);
    const monthlyFixed = fixedAnnual / 12;
    const monthlyVariable = variableAnnual / 12;
    const basicPercentage = fixedAnnual > 0 ? ((basicAnnual / fixedAnnual) * 100) : 0;

    document.getElementById('basicSalaryAnnual').value = formatCurrency(basicAnnual);
    document.getElementById('fixedCTCAnnual').value = fixedAnnual.toFixed(2);
    document.getElementById('totalCTCAnnual').value = formatCurrency(totalAnnual);
    document.getElementById('monthlyFixed').value = formatCurrency(monthlyFixed);
    document.getElementById('monthlyVariable').value = formatCurrency(monthlyVariable);
    document.getElementById('totalMonthlyCTC').value = formatCurrency(monthlyFixed + monthlyVariable);
    document.getElementById('basicPercentage').value = `${basicPercentage.toFixed(2)}%`;
    document.getElementById('previewBasicMonthly').innerText = formatCurrency(basicMonthly);
    document.getElementById('previewBasicAnnual').innerText = formatCurrency(basicAnnual);
    document.getElementById('variableCTCAnnual').innerText = formatCurrency(variableAnnual);

    const allowanceMap = {
        hra: ['previewHraMonthly', 'previewHraAnnual'],
        conveyance: ['previewConveyanceMonthly', 'previewConveyanceAnnual'],
        medical: ['previewMedicalMonthly', 'previewMedicalAnnual'],
        special: ['previewSpecialMonthly', 'previewSpecialAnnual'],
        lta: ['previewLtaMonthly', 'previewLtaAnnual'],
        education: ['previewEducationMonthly', 'previewEducationAnnual'],
    };

    const allowanceItems = Array.isArray(data?.allowances?.items) ? data.allowances.items : [];
    Object.entries(allowanceMap).forEach(([key, ids]) => {
        const item = allowanceItems.find(entry => entry.id === key);
        if (document.getElementById(ids[0])) document.getElementById(ids[0]).innerText = formatCurrency(item?.monthlyAmount || 0);
        if (document.getElementById(ids[1])) document.getElementById(ids[1]).innerText = formatCurrency(item?.annualAmount || 0);
        updateAmountDisplay(`.allowance-item[data-allowance="${key}"] .amount-display`, item?.monthlyAmount || 0, item?.annualAmount || 0);
    });

    const tax = data?.statutory?.tax || {};
    const deductions = Array.isArray(data?.deductions?.items) ? data.deductions.items : [];
    const insuranceItem = deductions.find(item => item.id === 'insurance' || item.name === 'Insurance Premium');

    if (document.getElementById('previewPFMonthly')) document.getElementById('previewPFMonthly').innerText = formatCurrency(data?.statutory?.pf?.employee?.monthly || 0);
    if (document.getElementById('previewPFAnnual')) document.getElementById('previewPFAnnual').innerText = formatCurrency(data?.statutory?.pf?.employee?.annual || 0);
    if (document.getElementById('previewESIMonthly')) document.getElementById('previewESIMonthly').innerText = formatCurrency(data?.statutory?.esi?.employee?.monthly || 0);
    if (document.getElementById('previewESIAnnual')) document.getElementById('previewESIAnnual').innerText = formatCurrency(data?.statutory?.esi?.employee?.annual || 0);
    if (document.getElementById('previewNPSMonthly')) document.getElementById('previewNPSMonthly').innerText = formatCurrency(data?.statutory?.nps?.employee?.monthly || 0);
    if (document.getElementById('previewNPSAnnual')) document.getElementById('previewNPSAnnual').innerText = formatCurrency(data?.statutory?.nps?.employee?.annual || 0);
    if (document.getElementById('previewPTMonthly')) document.getElementById('previewPTMonthly').innerText = formatCurrency(tax?.pt?.monthly || 0);
    if (document.getElementById('previewPTAnnual')) document.getElementById('previewPTAnnual').innerText = formatCurrency(tax?.pt?.annual || 0);
    if (document.getElementById('previewLSTMonthly')) document.getElementById('previewLSTMonthly').innerText = formatCurrency(tax?.lst?.monthly || 0);
    if (document.getElementById('previewLSTAnnual')) document.getElementById('previewLSTAnnual').innerText = formatCurrency(tax?.lst?.annual || 0);
    if (document.getElementById('previewTDSMonthly')) document.getElementById('previewTDSMonthly').innerText = formatCurrency(tax?.tds?.monthly || 0);
    if (document.getElementById('previewTDSAnnual')) document.getElementById('previewTDSAnnual').innerText = formatCurrency(tax?.tds?.annual || 0);
    if (document.getElementById('previewInsuranceMonthly')) document.getElementById('previewInsuranceMonthly').innerText = formatCurrency(insuranceItem?.monthlyAmount || 0);

    updateAmountDisplay('.deduction-item[data-deduction="pt"] .amount-display', tax?.pt?.monthly || 0, tax?.pt?.annual || 0);
    updateAmountDisplay('.deduction-item[data-deduction="lst"] .amount-display', tax?.lst?.monthly || 0, tax?.lst?.annual || 0);
    updateAmountDisplay('.deduction-item[data-deduction="tds"] .amount-display', tax?.tds?.monthly || 0, tax?.tds?.annual || 0);
    updateAmountDisplay('.deduction-item[data-deduction="insurance"] .amount-display', insuranceItem?.monthlyAmount || 0, insuranceItem?.annualAmount || 0);

    document.getElementById('totalEarningsMonthly').innerText = formatCurrency(data?.calculations?.monthly?.earnings || 0);
    document.getElementById('totalEarningsAnnual').innerText = formatCurrency(data?.calculations?.annual?.earnings || 0);
    document.getElementById('totalDeductionsMonthly').innerText = formatCurrency(data?.calculations?.monthly?.deductions || 0);
    document.getElementById('totalDeductionsAnnual').innerText = formatCurrency(data?.calculations?.annual?.deductions || 0);
    document.getElementById('grossSalaryMonthly').innerText = formatCurrency(data?.calculations?.monthly?.gross || 0);
    document.getElementById('grossSalaryAnnual').innerText = formatCurrency(data?.calculations?.annual?.gross || 0);
    document.getElementById('netSalaryMonthly').innerText = formatCurrency(data?.calculations?.monthly?.net || 0);
    document.getElementById('netSalaryAnnual').innerText = formatCurrency(data?.calculations?.annual?.net || 0);
    document.getElementById('employerPFMonthly').innerText = formatCurrency(data?.statutory?.pf?.employer?.monthly || 0);
    document.getElementById('employerESIMonthly').innerText = formatCurrency(data?.statutory?.esi?.employer?.monthly || 0);
    document.getElementById('employerNPSMonthly').innerText = formatCurrency(data?.statutory?.nps?.employer?.monthly || 0);
    document.getElementById('totalCostMonthly').innerText = formatCurrency(data?.calculations?.monthly?.totalCost || 0);
    document.getElementById('totalCostAnnual').innerText = formatCurrency(data?.calculations?.annual?.totalCost || 0);
}

async function calculateAll() {
    try {
        const response = await fetch('/salary-structure/live-preview', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                basic_monthly: num(document.getElementById('basicSalaryMonthly').value),
                variable_ctc: num(document.getElementById('variableCTCAnnual').value),
                policy_data: getCurrentPolicyData()
            })
        });

        if (!response.ok) {
            throw new Error('Live preview fetch failed');
        }

        const data = await response.json();
        applyLivePreview(data);
    } catch (error) {
        console.error('Preview calculation failed:', error);
        showToast('Unable to recalculate salary preview right now.', 'error');
    }
}

// Schedule calculation on input changes
function scheduleCalculation() {
    if (calculationTimeout) clearTimeout(calculationTimeout);
    calculationTimeout = setTimeout(() => {
        calculateAll();
    }, 300);
}

// Save draft to localStorage
function saveDraft() {
    let draftData = {
        basicSalaryMonthly: document.getElementById('basicSalaryMonthly').value,
        fixedCTCAnnual: document.getElementById('fixedCTCAnnual').value,
        variableCTCAnnual: document.getElementById('variableCTCAnnual').value,
        allowances: {},
        deductions: {}
    };
    
    document.querySelectorAll('.allowance-input').forEach(input => {
        draftData.allowances[input.dataset.type] = {
            value: input.value,
            calcType: input.dataset.calcType
        };
    });
    
    document.querySelectorAll('.deduction-input').forEach(input => {
        draftData.deductions[input.dataset.type] = {
            value: input.value,
            calcType: input.dataset.calcType
        };
    });
    
    localStorage.setItem('salary_edit_draft', JSON.stringify(draftData));
    showToast('Draft saved!', 'success');
}

// Save all changes to server
async function saveAllChanges() {
    if (!confirm('Are you sure you want to save all changes?')) {
        return;
    }
    
    showLoading(true);
    
    try {
        let basicMonthly = num(document.getElementById('basicSalaryMonthly').value);
        let fixedCTC = num(document.getElementById('fixedCTCAnnual').value);
        let variableCTC = num(document.getElementById('variableCTCAnnual').value);
        
        // 1. Update salary structure
        let structureData = {
            basic_salary_monthly: basicMonthly,
            basic_salary_annual: basicMonthly * 12,
            fixed_ctc_annual: fixedCTC,
            variable_ctc_annual: variableCTC,
            total_ctc_annual: fixedCTC + variableCTC
        };
        
        let structureResponse = await fetch(`/institute/admin/payroll/salary-structure/update/${salaryStructureId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(structureData)
        });
        
        if (!structureResponse.ok) throw new Error('Failed to update salary structure');
        
        // 2. Update allowances with proper calculations
        let allowanceData = {};
        document.querySelectorAll('.allowance-input').forEach(input => {
            let type = input.dataset.type;
            let calcType = input.dataset.calcType;
            let value = num(input.value);
            let monthlyAmount = value;
            
            if (calcType === 'percentage') {
                monthlyAmount = basicMonthly * (value / 100);
            }
            
            allowanceData[`${type}_selected`] = true;
            allowanceData[`${type}_type`] = calcType;
            allowanceData[`${type}_value`] = value;
            allowanceData[`${type}_value_monthly`] = monthlyAmount;
            allowanceData[`${type}_value_annual`] = monthlyAmount * 12;
        });
        
        let allowancesResponse = await fetch(`/institute/admin/payroll/salary-structure/allowances/${salaryStructureId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(allowanceData)
        });
        
        if (!allowancesResponse.ok) throw new Error('Failed to update allowances');
        
        // 3. Update tax deductions with proper calculations
        let totalEarningsMonthly = num(document.getElementById('totalEarningsMonthly').innerText.replace(/[^0-9.-]/g, ''));
        let deductionData = {};
        
        document.querySelectorAll('.deduction-input').forEach(input => {
            let type = input.dataset.type;
            let calcType = input.dataset.calcType;
            let value = num(input.value);
            let monthlyAmount = value;
            
            if (calcType === 'percentage') {
                monthlyAmount = totalEarningsMonthly * (value / 100);
            }
            
            deductionData[`${type}_selected`] = true;
            if (type !== 'tds') {
                deductionData[`${type}_type`] = calcType;
            }
            deductionData[`${type}_value`] = value;
            deductionData[`${type}_value_monthly`] = monthlyAmount;
            deductionData[`${type}_value_annual`] = monthlyAmount * 12;
        });
        
        let deductionsResponse = await fetch(`/institute/admin/payroll/salary-structure/deductions/${salaryStructureId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(deductionData)
        });
        
        if (!deductionsResponse.ok) throw new Error('Failed to update deductions');
        
        // 4. Update preview
        let previewData = {
            basic_salary_monthly: basicMonthly,
            basic_salary_annual: basicMonthly * 12,
            total_earnings_monthly: num(document.getElementById('totalEarningsMonthly').innerText.replace(/[^0-9.-]/g, '')),
            total_earnings_annual: num(document.getElementById('totalEarningsAnnual').innerText.replace(/[^0-9.-]/g, '')),
            total_deductions_monthly: num(document.getElementById('totalDeductionsMonthly').innerText.replace(/[^0-9.-]/g, '')),
            total_deductions_annual: num(document.getElementById('totalDeductionsAnnual').innerText.replace(/[^0-9.-]/g, '')),
            gross_salary_monthly: num(document.getElementById('grossSalaryMonthly').innerText.replace(/[^0-9.-]/g, '')),
            gross_salary_annual: num(document.getElementById('grossSalaryAnnual').innerText.replace(/[^0-9.-]/g, '')),
            net_salary_monthly: num(document.getElementById('netSalaryMonthly').innerText.replace(/[^0-9.-]/g, '')),
            net_salary_annual: num(document.getElementById('netSalaryAnnual').innerText.replace(/[^0-9.-]/g, '')),
            total_cost_monthly: num(document.getElementById('totalCostMonthly').innerText.replace(/[^0-9.-]/g, '')),
            total_cost_annual: num(document.getElementById('totalCostAnnual').innerText.replace(/[^0-9.-]/g, '')),
            employer_pf_monthly: num(document.getElementById('employerPFMonthly').innerText.replace(/[^0-9.-]/g, '')),
            employer_pf_annual: num(document.getElementById('employerPFMonthly').innerText.replace(/[^0-9.-]/g, '')) * 12,
            employer_esi_monthly: num(document.getElementById('employerESIMonthly').innerText.replace(/[^0-9.-]/g, '')),
            employer_esi_annual: num(document.getElementById('employerESIMonthly').innerText.replace(/[^0-9.-]/g, '')) * 12,
            employer_nps_monthly: num(document.getElementById('employerNPSMonthly').innerText.replace(/[^0-9.-]/g, '')),
            employer_nps_annual: num(document.getElementById('employerNPSMonthly').innerText.replace(/[^0-9.-]/g, '')) * 12,
        };
        
        let previewResponse = await fetch(`/institute/admin/payroll/salary-structure/preview/${salaryStructureId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(previewData)
        });
        
        if (!previewResponse.ok) throw new Error('Failed to update preview');
        
        showToast('All changes saved successfully!', 'success');
        
        // Redirect after 2 seconds
        setTimeout(() => {
            window.location.href = '{{ route("institute.payroll.salary.management") }}';
        }, 2000);
        
    } catch (error) {
        console.error('Save error:', error);
        showToast('Error saving changes: ' + error.message, 'error');
    } finally {
        showLoading(false);
    }
}

// Show/hide loading overlay
function showLoading(show) {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = show ? 'flex' : 'none';
    }
}

// Show toast notification
function showToast(message, type = 'success') {
    alert(message);
}

// Add event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Add input event listeners
    document.getElementById('basicSalaryMonthly').addEventListener('input', scheduleCalculation);
    document.getElementById('fixedCTCAnnual').addEventListener('input', scheduleCalculation);
    document.getElementById('variableCTCAnnual').addEventListener('input', scheduleCalculation);
    
    document.querySelectorAll('.allowance-input').forEach(input => {
        input.addEventListener('input', scheduleCalculation);
    });
    
    document.querySelectorAll('.deduction-input').forEach(input => {
        input.addEventListener('input', scheduleCalculation);
    });
    
    // Initial calculation
    calculateAll();
});
</script>
@endsection
