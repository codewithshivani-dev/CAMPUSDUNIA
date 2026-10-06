<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransportFeeStructureExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Registration No',
            'Department',
            'Course',
            'Branch',
            'Batch',
            'Academic Year',
            'Semester',
            'Transport Stop',
            'Fee Duration Type',
            'Installment Number',
            'Total Installments',
            'Transport Fee',
            'Total Fee',
            'Discount Amount',
            'Late Fee Amount',
            'Start Date',
            'Due Date',
            'Pay Date',
            'Payment Type',
            'Payment Status',
            'Updated At',
        ];
    }
}