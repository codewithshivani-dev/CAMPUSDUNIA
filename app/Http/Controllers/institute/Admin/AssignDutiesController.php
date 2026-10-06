<?php

namespace App\Http\Controllers\Institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssignDuties;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use App\Models\DutyType;
use App\Models\AddBlock;
use App\Models\AddFloor;
use App\Models\AddRooms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Traits\EmployeeAvailability;
use App\Traits\InstituteBranchAccess;

class AssignDutiesController extends Controller
{
    use EmployeeAvailability, InstituteBranchAccess;

    /**
     * Display a listing of duty assignments.
     */
    public function index(Request $request)
    {
        $instituteId = auth()->user()->institute_id;
        
        $query = AssignDuties::with(['employee', 'assignedBy', 'supervisor', 'dutyType'])
            ->where('institute_id', $instituteId);
            
        // Apply filters
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        
        if ($request->filled('duty_type_id')) {
            $query->where('duty_type_id', $request->duty_type_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $query->where(function($q) use ($dates) {
                    $q->whereBetween('date', [Carbon::parse($dates[0]), Carbon::parse($dates[1])])
                      ->orWhere(function($q2) use ($dates) {
                          $q2->where('frequency', '!=', 'once')
                             ->where('from_date', '<=', Carbon::parse($dates[1]))
                             ->where('to_date', '>=', Carbon::parse($dates[0]));
                      });
                });
            }
        }
        
        $duties = $query->latest()->paginate(20);
        
        // Get data for filters and forms
        $employees = EmployeeDetails::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->get();
        
        $departments = Departments::where('institute_id', $instituteId)->get();
        
        // Get duty types from database
        $dutyTypes = DutyType::where('institute_id', $instituteId)
            ->active()
            ->orderBy('duty_type')
            ->get();
        
        return view('instituteAdmin.AssignDuties.assignduties', compact(
            'duties', 
            'employees', 
            'departments', 
            'dutyTypes'
        ));
    }

