<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CourseFeeStructureExport implements FromCollection, WithHeadings
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
        'Student Name',
        'Registration No',
        'Department',
        'Course',
        'Branch',
        'Batch',
        'Academic Year',
        'Semester',
        'Course Fee',
        'Course Total Fee',
        'Paid Amount',
        'Discount',
        'Late Fee',
        'Final Payable Fee',
        'Pay Date',
        'Start Date',
        'Due Date',
        'Payment Status',
        'Updated Date',
    ];
}

}