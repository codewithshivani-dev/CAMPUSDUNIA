<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntranceTest extends Model
{
    use HasFactory;

    protected $table = 'entrance_tests';

    protected $fillable = [
        'lead_id',
        'reference_id',
        'institute_id',
        'branch_id',
        'admission_config_id',
        'registration_date',
        'registration_payment',
        'test_id',
        'entrance_payment',
        'entrance_test_fee',
        'submission_date',
        'payment_date',
        'entrance_test_date',
        'entrance_test_time',
        'test_name',
        'test_marks',
        'test_obtained_marks',
        'test_passing_marks',
        'test_status',
        'entrance_test_slot_id',
        'test_payment',
        'test_reattempt_date',
        'entrance_fee_transaction_id',
        'entrance_payment_type',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'lead_id' => 'integer',
        'test_id'=> 'integer',
        'institute_id' => 'integer',
        'branch_id' => 'integer',
        'admission_config_id' => 'integer',
        'registration_date' => 'date',
        'registration_payment' => 'decimal:2',
        'entrance_payment' => 'decimal:2',
        'entrance_test_fee' => 'decimal:2',
        'submission_date' => 'date',
        'payment_date' => 'date',
        'entrance_test_date' => 'date',
        'entrance_test_time' => 'string',
        'test_marks' => 'decimal:2',
        'test_obtained_marks' => 'decimal:2',
        'test_passing_marks' => 'decimal:2',
        'test_status' => 'string',
        'entrance_test_slot_id' => 'integer',
        'test_payment' => 'string',
        'test_reattempt_date' => 'date',
        'entrance_fee_transaction_id' => 'string',
        'entrance_payment_type' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Optional: Define relationships
    public function lead()
    {
        return $this->belongsTo(Lead::class); 
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function admissionConfig()
    {
        return $this->belongsTo(AdmissionConfig::class);
    }

    public function entranceTestSlot()
    {
        return $this->belongsTo(EntranceTestSlot::class);
    }

    // Optional: Accessor for test status badge
    public function getTestStatusBadgeAttribute()
    {
        return match($this->test_status) {
            'passed' => '<span class="badge bg-success">Passed</span>',
            'failed' => '<span class="badge bg-danger">Failed</span>',
            'absent' => '<span class="badge bg-warning">Absent</span>',
            default => '<span class="badge bg-secondary">' . ucfirst($this->test_status) . '</span>',
        };
    }

    // Optional: Accessor for test payment badge
    public function getTestPaymentBadgeAttribute()
    {
        return match($this->test_payment) {
            'completed' => '<span class="badge bg-success">Completed</span>',
            'failed' => '<span class="badge bg-danger">Failed</span>',
            default => '<span class="badge bg-warning">Pending</span>',
        };
    }

    // Optional: Scope for pending tests
    public function scopePending($query)
    {
        return $query->where('test_status', 'pending');
    }

    // Optional: Scope for passed tests
    public function scopePassed($query)
    {
        return $query->where('test_status', 'passed');
    }

    // Optional: Check if test is passed
    public function isPassed()
    {
        return $this->test_status === 'passed';
    }

    // Optional: Calculate percentage
    public function getPercentageAttribute()
    {
        if ($this->test_marks && $this->test_marks > 0) {
            return round(($this->test_obtained_marks / $this->test_marks) * 100, 2);
        }
        return null;
    }
}