    /**
     * Show the form for creating a new duty assignment.
     */
    public function create()
    {
        $instituteId = auth()->user()->institute_id;
        
        // Get departments for dropdown
        $departments = Departments::where('institute_id', $instituteId)
            ->when(auth()->user()->is_branch_admin && auth()->user()->branch_id, function($query) {
                $query->where('branch_id', auth()->user()->branch_id);
            }, function($query) {
                $query->whereNull('branch_id');
            })
            ->orderBy('department')
            ->get();

        $blocks = AddBlock::where('status', 'active')->orwhere('institute_id', $instituteId)
            ->with('building')
            ->orderBy('name')
            ->get();    
        
        // Get duty types from database
        $dutyTypes = DutyType::where('institute_id', $instituteId)
            ->when(auth()->user()->is_branch_admin && auth()->user()->branch_id, function($query) {
                $query->where('branch_id', auth()->user()->branch_id);
            }, function($query) {
                $query->whereNull('branch_id');
            })
            ->active()
            ->orderBy('duty_type')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->id => $item->duty_type];
            })
            ->toArray();
        
        return view('instituteAdmin.AssignDuties.create', compact('dutyTypes', 'departments','blocks'));
    }

    /**
     * Store a newly created duty type.
     */
    public function storeDutyType(Request $request)
    {
        $request->validate([
            'duty_type' => 'required|string|max:100|unique:duty_types,duty_type,NULL,id,institute_id,' . auth()->user()->institute_id,
            'description' => 'nullable|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            $dutyType = DutyType::create([
                'employee_duty_type_id' => 'DUTY-TYPE-' . strtoupper(Str::random(6)),
                'institute_id' => auth()->user()->institute_id,
                'branch_id' => auth()->user()->is_branch_admin ? auth()->user()->branch_id : null,
                'duty_type' => $request->duty_type,
                'description' => $request->description,
                'is_active' => true,
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Duty type created successfully!',
                'duty_type' => [
                    'id' => $dutyType->id,
                    'duty_type' => $dutyType->duty_type,
                    'description' => $dutyType->description,
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating duty type: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create duty type: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created duty assignment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'department_id' => 'required',
            'duty_type_id' => 'required',
            'description' => 'nullable|string',
            'frequency' => 'required|in:daily,weekly,monthly,once',
            'date' => 'required_if:frequency,once|date',
            'from_date' => 'required_if:frequency,daily,weekly,monthly|date',
            'to_date' => 'required_if:frequency,daily,weekly,monthly|date|after_or_equal:from_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'days_of_week' => 'required_if:frequency,weekly|array',
            'days_of_week.*' => 'integer|between:0,6',
            'day_of_month' => 'required_if:frequency,monthly|integer|between:1,31',
            'priority' => 'required|in:low,medium,high,urgent',
            'instructions' => 'nullable|string',
            'required_materials' => 'nullable|string',
            'block_id' => 'nullable',
            'floor_id' => 'nullable',
            'room_id' => 'nullable',
        ]);
       
        $dutytypeId = $request->duty_type_id;
        // Get duty type from database
        $dutyType = DutyType::where('id', $dutytypeId)->first();
        $dutyTypeId = $dutyType->employee_duty_type_id;
    
        // Generate a unique employee_duty_id
        $employeeDutyId = 'EMP-DUTY-' . strtoupper(Str::random(8));
        
        // Check employee availability
        $context = $this->getContextForCurrentUser();
        $timeSlot = $this->prepareTimeSlot($request);
        
        $availability = $this->checkEmployeeAvailability(
            $request->employee_id,
            $timeSlot,
            $context
        );
       
        // Start database transaction
        DB::beginTransaction();
        
        // try {
            // If employee is not available, mark conflicting lectures as on hold
            if (!$availability['available'] && !empty($availability['conflicting_assignments'])) {
                $this->markAssignmentsAsOnHold(
                    $request->employee_id,
                    $availability['conflicting_assignments'],
                    array_merge($request->all(), ['employee_duty_id' => $employeeDutyId])
                );
            }
            
            // Create duty assignment with employee_duty_id
            $duty = AssignDuties::create([
                'institute_id' => auth()->user()->institute_id,
                'employee_id' => $request->employee_id,
                'employee_duty_id' => $employeeDutyId, // Custom ID instead of auto-increment
                'department_id' => $request->department_id,
                'employee_duty_type_id' => $dutyTypeId,
                'duty_type' => $dutyType->duty_type,
                'description' => $request->description,
                'frequency' => $request->frequency,
                'date' => $request->frequency === 'once' ? $request->date : null,
                'from_date' => $request->frequency !== 'once' ? $request->from_date : null,
                'to_date' => $request->frequency !== 'once' ? $request->to_date : null,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'days_of_week' => $request->days_of_week ? json_encode($request->days_of_week) : null,
                'day_of_month' => $request->day_of_month,
                'location' => $this->getLocationString($request), // Combine block/floor/room
                'venue' => $this->getVenueDetails($request), // Get venue details
                'priority' => $request->priority,
                'status' => 'assigned',
                'assigned_by' => auth()->id(),
                'instructions' => $request->instructions,
                'required_materials' => $request->required_materials,
                'assigned_at' => now(),
                'has_conflicts' => !$availability['available'],
                'block_id' => $request->block_id,
                'floor_id' => $request->floor_id,
                'room_id' => $request->room_id,
            ]);
            
            DB::commit();
            
            return redirect()->route('institute.duties.index')
                ->with('success', 'Duty assigned successfully! Employee Duty ID: ' . $employeeDutyId . 
                    (!$availability['available'] ? ' Note: Some lectures were marked as "On Hold".' : ''));
                
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     \Log::error('Error assigning duty: ' . $e->getMessage());
            
        //     return back()->withInput()
        //         ->with('error', 'Failed to assign duty: ' . $e->getMessage());
        // }
    }
    
    /**
     * Get location string from block, floor, and room
     */
    private function getLocationString(Request $request): ?string
    {
    $parts = [];
    
    if ($request->filled('block_id')) {
        $block = AddBlock::find($request->block_id);
        if ($block) {
            $parts[] = $block->name;
        }
    }
    
    if ($request->filled('floor_id')) {
        $floor = AddFloor::find($request->floor_id);
        if ($floor) {
            $parts[] = 'Floor ' . $floor->floor_number;
        }
    }
    
    if ($request->filled('room_id')) {
        $room = AddRooms::find($request->room_id);
        if ($room) {
            $parts[] = $room->room_name;
        }
    }
    
    return !empty($parts) ? implode(' - ', $parts) : null;
    }

    /**
     * Get venue details from location
     */
    private function getVenueDetails(Request $request): ?string
    {
        if ($request->filled('room_id')) {
            $room = AddRooms::find($request->room_id);
            if ($room && $room->room_name) {
                return $room->room_name;
            }
        }
        
        if ($request->filled('block_id')) {
            $block = AddBlock::find($request->block_id);
            if ($block && $block->name) {
                return $block->name;
            }
        }
        
        return null;
    }

    /**
     * Prepare time slot for availability check.
     */
    private function prepareTimeSlot(Request $request): array
    {
        $timeSlot = [
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'frequency' => $request->frequency,
        ];
        
        if ($request->frequency === 'once') {
            $timeSlot['date'] = $request->date;
        } else {
            $timeSlot['valid_from'] = $request->from_date;
            $timeSlot['valid_to'] = $request->to_date;
            
            if ($request->frequency === 'weekly' && $request->has('days_of_week')) {
                $timeSlot['days_of_week'] = $request->days_of_week;
            } else if ($request->frequency === 'monthly') {
                $timeSlot['day_of_month'] = $request->day_of_month;
            }
        }
        
        return $timeSlot;
    }

    /**
     * Mark conflicting assignments as on hold
     */
    private function markAssignmentsAsOnHold(string $employeeId, array $conflictingAssignments, array $dutyData)
    {
        // Separate lectures and duties
        $lectureIds = [];
        $dutyIds = [];
        
        foreach ($conflictingAssignments as $assignment) {
            if ($assignment['type'] === 'lecture') {
                $lectureIds[] = $assignment['id'];
            } else if ($assignment['type'] === 'duty') {
                $dutyIds[] = $assignment['id'];
            }
        }
        
        // Update lectures to on hold status
        if (!empty($lectureIds)) {
            $updated = DB::table('employee_subject_lectures')
                ->whereIn('emp_assign_subject_id', $lectureIds)
                ->update([
                    'status' => 'on_hold',
                    'hold_reason' => 'Duty Assignment: ' . ($dutyData['title'] ?? 'Unknown'),
                    'hold_start_date' => $dutyData['frequency'] === 'once' ? $dutyData['date'] : $dutyData['from_date'],
                    'hold_end_date' => $dutyData['frequency'] === 'once' ? $dutyData['date'] : $dutyData['to_date'],
                    'hold_duty_id' => 'EMP-DUTY-' . strtoupper(Str::random(6)), 
                    'updated_at' => now(),
                ]);
        }
        
        // Update duties to on hold status
        if (!empty($dutyIds)) {
            $updated = DB::table('employee_duties')
                ->whereIn('id', $dutyIds)
                ->update([
                    'status' => 'on_hold',
                    'hold_reason' => 'Higher Priority Duty: ' . ($dutyData['title'] ?? 'Unknown'),
                    'hold_start_date' => $dutyData['frequency'] === 'once' ? $dutyData['date'] : $dutyData['from_date'],
                    'hold_end_date' => $dutyData['frequency'] === 'once' ? $dutyData['date'] : $dutyData['to_date'],
                    'updated_at' => now(),
                ]);
        }
    }

     /**
     * Get context for current user
     */
    private function getContextForCurrentUser(): array
    {
        return [
            'institute_id' => auth()->user()->institute_id,
            'branch_id' => auth()->user()->is_branch_admin ? auth()->user()->branch_id : null,
            'is_branch_admin' => auth()->user()->is_branch_admin,
        ];
    }
    
    /**
     * Display the specified duty assignment.
     */
    public function show($id)
    {       
        $duty = AssignDuties::with([
            'employee.department',
            'assignedBy',
            'supervisor',
            'dutyType'
        ])->findOrFail($id);

        $departments = Departments::where('department_id', $duty->department_id)->first();
        $departments = $departments->department;

        $block = AddBlock::find($duty->block_id);
        $floor = AddFloor::find($duty->floor_id);
        $room  = AddRooms::find($duty->room_id);

        // Check if user belongs to the same institute
        if ($duty->institute_id != auth()->user()->institute_id) {
            abort(403, 'Unauthorized access.');
        }
        // dd($blocks);
        return view('instituteAdmin.AssignDuties.show', compact('duty','departments', 'block', 'floor', 'room'));
    }
    
    /**
     * Show the form for editing the duty assignment.
     */
    public function edit($id)
    {
        $duty = AssignDuties::with(['employee', 'dutyType'])->findOrFail($id);

        // Check if user belongs to the same institute
        if ($duty->institute_id != auth()->user()->institute_id) {
            abort(403, 'Unauthorized access.');
        }
        
        $instituteId = auth()->user()->institute_id;

        $employees = EmployeeDetails::where('institute_id', $instituteId)
            ->where('status', 'active')
            ->get();
        
        // Get duty types from database
        $dutyTypes = DutyType::where('institute_id', $instituteId)
            ->active()
            ->orderBy('duty_type')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->employee_duty_type_id => $item->duty_type];
            })
            ->toArray();
        // dd($dutyTypes);
        $departments = Departments::where('department_id', $duty->department_id)->first();
        $departments = $departments->department;
        // dd($duty);
        $blocks = AddBlock::where('status', 'active')
            ->where('institute_id', $instituteId)
            ->orderBy('name')
            ->get();
        
        $floors = AddFloor::where('institute_id', $instituteId)->get();
        // dd($floors);
        $rooms = AddRooms::where('institute_id', $instituteId)->get();

        return view('instituteAdmin.AssignDuties.edit', compact('duty', 'departments', 'employees', 'blocks', 'floors', 'rooms', 'dutyTypes'));
    }
    
    /**
     * Update the specified duty assignment.
     */
    public function update(Request $request, $id)
    {
        $duty = AssignDuties::findOrFail($id);
        
        // Check if user belongs to the same institute
        if ($duty->institute_id != auth()->user()->institute_id) {
            abort(403, 'Unauthorized access.');
        }
        
        $validated = $request->validate([
            'employee_id' => 'required',
            'duty_type_id' => 'required|exists:duty_types,id',
            'description' => 'nullable|string',
            'frequency' => 'required|in:daily,weekly,monthly,once',
            'date' => 'required_if:frequency,once|date',
            'from_date' => 'required_if:frequency,daily,weekly,monthly|date',
            'to_date' => 'required_if:frequency,daily,weekly,monthly|date|after_or_equal:from_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'days_of_week' => 'required_if:frequency,weekly|array',
            'days_of_week.*' => 'integer|between:0,6',
            'day_of_month' => 'required_if:frequency,monthly|integer|between:1,31',
            'location' => 'nullable|string|max:255',
            'venue' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,assigned,in_progress,completed,cancelled,on_hold',
            'instructions' => 'nullable|string',
            'required_materials' => 'nullable|string',
        ]);
        
        // Get duty type from database
        $dutyType = DutyType::findOrFail($request->duty_type_id);
        $validated['duty_type'] = $dutyType->duty_type;
        $validated['duty_type_id'] = $dutyType->id;
        
        // Check for time conflicts if requested
        if ($request->has('check_conflict')) {
            $conflicts = $this->checkForConflicts($request, $id);
            if ($conflicts->isNotEmpty()) {
                return response()->json([
                    'has_conflict' => true,
                    'conflicts' => $conflicts
                ]);
            }
            return response()->json(['has_conflict' => false]);
        }
        
        // Update duty assignment
        $duty->update($validated);
        
        // If status is completed, set completed_at
        if ($request->status == 'completed' && !$duty->completed_at) {
            $duty->update(['completed_at' => now()]);
        }
        
        return redirect()->route('institute.duties.show', $duty->id)
            ->with('success', 'Duty updated successfully!');
    }
    
    /**
     * Remove the specified duty assignment.
     */
    public function destroy($id)
    {
        $duty = AssignDuties::findOrFail($id);
        
        // Check if user belongs to the same institute
        if ($duty->institute_id != auth()->user()->institute_id) {
            abort(403, 'Unauthorized access.');
        }
        
        $duty->delete();
        
        return redirect()->route('institute.duties.index')
            ->with('success', 'Duty deleted successfully!');
    }
    
    
      /**
     * Helper function to check for conflicts.
     */
    private function checkForConflicts(Request $request, $excludeId = null)
    {
        $query = AssignDuties::with('employee')
            ->where('employee_id', $request->employee_id)
            ->where('status', '!=', 'cancelled');
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        $conflicts = collect();
        
        if ($request->frequency == 'once') {
            $date = Carbon::parse($request->date);
            
            $conflicts = $query->where(function($q) use ($date, $request) {
                // Check one-time duties on the same date
               
                $q->where('frequency', 'once')
                  ->where('date', $date)
                  ->where(function($q2) use ($request) {
                      $q2->whereBetween('start_time', [$request->start_time, $request->end_time])
                         ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                         ->orWhere(function($q3) use ($request) {
                             $q3->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                         });
                  });
                
                // Check recurring duties that include this date
                $q->orWhere(function($q3) use ($date, $request) {
                    $q3->where('frequency', '!=', 'once')
                       ->where('from_date', '<=', $date)
                       ->where('to_date', '>=', $date)
                       ->where(function($q4) use ($request) {
                           $q4->whereBetween('start_time', [$request->start_time, $request->end_time])
                              ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                              ->orWhere(function($q5) use ($request) {
                                  $q5->where('start_time', '<=', $request->start_time)
                                     ->where('end_time', '>=', $request->end_time);
                              });
                       });
                });
            })->get();
            
        } else {
            // For recurring duties, check for overlapping date ranges and times
            $fromDate = Carbon::parse($request->from_date);
            $toDate = Carbon::parse($request->to_date);
            
            $conflicts = $query->where(function($q) use ($fromDate, $toDate, $request) {
                // Check overlapping date ranges
                $q->where(function($q2) use ($fromDate, $toDate) {
                    $q2->whereBetween('from_date', [$fromDate, $toDate])
                       ->orWhereBetween('to_date', [$fromDate, $toDate])
                       ->orWhere(function($q3) use ($fromDate, $toDate) {
                           $q3->where('from_date', '<=', $fromDate)
                              ->where('to_date', '>=', $toDate);
                       });
                });
                
                // Check time overlap
                $q->where(function($q4) use ($request) {
                    $q4->whereBetween('start_time', [$request->start_time, $request->end_time])
                       ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                       ->orWhere(function($q5) use ($request) {
                           $q5->where('start_time', '<=', $request->start_time)
                              ->where('end_time', '>=', $request->end_time);
                       });
                });
            })->get();
        }
        
        // Format conflicts for response
        return $conflicts->map(function($conflict) {
            return [
                'employee' => $conflict->employee->name,
                'title' => $conflict->title,
                'duty_type' => $conflict->duty_type,
                'date' => $conflict->frequency == 'once' 
                    ? Carbon::parse($conflict->date)->format('d M Y')
                    : Carbon::parse($conflict->from_date)->format('d M Y') . ' - ' . Carbon::parse($conflict->to_date)->format('d M Y'),
                'start_time' => $conflict->start_time,
                'end_time' => $conflict->end_time,
                'status' => ucfirst(str_replace('_', ' ', $conflict->status)),
            ];
        });
    }
    
   

     /**
     * Check employee availability using the trait
     */
    public function checkAvailability(Request $request)
    {
         
        $request->validate([
            'employee_id' => 'required',
            'time_slot' => 'required|array',
            'context' => 'required|array',
        ]);
        
        // try {
            // Get context from request or use current user context
            $context = $request->context;
            
            // Use the trait to check availability
            $result = $this->checkEmployeeAvailability(
                $request->employee_id,
                $request->time_slot,
                $context
            );
            
            // Convert duty types to display names
            if (isset($result['conflicting_assignments'])) {
                foreach ($result['conflicting_assignments'] as &$conflict) {
                    if ($conflict['type'] === 'duty' && isset($conflict['duty_type'])) {
                        $conflict['duty_type_display'] = $this->getDutyTypeDisplay($conflict['duty_type']);
                    }
                }
            }
            
            return response()->json($result);
            
        // } catch (\Exception $e) {
        //     \Log::error('Availability check error: ' . $e->getMessage(), [
        //         'trace' => $e->getTraceAsString()
        //     ]);
            
        //     return response()->json([
        //         'available' => false,
        //         'message' => 'Error checking availability: ' . $e->getMessage(),
        //         'conflicting_assignments' => []
        //     ], 500);
        // }
    }

       /**
     * Get duty type display name
     */
    private function getDutyTypeDisplay($typeKey)
    {
        
         $instituteId = auth()->user()->institute_id;
        
        $dutyTypes = DutyType::where('institute_id', $instituteId)
            ->active()
            ->orderBy('duty_type')
            ->get();
        return $dutyTypes[$typeKey] ?? $typeKey;
    }
}