<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmployeeDetails;
use App\Models\LetterTemplate;

class Letter extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'template_key',
        'template_id',
        'official_document_file',
        'title',
        'reference_id',
        'content',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }

    public function template()
    {
        return $this->belongsTo(LetterTemplate::class, 'template_key', 'key');
    }

    public function templateById()
    {
        return $this->belongsTo(LetterTemplate::class, 'template_id', 'id');
    }
    public function getOfficialDocumentUrlAttribute()
{
    if ($this->official_document_file) {
        return asset('storage/' . $this->official_document_file);
    }
    return null;
}
public static function findByLetterId($letterId)
{
    return static::where('letter_id', $letterId)->first();
}
}