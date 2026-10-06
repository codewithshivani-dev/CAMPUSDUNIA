<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'institute_id',
        'branch_id',
        'department_id',
        'employee_id',
        'course_type_id',
        'semester_id',
        'subject_id',
        'title',
        'description',
        'assignment_file',
        'due_date',
    ];

    // An assignment belongs to one employee (teacher)
    // Relationship with Department
    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }

    // Relationship with Employee
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }

    // Relationship with Course Type (through ProductDetails)
    public function courseType()
    {
        return $this->belongsTo(ProductDetails::class, 'course_type_id', 'product_id');
    }

    // Relationship with Branch (Sub Type through ProductDetails)
    public function branchDetail()
    {
        return $this->belongsTo(ProductDetails::class, 'branch_id', 'product_id');
    }

    // Relationship with Subject
    public function subject()
    {
        return $this->belongsTo(SubjectsCoursewise::class, 'subject_id', 'subject_id');
    }

    // Relationship with Files
    public function files()
    {
        return $this->hasMany(EmployeeFile::class, 'assignment_id');
    }

    public function assignedStudents()
    {
        return $this->hasMany(AssignmentStudents::class, 'assignment_id', 'id')
                ->with('student'); // eager load parent details
    }

    // Add these relationships to your Assignment model
public function assignedStudentsWithStatus()
{
    return $this->hasMany(AssignmentStudents::class, 'assignment_id');
}


}
