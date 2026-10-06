<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shifts extends Model
{
    use HasFactory;

    protected $table = "shifts";

    protected $guarded = [];

    protected $casts = [
        'flexible_working_hours' => 'boolean',
        'weekly_off_days' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    
  // ==================== RELATIONSHIPS ====================
    
    /**
     * Get the employee assignments for this shift
     * This is the key relationship for counting employees
     */
    public function employeeAssignments()
    {
        return $this->hasMany(EmployeeShift::class, 'shift_id', 'id');
    }

    /**
     * Get the employee details through employee_shift table
     */
    public function employees()
    {
        return $this->belongsToMany(EmployeeDetails::class, 'employee_shifts', 'shift_id', 'employee_id')
            ->where('employee_shifts.status', 'active')
            ->withPivot('institute_id', 'branch_id', 'status', 'assignment_type');
    }

    /**
     * Get the department assignments for this shift
     */
    public function departmentAssignments()
    {
        return $this->hasMany(DepartmentShift::class, 'shift_id', 'id');
    }

    /**
     * Get departments through department_shift table
     */
    public function departments()
    {
        return $this->belongsToMany(Departments::class, 'department_shifts', 'shift_id', 'department_id')
            ->where('department_shifts.status', 'active')
            ->withPivot('institute_id', 'branch_id', 'status');
    }

    /**
     * Get the student assignments for this shift
     */
    public function studentAssignments()
    {
        return $this->hasMany(StudentShift::class, 'shift_id', 'id');
    }

    /**
     * Get the student details through student_shift table
     */
    public function students()
    {
        return $this->belongsToMany(StudentParentDetails::class, 'student_shifts', 'shift_id', 'student_id')
            ->where('student_shifts.status', 'active')
            ->withPivot('institute_id', 'branch_id', 'status', 'assignment_type');
    }

    // ==================== HELPER METHODS ====================
    
    /**
     * Get count of active employees assigned to this shift
     */
    public function getActiveEmployeesCountAttribute()
    {
        return $this->employeeAssignments()
            ->where('status', 'active')
            ->count();
    }

    /**
     * Get count of active students assigned to this shift
     */
    public function getActiveStudentsCountAttribute()
    {
        return $this->studentAssignments()
            ->where('status', 'active')
            ->count();
    }

    /**
     * Get all employee IDs assigned to this shift (active only)
     */
    public function getAssignedEmployeeIdsAttribute()
    {
        return $this->employeeAssignments()
            ->where('status', 'active')
            ->pluck('employee_id')
            ->toArray();
    }

    /**
     * Get all student IDs assigned to this shift (active only)
     */
    public function getAssignedStudentIdsAttribute()
    {
        return $this->studentAssignments()
            ->where('status', 'active')
            ->pluck('student_id')
            ->toArray();
    }

    /**
     * Check if a specific employee is assigned to this shift
     */
    public function isAssignedToEmployee($employeeId)
    {
        return $this->employeeAssignments()
            ->where('employee_id', $employeeId)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Check if a specific student is assigned to this shift
     */
    public function isAssignedToStudent($studentId)
    {
        return $this->studentAssignments()
            ->where('student_id', $studentId)
            ->where('status', 'active')
            ->exists();
    }

    // ==================== SCOPES ====================
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now()->toDateString())
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now()->toDateString());
            });
    }

    public function scopeFlexible($query)
    {
        return $query->where('flexible_working_hours', true);
    }

    // ==================== ACCESSORS ====================
    
    public function getFormattedStartTimeAttribute()
    {
        return $this->start_time ? date('h:i A', strtotime($this->start_time)) : null;
    }

    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time ? date('h:i A', strtotime($this->end_time)) : null;
    }

    public function getIsCurrentlyActiveAttribute()
    {
        $today = now()->toDateString();
        $start = $this->start_date ? $this->start_date->toDateString() : null;
        $end = $this->end_date ? $this->end_date->toDateString() : null;

        if (!$start) return false;
        if ($start > $today) return false;
        if ($end && $end < $today) return false;
        return $this->is_active;
    }

    public function getFlexibilityLabelAttribute()
    {
        return $this->flexible_working_hours ? 'Flexible' : 'Fixed';
    }

    public function getWeeklyOffDaysAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }
        if (is_string($value) && !empty($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    public function setWeeklyOffDaysAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['weekly_off_days'] = null;
            return;
        }
        
        if (is_array($value)) {
            $this->attributes['weekly_off_days'] = json_encode(array_values($value));
            return;
        }
        
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                $this->attributes['weekly_off_days'] = json_encode(array_values($decoded));
                return;
            }
        }
        
        $this->attributes['weekly_off_days'] = null;
    }

    public function getFlexibilitySummaryAttribute()
    {
        if ($this->flexible_working_hours) {
            return "Flexible (" . number_format($this->min_working_hours ?? $this->working_hours, 1) . " - " . number_format($this->max_working_hours ?? $this->working_hours, 1) . " hrs)";
        }
        return 'Fixed (' . number_format($this->working_hours, 1) . ' hrs)';
    }

    /**
     * Validate employee working hours against flexibility rules
     */
    public function validateWorkingHours($hours)
    {
        if (!$this->flexible_working_hours) {
            return $hours == $this->working_hours;
        }
        
        if ($this->min_working_hours && $hours < $this->min_working_hours) {
            return false;
        }
        
        if ($this->max_working_hours && $hours > $this->max_working_hours) {
            return false;
        }
        
        return true;
    }
}