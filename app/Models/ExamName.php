<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class ExamName extends Model
{
    use HasFactory;

    protected $table = 'exam_names';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->exam_name_id)) {
                $model->exam_name_id = 'EXM-' . Str::upper(Str::random(3)) . '-' . time();
            }
        });
    }

    // Relationship with exams
    public function exams()
    {
        return $this->hasMany(ExamStructureOfflineExam::class, 'exam_name_id', 'exam_name_id');
    }

    // Relationship with institute
    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    // Scope for active exam names
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for current academic year
    public function scopeCurrentYear($query, $year = null)
    {
        $year = $year ?? date('Y') . '-' . (date('Y') + 1);
        return $query->where('academic_year', $year);
    }
}