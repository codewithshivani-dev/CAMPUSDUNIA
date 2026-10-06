<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscountAssignment extends Model
{
    use HasFactory;

  
    
    protected $fillable = [
        'discount_hash_id',
        'assign_type',
        'department_category_id',
        'department_id',
        'course_id',
        'product_id',
        'student_id',
        'student_hash_id',
        'is_active',
        'usage_count',
        'assigned_at',
        'assigned_by'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_hash_id');
    }

    public function departmentCategory()
    {
        return $this->belongsTo(DepartmentCategory::class, 'department_category_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductDetails::class, 'product_id');
    }

    public function student()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}