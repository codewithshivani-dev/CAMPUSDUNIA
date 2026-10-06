<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstituteBeneficiaryDetail extends Model
{
    use HasFactory;
    protected $table = "institute_beneficiary_details";

    protected $guarded = [];
    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id', 'fincap_merchant_id');
    }
}
