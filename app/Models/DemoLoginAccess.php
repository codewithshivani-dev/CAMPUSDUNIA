<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemoLoginAccess extends Model
{
    use HasFactory;
    protected $table = "demo_login_access";

    protected $guarded = [];
}
