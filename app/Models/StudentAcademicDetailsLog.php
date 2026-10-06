<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAcademicDetailsLog extends Model
{
    use HasFactory;

    protected $table = 'student_academic_details_logs';

    protected $fillable = [
        'institute_id',
        'student_hash_id',
        'user_id',
        'institute_id',
        'branch_id',
        
        'previous_department_id',
        'previous_department',
        'previous_department_category_id',
        'previous_course_type_id',
        'previous_course_type',
        'previous_course_subtype_id',
        'previous_course_subtype',
        'previous_session_id',
        'previous_semester_id',
        'previous_section_id',
        'previous_batch_id',
        'previous_batch',
        'previous_academic_year_id',
        'previous_academic_year',
        'previous_mode_of_course',
        'previous_mode_type',
        
        'new_department_id',
        'new_department',
        'new_department_category_id',
        'new_course_type_id',
        'new_course_type',
        'new_course_subtype_id',
        'new_course_subtype',
        'new_session_id',
        'new_semester_id',
        'new_section_id',
        'new_batch_id',
        'new_batch',
        'new_academic_year_id',
        'new_academic_year',
        'new_mode_of_course',
        'new_mode_type',
        
        'promotion_type',
        'promoted_by',
        'promoted_at'
    ];

    protected $casts = [
        'promoted_at' => 'datetime'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }

    public function promoter()
    {
        return $this->belongsTo(User::class, 'promoted_by');
    }
}