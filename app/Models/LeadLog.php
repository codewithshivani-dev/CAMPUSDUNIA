<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadLog extends Model
{
    protected $fillable = [
        'lead_id',
        'action',
        'field_name',
        'old_value',
        'new_value',
        'remarks'
    ];
}
?>