<?php
// app/Models/Category.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'inventory_categories';

    protected $fillable = [
        'institute_id',
        'name',
        'icon',
        'color',
        'has_uniform_fields',
        'custom_fields',
        'subcategories'
    ];

    protected $casts = [
        'subcategories' => 'array',
        'custom_fields' => 'array',
        'has_uniform_fields' => 'boolean'
    ];

    // Relationships
    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function customFields()
    {
        return $this->hasMany(CustomField::class);
    }

    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Item::class);
    }

    // Scopes
    public function scopeWithUniformFields($query)
    { 
        return $query->where('has_uniform_fields', true);
    }

    // Accessors
    public function getItemCountAttribute()
    {
        return $this->items()->count();
    }

    public function getSubcategoriesListAttribute()
    {
        return $this->subcategories ?? [];
    }

    public function getHasSubcategoriesAttribute()
    {
        return !empty($this->subcategories) && count($this->subcategories) > 0;
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'category_id');
    }

    // Methods
    public function addSubcategory($subcategory)
    {
        $subcategories = $this->subcategories ?? [];
        if (!in_array($subcategory, $subcategories)) {
            $subcategories[] = $subcategory;
            $this->subcategories = $subcategories;
            $this->save();
        }
        return $this;
    }

    public function removeSubcategory($subcategory)
    {
        $subcategories = $this->subcategories ?? [];
        $key = array_search($subcategory, $subcategories);
        if ($key !== false) {
            unset($subcategories[$key]);
            $this->subcategories = array_values($subcategories);
            $this->save();
        }
        return $this;
    }

    public function updateCustomField($key, $value)
    {
        $customFields = $this->custom_fields ?? [];
        $customFields[$key] = $value;
        $this->custom_fields = $customFields;
        $this->save();
        return $this;
    }
}