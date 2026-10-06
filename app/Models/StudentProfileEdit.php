<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfileEdit extends Model
{
    protected $table = 'student_profile_edits';
    
    protected $fillable = [
        'student_hash_id',
        'field_name',
        'old_value',
        'new_value',
        'verified_at',
        'verification_method'
    ];
    
    protected $casts = [
        'verified_at' => 'datetime'
    ];
    
    /**
     * Get the student that owns the profile edit.
     */
    public function student()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }
}