<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommonCustomFees extends Model
{
    use HasFactory;

    protected $table = 'common_custom_fees';
   
    protected $guarded = [];

      /**
     * Generate unique fee reference ID
     */
    public static function generateReferenceId($instituteId)
    {
        $prefix = 'CF';
        $rand = strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 10));
       return "{$prefix}{$rand}";
    }

    /**
     * Calculate late fee amount based on type
     */
    public function calculateLateFee($baseAmount = 0)
    {
        if ($this->late_fee_type === 'fixed') {
            return $this->late_fee_value ?: 0;
        } elseif ($this->late_fee_type === 'percentage') {
            return ($baseAmount * $this->late_fee_value) / 100;
        }
        
        return 0;
    }

    /**
     * Get final fee after discount
     */
    public function getFinalFee($baseAmount = 0)
    {
        $lateFee = $this->calculateLateFee($baseAmount);
        $total = $baseAmount + $lateFee;
        
        return max(0, $total - $this->discount_amount);
    }

    /**
     * Scope for active fees
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope by institute
     */
    public function scopeByInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }

    /**
     * Scope by academic year
     */
    public function scopeByAcademicYear($query, $academicYear)
    {
        return $query->where('academic_year', $academicYear);
    }

    /**
     * Scope by fee type
     */
    public function scopeByFeeType($query, $feeType)
    {
        return $query->where('fee_type', $feeType);
    }

}