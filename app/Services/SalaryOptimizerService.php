<?php

namespace App\Services;

use App\Models\ProvidentFundPolicy;
use App\Models\PayrollPolicyAllowance;
use App\Models\PayrollPolicyTaxDeduction;
use App\Models\PayrollPolicyOtherDeduction;

class SalaryOptimizerService
{
    /**
     * Configuration for optimization
     */
    protected array $config = [
        'esi_limit' => 21000,
        'pf_wage_limit_default' => 0,
        'max_iterations' => 30,
        'convergence_threshold' => 1,  // ₹1 tolerance
        'min_iterations' => 3,
        'allowance_min_amount' => 100,  // Minimum ₹100 per allowance
    ];

    /**
     * Cache for policy structure
     */
    protected array $policyCache = [];

    /**
     * Main optimization method - deterministic iterative solver
     */
    public function optimizeWithPolicy(array $data): array
    {
        $fixedCTC = (float) $data['ctc'];
        $variableCTC = (float) ($data['variable_ctc'] ?? 0);
        $totalCTC = $fixedCTC + $variableCTC;
        $basicPercentage = (float) ($data['basic_percentage'] ?? 40);
        $policyId = $data['policy_id'] ?? null;
        $financialYear = $data['financial_year'] ?? date('Y') . '-' . (date('Y') + 1);
        
        // Load policy structure
        $policyStructure = $this->loadPolicyStructure($policyId, $financialYear);
        
        // Get enabled allowances
        $enabledAllowances = $this->getEnabledAllowances($policyStructure['allowances']);
        
        if (empty($enabledAllowances)) {
            return $this->buildErrorResponse('No enabled allowances found in policy');
        }
        
        // Initial Basic Salary
        $basicAnnual = $fixedCTC * ($basicPercentage / 100);
        $basicMonthly = $basicAnnual / 12;
        
        // ============================================
        // INITIAL ALLOCATION - Equal distribution
        // ============================================
        $allowanceKeys = array_keys($enabledAllowances);
        $allowanceCount = count($allowanceKeys);
        
        // Step 1: Initial rough estimate - ignore statutory first
        $initialRemaining = $fixedCTC - $basicAnnual;
        $initialPerAllowanceAnnual = $initialRemaining / $allowanceCount;
        $initialPerAllowanceMonthly = $initialPerAllowanceAnnual / 12;
        
        $allowances = [];
        foreach ($allowanceKeys as $key) {
            $config = $enabledAllowances[$key];
            $type = $config['type'];
            
            if ($type === 'percentage') {
                // Convert monthly amount to percentage of basic
                $percentage = ($initialPerAllowanceMonthly / $basicMonthly) * 100;
                $percentage = max(5, min(60, round($percentage / 5) * 5)); // 5% to 60%, rounded to 5
                $monthly = $basicMonthly * ($percentage / 100);
            } else {
                $monthly = max($this->config['allowance_min_amount'], round($initialPerAllowanceMonthly / 100) * 100);
            }
            
            $allowances[$key] = [
                'name' => $config['name'],
                'type' => $type,
                'value' => $type === 'percentage' ? $percentage : $monthly,
                'display_value' => $type === 'percentage' ? $percentage : $monthly,
                'display_unit' => $type === 'percentage' ? '%' : '₹',
                'monthly' => round($monthly, 2),
                'annual' => round($monthly * 12, 2),
                'is_custom' => $config['is_custom'] ?? false,
                'description' => $config['description'] ?? '',
                'can_optimize' => $config['can_optimize'] ?? true,
                'is_balancing' => false,
                'enabled' => true,
            ];
        }
        
        // ============================================
        // ITERATIVE SOLVER
        // ============================================
        $previousDifference = PHP_FLOAT_MAX;
        $consecutiveNoChange = 0;
        $iterations = 0;
        $converged = false;
        $balancingKey = $this->identifyBalancingAllowance($enabledAllowances, $allowanceKeys);
        
        for ($iteration = 0; $iteration < $this->config['max_iterations']; $iteration++) {
            $iterations = $iteration + 1;
            
            // Calculate current totals
            $allowancesTotalMonthly = array_sum(array_column($allowances, 'monthly'));
            $allowancesTotalAnnual = $allowancesTotalMonthly * 12;
            $grossMonthly = $basicMonthly + $allowancesTotalMonthly;
            $grossAnnual = $grossMonthly * 12;
            
            // Calculate Employer Contributions
            $employerContributions = $this->calculateEmployerContributions(
                $policyStructure['statutory'],
                $basicMonthly,
                $grossMonthly
            );
            
            // Calculate Employee Deductions
            $employeeDeductions = $this->calculateEmployeeDeductions(
                $policyStructure['statutory'],
                $policyStructure['tax_deductions'],
                $policyStructure['other_deductions'],
                $basicMonthly,
                $grossMonthly
            );
            
            // Calculate Fixed CTC
            $employerTotalAnnual = $employerContributions['total'] * 12;
            $calculatedFixedCTC = $grossAnnual + $employerTotalAnnual;
            $difference = $fixedCTC - $calculatedFixedCTC;
            
            // Check convergence
            if (abs($difference) < $this->config['convergence_threshold'] && $iteration >= $this->config['min_iterations']) {
                $converged = true;
                break;
            }
            
            // Check if we're stuck
            if (abs($difference) >= abs($previousDifference)) {
                $consecutiveNoChange++;
                if ($consecutiveNoChange >= 5) {
                    break;
                }
            } else {
                $consecutiveNoChange = 0;
            }
            $previousDifference = $difference;
            
            // ============================================
            // ADJUST BALANCING ALLOWANCE
            // ============================================
            if ($balancingKey && isset($allowances[$balancingKey])) {
                $currentMonthly = $allowances[$balancingKey]['monthly'];
                $adjustmentMonthly = $difference / 12;
                $newMonthly = max($this->config['allowance_min_amount'], $currentMonthly + $adjustmentMonthly);
                
                // Update balancing allowance
                $allowances[$balancingKey]['monthly'] = round($newMonthly, 2);
                $allowances[$balancingKey]['annual'] = round($newMonthly * 12, 2);
                
                if ($allowances[$balancingKey]['type'] === 'percentage') {
                    $newPercentage = ($newMonthly / $basicMonthly) * 100;
                    $allowances[$balancingKey]['value'] = round($newPercentage, 2);
                    $allowances[$balancingKey]['display_value'] = round($newPercentage, 2);
                } else {
                    $allowances[$balancingKey]['value'] = round($newMonthly, 2);
                    $allowances[$balancingKey]['display_value'] = round($newMonthly, 2);
                }
            } else {
                // Fallback: adjust all allowances proportionally
                $totalMonthly = array_sum(array_column($allowances, 'monthly'));
                if ($totalMonthly > 0) {
                    $adjustmentFactor = ($totalMonthly + ($difference / 12)) / $totalMonthly;
                    $adjustmentFactor = max(0.5, min(1.5, $adjustmentFactor));
                    
                    foreach ($allowances as $key => &$allowance) {
                        $newMonthly = $allowance['monthly'] * $adjustmentFactor;
                        $newMonthly = max($this->config['allowance_min_amount'], $newMonthly);
                        $allowance['monthly'] = round($newMonthly, 2);
                        $allowance['annual'] = round($newMonthly * 12, 2);
                        
                        if ($allowance['type'] === 'percentage') {
                            $newPercentage = ($newMonthly / $basicMonthly) * 100;
                            $allowance['value'] = round($newPercentage, 2);
                            $allowance['display_value'] = round($newPercentage, 2);
                        } else {
                            $allowance['value'] = round($newMonthly, 2);
                            $allowance['display_value'] = round($newMonthly, 2);
                        }
                    }
                }
            }
        }
        
        // ============================================
        // FINAL CALCULATIONS
        // ============================================
        $allowancesTotalMonthly = array_sum(array_column($allowances, 'monthly'));
        $allowancesTotalAnnual = $allowancesTotalMonthly * 12;
        $grossMonthly = $basicMonthly + $allowancesTotalMonthly;
        $grossAnnual = $grossMonthly * 12;
        
        $employerContributions = $this->calculateEmployerContributions(
            $policyStructure['statutory'],
            $basicMonthly,
            $grossMonthly
        );
        
        $employeeDeductions = $this->calculateEmployeeDeductions(
            $policyStructure['statutory'],
            $policyStructure['tax_deductions'],
            $policyStructure['other_deductions'],
            $basicMonthly,
            $grossMonthly
        );
        
        $totalDeductionsMonthly = array_sum(array_column($employeeDeductions, 'monthly'));
        $totalDeductionsAnnual = $totalDeductionsMonthly * 12;
        
        $netMonthly = $grossMonthly - $totalDeductionsMonthly;
        $netAnnual = $netMonthly * 12;
        
        $employerTotalMonthly = $employerContributions['total'];
        $employerTotalAnnual = $employerTotalMonthly * 12;
        $calculatedFixedCTC = $grossAnnual + $employerTotalAnnual;
        $finalDifference = $fixedCTC - $calculatedFixedCTC;
        
        // Build employer contributions display
        $employerContributionsDisplay = [];
        if ($employerContributions['pf'] > 0) {
            $employerContributionsDisplay['pf'] = round($employerContributions['pf'], 2);
        }
        if ($employerContributions['esi'] > 0) {
            $employerContributionsDisplay['esi'] = round($employerContributions['esi'], 2);
        }
        if ($employerContributions['nps'] > 0) {
            $employerContributionsDisplay['nps'] = round($employerContributions['nps'], 2);
        }
        
        // Separate statutory deductions
        $statutoryDeductions = [];
        $taxDeductionsList = [];
        $otherDeductionsList = [];
        
        foreach ($employeeDeductions as $deduction) {
            $name = $deduction['name'] ?? '';
            if (in_array($name, ['PF (Employee)', 'ESI (Employee)', 'NPS (Employee)'])) {
                $statutoryDeductions[] = $deduction;
            } elseif (in_array($name, ['Professional Tax', 'Labour State Tax', 'TDS'])) {
                $taxDeductionsList[] = $deduction;
            } else {
                $otherDeductionsList[] = $deduction;
            }
        }
        
        // Build changes
        $changes = $this->buildChanges($allowances, $basicPercentage, $fixedCTC, $iterations, $balancingKey);
        
        // Health score
        $healthScore = $this->calculateHealthScore($finalDifference);
        
        // Validation
        $validation = $this->buildValidation($fixedCTC, $calculatedFixedCTC, $finalDifference, $iterations);
        
        return [
            'success' => true,
            'fixed_ctc' => $fixedCTC,
            'variable_ctc' => $variableCTC,
            'total_ctc' => $totalCTC,
            'basic_percentage' => $basicPercentage,
            'basic_annual' => round($basicAnnual, 2),
            'basic_monthly' => round($basicMonthly, 2),
            'gross_monthly' => round($grossMonthly, 2),
            'gross_annual' => round($grossAnnual, 2),
            'net_monthly' => round($netMonthly, 2),
            'net_annual' => round($netAnnual, 2),
            'allowances' => $allowances,
            'allowances_total_monthly' => round($allowancesTotalMonthly, 2),
            'allowances_total_annual' => round($allowancesTotalAnnual, 2),
            'deductions' => $employeeDeductions,
            'deductions_total_monthly' => round($totalDeductionsMonthly, 2),
            'deductions_total_annual' => round($totalDeductionsAnnual, 2),
            'employee_statutory' => $statutoryDeductions,
            'tax_deductions' => $taxDeductionsList,
            'other_deductions' => $otherDeductionsList,
            'employer_contributions' => $employerContributionsDisplay,
            'employer_total_monthly' => round($employerTotalMonthly, 2),
            'employer_total_annual' => round($employerTotalAnnual, 2),
            'total_cost' => round($calculatedFixedCTC, 2),
            'difference' => round($finalDifference, 2),
            'changes' => $changes,
            'health_score' => $healthScore,
            'validation' => $validation,
            'summary' => $this->generateSummary($basicPercentage, $allowances, $calculatedFixedCTC, $healthScore, $validation, $iterations),
            'iterations_used' => $iterations,
            'converged' => $converged,
            'balancing_allowance' => $balancingKey,
        ];
    }

