<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regeneration extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'certificate_regeneration_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_hash_id',
        'institute_id',
        'registration_number',
        'certificate_number_before',
        'certificate_number_after',
        'certificate_type',
        'generation_sequence',
        'full_texts',
        'regeneration_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'institute_id' => 'integer',
        'regeneration_time' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false; // Using custom regeneration_time instead of created_at/updated_at

    /**
     * Scope a query to filter by institute.
     */
    public function scopeByInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }

    /**
     * Scope a query to filter by student.
     */
    public function scopeByStudent($query, $studentHashId)
    {
        return $query->where('student_hash_id', $studentHashId);
    }

    /**
     * Scope a query to get recent regenerations.
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('regeneration_time', 'desc')->limit($limit);
    }

    /**
     * Get the old certificate number.
     */
    public function getOldCertificateAttribute()
    {
        return $this->certificate_number_before;
    }

    /**
     * Get the new certificate number.
     */
    public function getNewCertificateAttribute()
    {
        return $this->certificate_number_after;
    }

    /**
     * Store full texts as JSON.
     */
    public function setFullTextsJsonAttribute($value)
    {
        $this->attributes['full_texts'] = json_encode($value);
    }

    /**
     * Get full texts as array.
     */
    public function getFullTextsArrayAttribute()
    {
        return json_decode($this->full_texts, true);
    }
}