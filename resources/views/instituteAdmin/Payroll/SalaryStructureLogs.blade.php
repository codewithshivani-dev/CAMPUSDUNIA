@extends('instituteAdmin.Payroll.PayrollManagement')
@section('payroll-content')

@php
    if (!function_exists('renderStructureData')) {
        function renderStructureData($data) {
            if (!$data) return '';
            
            if (is_string($data)) {
                $decoded = json_decode($data, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data = $decoded;
                }
            }
            
            $salary = is_array($data) ? $data : (array)$data;
            $allowances = $salary['allowances'] ?? [];
            $deductions = $salary['deductions'] ?? [];
            $preview    = $salary['preview'] ?? [];

            $basicSalaryMonthly = $salary['basic_salary_monthly'] ?? 0;
            $basicSalaryAnnual = $salary['basic_salary_annual'] ?? 0;
            $hraMonthly = $allowances['hra_value_monthly'] ?? 0;
            $conveyanceMonthly = $allowances['conveyance_value_monthly'] ?? 0;
            $medicalMonthly = $allowances['medical_value_monthly'] ?? 0;
            $specialMonthly = $allowances['special_value_monthly'] ?? 0;
            $ltaMonthly = $allowances['lta_value_monthly'] ?? 0;
            $educationMonthly = $allowances['education_value_monthly'] ?? 0;
            
            $customAllowances = $allowances['custom_allowances'] ?? [];
            $customTotalMonthly = 0;
            $customAllowancesList = [];
            
            if (is_array($customAllowances) && !empty($customAllowances)) {
                foreach ($customAllowances as $allowance) {
                    $isEnabled = ($allowance['selected'] ?? 0) == 1 || ($allowance['enabled'] ?? 0) == 1;
                    if ($isEnabled && ($allowance['value_monthly'] ?? 0) > 0) {
                        $customAllowancesList[] = $allowance;
                        $customTotalMonthly += $allowance['value_monthly'] ?? 0;
                    }
                }
            }
            
            $pfEmployeeMonthly = $preview['pf_employee_monthly'] ?? ($deductions['pf_employee_monthly'] ?? 0);
            $esiEmployeeMonthly = $preview['esi_employee_monthly'] ?? ($deductions['esi_employee_monthly'] ?? 0);
            $npsEmployeeMonthly = $preview['nps_employee_monthly'] ?? ($deductions['nps_employee_monthly'] ?? 0);
            $ptMonthly = $preview['pt_monthly'] ?? ($deductions['pt_value_monthly'] ?? 0);
            $lstMonthly = $preview['lst_monthly'] ?? ($deductions['lst_value_monthly'] ?? 0);
            $tdsMonthly = $preview['tds_monthly'] ?? ($deductions['tds_value_monthly'] ?? 0);
            $insuranceMonthly = $preview['insurance_premium_monthly'] ?? ($deductions['insurance_value_monthly'] ?? 0);
            $employerPfMonthly = $preview['employer_pf_monthly'] ?? 0;
            $employerEsiMonthly = $preview['employer_esi_monthly'] ?? 0;
            $employerNpsMonthly = $preview['employer_nps_monthly'] ?? 0;
            
            $netSalaryMonthly = $preview['net_salary_monthly'] ?? ($salary['net_salary_monthly'] ?? 0);
            $netSalaryAnnual = $preview['net_salary_annual'] ?? ($salary['net_salary_annual'] ?? 0);
            $grossSalaryMonthly = $preview['gross_salary_monthly'] ?? 0;
            $grossSalaryAnnual = $preview['gross_salary_annual'] ?? 0;
            $totalAllowancesMonthly = $preview['total_allowances_monthly'] 
                ?? ($hraMonthly + $conveyanceMonthly + $medicalMonthly + $specialMonthly + $ltaMonthly + $educationMonthly);
            $totalDeductionsMonthly = $preview['total_deductions_monthly'] 
                ?? ($pfEmployeeMonthly + $esiEmployeeMonthly + $npsEmployeeMonthly + $ptMonthly + $lstMonthly + $tdsMonthly + $insuranceMonthly);
            
            $fixedCtcAnnual = $salary['fixed_ctc_annual'] ?? 0;
            $variableCtcAnnual = $salary['variable_ctc_annual'] ?? 0;
            $totalCtcAnnual = $fixedCtcAnnual + $variableCtcAnnual;
            $employerPfAnnual = $preview['employer_pf_annual'] ?? 0;
            $employerEsiAnnual = $preview['employer_esi_annual'] ?? 0;
            $employerNpsAnnual = $preview['employer_nps_annual'] ?? 0;
            $totalCostAnnual = $preview['total_cost_annual'] ?? 0;
            
            $html = '<div class="policy-section-card">';
            $html .= '<div class="section-header-icon"><i class="fas fa-receipt"></i> Salary Structure Snapshot</div>';
            $html .= '<div class="section-divider"></div>';

            if ($basicSalaryMonthly > 0 || $fixedCtcAnnual > 0) {
                // Structure Details
                $html .= '<div class="sub-section">';
                $html .= '<div class="sub-section-title"><i class="fas fa-chart-simple"></i> Structure Details</div>';
                $html .= '<div class="detail-card">';
                $html .= '<div class="detail-field"><span class="field-label">Basic Salary (Monthly)</span><span class="field-value">₹ ' . number_format($basicSalaryMonthly) . '</span></div>';
                $html .= '<div class="detail-field"><span class="field-label">Basic Salary (Annual)</span><span class="field-value">₹ ' . number_format($basicSalaryAnnual) . '</span></div>';
                $html .= '<div class="detail-field"><span class="field-label">Fixed CTC (Annual)</span><span class="field-value">₹ ' . number_format($fixedCtcAnnual) . '</span></div>';
                $html .= '<div class="detail-field"><span class="field-label">Variable CTC (Annual)</span><span class="field-value">₹ ' . number_format($variableCtcAnnual) . '</span></div>';
                $html .= '<div class="detail-field"><span class="field-label">Total CTC (Annual)</span><span class="field-value">₹ ' . number_format($totalCtcAnnual) . '</span></div>';
                $html .= '</div></div>';

                // Standard Allowances
                $hasStandardAllowances = ($hraMonthly > 0 || $conveyanceMonthly > 0 || $medicalMonthly > 0 || $specialMonthly > 0 || $ltaMonthly > 0 || $educationMonthly > 0);
                if ($hasStandardAllowances) {
                    $html .= '<div class="sub-section">';
                    $html .= '<div class="sub-section-title"><i class="fas fa-plus-circle"></i> Allowances (Monthly)</div>';
                    $html .= '<div class="detail-card">';
                    if ($hraMonthly > 0) $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-home"></i> HRA</span><span class="field-value">₹ ' . number_format($hraMonthly) . '</span></div>';
                    if ($conveyanceMonthly > 0) $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-car"></i> Conveyance</span><span class="field-value">₹ ' . number_format($conveyanceMonthly) . '</span></div>';
                    if ($medicalMonthly > 0) $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-first-aid"></i> Medical</span><span class="field-value">₹ ' . number_format($medicalMonthly) . '</span></div>';
                    if ($specialMonthly > 0) $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-star"></i> Special</span><span class="field-value">₹ ' . number_format($specialMonthly) . '</span></div>';
                    if ($ltaMonthly > 0) $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-plane"></i> LTA</span><span class="field-value">₹ ' . number_format($ltaMonthly) . '</span></div>';
                    if ($educationMonthly > 0) $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-graduation-cap"></i> Education</span><span class="field-value">₹ ' . number_format($educationMonthly) . '</span></div>';
                    $html .= '<div class="detail-field border-top mt-1 pt-1"><span class="field-label fw-bold">Total Allowances</span><span class="field-value fw-bold">₹ ' . number_format($totalAllowancesMonthly) . '</span></div>';
                    $html .= '</div></div>';
                }

                // Custom Allowances
                if (!empty($customAllowancesList)) {
                    $html .= '<div class="sub-section">';
                    $html .= '<div class="sub-section-title"><i class="fas fa-puzzle-piece"></i> Custom Allowances</div>';
                    $html .= '<div class="detail-card">';
                    foreach ($customAllowancesList as $allowance) {
                        $name = $allowance['name'] ?? 'Custom';
                        $valueMonthly = $allowance['value_monthly'] ?? 0;
                        $html .= '<div class="detail-field"><span class="field-label">' . e($name) . '</span><span class="field-value">₹ ' . number_format($valueMonthly) . '</span></div>';
                    }
                    if ($customTotalMonthly > 0) {
                        $html .= '<div class="detail-field border-top mt-1 pt-1"><span class="field-label fw-bold">Total Custom</span><span class="field-value fw-bold">₹ ' . number_format($customTotalMonthly) . '</span></div>';
                    }
                    $html .= '</div></div>';
                }

                // Gross Salary
                $html .= '<div class="sub-section">';
                $html .= '<div class="sub-section-title"><i class="fas fa-chart-line"></i> Gross Salary</div>';
                $html .= '<div class="detail-card highlight-card-green">';
                $html .= '<div class="detail-field"><span class="field-label">Gross Salary (Monthly)</span><span class="field-value highlight">₹ ' . number_format($grossSalaryMonthly) . '</span></div>';
                $html .= '</div></div>';

                // Deductions
                $hasDeductions = ($pfEmployeeMonthly > 0 || $esiEmployeeMonthly > 0 || $npsEmployeeMonthly > 0 || $ptMonthly > 0 || $lstMonthly > 0 || $tdsMonthly > 0 || $insuranceMonthly > 0);
                if ($hasDeductions) {
                    $html .= '<div class="sub-section">';
                    $html .= '<div class="sub-section-title"><i class="fas fa-minus-circle"></i> Deductions (Monthly)</div>';
                    $html .= '<div class="detail-card">';
                    if ($pfEmployeeMonthly > 0) $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-landmark"></i> PF (Employee)</span><span class="field-value">₹ ' . number_format($pfEmployeeMonthly) . '</span></div>';
                    if ($esiEmployeeMonthly > 0) $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-heartbeat"></i> ESI</span><span class="field-value">₹ ' . number_format($esiEmployeeMonthly) . '</span></div>';
                    if ($npsEmployeeMonthly > 0) $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-chart-line"></i> NPS</span><span class="field-value">₹ ' . number_format($npsEmployeeMonthly) . '</span></div>';
                    if ($ptMonthly > 0) $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-file-invoice"></i> Professional Tax</span><span class="field-value">₹ ' . number_format($ptMonthly) . '</span></div>';
                    if ($lstMonthly > 0) $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-university"></i> LST</span><span class="field-value">₹ ' . number_format($lstMonthly) . '</span></div>';
                    if ($tdsMonthly > 0) $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-receipt"></i> TDS</span><span class="field-value">₹ ' . number_format($tdsMonthly) . '</span></div>';
                    if ($insuranceMonthly > 0) $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-shield-alt"></i> Insurance</span><span class="field-value">₹ ' . number_format($insuranceMonthly) . '</span></div>';
                    $html .= '<div class="detail-field border-top mt-1 pt-1"><span class="field-label fw-bold">Total Deductions</span><span class="field-value fw-bold">₹ ' . number_format($totalDeductionsMonthly) . '</span></div>';
                    $html .= '</div></div>';
                }

                // Employer Contributions
                if ($employerPfMonthly > 0 || $employerEsiMonthly > 0 || $employerNpsMonthly > 0) {
                    $html .= '<div class="sub-section">';
                    $html .= '<div class="sub-section-title"><i class="fas fa-building"></i> Employer Contributions</div>';
                    $html .= '<div class="detail-card employer-card">';
                    if ($employerPfMonthly > 0) $html .= '<div class="detail-field"><span class="field-label">Employer PF</span><span class="field-value">₹ ' . number_format($employerPfMonthly) . '</span></div>';
                    if ($employerEsiMonthly > 0) $html .= '<div class="detail-field"><span class="field-label">Employer ESI</span><span class="field-value">₹ ' . number_format($employerEsiMonthly) . '</span></div>';
                    if ($employerNpsMonthly > 0) $html .= '<div class="detail-field"><span class="field-label">Employer NPS</span><span class="field-value">₹ ' . number_format($employerNpsMonthly) . '</span></div>';
                    $html .= '</div></div>';
                }

                // Net Salary
                $html .= '<div class="sub-section">';
                $html .= '<div class="sub-section-title"><i class="fas fa-wallet"></i> Take Home Salary</div>';
                $html .= '<div class="detail-card highlight-card-blue">';
                $html .= '<div class="detail-field border-0"><span class="field-label fw-bold">Net Salary (Monthly)</span><span class="field-value fw-bold" style="font-size: 1.1rem;">₹ ' . number_format($netSalaryMonthly) . '</span></div>';
                $html .= '<div class="detail-field border-0"><span class="field-label fw-bold">Net Salary (Annual)</span><span class="field-value fw-bold" style="font-size: 1.1rem;">₹ ' . number_format($netSalaryAnnual) . '</span></div>';
                $html .= '</div></div>';
                
                // Annual Summary
                $html .= '<div class="sub-section">';
                $html .= '<div class="sub-section-title"><i class="fas fa-calendar-alt"></i> Annual Summary</div>';
                $html .= '<div class="detail-card">';
                $html .= '<div class="detail-field"><span class="field-label">Gross Salary (Annual)</span><span class="field-value">₹ ' . number_format($grossSalaryAnnual) . '</span></div>';
                if ($employerPfAnnual > 0) $html .= '<div class="detail-field"><span class="field-label">Employer PF (Annual)</span><span class="field-value">₹ ' . number_format($employerPfAnnual) . '</span></div>';
                if ($employerEsiAnnual > 0) $html .= '<div class="detail-field"><span class="field-label">Employer ESI (Annual)</span><span class="field-value">₹ ' . number_format($employerEsiAnnual) . '</span></div>';
                if ($employerNpsAnnual > 0) $html .= '<div class="detail-field"><span class="field-label">Employer NPS (Annual)</span><span class="field-value">₹ ' . number_format($employerNpsAnnual) . '</span></div>';
                $html .= '<div class="detail-field mt-2 pt-2 border-top"><span class="field-label fw-bold">Total Annual CTC</span><span class="field-value fw-bold">₹ ' . number_format($totalCostAnnual) . '</span></div>';
                $html .= '</div></div>';
            } else {
                $html .= '<div class="text-center py-5 text-muted">';
                $html .= '<i class="fas fa-inbox fa-2x mb-3 opacity-25"></i>';
                $html .= '<p class="mb-0">No structure data available</p>';
                $html .= '</div>';
            }
            $html .= '</div>';
            return $html;
        }
    }
