<?php
// app/Models/MenuItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
class MenuItem extends Model
{
    protected $fillable = [
        'name',
        'route',
        'icon',
        'parent_id',
        'order',
        'is_active',
        'permission_name',
        'description',
        'allowed_roles'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'allowed_roles' => 'array',
        'order' => 'integer'
    ];

    /**
     * Get the parent menu item
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Get the child menu items
     */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
                    ->where('is_active', true)
                    ->orderBy('order');
    }

    /**
     * Get all children recursively
     */
    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    /**
     * Get employees assigned to this menu item
     */
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(EmployeeDetails::class, 'employee_responsibilities')
                    ->withPivot(['can_view', 'can_create', 'can_edit', 'can_delete'])
                    ->withTimestamps();
    }

    /**
     * Scope active menu items
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope root menu items (no parent)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Check if menu item has children
     */
    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get full permission name
     */
    public function getFullPermissionAttribute(): string
    {
        return $this->permission_name ?: 'menu.' . str_slug($this->name);
    }

    //////////////////////////

    // Relationships
    // public function parent()
    // {
    //     return $this->belongsTo(MenuItem::class, 'parent_id');
    // }

    // public function newChildren()
    // {
    //     return $this->hasMany(MenuItem::class, 'parent_id')
    //                By('display_order');
    // }

    // public function allChildren()
    // {
    //     return $this->children()->with('allChildren');
    // }

    // Scopes
    // public function scopeActive($query)
    // {
    //     return $query->where('is_active', 1);
    // }

    public function scopeParentItems($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Accessors & Mutators
    public function getRouteAttribute($value)
    {
        return $value ? url($value) : null;
    }

    public function getIconHtmlAttribute()
    {
        return $this->icon ? "<i class='{$this->icon}'></i>" : '';
    }

    // Helper Methods
    public function isParent()
    {
        return $this->children()->exists();
    }

    public function hasPermission($userRoles)
    {
        if (empty($this->allowed_roles)) {
            return true;
        }

        return !empty(array_intersect($userRoles, $this->allowed_roles));
    }

    public function getBreadcrumb()
    {
        $breadcrumb = [];
        $current = $this;

        while ($current) {
            array_unshift($breadcrumb, [
                'name' => $current->name,
                'route' => $current->route
            ]);
            $current = $current->parent;
        }

        return $breadcrumb;
    }

    // Cache Methods
    public static function getCachedMenuItems()
    {
        return Cache::remember('menu_items', 3600, function () {
            return self::with(['children' => function($query) {
                $query->active()->ordered();
            }])
            ->whereNull('parent_id')
            ->active()
            ->ordered()
            ->get();
        });
    }

    public static function clearMenuCache()
    {
        Cache::forget('menu_items');
    }

    protected static function booted()
    {
        static::saved(function ($menuItem) {
            self::clearMenuCache();
        });

        static::deleted(function ($menuItem) {
            self::clearMenuCache();
        });
    }
}