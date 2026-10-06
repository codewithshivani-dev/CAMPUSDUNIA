<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IDCardSetting extends Model
{
    use HasFactory;
    protected $table = "student_id_card_settings";
    protected $guarded = [];
   
}