    /**
     * Identify which allowance should be the balancing allowance
     */
    protected function identifyBalancingAllowance(array $enabledAllowances, array $allowanceKeys): ?string
    {
        // Priority 1: Special allowance
        foreach ($allowanceKeys as $key) {
            if (in_array($key, ['special', 'special_allowance'])) {
                return $key;
            }
        }
        
        // Priority 2: Custom allowances
        foreach ($allowanceKeys as $key) {
            if (strpos($key, 'custom_') === 0) {
                return $key;
            }
        }
        
        // Priority 3: Any non-HRA allowance
        foreach ($allowanceKeys as $key) {
            if ($key !== 'hra') {
                return $key;
            }
        }
        
        // Priority 4: HRA (last resort)
        if (in_array('hra', $allowanceKeys)) {
            return 'hra';
        }
        
        return $allowanceKeys[0] ?? null;
    }

    /**
     * Get enabled allowances from policy structure
     */
    protected function getEnabledAllowances(array $allowanceStructure): array
    {
        $enabled = [];
        
        foreach ($allowanceStructure as $key => $structure) {
            if (!$structure['enabled']) {
                continue;
            }
            
            $enabled[$key] = [
                'type' => $structure['type'] ?? 'fixed',
                'name' => $structure['name'] ?? ucfirst($key),
                'is_custom' => $structure['is_custom'] ?? false,
                'can_optimize' => $structure['can_optimize'] ?? true,
                'description' => $structure['description'] ?? '',
            ];
        }
        
        return $enabled;
    }

