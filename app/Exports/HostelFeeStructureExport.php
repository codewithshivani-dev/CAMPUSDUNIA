<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class HostelFeeStructureExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        // Convert array of data to collection
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
                'Hostel Fee',
                'Total Installments',
                'Discount',
                'Hostel Fee',
                'Hostel Total Fee',
                'Late Fee',
                'Start Date',
                'Due Date',
                'Pay Date',
                'Payment Type',
                'Payment Status',
                'Updated At',
        ];
    }
}