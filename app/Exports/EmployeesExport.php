<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class EmployeesExport implements FromCollection, WithHeadings
{
    protected $employees;

    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    public function collection()
    {
        return $this->employees->map(function ($emp) {

            return [
                $emp->id,
                $emp->employee_code,
                $emp->employee_id,
                $emp->name,
                $emp->email,
                $emp->mobile_number,
                $emp->gender,
                $emp->dob,
                $emp->nationality,
                $emp->religion,
                $emp->blood_group,

                $emp->department,
                $emp->designation,
                $emp->employment_type,
                $emp->salary_type,
                $emp->assigned_role,

                $emp->addressline1,
                $emp->addressline2,
                $emp->city,
                $emp->state,
                $emp->pincode,

                $emp->bank_name,
                $emp->branch_name,
                $emp->account_number,
                $emp->ifsc_code,

                $emp->emergency_contact_number,
                $emp->contact_person_name,
                $emp->relation_with_contact,

                $emp->reference_name,
                $emp->reference_contact_number,

                $emp->doj,
                $emp->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Employee Code',
            'Employee ID',
            'Name',
            'Email',
            'Mobile Number',
            'Gender',
            'Date of Birth',
            'Nationality',
            'Religion',
            'Blood Group',
            'Department',
            'Designation',
            'Employment Type',
            'Salary Type',
            'Assigned Role',
            'Address Line 1',
            'Address Line 2',
            'City',
            'State',
            'Pincode',
            'Bank Name',
            'Bank Branch',
            'Account Number',
            'IFSC Code',
            'Emergency Contact Number',
            'Contact Person Name',
            'Relation With Contact',
            'Reference Name',
            'Reference Contact Number',
            'Date Of Joining',
            'Status'
        ];
    }
}