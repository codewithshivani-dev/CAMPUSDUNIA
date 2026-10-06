<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewEditLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'interview_edit_logs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'lead_id',
        'institute_id',
        'field_name',
        'value_before',
        'value_after',
        'edit_count',
        'edited_by',
        'edited_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'edited_at' => 'datetime',
        'edit_count' => 'integer',
        'lead_id' => 'integer',
        'institute_id' => 'integer',
        'edited_by' => 'integer',
    ];

    /**
     * Get the lead that owns the edit log.
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    /**
     * Get the institute that owns the edit log.
     */
    public function institute()
    {
        return $this->belongsTo(Institute::class, 'institute_id');
    }

    /**
     * Get the user who made the edit.
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    /**
     * Scope a query to get logs for a specific lead.
     */
    public function scopeForLead($query, $leadId)
    {
        return $query->where('lead_id', $leadId);
    }

    /**
     * Scope a query to get logs for a specific institute.
     */
    public function scopeForInstitute($query, $instituteId)
    {
        return $query->where('institute_id', $instituteId);
    }

    /**
     * Scope a query to get logs for a specific field.
     */
    public function scopeForField($query, $fieldName)
    {
        return $query->where('field_name', $fieldName);
    }

    /**
     * Helper method to log an edit.
     */
    public static function logEdit($leadId, $instituteId, $fieldName, $oldValue, $newValue, $editedBy = null)
    {
        // Count how many times this field has been edited
        $editCount = self::where('lead_id', $leadId)
            ->where('institute_id', $instituteId)
            ->where('field_name', $fieldName)
            ->count();

        // Always create a new log entry
        return self::create([
            'lead_id' => $leadId,
            'institute_id' => $instituteId,
            'field_name' => $fieldName,
            'value_before' => $oldValue,
            'value_after' => $newValue,
            'edit_count' => $editCount + 1,
            'edited_by' => $editedBy,
            'edited_at' => now(),
        ]);
    }

    /**
     * Get formatted before and after values for display.
     */
    public function getFormattedChangeAttribute()
    {
        return [
            'field' => ucfirst(str_replace('_', ' ', $this->field_name)),
            'before' => $this->value_before ?? 'NULL',
            'after' => $this->value_after ?? 'NULL',
            'count' => $this->edit_count,
            'by' => $this->editor ? $this->editor->name : 'System',
            'at' => $this->edited_at->format('Y-m-d H:i:s'),
        ];
    }
}