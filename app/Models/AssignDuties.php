<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignDuties extends Model
{
    protected $table = 'employee_duties';
    protected $guarded = [];

    protected $casts = [
        'days_of_week' => 'array',
        'date' => 'date',
        'from_date' => 'date',
        'to_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
    
    /**
     * Get the institute that owns the duty assignment.
     */
    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }
    
    
    /**
     * Get the department that owns the duty assignment.
     */
    public function department()
    {
        return $this->belongsTo(Departments::class);
    }
    
    /**
     * Get the employee assigned to the duty.
     */
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }
    
    /**
     * Get the admin who assigned the duty.
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
    
    /**
     * Get the supervisor for the duty.
     */
    public function supervisor()
    {
        return $this->belongsTo(EmployeeDetails::class, 'supervisor_id');
    }
    
    /**
     * Scope a query to only include pending duties.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope a query to only include active duties.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['assigned', 'in_progress']);
    }
    
    /**
     * Scope a query to only include completed duties.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function dutyType()
    {
        return $this->belongsTo(DutyType::class, 'employee_duty_type_id','employee_duty_type_id');
    }
    
    /**
     * Scope a query to only include duties for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->where(function($q) use ($date) {
            $q->where('date', $date)
              ->orWhere(function($q2) use ($date) {
                  $q2->where('frequency', 'daily')
                     ->where('from_date', '<=', $date)
                     ->where('to_date', '>=', $date);
              })
              ->orWhere(function($q3) use ($date) {
                  $q3->where('frequency', 'weekly')
                     ->where('from_date', '<=', $date)
                     ->where('to_date', '>=', $date)
                     ->whereJsonContains('days_of_week', (int)$date->dayOfWeek);
              })
              ->orWhere(function($q4) use ($date) {
                  $q4->where('frequency', 'monthly')
                     ->where('from_date', '<=', $date)
                     ->where('to_date', '>=', $date)
                     ->where('day_of_month', $date->day);
              });
        });
    }
    
    /**
     * Check if duty has time conflict with another duty.
     */
    public function hasTimeConflict($employeeId, $date, $startTime, $endTime, $excludeId = null)
    {
        $query = self::where('employee_id', $employeeId)
            ->forDate($date)
            ->where(function($q) use ($startTime, $endTime) {
                $q->where(function($q2) use ($startTime, $endTime) {
                    // Check if new time overlaps with existing duty
                    $q2->where('start_time', '<', $endTime)
                       ->where('end_time', '>', $startTime);
                });
            });
            
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }
    
    /**
     * Get duration in hours.
     */
    public function getDurationAttribute()
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        return $start->diffInHours($end);
    }
    
    /**
     * Get formatted time range.
     */
    public function getTimeRangeAttribute()
    {
        return \Carbon\Carbon::parse($this->start_time)->format('h:i A') . ' - ' . 
               \Carbon\Carbon::parse($this->end_time)->format('h:i A');
    }
}