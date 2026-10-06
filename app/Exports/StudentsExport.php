<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $students;

    public function __construct($students)
    {
        $this->students = $students;
    }

    public function collection()
    {
        return $this->students;
    }

    public function headings(): array
    {
        return [

            // Student Basic
            'Registration No',
            'First Name',
            'Last Name',
            'DOB',
            'Gender',
            'Mobile',
            'Email',

            // Academic
            'Department',
            'Class',
            'Section',
            'Academic Year',

            // Father
            'Father Name',
            'Father Phone',
            'Father Occupation',

            // Mother
            'Mother Name',
            'Mother Phone',
            'Mother Occupation',

            // Guardian
            'Guardian Name',
            'Guardian Phone',

            // Address
            'Student City',
            'Student State',
            'Student Pincode',

            // Bank
            'Student Bank',
            'Student Account No',

            // Documents
            'Student Aadhaar',
            'Student PAN',
        ];
    }

    public function map($row): array
    {
        return [

            // Student
            $row->registration_number,
            $row->first_name,
            $row->last_name,
            $row->dob,
            $row->gender,
            $row->mobile,
            $row->email,

            // Academic
            optional($row->academicTransportDetails)->department,
            optional($row->academicTransportDetails)->course_subtype,
            optional($row->academicTransportDetails)->section_name ?? optional($row->academicTransportDetails)->section_id,
            optional($row->academicTransportDetails)->academic_year,
            // Use resolved section_name (attached by controller) if available

            // Father
            $row->father_first_name . ' ' . $row->father_last_name,
            $row->father_phone,
            $row->father_occupation,

            // Mother
            $row->mother_first_name . ' ' . $row->mother_last_name,
            $row->mother_phone,
            $row->mother_occupation,

            // Guardian
            $row->guardian_first_name . ' ' . $row->guardian_last_name,
            $row->guardian_phone,

            // Address
            optional($row->address)->student_perm_city,
            optional($row->address)->student_perm_state,
            optional($row->address)->student_perm_pincode,

            // Bank
            optional($row->bankAccount)->student_bank_name,
            optional($row->bankAccount)->student_bank_account_number,

            // Documents
            optional($row->documents)->student_aadhaar_number,
            optional($row->documents)->student_pan_number,
        ];
    }
}
