<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StakeholderDocument extends Model
{
    use HasFactory;
    protected $table = "stakeholder_documents";

    protected $guarded = [];

     public function stakeholder()
    {
        return $this->belongsTo(Stakeholder::class);
    }
    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id', 'fincap_merchant_id');
    }
}