@endphp

<title>Salary Structure Logs</title>
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #4361ee, #3a0ca3);
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
    --success-gradient: linear-gradient(135deg, #10b981, #059669);
    --success-color: #10b981;
    --warning-gradient: linear-gradient(135deg, #f59e0b, #d97706);
    --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
    --border-color: #e2e8f0;
    --text-dark: #1e293b;
    --text-muted: #64748b;
    --bg-light: #f8fafc;
    --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
    --transition: all 0.2s ease;
}

.log-container {
    padding: 24px;
    background: #f1f5f9;
    min-height: 100vh;
}

/* Page Header */
.page-header-premium {
    background: var(--primary-gradient);
    border-radius: 24px;
    padding: 24px 32px;
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    box-shadow: 0 10px 25px rgba(67, 97, 238, 0.25);
}

.page-title-premium {
    font-size: 1.8rem;
    font-weight: 800;
    color: white;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 15px;
}

.page-title-premium i {
    background: rgba(255, 255, 255, 0.2);
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 15px;
}

.page-subtitle {
    color: rgba(255, 255, 255, 0.8);
    margin: 8px 0 0 65px;
    font-size: 0.85rem;
}

.btn-back-premium {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    border-radius: 12px;
    padding: 10px 24px;
    font-weight: 600;
    transition: var(--transition);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-back-premium:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
}

/* Filter Card */
.filter-card {
    background: white;
    border-radius: 20px;
    padding: 24px;
    margin-bottom: 24px;
    border: 1px solid var(--border-color);
    box-shadow: var(--card-shadow);
}

.filter-card label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    margin-bottom: 6px;
}

