@extends('instituteAdmin.Payroll.PayrollManagement')

@section('payroll-content')
<style>
/* Modern Edit Page Styles */
.edit-page-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.edit-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 25px 30px;
    margin-bottom: 30px;
    color: white;
}

.edit-header h1 {
    font-size: 1.8rem;
    font-weight: 600;
    margin: 0 0 10px 0;
}

.edit-header h1 i {
    margin-right: 12px;
}

.policy-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.2);
    padding: 8px 16px;
    border-radius: 40px;
    font-size: 0.85rem;
    margin-top: 12px;
}

.target-info-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 25px;
    border: 1px solid #e0e7ff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.target-info-card h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #4f46e5;
    margin-bottom: 15px;
}

.target-details {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}

.target-detail-item {
    display: flex;
    align-items: baseline;
    gap: 10px;
}

.target-detail-item .label {
    font-size: 0.8rem;
    color: #6b7280;
    font-weight: 500;
}

.target-detail-item .value {
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
}

/* Step Navigation */
.edit-timeline {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
    background: white;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.edit-timeline-step {
    flex: 1;
    text-align: center;
    position: relative;
    cursor: pointer;
    transition: all 0.3s;
}

.edit-timeline-step::after {
    content: "";
    position: absolute;
    top: 20px;
    left: 50%;
    right: -50%;
    height: 2px;
    background: #e5e7eb;
    z-index: 0;
}

.edit-timeline-step:last-child::after {
    display: none;
}

.edit-timeline-step .step-dot {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f3f4f6;
    border: 2px solid #d1d5db;
    color: #6b7280;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    position: relative;
    z-index: 1;
    transition: all 0.3s;
}

.edit-timeline-step.active .step-dot {
    background: #4f46e5;
    border-color: #4f46e5;
    color: white;
}

.edit-timeline-step.completed .step-dot {
    background: #10b981;
    border-color: #10b981;
    color: white;
}

.edit-timeline-step .step-label {
    margin-top: 10px;
    font-size: 0.75rem;
    font-weight: 600;
    color: #6b7280;
}

.edit-timeline-step.active .step-label {
    color: #4f46e5;
}

/* Edit Sections */
.edit-section {
    background: white;
    border-radius: 20px;
    margin-bottom: 25px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    display: none;
}

.edit-section.active {
    display: block;
    animation: fadeInUp 0.4s ease;
}

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

.section-header-modern {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 20px 25px;
    border-bottom: 1px solid #e2e8f0;
}

.section-header-modern h3 {
    font-size: 1.3rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.section-content {
    padding: 25px;
}

/* Contribution Cards */
.contribution-card {
    background: #f8fafc;
    border-radius: 16px;
    margin-bottom: 20px;
    border: 1px solid #e2e8f0;
}

.card-header-toggle {
    padding: 18px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    background: white;
    border-radius: 16px;
}

.card-header-toggle h4 {
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
    color: #1e293b;
}

.card-header-toggle h4 i {
    color: #4f46e5;
    margin-right: 10px;
}

.card-body-toggle {
    padding: 0 20px 20px 20px;
    display: none;
    border-top: 1px solid #e2e8f0;
    margin-top: 10px;
}

.card-body-toggle.show {
    display: block;
}

/* Toggle Switch */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: 0.3s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #4f46e5;
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
}

/* Form Groups */
.form-group-modern {
    margin: 10px;
    padding: 22px;
    background: white;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
}

.form-group-modern label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #475569;
    margin-bottom: 8px;
    display: block;
}

.form-control-modern {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.9rem;
}

.input-group-modern {
    display: flex;
    gap: 12px;
    align-items: center;
}

.input-suffix {
    color: #64748b;
    font-size: 0.85rem;
}

/* Radio Group */
.radio-group-modern {
    display: flex;
    gap: 20px;
    margin-top: 8px;
}

.radio-label-modern {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 0.85rem;
}

/* Items Grid */
.items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 16px;
}

.item-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
}

.item-card.selected {
    background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%);
    border-color: #4f46e5;
}