    /**
     * Calculate Employer Contributions
     */
    protected function calculateEmployerContributions(array $policy, float $basicMonthly, float $grossMonthly): array
    {
        $pf = 0;
        $esi = 0;
        $nps = 0;
        
        // PF Employer
        if (isset($policy['enable_pf']) && $policy['enable_pf'] == 1) {
            if (isset($policy['pf_employer_enabled']) && $policy['pf_employer_enabled'] == 1) {
                $pfWageLimit = (float) ($policy['pf_wage_limit'] ?? $this->config['pf_wage_limit_default']);
                $baseForPF = ($pfWageLimit && $basicMonthly > $pfWageLimit) ? $pfWageLimit : $basicMonthly;
                $pf = $this->calculateContribution(
                    $baseForPF,
                    $policy['pf_employer_type'] ?? 'percentage',
                    (float) ($policy['pf_employer_value'] ?? 12)
                );
            }
        }
        
        // ESI Employer
        if ($this->isESIEnabled($policy)) {
            $esiLimit = $this->config['esi_limit'];
            if ($grossMonthly <= $esiLimit) {
                if (isset($policy['esi_employer_enabled']) && $policy['esi_employer_enabled'] == 1) {
                    $esi = $this->calculateContribution(
                        $grossMonthly,
                        $policy['esi_employer_type'] ?? 'percentage',
                        (float) ($policy['esi_employer_value'] ?? 3.25)
                    );
                }
            }
        }
        
        // NPS Employer
        if (isset($policy['enable_nps']) && $policy['enable_nps'] == 1) {
            if (isset($policy['nps_employer_enabled']) && $policy['nps_employer_enabled'] == 1) {
                $nps = $this->calculateContribution(
                    $basicMonthly,
                    $policy['nps_employer_type'] ?? 'percentage',
                    (float) ($policy['nps_employer_value'] ?? 10)
                );
            }
        }
        
        return [
            'pf' => $pf,
            'esi' => $esi,
            'nps' => $nps,
            'total' => $pf + $esi + $nps
        ];
    }

