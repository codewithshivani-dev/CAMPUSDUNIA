<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentAssignmentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected Collection $assignments;

    public function __construct(Collection $assignments)
    {
        $this->assignments = $assignments;
    }

    public function collection()
    {
        return $this->assignments;
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Student Hash ID',
            'Course Type',
            'Sub Type',
            'Subject Type',
            'Total Subjects',
            'Assigned On',
        ];
    }

    public function map($assignment): array
    {
        return [
            $assignment->student_name ?? '',
            $assignment->student_hash_id ?? '',
            $assignment->course_type ?? '',
            $assignment->sub_type ?? '',
            $assignment->subject_type ?? '',
            $assignment->subject_count ?? 0,
            $assignment->assigned_date ?? '',
        
        ];
    }
}
