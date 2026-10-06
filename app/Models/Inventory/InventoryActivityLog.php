<?php

namespace App\Models\Inventory;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class InventoryActivityLog extends Model
{
    protected $table = 'inventory_activity_logs';

    protected $guarded = [];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}