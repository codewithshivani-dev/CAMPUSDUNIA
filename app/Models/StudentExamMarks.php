<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExamMarks extends Model
{
    use HasFactory;

    protected $table = 'student_exam_marks';
    
    protected $primaryKey = 'exam_mark_id';
    
    protected $guarded = [];
    protected $casts = [
        'total_marks' => 'decimal:2',
        'obtained_marks' => 'decimal:2',
        'passing_marks' => 'decimal:2',
        'marked_date' => 'datetime'
    ];

    /**
     * Relationships
     */
    public function exam()
    {
        return $this->belongsTo(ExamStructureOfflineExam::class, 'exam_id', 'exam_id');
    }

    public function student()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }

    public function subject()
    {
        return $this->belongsTo(SubjectsCoursewise::class, 'subject_id', 'subject_id');
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id', 'institute_id');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }

    public function course()
    {
        return $this->belongsTo(ProductDetails::class, 'course_id', 'product_id');
    }

    /**
     * Scopes
     */
    public function scopeForInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }

    public function scopeForExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }

    public function scopeForStudent($query, $studentHashId)
    {
        return $query->where('student_hash_id', $studentHashId);
    }

    public function scopePassed($query)
    {
        return $query->whereColumn('obtained_marks', '>=', 'passing_marks');
    }

    public function scopeFailed($query)
    {
        return $query->whereColumn('obtained_marks', '<', 'passing_marks');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Accessors
     */
    public function getPercentageAttribute()
    {
        if ($this->total_marks > 0) {
            return round(($this->obtained_marks / $this->total_marks) * 100, 2);
        }
        return 0;
    }

    public function getResultAttribute()
    {
        return $this->obtained_marks >= $this->passing_marks ? 'Pass' : 'Fail';
    }

    public function getMarkedByNameAttribute()
    {
        if ($this->markedBy) {
            return $this->markedBy->name;
        }
        return 'N/A';
    }

    /**
     * Mutators
     */
    public function setObtainedMarksAttribute($value)
    {
        $this->attributes['obtained_marks'] = round($value, 2);
    }

    public function setTotalMarksAttribute($value)
    {
        $this->attributes['total_marks'] = round($value, 2);
    }

    public function setPassingMarksAttribute($value)
    {
        $this->attributes['passing_marks'] = round($value, 2);
    }

    /**
     * Business Logic Methods
     */
    public function isPassed()
    {
        return $this->obtained_marks >= $this->passing_marks;
    }

    public function calculateGrade()
    {
        $percentage = $this->percentage;
        
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($this->isPassed()) return 'C';
        return 'F';
    }

    public function updateGrade()
    {
        $this->grade = $this->calculateGrade();
        return $this;
    }
}