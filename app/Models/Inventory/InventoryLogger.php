<?php
// app/Services/Inventory/InventoryLogger.php

namespace App\Services\Inventory;

use App\Models\Inventory\InventoryActivityLog;

class InventoryLogger
{
    public static function log(array $data)
    {
        return InventoryActivityLog::create([
            'institute_id' => auth()->user()->institute_id ?? null,
            'module' => $data['module'] ?? 'INVENTORY',
            'action' => $data['action'] ?? 'UNKNOWN',
            'record_id' => $data['record_id'] ?? null,
            'old_data' => $data['old_data'] ?? null,
            'new_data' => $data['new_data'] ?? null,
            'remarks' => $data['remarks'] ?? null,
            'created_by' => auth()->id()
        ]);
    }
}