<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LibraryBookExport implements FromCollection, WithHeadings
{
    protected $books;

    public function __construct($books)
    {
        $this->books = $books;
    }

    public function collection()
    {
        return $this->books->map(function ($book) {
            return [
                $book->librarybook_id,
                $book->book_categories_id,
                $book->title,
                $book->subject,
                $book->writer_name,
                $book->class,
                $book->total_copies,
                $book->available_copies,
                $book->status,
                optional($book->created_at)->format('Y-m-d H:i:s'),
                optional($book->updated_at)->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Library Book ID',
            'Category ID',
            'Title',
            'Subject',
            'Writer Name',
            'Class',
            'Total Copies',
            'Available Copies',
            'Status',
            'Created At',
            'Updated At',
        ];
    }
}