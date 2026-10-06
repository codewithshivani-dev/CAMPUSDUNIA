<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLeave extends Model
{
    use HasFactory;

    protected $fillable = [
        'institute_id',
        'student_hash_id',
        'leave_type',
        'leave_duration_type',
        'start_date',
        'end_date',
        'reason',
        'total_days',
        'leave_document',
        'status',
        'approver_id',
        'approved_by',
    ];

    public function student()
    {
        return $this->belongsTo(StudentParentDetails::class, 'student_hash_id', 'student_hash_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
