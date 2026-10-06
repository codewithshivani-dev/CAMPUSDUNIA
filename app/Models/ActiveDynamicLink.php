<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActiveDynamicLink extends Model
{
     protected $table = 'active_dynamic_link';

    protected $fillable = ['link_type','token', 'expiry_type', 'expiry_value', 'expires_at', 'is_used'];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];
}