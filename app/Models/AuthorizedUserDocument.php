<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class AuthorizedUserDocument extends Model
{
    use HasFactory;
    
    protected $table = "authorized_user_documents";
    protected $guarded = [];

    public function authorizedUser()
    {
        return $this->belongsTo(AuthorizedUser::class);
    }
    
    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id', 'fincap_merchant_id');
    }

    // Accessor for stamp URL
    public function getStampUrlAttribute()
    {
        if ($this->stamp_path && Storage::disk('public')->exists($this->stamp_path)) {
            return Storage::disk('public')->url($this->stamp_path);
        }
        return null;
    }

    // Accessor for signature URL
    public function getSignatureUrlAttribute()
    {
        if ($this->signature_path && Storage::disk('public')->exists($this->signature_path)) {
            return Storage::disk('public')->url($this->signature_path);
        }
        return null;
    }
}