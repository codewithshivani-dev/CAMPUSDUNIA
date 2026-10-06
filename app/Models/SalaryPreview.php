<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class SalaryPreview extends Model
{
    use HasFactory;
    protected $table = 'salary_previews';
    protected $guarded = [];
}