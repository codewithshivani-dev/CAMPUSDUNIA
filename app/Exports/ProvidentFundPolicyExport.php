<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProvidentFundPolicyExport implements FromCollection, WithHeadings
{
    protected $policies;

    public function __construct(Collection $policies)
    {
        $this->policies = $policies;
    }

    public function collection()
    {
        return $this->policies->map(function ($policy) {

            // Mode detection
            if ($policy->employee_id) {
                $mode = 'Employee';
                $target = $policy->employee_id;
            } elseif ($policy->department_id) {
                $mode = 'Department';
                $target = $policy->department_id;
            } else {
                $mode = 'Global';
                $target = 'All';
            }

            return [
                'Payroll Policy ID'        => $policy->payroll_policy_id,
                'Financial Year'           => $policy->financial_year,
                'Mode'                     => $mode,
                'Target'                   => $target,

                'PF Enabled'               => $policy->enable_pf,
                'PF Employee Enabled'      => $policy->pf_employee_enabled,
                'PF Employee Type'         => $policy->pf_employee_type,
                'PF Employee Value'        => $policy->pf_employee_value,

                'PF Employer Enabled'      => $policy->pf_employer_enabled,
                'PF Employer Type'         => $policy->pf_employer_type,
                'PF Employer Value'        => $policy->pf_employer_value,

                'ESI Enabled'              => $policy->enable_esi,
                'ESI Employee Enabled'     => $policy->esi_employee_enabled,
                'ESI Employee Type'        => $policy->esi_employee_type,
                'ESI Employee Value'       => $policy->esi_employee_value,

                'ESI Employer Enabled'     => $policy->esi_employer_enabled,
                'ESI Employer Type'        => $policy->esi_employer_type,
                'ESI Employer Value'       => $policy->esi_employer_value,

                'Created At'               => $policy->created_at,
                'Updated At'               => $policy->updated_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Payroll Policy ID',
            'Financial Year',
            'Mode',
            'Target',

            'PF Enabled',
            'PF Employee Enabled',
            'PF Employee Type',
            'PF Employee Value',

            'PF Employer Enabled',
            'PF Employer Type',
            'PF Employer Value',

            'ESI Enabled',
            'ESI Employee Enabled',
            'ESI Employee Type',
            'ESI Employee Value',

            'ESI Employer Enabled',
            'ESI Employer Type',
            'ESI Employer Value',

            'Created At',
            'Updated At',
        ];
    }
}



