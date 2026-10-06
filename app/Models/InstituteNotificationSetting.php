<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstituteNotificationSetting extends Model
{
    protected $table = 'institute_notification_settings';
    
    protected $fillable = [
        'institute_id',
        'module_name',
        'email_enabled',
        'whatsapp_enabled',
        'sms_enabled'
    ];
    
    protected $casts = [
        'email_enabled' => 'boolean',
        'whatsapp_enabled' => 'boolean',
        'sms_enabled' => 'boolean'
    ];
    
    // Helper method to get settings for an institute
    public static function getInstituteSettings($instituteId)
    {
        $allModules = NotificationModule::where('is_active', true)
            ->where('institute_id', $instituteId)
            ->get();
        
        $existingSettings = self::where('institute_id', $instituteId)
            ->get()
            ->keyBy('module_name');
        
        $modulesByCategory = [];
        foreach ($allModules as $module) {
            $settings = $existingSettings->get($module->module_name);
            
            $modulesByCategory[$module->category][] = [
                'module_name' => $module->module_name,
                'module_display_name' => $module->module_display_name,
                'description' => $module->description,
                'is_mandatory' => (bool) $module->is_mandatory,
                'email_enabled' => $settings ? (bool) $settings->email_enabled : ($module->is_mandatory ? true : false),
                'whatsapp_enabled' => $settings ? (bool) $settings->whatsapp_enabled : false,
                'sms_enabled' => $settings ? (bool) $settings->sms_enabled : false,
            ];
        }
        
        return $modulesByCategory;
    }
}