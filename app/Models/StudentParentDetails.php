<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentParentDetails extends Model
{
    protected $table = "student_parent_details";

    protected $guarded = [];

    public function bookIssues()
    {
        return $this->morphMany(BooksIssues::class, 'issueable');
    }
     public function leaves()
    {
        return $this->hasMany(StudentLeave::class, 'student_hash_id', 'student_hash_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
    
    public function institute()
   {
       return $this->hasMany(InstituteBasicDetails::class, 'fincap_merchant_id', 'institute_id');
   }
    
    // Relationship with AcademicTransportDetails
    public function academicTransportDetails()
    {
        return $this->hasOne(StudentAcademicTransportDetails::class, 'student_hash_id', 'student_hash_id');
    }
  
     public function documents()
    {
        return $this->hasOne(StudentParentDocuments::class, 'student_hash_id', 'student_hash_id');
    }

     public function address()
    {
        return $this->hasOne(StudentParentAddress::class, 'student_hash_id', 'student_hash_id');
    }

     public function bankAccount()
    {
        return $this->hasOne(StudentParentBankAccount::class, 'student_hash_id', 'student_hash_id');
    }
    public function departmentCategory()
    {
        return $this->hasOneThrough(
            DepartmentCategory::class,
            StudentAcademicTransportDetails::class,
            'student_hash_id', // Foreign key on AcademicTransportDetails
            'department_category_id', // Foreign key on DepartmentCategory
            'student_hash_id', // Local key on StudentParentDetails
            'department_category_id' // Local key on AcademicTransportDetails
        );
    }

    public function shift()
    {
        return $this->belongsTo(Shifts::class, 'shift_id');
    }

    public function getEffectiveShiftAttribute()
    {
        // Check if student has individual shift assigned
        if ($this->shift_id) {
            return $this->shift;
        }
        
        // Check if department category has shift assigned
        if ($this->department_category_id) {
            $department = Department::where('department_category_id', $this->department_category_id)
                ->whereNotNull('shift_id')
                ->first();
            if ($department) {
                return $department->shift;
            }
        }
        
        return null;
    }
    // All student fee structure relation
    public function StudentCourseFeeStructure()
    {
        return $this->hasMany(StudentCourseFeeStructure::class, 'student_hash_id', 'student_hash_id');
    }
    
    public function StudentCustomFeestructure()
    {
        return $this->hasMany(StudentCustomFeestructure::class, 'student_hash_id', 'student_hash_id');
    }
    
    public function StudentHostelFeeStructure()
    {
        return $this->hasMany(StudentHostelFeeStructure::class, 'student_hash_id', 'student_hash_id');
    }
    
    public function StudentMiscellaneousFeeStructure()
    {
        return $this->hasMany(StudentMiscellaneousFeeStructure::class, 'student_hash_id', 'student_hash_id');
    }
    
    public function StudentRegistrationFeeStructure()
    {
        return $this->hasMany(StudentRegistrationFeeStructure::class, 'student_hash_id', 'student_hash_id');
    }
    
    public function StudentTransportFeeStructure()
    {
        return $this->hasMany(StudentTransportFeeStructure::class, 'student_hash_id', 'student_hash_id');
    }
    
    /**
     * Get the profile edits for the student.
     */
    public function profileEdits()
    {
        return $this->hasMany(StudentProfileEdit::class, 'student_hash_id', 'student_hash_id');
    }
    
    public function suspensionLogs()
    {
        return $this->hasMany(StudentSuspensionLog::class, 'student_hash_id', 'student_hash_id');
    }

    public function activeSuspension()
    {
        return $this->hasOne(StudentSuspensionLog::class, 'student_hash_id', 'student_hash_id')
            ->where('action', 'suspend')
            ->whereRaw('created_at > COALESCE((
                SELECT MAX(created_at) FROM student_suspension_logs AS l2
                WHERE l2.student_hash_id = student_suspension_logs.student_hash_id
                AND l2.action = "unsuspend"
            ), "1900-01-01")');
    }

    public function suspensionCount()
    {
        return $this->hasMany(StudentSuspensionLog::class, 'student_hash_id', 'student_hash_id')
            ->where('action', 'suspend');
    }

    public function getTotalSuspensionsAttribute()
    {
        return $this->suspensionLogs()->where('action', 'suspend')->count();
    }

    public function getLastSuspensionAttribute()
    {
        return $this->suspensionLogs()->where('action', 'suspend')->latest()->first();
    }
    
    public function rollNumber()
    {
        return $this->hasOne(StudentRollNumber::class, 'student_hash_id', 'student_hash_id')
            ->where('status', 'active');
    }
}
