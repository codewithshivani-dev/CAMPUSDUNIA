<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StakeholderAuthorizedDetails extends Model
{
    use HasFactory;
    protected $table = "stakeholders_authorized";

    protected $guarded = [];

    // Relationships
    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id');
    }

    public function documentsAsStakeholder()
    {
        return $this->hasMany(InstituteDocuments::class, 'stakeholder_id');
    }

    public function documentsAsAuthorizedUser()
    {
        return $this->hasMany(InstituteDocuments::class, 'authorized_user_id');
    }
}
