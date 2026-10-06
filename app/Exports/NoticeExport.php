<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NoticeExport implements FromCollection, WithHeadings
{
    protected $notices;

    public function __construct(Collection $notices)
    {
        $this->notices = $notices;
    }

    public function collection()
    {
        return $this->notices->map(function ($notice) {
            return [
                'Title' => $notice->title,
                'Content' => strip_tags($notice->content),
                'Notice Type' => $notice->notice_type,
                'Recipient Type' => ucfirst($notice->recipient_type),
                'Status' => ucfirst($notice->status),
                'For Employees' => $notice->employees,
                'Department Category' => $notice->department_category_id,
                'Department' => $notice->department_id,
                'Branch ID' => $notice->branch_id,
                'Attachment' => $notice->attachment,
                'Created Date' => optional($notice->created_at)->format('Y-m-d H:i'),
                'Updated Date' => optional($notice->updated_at)->format('Y-m-d H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Title',
            'Content',
            'Notice Type',
            'Recipient Type',
            'Status',
            'For Employees',
            'Department Category Id',
            'Department Id',
            'Branch ID',
            'Attachment',
            'Created Date',
            'Updated Date',
        ];
    }
}