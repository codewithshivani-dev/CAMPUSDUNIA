<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTransportFeeStructure extends Model
{
    protected $table = "student_transport_fees";

    protected $guarded = [];

    public function studentDetail()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }
    public function transportStopId()
    {
        return $this->belongsTo(TransportationFee::class, 'fee_type_id', 'transport_reference_id');
    }
    public function academic()
    {
        return $this->belongsTo(StudentAcademicTransportDetails::class, 'student_hash_id', 'student_hash_id')
            ->whereColumn('academic_year_id', 'academic_transport_details.academic_year_id');
    }
    
}
