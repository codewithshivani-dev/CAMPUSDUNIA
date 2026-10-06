<?php
// app/Models/EmployeeResponsibility.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeResponsibility extends Model
{
    protected $fillable = [
        'employee_id',
        'menu_item_id',
        'can_view',
        'can_create',
        'can_edit',
        'can_delete'
    ];

    protected $casts = [
        'can_view' => 'boolean',
        'can_create' => 'boolean',
        'can_edit' => 'boolean',
        'can_delete' => 'boolean'
    ];

    /**
     * Get the employee
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeeDetails::class);
    }

    /**
     * Get the menu item
     */
    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    /**
     * Check if employee has any permission
     */
    public function hasAnyPermission(): bool
    {
        return $this->can_view || $this->can_create || $this->can_edit || $this->can_delete;
    }
}