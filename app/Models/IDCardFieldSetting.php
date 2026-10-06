<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class IDCardFieldSetting extends Model
{
    protected $table = 'id_card_field_settings';
    
    protected $fillable = [
        'institute_id',
        'type',
        'field_name',
        'field_label',
        'is_visible',
        'sort_order'
    ];
    
    protected $casts = [
        'is_visible' => 'boolean',
        'sort_order' => 'integer'
    ];
}