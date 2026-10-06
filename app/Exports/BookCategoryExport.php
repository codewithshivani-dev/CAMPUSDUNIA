<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookCategoryExport implements FromCollection, WithHeadings
{
    protected $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    public function collection()
    {
        return $this->categories->map(function ($item) {
            return [
                $item->book_categories_id,
                $item->name,
                $item->description,
                optional($item->created_at)->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Book Category ID',
            'Category Name',
            'Description',
            'Created At',
        ];
    }
}