<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubjectsCoursewiseExport implements FromCollection, WithHeadings
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
            'Category',
            'Department',
            'Academic Year',
            'Semester',
            'Course Type',
            'Sub Type',
            'Subject Name',
            'Sub Subjects',
            'Assigned Date',
            'Status',
            'Created At',
            'Updated At'
        ];
    }
}