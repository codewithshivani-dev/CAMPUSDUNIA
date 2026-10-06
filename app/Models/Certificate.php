<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;
    
    protected $table = 'bonafied_certificates';
    
    protected $fillable = [
        'institute_id',
        'registration_number',
        'student_hash_id',
        'student_name',
        
        // Certificate numbers
        'bonafide_cf_num',
        'tc_cf_num',
        'sch_leaving_cf_num',
        'course_com_cf_num',
        'character_cf_num',
        
        // Separate issue dates for each certificate
        'bonafide_issue_date',
        'character_issue_date',
        'tc_issue_date',
        'sch_leaving_issue_date',
        'course_com_issue_date',
        
        // Common fields
        'academic_session',
        
        // Character certificate specific fields
        'father_name',
        'mother_name',
        'class_name',
        'address',
        'character_grade',
        'conduct_remarks',
        'authorized_signatory',
        'designation',
        'institute_name',
        'date_of_birth'
    ];
    
    protected $casts = [
        'bonafide_issue_date' => 'date',
        'character_issue_date' => 'date',
        'tc_issue_date' => 'date',
        'sch_leaving_issue_date' => 'date',
        'course_com_issue_date' => 'date',
        'dob' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}