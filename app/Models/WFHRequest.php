<?php
// app/Models/WFHRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WFHRequest extends Model
{
    use HasFactory;

    protected $table = 'wfh_requests';

    protected $fillable = [
        'request_id',
        'institute_id',
        'branch_id',
        'employee_id',
        'request_date',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'reason',
        'work_plan',
        'emergency_contact',
        'request_status',
        'admin_remarks',
        'processed_by',
        'approved_at',
        'rejected_at',
        'completed_at',
        'additional_data',
        'is_active',
    ];

    protected $casts = [
        'request_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'additional_data' => 'array',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function employee()
    {
        return $this->belongsTo(EmployeeDetails::class, 'employee_id', 'employee_id');
    }

    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class);
    }

  

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('request_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('request_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('request_status', 'rejected');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->where(function($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function($q2) use ($startDate, $endDate) {
                  $q2->where('start_date', '<=', $startDate)
                     ->where('end_date', '>=', $endDate);
              });
        });
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="status-badge status-pending"><i class="fas fa-clock"></i> Pending</span>',
            'approved' => '<span class="status-badge status-approved"><i class="fas fa-check-circle"></i> Approved</span>',
            'rejected' => '<span class="status-badge status-rejected"><i class="fas fa-times-circle"></i> Rejected</span>',
            'cancelled' => '<span class="status-badge status-cancelled"><i class="fas fa-ban"></i> Cancelled</span>',
            'completed' => '<span class="status-badge status-completed"><i class="fas fa-check-double"></i> Completed</span>',
        ];

        return $badges[$this->request_status] ?? '<span class="status-badge">' . ucfirst($this->request_status) . '</span>';
    }

    public function getDurationAttribute()
    {
        $start = \Carbon\Carbon::parse($this->start_date);
        $end = \Carbon\Carbon::parse($this->end_date);
        $days = $start->diffInDays($end) + 1;
        
        return $days . ' day' . ($days > 1 ? 's' : '');
    }

    public function getFormattedDateRangeAttribute()
    {
        $start = \Carbon\Carbon::parse($this->start_date)->format('d M, Y');
        $end = \Carbon\Carbon::parse($this->end_date)->format('d M, Y');
        
        return $start . ' - ' . $end;
    }

    // Boot method to generate request ID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->request_id)) {
                $model->request_id = 'WFH-' . date('Ymd') . '-' . Str::random(6);
            }
            if (empty($model->request_date)) {
                $model->request_date = now()->toDateString();
            }
        });
    }

    // Helper methods
    public function isPending()
    {
        return $this->request_status === 'pending';
    }

    public function isApproved()
    {
        return $this->request_status === 'approved';
    }

    public function isRejected()
    {
        return $this->request_status === 'rejected';
    }

    public function isCompleted()
    {
        return $this->request_status === 'completed';
    }

    public function canApprove()
    {
        return $this->isPending() && $this->is_active;
    }

    public function approve($adminId, $remarks = null)
    {
        $this->request_status = 'approved';
        $this->processed_by = $adminId;
        $this->admin_remarks = $remarks;
        $this->approved_at = now();
        $this->save();

        return $this;
    }

    public function reject($adminId, $remarks = null)
    {
        $this->request_status = 'rejected';
        $this->processed_by = $adminId;
        $this->admin_remarks = $remarks;
        $this->rejected_at = now();
        $this->save();

        return $this;
    }

    public function cancel()
    {
        $this->request_status = 'cancelled';
        $this->save();

        return $this;
    }

    public function complete()
    {
        $this->request_status = 'completed';
        $this->completed_at = now();
        $this->save();

        return $this;
    }
}