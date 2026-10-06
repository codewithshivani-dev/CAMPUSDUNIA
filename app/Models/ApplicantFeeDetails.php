<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantFeeDetails extends Model
{
    use HasFactory;
    protected $table = "applicant_fee_details";
    protected $guarded = [];
}
