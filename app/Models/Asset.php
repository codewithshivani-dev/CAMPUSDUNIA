<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'assets';

    protected $guarded = [
    ];

    /*
    |--------------------------------------------------------------------------
    | UPDATED FOR ASSET MANAGEMENT
    |--------------------------------------------------------------------------
    | Asset fields are currently protected by $guarded = [].
    | Keeping this unchanged ensures the existing AssetManagementController
    | create/update logic continues to work.
    |--------------------------------------------------------------------------
    */

    /**
     * Get the category associated with this asset.
     *
     * Existing relationship preserved.
     */
    public function category()
    {
        return $this->belongsTo(
            AssetCategory::class,
            'category_id',
            'category_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATED FOR ASSET MANAGEMENT
    |--------------------------------------------------------------------------
    | Specifications can contain structured JSON/array data.
    |
    | This cast allows Laravel to automatically convert JSON data into
    | an array when reading and back to JSON when saving.
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'specifications' => 'array',
    ];
}