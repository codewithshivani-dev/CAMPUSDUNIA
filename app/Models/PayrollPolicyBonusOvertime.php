<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollPolicyBonusOvertime extends Model
{
    use HasFactory;

    protected $table = 'payrollpolicy_bonus_overtime';
    protected $guarded = [];
    
    protected $fillable = [
        'payroll_policy_id',
        'institute_id',
        'branch_id',
        'overtime_enabled',
        'bonuses',
    ];

    protected $casts = [
        'bonuses' => 'array',
        'overtime_enabled' => 'integer',
    ];

    /**
     * Get the payroll policy that owns this bonus/overtime configuration.
     */
    public function payrollPolicy()
    {
        return $this->belongsTo(ProvidentFundPolicy::class, 'payroll_policy_id', 'payroll_policy_id');
    }

    /**
     * Get the institute that owns this configuration.
     */
    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }
}