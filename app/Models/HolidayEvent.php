<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayEvent extends Model
{
    use HasFactory;
    protected $table = "holiday_events";
    protected $guarded = [];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_recurring' => 'boolean'
    ];

     public function departmentCategory()
    {
        return $this->belongsTo(DepartmentCategory::class, 'department_category_id', 'department_category_id');
    }
    

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'department_id');
    }

     public function gradeSystem()
    {
        return $this->belongsTo(GradeSystem::class, 'grade_system_id', 'id');
    }
    // Scope for overall events
    public function scopeOverall($query)
    {
        return $query->where('scope', 'overall');
    }

    // Scope for department-wise events
    public function scopeDepartmentWise($query)
    {
        return $query->where('scope', 'department_wise');
    }

    // Get events for a specific department
    public function scopeForDepartment($query, $departmentId)
    {
        return $query->where('scope', 'overall')
            ->orWhere(function($q) use ($departmentId) {
                $q->where('scope', 'department_wise')
                  ->where('department_id', $departmentId);
            });
    }

    // Accessor for target audience label
    public function getTargetAudienceLabelAttribute()
    {
        $labels = [
            'students' => '👨‍🎓 Students',
            'employees' => '👨‍💼 Employees',
            'both' => '👥 Both'
        ];
        return $labels[$this->target_audience] ?? '👥 Both';
    }
    
    // Scope for filtering by target audience
    public function scopeForStudents($query)
    {
        return $query->whereIn('target_audience', ['students', 'both']);
    }
    
    public function scopeForEmployees($query)
    {
        return $query->whereIn('target_audience', ['employees', 'both']);
    }
    
    public function scopeForAudience($query, $audience)
    {
        if ($audience === 'students') {
            return $query->forStudents();
        } elseif ($audience === 'employees') {
            return $query->forEmployees();
        }
        return $query;
    }
}