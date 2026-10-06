<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseFeeStructure extends Model
{
    use HasFactory;

    protected $table = 'course_fee_structures';

    // protected $fillable = [
    //     'product_id',
    //     'branch_id',
    //     'department_id',
    //     'course_type',
    //     'sub_type',
    //     'academic_year',
    //     'batch_year',
    //     'batch_start_date',
    //     'batch_end_date',
    //     'session_range',
    //     'course_duration',
    //     'course_length',
    //     'mode_of_course',
    //     'mode_type',
    //     'total_fee',
    //     'is_active',
    //     'course_fee',
    //     'hostel_fee',
    //     'transportation_fee',
    //     'registration_fee',
    //     'miscellaneous_fee',
    //     'custom_fees'
    // ];
    
    protected $guarded = [];

    protected $casts = [
        'batch_start_date' => 'date',
        'batch_end_date' => 'date',
        'is_active' => 'boolean',
        'course_fee' => 'array',
        'hostel_fee' => 'array',
        'transportation_fee' => 'array',
        'registration_fee' => 'array',
        'miscellaneous_fee' => 'array',
        'custom_fees' => 'array',
    ];

    // Relationship with product_details
    public function productDetail()
    {
        return $this->belongsTo(ProductDetails::class, 'product_id', 'product_id');
    }

    // Scope for active fee structures
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for specific academic year
    public function scopeForAcademicYear($query, $academicYear)
    {
        return $query->where('academic_year', $academicYear);
    }

    // Scope for specific batch
    public function scopeForBatch($query, $batchYear)
    {
        return $query->where('batch_year', $batchYear);
    }
}