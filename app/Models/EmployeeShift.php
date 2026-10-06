<?php
// app/Models/EmployeeShift.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeShift extends Model
{
    protected $table = 'employee_shifts';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'institute_id',
        'branch_id',
        'shift_id',
        'employee_id',
        'department_id',
        'status',
        'assignment_type',
        'assigned_by',
        'assigned_at',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'status' => 'string',
        'assignment_type' => 'string',
        'assigned_at' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // ==================== RELATIONSHIPS ====================
    
    public function shift()
    {
        return $this->belongsTo(Shifts::class, 'shift_id', 'id');
    }

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

    // ==================== SCOPES ====================
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeByInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }

    public function scopeByBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    // ==================== ACCESSORS ====================
    
    public function getAssignmentTypeLabelAttribute()
    {
        return $this->assignment_type === 'direct' ? 'Individual' : 'Department';
    }

    public function getStatusLabelAttribute()
    {
        return $this->status === 'active' ? 'Active' : 'Inactive';
    }
}