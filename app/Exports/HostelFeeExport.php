<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HostelFeeExport implements FromCollection, WithHeadings
{
    protected $hostelFees;

    public function __construct($hostelFees)
    {
        $this->hostelFees = $hostelFees;
    }

    public function collection()
    {
        return $this->hostelFees->map(function ($fee) {

            return [
                'Institute ID' => $fee->institute_id,
                'Hostel Name' => $fee->hostel_name,
                'Hostel Type' => $fee->hostel_type,
                'Room Type' => $fee->room_type,
                'Academic Year' => $fee->academic_year,
                'Total Capacity' => $fee->total_capacity,
                'Available Seats' => $fee->available_seats,
                'Description' => $fee->description,
                'Hostel Fee' => $fee->hostel_fee,
                'Partial Fee Type' => $fee->partially_fee_type,
                'Partial Fee Value' => $fee->partially_fee_value,
                'Late Fee Type' => $fee->late_fee_type,
                'Late Fee Value' => $fee->late_fee_value,
                'Fee Type' => $fee->fee_type,
                'Base Fee' => $fee->base_fee,
                'Fee Duration' => $fee->fee_duration,
                'Monthly Fee' => $fee->monthly_fee,
                'Total Duration' => $fee->total_duration,
                'Security Deposit' => $fee->security_deposit,
                'Maintenance Fee' => $fee->maintenance_fee,
                'Utility Charges' => $fee->utility_charges,
                'Apply Particular Fee' => $fee->apply_particular_fee,
                'Particular Fee Name' => $fee->particular_fee_name,
                'Particular Fee Type' => $fee->particular_fee_type,
                'Particular Fee Value' => $fee->particular_fee_value,
                'Total Fee' => $fee->total_fee,
                'Fee Breakdown' => $fee->fee_breakdown,
                'Grace Period' => $fee->grace_period,
                'Apply Late Fee' => $fee->apply_late_fee,
                'Status' => ucfirst($fee->status),
                'Created At' => optional($fee->created_at)->format('Y-m-d H:i:s'),
                'Updated At' => optional($fee->updated_at)->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Institute ID',
            'Hostel Name',
            'Hostel Type',
            'Room Type',
            'Academic Year',
            'Total Capacity',
            'Available Seats',
            'Description',
            'Hostel Fee',
            'Partial Fee Type',
            'Partial Fee Value',
            'Late Fee Type',
            'Late Fee Value',
            'Fee Type',
            'Base Fee',
            'Fee Duration',
            'Monthly Fee',
            'Total Duration',
            'Security Deposit',
            'Maintenance Fee',
            'Utility Charges',
            'Apply Particular Fee',
            'Particular Fee Name',
            'Particular Fee Type',
            'Particular Fee Value',
            'Total Fee',
            'Fee Breakdown',
            'Grace Period',
            'Apply Late Fee',
            'Status',
            'Created At',
            'Updated At'
        ];
    }
}
