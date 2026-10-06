<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusFile extends Model
{
    use HasFactory;

    protected $fillable = ['syllabus_id', 'file_path', 'file_name'];

    public function syllabus() { return $this->belongsTo(Syllabus::class); }
}
