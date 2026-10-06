<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalaryStructureExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Transform the collection for export
     */
    public function collection()
    {
        return collect($this->data)->map(function ($row) {
            return [
                'Salary Structure ID'      => $row->salary_structure_id,
                'Payroll Policy ID'        => $row->payroll_policy_id,
                'Department ID'            => $row->department_id,
                'Employee ID'              => $row->employee_id,
                'Financial Year'           => $row->financial_year,
                'Fixed CTC Annual'         => $row->fixed_ctc_annual,
                'Variable CTC Annual'      => $row->variable_ctc_annual,
                'Total CTC Annual'         => $row->total_ctc_annual,
                'Monthly Fixed'            => $row->monthly_fixed,
                'Monthly Variable'         => $row->monthly_variable,
                'Basic Salary %'           => $row->basic_salary_percentage,
                'Basic Salary Monthly'     => $row->basic_salary_monthly,
                'Basic Salary Annual'      => $row->basic_salary_annual,
                'Is Active'                => $row->is_active,
                'Created At'               => $row->created_at,
                'Updated At'               => $row->updated_at,
            ];
        });
    }

    /**
     * Column headings
     */
    public function headings(): array
    {
        return [
            'Salary Structure ID',
            'Payroll Policy ID',
            'Department ID',
            'Employee ID',
            'Financial Year',
            'Fixed CTC Annual',
            'Variable CTC Annual',
            'Total CTC Annual',
            'Monthly Fixed',
            'Monthly Variable',
            'Basic Salary %',
            'Basic Salary Monthly',
            'Basic Salary Annual',
            'Is Active',
            'Created At',
            'Updated At',
        ];
    }
}