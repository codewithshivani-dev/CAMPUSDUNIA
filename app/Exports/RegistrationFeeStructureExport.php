<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegistrationFeeStructureExport implements FromCollection, WithHeadings
{
    protected $fees;

    public function __construct($fees)
    {
        $this->fees = $fees;
    }

    public function collection()
    {
        return collect($this->fees);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Student Name',
            'Student Reg',
            'Department',
            'Course',
            'Branch',
            'Batch',
            'Academic Year',
            'Semester',
            'Section',
            'Mode Type',
            'Mode of Course',
            'Fee Duration',
            'Fee Amount',
            'Paid Amount',
            'Late Fee',
            'Discount',
            'Pay Date',
            'Start Date',
            'Due Date',
            'Payment Type',
            'Payment Status',
            'Created At',
        ];
    }
}