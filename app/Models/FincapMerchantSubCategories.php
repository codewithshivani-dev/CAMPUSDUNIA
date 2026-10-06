<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FincapMerchantSubCategories extends Model
{
    use HasFactory;
    protected $table = "fincap_merchant_sub_categories";

     protected $guarded = [];

    public function department()
    {
        return $this->belongsTo(\App\Models\Departments::class, 'department_id', 'department_id');
    }

    public function departmentCategory()
    {
        return $this->belongsTo(DepartmentCategory::class, 'department_category_id', 'department_category_id');
    }

    public function productDetails()
    {
        return $this->hasMany(ProductDetails::class, 'finacp_merchant_sub_category_id', 'finacp_merchant_sub_category_id');
    }
}
