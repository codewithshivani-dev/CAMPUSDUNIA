@extends('instituteAdmin.Payroll.PayrollManagement')
@section('payroll-content')

@php
    /**
     * Helper to safely decode JSON fields
     */
    if (!function_exists('safeDecode')) {
        function safeDecode($value) {
            if (is_null($value)) return [];
            if (is_array($value)) return $value;
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                return (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [];
            }
            return [];
        }
    }
    
    /**
     * Helper to render the log data in a nice format
     */
    if (!function_exists('renderLogData')) {
        function renderLogData($data, $changeType = null) {
            if (!$data) return '';
            
            // Handle new Full Policy Snapshot structure if detected
            if (isset($data['base']) && !isset($data['salary_structure'])) {
                $base = !empty($data['base']) ? (object)$data['base'] : null;
                $allowances = !empty($data['allowances']) ? (object)$data['allowances'] : null;
                $tax = !empty($data['tax']) ? (object)$data['tax'] : null;
                $other = !empty($data['other']) ? (object)$data['other'] : null;
                
                $html = '';
                
                // Helper to check if section is the active change
                $isActive = function($sectionName) use ($changeType) {
                    if (!$changeType || $changeType === 'Full Policy') return true;
                    if ($sectionName === 'Statutory Deductions' && in_array($changeType, ['Base Policy', 'Tax Deductions', 'Statutory Deductions'])) return true;
                    if ($sectionName === 'Allowances' && $changeType === 'Allowances') return true;
                    if ($sectionName === 'Other Deductions' && $changeType === 'Other Deductions') return true;
                    return $changeType === $sectionName;
                };
                
                // 1. STATUTORY DEDUCTIONS (PF, ESI, NPS, PT, LST, TDS combined)
                $activeClass = $isActive('Statutory Deductions') ? 'border-primary shadow-lg' : 'opacity-75';
                $html .= '<div class="policy-section-card ' . $activeClass . '" style="transition: all 0.2s;">';
                if ($isActive('Statutory Deductions')) {
                    $html .= '<div class="section-modified-badge bg-primary"><i class="fas fa-pen"></i> MODIFIED IN THIS UPDATE</div>';
                }
                $html .= '<div class="section-header-icon"><i class="fas fa-shield-alt"></i> Statutory Deductions</div>';
                $html .= '<div class="section-divider"></div>';
                
                if ($base) {
                    // PF Section
                    $pf_enabled = isset($base->enable_pf) && $base->enable_pf;
                    $html .= '<div class="sub-section">';
                    $html .= '<div class="sub-section-title"><i class="fas fa-landmark"></i> Provident Fund (PF)</div>';
                    $html .= '<div class="status-row"><span class="status-label">Status</span><span class="status-badge ' . ($pf_enabled ? 'status-enabled' : 'status-disabled') . '">' . ($pf_enabled ? 'ENABLED' : 'DISABLED') . '</span></div>';
                    if($pf_enabled) {
                        $html .= '<div class="detail-card">';
                        $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-user"></i> Employee Contribution</span><span class="field-value">' . ucfirst($base->pf_employee_type ?? "percentage") . ' - ' . ($base->pf_employee_value ?? "0") . ($base->pf_employee_type === 'percentage' ? '%' : ' INR') . '</span></div>';
                        $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-building"></i> Employer Contribution</span><span class="field-value">' . ucfirst($base->pf_employer_type ?? "percentage") . ' - ' . ($base->pf_employer_value ?? "0") . ($base->pf_employer_type === 'percentage' ? '%' : ' INR') . '</span></div>';
                        $html .= '</div>';
                    } else {
                        $html .= '<div class="disabled-note">Not included in salary calculation</div>';
                    }
                    $html .= '</div>';
                    
                    // ESI Section
                    $esi_enabled = isset($base->enable_esi) && $base->enable_esi;
                    $html .= '<div class="sub-section">';
                    $html .= '<div class="sub-section-title"><i class="fas fa-heartbeat"></i> Employee State Insurance (ESI)</div>';
                    $html .= '<div class="status-row"><span class="status-label">Status</span><span class="status-badge ' . ($esi_enabled ? 'status-enabled' : 'status-disabled') . '">' . ($esi_enabled ? 'ENABLED' : 'DISABLED') . '</span></div>';
                    if($esi_enabled) {
                        $html .= '<div class="detail-card">';
                        $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-user"></i> Employee Contribution</span><span class="field-value">' . ucfirst($base->esi_employee_type ?? "percentage") . ' - ' . ($base->esi_employee_value ?? "0") . ($base->esi_employee_type === 'percentage' ? '%' : ' INR') . '</span></div>';
                        $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-building"></i> Employer Contribution</span><span class="field-value">' . ucfirst($base->esi_employer_type ?? "percentage") . ' - ' . ($base->esi_employer_value ?? "0") . ($base->esi_employer_type === 'percentage' ? '%' : ' INR') . '</span></div>';
                        $html .= '</div>';
                    } else {
                        $html .= '<div class="disabled-note">Not included in salary calculation</div>';
                    }
                    $html .= '</div>';
                    
                    // NPS Section
                    $nps_enabled = isset($base->enable_nps) && $base->enable_nps;
                    $html .= '<div class="sub-section">';
                    $html .= '<div class="sub-section-title"><i class="fas fa-chart-line"></i> National Pension System (NPS)</div>';
                    $html .= '<div class="status-row"><span class="status-label">Status</span><span class="status-badge ' . ($nps_enabled ? 'status-enabled' : 'status-disabled') . '">' . ($nps_enabled ? 'ENABLED' : 'DISABLED') . '</span></div>';
                    if($nps_enabled) {
                        $html .= '<div class="detail-card">';
                        $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-user"></i> Employee Contribution</span><span class="field-value">' . ucfirst($base->nps_employee_type ?? "percentage") . ' - ' . ($base->nps_employee_value ?? "0") . ($base->nps_employee_type === 'percentage' ? '%' : ' INR') . '</span></div>';
                        $html .= '<div class="detail-field"><span class="field-label"><i class="fas fa-building"></i> Employer Contribution</span><span class="field-value">' . ucfirst($base->nps_employer_type ?? "percentage") . ' - ' . ($base->nps_employer_value ?? "0") . ($base->nps_employer_type === 'percentage' ? '%' : ' INR') . '</span></div>';
                        $html .= '</div>';
                    } else {
                        $html .= '<div class="disabled-note">Not included in salary calculation</div>';
                    }
                    $html .= '</div>';
                }
                
                // Tax Deductions (PT, LST, TDS)
                if ($tax) {
                    $html .= '<div class="sub-section mt-3">';
                    $html .= '<div class="sub-section-title"><i class="fas fa-file-invoice-dollar"></i> Tax Deductions</div>';
                    
                    // PT
                    $pt_selected = isset($tax->pt_selected) && $tax->pt_selected;
                    $html .= '<div class="status-row"><span class="status-label">Professional Tax (PT)</span><span class="status-badge ' . ($pt_selected ? 'status-enabled' : 'status-disabled') . '">' . ($pt_selected ? 'ENABLED' : 'DISABLED') . '</span></div>';
                    if ($pt_selected && isset($tax->pt_type)) {
                        $html .= '<div class="detail-field ml-3"><span class="field-label">Calculation Type</span><span class="field-value">' . ucfirst($tax->pt_type) . '</span></div>';
                        $ptSlabs = safeDecode($tax->pt_slabs ?? []);
                        if ($tax->pt_type === 'slabs' && count($ptSlabs) > 0) {
                            $html .= '<div class="slabs-list"><span class="field-label">Slabs</span><div class="slabs-container">';
                            foreach ($ptSlabs as $slab) {
                                $slab = (object)$slab;
                                $html .= '<div class="slab-chip">₹' . number_format($slab->from) . ' - ₹' . number_format($slab->to) . ' → ₹' . number_format($slab->amount) . '</div>';
                            }
                            $html .= '</div></div>';
                        }
                    }
                    
                    // LST
                    $lst_selected = isset($tax->lst_selected) && $tax->lst_selected;
                    $html .= '<div class="status-row mt-2"><span class="status-label">Labour Welfare Fund (LST)</span><span class="status-badge ' . ($lst_selected ? 'status-enabled' : 'status-disabled') . '">' . ($lst_selected ? 'ENABLED' : 'DISABLED') . '</span></div>';
                    if ($lst_selected && isset($tax->lst_type)) {
                        $html .= '<div class="detail-field ml-3"><span class="field-label">Calculation Type</span><span class="field-value">' . ucfirst($tax->lst_type) . '</span></div>';
                        $lstSlabs = safeDecode($tax->lst_slabs ?? []);
                        if ($tax->lst_type === 'slabs' && count($lstSlabs) > 0) {
                            $html .= '<div class="slabs-list"><span class="field-label">Slabs</span><div class="slabs-container">';
                            foreach ($lstSlabs as $slab) {
                                $slab = (object)$slab;
                                $html .= '<div class="slab-chip">₹' . number_format($slab->from) . ' - ₹' . number_format($slab->to) . ' → ' . $slab->rate . '%</div>';
                            }
                            $html .= '</div></div>';
                        }
                    }
                    
                    // TDS
                    $tds_selected = isset($tax->tds_selected) && $tax->tds_selected;
                    $html .= '<div class="status-row mt-2"><span class="status-label">TDS (Tax Deducted at Source)</span><span class="status-badge ' . ($tds_selected ? 'status-enabled' : 'status-disabled') . '">' . ($tds_selected ? 'ENABLED' : 'DISABLED') . '</span></div>';
                    $tdsSlabs = safeDecode($tax->tds_slabs ?? []);
                    if ($tds_selected && count($tdsSlabs) > 0) {
                        $html .= '<div class="slabs-list"><span class="field-label">Income Tax Slabs</span><div class="slabs-container">';
                        foreach ($tdsSlabs as $slab) {
                            $slab = (object)$slab;
                            $toText = $slab->to >= 10000000 ? 'Above' : '₹' . number_format($slab->to);
                            $html .= '<div class="slab-chip">₹' . number_format($slab->from) . ' - ' . $toText . ' → ' . $slab->rate . '%' . (isset($slab->additionalTax) && $slab->additionalTax ? ' + ₹' . number_format($slab->additionalTax) : '') . '</div>';
                        }
                        $html .= '</div></div>';
                    }
                    $html .= '</div>';
                }
                
                $html .= '</div>';
                
                // 2. ALLOWANCES
                $activeClass = $isActive('Allowances') ? 'border-success shadow-lg' : 'opacity-75';
                $html .= '<div class="policy-section-card ' . $activeClass . '" style="transition: all 0.2s;">';
                if ($isActive('Allowances')) {
                    $html .= '<div class="section-modified-badge bg-success"><i class="fas fa-pen"></i> MODIFIED IN THIS UPDATE</div>';
                }
                $html .= '<div class="section-header-icon"><i class="fas fa-plus-circle" style="color: #10b981;"></i> Allowances</div>';
                $html .= '<div class="section-divider"></div>';
                
                if ($allowances) {
                    $al_items = [
                        'hra' => ['House Rent Allowance (HRA)', 'fas fa-home'],
                        'conveyance' => ['Conveyance Allowance', 'fas fa-car'],
                        'medical' => ['Medical Allowance', 'fas fa-first-aid'],
                        'special' => ['Special Allowance', 'fas fa-star'],
                        'lta' => ['Leave Travel Allowance (LTA)', 'fas fa-plane'],
                        'education' => ['Education Allowance', 'fas fa-graduation-cap']
                    ];

                    foreach ($al_items as $key => $item) {
                        $sel_key = $key . '_selected';
                        $type_key = $key . '_type';
                        $is_sel = isset($allowances->$sel_key) && $allowances->$sel_key;
                        $html .= '<div class="allowance-row">';
                        $html .= '<div class="allowance-name"><i class="' . $item[1] . ' mr-2"></i> ' . $item[0] . '</div>';
                        $html .= '<div class="allowance-status"><span class="status-badge ' . ($is_sel ? 'status-enabled' : 'status-disabled') . '">' . ($is_sel ? 'INCLUDED' : 'EXCLUDED') . '</span></div>';
                        $html .= '</div>';
                        if ($is_sel && isset($allowances->$type_key)) {
                            $html .= '<div class="detail-field ml-4 mb-2"><span class="field-label">Calculation Type</span><span class="field-value">' . ucfirst($allowances->$type_key) . '</span></div>';
                        }
                    }
                    
                    $customAllowances = safeDecode($allowances->custom_allowances ?? []);
                    if (count($customAllowances) > 0) {
                        $html .= '<div class="custom-section mt-3"><i class="fas fa-plus-circle"></i> Custom Allowances</div>';
                        foreach ($customAllowances as $ca) {
                            $ca = (object)$ca;
                            $html .= '<div class="allowance-row">';
                            $html .= '<div class="allowance-name"><i class="fas fa-money-check-alt mr-2"></i> ' . ($ca->name ?? 'Custom') . '</div>';
                            $html .= '<div class="allowance-status"><span class="status-badge status-enabled">INCLUDED</span></div>';
                            $html .= '</div>';
                            if (isset($ca->type)) {
                                $html .= '<div class="detail-field ml-4 mb-2"><span class="field-label">Calculation Type</span><span class="field-value">' . ucfirst($ca->type) . '</span></div>';
                            }
                        }
                    }
                } else {
                    $html .= '<div class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 opacity-25"></i><br>No allowances configured</div>';
                }
                $html .= '</div>';
                
                // 3. OTHER DEDUCTIONS
                $activeClass = $isActive('Other Deductions') ? 'border-warning shadow-lg' : 'opacity-75';
                $html .= '<div class="policy-section-card ' . $activeClass . '" style="transition: all 0.2s;">';
                if ($isActive('Other Deductions')) {
                    $html .= '<div class="section-modified-badge bg-warning text-dark"><i class="fas fa-pen"></i> MODIFIED IN THIS UPDATE</div>';
                }
                $html .= '<div class="section-header-icon"><i class="fas fa-minus-circle" style="color: #f59e0b;"></i> Other Deductions</div>';
                $html .= '<div class="section-divider"></div>';
                
                if ($other) {
                    $oth_items = [
                        'insurance' => ['Insurance Premium', 'fas fa-shield-alt'],
                        'loan' => ['Loan Recovery', 'fas fa-hand-holding-usd'],
                        'advance' => ['Salary Advance', 'fas fa-money-bill-wave']
                    ];

                    foreach ($oth_items as $key => $item) {
                        $sel_key = $key . '_selected';
                        $type_key = $key . '_type';
                        $is_sel = isset($other->$sel_key) && $other->$sel_key;
                        $html .= '<div class="allowance-row">';
                        $html .= '<div class="allowance-name"><i class="' . $item[1] . ' mr-2"></i> ' . $item[0] . '</div>';
                        $html .= '<div class="allowance-status"><span class="status-badge ' . ($is_sel ? 'status-enabled' : 'status-disabled') . '">' . ($is_sel ? 'INCLUDED' : 'EXCLUDED') . '</span></div>';
                        $html .= '</div>';
                        if ($is_sel && isset($other->$type_key)) {
                            $html .= '<div class="detail-field ml-4 mb-2"><span class="field-label">Calculation Type</span><span class="field-value">' . ucfirst($other->$type_key) . '</span></div>';
                        }
                    }
                    
                    $customDeductions = safeDecode($other->custom_deductions ?? []);
                    if (count($customDeductions) > 0) {
                        $html .= '<div class="custom-section mt-3"><i class="fas fa-plus-circle"></i> Custom Deductions</div>';
                        foreach ($customDeductions as $cd) {
                            $cd = (object)$cd;
                            $html .= '<div class="allowance-row">';
                            $html .= '<div class="allowance-name"><i class="fas fa-file-invoice-dollar mr-2"></i> ' . ($cd->name ?? 'Custom') . '</div>';
                            $html .= '<div class="allowance-status"><span class="status-badge status-enabled">INCLUDED</span></div>';
                            $html .= '</div>';
                            if (isset($cd->type)) {
                                $html .= '<div class="detail-field ml-4 mb-2"><span class="field-label">Calculation Type</span><span class="field-value">' . ucfirst($cd->type) . '</span></div>';
                            }
                        }
                    }
                } else {
                    $html .= '<div class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 opacity-25"></i><br>No other deductions configured</div>';
                }
                $html .= '</div>';
                
                return $html;
            }
            
            // Handle Salary Structure Snapshot
            if (isset($data['salary_structure']) || isset($data['allowances']) || isset($data['deductions']) || isset($data['preview'])) {
                $salary = !empty($data['salary_structure']) ? (object)$data['salary_structure'] : null;
                $allowances = !empty($data['allowances']) ? (object)$data['allowances'] : null;
                $deductions = !empty($data['deductions']) ? (object)$data['deductions'] : null;
                $preview = !empty($data['preview']) ? (object)$data['preview'] : null;

                $html = '<div class="policy-section-card border-primary shadow-lg">';
                $html .= '<div class="section-header-icon"><i class="fas fa-receipt"></i> Salary Structure Snapshot</div>';
                $html .= '<div class="section-divider"></div>';

                if ($salary) {
                    $html .= '<div class="sub-section">';
                    $html .= '<div class="sub-section-title"><i class="fas fa-chart-simple"></i> Structure Details</div>';
                    $html .= '<div class="detail-card">';
                    $html .= '<div class="detail-field"><span class="field-label">Basic Salary (Monthly)</span><span class="field-value">₹ ' . number_format($salary->basic_salary_monthly ?? 0) . '</span></div>';
                    $html .= '<div class="detail-field"><span class="field-label">Fixed CTC (Annual)</span><span class="field-value">₹ ' . number_format($salary->fixed_ctc_annual ?? 0) . '</span></div>';
                    $html .= '<div class="detail-field"><span class="field-label">Variable CTC (Annual)</span><span class="field-value">₹ ' . number_format($salary->variable_ctc_annual ?? 0) . '</span></div>';
                    $html .= '<div class="detail-field"><span class="field-label">Total CTC (Annual)</span><span class="field-value">₹ ' . number_format($salary->total_ctc_annual ?? 0) . '</span></div>';
                    $html .= '</div></div>';
                }

                if ($allowances) {
                    $html .= '<div class="sub-section">';
                    $html .= '<div class="sub-section-title"><i class="fas fa-plus-circle" style="color: #10b981;"></i> Allowances Applied</div>';
                    $html .= '<div class="detail-card">';
                    foreach (['hra', 'conveyance', 'medical', 'special', 'lta', 'education'] as $key) {
                        $val_key = $key . '_value';
                        $sel_key = $key . '_selected';
                        if (!empty($allowances->$sel_key)) {
                            $html .= '<div class="detail-field"><span class="field-label">' . strtoupper($key) . '</span><span class="field-value">₹ ' . number_format($allowances->$val_key ?? 0) . '</span></div>';
                        }
                    }
                    $html .= '</div></div>';
                }

                if ($deductions) {
                    $html .= '<div class="sub-section">';
                    $html .= '<div class="sub-section-title"><i class="fas fa-minus-circle" style="color: #f59e0b;"></i> Deductions Applied</div>';
                    $html .= '<div class="detail-card">';
                    $deduction_fields = [
                        'pf_employee_monthly' => 'PF (Employee)',
                        'esi_employee_monthly' => 'ESI (Employee)',
                        'nps_employee_monthly' => 'NPS (Employee)',
                        'pt_value_monthly' => 'Professional Tax',
                        'lst_value_monthly' => 'LST',
                        'tds_value_monthly' => 'TDS',
                        'insurance_value_monthly' => 'Insurance Premium',
                        'loan_value_monthly' => 'Loan Recovery',
                        'advance_value_monthly' => 'Salary Advance'
                    ];
                    foreach ($deduction_fields as $field => $label) {
                        if (!empty($deductions->$field)) {
                            $html .= '<div class="detail-field"><span class="field-label">' . $label . '</span><span class="field-value">₹ ' . number_format($deductions->$field) . '</span></div>';
                        }
                    }
                    $html .= '</div></div>';
                }

                if ($preview) {
                    $html .= '<div class="sub-section">';
                    $html .= '<div class="sub-section-title"><i class="fas fa-calculator"></i> Calculated Summary</div>';
                    $html .= '<div class="detail-card highlight-card">';
                    $html .= '<div class="detail-field"><span class="field-label">Gross Salary (Monthly)</span><span class="field-value highlight">₹ ' . number_format($preview->gross_salary_monthly ?? 0) . '</span></div>';
                    $html .= '<div class="detail-field"><span class="field-label">Total Deductions (Monthly)</span><span class="field-value highlight-warning">₹ ' . number_format($preview->total_deductions_monthly ?? 0) . '</span></div>';
                    $html .= '<div class="detail-field"><span class="field-label">Net Salary (Monthly)</span><span class="field-value highlight-success">₹ ' . number_format($preview->net_salary_monthly ?? 0) . '</span></div>';
                    $html .= '<div class="detail-field"><span class="field-label">Employer Cost (Monthly)</span><span class="field-value">₹ ' . number_format($preview->total_cost_monthly ?? 0) . '</span></div>';
                    $html .= '</div></div>';
                }

                $html .= '</div>';
                return $html;
            }

            // Fallback for legacy logs or single section logs
            $html = '<div class="policy-section-card">';
            $html .= '<div class="section-header-icon"><i class="fas fa-info-circle"></i> Raw Data</div>';
            $html .= '<div class="section-divider"></div>';
            
            if (is_array($data)) {
                foreach ($data as $key => $val) {
                    if (is_array($val)) {
                        $html .= '<div class="sub-section-title mt-2">' . str_replace('_', ' ', ucfirst($key)) . '</div>';
                        foreach ($val as $subkey => $subval) {
                            $displayVal = is_scalar($subval) ? $subval : json_encode($subval);
                            $html .= '<div class="detail-field"><span class="field-label">' . str_replace('_', ' ', ucfirst($subkey)) . '</span><span class="field-value">' . $displayVal . '</span></div>';
                        }
                        continue;
                    }
                    $html .= '<div class="detail-field">';
                    $html .= '<span class="field-label">' . str_replace('_', ' ', ucfirst($key)) . '</span>';
                    if (in_array($val, ['0', '1', 0, 1]) && strpos($key, 'selected') !== false) {
                        $html .= '<span class="status-badge ' . ($val ? 'status-enabled' : 'status-disabled') . '">' . ($val ? 'ENABLED' : 'DISABLED') . '</span>';
                    } else {
                        $html .= '<span class="field-value">' . $val . '</span>';
                    }
                    $html .= '</div>';
                }
            }
            $html .= '</div>';
            return $html;
        }
    }
