<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class EmployeeSubjectAssignmentsExport implements FromCollection, WithHeadings
{
    protected $assignments;

    public function __construct(Collection $assignments)
    {
        $this->assignments = $assignments;
    }

    /**
     * Export ALL backend data
     */
    public function collection()
    {
        return $this->assignments->map(function ($row) {
            return (array) $row;   // ✅ EVERYTHING FROM BACKEND
        });
    }

    /**
     * Auto headings from backend keys
     */
    public function headings(): array
    {
        if ($this->assignments->isEmpty()) {
            return [];
        }

        return array_keys((array) $this->assignments->first());
    }
}
