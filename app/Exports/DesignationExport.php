<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DesignationExport implements FromCollection, WithHeadings
{
    protected $designations;

    public function __construct($designations)
    {
        $this->designations = $designations;
    }

    /*
    |--------------------------------------
    | Export only table columns
    |--------------------------------------
    */
    public function collection()
    {
        return $this->designations->map->getAttributes();
    }

    /*
    |--------------------------------------
    | Readable column headings
    |--------------------------------------
    */
    public function headings(): array
    {
        if ($this->designations->isEmpty()) {
            return [];
        }

        return array_map(function ($column) {
            return ucwords(str_replace('_', ' ', $column));
        }, array_keys($this->designations->first()->getAttributes()));
    }
}
