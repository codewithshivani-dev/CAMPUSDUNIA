<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoryDepartmentExport implements FromCollection, WithHeadings
{
    protected $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    public function collection()
    {
        return $this->categories->map(function ($category) {
            return [
                'Category ID'   => $category->department_category_id,
                'Category Name' => $category->category_name,
                'Departments'   => $category->departments->pluck('department')->implode(', '),
                'Total Departments' => $category->departments_count,
                'Description' => $category->description,
                'Status'        => $category->status,
                'Created At'    => $category->created_at,
            ];
        });
    }

    public function headings(): array
    {
        if ($this->categories->isEmpty()) {
            return [];
        }

        return array_keys($this->collection()->first());
    }
}
