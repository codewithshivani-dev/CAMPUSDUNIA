<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class StudentCourseDiscounts extends Model
{
    use HasFactory;
    protected $table = 'student_course_discounts';
    protected $guarded = [];
   
    protected $casts = [
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function student()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }

    public function appliedBy()
    {
        return $this->belongsTo(User::class, 'applied_by');
    }


    public function product()
    {
        return $this->belongsTo(ProductDetails::class, 'product_id', 'product_id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'discount_hash_id');
    }

    public function courseFee()
    {
        return $this->belongsTo(CourseFeeStructure::class, 'product_id', 'product_id');
    }


}