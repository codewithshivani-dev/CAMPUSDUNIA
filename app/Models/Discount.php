<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discount extends Model
{
    use HasFactory;
    
    // Add this if you're not using auto-incrementing IDs
    public $incrementing = false;
    
    // Specify the key type
    protected $keyType = 'string';
    
    protected $table="discounts";
    
    protected $guarded = [];

    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2'
    ];

    public function assignments()
    {
        return $this->hasMany(DiscountAssignment::class,'discount_hash_id', 'discount_hash_id');
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class,'institute_id', 'institute_id');
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isValid()
    {
        $now = now();
        return $this->is_active 
            && $now->between($this->valid_from, $this->valid_to)
            && ($this->max_usage === null || $this->used_count < $this->max_usage);
    }

    public function studentTransportFeeAssignments()
    {
        return $this->hasMany(StudentTransportFeeStructure::class, 'discount_id', 'discount_hash_id');
    }

    // Relationship for employee transport fee assignments
    public function employeeTransportFeeAssignments()
    {
        return $this->hasMany(EmployeeTransportFeeStructure::class, 'discount_id', 'discount_hash_id');
    }

    // Get all transport fee assignments (both student and employee)
    public function transportFeeAssignments()
    {
        // Return a collection combining both types of assignments
        $studentAssignments = $this->studentTransportFeeAssignments;
        $employeeAssignments = $this->employeeTransportFeeAssignments;
        
        return $studentAssignments->merge($employeeAssignments);
    }

    // Get active transport fee assignments
    public function activeTransportAssignments()
    {
        $activeStudent = $this->studentTransportFeeAssignments()
            ->where('payment_status', 'active')
            ->get();
            
        $activeEmployee = $this->employeeTransportFeeAssignments()
            ->where('payment_status', 'active')
            ->get();
            
        return $activeStudent->merge($activeEmployee);
    }

    // Count of active transport assignments
    public function getActiveTransportAssignmentsCountAttribute()
    {
        $studentCount = $this->studentTransportFeeAssignments()
            ->where('payment_status', 'active')
            ->count();
            
        $employeeCount = $this->employeeTransportFeeAssignments()
            ->where('payment_status', 'active')
            ->count();
            
        return $studentCount + $employeeCount;
    }
}