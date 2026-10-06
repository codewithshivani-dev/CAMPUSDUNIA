<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeHostelFee extends Model
{
    use HasFactory;
    protected $table = 'employee_hostel_fees';    
    protected $guarded = [];
    protected $casts = [
        'hostel_fee' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'maintenance_fee' => 'decimal:2',
        'utility_charges' => 'decimal:2',
        'total_fee_amount' => 'decimal:2',
        'late_fee_value' => 'decimal:2',
        'late_fee_amount' => 'decimal:2',
        'partially_fee_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'start_date' => 'date',
        'due_date' => 'date',
        'pay_date' => 'date',
    ];

    // Generate unique fee reference ID
    public static function generateFeeReferenceId($instituteId)
    {
        $prefix = 'HE';
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

    // Relationship with employee
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }
}