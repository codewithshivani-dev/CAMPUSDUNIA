<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetPaymentGatewayCharges extends Model
{
    use HasFactory;
    protected $table = "payment_gateway_charges_set";

    protected $guarded = [];
}
