<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class MiscellaneousFeeStructureExport implements FromCollection, WithHeadings
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
            'Fee Duration Type',
            'Custom Fee Key',
            'Custom Fee Value',
            'Discount Amount',
            'Late Fee Amount',
            'Total Fee Amount',
            'Pay Date',
            'Start Date',
            'Due Date',
            'Payment Type',
            'Payment Status',
            'Updated At',
        ];
    }
}