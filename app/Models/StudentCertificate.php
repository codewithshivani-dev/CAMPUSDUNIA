<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_hash_id',
        'institute_id',
        'registration_number',
        'authorized_signatory',
        'certificate_type',
        'certificate_number',
        'issue_date',
        'certificate_view',
        'certification_period',
        'character_grade',
        'start_date',
        'completion_date',
        'duration',
        'grade',
        'course_name',
        'relation_type',
        'parent_name',
        'purpose',
        'conduct_remarks',
        'organization_name',
        'organization_address_line1',
        'organization_address_line2',
        'leaving_reason',
        'leaving_date',
        'designation',
        'remarks',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'start_date' => 'date',
        'completion_date' => 'date',
        'leaving_date' => 'date',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }

    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id', 'fincap_merchant_id');
    }
}