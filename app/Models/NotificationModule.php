<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationModule extends Model
{
    protected $table = 'notification_modules';
    
    protected $fillable = [
        'institute_id',
        'module_name',
        'module_display_name',
        'category',
        'description',
        'is_mandatory',
        'is_active'
    ];
    
    protected $casts = [
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean'
    ];
    
    // Relationship with institute
    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id', 'fincap_merchant_id');
    }
    
    // Relationship with settings
    public function settings()
    {
        return $this->hasMany(InstituteNotificationSetting::class, 'module_name', 'module_name')
            ->where('institute_id', $this->institute_id);
    }
}