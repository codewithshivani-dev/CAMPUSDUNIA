<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DepartmentCustomFeeAssignment extends Model
{
    use HasFactory;

    protected $table = 'department_custom_fee_assignments';

    protected $fillable = [
        'institute_id',
        'branch_id',
        'department_id',
        'course_id',
        'custom_reference_id',
        'custom_fee_key',
        'custom_fee_value',
        'assignment_type',
        'selected_classes',
        'academic_year',
        'fee_type',
        'fee_duration_type',
        'total_installments',
        'total_fee_amount',
        'late_fee_type',
        'late_fee_value',
        'late_fee_amount',
        'partially_fee_type',
        'partially_fee_value',
        'partially_fee_amount',
        'discount_id',
        'discount_coupon_code',
        'discount_type',
        'discount_value',
        'discount_amount',
        'discount_applicability',
        'discount_duration_type',
        'discount_reason',
        'status',
    ];

    protected $casts = [
        'custom_fee_value' => 'decimal:2',
        'total_fee_amount' => 'decimal:2',
        'late_fee_value' => 'decimal:2',
        'late_fee_amount' => 'decimal:2',
        'partially_fee_value' => 'decimal:2',
        'partially_fee_amount' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'selected_classes' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the department associated with this assignment.
     */
    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }

    /**
     * Get the course associated with this assignment.
     */
    public function course()
    {
        return $this->belongsTo(\App\Models\FincapMerchantSubCategories::class, 'course_id', 'finacp_merchant_sub_category_id');
    }

    /**
     * Get the custom fee details.
     */
    public function customFee()
    {
        return $this->belongsTo(CommonCustomFees::class, 'custom_reference_id', 'custom_reference_id');
    }

    /**
     * Get the discount details.
     */
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'discount_hash_id');
    }

    /**
     * Scope a query to only include active assignments.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include assignments for a specific institute.
     */
    public function scopeForInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }

    /**
     * Scope a query to only include assignments for a specific department.
     */
    public function scopeForDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    /**
     * Scope a query to only include assignments for a specific course.
     */
    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    /**
     * Scope a query to only include whole department assignments.
     */
    public function scopeWholeDepartment($query)
    {
        return $query->where('assignment_type', 'whole_department');
    }

    /**
     * Scope a query to only include class-wise assignments.
     */
    public function scopeClassWise($query)
    {
        return $query->where('assignment_type', 'class_wise');
    }

    /**
     * Get the formatted fee amount.
     */
    protected function formattedFeeAmount(): Attribute
    {
        return new Attribute(
            get: fn () => '₹' . number_format($this->total_fee_amount, 2)
        );
    }

    /**
     * Get the classes as an array.
     */
    protected function classesArray(): Attribute
    {
        return new Attribute(
            get: function () {
                $classes = $this->selected_classes ?? [];
                
                if (is_string($classes)) {
                    $classes = json_decode($classes, true) ?? [];
                }
                
                return $classes;
            }
        );
    }


    /**
     * Get the payment duration in a readable format.
     */
    protected function paymentDuration(): Attribute
    {
        return new Attribute(
            get: function () {
                $durations = [
                    'one_time' => 'One Time',
                    'monthly' => 'Monthly',
                    'quarterly' => 'Quarterly',
                    'half_yearly' => 'Half Yearly',
                    'yearly' => 'Yearly',
                ];
                
                return $durations[$this->fee_duration_type] ?? 'One Time';
            }
        );
    }

    /**
     * Get the assignment type in a readable format.
     */
    protected function assignmentTypeFormatted(): Attribute
    {
        return new Attribute(
            get: function () {
                $types = [
                    'whole_department' => 'Whole Department',
                    'class_wise' => 'Class Wise',
                ];
                
                return $types[$this->assignment_type] ?? 'Whole Department';
            }
        );
    }

    /**
     * Check if this is a class-wise assignment.
     */
    protected function isClassWise(): Attribute
    {
        return new Attribute(
            get: fn () => $this->assignment_type === 'class_wise'
        );
    }

    /**
     * Check if this is a whole department assignment.
     */
    protected function isWholeDepartment(): Attribute
    {
        return new Attribute(
            get: fn () => $this->assignment_type === 'whole_department'
        );
    }

    /**
     * Get the total payable amount after discount.
     */
    protected function payableAmount(): Attribute
    {
        return new Attribute(
            get: fn () => max(0, $this->total_fee_amount - $this->discount_amount)
        );
    }

    /**
     * Get the course name if available.
     */
    protected function courseName(): Attribute
    {
        return new Attribute(
            get: function () {
                if ($this->course_id && $this->course) {
                    return $this->course->finacp_merchant_sub_category_type;
                }
                
                return null;
            }
        );
    }

    /**
     * Get the selected classes names.
     */
    protected function selectedClassNames(): Attribute
    {
        return new Attribute(
            get: function () {
                $classIds = $this->classes_array;
                $classNames = [];
                
                if (!empty($classIds)) {
                    $courses = \App\Models\FincapMerchantSubCategories::whereIn('finacp_merchant_sub_category_id', $classIds)
                        ->get()
                        ->pluck('finacp_merchant_sub_category_type', 'finacp_merchant_sub_category_id')
                        ->toArray();
                    
                    foreach ($classIds as $classId) {
                        if (isset($courses[$classId])) {
                            $classNames[] = $courses[$classId];
                        } else {
                            $classNames[] = "Class ID: {$classId}";
                        }
                    }
                }
                
                return $classNames;
            }
        );
    }

    /**
     * Get the discount percentage if applicable.
     */
    protected function discountPercentage(): Attribute
    {
        return new Attribute(
            get: function () {
                if ($this->discount_amount > 0 && $this->total_fee_amount > 0) {
                    return round(($this->discount_amount / $this->total_fee_amount) * 100, 2);
                }
                return 0;
            }
        );
    }

    /**
     * Get the discount details.
     */
    protected function discountDetails(): Attribute
    {
        return new Attribute(
            get: function () {
                if ($this->discount) {
                    return [
                        'name' => $this->discount->name,
                        'code' => $this->discount->coupon_code,
                        'type' => $this->discount->type,
                        'value' => $this->discount->value,
                    ];
                }
                return null;
            }
        );
    }

    /**
     * Get the formatted installment dates.
     */
    protected function formattedInstallmentDates(): Attribute
    {
        return new Attribute(
            get: function () {
                $installments = $this->installments_array;
                $formatted = [];
                
                foreach ($installments as $installment) {
                    if (isset($installment['start_date']) && isset($installment['due_date'])) {
                        $formatted[] = [
                            'installment_name' => $installment['installment_name'] ?? 'Installment',
                            'start_date' => \Carbon\Carbon::parse($installment['start_date'])->format('d M Y'),
                            'due_date' => \Carbon\Carbon::parse($installment['due_date'])->format('d M Y'),
                            'amount' => '₹' . number_format($installment['amount'] ?? 0, 2),
                        ];
                    }
                }
                
                return $formatted;
            }
        );
    }
}