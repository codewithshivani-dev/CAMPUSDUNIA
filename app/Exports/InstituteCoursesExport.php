<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class InstituteCoursesExport implements FromCollection, WithHeadings
{
    protected $courses;

    public function __construct($courses)
    {
        $this->courses = $courses;
    }

    public function collection()
    {
        return $this->courses;
    }

    public function headings(): array
    {
        return array_keys($this->courses->first()->toArray());
    }

}
