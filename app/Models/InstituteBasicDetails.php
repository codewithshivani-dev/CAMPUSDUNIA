<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstituteBasicDetails extends Model
{
    use HasFactory;
    protected $table = "institutes";

    protected $guarded = [];

    // Relationships
    public function stakeholders()
    {
        return $this->hasMany(Stakeholder::class, 'institute_id','fincap_merchant_id');
    }
    public function stakeholderDocuments()
    {
        return $this->hasMany(StakeholderDocument::class, 'stakeholder_id','id');
    }
    public function authorizedUser()
    {
        return $this->hasMany(AuthorizedUser::class, 'institute_id','fincap_merchant_id');
    }
    public function authorizedUserDocuments()
    {
        return $this->hasMany(AuthorizedUserDocument::class, 'authorized_user_id','id');
    }
    public function documents()
    {
        return $this->hasMany(InstituteDocuments::class, 'institute_id','fincap_merchant_id');
    }

    public function beneficiary()
    {
        return $this->hasOne(InstituteBeneficiaryDetail::class, 'institute_id','fincap_merchant_id');
    }
    
    // Parent institute relationship
    public function parentInstitute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'branch_id', 'fincap_merchant_id');
    }

    // Child branches relationship
    public function childBranches()
    {
        return $this->hasMany(InstituteBasicDetails::class, 'branch_id', 'fincap_merchant_id');
    }

    // Scope to get only parent institutes (no branch_id)
    public function scopeParentInstitutes($query)
    {
        return $query->whereNull('branch_id');
    }

    // Scope to get only branches
    public function scopeBranches($query)
    {
        return $query->whereNotNull('branch_id');
    }
}
