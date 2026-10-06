<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\HolidayEvent;
use App\Models\DepartmentCategory;
use App\Models\User;
use App\Models\EmployeeDetails;
use App\Notifications\EventCreatedNotification;
use App\Models\Departments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\InstituteBranchAccess;
class HolidayEventController extends Controller
{
    use InstituteBranchAccess; 
    // Display holiday events management page
    public function index()
    {
        $events = HolidayEvent::with(['departmentCategory', 'department'])->get();
        $categories = DepartmentCategory::all();
        
        return view('instituteAdmin.DashboardFiles.HolidaysEvents', compact('events', 'categories'));
    }

    // Get departments by category (for AJAX)
    public function getDepartmentsByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');
        
        if (!$categoryId) {
            return response()->json([
                'success' => false,
                'message' => 'Category ID is required'
            ]);
        }

        $departments = Departments::where('department_category_id', $categoryId)
            ->select('department_id', 'department')
            ->orderBy('department')
            ->get();

        return response()->json([
            'success' => true,
            'departments' => $departments
        ]);
    }

    // Store new holiday event
    public function store(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
        $institute_id = $context['institute_id'];
        $branch_id = $context['is_branch_admin'] ? $context['branch_id'] : null;
    
        $validated = $request->validate([
            'holiday_event_id' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'type' => 'nullable|in:holiday,event,meeting,other',
            'scope' => 'nullable|in:overall,department_wise',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'color' => 'nullable|string',
            'description' => 'nullable|string',
            'department_category_id' => 'nullable|required_if:scope,department_wise|exists:department_categories,department_category_id',
            'department_id' => 'nullable|required_if:scope,department_wise|exists:departments,department_id',
            'is_recurring' => 'boolean',
            'recurring_type' => 'required_if:is_recurring,true|in:none,yearly,monthly,weekly'
        ]);

        // For overall events, clear department fields
        if ($validated['scope'] === 'overall') {
            $validated['department_category_id'] = null;
            $validated['department_id'] = null;
        }

        // Ensure boolean fields are properly cast
        $validated['is_recurring'] = $request->boolean('is_recurring');
        $validated['holiday_event_id'] = 'HE' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        
        $EventData = array_merge(
            $this->createWithInstituteBranchContext([]), // This adds institute_id and branch_id
            $validated
        );
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            $event = HolidayEvent::create($EventData);
            
            // ✅ SEND NOTIFICATIONS BASED ON SCOPE
            $notificationCount = $this->sendEventNotifications($event);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Event created successfully. Notifications sent to ' . $notificationCount . ' users.',
                'event' => $event->load(['departmentCategory', 'department']),
                'notification_count' => $notificationCount
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create event: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notifications based on event scope
     */
    private function sendEventNotifications($event)
    {
        $notificationCount = 0;
        $context = $this->getInstituteBranchContext();
        
        // Determine recipients based on scope
        $users = collect();
        
        if ($event->scope === 'overall') {
            // Send to all users in the institute
            $users = User::whereHas('employeeDetails', function($query) use ($context, $event) {
                    $query->where('institute_id', $event->institute_id);
                    
                    if ($event->branch_id) {
                        $query->where('branch_id', $event->branch_id);
                    }
                })
                ->get();
                
        } elseif ($event->scope === 'department_wise' && $event->department_id) {
            // Send to employees in specific department
            $employeeIds = EmployeeDetails::where('department_id', $event->department_id)
                ->where('institute_id', $event->institute_id)
                ->when($event->branch_id, function($query) use ($event) {
                    return $query->where('branch_id', $event->branch_id);
                })
                ->pluck('user_id');
            
            $users = User::whereIn('id', $employeeIds)->get();
        }
        
        // Also send to admins (they should know about all events)
        $adminUsers = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['admin', 'superadmin', 'institute_admin']);
            })
            ->whereHas('employeeDetails', function($query) use ($context, $event) {
                $query->where('institute_id', $event->institute_id);
                if ($event->branch_id) {
                    $query->where('branch_id', $event->branch_id);
                }
            })
            ->get();
        
        // Merge admin users with regular users (avoid duplicates)
        $allRecipients = $users->merge($adminUsers)->unique('id');
        
        // Send notifications
        foreach ($allRecipients as $user) {
            $user->notify(new EventCreatedNotification($event));
            $notificationCount++;
        }
        
        return $notificationCount;
    }

    // Update holiday event
    public function update(Request $request, $id)
    {
        $event = HolidayEvent::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:holiday,event,meeting,other',
            'scope' => 'required|in:overall,department_wise',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'color' => 'nullable|string',
            'description' => 'nullable|string',
            'department_category_id' => 'nullable|required_if:scope,department_wise|exists:department_categories,department_category_id',
            'department_id' => 'nullable|required_if:scope,department_wise|exists:departments,department_id',
            'is_recurring' => 'boolean',
            'recurring_type' => 'required_if:is_recurring,true|in:none,yearly,monthly,weekly'
        ]);

        // For overall events, clear department fields
        if ($validated['scope'] === 'overall') {
            $validated['department_category_id'] = null;
            $validated['department_id'] = null;
        }

        $event->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully',
            'event' => $event->fresh(['departmentCategory', 'department'])
        ]);
    }

    // Delete holiday event
    public function destroy($id)
    {
        $event = HolidayEvent::findOrFail($id);
        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully'
        ]);
    }

    // Get events for calendar (with department filtering)
    public function getCalendarEvents(Request $request)
    {
        $query = HolidayEvent::query();
        
        // Filter by department if provided
        if ($request->has('department_id') && $request->department_id) {
            $departmentId = $request->department_id;
            $query->where(function($q) use ($departmentId) {
                $q->where('scope', 'overall')
                  ->orWhere(function($q2) use ($departmentId) {
                      $q2->where('scope', 'department_wise')
                         ->where('department_id', $departmentId);
                  });
            });
        }
        
        // Filter by type if provided
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        // Filter by date range if provided
        if ($request->has('start') && $request->has('end')) {
            $query->where(function($q) use ($request) {
                $q->whereBetween('start_date', [$request->start, $request->end])
                  ->orWhereBetween('end_date', [$request->start, $request->end])
                  ->orWhere(function($q2) use ($request) {
                      $q2->where('start_date', '<=', $request->start)
                         ->where('end_date', '>=', $request->end);
                  });
            });
        }

        $events = $query->get()->map(function($event) {
            $title = $event->title;
            
            // Add department info for department-wise events
            if ($event->scope === 'department_wise' && $event->department) {
                $title .= " - " . $event->department->department;
            }
            
            // Add event type indicator
            $title .= " [" . ucfirst($event->type) . "]";

            return [
                'id' => $event->holiday_event_id,
                'title' => $title,
                'start' => $event->start_date,
                'end' => $event->end_date,
                'color' => $event->color,
                'extendedProps' => [
                    'type' => $event->type,
                    'scope' => $event->scope,
                    'description' => $event->description,
                    'department_id' => $event->department_id,
                    'department_name' => $event->department ? $event->department->department : null,
                    'category_id' => $event->department_category_id,
                    'category_name' => $event->departmentCategory ? $event->departmentCategory->category_name : null,
                    'is_recurring' => $event->is_recurring,
                    'recurring_type' => $event->recurring_type
                ]
            ];
        });

        return response()->json($events);
    }

    // NEW METHOD: Get holiday events for Google Calendar
    public function getHolidaysForGoogleCalendar(Request $request)
    {
        $holidayEvents = HolidayEvent::query()
            ->when($request->has('employee_id'), function($query) use ($request) {
                // If employee has department, show overall events + their department events
                $employee = EmployeeDetails::find($request->employee_id);
                if ($employee && $employee->department_id) {
                    return $query->where('scope', 'overall')
                        ->orWhere(function($q) use ($employee) {
                            $q->where('scope', 'department_wise')
                              ->where('department_id', $employee->department_id);
                        });
                }
                return $query;
            })
            ->get()
            ->map(function($event) {
                return [
                    "title" => $event->title . " (" . ucfirst($event->type) . ")",
                    "start" => $event->start_date,
                    "end" => $event->end_date,
                    "color" => $event->color,
                    "extendedProps" => [
                        "category" => "holidays",
                        "type" => $event->type,
                        "scope" => $event->scope,
                        "description" => $event->description
                    ]
                ];
            })->toArray();

        return response()->json($holidayEvents);
    }
}