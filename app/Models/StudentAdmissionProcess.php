<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAdmissionProcess extends Model
{
    use HasFactory;

    protected $table = 'student_admission_process';

    protected $guarded = [];

    protected $casts = [
        'entrance_test_results' => 'array',
    ];

    public function admissionConfig()
    {
        return $this->belongsTo(AdmissionProcessConfig::class, 'admission_config_id');
    }

    public function counsellingSlot()
    {
        return $this->belongsTo(CounsellingTimeSlot::class, 'counselling_slot_id');
    }

    public function entranceTestSlot()
    {
        return $this->belongsTo(EntranceTestSlot::class, 'entrance_test_slot_id');
    }
    public function entranceTests()
    {
        return $this->hasMany(EntranceTest::class, 'reference_id', 'reference_id');
    }
}