    /**
     * Calculate Employee Deductions
     */
    protected function calculateEmployeeDeductions(
        array $statutoryPolicy,
        array $taxConfig,
        array $otherConfig,
        float $basicMonthly,
        float $grossMonthly
    ): array {
        $deductions = [];
        
        // PF Employee
        if (isset($statutoryPolicy['enable_pf']) && $statutoryPolicy['enable_pf'] == 1) {
            if (isset($statutoryPolicy['pf_employee_enabled']) && $statutoryPolicy['pf_employee_enabled'] == 1) {
                $pfWageLimit = (float) ($statutoryPolicy['pf_wage_limit'] ?? $this->config['pf_wage_limit_default']);
                $baseForPF = ($pfWageLimit && $basicMonthly > $pfWageLimit) ? $pfWageLimit : $basicMonthly;
                $empPF = $this->calculateContribution(
                    $baseForPF,
                    $statutoryPolicy['pf_employee_type'] ?? 'percentage',
                    (float) ($statutoryPolicy['pf_employee_value'] ?? 12)
                );
                if ($empPF > 0) {
                    $deductions[] = [
                        'name' => 'PF (Employee)',
                        'monthly' => round($empPF, 2),
                        'annual' => round($empPF * 12, 2),
                    ];
                }
            }
        }
        
        // ESI Employee
        if ($this->isESIEnabled($statutoryPolicy)) {
            $esiLimit = $this->config['esi_limit'];
            if ($grossMonthly <= $esiLimit) {
                if (isset($statutoryPolicy['esi_employee_enabled']) && $statutoryPolicy['esi_employee_enabled'] == 1) {
                    $empESI = $this->calculateContribution(
                        $grossMonthly,
                        $statutoryPolicy['esi_employee_type'] ?? 'percentage',
                        (float) ($statutoryPolicy['esi_employee_value'] ?? 0.75)
                    );
                    if ($empESI > 0) {
                        $deductions[] = [
                            'name' => 'ESI (Employee)',
                            'monthly' => round($empESI, 2),
                            'annual' => round($empESI * 12, 2),
                        ];
                    }
                }
            }
        }
        
        // NPS Employee
        if (isset($statutoryPolicy['enable_nps']) && $statutoryPolicy['enable_nps'] == 1) {
            if (isset($statutoryPolicy['nps_employee_enabled']) && $statutoryPolicy['nps_employee_enabled'] == 1) {
                $empNPS = $this->calculateContribution(
                    $grossMonthly,
                    $statutoryPolicy['nps_employee_type'] ?? 'percentage',
                    (float) ($statutoryPolicy['nps_employee_value'] ?? 10)
                );
                if ($empNPS > 0) {
                    $deductions[] = [
                        'name' => 'NPS (Employee)',
                        'monthly' => round($empNPS, 2),
                        'annual' => round($empNPS * 12, 2),
                    ];
                }
            }
        }
        
        // Professional Tax
        if (isset($taxConfig['pt_selected']) && $taxConfig['pt_selected'] == 1) {
            $ptMonthly = 0;
            $ptType = $taxConfig['pt_type'] ?? 'fixed';
            
            if ($ptType === 'slabs' && !empty($taxConfig['pt_slabs'])) {
                foreach ($taxConfig['pt_slabs'] as $slab) {
                    if ($grossMonthly >= (float) $slab['from'] && $grossMonthly <= (float) $slab['to']) {
                        $ptMonthly = (float) ($slab['amount'] ?? 0);
                        break;
                    }
                }
            } elseif ($ptType === 'percentage') {
                $ptMonthly = $grossMonthly * ((float) ($taxConfig['pt_value'] ?? 0) / 100);
            } else {
                $ptMonthly = (float) ($taxConfig['pt_value'] ?? 0);
            }
            
            if ($ptMonthly > 0) {
                $deductions[] = [
                    'name' => 'Professional Tax',
                    'monthly' => round($ptMonthly, 2),
                    'annual' => round($ptMonthly * 12, 2),
                ];
            }
        }
        
        // Labour State Tax
        if (isset($taxConfig['lst_selected']) && $taxConfig['lst_selected'] == 1) {
            $lstMonthly = 0;
            $lstType = $taxConfig['lst_type'] ?? 'fixed';
            
            if ($lstType === 'slabs' && !empty($taxConfig['lst_slabs'])) {
                foreach ($taxConfig['lst_slabs'] as $slab) {
                    if ($grossMonthly >= (float) $slab['from'] && $grossMonthly <= (float) $slab['to']) {
                        if (isset($slab['rate'])) {
                            $lstMonthly = $grossMonthly * ((float) $slab['rate'] / 100);
                        } else {
                            $lstMonthly = (float) ($slab['amount'] ?? 0);
                        }
                        break;
                    }
                }
            } elseif ($lstType === 'percentage') {
                $lstMonthly = $grossMonthly * ((float) ($taxConfig['lst_value'] ?? 0) / 100);
            } else {
                $lstMonthly = (float) ($taxConfig['lst_value'] ?? 0);
            }
            
            if ($lstMonthly > 0) {
                $deductions[] = [
                    'name' => 'Labour State Tax',
                    'monthly' => round($lstMonthly, 2),
                    'annual' => round($lstMonthly * 12, 2),
                ];
            }
        }
        
        // TDS
        if (isset($taxConfig['tds_selected']) && $taxConfig['tds_selected'] == 1) {
            $tdsMonthly = 0;
            $slabs = $taxConfig['tds_slabs'] ?? $this->getDefaultTaxSlabs();
            $grossAnnual = $grossMonthly * 12;
            
            if (!empty($slabs)) {
                foreach ($slabs as $slab) {
                    if ($grossAnnual >= (float) $slab['from'] && $grossAnnual <= (float) $slab['to']) {
                        if (isset($slab['rate'])) {
                            $annualTax = $grossAnnual * ((float) $slab['rate'] / 100);
                            if (isset($slab['additionalTax'])) {
                                $annualTax += (float) $slab['additionalTax'];
                            }
                            $tdsMonthly = $annualTax / 12;
                        }
                        break;
                    }
                }
            }
            
            if ($tdsMonthly > 0) {
                $deductions[] = [
                    'name' => 'TDS',
                    'monthly' => round($tdsMonthly, 2),
                    'annual' => round($tdsMonthly * 12, 2),
                ];
            }
        }
        
        // Insurance Premium
        if (isset($otherConfig['insurance_selected']) && $otherConfig['insurance_selected'] == 1) {
            $monthly = 0;
            $calcType = $otherConfig['insurance_type'] ?? 'fixed';
            $value = (float) ($otherConfig['insurance_value'] ?? 0);
            
            if ($calcType === 'percentage') {
                $monthly = $grossMonthly * ($value / 100);
            } else {
                $monthly = $value;
            }
            
            if ($monthly > 0) {
                $deductions[] = [
                    'name' => 'Insurance Premium',
                    'monthly' => round($monthly, 2),
                    'annual' => round($monthly * 12, 2),
                ];
            }
        }
        
        return $deductions;
    }

