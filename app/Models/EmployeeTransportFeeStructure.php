<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeTransportFeeStructure extends Model
{
    use HasFactory;

    protected $table = 'employee_transport_fee_structures';

    protected $guarded = [];

    protected $casts = [
        'transport_fee' => 'decimal:2',
        'transport_total_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'late_fee_value' => 'decimal:2',
        'late_fee_amount' => 'decimal:2',
        'partially_fee_value' => 'decimal:2',
        'start_date' => 'date',
        'due_date' => 'date',
        'pay_date' => 'date'
    ];

    /**
     * Get the employee associated with this fee.
     */
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the institute associated with this fee.
     */
    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id', 'institute_id');
    }


    /**
     * Scope for pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope for overdue payments.
     */
    public function scopeOverdue($query)
    {
        return $query->where('payment_status', 'overdue');
    }

    /**
     * Scope for paid payments.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Check if payment is overdue.
     */
    public function getIsOverdueAttribute()
    {
        return $this->due_date < now() && $this->payment_status === 'pending';
    }
}