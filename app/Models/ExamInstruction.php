<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamInstruction extends Model
{
    use HasFactory;

    protected $table = 'exam_instructions';

    protected $guarded = [];

    public function exam()
    {
        return $this->belongsTo(
            ExamStructureOfflineExam::class,
            'exam_structure_offline_exam_id',
            'id'
        );
    }
}