    /**
     * Check if ESI is enabled in policy
     */
    protected function isESIEnabled(array $policy): bool
    {
        return isset($policy['enable_esi']) && $policy['enable_esi'] == 1;
    }

    /**
     * Calculate contribution amount
     */
    protected function calculateContribution(float $base, string $type, float $value): float
    {
        if ($type === 'percentage') {
            return $base * ($value / 100);
        }
        return $value;
    }

    /**
     * Load policy structure with caching
     */
    protected function loadPolicyStructure($policyId, $financialYear): array
    {
        $cacheKey = $policyId ?: 'default_' . $financialYear;
        
        if (isset($this->policyCache[$cacheKey])) {
            return $this->policyCache[$cacheKey];
        }
        
        if (!$policyId) {
            $structure = $this->getDefaultPolicyStructure();
            $this->policyCache[$cacheKey] = $structure;
            return $structure;
        }

        $policy = ProvidentFundPolicy::where('payroll_policy_id', $policyId)->first();
        $allowances = PayrollPolicyAllowance::where('payroll_policy_id', $policyId)->first();
        $otherDeductions = PayrollPolicyOtherDeduction::where('payroll_policy_id', $policyId)->first();
        $taxDeductions = PayrollPolicyTaxDeduction::where('payroll_policy_id', $policyId)->first();

        $allowanceStructure = $this->buildAllowanceStructure($allowances);
        
        $structure = [
            'statutory' => $policy ? $policy->toArray() : [],
            'allowances' => $allowanceStructure,
            'other_deductions' => $otherDeductions ? $otherDeductions->toArray() : [],
            'tax_deductions' => $taxDeductions ? $taxDeductions->toArray() : [],
        ];
        
        $this->policyCache[$cacheKey] = $structure;
        return $structure;
    }

