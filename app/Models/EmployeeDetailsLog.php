<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDetailsLog extends Model
{
    
    protected $table = 'employee_details_logs';

    protected $fillable = [
        'employee_id',
        'employee_code',
        'employee_name',
        'institute_id',
        'branch_id',
        'changed_by',
        'changed_by_name',
        'changes',
        'change_summary',
        'change_count',
        'step',
        'ip_address',
        'user_agent',
        'created_at'
    ];

    protected $casts = [
        'changes' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    
    ];

    /**
     * Get the employee that owns the log
     */
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id');
    }

    /**
     * Get the user who made the change
     */
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Get the institute
     */
    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id');
    }

    /**
     * Scope for specific employee
     */
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope for specific institute
     */
    public function scopeForInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }

    /**
     * Get formatted changes for display
     */
    public function getFormattedChangesAttribute()
    {
        if (empty($this->changes)) {
            return [];
        }

        $changes = $this->changes;
        $formatted = [];

        foreach ($changes as $change) {
            $fieldLabel = str_replace('_', ' ', ucwords($change['field'] ?? 'Unknown'));
            $oldValue = $this->formatValue($change['old_value'] ?? null);
            $newValue = $this->formatValue($change['new_value'] ?? null);
            
            $formatted[] = [
                'field' => $change['field'] ?? 'unknown',
                'label' => $fieldLabel,
                'old_value' => $oldValue,
                'new_value' => $newValue,
                'display' => "Changed {$fieldLabel} from '{$oldValue}' to '{$newValue}'"
            ];
        }

        return $formatted;
    }

    /**
     * Format value for display
     */
    private function formatValue($value)
    {
        if (is_null($value)) {
            return 'Not set';
        }

        if (is_array($value)) {
            return json_encode($value, JSON_PRETTY_PRINT);
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (strtotime($value) !== false) {
            try {
                return Carbon::parse($value)->format('d-m-Y H:i:s');
            } catch (\Exception $e) {
                return $value;
            }
        }

        return (string) $value;
    }
}