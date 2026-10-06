<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetails extends Model
{
    use HasFactory;
    protected $table = "product_details";

    protected $guarded = [];

      public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }

    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id', 'fincap_merchant_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function course()
    {
        return $this->belongsTo(
            FincapMerchantSubCategories::class,
            'finacp_merchant_sub_category_id',
            'finacp_merchant_sub_category_id'
        );
    }


}
