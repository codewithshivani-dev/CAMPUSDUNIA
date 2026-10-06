<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetCategory extends Model
{
    use HasFactory;

    protected $table = 'asset_categories';

    protected $fillable = [
        'category_id',
         'institute_id',
        'name',
    ];
 
    /**
     * Get all assets belonging to this category.
     *
     * Existing relationship preserved.
     */
    public function assets()
    {
        return $this->hasMany(
            Asset::class,
            'category_id',
            'category_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EXISTING METHOD PRESERVED
    |--------------------------------------------------------------------------
    | This method was already present in your model.
    | It has intentionally NOT been removed.
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $categories = AssetCategory::withCount([
            'assets' => function ($query) {
                $query->where(
                    'category_id',
                    '=',
                    $this->category_id
                );
            }
        ])
        ->orderBy('name', 'asc')
        ->get();

        return view(
            'instituteAdmin.Assets.assets',
            compact('categories')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATED FOR ASSET MANAGEMENT
    |--------------------------------------------------------------------------
    | Convenient relationship count.
    |
    | The actual category asset count should normally be retrieved using
    | withCount('assets') from the controller.
    |
    | No database changes are required for this relationship.
    |--------------------------------------------------------------------------
    */
}