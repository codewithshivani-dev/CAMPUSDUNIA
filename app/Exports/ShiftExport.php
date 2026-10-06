<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class ShiftExport implements FromCollection, WithHeadings
{
    protected $shifts;

    public function __construct($shifts)
    {
        $this->shifts = $shifts;
    }

public function collection()
{
    return $this->shifts->map(function ($shift) {

        $data = $shift->getAttributes(); // all DB columns

        // Add relationship data
        $data['employees'] = $shift->employees
            ? $shift->employees->pluck('name')->implode(', ')
            : '';

        $data['students'] = $shift->students
            ? $shift->students->pluck('name')->implode(', ')
            : '';

        $data['employees_count'] = $shift->employees
            ? $shift->employees->count()
            : 0;

        $data['students_count'] = $shift->students
            ? $shift->students->count()
            : 0;

        return $data;
    });
}

public function headings(): array
{
    if ($this->shifts->isEmpty()) {
        return [];
    }

    $first = $this->collection()->first();
    return array_keys($first);
}

}