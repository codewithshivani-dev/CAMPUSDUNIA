<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FincapMerchant extends Model
{
    use HasFactory;
    protected $table = "fincap_merchant";

    protected $guarded = [];
}