.item-header {
    padding: 14px 16px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.item-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.item-header-left i {
    font-size: 1.1rem;
    color: #4f46e5;
}

.item-options {
    padding: 12px 16px;
    border-top: 1px solid #e2e8f0;
    background: white;
    display: none;
}

.item-options.show {
    display: block;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.btn-modern {
    padding: 10px 28px;
    border-radius: 40px;
    font-weight: 600;
    font-size: 0.9rem;
    border: none;
    cursor: pointer;
}

.btn-modern-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
    color: white;
}

.btn-modern-secondary {
    background: #f1f5f9;
    color: #475569;
}

/* Loading Overlay */
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

.loading-overlay.show {
    display: flex;
}

.loading-spinner {
    background: white;
    padding: 30px 40px;
    border-radius: 20px;
    text-align: center;
}

.loading-spinner i {
    font-size: 2rem;
    color: #4f46e5;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Toast */
.toast-notification {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: white;
    padding: 14px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 10000;
    transform: translateX(400px);
    transition: transform 0.3s ease;
}

.toast-notification.show {
    transform: translateX(0);
}

.toast-success {
    border-left: 4px solid #10b981;
}

.toast-error {
    border-left: 4px solid #ef4444;
}

/* Disabled custom item styles */
.item-card.disabled {
    opacity: 0.7;
    background: #f3f4f6;
}
.item-card.disabled .item-header {
    cursor: not-allowed;
}
.item-card.disabled .toggle-switch {
    opacity: 0.7;
}
</style>

<div class="edit-page-container">
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p class="mt-2">Loading policy data...</p>
        </div>
    </div>

    <div class="edit-header">
        <h1><i class="fas fa-edit"></i> Edit Payroll Policy</h1>
        <div class="policy-badge">
            <i class="fas fa-id-card"></i> Policy ID: <strong>{{ $policyId }}</strong>
            <i class="fas fa-calendar-alt ml-3"></i> Financial Year: <strong>{{ $policy->financial_year ?? 'N/A' }}</strong>
        </div>
    </div>

    <div class="target-info-card">
        <h4><i class="fas fa-info-circle"></i> Policy Target</h4>
        <div class="target-details">
            <div class="target-detail-item">
                <span class="label">Target Type:</span>
                <span class="value">{{ ucfirst($targetType) }}</span>
            </div>
            <div class="target-detail-item">
                <span class="label">Target Name:</span>
                <span class="value">{{ $targetName }}</span>
            </div>
        </div>
    </div>

    <div class="edit-timeline">
        <div class="edit-timeline-step active" data-step="1">
            <div class="step-dot">1</div>
            <div class="step-label">Statutory Deductions</div>
        </div>
        <div class="edit-timeline-step" data-step="2">
            <div class="step-dot">2</div>
            <div class="step-label">Allowances</div>
        </div>
        <div class="edit-timeline-step" data-step="3">
            <div class="step-dot">3</div>
            <div class="step-label">Tax Deductions</div>
        </div>
        <div class="edit-timeline-step" data-step="4">
            <div class="step-dot">4</div>
            <div class="step-label">Other Deductions</div>
        </div>
        <div class="edit-timeline-step" data-step="5">
            <div class="step-dot">5</div>
            <div class="step-label">Bonus & Overtime</div>
        </div>
    </div>

    <!-- Step 1: Statutory Deductions -->
    <div class="edit-section active" data-step="1">
        <div class="section-header-modern">
            <h3><i class="fas fa-chart-line"></i> Statutory Deductions</h3>
        </div>
        <div class="section-content">
            <!-- PF -->
            <div class="contribution-card">
                <div class="card-header-toggle" data-target="pfBody">
                    <h4><i class="fas fa-landmark"></i> Provident Fund (PF)</h4>
                    <label class="toggle-switch" onclick="event.stopPropagation()">
                        <input type="checkbox" id="enable_pf_edit">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="wage-limit-container" id="pfWageLimitContainerEdit">
                    <div class="form-group-modern mt-3">
                        <label>
                            <i class="fas fa-money-bill-wave"></i>
                            PF Wage Limit
                        </label>

                        <div class="input-group-modern">
                            <span class="input-suffix">₹</span>

                            <input
                                type="number"
                                class="form-control-modern"
                                id="pf_wage_limit_edit"
                                min="0"
                                step="1"
                                placeholder="Enter PF Wage Limit">

                            <span class="input-suffix">Monthly</span>
                        </div>

                        <small class="text-muted">
                            Leave 0 if no wage limit should be applied.
                        </small>
                    </div>
                </div>

                <div class="card-body-toggle" id="pfBody">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label>Employee Contribution</label>
                                <label class="toggle-switch mb-2" onclick="event.stopPropagation()">
                                    <input type="checkbox" id="pf_employee_enabled_edit">
                                    <span class="toggle-slider"></span>
                                </label>
                                <div id="pfEmployeeOptionsEdit">
                                    <div class="radio-group-modern mb-2">
                                        <label class="radio-label-modern" onclick="event.stopPropagation()">
                                            <input type="radio" name="pf_employee_type_edit" value="percentage"> Percentage
                                        </label>
                                        <label class="radio-label-modern" onclick="event.stopPropagation()">
                                            <input type="radio" name="pf_employee_type_edit" value="fixed"> Fixed
                                        </label>
                                    </div>
                                    <div class="input-group-modern" onclick="event.stopPropagation()">
                                        <input type="number" class="form-control-modern" id="pf_employee_value_edit" step="0.01">
                                        <span class="input-suffix" id="pfEmployeeSuffixEdit">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label>Employer Contribution</label>
                                <label class="toggle-switch mb-2" onclick="event.stopPropagation()">
                                    <input type="checkbox" id="pf_employer_enabled_edit">
                                    <span class="toggle-slider"></span>
                                </label>
                                <div id="pfEmployerOptionsEdit">
                                    <div class="radio-group-modern mb-2">
                                        <label class="radio-label-modern" onclick="event.stopPropagation()">
                                            <input type="radio" name="pf_employer_type_edit" value="percentage"> Percentage
                                        </label>
                                        <label class="radio-label-modern" onclick="event.stopPropagation()">
                                            <input type="radio" name="pf_employer_type_edit" value="fixed"> Fixed
                                        </label>
                                    </div>
                                    <div class="input-group-modern" onclick="event.stopPropagation()">
                                        <input type="number" class="form-control-modern" id="pf_employer_value_edit" step="0.01">
                                        <span class="input-suffix" id="pfEmployerSuffixEdit">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ESI -->
            <div class="contribution-card">
                <div class="card-header-toggle" data-target="esiBody">
                    <h4><i class="fas fa-heartbeat"></i> ESI</h4>
                    <label class="toggle-switch" onclick="event.stopPropagation()">
                        <input type="checkbox" id="enable_esi_edit">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="card-body-toggle" id="esiBody">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label>Employee Contribution</label>
                                <label class="toggle-switch mb-2" onclick="event.stopPropagation()">
                                    <input type="checkbox" id="esi_employee_enabled_edit">
                                    <span class="toggle-slider"></span>
                                </label>
                                <div id="esiEmployeeOptionsEdit">
                                    <div class="radio-group-modern mb-2">
                                        <label class="radio-label-modern" onclick="event.stopPropagation()">
                                            <input type="radio" name="esi_employee_type_edit" value="percentage"> Percentage
                                        </label>
                                        <label class="radio-label-modern" onclick="event.stopPropagation()">
                                            <input type="radio" name="esi_employee_type_edit" value="fixed"> Fixed
                                        </label>
                                    </div>
                                    <div class="input-group-modern" onclick="event.stopPropagation()">
                                        <input type="number" class="form-control-modern" id="esi_employee_value_edit" step="0.01">
                                        <span class="input-suffix" id="esiEmployeeSuffixEdit">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label>Employer Contribution</label>
                                <label class="toggle-switch mb-2" onclick="event.stopPropagation()">
                                    <input type="checkbox" id="esi_employer_enabled_edit">
                                    <span class="toggle-slider"></span>
                                </label>
                                <div id="esiEmployerOptionsEdit">
                                    <div class="radio-group-modern mb-2">
                                        <label class="radio-label-modern" onclick="event.stopPropagation()">
                                            <input type="radio" name="esi_employer_type_edit" value="percentage"> Percentage
                                        </label>
                                        <label class="radio-label-modern" onclick="event.stopPropagation()">
                                            <input type="radio" name="esi_employer_type_edit" value="fixed"> Fixed
                                        </label>
                                    </div>
                                    <div class="input-group-modern" onclick="event.stopPropagation()">
                                        <input type="number" class="form-control-modern" id="esi_employer_value_edit" step="0.01">
                                        <span class="input-suffix" id="esiEmployerSuffixEdit">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NPS -->
            <div class="contribution-card">
                <div class="card-header-toggle" data-target="npsBody">
                    <h4><i class="fas fa-chart-line"></i> NPS</h4>
                    <label class="toggle-switch" onclick="event.stopPropagation()">
                        <input type="checkbox" id="enable_nps_edit">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="card-body-toggle" id="npsBody">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label>Employee Contribution</label>
                                <label class="toggle-switch mb-2" onclick="event.stopPropagation()">
                                    <input type="checkbox" id="nps_employee_enabled_edit">
                                    <span class="toggle-slider"></span>
                                </label>
                                <div id="npsEmployeeOptionsEdit">
                                    <div class="radio-group-modern mb-2">
                                        <label class="radio-label-modern" onclick="event.stopPropagation()">
                                            <input type="radio" name="nps_employee_type_edit" value="percentage"> Percentage
                                        </label>
                                        <label class="radio-label-modern" onclick="event.stopPropagation()">
                                            <input type="radio" name="nps_employee_type_edit" value="fixed"> Fixed
                                        </label>
                                    </div>
                                    <div class="input-group-modern" onclick="event.stopPropagation()">
                                        <input type="number" class="form-control-modern" id="nps_employee_value_edit" step="0.01">
                                        <span class="input-suffix" id="npsEmployeeSuffixEdit">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group-modern">
                                <label>Employer Contribution</label>
                                <label class="toggle-switch mb-2" onclick="event.stopPropagation()">
                                    <input type="checkbox" id="nps_employer_enabled_edit">
                                    <span class="toggle-slider"></span>
                                </label>
                                <div id="npsEmployerOptionsEdit">
                                    <div class="radio-group-modern mb-2">
                                        <label class="radio-label-modern" onclick="event.stopPropagation()">
                                            <input type="radio" name="nps_employer_type_edit" value="percentage"> Percentage
                                        </label>
                                        <label class="radio-label-modern" onclick="event.stopPropagation()">
                                            <input type="radio" name="nps_employer_type_edit" value="fixed"> Fixed
                                        </label>
                                    </div>
                                    <div class="input-group-modern" onclick="event.stopPropagation()">
                                        <input type="number" class="form-control-modern" id="nps_employer_value_edit" step="0.01">
                                        <span class="input-suffix" id="npsEmployerSuffixEdit">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <button class="btn-modern btn-modern-secondary" id="cancelEditBtn">Cancel</button>
                <button class="btn-modern btn-modern-primary" id="nextToAllowancesBtn">Next: Allowances →</button>
            </div>
        </div>
    </div>

    <!-- Step 2: Allowances -->
    <div class="edit-section" data-step="2">
        <div class="section-header-modern">
            <h3><i class="fas fa-gift"></i> Allowances</h3>
        </div>
        <div class="section-content">
            <div class="items-grid" id="allowancesGridEdit">
                @php
                    $allowanceTypes = ['hra' => 'Home', 'conveyance' => 'Car', 'medical' => 'First Aid', 'special' => 'Star', 'lta' => 'Plane', 'education' => 'Graduation Cap'];
                    $allowanceIcons = ['hra' => 'home', 'conveyance' => 'car', 'medical' => 'first-aid', 'special' => 'star', 'lta' => 'plane', 'education' => 'graduation-cap'];
                    $allowanceNames = ['hra' => 'House Rent Allowance (HRA)', 'conveyance' => 'Conveyance Allowance', 'medical' => 'Medical Allowance', 'special' => 'Special Allowance', 'lta' => 'Leave Travel Allowance (LTA)', 'education' => 'Education Allowance'];
                @endphp
                @foreach($allowanceTypes as $type => $icon)
                <div class="item-card" data-type="{{ $type }}">
                    <div class="item-header" data-toggle-options="{{ $type }}Options">
                        <div class="item-header-left">
                            <i class="fas fa-{{ $allowanceIcons[$type] }}"></i>
                            <span>{{ $allowanceNames[$type] }}</span>
                        </div>
                        <label class="toggle-switch" onclick="event.stopPropagation()">
                            <input type="checkbox" class="allowance-checkbox-edit" data-type="{{ $type }}">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="item-options" id="{{ $type }}Options">
                        <div class="radio-group-modern" onclick="event.stopPropagation()">
                            <label class="radio-label-modern">
                                <input type="radio" name="{{ $type }}_type_edit" value="percentage"> Percentage
                            </label>
                            <label class="radio-label-modern">
                                <input type="radio" name="{{ $type }}_type_edit" value="fixed"> Fixed
                            </label>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div id="customAllowancesContainerEdit" class="items-grid mt-3"></div>
            <button class="btn-modern btn-modern-secondary mt-3" id="addCustomAllowanceEditBtn">+ Add Custom Allowance</button>

            <div class="action-buttons">
                <button class="btn-modern btn-modern-secondary" id="backToStatutoryBtn">← Back</button>
                <button class="btn-modern btn-modern-primary" id="nextToTaxDeductionsBtn">Next: Tax Deductions →</button>
            </div>
        </div>
    </div>

    <!-- Step 3: Tax Deductions -->
    <div class="edit-section" data-step="3">
        <div class="section-header-modern">
            <h3><i class="fas fa-file-invoice-dollar"></i> Tax Deductions</h3>
        </div>
        <div class="section-content">
            <div class="item-card mb-3">
                <div class="item-header" data-toggle-options="ptOptions">
                    <div class="item-header-left">
                        <i class="fas fa-file-invoice"></i>
                        <span>Professional Tax (PT)</span>
                    </div>
                    <label class="toggle-switch" onclick="event.stopPropagation()">
                        <input type="checkbox" id="pt_enabled_edit">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="item-options" id="ptOptions">
                    <div class="radio-group-modern mb-3" onclick="event.stopPropagation()">
                        <label class="radio-label-modern"><input type="radio" name="pt_type_edit" value="percentage"> Percentage</label>
                        <label class="radio-label-modern"><input type="radio" name="pt_type_edit" value="fixed"> Fixed</label>
                        <label class="radio-label-modern"><input type="radio" name="pt_type_edit" value="slabs"> Slabs</label>
                    </div>
                    <div id="ptSlabsContainerEdit" style="display:none">
                        <div id="ptSlabsListEdit"></div>
                        <button class="btn-modern btn-modern-secondary btn-sm" id="addPtSlabEditBtn">+ Add Slab</button>
                    </div>
                </div>
            </div>

            <div class="item-card mb-3">
                <div class="item-header" data-toggle-options="lstOptions">
                    <div class="item-header-left">
                        <i class="fas fa-university"></i>
                        <span>Labor State Tax (LST)</span>
                    </div>
                    <label class="toggle-switch" onclick="event.stopPropagation()">
                        <input type="checkbox" id="lst_enabled_edit">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="item-options" id="lstOptions">
                    <div class="radio-group-modern mb-3" onclick="event.stopPropagation()">
                        <label class="radio-label-modern"><input type="radio" name="lst_type_edit" value="percentage"> Percentage</label>
                        <label class="radio-label-modern"><input type="radio" name="lst_type_edit" value="fixed"> Fixed</label>
                        <label class="radio-label-modern"><input type="radio" name="lst_type_edit" value="slabs"> Slabs</label>
                    </div>
                    <div id="lstSlabsContainerEdit" style="display:none">
                        <div id="lstSlabsListEdit"></div>
                        <button class="btn-modern btn-modern-secondary btn-sm" id="addLstSlabEditBtn">+ Add Slab</button>
                    </div>
                </div>
            </div>

            <div class="item-card">
                <div class="item-header" data-toggle-options="tdsOptions">
                    <div class="item-header-left">
                        <i class="fas fa-receipt"></i>
                        <span>TDS</span>
                    </div>
                    <label class="toggle-switch" onclick="event.stopPropagation()">
                        <input type="checkbox" id="tds_enabled_edit">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="item-options" id="tdsOptions">
                    <div id="tdsSlabsListEdit"></div>
                </div>
            </div>

            <div class="action-buttons">
                <button class="btn-modern btn-modern-secondary" id="backToAllowancesBtn">← Back</button>
                <button class="btn-modern btn-modern-primary" id="nextToOtherDeductionsBtn">Next: Other Deductions →</button>
            </div>
        </div>
    </div>

    <!-- Step 4: Other Deductions -->
    <div class="edit-section" data-step="4">
        <div class="section-header-modern">
            <h3><i class="fas fa-hand-holding-usd"></i> Other Deductions</h3>
        </div>
        <div class="section-content">
            <div class="items-grid" id="otherDeductionsGridEdit">
                @php
                    $deductionTypes = ['insurance' => 'Shield Alt', 'loan' => 'Hand Holding Usd', 'advance' => 'Money Bill Wave'];
                    $deductionNames = ['insurance' => 'Insurance Premium', 'loan' => 'Loan Deduction', 'advance' => 'Advance Salary'];
                @endphp
                @foreach($deductionTypes as $type => $icon)
                <div class="item-card" data-type="{{ $type }}">
                    <div class="item-header" data-toggle-options="{{ $type }}Options">
                        <div class="item-header-left">
                            <i class="fas fa-{{ strtolower(str_replace(' ', '-', $icon)) }}"></i>
                            <span>{{ $deductionNames[$type] }}</span>
                        </div>
                        <label class="toggle-switch" onclick="event.stopPropagation()">
                            <input type="checkbox" class="deduction-checkbox-edit" data-type="{{ $type }}">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="item-options" id="{{ $type }}Options">
                        <div class="radio-group-modern" onclick="event.stopPropagation()">
                            <label class="radio-label-modern"><input type="radio" name="{{ $type }}_type_edit" value="percentage"> Percentage</label>
                            <label class="radio-label-modern"><input type="radio" name="{{ $type }}_type_edit" value="fixed"> Fixed</label>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div id="customDeductionsContainerEdit" class="items-grid mt-3"></div>
            <button class="btn-modern btn-modern-secondary mt-3" id="addCustomDeductionEditBtn">+ Add Custom Deduction</button>

            <div class="action-buttons">
                <button class="btn-modern btn-modern-secondary" id="backToTaxDeductionsBtn">← Back</button>
                <button class="btn-modern btn-modern-primary" id="nextToBonusOvertime">Next: Bonus & Overtime →</button>
            </div>
        </div>
    </div>

    <!-- Step Bonus & Overtime -->
    <div class="edit-section" data-step="5">
        <div class="section-header-modern">
            <h3>
                <i class="fas fa-gift"></i>
                Bonus & Overtime
            </h3>
        </div>

        <div class="section-content">

            <!-- Overtime -->
            <div class="contribution-card">
                <div class="card-header-toggle">
                    <h4>
                        <i class="fas fa-business-time"></i>
                        Overtime
                    </h4>

                    <label class="toggle-switch">
                        <input type="checkbox" id="overtime_enabled_edit">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <!-- Bonuses -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Bonuses</h5>

                <button type="button"
                        class="btn-modern btn-modern-secondary"
                        id="addBonusBtn">
                    + Add Bonus
                </button>
            </div>

            <div id="bonusesContainerEdit"></div>

            <div class="action-buttons">
                <button class="btn-modern btn-modern-secondary"
                        id="backToOtherDeductionsBtn">
                    ← Back
                </button>

                <button class="btn-modern btn-modern-primary" id="savePolicyEditBtn">Save Changes</button>
            </div>

        </div>
    </div>

</div>

<!-- Modals -->
<div class="modal fade" id="addPtSlabModalEdit">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5>Add PT Slab</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <input type="number" id="ptSlabFromEdit" class="form-control-modern mb-2" placeholder="From (₹)">
                <input type="number" id="ptSlabToEdit" class="form-control-modern mb-2" placeholder="To (₹)">
                <input type="number" id="ptSlabAmountEdit" class="form-control-modern" placeholder="Amount (₹)">
            </div>
            <div class="modal-footer">
                <button class="btn-modern btn-modern-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn-modern btn-modern-primary" id="savePtSlabEditBtn">Add</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addLstSlabModalEdit">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5>Add LST Slab</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <input type="number" id="lstSlabFromEdit" class="form-control-modern mb-2" placeholder="From (₹)">
                <input type="number" id="lstSlabToEdit" class="form-control-modern mb-2" placeholder="To (₹)">
                <input type="number" id="lstSlabRateEdit" class="form-control-modern" placeholder="Rate (%)">
            </div>
            <div class="modal-footer">
                <button class="btn-modern btn-modern-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn-modern btn-modern-primary" id="saveLstSlabEditBtn">Add</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCustomAllowanceModalEdit">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5>Add Custom Allowance</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <input type="text" id="customAllowanceNameEdit" class="form-control-modern mb-2" placeholder="Name">
                <select id="customAllowanceTypeEdit" class="form-control-modern">
                    <option value="fixed">Fixed</option><option value="percentage">Percentage</option>
                </select>
                <textarea id="customAllowanceDescEdit" class="form-control-modern mt-2" placeholder="Description"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn-modern btn-modern-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn-modern btn-modern-primary" id="saveCustomAllowanceEditBtn">Add</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCustomDeductionModalEdit">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5>Add Custom Deduction</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
            <div class="modal-body">
                <input type="text" id="customDeductionNameEdit" class="form-control-modern mb-2" placeholder="Name">
                <select id="customDeductionTypeEdit" class="form-control-modern">
                    <option value="fixed">Fixed</option><option value="percentage">Percentage</option>
                </select>
                <textarea id="customDeductionDescEdit" class="form-control-modern mt-2" placeholder="Description"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn-modern btn-modern-secondary" data-dismiss="modal">Cancel</button>
                <button class="btn-modern btn-modern-primary" id="saveCustomDeductionEditBtn">Add</button>
            </div>
        </div>
    </div>
</div>

<script>
const policyId = '{{ $policyId }}';
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
let policyData = null, allowancesData = null, taxDeductionsData = null, otherDeductionsData = null;
let customAllowancesList = [], customDeductionsList = [], ptSlabsList = [], lstSlabsList = [];
let bonusOvertimeData = null;
// Add this variable at the top of your script
let pendingOverrideConfirmation = null;

document.addEventListener('DOMContentLoaded', () => {
    loadPolicyData();
    setupEventListeners();
});

async function loadPolicyData() {
    showLoading(true);
    try {
        const res = await fetch(`/institute/admin/payroll/api/policy-data/${policyId}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const result = await res.json();
        if (result.success) {
            policyData = result.data.policy;
            allowancesData = result.data.allowances;
            taxDeductionsData = result.data.tax_deductions;
            otherDeductionsData = result.data.other_deductions;
            bonusOvertimeData = result.data.bonus_overtime;
            populateBonusOvertime();
            initArrays();
            populateStatutory();
            populateAllowances();
            populateTaxDeductions();
            populateOtherDeductions();
        }
    } catch(e) { showToast('Error loading data', 'error'); }
    finally { showLoading(false); }
}

function initArrays() {
    ptSlabsList = taxDeductionsData?.pt_slabs ? (typeof taxDeductionsData.pt_slabs === 'string' ? JSON.parse(taxDeductionsData.pt_slabs) : taxDeductionsData.pt_slabs) : [];
    lstSlabsList = taxDeductionsData?.lst_slabs ? (typeof taxDeductionsData.lst_slabs === 'string' ? JSON.parse(taxDeductionsData.lst_slabs) : taxDeductionsData.lst_slabs) : [];
    if (!Array.isArray(ptSlabsList)) ptSlabsList = [];
    if (!Array.isArray(lstSlabsList)) lstSlabsList = [];
}

function populateBonusOvertime() {

    if (!bonusOvertimeData) return;

    $('#overtime_enabled_edit').prop(
        'checked',
        bonusOvertimeData.overtime_enabled == 1
    );

    $('#bonusesContainerEdit').empty();

    if (bonusOvertimeData.bonuses) {

        bonusOvertimeData.bonuses.forEach(bonus => {

            $('#bonusesContainerEdit').append(
                createBonusCard(bonus)
            );

        });

    }
}

function populateStatutory() {
    if (!policyData) return;
    
    const pfWageLimit = document.getElementById('pf_wage_limit_edit');
    if (pfWageLimit) {
        pfWageLimit.value = policyData.pf_wage_limit || '';
    }

    // PF Section
    const enablePf = document.getElementById('enable_pf_edit');
    const pfBody = document.getElementById('pfBody');
    if (enablePf) {
        enablePf.checked = policyData.enable_pf == 1;
        if (pfBody) {
            pfBody.classList.toggle('show', policyData.enable_pf == 1);
        }
    }
    
    // PF Employee
    const pfEmployeeEnabled = document.getElementById('pf_employee_enabled_edit');
    if (pfEmployeeEnabled) {
        pfEmployeeEnabled.checked = policyData.pf_employee_enabled == 1;
        const pfEmployeeOptions = document.getElementById('pfEmployeeOptionsEdit');
        if (pfEmployeeOptions) {
            pfEmployeeOptions.style.display = policyData.pf_employee_enabled == 1 ? 'block' : 'none';
        }
    }
    
    // PF Employer
    const pfEmployerEnabled = document.getElementById('pf_employer_enabled_edit');
    if (pfEmployerEnabled) {
        pfEmployerEnabled.checked = policyData.pf_employer_enabled == 1;
        const pfEmployerOptions = document.getElementById('pfEmployerOptionsEdit');
        if (pfEmployerOptions) {
            pfEmployerOptions.style.display = policyData.pf_employer_enabled == 1 ? 'block' : 'none';
        }
    }
    
    // PF Employee Type & Value
    if (policyData.pf_employee_type) {
        const radio = document.querySelector(`input[name="pf_employee_type_edit"][value="${policyData.pf_employee_type}"]`);
        if (radio) radio.checked = true;
    }
    const pfEmployeeValue = document.getElementById('pf_employee_value_edit');
    if (pfEmployeeValue) pfEmployeeValue.value = policyData.pf_employee_value || '';
    
    // PF Employer Type & Value
    if (policyData.pf_employer_type) {
        const radio = document.querySelector(`input[name="pf_employer_type_edit"][value="${policyData.pf_employer_type}"]`);
        if (radio) radio.checked = true;
    }
    const pfEmployerValue = document.getElementById('pf_employer_value_edit');
    if (pfEmployerValue) pfEmployerValue.value = policyData.pf_employer_value || '';
    
    // ESI Section
    const enableEsi = document.getElementById('enable_esi_edit');
    const esiBody = document.getElementById('esiBody');
    if (enableEsi) {
        enableEsi.checked = policyData.enable_esi == 1;
        if (esiBody) {
            esiBody.classList.toggle('show', policyData.enable_esi == 1);
        }
    }
    
    // ESI Employee
    const esiEmployeeEnabled = document.getElementById('esi_employee_enabled_edit');
    if (esiEmployeeEnabled) {
        esiEmployeeEnabled.checked = policyData.esi_employee_enabled == 1;
        const esiEmployeeOptions = document.getElementById('esiEmployeeOptionsEdit');
        if (esiEmployeeOptions) {
            esiEmployeeOptions.style.display = policyData.esi_employee_enabled == 1 ? 'block' : 'none';
        }
    }
    
    // ESI Employer
    const esiEmployerEnabled = document.getElementById('esi_employer_enabled_edit');
    if (esiEmployerEnabled) {
        esiEmployerEnabled.checked = policyData.esi_employer_enabled == 1;
        const esiEmployerOptions = document.getElementById('esiEmployerOptionsEdit');
        if (esiEmployerOptions) {
            esiEmployerOptions.style.display = policyData.esi_employer_enabled == 1 ? 'block' : 'none';
        }
    }
    
    // ESI Employee Type & Value
    if (policyData.esi_employee_type) {
        const radio = document.querySelector(`input[name="esi_employee_type_edit"][value="${policyData.esi_employee_type}"]`);
        if (radio) radio.checked = true;
    }
    const esiEmployeeValue = document.getElementById('esi_employee_value_edit');
    if (esiEmployeeValue) esiEmployeeValue.value = policyData.esi_employee_value || '';
    
    // ESI Employer Type & Value
    if (policyData.esi_employer_type) {
        const radio = document.querySelector(`input[name="esi_employer_type_edit"][value="${policyData.esi_employer_type}"]`);
        if (radio) radio.checked = true;
    }
    const esiEmployerValue = document.getElementById('esi_employer_value_edit');
    if (esiEmployerValue) esiEmployerValue.value = policyData.esi_employer_value || '';
    
    // NPS Section
    const enableNps = document.getElementById('enable_nps_edit');
    const npsBody = document.getElementById('npsBody');
    if (enableNps) {
        enableNps.checked = policyData.enable_nps == 1;
        if (npsBody) {
            npsBody.classList.toggle('show', policyData.enable_nps == 1);
        }
    }
    
    // NPS Employee
    const npsEmployeeEnabled = document.getElementById('nps_employee_enabled_edit');
    if (npsEmployeeEnabled) {
        npsEmployeeEnabled.checked = policyData.nps_employee_enabled == 1;
        const npsEmployeeOptions = document.getElementById('npsEmployeeOptionsEdit');
        if (npsEmployeeOptions) {
            npsEmployeeOptions.style.display = policyData.nps_employee_enabled == 1 ? 'block' : 'none';
        }
    }
    
    // NPS Employer
    const npsEmployerEnabled = document.getElementById('nps_employer_enabled_edit');
    if (npsEmployerEnabled) {
        npsEmployerEnabled.checked = policyData.nps_employer_enabled == 1;
        const npsEmployerOptions = document.getElementById('npsEmployerOptionsEdit');
        if (npsEmployerOptions) {
            npsEmployerOptions.style.display = policyData.nps_employer_enabled == 1 ? 'block' : 'none';
        }
    }
    
    // NPS Employee Type & Value
    if (policyData.nps_employee_type) {
        const radio = document.querySelector(`input[name="nps_employee_type_edit"][value="${policyData.nps_employee_type}"]`);
        if (radio) radio.checked = true;
    }
    const npsEmployeeValue = document.getElementById('nps_employee_value_edit');
    if (npsEmployeeValue) npsEmployeeValue.value = policyData.nps_employee_value || '';
    
    // NPS Employer Type & Value
    if (policyData.nps_employer_type) {
        const radio = document.querySelector(`input[name="nps_employer_type_edit"][value="${policyData.nps_employer_type}"]`);
        if (radio) radio.checked = true;
    }
    const npsEmployerValue = document.getElementById('nps_employer_value_edit');
    if (npsEmployerValue) npsEmployerValue.value = policyData.nps_employer_value || '';
    
    // Update suffix displays
    updateSuffixes();
}

function populateAllowances() {
    if (!allowancesData) return;
    ['hra', 'conveyance', 'medical', 'special', 'lta', 'education'].forEach(type => {
        const cb = document.querySelector(`.allowance-checkbox-edit[data-type="${type}"]`);
        const optionsDiv = document.getElementById(`${type}Options`);
        const card = cb?.closest('.item-card');
        if (cb) {
            cb.checked = allowancesData[`${type}_selected`] == 1;
            if (cb.checked) {
                card?.classList.add('selected');
                if (optionsDiv) optionsDiv.classList.add('show');
            } else {
                card?.classList.remove('selected');
                if (optionsDiv) optionsDiv.classList.remove('show');
            }
            const radio = document.querySelector(`input[name="${type}_type_edit"][value="${allowancesData[`${type}_type`]}"]`);
            if (radio) radio.checked = true;
        }
    });
    if (allowancesData.custom_allowances) {
        let ca = allowancesData.custom_allowances;
        if (typeof ca === 'string') ca = JSON.parse(ca);
        if (Array.isArray(ca)) ca.forEach(a => addCustomAllowanceToUI(a, false)); // false = do not auto-show options on load
    }
}

function populateTaxDeductions() {
    if (!taxDeductionsData) return;
    // PT checkbox
    const ptCheckbox = document.getElementById('pt_enabled_edit');
    const ptOptionsDiv = document.getElementById('ptOptions');
    if (ptCheckbox) {
        ptCheckbox.checked = taxDeductionsData.pt_selected == 1;
        if (ptOptionsDiv) {
            ptOptionsDiv.classList.toggle('show', taxDeductionsData.pt_selected == 1);
        }
    }
    setRadio('pt_type_edit', taxDeductionsData.pt_type);
    
    // LST checkbox
    const lstCheckbox = document.getElementById('lst_enabled_edit');
    const lstOptionsDiv = document.getElementById('lstOptions');
    if (lstCheckbox) {
        lstCheckbox.checked = taxDeductionsData.lst_selected == 1;
        if (lstOptionsDiv) {
            lstOptionsDiv.classList.toggle('show', taxDeductionsData.lst_selected == 1);
        }
    }
    setRadio('lst_type_edit', taxDeductionsData.lst_type);
    
    // TDS checkbox
    const tdsCheckbox = document.getElementById('tds_enabled_edit');
    const tdsOptionsDiv = document.getElementById('tdsOptions');
    if (tdsCheckbox) {
        tdsCheckbox.checked = taxDeductionsData.tds_selected == 1;
        if (tdsOptionsDiv) {
            tdsOptionsDiv.classList.toggle('show', taxDeductionsData.tds_selected == 1);
        }
    }
    
    renderPtSlabs();
    renderLstSlabs();
    renderTdsSlabs();
    const ptType = getRadioValue('pt_type_edit');
    const ptContainer = document.getElementById('ptSlabsContainerEdit');
    if (ptContainer) ptContainer.style.display = ptType === 'slabs' ? 'block' : 'none';
    const lstType = getRadioValue('lst_type_edit');
    const lstContainer = document.getElementById('lstSlabsContainerEdit');
    if (lstContainer) lstContainer.style.display = lstType === 'slabs' ? 'block' : 'none';
}

function populateOtherDeductions() {
    if (!otherDeductionsData) return;
    ['insurance', 'loan', 'advance'].forEach(type => {
        const cb = document.querySelector(`.deduction-checkbox-edit[data-type="${type}"]`);
        const optionsDiv = document.getElementById(`${type}Options`);
        const card = cb?.closest('.item-card');
        if (cb) {
            cb.checked = otherDeductionsData[`${type}_selected`] == 1;
            if (cb.checked) {
                card?.classList.add('selected');
                if (optionsDiv) optionsDiv.classList.add('show');
            } else {
                card?.classList.remove('selected');
                if (optionsDiv) optionsDiv.classList.remove('show');
            }
            const radio = document.querySelector(`input[name="${type}_type_edit"][value="${otherDeductionsData[`${type}_type`]}"]`);
            if (radio) radio.checked = true;
        }
    });
    if (otherDeductionsData.custom_deductions) {
        let cd = otherDeductionsData.custom_deductions;
        if (typeof cd === 'string') cd = JSON.parse(cd);
        if (Array.isArray(cd)) cd.forEach(d => addCustomDeductionToUI(d, false));
    }
}

function renderPtSlabs() {
    const container = document.getElementById('ptSlabsListEdit');
    if (!container) return;
    if (!ptSlabsList.length) { container.innerHTML = '<p class="text-muted">No PT slabs</p>'; return; }
    container.innerHTML = ptSlabsList.map((s, i) => `<div class="slab-item mb-2 p-2 bg-light rounded d-flex justify-content-between"><div>₹${s.from} - ₹${s.to} <span class="text-primary">₹${s.amount}</span></div><button class="btn btn-sm btn-outline-danger delete-pt-slab" data-index="${i}"><i class="fas fa-trash"></i></button></div>`).join('');
    document.querySelectorAll('.delete-pt-slab').forEach(btn => btn.onclick = () => { ptSlabsList.splice(parseInt(btn.dataset.index), 1); renderPtSlabs(); });
}

function renderLstSlabs() {
    const container = document.getElementById('lstSlabsListEdit');
    if (!container) return;
    if (!lstSlabsList.length) { container.innerHTML = '<p class="text-muted">No LST slabs</p>'; return; }
    container.innerHTML = lstSlabsList.map((s, i) => `<div class="slab-item mb-2 p-2 bg-light rounded d-flex justify-content-between"><div>₹${s.from} - ₹${s.to} <span class="text-primary">${s.rate}%</span></div><button class="btn btn-sm btn-outline-danger delete-lst-slab" data-index="${i}"><i class="fas fa-trash"></i></button></div>`).join('');
    document.querySelectorAll('.delete-lst-slab').forEach(btn => btn.onclick = () => { lstSlabsList.splice(parseInt(btn.dataset.index), 1); renderLstSlabs(); });
}

function renderTdsSlabs() {
    const container = document.getElementById('tdsSlabsListEdit');
    if (container) container.innerHTML = '<p class="text-muted">TDS slabs loaded from financial year</p>';
}

function addCustomAllowanceToUI(a, autoShowOptions = true) {
    const id = 'ca_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5);
    // Store enabled status - if a.enabled is not defined, default to true
    const isEnabled = a.enabled !== undefined ? a.enabled : true;
    customAllowancesList.push({ id, name: a.name, type: a.type, desc: a.description, enabled: isEnabled });
    
    const html = `
        <div class="item-card custom-item selected" data-id="${id}">
            <div class="item-header" data-toggle-options="${id}_opts">
                <div class="item-header-left">
                    <i class="fas fa-money-check-alt"></i>
                    <span>${escapeHtml(a.name)}</span>
                    <span class="badge badge-secondary">Custom</span>
                </div>
            </div>

            <div class="item-options show" id="${id}_opts">
                <div class="radio-group-modern">
                    <label class="radio-label-modern">
                        <input type="radio" name="${id}_type" value="percentage"
                            ${a.type === 'percentage' ? 'checked' : ''}>
                        Percentage
                    </label>

                    <label class="radio-label-modern">
                        <input type="radio" name="${id}_type" value="fixed"
                            ${a.type === 'fixed' ? 'checked' : ''}>
                        Fixed
                    </label>
                </div>

                <button class="btn btn-sm btn-outline-danger remove-custom-allowance mt-2"
                    data-id="${id}">
                    Remove
                </button>
            </div>
        </div>
    `;
    
    const container = document.getElementById('customAllowancesContainerEdit');
    container.insertAdjacentHTML('beforeend', html);
    setupCustomItemEvents(id, 'allowance', autoShowOptions);
}

function addCustomDeductionToUI(d, autoShowOptions = true) {
    const id = 'cd_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5);
    const isEnabled = d.enabled !== undefined ? d.enabled : true;
    customDeductionsList.push({ id, name: d.name, type: d.type, desc: d.description, enabled: isEnabled });
    
    const html = `
        <div class="item-card custom-item selected" data-id="${id}">
            <div class="item-header" data-toggle-options="${id}_opts">
                <div class="item-header-left">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>${escapeHtml(d.name)}</span>
                    <span class="badge badge-secondary">Custom</span>
                </div>
            </div>

            <div class="item-options show" id="${id}_opts">
                <div class="radio-group-modern">
                    <label class="radio-label-modern">
                        <input type="radio" name="${id}_type" value="percentage"
                            ${d.type === 'percentage' ? 'checked' : ''}>
                        Percentage
                    </label>

                    <label class="radio-label-modern">
                        <input type="radio" name="${id}_type" value="fixed"
                            ${d.type === 'fixed' ? 'checked' : ''}>
                        Fixed
                    </label>
                </div>

                <button class="btn btn-sm btn-outline-danger remove-custom-deduction mt-2"
                    data-id="${id}">
                    Remove
                </button>
            </div>
        </div>
    `;
    
    const container = document.getElementById('customDeductionsContainerEdit');
    container.insertAdjacentHTML('beforeend', html);
    setupCustomItemEvents(id, 'deduction', autoShowOptions);
}

function setupCustomItemEvents(id, type, autoShowOptions = true) {
    const container = type === 'allowance' ? document.getElementById('customAllowancesContainerEdit') : document.getElementById('customDeductionsContainerEdit');
    const card = container.querySelector(`[data-id="${id}"]`);
    if (!card) return;
    
    const header = card.querySelector('.item-header');
    const cb = card.querySelector(`.${type === 'allowance' ? 'custom-allowance-checkbox' : 'custom-deduction-checkbox'}`);
    const opts = card.querySelector('.item-options');
    const remove = card.querySelector(`.remove-custom-${type}`);
    
    // Toggle options when header is clicked (only if enabled)
    header?.addEventListener('click', (e) => {
        if (!e.target.closest('.toggle-switch')) {
            // Only allow opening options if the item is enabled
            if (cb && cb.checked) {
                opts?.classList.toggle('show');
            } else if (cb && !cb.checked) {
                // If disabled, don't open options
                e.stopPropagation();
            }
        }
    });
    
    // Handle enable/disable toggle
    cb?.addEventListener('change', function(e) {
        e.stopPropagation();
        const item = (type === 'allowance' ? customAllowancesList : customDeductionsList).find(i => i.id === id);
        if (item) item.enabled = this.checked;
        
        if (this.checked) {
            card.classList.remove('disabled');
            card.classList.add('selected');
            if (autoShowOptions && opts) opts.classList.add('show');
        } else {
            card.classList.add('disabled');
            card.classList.remove('selected');
            if (opts) opts.classList.remove('show');
        }
    });
    
    // Handle radio button changes
    card.querySelectorAll(`input[name="${id}_type"]`).forEach(r => {
        r.addEventListener('change', (e) => {
            e.stopPropagation();
            const item = (type === 'allowance' ? customAllowancesList : customDeductionsList).find(i => i.id === id);
            if (item) item.type = r.value;
        });
    });
    
    // Handle remove button
    remove?.addEventListener('click', (e) => {
        e.stopPropagation();
        card.remove();
        const arr = type === 'allowance' ? customAllowancesList : customDeductionsList;
        const idx = arr.findIndex(i => i.id === id);
        if (idx !== -1) arr.splice(idx, 1);
        showToast(`${type === 'allowance' ? 'Allowance' : 'Deduction'} removed`, 'success');
    });
    
    // Set initial state
    if (cb && cb.checked) {
        card.classList.add('selected');
        card.classList.remove('disabled');
        if (autoShowOptions && opts) opts.classList.add('show');
    } else if (cb && !cb.checked) {
        card.classList.add('disabled');
        card.classList.remove('selected');
        if (opts) opts.classList.remove('show');
    }
}

// Modify the saveAll function
async function saveAll() {
    showLoading(true);
    try {
        // Save statutory deductions
        const policyUpdateResponse = await fetch(`/institute/admin/payroll/api/update-policy/${policyId}`, {
            method: 'POST', 
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({
                enable_pf: getCheckbox('enable_pf_edit'), 
                pf_wage_limit: getNumber('pf_wage_limit_edit'),
                pf_employee_enabled: getCheckbox('pf_employee_enabled_edit'),
                pf_employee_type: getRadioValue('pf_employee_type_edit'), 
                pf_employee_value: getNumber('pf_employee_value_edit'),
                pf_employer_enabled: getCheckbox('pf_employer_enabled_edit'), 
                pf_employer_type: getRadioValue('pf_employer_type_edit'),
                pf_employer_value: getNumber('pf_employer_value_edit'), 
                enable_esi: getCheckbox('enable_esi_edit'),
                esi_employee_enabled: getCheckbox('esi_employee_enabled_edit'), 
                esi_employee_type: getRadioValue('esi_employee_type_edit'),
                esi_employee_value: getNumber('esi_employee_value_edit'), 
                esi_employer_enabled: getCheckbox('esi_employer_enabled_edit'),
                esi_employer_type: getRadioValue('esi_employer_type_edit'), 
                esi_employer_value: getNumber('esi_employer_value_edit'),
                enable_nps: getCheckbox('enable_nps_edit'), 
                nps_employee_enabled: getCheckbox('nps_employee_enabled_edit'),
                nps_employee_type: getRadioValue('nps_employee_type_edit'), 
                nps_employee_value: getNumber('nps_employee_value_edit'),
                nps_employer_enabled: getCheckbox('nps_employer_enabled_edit'), 
                nps_employer_type: getRadioValue('nps_employer_type_edit'),
                nps_employer_value: getNumber('nps_employer_value_edit')
            })
        });
        
        const policyResult = await policyUpdateResponse.json();
        
        // Check if confirmation is required
        if (!policyResult.success && policyResult.requires_confirmation) {
            showLoading(false);
            showStructureDisableWarning(policyResult.data.structures_count, policyId, async () => {
                // Retry with confirmation
                const retryResponse = await fetch(`/institute/admin/payroll/api/update-policy/${policyId}`, {
                    method: 'POST', 
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({
                        enable_pf: getCheckbox('enable_pf_edit'), pf_employee_enabled: getCheckbox('pf_employee_enabled_edit'),
                        pf_employee_type: getRadioValue('pf_employee_type_edit'), pf_employee_value: getNumber('pf_employee_value_edit'),
                        pf_employer_enabled: getCheckbox('pf_employer_enabled_edit'), pf_employer_type: getRadioValue('pf_employer_type_edit'),
                        pf_employer_value: getNumber('pf_employer_value_edit'), enable_esi: getCheckbox('enable_esi_edit'),
                        esi_employee_enabled: getCheckbox('esi_employee_enabled_edit'), esi_employee_type: getRadioValue('esi_employee_type_edit'),
                        esi_employee_value: getNumber('esi_employee_value_edit'), esi_employer_enabled: getCheckbox('esi_employer_enabled_edit'),
                        esi_employer_type: getRadioValue('esi_employer_type_edit'), esi_employer_value: getNumber('esi_employer_value_edit'),
                        enable_nps: getCheckbox('enable_nps_edit'), nps_employee_enabled: getCheckbox('nps_employee_enabled_edit'),
                        nps_employee_type: getRadioValue('nps_employee_type_edit'), nps_employee_value: getNumber('nps_employee_value_edit'),
                        nps_employer_enabled: getCheckbox('nps_employer_enabled_edit'), nps_employer_type: getRadioValue('nps_employer_type_edit'),
                        nps_employer_value: getNumber('nps_employer_value_edit'),
                        confirm_disable_structures: true  // Add this flag
                    })
                });
                const retryResult = await retryResponse.json();
                if (retryResult.success) {
                    await saveRemainingData();
                } else {
                    showToast('Failed to update policy', 'error');
                }
            });
            return;
        }
        
        if (!policyResult.success) {
            throw new Error(policyResult.message || 'Policy update failed');
        }
        
        await saveRemainingData();
        
    } catch(e) { 
        showToast('Error saving policy: ' + (e.message || 'Unknown error'), 'error'); 
        showLoading(false);
    }
}

async function saveRemainingData() {
    // Save allowances
    const allowanceData = { custom_allowances: customAllowancesList.map(a => ({ name: a.name, type: a.type, description: a.desc, enabled: a.enabled ? 1 : 0 })) };
    ['hra', 'conveyance', 'medical', 'special', 'lta', 'education'].forEach(t => {
        const cb = document.querySelector(`.allowance-checkbox-edit[data-type="${t}"]`);
        allowanceData[`${t}_selected`] = cb?.checked ? 1 : 0;
        allowanceData[`${t}_type`] = document.querySelector(`input[name="${t}_type_edit"]:checked`)?.value || 'fixed';
    });
    await fetch(`/institute/admin/payroll/api/update-allowances/${policyId}`, {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify(allowanceData)
    });
    
    // Save tax deductions
    await fetch(`/institute/admin/payroll/api/update-tax-deductions/${policyId}`, {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ pt_selected: getCheckbox('pt_enabled_edit'), pt_type: getRadioValue('pt_type_edit'), pt_slabs: ptSlabsList, lst_selected: getCheckbox('lst_enabled_edit'), lst_type: getRadioValue('lst_type_edit'), lst_slabs: lstSlabsList, tds_selected: getCheckbox('tds_enabled_edit') })
    });
    
    // Save other deductions
    const otherData = { custom_deductions: customDeductionsList.map(d => ({ name: d.name, type: d.type, description: d.desc, enabled: d.enabled ? 1 : 0 })) };
    ['insurance', 'loan', 'advance'].forEach(t => {
        const cb = document.querySelector(`.deduction-checkbox-edit[data-type="${t}"]`);
        otherData[`${t}_selected`] = cb?.checked ? 1 : 0;
        otherData[`${t}_type`] = document.querySelector(`input[name="${t}_type_edit"]:checked`)?.value || 'fixed';
    });
    await fetch(`/institute/admin/payroll/api/update-other-deductions/${policyId}`, {
        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify(otherData)
    });

    let bonuses = [];

    $('.bonus-card').each(function () {
        bonuses.push({
            name: $(this).find('.bonus-name').val(),
            enabled: $(this).find('.bonus-enabled').is(':checked') ? 1 : 0,
            month: $(this).find('.bonus-month').val(),
            value: $(this).find('.bonus-value').val(),
            type: $(this).find('.bonus-type').val()
        });
    });

    await fetch('/payroll-policy/bonus-overtime/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            payroll_policy_id: policyId,
            overtime_enabled: getCheckbox('overtime_enabled_edit'),
            bonuses: bonuses
        })
    });

    
    showToast('Policy updated successfully!', 'success');
    setTimeout(() => window.location.href = '/institute/admin/payroll/policy-management', 1500);
}

function showStructureDisableWarning(count, policyId, onConfirm) {
    const warningHtml = `
        <div class="modal fade" id="structureWarningModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                    <div class="modal-header border-0" style="background: #fef3c7;">
                        <h5 class="modal-title text-warning"><i class="fas fa-exclamation-triangle"></i> Warning: Existing Structures Found</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="fas fa-building" style="font-size: 48px; color: #f59e0b;"></i>
                        <h4 class="mt-3">${count} Salary Structure(s) Will Be Disabled</h4>
                        <p class="text-muted mt-3">
                            This policy has ${count} existing salary structure(s).<br>
                            <strong>Editing this policy will disable all linked salary structures.</strong><br>
                            You will need to recreate them after saving.
                        </p>
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-info-circle"></i> Are you sure you want to proceed?
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-warning" id="confirmStructureDisable">Yes, Proceed & Disable Structures</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if present
    const existingModal = document.getElementById('structureWarningModal');
    if (existingModal) existingModal.remove();
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', warningHtml);
    
    // Show modal
    $('#structureWarningModal').modal('show');
    
    // Handle confirm
    document.getElementById('confirmStructureDisable').onclick = () => {
        $('#structureWarningModal').modal('hide');
        if (onConfirm) onConfirm();
    };
}

function setupEventListeners() {
    document.querySelectorAll('.edit-timeline-step').forEach(s => s.onclick = () => showStep(parseInt(s.dataset.step)));
    document.getElementById('nextToAllowancesBtn').onclick = () => showStep(2);
    document.getElementById('nextToTaxDeductionsBtn').onclick = () => showStep(3);
    document.getElementById('nextToOtherDeductionsBtn').onclick = () => showStep(4);
    document.getElementById('nextToBonusOvertime').onclick = () => showStep(5);
    document.getElementById('backToStatutoryBtn').onclick = () => showStep(1);
    document.getElementById('backToAllowancesBtn').onclick = () => showStep(2);
    document.getElementById('backToTaxDeductionsBtn').onclick = () => showStep(3);
    document.getElementById('backToOtherDeductionsBtn').onclick = () => showStep(4);
    document.getElementById('savePolicyEditBtn').onclick = saveAll;
    document.getElementById('cancelEditBtn').onclick = () => { if(confirm('Cancel changes?')) window.location.href = '/institute/admin/payroll/policy-management'; };
    
    // Card header toggle for statutory sections (click on header toggles body)
    document.querySelectorAll('.card-header-toggle').forEach(header => {
        header.addEventListener('click', function(e) {
            // Don't toggle if clicking on toggle switch - toggle switch has its own handler
            if (!e.target.closest('.toggle-switch')) {
                const targetId = this.dataset.target;
                const body = document.getElementById(targetId);
                if (body) {
                    body.classList.toggle('show');
                }
            }
        });
    });
    
    // Item header toggle for allowance/deduction sections
    document.querySelectorAll('.item-header').forEach(header => {
        header.addEventListener('click', function(e) {
            if (!e.target.closest('.toggle-switch')) {
                const targetId = this.dataset.toggleOptions;
                const optionsDiv = document.getElementById(targetId);
                // Only toggle if the associated checkbox is checked (item is enabled)
                const checkbox = this.querySelector('.toggle-switch input');
                if (optionsDiv && checkbox && checkbox.checked) {
                    optionsDiv.classList.toggle('show');
                } else if (optionsDiv && (!checkbox || !checkbox.checked)) {
                    // If item is disabled, don't open options
                    e.stopPropagation();
                }
            }
        });
    });
    
    // Allowance checkboxes: when toggled, show/hide options and selected class
    document.querySelectorAll('.allowance-checkbox-edit').forEach(cb => {
        cb.addEventListener('change', function(e) {
            e.stopPropagation();
            const card = this.closest('.item-card');
            const targetId = card?.querySelector('.item-header')?.dataset.toggleOptions;
            const optionsDiv = document.getElementById(targetId);
            if (this.checked) {
                card?.classList.add('selected');
                if (optionsDiv) optionsDiv.classList.add('show');
            } else {
                card?.classList.remove('selected');
                if (optionsDiv) optionsDiv.classList.remove('show');
            }
        });
    });
    
    // Deduction checkboxes
    document.querySelectorAll('.deduction-checkbox-edit').forEach(cb => {
        cb.addEventListener('change', function(e) {
            e.stopPropagation();
            const card = this.closest('.item-card');
            const targetId = card?.querySelector('.item-header')?.dataset.toggleOptions;
            const optionsDiv = document.getElementById(targetId);
            if (this.checked) {
                card?.classList.add('selected');
                if (optionsDiv) optionsDiv.classList.add('show');
            } else {
                card?.classList.remove('selected');
                if (optionsDiv) optionsDiv.classList.remove('show');
            }
        });
    });
    
    // Tax deduction checkboxes (PT, LST, TDS)
    const ptCheckbox = document.getElementById('pt_enabled_edit');
    if (ptCheckbox) {
        ptCheckbox.addEventListener('change', function(e) {
            e.stopPropagation();
            const ptOptions = document.getElementById('ptOptions');
            if (ptOptions) ptOptions.classList.toggle('show', this.checked);
        });
    }
    
    const lstCheckbox = document.getElementById('lst_enabled_edit');
    if (lstCheckbox) {
        lstCheckbox.addEventListener('change', function(e) {
            e.stopPropagation();
            const lstOptions = document.getElementById('lstOptions');
            if (lstOptions) lstOptions.classList.toggle('show', this.checked);
        });
    }
    
    const tdsCheckbox = document.getElementById('tds_enabled_edit');
    if (tdsCheckbox) {
        tdsCheckbox.addEventListener('change', function(e) {
            e.stopPropagation();
            const tdsOptions = document.getElementById('tdsOptions');
            if (tdsOptions) tdsOptions.classList.toggle('show', this.checked);
        });
    }
    
    // Statutory enable checkboxes: when toggled, show/hide body
    const enablePfCheckbox = document.getElementById('enable_pf_edit');
    if (enablePfCheckbox) {
        enablePfCheckbox.addEventListener('change', function() {
            const pfBody = document.getElementById('pfBody');
            if (pfBody) pfBody.classList.toggle('show', this.checked);
        });
    }
    
    const enableEsiCheckbox = document.getElementById('enable_esi_edit');
    if (enableEsiCheckbox) {
        enableEsiCheckbox.addEventListener('change', function() {
            const esiBody = document.getElementById('esiBody');
            if (esiBody) esiBody.classList.toggle('show', this.checked);
        });
    }
    
    const enableNpsCheckbox = document.getElementById('enable_nps_edit');
    if (enableNpsCheckbox) {
        enableNpsCheckbox.addEventListener('change', function() {
            const npsBody = document.getElementById('npsBody');
            if (npsBody) npsBody.classList.toggle('show', this.checked);
        });
    }
    
    // Employee/employer enabled checkboxes for statutory sections
    const pfEmployeeEnabled = document.getElementById('pf_employee_enabled_edit');
    if (pfEmployeeEnabled) {
        pfEmployeeEnabled.addEventListener('change', function() {
            const options = document.getElementById('pfEmployeeOptionsEdit');
            if (options) options.style.display = this.checked ? 'block' : 'none';
        });
    }
    
    const pfEmployerEnabled = document.getElementById('pf_employer_enabled_edit');
    if (pfEmployerEnabled) {
        pfEmployerEnabled.addEventListener('change', function() {
            const options = document.getElementById('pfEmployerOptionsEdit');
            if (options) options.style.display = this.checked ? 'block' : 'none';
        });
    }
    
    const esiEmployeeEnabled = document.getElementById('esi_employee_enabled_edit');
    if (esiEmployeeEnabled) {
        esiEmployeeEnabled.addEventListener('change', function() {
            const options = document.getElementById('esiEmployeeOptionsEdit');
            if (options) options.style.display = this.checked ? 'block' : 'none';
        });
    }
    
    const esiEmployerEnabled = document.getElementById('esi_employer_enabled_edit');
    if (esiEmployerEnabled) {
        esiEmployerEnabled.addEventListener('change', function() {
            const options = document.getElementById('esiEmployerOptionsEdit');
            if (options) options.style.display = this.checked ? 'block' : 'none';
        });
    }
    
    const npsEmployeeEnabled = document.getElementById('nps_employee_enabled_edit');
    if (npsEmployeeEnabled) {
        npsEmployeeEnabled.addEventListener('change', function() {
            const options = document.getElementById('npsEmployeeOptionsEdit');
            if (options) options.style.display = this.checked ? 'block' : 'none';
        });
    }
    
    const npsEmployerEnabled = document.getElementById('nps_employer_enabled_edit');
    if (npsEmployerEnabled) {
        npsEmployerEnabled.addEventListener('change', function() {
            const options = document.getElementById('npsEmployerOptionsEdit');
            if (options) options.style.display = this.checked ? 'block' : 'none';
        });
    }
    
    // PT/LST type change to show/hide slabs container
    document.querySelectorAll('input[name="pt_type_edit"]').forEach(r => r.onchange = () => { 
        const c = document.getElementById('ptSlabsContainerEdit'); 
        if(c) c.style.display = document.querySelector('input[name="pt_type_edit"]:checked')?.value === 'slabs' ? 'block' : 'none'; 
    });
    document.querySelectorAll('input[name="lst_type_edit"]').forEach(r => r.onchange = () => { 
        const c = document.getElementById('lstSlabsContainerEdit'); 
        if(c) c.style.display = document.querySelector('input[name="lst_type_edit"]:checked')?.value === 'slabs' ? 'block' : 'none'; 
    });
    
    // Radio change to update suffixes for statutory
    document.querySelectorAll('input[name="pf_employee_type_edit"],input[name="pf_employer_type_edit"],input[name="esi_employee_type_edit"],input[name="esi_employer_type_edit"],input[name="nps_employee_type_edit"],input[name="nps_employer_type_edit"]').forEach(r => r.onchange = updateSuffixes);
    
    // Modal buttons
    document.getElementById('addPtSlabEditBtn').onclick = () => $('#addPtSlabModalEdit').modal('show');
    document.getElementById('savePtSlabEditBtn').onclick = () => { 
        const f=parseFloat(document.getElementById('ptSlabFromEdit').value), t=parseFloat(document.getElementById('ptSlabToEdit').value), a=parseFloat(document.getElementById('ptSlabAmountEdit').value); 
        if(f && t && a && f < t){ 
            ptSlabsList.push({from:f,to:t,amount:a}); 
            renderPtSlabs(); 
            $('#addPtSlabModalEdit').modal('hide'); 
            document.querySelectorAll('#addPtSlabModalEdit input').forEach(i=>i.value=''); 
            showToast('PT slab added','success'); 
        } else showToast('Invalid values','error'); 
    };
    
    document.getElementById('addLstSlabEditBtn').onclick = () => $('#addLstSlabModalEdit').modal('show');
    document.getElementById('saveLstSlabEditBtn').onclick = () => { 
        const f=parseFloat(document.getElementById('lstSlabFromEdit').value), t=parseFloat(document.getElementById('lstSlabToEdit').value), r=parseFloat(document.getElementById('lstSlabRateEdit').value); 
        if(f && t && r && f < t){ 
            lstSlabsList.push({from:f,to:t,rate:r}); 
            renderLstSlabs(); 
            $('#addLstSlabModalEdit').modal('hide'); 
            document.querySelectorAll('#addLstSlabModalEdit input').forEach(i=>i.value=''); 
            showToast('LST slab added','success'); 
        } else showToast('Invalid values','error'); 
    };
    
    document.getElementById('addCustomAllowanceEditBtn').onclick = () => $('#addCustomAllowanceModalEdit').modal('show');
    document.getElementById('saveCustomAllowanceEditBtn').onclick = () => { 
        const n=document.getElementById('customAllowanceNameEdit').value; 
        if(n){ 
            addCustomAllowanceToUI({
                name:n, 
                type:document.getElementById('customAllowanceTypeEdit').value,
                description:document.getElementById('customAllowanceDescEdit').value,
                enabled:true
            }, true); 
            $('#addCustomAllowanceModalEdit').modal('hide'); 
            document.querySelectorAll('#addCustomAllowanceModalEdit input, #addCustomAllowanceModalEdit textarea').forEach(i=>i.value=''); 
            showToast('Allowance added','success'); 
        } else showToast('Please enter allowance name','error');
    };
    
    document.getElementById('addCustomDeductionEditBtn').onclick = () => $('#addCustomDeductionModalEdit').modal('show');
    document.getElementById('saveCustomDeductionEditBtn').onclick = () => { 
        const n=document.getElementById('customDeductionNameEdit').value; 
        if(n){ 
            addCustomDeductionToUI({
                name:n, 
                type:document.getElementById('customDeductionTypeEdit').value,
                description:document.getElementById('customDeductionDescEdit').value,
                enabled:true
            }, true); 
            $('#addCustomDeductionModalEdit').modal('hide'); 
            document.querySelectorAll('#addCustomDeductionModalEdit input, #addCustomDeductionModalEdit textarea').forEach(i=>i.value=''); 
            showToast('Deduction added','success'); 
        } else showToast('Please enter deduction name','error');
    };

    $(document).on('click', '#addBonusBtn', function () {

        $('#bonusesContainerEdit').append(
            createBonusCard()
        );

    });

    $(document).on('click', '.removeBonusBtn', function () {

        $(this).closest('.bonus-card').remove();

    });

    $('#saveBonusOvertimeBtn').on('click', function () {

        let bonuses = [];

        $('.bonus-card').each(function () {

            bonuses.push({
                name: $(this).find('.bonus-name').val(),
                enabled: $(this).find('.bonus-enabled').is(':checked') ? 1 : 0,
                month: $(this).find('.bonus-month').val(),
                value: $(this).find('.bonus-value').val(),
                type: $(this).find('.bonus-type').val()
            });

        });

        $.ajax({

            url: '/payroll-policy/bonus-overtime/save',

            method: 'POST',

            data: {
                _token: csrfToken,
                payroll_policy_id: policyId,
                overtime_enabled:
                    $('#overtime_enabled_edit').is(':checked') ? 1 : 0,
                bonuses: bonuses
            },

            success: function(res) {

                showToast(
                    'Bonus & Overtime updated successfully',
                    'success'
                );

            }

        });

    });

}

function showStep(step) {
    document.querySelectorAll('.edit-timeline-step').forEach((s,i) => { 
        s.classList.remove('active','completed'); 
        if(i+1 === step) s.classList.add('active'); 
        else if(i+1 < step) s.classList.add('completed'); 
    });
    document.querySelectorAll('.edit-section').forEach(s => s.classList.remove('active'));
    document.querySelector(`.edit-section[data-step="${step}"]`)?.classList.add('active');
}

function updateSuffixes() {
    const pfEmpSuffix = document.getElementById('pfEmployeeSuffixEdit');
    if(pfEmpSuffix) pfEmpSuffix.textContent = getRadioValue('pf_employee_type_edit') === 'percentage' ? '%' : '₹';
    const pfEmpSuffix2 = document.getElementById('pfEmployerSuffixEdit');
    if(pfEmpSuffix2) pfEmpSuffix2.textContent = getRadioValue('pf_employer_type_edit') === 'percentage' ? '%' : '₹';
    const esiEmpSuffix = document.getElementById('esiEmployeeSuffixEdit');
    if(esiEmpSuffix) esiEmpSuffix.textContent = getRadioValue('esi_employee_type_edit') === 'percentage' ? '%' : '₹';
    const esiEmpSuffix2 = document.getElementById('esiEmployerSuffixEdit');
    if(esiEmpSuffix2) esiEmpSuffix2.textContent = getRadioValue('esi_employer_type_edit') === 'percentage' ? '%' : '₹';
    const npsEmpSuffix = document.getElementById('npsEmployeeSuffixEdit');
    if(npsEmpSuffix) npsEmpSuffix.textContent = getRadioValue('nps_employee_type_edit') === 'percentage' ? '%' : '₹';
    const npsEmpSuffix2 = document.getElementById('npsEmployerSuffixEdit');
    if(npsEmpSuffix2) npsEmpSuffix2.textContent = getRadioValue('nps_employer_type_edit') === 'percentage' ? '%' : '₹';
}

function createBonusCard(bonus = {}) {

    return `
        <div class="item-card bonus-card mb-3">

            <div class="item-header">
                <div class="item-header-left">
                    <i class="fas fa-gift"></i>
                    <span>${bonus.name || 'New Bonus'}</span>
                </div>

                <label class="toggle-switch">
                    <input type="checkbox"
                           class="bonus-enabled"
                           ${bonus.enabled == 1 || bonus.enabled === undefined ? 'checked' : ''}>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="item-options show">

                <div class="form-group-modern">
                    <label>Bonus Name</label>
                    <input type="text"
                           class="form-control-modern bonus-name"
                           value="${bonus.name || ''}">
                </div>

                <div class="form-group-modern">
                    <label>Month</label>

                    <select class="form-control-modern bonus-month">
                        <option value="">Select Month</option>
                        ${[
                            'January','February','March','April',
                            'May','June','July','August',
                            'September','October','November','December'
                        ].map(month =>
                            `<option value="${month}"
                             ${bonus.month===month?'selected':''}>
                             ${month}
                             </option>`
                        ).join('')}
                    </select>
                </div>

                <div class="form-group-modern">
                    <label>Value</label>
                    <input type="number"
                           class="form-control-modern bonus-value"
                           value="${bonus.value || 0}">
                </div>

                <div class="form-group-modern">
                    <label>Type</label>

                    <select class="form-control-modern bonus-type">
                        <option value="fixed"
                            ${bonus.type==='fixed'?'selected':''}>
                            Fixed
                        </option>

                        <option value="percentage"
                            ${bonus.type==='percentage'?'selected':''}>
                            Percentage
                        </option>
                    </select>
                </div>

                <button type="button"
                        class="btn btn-danger btn-sm removeBonusBtn">
                    Remove
                </button>

            </div>

        </div>
    `;
}


function getCheckbox(id) { const el = document.getElementById(id); return el ? (el.checked ? 1 : 0) : 0; }
function setCheckbox(id, val) { const el = document.getElementById(id); if(el) el.checked = val == 1; }
function getNumber(id) { const el = document.getElementById(id); return el ? (parseFloat(el.value) || 0) : 0; }
function setValue(id, val) { const el = document.getElementById(id); if(el) el.value = val || ''; }
function getRadioValue(name) { const selected = document.querySelector(`input[name="${name}"]:checked`); return selected ? selected.value : null; }
function setRadio(name, val) { const r = document.querySelector(`input[name="${name}"][value="${val}"]`); if(r) r.checked = true; }
function showLoading(show) { const el = document.getElementById('loadingOverlay'); if(el) el.classList.toggle('show', show); }
function showToast(msg, type) { 
    const t=document.createElement('div'); 
    t.className=`toast-notification toast-${type}`; 
    t.innerHTML=`<i class="fas ${type==='success'?'fa-check-circle':'fa-exclamation-circle'}"></i><span>${msg}</span>`; 
    document.body.appendChild(t); 
    setTimeout(()=>t.classList.add('show'),100); 
    setTimeout(()=>{t.classList.remove('show');setTimeout(()=>t.remove(),300);},3000); 
}
function escapeHtml(s) { if(!s) return ''; const d=document.createElement('div'); d.textContent=s; return d.innerHTML; }
</script>
@endsection