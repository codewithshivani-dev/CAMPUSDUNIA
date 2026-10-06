<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExamOfflineExport implements FromCollection, WithHeadings
{
    protected $exams;

    public function __construct(Collection $exams)
    {
        $this->exams = $exams;
    }

    public function collection()
    {
        return $this->exams->map(function ($exam) {
            return [
                'Exam Name ID' => $exam->exam_name_id,
                'Academic Year' => $exam->academic_year,
                'Department Category' => optional($exam->departmentCategory)->department_category_id,
                'Department' => optional($exam->department)->department,
                'Course' => optional($exam->course)->course_name,
                'Subject' => optional($exam->subject)->subject_name,
                'Sections' => $exam->section_names ?? null,
                'Exam Name' => $exam->exam_name,
                'Exam Code' => $exam->exam_id,
                'Exam Type' => $exam->exam_type,
                'Exam Date' => $exam->exam_date,
                'Start Time' => $exam->start_time,
                'End Time' => $exam->end_time,
                'Duration (Minutes)' => $exam->duration_minutes,
                'Classroom ID' => $exam->classroom_id,
                'Total Marks' => $exam->total_marks,
                'Passing Marks' => $exam->passing_marks,
                'Instructions' => $exam->instructions,
                'Syllabus' => $exam->syllabus,
                'Status' => $exam->status,
                'Published' => $exam->is_published ? 'Yes' : 'No',
                'Published At' => $exam->published_at,
                'Created At' => $exam->created_at,
                'Updated At' => $exam->updated_at,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Exam Name ID',
            'Academic Year',
            'Department Category',
            'Department',
            'Course',
            'Subject',
            'Sections',
            'Exam Name',
            'Exam Code',
            'Exam Type',
            'Exam Date',
            'Start Time',
            'End Time',
            'Duration (Minutes)',
            'Classroom ID',
            'Total Marks',
            'Passing Marks',
            'Instructions',
            'Syllabus',
            'Status',
            'Published',
            'Published At',
            'Created At',
            'Updated At',
        ];
    }
}