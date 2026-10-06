<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    use HasFactory;
    protected $table = "syllabuses";
    protected $guarded = [];

    public function files() { return $this->hasMany(SyllabusFile::class); }
}