    /**
     * Build allowance structure from policy data
     */
    protected function buildAllowanceStructure($allowances): array
    {
        $structure = [];
        
        if (!$allowances) {
            return $this->getDefaultAllowanceStructure();
        }
        
        $allowanceTypes = ['hra', 'conveyance', 'medical', 'special', 'lta', 'education'];
        foreach ($allowanceTypes as $type) {
            $selectedKey = $type . '_selected';
            $typeKey = $type . '_type';
            
            $structure[$type] = [
                'enabled' => isset($allowances->$selectedKey) && $allowances->$selectedKey == 1,
                'type' => $allowances->$typeKey ?? 'fixed',
                'name' => $this->getAllowanceName($type),
                'is_custom' => false,
                'can_optimize' => $type !== 'hra',
            ];
        }
        
        // Custom allowances
        if (isset($allowances->custom_allowances) && is_array($allowances->custom_allowances)) {
            foreach ($allowances->custom_allowances as $index => $custom) {
                $key = 'custom_' . $index;
                $structure[$key] = [
                    'enabled' => $this->isCustomAllowanceEnabled($custom),
                    'type' => $custom['type'] ?? 'fixed',
                    'name' => $custom['name'] ?? 'Custom Allowance ' . ($index + 1),
                    'is_custom' => true,
                    'description' => $custom['description'] ?? '',
                    'can_optimize' => true,
                ];
            }
        }
        
        return $structure;
    }

    /**
     * Get default allowance structure
     */
    protected function getDefaultAllowanceStructure(): array
    {
        return [
            'hra' => ['enabled' => true, 'type' => 'percentage', 'name' => 'HRA', 'is_custom' => false, 'can_optimize' => false],
            'conveyance' => ['enabled' => true, 'type' => 'fixed', 'name' => 'Conveyance', 'is_custom' => false, 'can_optimize' => true],
            'medical' => ['enabled' => true, 'type' => 'fixed', 'name' => 'Medical', 'is_custom' => false, 'can_optimize' => true],
            'special' => ['enabled' => true, 'type' => 'fixed', 'name' => 'Special Allowance', 'is_custom' => false, 'can_optimize' => true],
            'lta' => ['enabled' => false, 'type' => 'fixed', 'name' => 'LTA', 'is_custom' => false, 'can_optimize' => true],
            'education' => ['enabled' => false, 'type' => 'fixed', 'name' => 'Education Allowance', 'is_custom' => false, 'can_optimize' => true],
        ];
    }

