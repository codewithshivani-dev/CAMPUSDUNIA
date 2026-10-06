<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stakeholder extends Model
{
     use HasFactory;
     protected $table = "stakeholders";
     protected $guarded = [];

    public function documents()
    {
        return $this->hasOne(StakeholderDocument::class);
    }

    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id', 'fincap_merchant_id');
    }
}