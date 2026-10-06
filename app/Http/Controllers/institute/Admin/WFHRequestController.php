<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WFHRequest;
use App\Models\EmployeeDetails;
use App\Models\EmployeeShift;
use App\Models\DepartmentShift;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WFHRequestController extends Controller
{
    /**
     * Display a listing of the employee's WFH requests with tracking
     */
    public function index(Request $request)
    {
        try {
            $employee = EmployeeDetails::where('user_id', Auth::id())->firstOrFail();
            
            // Build query
            $query = WFHRequest::where('employee_id', $employee->employee_id)
                ->orderBy('created_at', 'desc');
            
            // Apply filters
            if ($request->filled('status')) {
                $query->where('request_status', $request->status);
            }
            
            if ($request->filled('date_from')) {
                $query->whereDate('start_date', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->whereDate('end_date', '<=', $request->date_to);
            }
            
            // Get requests with pagination
            $requests = $query->paginate(10);
            
            // Get statistics
            $stats = $this->getStatistics($employee->employee_id);
            
            // Get upcoming requests
            $upcoming = $this->getUpcomingRequests($employee->employee_id);
            
            return view('instituteAdmin.WFHRequests.trackrequest', [
                'requests' => $requests,
                'stats' => $stats,
                'upcoming' => $upcoming,
                'filters' => $request->all()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error loading WFH requests: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load requests');
        }
    }
    
    /**
     * Get statistics for the employee
     */
    private function getStatistics($employeeId)
    {
        return [
            'total' => WFHRequest::where('employee_id', $employeeId)->count(),
            'pending' => WFHRequest::where('employee_id', $employeeId)
                ->where('request_status', 'pending')->count(),
            'approved' => WFHRequest::where('employee_id', $employeeId)
                ->where('request_status', 'approved')->count(),
            'rejected' => WFHRequest::where('employee_id', $employeeId)
                ->where('request_status', 'rejected')->count(),
            'completed' => WFHRequest::where('employee_id', $employeeId)
                ->where('request_status', 'completed')->count(),
            'cancelled' => WFHRequest::where('employee_id', $employeeId)
                ->where('request_status', 'cancelled')->count(),
            'active_today' => WFHRequest::where('employee_id', $employeeId)
                ->where('request_status', 'approved')
                ->whereDate('start_date', '<=', today())
                ->whereDate('end_date', '>=', today())
                ->count(),
        ];
    }
    
    /**
     * Get upcoming approved requests
     */
    private function getUpcomingRequests($employeeId)
    {
        return WFHRequest::where('employee_id', $employeeId)
            ->where('request_status', 'approved')
            ->whereDate('start_date', '>', today())
            ->orderBy('start_date', 'asc')
            ->limit(5)
            ->get();
    }
    
    /**
     * Show the form for creating a new WFH request
     */
    public function create()
    {
        try {
            $user = Auth::user();
            $employee = EmployeeDetails::where('user_id', $user->id)->firstOrFail();
          
            // Get shift details for today (default)
            $shiftData = $this->getEmployeeEffectiveShift($employee);
            $shiftDetails = $this->getShiftDetails($shiftData);
            
            // Get employee details with emergency contact
            $employeeDetails = EmployeeDetails::where('user_id', $user->id)
                ->select('contact_person_name', 'emergency_contact_number')
                ->first();
            
            return view('instituteAdmin.WFHRequests.create', [
                'shiftDetails' => $shiftDetails,
                'employeeDetails' => $employeeDetails,
                'selectedStartDate' => date('Y-m-d'),
                'selectedEndDate' => date('Y-m-d')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error loading WFH form: ' . $e->getMessage());
            return view('instituteAdmin.WFHRequests.create', [
                'shiftDetails' => null,
                'employeeDetails' => null,
                'selectedStartDate' => date('Y-m-d'),
                'selectedEndDate' => date('Y-m-d')
            ]);
        }
    }
    
    /**
     * Get shifts for the selected date range
     */
    public function getShiftsForDateRange(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);
            
            $employee = EmployeeDetails::where('user_id', Auth::id())->firstOrFail();
            
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            
            // Get all shifts for the date range
            $shiftsData = $this->getEmployeeShiftsForDateRange($employee, $startDate, $endDate);
            
            // Calculate duration
            $duration = $this->calculateDuration($request->start_date, $request->end_date);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'shifts' => $shiftsData,
                    'duration' => $duration,
                    'total_days' => $startDate->diffInDays($endDate) + 1
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching shifts for date range: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch shift information: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get employee shifts for a date range
     */
    private function getEmployeeShiftsForDateRange($employee, $startDate, $endDate)
    {
        $shifts = [];
        $currentDate = clone $startDate;
        $instituteId = $employee->institute_id;
        $branchId = $employee->branch_id;
        
        // Get all active shifts for the employee
        $allShifts = $this->getAllEmployeeShifts($employee);
        
        // If no shifts found, return default
        if ($allShifts->isEmpty()) {
            return [
                [
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'shift_name' => 'No Shift Assigned',
                    'start_time' => null,
                    'end_time' => null,
                    'is_weekly_off' => false,
                    'days' => $startDate->diffInDays($endDate) + 1
                ]
            ];
        }
        
        // Process each day in the range
        $currentGroup = null;
        $groups = [];
        
        while ($currentDate <= $endDate) {
            $dateString = $currentDate->format('Y-m-d');
            $dayOfWeek = $currentDate->format('l');
            
            // Find applicable shift for this date
            $applicableShift = null;
            foreach ($allShifts as $shift) {
                $shiftStart = Carbon::parse($shift->shift->start_date);
                $shiftEnd = $shift->shift->end_date ? Carbon::parse($shift->shift->end_date) : null;
                
                if ($shiftStart <= $currentDate && ($shiftEnd === null || $shiftEnd >= $currentDate)) {
                    $applicableShift = $shift;
                    break;
                }
            }
            
            // Check if it's a weekly off
            $isWeeklyOff = false;
            $shiftName = 'No Shift Assigned';
            $startTime = null;
            $endTime = null;
            $priority = 'low';
            
            if ($applicableShift && $applicableShift->shift) {
                $shift = $applicableShift->shift;
                $shiftName = $shift->shift_name;
                $startTime = $shift->start_time;
                $endTime = $shift->end_time;
                $priority = $shift->priority;
                
                // Check weekly off
                $weeklyOffs = [];
                if ($shift->weekly_off_days) {
                    try {
                        $weeklyOffs = is_array($shift->weekly_off_days) 
                            ? $shift->weekly_off_days 
                            : json_decode($shift->weekly_off_days, true);
                        if (!is_array($weeklyOffs)) {
                            $weeklyOffs = [];
                        }
                    } catch (\Exception $e) {
                        $weeklyOffs = [];
                    }
                }
                $isWeeklyOff = in_array($dayOfWeek, $weeklyOffs);
            }
            
            // Group consecutive days with same shift
            $groupKey = $shiftName . '|' . ($startTime ?? '') . '|' . ($endTime ?? '');
            
            if ($currentGroup === null || $currentGroup['key'] !== $groupKey) {
                if ($currentGroup !== null) {
                    $groups[] = $currentGroup;
                }
                $currentGroup = [
                    'key' => $groupKey,
                    'shift_name' => $shiftName,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'is_weekly_off' => $isWeeklyOff,
                    'priority' => $priority,
                    'start_date' => $dateString,
                    'end_date' => $dateString,
                    'days' => 1,
                    'dates' => [$dateString]
                ];
            } else {
                // Extend the current group
                $currentGroup['end_date'] = $dateString;
                $currentGroup['days']++;
                $currentGroup['dates'][] = $dateString;
            }
            
            $currentDate->addDay();
        }
        
        // Add the last group
        if ($currentGroup !== null) {
            $groups[] = $currentGroup;
        }
        
        return $groups;
    }
    
    /**
     * Get all employee shifts (both individual and department)
     */
    private function getAllEmployeeShifts($employee)
    {
        $instituteId = $employee->institute_id;
        $branchId = $employee->branch_id;
        $currentDate = Carbon::now()->toDateString();

        // Individual shifts
        $individualShifts = EmployeeShift::where('institute_id', $instituteId)
            ->where('employee_id', $employee->employee_id)
            ->where('status', 'active')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId),
                            fn($q) => $q->whereNull('branch_id'))
            ->with('shift')
            ->get()
            ->filter(fn($s) => $s->shift !== null)
            ->each(function ($shift) {
                $shift->type = 'individual';
                $shift->priority_value = $this->getPriorityValue($shift->shift->priority);
            });

        // Department shifts
        $departmentShifts = collect([]);
        if ($employee->department_id) {
            $departmentShifts = DepartmentShift::where('institute_id', $instituteId)
                ->where('department_id', $employee->department_id)
                ->where('status', 'active')
                ->where('shift_type', 'employee')
                ->when($branchId, fn($q) => $q->where('branch_id', $branchId),
                                fn($q) => $q->whereNull('branch_id'))
                ->with('shift')
                ->get()
                ->filter(fn($s) => $s->shift !== null)
                ->each(function ($shift) {
                    $shift->type = 'department';
                    $shift->priority_value = $this->getPriorityValue($shift->shift->priority);
                });
        }

        // Merge and sort by priority
        $allShifts = $individualShifts->merge($departmentShifts);
        
        // Sort by priority (high to low) and then by created_at
        $sorted = $allShifts->sortByDesc(function ($s) {
            return [$s->priority_value, $s->created_at->timestamp];
        });

        return $sorted;
    }
    
    /**
     * Calculate duration between two dates
     */
    private function calculateDuration($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $days = $start->diffInDays($end) + 1;
        return $days . ' day' . ($days > 1 ? 's' : '');
    }
    
    /**
     * Store a newly created WFH request
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
                'reason' => 'nullable|string|max:500',
                'work_plan' => 'nullable|string|max:1000',
            ]);

            $employee = EmployeeDetails::where('user_id', Auth::id())->firstOrFail();
            
            // Get shifts for the date range
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $shiftsData = $this->getEmployeeShiftsForDateRange($employee, $startDate, $endDate);
            
            // Get the first shift for timings (for backward compatibility)
            $firstShift = $shiftsData[0] ?? null;
            $startTime = $firstShift['start_time'] ?? null;
            $endTime = $firstShift['end_time'] ?? null;
            $shiftNames = array_unique(array_column($shiftsData, 'shift_name'));

            // Check for overlapping requests
            $overlapping = WFHRequest::where('employee_id', $employee->employee_id)
                ->where('is_active', true)
                ->whereIn('request_status', ['pending', 'approved'])
                ->where(function($q) use ($request) {
                    $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function($q2) use ($request) {
                        $q2->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
                })
                ->exists();

            if ($overlapping) {
                return redirect()->back()
                    ->with('error', 'You already have a pending or approved WFH request for this date range.')
                    ->withInput();
            }

            // Create request with shift timings
            $wfhRequest = WFHRequest::create([
                'institute_id' => $employee->institute_id,
                'branch_id' => $employee->branch_id,
                'employee_id' => $employee->employee_id,
                'request_date' => now()->toDateString(),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'reason' => $request->reason,
                'work_plan' => $request->work_plan,
                'emergency_contact' => $employee->emergency_contact_number ?? null,
                'request_status' => 'pending',
                'is_active' => true,
                'additional_data' => [
                    'created_by_employee' => true,
                    'employee_name' => $employee->name,
                    'shifts' => $shiftsData,
                    'shift_names' => implode(' → ', $shiftNames),
                    'emergency_contact_person' => $employee->contact_person_name ?? null,
                ]
            ]);

            return redirect()->route('employee.wfh-requests.trackrequest')
                ->with('success', 'WFH request submitted successfully. Request ID: ' . $wfhRequest->request_id);

        } catch (\Exception $e) {
            Log::error('Error creating WFH request: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to submit request: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display the specified WFH request details
     */
    public function show($id)
    {
        try {
            $employee = EmployeeDetails::where('user_id', Auth::id())->firstOrFail();
            
            $request = WFHRequest::where('employee_id', $employee->employee_id)
                ->with(['processor'])
                ->findOrFail($id);
            
            // Get timeline events
            $timeline = $this->getRequestTimeline($request);
            
            // Get shifts breakdown if available
            $shiftsBreakdown = $request->additional_data['shifts'] ?? null;
            
            return view('instituteAdmin.WFHRequests.detailedrequestview', [
                'request' => $request,
                'timeline' => $timeline,
                'shiftsBreakdown' => $shiftsBreakdown
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error viewing WFH request: ' . $e->getMessage());
            return redirect()->route('employee.wfh-requests.trackrequest')
                ->with('error', 'Request not found.');
        }
    }
    
    /**
     * Cancel a pending WFH request
     */
    public function cancel($id)
    {
        try {
            $employee = EmployeeDetails::where('user_id', Auth::id())->firstOrFail();
            
            $request = WFHRequest::where('employee_id', $employee->employee_id)
                ->findOrFail($id);

            if (!$request->isPending()) {
                return redirect()->back()
                    ->with('error', 'Only pending requests can be cancelled.');
            }

            $request->cancel();

            return redirect()->back()
                ->with('success', 'Request cancelled successfully.');

        } catch (\Exception $e) {
            Log::error('Error cancelling WFH request: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to cancel request.');
        }
    }
    
    /**
     * Get request timeline events
     */
    private function getRequestTimeline($request)
    {
        $timeline = [];
        
        // Created event
        $timeline[] = [
            'date' => $request->created_at,
            'title' => 'Request Created',
            'description' => 'Work from home request was submitted',
            'icon' => 'fas fa-plus-circle',
            'color' => 'primary'
        ];
        
        // Status changes
        if ($request->approved_at) {
            $timeline[] = [
                'date' => $request->approved_at,
                'title' => 'Request Approved',
                'description' => $request->admin_remarks ?? 'Request was approved by admin',
                'icon' => 'fas fa-check-circle',
                'color' => 'success'
            ];
        }
        
        if ($request->rejected_at) {
            $timeline[] = [
                'date' => $request->rejected_at,
                'title' => 'Request Rejected',
                'description' => $request->admin_remarks ?? 'Request was rejected by admin',
                'icon' => 'fas fa-times-circle',
                'color' => 'danger'
            ];
        }
        
        if ($request->completed_at) {
            $timeline[] = [
                'date' => $request->completed_at,
                'title' => 'Request Completed',
                'description' => 'Work from home period has been completed',
                'icon' => 'fas fa-flag-checkered',
                'color' => 'info'
            ];
        }
        
        if ($request->deleted_at) {
            $timeline[] = [
                'date' => $request->deleted_at,
                'title' => 'Request Cancelled',
                'description' => 'Request was cancelled',
                'icon' => 'fas fa-ban',
                'color' => 'warning'
            ];
        }
        
        // Sort by date
        usort($timeline, function($a, $b) {
            return $a['date']->timestamp - $b['date']->timestamp;
        });
        
        return $timeline;
    }
    
    /**
     * Get employee effective shift for a specific date (kept for backward compatibility)
     */
    private function getEmployeeEffectiveShiftForDate($employee, $date)
    {
        $currentDate = Carbon::parse($date)->toDateString();
        $instituteId = $employee->institute_id;
        $branchId = $employee->branch_id;

        $individualShifts = EmployeeShift::where('institute_id', $instituteId)
            ->where('employee_id', $employee->employee_id)
            ->where('status', 'active')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId),
                            fn($q) => $q->whereNull('branch_id'))
            ->with(['shift' => function($q) use ($currentDate) {
                $q->where('start_date', '<=', $currentDate)
                ->where(function($q2) use ($currentDate) {
                    $q2->where('end_date', '>=', $currentDate)  
                        ->orWhereNull('end_date'); 
                });
            }])
            ->get()
            ->filter(fn($s) => $s->shift !== null)
            ->each(function ($shift) {
                $shift->type = 'individual';
                $shift->priority_value = $this->getPriorityValue($shift->shift->priority);
            });

        $departmentShifts = collect([]);

        if ($employee->department_id) {
            $departmentShifts = DepartmentShift::where('institute_id', $instituteId)
                ->where('department_id', $employee->department_id)
                ->where('status', 'active')
                ->where('shift_type', 'employee')
                ->when($branchId, fn($q) => $q->where('branch_id', $branchId),
                                fn($q) => $q->whereNull('branch_id'))
                ->with(['shift' => function($q) use ($currentDate) {
                    $q->where('start_date', '<=', $currentDate)
                    ->where(function($q2) use ($currentDate) {
                        $q2->where('end_date', '>=', $currentDate)
                            ->orWhereNull('end_date');
                    });
                }])
                ->get()
                ->filter(fn($s) => $s->shift !== null)
                ->each(function ($shift) {
                    $shift->type = 'department';
                    $shift->priority_value = $this->getPriorityValue($shift->shift->priority);
                });
        }

        $allShifts = $individualShifts->merge($departmentShifts);

        if ($allShifts->isEmpty()) return null;

        $sorted = $allShifts->sortByDesc(function ($s) {
            return [$s->priority_value, $s->created_at->timestamp];
        });

        return $sorted->first();
    }

    private function getEmployeeEffectiveShift($employee)
    {
        $currentDate = Carbon::now()->toDateString();
        $instituteId = $employee->institute_id;
        $branchId = $employee->branch_id;

        $individualShifts = EmployeeShift::where('institute_id', $instituteId)
            ->where('employee_id', $employee->employee_id)
            ->where('status', 'active')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId),
                            fn($q) => $q->whereNull('branch_id'))
            ->with(['shift' => function($q) use ($currentDate) {
                $q->where('start_date', '<=', $currentDate)
                ->where(function($q2) use ($currentDate) {
                    $q2->where('end_date', '>=', $currentDate)  
                        ->orWhereNull('end_date'); 
                });
            }])
            ->get()
            ->filter(fn($s) => $s->shift !== null)
            ->each(function ($shift) {
                $shift->type = 'individual';
                $shift->priority_value = $this->getPriorityValue($shift->shift->priority);
            });

        $departmentShifts = collect([]);

        if ($employee->department_id) {
            $departmentShifts = DepartmentShift::where('institute_id', $instituteId)
                ->where('department_id', $employee->department_id)
                ->where('status', 'active')
                ->where('shift_type', 'employee')
                ->when($branchId, fn($q) => $q->where('branch_id', $branchId),
                                fn($q) => $q->whereNull('branch_id'))
                ->with(['shift' => function($q) use ($currentDate) {
                    $q->where('start_date', '<=', $currentDate)
                    ->where(function($q2) use ($currentDate) {
                        $q2->where('end_date', '>=', $currentDate)
                            ->orWhereNull('end_date');
                    });
                }])
                ->get()
                ->filter(fn($s) => $s->shift !== null)
                ->each(function ($shift) {
                    $shift->type = 'department';
                    $shift->priority_value = $this->getPriorityValue($shift->shift->priority);
                });
        }

        $allShifts = $individualShifts->merge($departmentShifts);

        if ($allShifts->isEmpty()) return null;

        $sorted = $allShifts->sortByDesc(function ($s) {
            return [$s->priority_value, $s->created_at->timestamp];
        });

        return $sorted->first();
    }

    private function getShiftDetails($shiftData)
    {
        if (!$shiftData) {
            return [
                'shift_name' => 'No Shift Assigned',
                'start_time' => null,
                'end_time' => null,
                'weekly_off_days' => [],
                'is_active' => false,
                'message' => 'No shift assigned'
            ];
        }

        $shift = $shiftData->shift;
        $currentDate = Carbon::now()->toDateString();

        $isActive = true;

        if ($shift->start_date && Carbon::parse($shift->start_date)->gt($currentDate)) {
            $isActive = false;
        }

        if ($shift->end_date && Carbon::parse($shift->end_date)->lt($currentDate)) {
            $isActive = false;
        }

        $weeklyOffs = [];
        if ($shift->weekly_off_days) {
            try {
                $weeklyOffs = is_array($shift->weekly_off_days) 
                    ? $shift->weekly_off_days 
                    : json_decode($shift->weekly_off_days, true);
                if (!is_array($weeklyOffs)) {
                    $weeklyOffs = [];
                }
            } catch (\Exception $e) {
                $weeklyOffs = [];
            }
        }

        return [
            'shift_name' => $shift->shift_name,
            'start_time' => $shift->start_time,
            'end_time' => $shift->end_time,
            'weekly_off_days' => $weeklyOffs,
            'is_active' => $isActive
        ];
    }

    private function getPriorityValue($priority)
    {
        $priorityValues = [
            'high' => 3,
            'medium' => 2,
            'low' => 1
        ];
        return $priorityValues[strtolower($priority)] ?? 1;
    }
}