.filter-card .form-control,
.filter-card .form-select {
    border-radius: 12px;
    height: 44px;
    border: 1px solid var(--border-color);
    font-size: 0.9rem;
    transition: var(--transition);
}

.filter-card .form-control:focus,
.filter-card .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.filter-card .input-group-text {
    background: white;
    border-right: none;
    border-radius: 12px 0 0 12px;
    color: var(--text-muted);
}

.btn-filter-premium {
    background: var(--primary-gradient);
    color: white;
    border: none;
    border-radius: 12px;
    height: 44px;
    font-weight: 600;
    transition: var(--transition);
}

.btn-filter-premium:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    color: white;
}

/* Table Card */
.log-table-card {
    background: white;
    border-radius: 24px;
    /*overflow: hidden;*/
    border: 1px solid var(--border-color);
    box-shadow: var(--card-shadow);
}

/*.table thead th {*/
/*    background: var(--bg-light);*/
/*    border-bottom: 2px solid var(--border-color);*/
/*    color: var(--text-muted);*/
/*    text-transform: uppercase;*/
/*    font-size: 0.7rem;*/
/*    font-weight: 700;*/
/*    letter-spacing: 0.5px;*/
/*    padding: 16px 24px;*/
/*}*/

.table tbody td {
    padding: 16px 24px;
    vertical-align: middle;
    color: var(--text-dark);
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.9rem;
}

