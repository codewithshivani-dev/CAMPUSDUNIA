<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatManagementHistory extends Model
{
    use HasFactory;

    protected $table = 'seat_management_history';

    protected $guarded = [];

    protected $casts = [
        'previous_section_seats' => 'json',
        'new_section_seats' => 'json',
    ];

    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'fincap_merchant_id', 'institute_id');
    }


    public function courseFeeStructure()
    {
        return $this->belongsTo(CourseFeeStructure::class);
    }

    public function student()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_id');
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}