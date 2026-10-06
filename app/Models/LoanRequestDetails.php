<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRequestDetails extends Model
{
    use HasFactory;
    protected $table = "loan_requests";

    protected $guarded = [];

}