.table tbody tr:hover {
    background: var(--bg-light);
}

/* Action Badges */
.action-badge {
    padding: 5px 14px;
    border-radius: 40px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
}

.badge-override { background: #fee2e2; color: #b91c1c; }
.badge-edit { background: #ecfdf5; color: #059669; }
.badge-create { background: #eff6ff; color: #2563eb; }

/* Timestamp */
.timestamp-label {
    font-size: 0.6rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    margin-bottom: 2px;
}

.timestamp-date {
    font-weight: 700;
    color: var(--text-dark);
    font-size: 0.85rem;
}

.timestamp-time {
    font-size: 0.7rem;
    color: var(--text-muted);
    margin-top: 2px;
}

/* Avatar */
.avatar-circle-sm {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-light);
}

/* View Button */
.btn-view-details {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-light);
    color: var(--text-muted);
    transition: var(--transition);
    border: none;
}

.btn-view-details:hover {
    background: var(--primary-gradient);
    color: white;
    transform: scale(1.05);
}

/* Modal */
.modal-xl { max-width: 1200px; }

.modal-content-premium {
    border-radius: 28px;
    border: none;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
}

.modal-header-premium {
    background: var(--primary-gradient);
    padding: 24px 32px;
    border-bottom: none;
}

.modal-header-premium .modal-title {
    color: white;
    font-weight: 700;
    font-size: 1.3rem;
}

.modal-header-premium .btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

.modal-body-premium {
    background: var(--bg-light);
    padding: 0;
}

.log-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    /* min-height: 600px; */
}

