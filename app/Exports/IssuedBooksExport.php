<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IssuedBooksExport implements FromCollection, WithHeadings
{
    protected $issuedBooks;

    public function __construct(Collection $issuedBooks)
    {
        $this->issuedBooks = $issuedBooks;
    }

    public function collection()
    {
        return $this->issuedBooks;
    }

    public function headings(): array
    {
        return [
            'Book Issue ID',
            'Library Book ID',
            'Book Title',
            'Copy ID',
            'Issueable ID',
            'Issueable Type',
            'Issued To Name',
            'Issue Date',
            'Due Date',
            'Return Date',
            'Status',
            'Created At',
            'Updated At',
        ];
    }

}