@endphp

<title>Payroll Activity Logs</title>
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
    .page-header-modern {
        background: var(--primary-gradient);
        border-radius: 24px;
        padding: 24px 32px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.25);
    }

    .page-title-modern {
        font-size: 1.8rem;
        font-weight: 800;
        color: white;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-title-modern i {
        background: rgba(255, 255, 255, 0.2);
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
    }

    .page-header-modern .btn-outline-secondary {
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        border-radius: 12px;
        padding: 8px 20px;
        transition: var(--transition);
    }

    .page-header-modern .btn-outline-secondary:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
    }

    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 30px;
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

    .filter-card .btn-primary {
        background: var(--primary-gradient);
        border: none;
        border-radius: 12px;
        height: 44px;
        font-weight: 600;
    }

    .filter-card .btn-primary:hover {
        transform: translateY(-1px);
        filter: brightness(1.05);
    }

    /* Log Table Card */
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
    /*    padding: 16px 20px;*/
    /*}*/

    .table tbody td {
        padding: 16px 20px;
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
        padding: 6px 14px;
        border-radius: 40px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
    }

    .badge-override { background: #fee2e2; color: #b91c1c; }
    .badge-edit { background: #ecfdf5; color: #059669; }
    .badge-creation { background: #eff6ff; color: #2563eb; }

    /* Timestamp Styles */
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

    /* Avatar Circle */
    .avatar-circle-sm {
        width: 36px;
        height: 36px;
        background: var(--bg-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* View Details Button */
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

    /* Modal Styles */
    .modal-xl { max-width: 1300px; }

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
        min-height: 600px;
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

    .section-modified-badge {
        position: absolute;
        top: -10px;
        right: 20px;
        padding: 4px 14px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
        color: white;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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

    .status-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px dashed var(--border-color);
    }

    .status-label {
        font-size: 0.85rem;
        font-weight: 500;
        color: #475569;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-enabled {
        background: #d1fae5;
        color: #059669;
    }

    .status-disabled {
        background: #fee2e2;
        color: #dc2626;
    }

    .detail-card {
        background: var(--bg-light);
        border-radius: 12px;
        padding: 12px 16px;
        margin: 10px 0;
    }

    .detail-card.highlight-card {
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

    .field-value.highlight-warning {
        color: #dc2626;
        font-size: 1rem;
    }

    .field-value.highlight-success {
        color: #10b981;
        font-size: 1.1rem;
    }

    .disabled-note {
        font-size: 0.75rem;
        color: #94a3b8;
        padding: 8px 0 8px 15px;
        font-style: italic;
    }

    .allowance-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .allowance-name {
        font-size: 0.85rem;
        font-weight: 500;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .custom-section {
        font-size: 0.75rem;
        font-weight: 700;
        color: #8b5cf6;
        margin: 15px 0 10px;
        padding-top: 10px;
        border-top: 1px dashed var(--border-color);
    }

    .slabs-list {
        margin-top: 10px;
        padding-left: 15px;
    }

    .slabs-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }

    .slab-chip {
        background: #e2e8f0;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
        color: #334155;
    }

    .modal-footer-premium {
        background: white;
        border-top: 1px solid var(--border-color);
        padding: 16px 28px;
        border-radius: 0 0 28px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-footer-premium .btn-outline-secondary {
        border-radius: 10px;
    }

    .modal-footer-premium .btn-primary {
        background: var(--primary-gradient);
        border: none;
        border-radius: 12px;
        padding: 10px 32px;
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

    /* Pagination */
    .pagination-wrapper {
        padding: 16px 20px;
        border-top: 1px solid var(--border-color);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .log-container {
            padding: 16px;
        }

        .page-header-modern {
            flex-direction: column;
            text-align: center;
            gap: 16px;
        }

        .page-title-modern {
            font-size: 1.4rem;
            justify-content: center;
        }

        .log-details-grid {
            grid-template-columns: 1fr;
        }

        .state-panel.previous {
            border-right: none;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-header-premium {
            padding: 16px 20px;
        }

        .state-panel {
            padding: 20px;
        }
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
    <div class="page-header-modern">
        <div>
            <h2 class="page-title-modern">
                <i class="fas fa-history"></i> Payroll Activity Logs
            </h2>
            <p class="text-white-50 mt-2 mb-0" style="color: rgba(255,255,255,0.8);">
                <i class="fas fa-chart-line me-1"></i> Complete audit trail of all payroll policy and salary structure changes
            </p>
        </div>
        <a href="{{ route('institute.payroll.policy.management') }}" class="btn btn-outline-secondary px-4">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <div class="filter-card">
        <form method="GET" action="{{ route('institute.payroll.policy.logs') }}" class="row align-items-end g-3">
            <div class="col-md-4">
                <label><i class="fas fa-search me-1"></i> SEARCH TARGET</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" style="border-radius: 0 12px 12px 0;" placeholder="Employee or Dept name..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label><i class="fas fa-tag me-1"></i> ACTION</label>
                <select name="action" class="form-select">
                    <option value="">All Actions</option>
                    <option value="Override" {{ request('action') == 'Override' ? 'selected' : '' }}>Override</option>
                    <option value="Edit" {{ request('action') == 'Edit' ? 'selected' : '' }}>Edit</option>
                    <option value="Creation" {{ request('action') == 'Creation' ? 'selected' : '' }}>Creation</option>
                </select>
            </div>
            <div class="col-md-3">
                <label><i class="fas fa-layer-group me-1"></i> CHANGE TYPE</label>
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="Base Policy" {{ request('type') == 'Base Policy' ? 'selected' : '' }}>Base Policy</option>
                    <option value="Allowances" {{ request('type') == 'Allowances' ? 'selected' : '' }}>Allowances</option>
                    <option value="Tax Deductions" {{ request('type') == 'Tax Deductions' ? 'selected' : '' }}>Tax Deductions</option>
                    <option value="Other Deductions" {{ request('type') == 'Other Deductions' ? 'selected' : '' }}>Other Deductions</option>
                    <option value="Statutory Deductions" {{ request('type') == 'Statutory Deductions' ? 'selected' : '' }}>Statutory Deductions</option>
                    <option value="Salary Structure" {{ request('type') == 'Salary Structure' ? 'selected' : '' }}>Salary Structure</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter me-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <div class="log-table-card">
        <div class="table-responsive custom-table-wrapper" id="tableWrapper">
            <table class="erp-table table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="sticky-main-2 sortable"><i class="fas fa-user me-1"></i> Target</th>
                        <th class="sortable"><i class="fas fa-tag me-1"></i> Action</th>
                        <th class="sortable"><i class="fas fa-calendar me-1"></i> Financial Year</th>
                        <th class="sortable"><i class="fas fa-clock me-1"></i> Last Updated</th>
                        <th class="sortable"><i class="fas fa-user-edit me-1"></i> Changed by</th>
                        <th class="sortable text-center"><i class="fas fa-eye me-1"></i> Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="sticky-main-2">
                                @if($log->payroll_type == 'employee')
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
                                <span class="badge bg-light text-dark px-3 py-2">{{ $log->financial_year }}</span>
                            </td>
                            <td>
                                <div class="timestamp-label">Updated</div>
                                <div class="timestamp-date">{{ $log->updated_at->format('d M, Y') }}</div>
                                <div class="timestamp-time">{{ $log->updated_at->format('h:i A') }}</div>
                                <div class="timestamp-label mt-2">Created</div>
                                <div class="small text-muted">{{ $log->created_at->format('d M Y, h:i A') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-primary">{{ $log->changed_by }}</div>
                            </td>
                            <td class="text-center">
                                <button type="button"
                                    class="btn-view-details mx-auto"
                                    data-bs-toggle="modal"
                                    data-bs-target="#logModal-{{ $log->id }}"
                                    onclick="loadFreshLogData({{ $log->id }}, '{{ $log->payroll_policy_id }}', '{{ $log->change_type }}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <!-- Premium Detail Modal -->
                                <div class="modal fade" id="logModal-{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered">
                                        <div class="modal-content modal-content-premium">
                                            <div class="modal-header modal-header-premium">
                                                <div>
                                                    <h4 class="modal-title fw-bold">
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
                                                            <i class="fas fa-arrow-left"></i> PREVIOUS STATE (Before Change)
                                                        </div>
                                                        <div id="prev-content-{{ $log->id }}">
                                                            @if($log->previous_data)
                                                                {!! renderLogData($log->previous_data, $log->change_type) !!}
                                                            @else
                                                                <div class="text-center py-5 text-muted">
                                                                    <i class="fas fa-plus-circle fa-3x mb-3 opacity-25"></i>
                                                                    <p class="mb-0">This was an <strong>initial creation</strong></p>
                                                                    <small>No previous state available</small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <!-- New State -->
                                                    <div class="state-panel current">
                                                        <div class="panel-title current">
                                                            <i class="fas fa-arrow-right"></i> CURRENT STATE (After Change)
                                                        </div>
                                                        <div id="curr-content-{{ $log->id }}">
                                                            <div class="text-center py-5 text-muted">
                                                                <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                                                                <p>Loading latest policy data...</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer-premium d-flex justify-content-between align-items-center">
                                                <button class="btn btn-sm btn-outline-secondary d-none" onclick="toggleRawJson('{{ $log->id }}')">
                                                    <i class="fas fa-code me-1"></i> View Raw JSON
                                                </button>
                                                <button type="button" class="btn btn-primary px-5" data-bs-dismiss="modal">
                                                    <i class="fas fa-check me-2"></i> Close
                                                </button>
                                            </div>
                                            <div id="raw-json-{{ $log->id }}" class="p-4 bg-dark d-none">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="text-white-50 small mb-2"><i class="fas fa-history me-1"></i> PREVIOUS STATE (JSON)</div>
                                                        <pre class="json-raw">{{ json_encode($log->previous_data, JSON_PRETTY_PRINT) }}</pre>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-white-50 small mb-2"><i class="fas fa-save me-1"></i> CURRENT STATE (JSON)</div>
                                                        <pre class="json-raw" id="raw-current-json-{{ $log->id }}">{{ json_encode($log->current_data, JSON_PRETTY_PRINT) }}</pre>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-25"></i>
                                <h5 class="text-muted">No audit logs found</h5>
                                <p class="text-muted small">Changes made to payroll policies will appear here</p>
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
    
    async function loadFreshLogData(logId, policyId, changeType) {
        const container = document.getElementById(`curr-content-${logId}`);
        const rawContainer = document.getElementById(`raw-current-json-${logId}`);

        if (!container) return;
        
        container.innerHTML = `
            <div class="text-center py-5 text-muted">
                <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                <p>Loading latest policy data...</p>
            </div>
        `;

        try {
            const response = await fetch(`/get-full-policy-details/${policyId}`);
            const result = await response.json();
            
            if (!result.success || !result.data) {
                container.innerHTML = `<div class="text-center py-5 text-danger"><i class="fas fa-exclamation-triangle fa-2x mb-3"></i><br>Failed to load current policy data</div>`;
                return;
            }
            
            if (rawContainer) {
                rawContainer.textContent = JSON.stringify(result.data, null, 2);
            }
            
            container.innerHTML = renderCurrentPolicyHTML(result.data, changeType);
            
        } catch (error) {
            console.error('Error loading policy data:', error);
            container.innerHTML = `<div class="text-center py-5 text-danger"><i class="fas fa-exclamation-triangle fa-2x mb-3"></i><br>Error loading data: ${error.message}</div>`;
        }
    }
    
    function renderCurrentPolicyHTML(data, changeType) {
        if (!data) return '<div class="text-center text-muted py-5">No data available</div>';
        
        let html = '';
        
        const isActive = (sectionName) => {
            if (!changeType || changeType === 'Full Policy') return true;
            if (sectionName === 'Statutory Deductions' && ['Base Policy', 'Tax Deductions', 'Statutory Deductions'].includes(changeType)) return true;
            return changeType === sectionName;
        };
        
        // STATUTORY DEDUCTIONS SECTION
        const statActive = isActive('Statutory Deductions');
        html += `<div class="policy-section-card ${statActive ? 'border-primary shadow-lg' : 'opacity-75'}" style="transition: all 0.2s;">`;
        if (statActive) html += '<div class="section-modified-badge bg-primary"><i class="fas fa-pen"></i> MODIFIED IN THIS UPDATE</div>';
        html += '<div class="section-header-icon"><i class="fas fa-shield-alt"></i> Statutory Deductions</div><div class="section-divider"></div>';
        
        // PF Section
        const pfEnabled = data.policy?.enable_pf ?? false;
        html += '<div class="sub-section"><div class="sub-section-title"><i class="fas fa-landmark"></i> Provident Fund (PF)</div>';
        html += `<div class="status-row"><span class="status-label">Status</span><span class="status-badge ${pfEnabled ? 'status-enabled' : 'status-disabled'}">${pfEnabled ? 'ENABLED' : 'DISABLED'}</span></div>`;
        if (pfEnabled) {
            html += '<div class="detail-card">';
            if (data.policy?.pf_employee_enabled) {
                html += `<div class="detail-field"><span class="field-label"><i class="fas fa-user"></i> Employee Contribution</span><span class="field-value">${ucfirst(data.policy.pf_employee_type || 'percentage')} - ${data.policy.pf_employee_value || 0}${data.policy.pf_employee_type === 'percentage' ? '%' : ' INR'}</span></div>`;
            } else {
                html += `<div class="detail-field"><span class="field-label"><i class="fas fa-user"></i> Employee Contribution</span><span class="field-value text-muted">Not enabled</span></div>`;
            }
            if (data.policy?.pf_employer_enabled) {
                html += `<div class="detail-field"><span class="field-label"><i class="fas fa-building"></i> Employer Contribution</span><span class="field-value">${ucfirst(data.policy.pf_employer_type || 'percentage')} - ${data.policy.pf_employer_value || 0}${data.policy.pf_employer_type === 'percentage' ? '%' : ' INR'}</span></div>`;
            } else {
                html += `<div class="detail-field"><span class="field-label"><i class="fas fa-building"></i> Employer Contribution</span><span class="field-value text-muted">Not enabled</span></div>`;
            }
            html += '</div>';
        } else {
            html += '<div class="disabled-note">Not included in salary calculation</div>';
        }
        html += '</div>';
        
        // ESI Section
        const esiEnabled = data.policy?.enable_esi ?? false;
        html += '<div class="sub-section"><div class="sub-section-title"><i class="fas fa-heartbeat"></i> Employee State Insurance (ESI)</div>';
        html += `<div class="status-row"><span class="status-label">Status</span><span class="status-badge ${esiEnabled ? 'status-enabled' : 'status-disabled'}">${esiEnabled ? 'ENABLED' : 'DISABLED'}</span></div>`;
        if (esiEnabled) {
            html += '<div class="detail-card">';
            if (data.policy?.esi_employee_enabled) {
                html += `<div class="detail-field"><span class="field-label"><i class="fas fa-user"></i> Employee Contribution</span><span class="field-value">${ucfirst(data.policy.esi_employee_type || 'percentage')} - ${data.policy.esi_employee_value || 0}${data.policy.esi_employee_type === 'percentage' ? '%' : ' INR'}</span></div>`;
            } else {
                html += `<div class="detail-field"><span class="field-label"><i class="fas fa-user"></i> Employee Contribution</span><span class="field-value text-muted">Not enabled</span></div>`;
            }
            if (data.policy?.esi_employer_enabled) {
                html += `<div class="detail-field"><span class="field-label"><i class="fas fa-building"></i> Employer Contribution</span><span class="field-value">${ucfirst(data.policy.esi_employer_type || 'percentage')} - ${data.policy.esi_employer_value || 0}${data.policy.esi_employer_type === 'percentage' ? '%' : ' INR'}</span></div>`;
            } else {
                html += `<div class="detail-field"><span class="field-label"><i class="fas fa-building"></i> Employer Contribution</span><span class="field-value text-muted">Not enabled</span></div>`;
            }
            html += '</div>';
        } else {
            html += '<div class="disabled-note">Not included in salary calculation</div>';
        }
        html += '</div>';
        
        // NPS Section
        const npsEnabled = data.policy?.enable_nps ?? false;
        html += '<div class="sub-section"><div class="sub-section-title"><i class="fas fa-chart-line"></i> National Pension System (NPS)</div>';
        html += `<div class="status-row"><span class="status-label">Status</span><span class="status-badge ${npsEnabled ? 'status-enabled' : 'status-disabled'}">${npsEnabled ? 'ENABLED' : 'DISABLED'}</span></div>`;
        if (npsEnabled) {
            html += '<div class="detail-card">';
            if (data.policy?.nps_employee_enabled) {
                html += `<div class="detail-field"><span class="field-label"><i class="fas fa-user"></i> Employee Contribution</span><span class="field-value">${ucfirst(data.policy.nps_employee_type || 'percentage')} - ${data.policy.nps_employee_value || 0}${data.policy.nps_employee_type === 'percentage' ? '%' : ' INR'}</span></div>`;
            } else {
                html += `<div class="detail-field"><span class="field-label"><i class="fas fa-user"></i> Employee Contribution</span><span class="field-value text-muted">Not enabled</span></div>`;
            }
            if (data.policy?.nps_employer_enabled) {
                html += `<div class="detail-field"><span class="field-label"><i class="fas fa-building"></i> Employer Contribution</span><span class="field-value">${ucfirst(data.policy.nps_employer_type || 'percentage')} - ${data.policy.nps_employer_value || 0}${data.policy.nps_employer_type === 'percentage' ? '%' : ' INR'}</span></div>`;
            } else {
                html += `<div class="detail-field"><span class="field-label"><i class="fas fa-building"></i> Employer Contribution</span><span class="field-value text-muted">Not enabled</span></div>`;
            }
            html += '</div>';
        } else {
            html += '<div class="disabled-note">Not included in salary calculation</div>';
        }
        html += '</div>';
        
        // Tax Deductions
        if (data.tax_deductions) {
            html += '<div class="sub-section mt-3"><div class="sub-section-title"><i class="fas fa-file-invoice-dollar"></i> Tax Deductions</div>';
            
            const ptSelected = data.tax_deductions.pt_selected ?? false;
            html += `<div class="status-row"><span class="status-label">Professional Tax (PT)</span><span class="status-badge ${ptSelected ? 'status-enabled' : 'status-disabled'}">${ptSelected ? 'ENABLED' : 'DISABLED'}</span></div>`;
            
            const lstSelected = data.tax_deductions.lst_selected ?? false;
            html += `<div class="status-row mt-2"><span class="status-label">Labour Welfare Fund (LST)</span><span class="status-badge ${lstSelected ? 'status-enabled' : 'status-disabled'}">${lstSelected ? 'ENABLED' : 'DISABLED'}</span></div>`;
            
            const tdsSelected = data.tax_deductions.tds_selected ?? false;
            html += `<div class="status-row mt-2"><span class="status-label">TDS (Tax Deducted at Source)</span><span class="status-badge ${tdsSelected ? 'status-enabled' : 'status-disabled'}">${tdsSelected ? 'ENABLED' : 'DISABLED'}</span></div>`;
            
            html += '</div>';
        }
        html += '</div>';
        
        // ALLOWANCES SECTION
        const allowActive = isActive('Allowances');
        html += `<div class="policy-section-card ${allowActive ? 'border-success shadow-lg' : 'opacity-75'}" style="transition: all 0.2s;">`;
        if (allowActive) html += '<div class="section-modified-badge bg-success"><i class="fas fa-pen"></i> MODIFIED IN THIS UPDATE</div>';
        html += '<div class="section-header-icon"><i class="fas fa-plus-circle" style="color: #10b981;"></i> Allowances</div><div class="section-divider"></div>';
        
        if (data.allowances) {
            const allowances = data.allowances;
            const allowanceItems = {
                hra: ['House Rent Allowance (HRA)', 'fas fa-home'],
                conveyance: ['Conveyance Allowance', 'fas fa-car'],
                medical: ['Medical Allowance', 'fas fa-first-aid'],
                special: ['Special Allowance', 'fas fa-star'],
                lta: ['Leave Travel Allowance (LTA)', 'fas fa-plane'],
                education: ['Education Allowance', 'fas fa-graduation-cap']
            };
            
            for (const [key, item] of Object.entries(allowanceItems)) {
                const isSelected = allowances[`${key}_selected`] ?? false;
                html += `<div class="allowance-row"><div class="allowance-name"><i class="${item[1]} mr-2"></i> ${item[0]}</div>`;
                html += `<div class="allowance-status"><span class="status-badge ${isSelected ? 'status-enabled' : 'status-disabled'}">${isSelected ? 'INCLUDED' : 'EXCLUDED'}</span></div></div>`;
            }
            
            if (allowances.custom_allowances?.length) {
                html += '<div class="custom-section mt-3"><i class="fas fa-plus-circle"></i> Custom Allowances</div>';
                allowances.custom_allowances.forEach(ca => {
                    html += `<div class="allowance-row"><div class="allowance-name"><i class="fas fa-money-check-alt mr-2"></i> ${ca.name || 'Custom'}</div>`;
                    html += `<div class="allowance-status"><span class="status-badge status-enabled">INCLUDED</span></div></div>`;
                });
            }
        } else {
            html += '<div class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 opacity-25"></i><br>No allowances configured</div>';
        }
        html += '</div>';
        
        // OTHER DEDUCTIONS SECTION
        const otherActive = isActive('Other Deductions');
        html += `<div class="policy-section-card ${otherActive ? 'border-warning shadow-lg' : 'opacity-75'}" style="transition: all 0.2s;">`;
        if (otherActive) html += '<div class="section-modified-badge bg-warning text-dark"><i class="fas fa-pen"></i> MODIFIED IN THIS UPDATE</div>';
        html += '<div class="section-header-icon"><i class="fas fa-minus-circle" style="color: #f59e0b;"></i> Other Deductions</div><div class="section-divider"></div>';
        
        if (data.other_deductions) {
            const deductions = data.other_deductions;
            const deductionItems = {
                insurance: ['Insurance Premium', 'fas fa-shield-alt'],
                loan: ['Loan Recovery', 'fas fa-hand-holding-usd'],
                advance: ['Salary Advance', 'fas fa-money-bill-wave']
            };
            
            for (const [key, item] of Object.entries(deductionItems)) {
                const isSelected = deductions[`${key}_selected`] ?? false;
                html += `<div class="allowance-row"><div class="allowance-name"><i class="${item[1]} mr-2"></i> ${item[0]}</div>`;
                html += `<div class="allowance-status"><span class="status-badge ${isSelected ? 'status-enabled' : 'status-disabled'}">${isSelected ? 'INCLUDED' : 'EXCLUDED'}</span></div></div>`;
            }
            
            if (deductions.custom_deductions?.length) {
                html += '<div class="custom-section mt-3"><i class="fas fa-plus-circle"></i> Custom Deductions</div>';
                deductions.custom_deductions.forEach(cd => {
                    html += `<div class="allowance-row"><div class="allowance-name"><i class="fas fa-file-invoice-dollar mr-2"></i> ${cd.name || 'Custom'}</div>`;
                    html += `<div class="allowance-status"><span class="status-badge status-enabled">INCLUDED</span></div></div>`;
                });
            }
        } else {
            html += '<div class="text-center text-muted py-4"><i class="fas fa-inbox fa-2x mb-2 opacity-25"></i><br>No other deductions configured</div>';
        }
        html += '</div>';
        
        return html;
    }
    
    function ucfirst(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
</script>

@endsection