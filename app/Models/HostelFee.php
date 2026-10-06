<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostelFee extends Model
{
       use HasFactory;
    protected $table = "hostel_fees";
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'base_fee' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'maintenance_fee' => 'decimal:2',
        'utility_charges' => 'decimal:2',
        'total_fee' => 'decimal:2',
        'late_fee_value' => 'decimal:2',
        'fee_breakdown' => 'array',
        'is_active' => 'boolean',
        'apply_late_fee' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
     // Room types
    public static $roomTypes = [
        'single' => 'Single Room',
        'double' => 'Double Sharing',
        'triple' => 'Triple Sharing',
        'dormitory' => 'Dormitory (4+ beds)',
    ];

    // Removed academicYear relationship

    /**
     * Accessors
     */
    public function getFormattedHostelTypeAttribute()
    {
        return match($this->hostel_type) {
            'boys' => 'Boys Hostel',
            'girls' => 'Girls Hostel',
            'co-ed' => 'Co-Educational Hostel',
            default => ucfirst($this->hostel_type),
        };
    }

    public function getFormattedFeeDurationAttribute()
    {
        return match($this->fee_duration) {
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'half_yearly' => 'Half Yearly',
            'yearly' => 'Yearly',
            default => ucfirst($this->fee_duration),
        };
    }

    public function getFormattedTotalFeeAttribute()
    {
        return '₹' . number_format($this->total_fee, 2);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForAcademicYear($query, $academicYear)
    {
        return $query->where('academic_year', $academicYear);
    }

    public function scopeByHostelType($query, $type)
    {
        return $query->where('hostel_type', $type);
    }

      // Generate reference ID
    public static function generateReferenceId($instituteId)
    {
        $year = date('Y');
        $month = date('m');
        $count = self::where('institute_id', $instituteId)
            ->whereYear('created_at', $year)
            ->count();
        
        return 'HOST-' . $year . $month . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    // Calculate total annual fee including charges
    public function getAnnualFeeAttribute()
    {
        return ($this->monthly_fee * 12) + $this->security_deposit + $this->maintenance_fee + $this->utility_charges;
    }

    // Calculate monthly total (monthly + pro-rated charges)
    public function getMonthlyTotalAttribute()
    {
        return $this->monthly_fee + ($this->security_deposit / 12) + ($this->maintenance_fee / 12) + ($this->utility_charges / 12);
    }

    // Check seat availability
    public function hasAvailableSeats()
    {
        return $this->available_seats > 0;
    }

    public function decreaseSeats()
    {
        if ($this->hasAvailableSeats()) {
            $this->decrement('available_seats');
            return true;
        }
        return false;
    }

    public function increaseSeats()
    {
        if ($this->available_seats < $this->total_capacity) {
            $this->increment('available_seats');
            return true;
        }
        return false;
    }

    // Get display name
    public function getDisplayNameAttribute()
    {
        $charges = [];
        if ($this->security_deposit > 0) $charges[] = "Deposit: ₹{$this->security_deposit}";
        if ($this->maintenance_fee > 0) $charges[] = "Maintenance: ₹{$this->maintenance_fee}";
        if ($this->utility_charges > 0) $charges[] = "Utilities: ₹{$this->utility_charges}";
        
        $chargesText = $charges ? ' (' . implode(', ', $charges) . ')' : '';
        
        return "{$this->hostel_name} ({$this->room_type}) - ₹{$this->monthly_fee}/month{$chargesText}";
    }
}