    /**
     * Get default policy structure
     */
    protected function getDefaultPolicyStructure(): array
    {
        return [
            'statutory' => [
                'enable_pf' => 1,
                'pf_employee_type' => 'percentage',
                'pf_employee_value' => 12,
                'pf_employer_type' => 'percentage',
                'pf_employer_value' => 12,
                'pf_wage_limit' => 15000,
                'pf_employee_enabled' => 1,
                'pf_employer_enabled' => 1,
                'enable_esi' => 0,
                'esi_employee_type' => 'percentage',
                'esi_employee_value' => 0.75,
                'esi_employer_type' => 'percentage',
                'esi_employer_value' => 3.25,
                'esi_employee_enabled' => 0,
                'esi_employer_enabled' => 0,
                'enable_nps' => 0,
                'nps_employee_type' => 'percentage',
                'nps_employee_value' => 10,
                'nps_employer_type' => 'percentage',
                'nps_employer_value' => 10,
                'nps_employee_enabled' => 0,
                'nps_employer_enabled' => 0,
            ],
            'allowances' => $this->getDefaultAllowanceStructure(),
            'other_deductions' => [
                'insurance_selected' => 0,
                'insurance_type' => 'fixed',
                'insurance_value' => 0,
            ],
            'tax_deductions' => [
                'pt_selected' => 1,
                'pt_type' => 'slabs',
                'pt_value' => 0,
                'pt_slabs' => [
                    ['from' => 0, 'to' => 15000, 'amount' => 0],
                    ['from' => 15001, 'to' => 30000, 'amount' => 150],
                    ['from' => 30001, 'to' => 45000, 'amount' => 300],
                    ['from' => 45001, 'to' => 60000, 'amount' => 500],
                    ['from' => 60001, 'to' => 1000000, 'amount' => 600],
                ],
                'lst_selected' => 0,
                'lst_type' => 'fixed',
                'lst_value' => 0,
                'lst_slabs' => [],
                'tds_selected' => 0,
                'tds_slabs' => [
                    ['from' => 0, 'to' => 400000, 'rate' => 0],
                    ['from' => 400001, 'to' => 800000, 'rate' => 5],
                    ['from' => 800001, 'to' => 1200000, 'rate' => 10],
                    ['from' => 1200001, 'to' => 1600000, 'rate' => 15],
                    ['from' => 1600001, 'to' => 2000000, 'rate' => 20],
                    ['from' => 2000001, 'to' => 2400000, 'rate' => 25],
                    ['from' => 2400001, 'to' => 10000000, 'rate' => 30],
                ],
            ],
        ];
    }

    /**
     * Get allowance display name
     */
    protected function getAllowanceName(string $type): string
    {
        $names = [
            'hra' => 'HRA',
            'conveyance' => 'Conveyance',
            'medical' => 'Medical',
            'special' => 'Special Allowance',
            'lta' => 'LTA',
            'education' => 'Education Allowance',
        ];
        return $names[$type] ?? ucfirst($type);
    }

    /**
     * Check if custom allowance is enabled
     */
    protected function isCustomAllowanceEnabled(array $allowance): bool
    {
        if (isset($allowance['selected'])) {
            return $allowance['selected'] == 1 || $allowance['selected'] === true;
        }
        if (isset($allowance['enabled'])) {
            return $allowance['enabled'] == 1 || $allowance['enabled'] === true;
        }
        return true;
    }

    /**
     * Get default tax slabs
     */
    protected function getDefaultTaxSlabs(): array
    {
        return [
            ['from' => 0, 'to' => 400000, 'rate' => 0],
            ['from' => 400001, 'to' => 800000, 'rate' => 5],
            ['from' => 800001, 'to' => 1200000, 'rate' => 10],
            ['from' => 1200001, 'to' => 1600000, 'rate' => 15],
            ['from' => 1600001, 'to' => 2000000, 'rate' => 20],
            ['from' => 2000001, 'to' => 2400000, 'rate' => 25],
            ['from' => 2400001, 'to' => 10000000, 'rate' => 30],
        ];
    }

    /**
     * Build changes for display
     */
    protected function buildChanges(array $allowances, float $basicPercentage, float $fixedCTC, int $iterations, ?string $balancingKey): array
    {
        $changes = [];
        
        $changes[] = [
            'component' => 'Basic Salary',
            'from' => 'Calculated',
            'to' => round($basicPercentage, 2) . '%',
            'type' => 'percentage',
            'monthly_change' => 0,
            'annual_change' => 0,
            'iteration' => 0,
        ];
        
        foreach ($allowances as $key => $allowance) {
            if (!($allowance['enabled'] ?? true)) {
                continue;
            }
            
            $displayValue = $allowance['type'] === 'percentage' 
                ? round($allowance['display_value'], 1) . '%'
                : '₹' . number_format($allowance['display_value']);
            
            $changes[] = [
                'component' => $allowance['name'],
                'from' => 'Optimized',
                'to' => $displayValue . '/month',
                'type' => 'allowance',
                'monthly_change' => $allowance['monthly'],
                'annual_change' => $allowance['annual'],
                'is_balancing' => ($key === $balancingKey),
                'iteration' => $iterations,
            ];
        }
        
        return $changes;
    }

