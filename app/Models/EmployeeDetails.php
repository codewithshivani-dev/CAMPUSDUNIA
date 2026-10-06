<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use App\Notifications\ProbationReminderNotification;
use App\Notifications\EmployeeProbationCompleteNotification;
class EmployeeDetails  extends Model
{
    use HasFactory;
    protected $table="employee_details";
    
    protected $guarded = [];

     protected $casts = [
    'additional_documents' => 'array',
    ];

    public function booksIssues()
    {
        return $this->morphMany(BooksIssues::class, 'issueable');
    }

    public function shift()
    {
        return $this->belongsTo(Shifts::class, 'shift_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class,'department_id', 'department_id');
    }

    public function departmentcategory()
    {
        return $this->belongsTo(DepartmentCategory::class, 'department_category_id','department_category_id');
    }

    public function institute()
    {
        return $this->belongsTo(InstituteBasicDetails::class, 'institute_id', 'fincap_merchant_id');
    }

    public function designationRelation()
    {
        return $this->belongsTo(Designations::class, 'designation_id', 'designation_id');
    }


    public function getEffectiveShiftAttribute()
    {
        // Check if employee has individual shift assigned
        if ($this->shift_id) {
            return $this->shift;
        }
        
        // Check if department has shift assigned
        if ($this->department_id) {
            $department = Departments::where('department_id', $this->department_id)->first();
            if ($department && $department->shift_id) {
                return $department->shift;
            }
        }
        
        return null;
    }

    public function canReceiveDepartmentShift($newShiftId = null): bool
    {
        // If no individual shift assigned, can receive department shift (any priority)
        if (is_null($this->shift_id)) {
            return true;
        }
        
        // If checking against a specific new shift, compare priorities
        if ($newShiftId) {
            $newShift = Shifts::find($newShiftId);
            $currentShift = $this->shift;
            
            if ($newShift && $currentShift) {
                $priorityOrder = ['high' => 3, 'medium' => 2, 'low' => 1];
                $newPriority = $priorityOrder[$newShift->priority] ?? 1;
                $currentPriority = $priorityOrder[$currentShift->priority] ?? 1;
                
                // Only allow if new shift has HIGHER priority (not equal)
                return $newPriority > $currentPriority;
            }
        }
        
        // If no specific shift provided or comparison fails, don't override existing shift
        return false;
    }

    public function transportFees()
    {
        return $this->hasMany(EmployeeTransportFeeStructure::class, 'employee_id', 'employee_id');
    }

    
    /**
     * Get the menu items assigned to this employee
     */
    public function responsibilities(): BelongsToMany
    {
        return $this->belongsToMany(
            MenuItem::class, 
            'employee_responsibilities',
            'employee_id',  // Foreign key on pivot table for EmployeeDetails
            'menu_item_id'  // Foreign key on pivot table for MenuItem
        )->withPivot(['can_view', 'can_create', 'can_edit', 'can_delete'])
        ->withTimestamps();
    }

    /**
     * Get employee responsibility records
     */
    public function responsibilityRecords(): HasMany
    {
        return $this->hasMany(EmployeeResponsibility::class);
    }

   
    /**
     * Check if employee has access to a menu item
     * (at least one permission is granted)
     */
    public function hasMenuItemAccess($menuItemId): bool
    {
        $responsibility = $this->responsibilities()
            ->where('menu_item_id', $menuItemId)
            ->first();

        if (!$responsibility) {
            return false;
        }

        return (
            (bool) $responsibility->pivot->can_view ||
            (bool) $responsibility->pivot->can_create ||
            (bool) $responsibility->pivot->can_edit ||
            (bool) $responsibility->pivot->can_delete
        );
    }
    /**
     * Check if employee has permission for a menu item
     */
    public function hasMenuPermission($menuItemId, $permission = 'view'): bool
    {
        $responsibility = $this->responsibilities()
            ->where('menu_item_id', $menuItemId)
            ->first();

        if (!$responsibility) {
            return false;
        }

        switch ($permission) {
            case 'create':
                return $responsibility->pivot->can_create;
            case 'edit':
                return $responsibility->pivot->can_edit;
            case 'delete':
                return $responsibility->pivot->can_delete;
            default:
                return $responsibility->pivot->can_view;
        }
    }

    /**
     * Get employee's accessible menu items
     */
    public function getAccessibleMenuItems($employe)
    {
        $employee = EmployeeDetails::find($employe);
        
        if (!$employee) {
            return collect(); // Return empty collection if employee not found
        }
        
        return $employee->responsibilitie()
            ->wherePivot('can_view', 1)
            ->with('children')
            ->get();
    }

    /**
     * Sync responsibilities and update cache
     */
    public function syncResponsibilities(array $menuItemIds): void
    {
        $this->responsibilities()->sync($menuItemIds);
        
        // Update cache
        $this->update([
            'assigned_responsibilities' => $menuItemIds,
            'menu_permissions' => $this->getPermissionsArray()
        ]);
    }

    /**
     * Get permissions as array
     */
    private function getPermissionsArray(): array
    {
        $permissions = [];
        
        foreach ($this->responsibilities as $item) {
            $permissions[$item->id] = [
                'view' => $item->pivot->can_view,
                'create' => $item->pivot->can_create,
                'edit' => $item->pivot->can_edit,
                'delete' => $item->pivot->can_delete
            ];
        }
        
        return $permissions;
    }
    /**
     * Get individual shifts assigned to this employee
     */
    public function employeeShifts(): HasMany
    {
        return $this->hasMany(EmployeeShift::class, 'employee_id', 'employee_id')
                    ->where('status', 'active');
    }

    /**
     * Get all individual shifts (including inactive)
     */
    public function allEmployeeShifts(): HasMany
    {
        return $this->hasMany(EmployeeShift::class, 'employee_id', 'employee_id');
    }

    public function resolveEffectiveShift(Carbon $date = null)
    {
        $date = $date ?? now();
        $currentDate = $date->toDateString();
        $instituteId = $this->institute_id;
        $branchId = $this->branch_id;
    
        // :one: Individual shift
        $individual = EmployeeShift::where('institute_id', $instituteId)
            ->where('employee_id', $this->employee_id)
            ->where('status', 'active')
            ->when($branchId,
                fn ($q) => $q->where('branch_id', $branchId),
                fn ($q) => $q->whereNull('branch_id')
            )
            ->whereHas('shift', function ($q) use ($currentDate) {
                $q->where('start_date', '<=', $currentDate)
                  ->where(function ($q2) use ($currentDate) {
                      $q2->where('end_date', '>=', $currentDate)
                         ->orWhereNull('end_date');
                  });
            })
            ->with('shift')
            ->latest()
            ->first();
    
        if ($individual) {
            return [
                'type' => 'individual',
                'shift' => $individual->shift,
                'assigned_at' => $individual->created_at
            ];
        }
    
        // :two: Department shift
        if ($this->department_id) {
            $department = DepartmentShift::where('institute_id', $instituteId)
                ->where('department_id', $this->department_id)
                ->where('status', 'active')
                ->where('shift_type', 'employee')
                ->when($branchId,
                    fn ($q) => $q->where('branch_id', $branchId),
                    fn ($q) => $q->whereNull('branch_id')
                )
                ->whereHas('shift', function ($q) use ($currentDate) {
                    $q->where('start_date', '<=', $currentDate)
                      ->where(function ($q2) use ($currentDate) {
                          $q2->where('end_date', '>=', $currentDate)
                             ->orWhereNull('end_date');
                      });
                })
                ->with('shift')
                ->latest()
                ->first();
    
            if ($department) {
                return [
                    'type' => 'department',
                    'shift' => $department->shift,
                    'assigned_at' => $department->created_at
                ];
            }
        }
    
        return null;
    }
    
    /**
     * Get the profile edits for this employee
     */
    public function profileEdits(): HasMany
    {
        return $this->hasMany(EmployeeProfileEdit::class, 'employee_id', 'employee_id');
    }

      /**
     * Get probation end date
     */
    public function getProbationEndDateAttribute()
    {
        if ($this->employment_type !== 'Probation-Period' || !$this->doj || !$this->probation_days) {
            return null;
        }
        
        return Carbon::parse($this->doj)->addDays($this->probation_days);
    }
    
    /**
     * Get probation status
     * Returns: 'one_day_remaining', 'completes_today', 'overdue', 'active', or null
     */
    public function getProbationStatusAttribute()
    {
        if ($this->employment_type !== 'Probation-Period' || !$this->doj || !$this->probation_days) {
            return null;
        }
        
        $endDate = $this->getProbationEndDateAttribute();
        $today = Carbon::now()->startOfDay();
        $diffInDays = $today->diffInDays($endDate, false);
        
        if ($diffInDays == 1) {
            return 'one_day_remaining';
        } elseif ($diffInDays == 0) {
            return 'completes_today';
        } elseif ($diffInDays < 0) {
            return 'overdue';
        } else {
            return 'active';
        }
    }
    
    /**
     * Get days remaining or overdue
     * Returns positive number for remaining days, negative for overdue days
     */
    public function getProbationDaysDeltaAttribute()
    {
        if ($this->employment_type !== 'Probation-Period' || !$this->doj || !$this->probation_days) {
            return null;
        }
        
        $endDate = $this->getProbationEndDateAttribute();
        $today = Carbon::now();
        
        return $today->diffInDays($endDate, false);
    }
    
    /**
     * Get formatted probation status text
     */
    public function getProbationStatusTextAttribute()
    {
        $status = $this->getProbationStatusAttribute();
        $days = $this->getProbationDaysDeltaAttribute();
        
        if (!$status) {
            return null;
        }
        
        switch ($status) {
            case 'overdue':
                $absDays = abs($days);
                return "<span class='badge bg-danger'>Overdue by {$absDays} day(s)</span>";
            case 'completes_today':
                return "<span class='badge bg-warning text-dark'>Completes Today</span>";
            case 'active':
                return "<span class='badge bg-info'>{$days} day(s) remaining</span>";
            default:
                return null;
        }
    }
    
    /**
     * Get CSS class for probation status
     */
    public function getProbationStatusClassAttribute()
    {
        $status = $this->getProbationStatusAttribute();
        
        switch ($status) {
            case 'overdue':
                return 'probation-overdue';
            case 'completes_today':
                return 'probation-today';
            case 'active':
                return 'probation-active';
            default:
                return '';
        }
    }

    /**
     * Send probation notifications to admins
     */
    public function sendProbationReminderToAdmins()
    {
        $status = $this->getProbationStatusAttribute();
        $daysDelta = $this->getProbationDaysDeltaAttribute();
        
        if (!$status || $this->employment_type !== 'Probation-Period') {
            return;
        }
        
        // Only send for these statuses
        $validStatuses = ['one_day_remaining', 'completes_today', 'overdue'];
        if (!in_array($status, $validStatuses)) {
            return;
        }
        
        // Get institute admins
        $admins = User::where('institute_id', $this->institute_id)
            ->whereHas('roles', function($query) {
                $query->whereIn('name', ['admin', 'super_admin', 'institute_admin']);
            })
            ->get();
        
        foreach ($admins as $admin) {
            $admin->notify(new ProbationReminderNotification($this, $status, $daysDelta));
        }
    }


    /**
     * Send probation notification to employee (with email fallback)
     */
    public function sendProbationReminderToEmployee()
    {
        $status = $this->getProbationStatusAttribute();
        $daysDelta = $this->getProbationDaysDeltaAttribute();
        
        \Log::info('📧 Employee notification triggered', [
            'employee_id' => $this->id,
            'name' => $this->name,
            'status' => $status,
            'days_delta' => $daysDelta
        ]);
        
        if (!$status || $this->employment_type !== 'Probation-Period') {
            \Log::warning('❌ Invalid status or employment type', [
                'status' => $status,
                'employment_type' => $this->employment_type
            ]);
            return false;
        }
        
        // ✅ NOW INCLUDING OVERDUE - Send for ALL statuses
        $validStatuses = ['one_day_remaining', 'completes_today', 'overdue'];
        if (!in_array($status, $validStatuses)) {
            \Log::info('⏭️ Status not in valid list', ['status' => $status]);
            return false;
        }
        
        $sent = false;
        $user = null;
        
        // Try to find user
        if ($this->user_id) {
            $user = User::find($this->user_id);
        }
        
        // If user exists with email, send notification
        if ($user && $user->email) {
            try {
                $user->notify(new EmployeeProbationCompleteNotification($this, $status, $daysDelta));
                $sent = true;
                \Log::info("✅ Probation notification sent to employee via user", [
                    'employee_id' => $this->id,
                    'employee_name' => $this->name,
                    'user_id' => $this->user_id,
                    'status' => $status
                ]);
            } catch (\Exception $e) {
                \Log::error("❌ Failed to send probation notification via user", [
                    'employee_id' => $this->id,
                    'error' => $e->getMessage()
                ]);
            }
        } 
        
      
        if (!$sent) {
            \Log::warning("⚠️ Could not send probation notification to employee", [
                'employee_id' => $this->id,
                'employee_name' => $this->name,
                'has_user_id' => !empty($this->user_id),
                'has_email' => !empty($this->email)
            ]);
        }
        
        return $sent;
    }
    
    // Add relationship for suspension logs
    public function suspensionLogs()
    {
        return $this->hasMany(EmployeeSuspensionLog::class, 'employee_id');
    }

    // Add scope for suspended employees
    public function scopeSuspended($query)
    {
        return $query->where('suspend_status', 'suspended');
    }

    // Add scope for active employees (not suspended)
    public function scopeActive($query)
    {
        return $query->where('suspend_status', '!=', 'suspended');
    }


    /**
     * Get the active exit for this employee
     */
    public function activeExit()
    {
        return $this->hasOne(EmployeeExit::class, 'employee_id', 'employee_id')
            ->whereIn('exit_status', ['pending_approval', 'notice_period', 'exited', 'approved'])
            ->latest();
    }

    /**
     * Get all exits for this employee
     */
    public function exits()
    {
        return $this->hasMany(EmployeeExit::class, 'employee_id', 'employee_id');
    }

    /**
     * Check if employee has an active exit process
     */
    public function hasActiveExit()
    {
        return $this->activeExit()->exists();
    }

    public function latestExit()
    {
        return $this->hasOne(EmployeeExit::class, 'employee_id')->latest();
    }

    public function completedExits()
    {
        return $this->hasMany(EmployeeExit::class, 'employee_id')
                    ->where('exit_status', 'exited');
    }

    // Add accessors for exit status
    public function getExitStatusAttribute()
    {
        $activeExit = $this->activeExit;
        return $activeExit ? $activeExit->exit_status : null;
    }

    public function getExitNoticeStartDateAttribute()
    {
        $activeExit = $this->activeExit;
        return $activeExit ? $activeExit->notice_start_date : null;
    }

    public function getExitNoticeEndDateAttribute()
    {
        $activeExit = $this->activeExit;
        return $activeExit ? $activeExit->notice_end_date : null;
    }

    public function getExitNoticeDaysAttribute()
    {
        $activeExit = $this->activeExit;
        return $activeExit ? $activeExit->notice_period_days : null;
    }

    public function getExitDaysRemainingAttribute()
    {
        $activeExit = $this->activeExit;
        return $activeExit ? $activeExit->days_remaining : 0;
    }

    public function getIsExitOverdueAttribute()
    {
        $activeExit = $this->activeExit;
        return $activeExit ? $activeExit->is_overdue : false;
    }

}

