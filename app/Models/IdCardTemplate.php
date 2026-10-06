<?php
// app/Models/IdCardTemplate.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IdCardTemplate extends Model
{
    protected $table = 'id_card_templates';
    
    protected $guarded = [];

    protected $casts = [
        'field_settings' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function generatedCards()
    {
        return $this->hasMany(GeneratedIdCard::class);
    }
}