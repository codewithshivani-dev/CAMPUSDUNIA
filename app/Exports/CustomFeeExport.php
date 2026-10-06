<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomFeeExport implements FromCollection, WithHeadings
{
    protected $fees;

    public function __construct(Collection $fees)
    {
        $this->fees = $fees;
    }

    public function collection()
    {
        return $this->fees->map(function ($fee) {

            return [

                // 'ID' => $fee->id,
                // 'Institute ID' => $fee->institute_id,
                // 'Branch ID' => $fee->branch_id,

                'Reference ID' => $fee->custom_reference_id,
                'Academic Year' => $fee->academic_year,
                'Fee Duration Type' => $fee->fee_duration_type,
                'Fee Key' => $fee->custom_fee_key,
                'Fee Value' => $fee->custom_fee_value,

                'Start Date' => optional($fee->start_date)->format('Y-m-d'),
                'Due Date' => optional($fee->due_date)->format('Y-m-d'),

                'Recurrence Period' => $fee->recurrence_period,

                'Late Fee Type' => $fee->late_fee_type,
                'Late Fee Value' => $fee->late_fee_value,
                'Late Fee Amount' => $fee->late_fee_amount,
                'Late Fee Description' => $fee->late_fee_description,

                'Discount Amount' => $fee->discount_amount,

                'Fee Type' => $fee->fee_type,

                'Partial Fee Type' => $fee->partially_fee_type,
                'Partial Fee Value' => $fee->partially_fee_value,
                'Partial Fee Amount' => $fee->partially_fee_amount,
                'Allow Partial Payments' => $fee->allow_partial_payments,

                'Description' => $fee->description,
                'Status' => $fee->status,

                'Created At' => optional($fee->created_at)->format('Y-m-d H:i:s'),
                'Updated At' => optional($fee->updated_at)->format('Y-m-d H:i:s'),

            ];
        });
    }

    public function headings(): array
    {
        return [

            // 'ID',
            // 'Institute ID',
            // 'Branch ID',

            'Reference ID',
            'Academic Year',
            'Fee Duration Type',
            'Fee Key',
            'Fee Value',

            'Start Date',
            'Due Date',

            'Recurrence Period',

            'Late Fee Type',
            'Late Fee Value',
            'Late Fee Amount',
            'Late Fee Description',

            'Discount Amount',

            'Fee Type',

            'Partial Fee Type',
            'Partial Fee Value',
            'Partial Fee Amount',
            'Allow Partial Payments',

            'Description',
            'Status',

            'Created At',
            'Updated At',

        ];
    }
}