<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesForAdmissionInterviewProcess extends Model
{
    use HasFactory;

    protected $table = 'roles_for_admission_interivew_process';

    protected $fillable = [
        'institute_id',
        'branch_id',
        'role_hash_id',
        'employee_id',
        'type',
        'department_id',
        'class_id',
        'status'
    ];

    protected $casts = [
        'class_id' => 'array'
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }

    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    // Get classes as collection
    public function getClasses()
    {
        if (is_array($this->class_id)) {
            return CourseType::whereIn('id', $this->class_id)->get();
        }
        return collect();
    }

    // Scope for active records
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope by type
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
}