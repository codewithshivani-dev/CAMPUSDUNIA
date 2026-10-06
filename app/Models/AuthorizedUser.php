<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class AuthorizedUser extends Model
{
      use HasFactory;
     protected $table = "authorized_users";
     protected $guarded = [];

    public function documents()
    {
        return $this->hasOne(AuthorizedUserDocument::class);
    }

    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id', 'fincap_merchant_id');
    }
}