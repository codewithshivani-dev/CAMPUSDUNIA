<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentEditLog extends Model
{
    protected $table = 'student_edit_logs';

    protected $fillable = [
        'institute_id',
        'lead_id',

        // Before
        'before_student_full_name',
        'before_student_dob',
        'before_student_gender',
        'before_student_nationality',
        'before_applying_for_grade',
        'before_email',
        'before_phone',

        'before_alternate_phone',
        'before_address',
        'before_city',
        'before_state',
        'before_pincode',

        'before_previous_school',
        'before_previous_class',
        'before_school_location',
        'before_percentage_cgpa',
        'before_marks_format',

        'before_sibling_option',
        'before_sibling_name',
        'before_sibling_class',
        'before_sibling_section',
        'before_sibling_admission_no',

        'before_father_name',
        'before_father_occupation',
        'before_father_phone',
        'before_father_email',

        'before_mother_name',
        'before_mother_occupation',
        'before_mother_phone',
        'before_mother_email',

        // After
        'after_student_full_name',
        'after_student_dob',
        'after_student_gender',
        'after_student_nationality',
        'after_applying_for_grade',
        'after_email',
        'after_phone',

        'after_alternate_phone',
        'after_address',
        'after_city',
        'after_state',
        'after_pincode',

        'after_previous_school',
        'after_previous_class',
        'after_school_location',
        'after_percentage_cgpa',
        'after_marks_format',

        'after_sibling_option',
        'after_sibling_name',
        'after_sibling_class',
        'after_sibling_section',
        'after_sibling_admission_no',

        'after_father_name',
        'after_father_occupation',
        'after_father_phone',
        'after_father_email',

        'after_mother_name',
        'after_mother_occupation',
        'after_mother_phone',
        'after_mother_email',
    ];
}