    /**
     * Calculate health score
     */
    protected function calculateHealthScore(float $difference): int
    {
        $score = 100;
        if (abs($difference) > 1) {
            $score -= min(abs($difference) * 0.01, 50);
        }
        return max(0, min(100, round($score)));
    }

    /**
     * Build validation array
     */
    protected function buildValidation(float $target, float $calculated, float $difference, int $iterations): array
    {
        $isMatch = abs($difference) < 1;
        
        return [
            'is_match' => $isMatch,
            'match' => $isMatch,
            'difference' => round($difference, 2),
            'target' => $target,
            'calculated' => round($calculated, 2),
            'iterations' => $iterations,
            'status' => $isMatch ? 'balanced' : ($difference > 0 ? 'under_budget' : 'over_budget'),
            'match_status' => $isMatch ? '✅ Perfect Match' : ($difference > 0 ? '⚠️ Under Budget' : '⚠️ Over Budget'),
            'match_color' => $isMatch ? '#dcfce7' : '#fef3c7',
            'match_border' => $isMatch ? '#22c55e' : '#f59e0b',
            'match_text' => $isMatch ? '#15803d' : '#92400e',
            'difference_status' => $isMatch ? 'Balanced' : ($difference > 0 ? 'Under Budget' : 'Over Budget'),
            'difference_color' => $isMatch ? '#dcfce7' : '#fef3c7',
            'difference_border' => $isMatch ? '#22c55e' : '#f59e0b',
            'difference_text' => $isMatch ? '#15803d' : '#92400e',
            'suggestion' => $isMatch ? null : (
                $difference > 0 
                    ? 'Increase balancing allowance by ₹' . number_format(abs($difference)) 
                    : 'Reduce balancing allowance by ₹' . number_format(abs($difference))
            )
        ];
    }

    /**
     * Generate summary
     */
    protected function generateSummary(
        float $basicPercentage,
        array $allowances,
        float $totalCost,
        int $healthScore,
        array $validation,
        int $iterations
    ): string {
        $parts = [];
        $parts[] = '✅ Basic: ' . round($basicPercentage, 2) . '%';
        $parts[] = '📊 Total Cost: ₹' . number_format($totalCost);
        
        if ($validation['is_match']) {
            $parts[] = '🎯 Perfectly Balanced!';
        } else {
            $diff = $validation['difference'] ?? 0;
            if ($diff > 0) {
                $parts[] = '📈 Under Budget by ₹' . number_format($diff);
            } else {
                $parts[] = '📉 Over Budget by ₹' . number_format(abs($diff));
            }
        }
        
        $parts[] = '💚 Health: ' . $healthScore . '%';
        $parts[] = '🔄 Iterations: ' . $iterations;
        
        $enabledCount = count(array_filter($allowances, function($a) {
            return $a['enabled'] ?? true;
        }));
        $parts[] = '📋 ' . $enabledCount . ' allowances';
        
        return implode(' | ', $parts);
    }

    /**
     * Build error response
     */
    protected function buildErrorResponse(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
            'fixed_ctc' => 0,
            'variable_ctc' => 0,
            'total_ctc' => 0,
            'basic_percentage' => 0,
            'basic_annual' => 0,
            'basic_monthly' => 0,
            'gross_monthly' => 0,
            'gross_annual' => 0,
            'net_monthly' => 0,
            'net_annual' => 0,
            'allowances' => [],
            'allowances_total_monthly' => 0,
            'allowances_total_annual' => 0,
            'deductions' => [],
            'deductions_total_monthly' => 0,
            'deductions_total_annual' => 0,
            'employee_statutory' => [],
            'tax_deductions' => [],
            'other_deductions' => [],
            'employer_contributions' => [],
            'employer_total_monthly' => 0,
            'employer_total_annual' => 0,
            'total_cost' => 0,
            'difference' => 0,
            'changes' => [],
            'health_score' => 0,
            'validation' => [
                'is_match' => false,
                'match' => false,
                'difference' => 0,
                'target' => 0,
                'calculated' => 0,
                'iterations' => 0,
                'status' => 'error',
                'match_status' => '❌ Error',
                'match_color' => '#fee2e2',
                'match_border' => '#ef4444',
                'match_text' => '#991b1b',
                'difference_status' => 'Error',
                'difference_color' => '#fee2e2',
                'difference_border' => '#ef4444',
                'difference_text' => '#991b1b',
                'suggestion' => $message
            ],
            'summary' => '❌ ' . $message,
            'iterations_used' => 0,
            'converged' => false,
            'balancing_allowance' => null
        ];
    }
}