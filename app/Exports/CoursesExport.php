<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CoursesExport implements FromCollection, WithHeadings
{
    protected $courses;

    public function __construct($courses)
    {
        $this->courses = $courses;
    }

    // ✅ Export all rows with all columns
    public function collection()
    {
        return $this->courses->map(function ($course) {
            return $course->toArray();
        });
    }

    // ✅ Auto-generate headings from table columns
    public function headings(): array
    {
        if ($this->courses->isEmpty()) {
            return [];
        }

        return array_keys($this->courses->first()->toArray());
    }
}