.state-panel {
    padding: 28px;
    overflow-y: auto;
    max-height: 80vh;
}

.state-panel.previous { 
    background: #f1f5f9;
    border-right: 1px solid var(--border-color);
}

.state-panel.current { 
    background: white;
}

.panel-title {
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 15px;
    border-bottom: 2px solid;
}

.panel-title.previous { color: var(--text-muted); border-bottom-color: #cbd5e1; }
.panel-title.current { color: var(--primary-color); border-bottom-color: var(--primary-color); }

/* Policy Section Cards (inside modal) */
.policy-section-card {
    background: white;
    border-radius: 20px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    border: 1px solid var(--border-color);
    position: relative;
    transition: var(--transition);
}

.policy-section-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.section-header-icon {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-header-icon i {
    font-size: 1.1rem;
    background: #eef2ff;
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
}

.section-divider {
    height: 2px;
    background: linear-gradient(90deg, var(--border-color), transparent);
    margin-bottom: 18px;
}

.sub-section {
    margin-bottom: 20px;
}

.sub-section-title {
    font-size: 0.8rem;
    font-weight: 700;
    color: #475569;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.detail-card {
    background: var(--bg-light);
    border-radius: 12px;
    padding: 12px 16px;
    margin: 10px 0;
}

.detail-card.highlight-card-green {
    background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
    border-left: 4px solid #10b981;
}

.detail-card.highlight-card-blue {
    background: linear-gradient(135deg, #eff6ff 0%, #eef2ff 100%);
    border-left: 4px solid var(--primary-color);
}

.detail-card.employer-card {
    background: linear-gradient(135deg, #fef3c7 0%, #fffbeb 100%);
    border-left: 4px solid #f59e0b;
}

.detail-field {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid var(--border-color);
}

.detail-field:last-child {
    border-bottom: none;
}

.field-label {
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 6px;
}

.field-value {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-dark);
}

.field-value.highlight {
    color: #059669;
    font-size: 1rem;
}

.json-raw {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.7rem;
    padding: 15px;
    background: #1e293b;
    color: #e2e8f0;
    border-radius: 12px;
    white-space: pre-wrap;
    max-height: 400px;
    overflow: auto;
}

.modal-footer-premium {
    background: white;
    border-top: 1px solid var(--border-color);
    padding: 16px 28px;
    border-radius: 0 0 28px 28px;
    display: flex;
    justify-content: flex-end;
}

.btn-close-premium {
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 10px 32px;
    font-weight: 600;
    transition: var(--transition);
}

.btn-close-premium:hover {
    background: var(--primary-gradient);
    color: white;
    border-color: transparent;
}

/* Pagination */
.pagination-wrapper {
    padding: 16px 24px;
    border-top: 1px solid var(--border-color);
}

/* Responsive */
@media (max-width: 768px) {
    .log-container { padding: 16px; }
    .page-header-premium { flex-direction: column; text-align: center; }
    .page-title-premium { font-size: 1.4rem; justify-content: center; }
    .page-subtitle { margin-left: 0; }
    .log-details-grid { grid-template-columns: 1fr; }
    .state-panel.previous { border-right: none; border-bottom: 1px solid var(--border-color); }
    .state-panel { padding: 20px; }
}

        /* ERP Table Styles */
        .erp-table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
        }
        
        .erp-table thead {
            background: var(--primary-gradient);
            border-bottom: 2px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .erp-table th {
            padding: 15px 8px;
            font-weight: 600;
            color: white;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s;
            position: relative;
        }

        .erp-table th h6 {
            color: rgba(255,255,255,0.9);
            font-size: 11px;
            margin: 2px 0 0;
        }

        .erp-table th:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .erp-table th.sortable {
            padding-right: 30px;
        }

        .sort-icons {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sort-icon {
            color: rgba(255,255,255,0.5);
            font-size: 12px;
            line-height: 1;
        }

        .sort-icon.active {
            color: white;
        }

        .erp-table td {
            padding: 15px 8px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 14px;
            vertical-align: middle;
        }

        .erp-table tbody tr {
            transition: background-color 0.2s, transform 0.2s;
        }

        .erp-table tbody tr:hover {
            background-color: #f8fafc;
            /*transform: translateY(-1px);*/
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .erp-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .table-responsive{
            overflow-x: hidden;
        }
</style>

<div class="log-container">
    <!-- Page Header -->
    <div class="page-header-premium">
        <div>
            <h2 class="page-title-premium">
                <i class="fas fa-history"></i> Salary Structure Logs
            </h2>
            <p class="page-subtitle">
                <i class="fas fa-chart-line me-1"></i> Complete audit trail of all salary structure changes
            </p>
        </div>
        <a href="{{ route('institute.payroll.salary.management') }}" class="btn-back-premium">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <form method="GET" action="{{ route('institute.payroll.salary.logs') }}" class="row align-items-end g-3">
            <div class="col-md-5">
                <label><i class="fas fa-search me-1"></i> SEARCH TARGET</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Employee name, ID, or Department..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <label><i class="fas fa-tag me-1"></i> ACTION</label>
                <select name="action" class="form-select">
                    <option value="">All Actions</option>
                    <option value="Override" {{ request('action') == 'Override' ? 'selected' : '' }}>Override</option>
                    <option value="Create" {{ request('action') == 'Create' ? 'selected' : '' }}>Creation</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-filter-premium w-100">
                    <i class="fas fa-filter me-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Log Table -->
    <div class="log-table-card">
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="sticky-main-2 sortable"><i class="fas fa-user me-1"></i> Target</th>
                        <th class="sortable"><i class="fas fa-tag me-1"></i> Action</th>
                        <th class="sortable"><i class="fas fa-calendar me-1"></i> Financial Year</th>
                        <th class="sortable"><i class="fas fa-clock me-1"></i> Last Updated</th>
                        <th class="sortable text-center"><i class="fas fa-eye me-1"></i> Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="sticky-main-2">
                                @if($log->employee)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle-sm me-3">
                                            <i class="fas fa-user-circle text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $log->employee->name ?? 'N/A' }}</div>
                                            <div class="small text-muted">Employee Policy</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle-sm me-3">
                                            <i class="fas fa-building text-info"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $log->department->department ?? 'N/A' }}</div>
                                            <div class="small text-muted">Department Policy</div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="action-badge badge-{{ strtolower($log->action) }}">
                                    {{ $log->action }}
                                </span>
                                <div class="small text-muted mt-1">{{ $log->change_type }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark px-3 py-2" style="border-radius: 8px;">{{ $log->financial_year }}</span>
                            </td>
                            <td>
                                <div class="timestamp-label">Updated</div>
                                <div class="timestamp-date">{{ $log->updated_at->format('d M, Y') }}</div>
                                <div class="timestamp-time">{{ $log->updated_at->format('h:i A') }}</div>
                                <div class="timestamp-label mt-2">Created</div>
                                <div class="small text-muted">{{ $log->created_at->format('d M Y, h:i A') }}</div>
                            </td>
                            <td class="text-center">
                                <button type="button"
                                    class="btn-view-details mx-auto"
                                    data-bs-toggle="modal"
                                    data-bs-target="#logModal-{{ $log->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <!-- Detail Modal -->
                                <div class="modal fade text-left" id="logModal-{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered">
                                        <div class="modal-content modal-content-premium">
                                            <div class="modal-header modal-header-premium">
                                                <div>
                                                    <h4 class="modal-title">
                                                        <i class="fas fa-history me-2"></i> Audit Log Detail
                                                    </h4>
                                                    <div class="d-flex align-items-center mt-2">
                                                        <span class="action-badge badge-{{ strtolower($log->action) }} me-2">{{ $log->action }}</span>
                                                        <span class="text-white-50 small">
                                                            <i class="far fa-clock me-1"></i> 
                                                            Last Updated: {{ $log->updated_at->format('d M Y, h:i A') }} | 
                                                            <i class="fas fa-tag me-1"></i> {{ $log->change_type }}
                                                        </span>
                                                    </div>
                                                    <div class="small text-white-50 mt-1">
                                                        <i class="fas fa-plus-circle me-1"></i> Created: {{ $log->created_at->format('d M Y, h:i A') }}
                                                    </div>
                                                </div>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body modal-body-premium">
                                                <div class="log-details-grid">
                                                    <!-- Previous State -->
                                                    <div class="state-panel previous">
                                                        <div class="panel-title previous">
                                                            <i class="fas fa-arrow-left"></i> PREVIOUS STATE
                                                        </div>
                                                        <div>
                                                            @if($log->previous_data)
                                                                {!! renderStructureData($log->previous_data) !!}
                                                            @else
                                                                <div class="text-center py-5 text-muted">
                                                                    <i class="fas fa-plus-circle fa-3x mb-3 opacity-25"></i>
                                                                    <p class="mb-0">This was an <strong>initial creation</strong></p>
                                                                    <small>No previous state available</small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <!-- Current State -->
                                                    <div class="state-panel current">
                                                        <div class="panel-title current">
                                                            <i class="fas fa-arrow-right"></i> CURRENT STATE
                                                        </div>
                                                        <div>
                                                            @if($log->currentStructure)
                                                                {!! renderStructureData($log->currentStructure->toArray()) !!}
                                                            @else
                                                                <div class="text-center py-5 text-danger">
                                                                    <i class="fas fa-exclamation-triangle fa-3x mb-3 opacity-25"></i>
                                                                    <p class="mb-0"><strong>Structure Not Found</strong></p>
                                                                    <small>Current state has been deleted or cannot be found.</small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer-premium">
                                                <button type="button" class="btn btn-close-premium" data-bs-dismiss="modal">
                                                    <i class="fas fa-check me-2"></i> Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-25"></i>
                                <h5 class="text-muted">No audit logs found</h5>
                                <p class="text-muted small">Changes made to salary structures will appear here</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Floating Horizontal Scrollbar -->
        <div class="table-scroll-top" id="tableScrollTop">
            <div class="table-scroll-inner"></div>
        </div>
        
        @if($logs->hasPages())
            <div class="pagination-wrapper">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function toggleRawJson(id) {
        const rawPanel = document.getElementById(`raw-json-${id}`);
        if (rawPanel) {
            rawPanel.classList.toggle('d-none');
        }
    }
</script>

@endsection