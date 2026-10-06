<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $table = "leads";
    protected $guarded = [];

    public function AdmissionRegistration()
    {
        return $this->belongsTo(AdmissionRegistration::class, 'reference_id', 'reference_id');
    }
    public function InterviewRegistration()
    {
        return $this->belongsTo(InterviewRegistration::class, 'reference_id', 'reference_id');
    }
    
    public function studentAdmissionProcess()
    {
        return $this->hasOne(
            StudentAdmissionProcess::class,
            'reference_id',   // foreign key in student_admission_process table
            'reference_id'    // local key in leads table
        );
    }
  public function roundStatuses()
    {
        return $this->hasMany(RoundStatus::class, 'lead_id', 'lead_id');
    }
public function department()
    {
        return $this->belongsTo(
            Departments::class,
            'department_id',
            'department_id'
        );
    }
    public function merchantSubCategory()
    {
        return $this->belongsTo(
            FincapMerchantSubCategories::class,
            'applying_for_grade',
            'finacp_merchant_sub_category_id'
        );
    }
    public function logs()
    {
        return $this->hasMany(LeadLog::class);
    }
}
