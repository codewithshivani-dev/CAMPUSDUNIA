<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FeeStructureExport implements FromCollection, WithHeadings
{
    protected $feeStructures;

    public function __construct($feeStructures)
    {
        $this->feeStructures = $feeStructures;
    }

    public function collection()
    {
        return $this->feeStructures->map(function ($item) {
            return (array) $item;   // 🔥 This exports ALL columns
        });
    }

    public function headings(): array
    {
        if ($this->feeStructures->isEmpty()) {
            return [];
        }

        return array_keys((array) $this->feeStructures->first());
    }
}


