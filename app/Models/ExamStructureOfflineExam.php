<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamStructureOfflineExam extends Model
{
    use HasFactory;

    protected $table = 'exam_structure_offline_exams';

    protected $guarded = [];

    protected $casts = [
        'exam_date'       => 'date',
        'start_time'      => 'datetime:H:i',
        'reporting_time'  => 'datetime:H:i',
        'end_time'       => 'datetime:H:i',
        'published_at'   => 'datetime',
        'is_published'   => 'boolean',
        'total_marks'    => 'integer',
        'passing_marks'  => 'integer',
        'duration_minutes' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Department
    |--------------------------------------------------------------------------
    */

    public function department()
    {
        return $this->belongsTo(
            Departments::class,
            'department_id',
            'department_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Course
    |--------------------------------------------------------------------------
    */

    public function course()
    {
        return $this->belongsTo(
            ProductDetails::class,
            'subtype_id',
            'product_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Subject
    |--------------------------------------------------------------------------
    */

    public function subject()
    {
        return $this->belongsTo(
            SubjectsCoursewise::class,
            'subject_id',
            'subject_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Department Category
    |--------------------------------------------------------------------------
    */

    public function departmentCategory()
    {
        return $this->belongsTo(
            DepartmentCategory::class,
            'department_category_id',
            'department_category_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Classroom
    |--------------------------------------------------------------------------
    */

    public function classroom()
    {
        return $this->belongsTo(
            Classroom::class,
            'classroom_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Invigilators
    |--------------------------------------------------------------------------
    */

    public function invigilators()
    {
        return $this->hasMany(
            Invigilator::class,
            'exam_structure_offline_exam_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Creator
    |--------------------------------------------------------------------------
    */

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Updater
    |--------------------------------------------------------------------------
    */

    public function updater()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Exam Name
    |--------------------------------------------------------------------------
    */

    public function examNameDetail()
    {
        return $this->belongsTo(
            ExamName::class,
            'exam_name_id',
            'exam_name_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Exam Instructions
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | exam_structure_offline_exams.id
    |             ↓
    | exam_instructions.exam_structure_offline_exam_id
    |
    |--------------------------------------------------------------------------
    */

    public function instructions()
    {
        return $this->hasMany(
            ExamInstruction::class,
            'exam_structure_offline_exam_id',
            'id'
        )
        ->orderBy('sort_order', 'asc')
        ->orderBy('id', 'asc');
    }

    /*
    |--------------------------------------------------------------------------
    | Grade System
    |--------------------------------------------------------------------------
    */

    public function gradeSystem()
    {
        return $this->belongsTo(
            GradeSystem::class,
            'grade_system_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePublished($query)
    {
        return $query->where(
            'is_published',
            true
        );
    }

    public function scopeUpcoming($query)
    {
        return $query
            ->whereDate(
                'exam_date',
                '>=',
                now()->toDateString()
            )
            ->where(
                'status',
                'scheduled'
            );
    }

    public function scopeByDepartment(
        $query,
        $departmentId
    ) {
        return $query->where(
            'department_id',
            $departmentId
        );
    }

    public function scopeByCourse(
        $query,
        $courseId
    ) {
        return $query->where(
            'course_id',
            $courseId
        );
    }

    public function scopeBySubject(
        $query,
        $subjectId
    ) {
        return $query->where(
            'subject_id',
            $subjectId
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Formatted Date
    |--------------------------------------------------------------------------
    */

    public function getFormattedDateAttribute()
    {
        return $this->exam_date
            ? $this->exam_date->format('l, F j, Y')
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Formatted Start Time
    |--------------------------------------------------------------------------
    */

    public function getFormattedStartTimeAttribute()
    {
        return $this->start_time
            ? $this->start_time->format('h:i A')
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Formatted Reporting Time
    |--------------------------------------------------------------------------
    */

    public function getFormattedReportingTimeAttribute()
    {
        return $this->reporting_time
            ? $this->reporting_time->format('h:i A')
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Formatted End Time
    |--------------------------------------------------------------------------
    */

    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time
            ? $this->end_time->format('h:i A')
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Duration
    |--------------------------------------------------------------------------
    */

    public function getDurationHoursAttribute()
    {
        return $this->duration_minutes
            ? $this->duration_minutes / 60
            : 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Upcoming
    |--------------------------------------------------------------------------
    */

    public function getIsUpcomingAttribute()
    {
        if (!$this->exam_date || !$this->start_time) {
            return false;
        }

        return $this->exam_date->toDateString()
                > now()->toDateString()
            ||
            (
                $this->exam_date->toDateString()
                === now()->toDateString()
                &&
                $this->start_time->format('H:i:s')
                    > now()->format('H:i:s')
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Ongoing
    |--------------------------------------------------------------------------
    */

    public function getIsOngoingAttribute()
    {
        if (
            !$this->exam_date ||
            !$this->start_time ||
            !$this->end_time
        ) {
            return false;
        }

        $now = now();

        return
            $this->exam_date->toDateString()
                === $now->toDateString()
            &&
            $this->start_time->format('H:i:s')
                <= $now->format('H:i:s')
            &&
            $this->end_time->format('H:i:s')
                >= $now->format('H:i:s');
    }

    /*
    |--------------------------------------------------------------------------
    | Publish
    |--------------------------------------------------------------------------
    */

    public function publish()
    {
        $this->update([
            'is_published' => true,
            'published_at' => now(),
            'status'       => 'scheduled',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Unpublish
    |--------------------------------------------------------------------------
    */

    public function unpublish()
    {
        $this->update([
            'is_published' => false,
            'published_at' => null,
            'status'       => 'draft',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Calculate Grade
    |--------------------------------------------------------------------------
    */

    public function calculateStudentGrade(
        $percentage
    ) {
        if (!$this->grade_system_id) {
            return $this->getDefaultGrade(
                $percentage
            );
        }

        $gradeSystem = $this->gradeSystem;

        if (
            $gradeSystem &&
            isset($gradeSystem->grade_ranges)
        ) {
            foreach (
                $gradeSystem->grade_ranges
                as $range
            ) {
                if (
                    $percentage >=
                        $range['min_percentage']
                    &&
                    $percentage <=
                        $range['max_percentage']
                ) {
                    return $range['grade'];
                }
            }
        }

        return $this->getDefaultGrade(
            $percentage
        );
    }

    private function getDefaultGrade(
        $percentage
    ) {
        if ($percentage >= 90) {
            return 'A+';
        }

        if ($percentage >= 80) {
            return 'A';
        }

        if ($percentage >= 70) {
            return 'B+';
        }

        if ($percentage >= 60) {
            return 'B';
        }

        if ($percentage >= 50) {
            return 'C+';
        }

        if ($percentage >= 40) {
            return 'C';
        }

        return 'F';
    }
}

