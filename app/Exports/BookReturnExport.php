<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BookReturnExport implements FromCollection, WithHeadings, WithMapping
{
    protected $issues;

    public function __construct(Collection $issues)
    {
        $this->issues = $issues;
    }

    public function collection()
    {
        return $this->issues;
    }

    public function headings(): array
    {
        return [
            'Return ID',
            'Issue ID',
            'Book Title',
            'Issued To',
            'Copy Id',
            'Issue Date',
            'Due Date',
            'Returned On',
            'Fine Amount',
            'Status'
        ];
    }

    public function map($issue): array
    {
        return [
            optional($issue->return)->book_return_id ?? '—',
            $issue->id,
            optional($issue->libraryBook)->title,
            optional($issue->issueable)->first_name . ' ' . optional($issue->issueable)->last_name,
            optional($issue->copy)->copy_id,
            $issue->issue_date,
            $issue->due_date,
            optional($issue->return)->returned_on,
            optional($issue->return)->fine_amount ?? 0,
            $issue->status
        ];
    }
}