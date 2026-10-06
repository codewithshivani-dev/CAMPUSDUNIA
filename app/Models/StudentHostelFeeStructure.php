<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentHostelFeeStructure extends Model
{
    protected $table = "student_hostel_fees";

    protected $guarded = [];

    public function studentDetail()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }

     // Generate unique fee reference ID
    public static function generateFeeReferenceId($instituteId)
    {
        $prefix = 'HS';
        $year = date('Y');
        $month = date('m');
        $count = self::where('institute_id', $instituteId)
            ->whereYear('created_at', $year)
            ->count();
        
        return "{$prefix}{$year}{$month}" . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    // Relationship with hostel
    public function hostel()
    {
        return $this->belongsTo(HostelFee::class, 'hostel_reference_id', 'hostel_reference_id');
    }

    // Relationship with student
    public function student()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }
    public function academic()
    {
        return $this->belongsTo(StudentAcademicTransportDetails::class, 'student_hash_id', 'student_hash_id');
    }
}
