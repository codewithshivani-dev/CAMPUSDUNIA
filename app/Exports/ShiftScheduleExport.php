<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShiftScheduleExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = collect($data);
    }

    public function collection()
    {
        return $this->data->map(function ($row) {
            return [
                'Employee ID' => $row->employee_id,
                'Employee Name' => $row->name,
                'Department' => $row->department_name,
                'Category' => $row->department_category_name,
                'Shift Name' => $row->shift_name,
                'Start Time' => $row->start_time,
                'End Time' => $row->end_time,
                'Shift Source' => $row->shift_source,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Employee ID',
            'Employee Name',
            'Department',
            'Category',
            'Shift Name',
            'Start Time',
            'End Time',
            'Shift Source',
        ];
    }
}