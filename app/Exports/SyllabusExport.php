<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SyllabusExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /*
    |--------------------------------------------------------------------------
    | DATA TRANSFORMATION
    |--------------------------------------------------------------------------
    */
    public function collection()
    {
        return collect($this->data)->map(function ($row) {

            // Convert topics array → readable string
            $topics = collect($row->topics)->map(function ($topic) {
                return $topic['topic_name']
                    . ' (' . $topic['book_name'] . ')'
                    . ' [' . $topic['start_date'] . ' → ' . $topic['end_date'] . ']';
            })->implode(' | ');

            return [
                'Syllabus ID'   => $row->syllabus_id,
                'Title'         => $row->title,
                'Subject'       => $row->subject_name,
                'Course'        => $row->course_type,
                'Course Sub Type'    => $row->sub_type,
                'Uploaded By'   => $row->employee_name,
                'Uploaded Date' => $row->uploaded_date,
                'Term Type'     => $row->term_type,
                'Term Value'    => $row->term_value,
                'Topics'        => $topics,
            ];
        });
    }

    /*
    |--------------------------------------------------------------------------
    | COLUMN HEADINGS
    |--------------------------------------------------------------------------
    */
    public function headings(): array
    {
        return [
            'Syllabus ID',
            'Title',
            'Subject',
            'Course',
            'Course Sub Type',
            'Uploaded By',
            'Uploaded Date',
            'Term Type',
            'Term Value',
            'Topics'
        ];
    }
}