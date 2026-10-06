<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstituteDocuments extends Model
{
    use HasFactory;
    protected $table = "institute_documents";

    protected $guarded = [];

     // Relationships
    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id');
    }

    public function stakeholder()
    {
        return $this->belongsTo(StakeholderAuthorizedDetails::class, 'stakeholder_id');
    }

    public function authorizedUser()
    {
        return $this->belongsTo(StakeholderAuthorizedDetails::class, 'authorized_user_id');
    }
}
