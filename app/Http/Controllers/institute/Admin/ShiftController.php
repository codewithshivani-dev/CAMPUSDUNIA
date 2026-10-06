<?php
// app/Http/Controllers/InstituteAdmin/ShiftController.php
namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use App\Models\Shifts;
use App\Models\Departments;
use App\Models\EmployeeDetails;
use App\Models\StudentParentDetails;
use App\Models\DepartmentCategory;
use App\Models\StudentShift;
use App\Models\DepartmentShift;
use App\Models\EmployeeShift;
use App\Models\StudentAcademicTransportDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ShiftScheduleExport;
use App\Exports\ShiftExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\InstituteNotificationSetting;

use Illuminate\Support\Facades\Validator;

class ShiftController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;
    
    public function index()
{
    $query = $this->getCommonQuery(Shifts::class);

    // Search filter
    if (request()->filled('search')) {
        $search = request('search');
        $query->where(function ($q) use ($search) {
            $q->where('shift_name', 'like', "%{$search}%")
                ->orWhere('start_time', 'like', "%{$search}%")
                ->orWhere('end_time', 'like', "%{$search}%");
        });
    }

    // Priority filter
    if (request()->filled('priority')) {
        $query->where('priority', request('priority'));
    }

    // Status filter
    if (request()->filled('status')) {
        $query->where('is_active', request('status') === 'active');
    }

    // Flexibility filter
    if (request()->filled('flexibility')) {
        if (request('flexibility') === 'flexible') {
            $query->where('flexible_working_hours', true);
        } elseif (request('flexibility') === 'fixed') {
            $query->where('flexible_working_hours', false);
        }
    }

    // Sorting
    $sortBy = request('sort_by', 'created_at');
    $sortOrder = request('sort_order', 'desc');
    
    if ($sortBy === 'employees_count') {
        // Sort by employee count using a subquery
        $query->withCount('employeeAssignments as employees_count')
            ->orderBy('employees_count', $sortOrder);
    } elseif ($sortBy === 'students_count') {
        // Sort by student count using a subquery
        $query->withCount('studentAssignments as students_count')
            ->orderBy('students_count', $sortOrder);
    } else {
        $query->orderBy($sortBy, $sortOrder);
    }

    // IMPORTANT: Add withCount for displaying counts
    $query->withCount([
        'employeeAssignments as employees_count' => function ($query) {
            $query->where('status', 'active');
        },
        'studentAssignments as students_count' => function ($query) {
            $query->where('status', 'active');
        }
    ]);

    // Pagination
    $shifts = $query->paginate(15)->withQueryString();

    $shiftNames = Shifts::select('shift_name')
        ->distinct()
        ->orderBy('shift_name')
        ->get();

    return view('instituteAdmin.Shifts.index', compact('shifts', 'shiftNames'));
    }

    /**
     * Show the form for creating a new shift (Create Page)
     */
    public function create()
    {
        return view('instituteAdmin.Shifts.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shift_name' => 'required|string|max:255|unique:shifts,shift_name,NULL,id,institute_id,' . $this->getCurrentInstituteId(),
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'working_hours' => 'required|numeric|min:0|max:24',
            'half_day_hours' => 'nullable|numeric|min:0|max:24',
            'short_leave_hours' => 'nullable|numeric|min:0|max:24',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'nullable|in:high,medium,low',
            'break_start_time' => 'nullable|date_format:H:i',
            'break_end_time' => 'nullable|date_format:H:i',
            'break_minutes' => 'nullable|numeric|min:0|max:180',
            'grace_minutes' => 'nullable|numeric|min:0|max:60',
            'weekly_off_days' => 'nullable|array',
            'weekly_off_days.*' => 'in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'flexible_working_hours' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $instituteId = $this->getCurrentInstituteId();
        $branchId = $this->getCurrentBranchId();

        // Prepare shift data
        $shiftData = [
            'institute_id' => $instituteId,
            'branch_id' => $branchId,
            'shift_name' => $request->shift_name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'working_hours' => $request->working_hours,
            'break_start_time' => $request->break_start_time,
            'half_day_hours' => $request->half_day_hours ?? 4.0,
            'short_leave_hours' => $request->short_leave_hours ?? 2.0,
            'break_end_time' => $request->break_end_time,
            'break_minutes' => $request->break_minutes ?? 0,
            'grace_minutes' => $request->grace_minutes ?? 15,
            'weekly_off_days' => $request->weekly_off_days,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'priority' => $request->priority ?? 'medium',
            'is_active' => true,
            'flexible_working_hours' => $request->boolean('flexible_working_hours', false),
        ];
        
        $shift = Shifts::create($shiftData);

        return response()->json([
            'success' => true, 
            'shift' => $shift,
            'message' => 'Shift created successfully!'
        ]);
    }

    /**
     * Display the specified shift
     */
    public function show($id)
    {
        $shift = $this->getCommonQuery(Shifts::class)
            ->with(['employees', 'students', 'departmentAssignments'])
            ->findOrFail($id);
        
        return view('instituteAdmin.Shifts.show', compact('shift'));
    }

    public function update(Request $request, $id)
{
    $shift = $this->getCommonQuery(Shifts::class)->findOrFail($id);

    $validator = Validator::make($request->all(), [
        'shift_name' => 'required|string|max:255|unique:shifts,shift_name,' . $id . ',id,institute_id,' . $this->getCurrentInstituteId(),
        'start_time' => 'required|date_format:H:i',
        'end_time' => 'required|date_format:H:i',
        'working_hours' => 'required|numeric|min:0|max:24',
        'half_day_hours' => 'nullable|numeric|min:0|max:24',
        'short_leave_hours' => 'nullable|numeric|min:0|max:24',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'priority' => 'nullable|in:high,medium,low',
        'is_active' => 'boolean',
        'flexible_working_hours' => 'boolean',
        'weekly_off_days' => 'nullable|array',
        'weekly_off_days.*' => 'in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }

    $updateData = $request->only([
        'shift_name', 'start_time', 'end_time', 'working_hours',
        'half_day_hours', 'short_leave_hours', 'break_start_time',
        'break_end_time', 'break_minutes', 'grace_minutes',
        'start_date', 'end_date', 'priority', 'is_active',
        'flexible_working_hours'
    ]);

    // FIX: Store weekly_off_days as proper JSON array
    if ($request->has('weekly_off_days')) {
        $updateData['weekly_off_days'] = is_array($request->weekly_off_days) 
            ? json_encode(array_values($request->weekly_off_days)) 
            : null;
    }

    // Boolean conversion
    $booleanFields = ['is_active', 'flexible_working_hours'];
    foreach ($booleanFields as $field) {
        if (isset($updateData[$field])) {
            $updateData[$field] = $request->boolean($field);
        }
    }

    $shift->update($updateData);

    return response()->json([
        'success' => true,
        'shift' => $shift,
        'message' => 'Shift updated successfully!'
    ]);
    }

    public function destroy($id)
    {
        $shift = $this->getCommonQuery(Shifts::class)
            ->where('id', $id)
            ->firstOrFail();
        
        // Check if shift is assigned to any employee or student
        if ($shift->employees()->exists() || $shift->students()->exists()) {
            return response()->json([
                'success' => false, 
                'message' => 'Cannot delete shift assigned to employees or students.'
            ], 422);
        }

        $shift->delete();
        return response()->json(['success' => true, 'message' => 'Shift deleted successfully']);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'shift_ids' => 'required|array',
            'shift_ids.*' => 'exists:shifts,id'
        ]);

        $shiftIds = $request->shift_ids;
        $deletedCount = 0;
        $failedIds = [];

        foreach ($shiftIds as $id) {
            $shift = $this->getCommonQuery(Shifts::class)->find($id);
            if ($shift && !$shift->employees()->exists() && !$shift->students()->exists()) {
                $shift->delete();
                $deletedCount++;
            } else {
                $failedIds[] = $id;
            }
        }

        $message = "{$deletedCount} shift(s) deleted successfully.";
        if (!empty($failedIds)) {
            $message .= " Failed to delete shifts with IDs: " . implode(', ', $failedIds) . " (assigned to employees/students).";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'deleted_count' => $deletedCount,
            'failed_ids' => $failedIds
        ]);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'shift_ids' => 'required|array',
            'shift_ids.*' => 'exists:shifts,id',
            'is_active' => 'required|boolean'
        ]);

        $updatedCount = Shifts::whereIn('id', $request->shift_ids)
            ->update(['is_active' => $request->is_active]);

        return response()->json([
            'success' => true,
            'message' => "{$updatedCount} shift(s) updated successfully.",
            'updated_count' => $updatedCount
        ]);
    }

    public function assignShiftForm()
    {
        // Get shifts
        $shifts = $this->getCommonQuery(Shifts::class)
            ->active()
            ->orderBy('priority')
            ->get();
        
        // Get department categories
        $departmentCategories = $this->getCommonQuery(DepartmentCategory::class)
            ->orderBy('category_name')
            ->get();

        return view('instituteAdmin.Shifts.assign', compact(
            'shifts', 
            'departmentCategories'
        ));
    }
    // Show assign shift form
    public function assignShift(Request $request)
    {
        
        // Validate single category, multiple shifts & departments
        $request->validate([
            'shift_ids' => 'required|array|min:1',
            'shift_ids.*' => 'exists:shifts,id',
            'department_category_id' => 'required|exists:department_categories,department_category_id', // Single category
            'department_ids' => 'required|array|min:1',
            'department_ids.*' => 'exists:departments,department_id',
            'assign_to_type' => 'required|in:whole_department,selected_employees',
            'employee_ids' => 'required_if:assign_to_type,selected_employees|array',
        ]);
       
        // try {
            DB::beginTransaction();
            
            $instituteId = $this->getCurrentInstituteId();
            $branchId = $this->getCurrentBranchId();
            
            $totalSuccessCount = 0;
            
            // Verify departments belong to selected category
            foreach ($request->department_ids as $departmentId) {
                $department = Departments::where('department_id', $departmentId)
                    ->where('department_category_id', $request->department_category_id)
                    ->first();
                
                if (!$department) {
                    throw new \Exception("Department ID {$departmentId} does not belong to selected category");
                }
            }
            
            // Process each selected shift
            foreach ($request->shift_ids as $shiftId) {
                $shift = $this->getCommonQuery(Shifts::class)->find($shiftId);
                
                if (!$shift) {
                    continue;
                }
                
                // Process based on assignment type
                switch ($request->assign_to_type) {
                    case 'whole_department':
                        $successCount = $this->assignToDepartments($shift, $request->department_ids, $instituteId, $branchId);
                        break;
                        
                    case 'selected_employees':
                        if (empty($request->employee_ids)) {
                            throw new \Exception('Please select employees');
                        }
                        $successCount = $this->assignToEmployees($shift, $request->employee_ids, $instituteId, $branchId);
                        break;
                        
                    default:
                        $successCount = 0;
                        break;
                }
                
                $totalSuccessCount += $successCount;
            }
            
            DB::commit();
            
            $message = $this->getSuccessMessage($totalSuccessCount, count($request->shift_ids), $request->assign_to_type);
            return redirect()->back()->with('success', $message);
            
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        // }
    }
   

    private function assignToDepartments($shift, $departmentIds, $instituteId, $branchId)
    {
        $successCount = 0;
        $assignedDepartments = []; // Track departments for notifications
        
        foreach ($departmentIds as $departmentId) {
            // try {
                // Prepare data for creation
                $data = [
                    'institute_id' => $instituteId,
                    'shift_id' => $shift->id,
                    'department_id' => $departmentId,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                // Add branch_id only if it's not null
                if ($branchId !== null) {
                    $data['branch_id'] = $branchId;
                }
                
                // Build query to check if assignment already exists
                $query = DepartmentShift::where('institute_id', $instituteId)
                    ->where('shift_id', $shift->id)
                    ->where('department_id', $departmentId);
                
                // Handle branch_id condition
                if ($branchId !== null) {
                    $query->where('branch_id', $branchId);
                } else {
                    $query->whereNull('branch_id');
                }
                
                $existing = $query->first();
                
                if ($existing) {
                    if ($existing->status == 'inactive') {
                        $existing->update(['status' => 'active', 'updated_at' => now()]);
                        $successCount++;
                        $assignedDepartments[] = $departmentId;
                    } else {
                        \Log::info('Assignment already active, skipping');
                    }
                    continue;
                }               
                $departmentShift = DepartmentShift::create($data);
                
                if ($departmentShift) {
                    $successCount++;
                    $assignedDepartments[] = $departmentId;
                }
                
            // } catch (\Exception $e) {
            //     \Log::error("Error assigning shift to department {$departmentId}: " . $e->getMessage());
            // }
        }
        
        // Send notifications to employees in assigned departments
        foreach ($assignedDepartments as $departmentId) {
            $this->notifyDepartmentEmployees($shift, $departmentId, $instituteId, $branchId);
        }
        
        return $successCount;
    }

    private function assignToEmployees($shift, $employeeIds, $instituteId, $branchId)
    {
        $successCount = 0;
        $assignedEmployees = []; // Track successfully assigned employees for notifications
        
        foreach ($employeeIds as $employeeId) {
            // try {
                // Verify employee exists
                $employee = EmployeeDetails::where('employee_id', $employeeId)
                    ->where('institute_id', $instituteId)
                    ->first();
                
                if (!$employee) {
                    \Log::warning('Employee not found', ['employee_id' => $employeeId]);
                    continue;
                }
                
                // Build data for creation
                $data = [
                    'institute_id' => $instituteId,
                    'shift_id' => $shift->id,
                    'employee_id' => $employeeId,
                    'status' => 'active',
                    'assignment_type' => 'direct',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                // Add branch_id only if it's not null
                if ($branchId !== null) {
                    $data['branch_id'] = $branchId;
                }
                
                // Check if this assignment already exists
                $query = EmployeeShift::where('institute_id', $instituteId)
                    ->where('shift_id', $shift->id)
                    ->where('employee_id', $employeeId);
                
                // Condition for branch_id - handle null properly
                if ($branchId !== null) {
                    $query->where('branch_id', $branchId);
                } else {
                    $query->whereNull('branch_id');
                }
                
                $existing = $query->first();
                
                if ($existing) {
                    if ($existing->status == 'inactive') {
                        $existing->update(['status' => 'active']);
                        $successCount++;
                        $assignedEmployees[] = $employee;
                        \Log::info('Reactivated employee assignment');
                    }
                    continue;
                }
                
                // Create new assignment
                EmployeeShift::create($data);
                $successCount++;
                $assignedEmployees[] = $employee;
                \Log::info('Created new employee assignment');
                
            // } catch (\Exception $e) {
            //     \Log::error("Error assigning shift to employee {$employeeId}: " . $e->getMessage());
            // }
        }
        
        // Send notifications to successfully assigned employees
        if (!empty($assignedEmployees)) {
            $this->sendBulkShiftNotifications($assignedEmployees, $shift, 'individual');
        }
     
        return $successCount;
    }

    private function getSuccessMessage($totalCount, $shiftCount, $assignToType)
    {
        if ($assignToType === 'whole_department') {
            return "{$shiftCount} shift(s) assigned to departments. Total: {$totalCount} assignment(s) created!";
        } else {
            return "{$shiftCount} shift(s) assigned to employees. Total: {$totalCount} assignment(s) created!";
        }
    }

    public function getmultiDepartmentsByCategories(Request $request)
    {
        try {
            $categoryIds = $request->input('category_ids', []);
            
            if (empty($categoryIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No categories selected'
                ]);
            }
            
            // Convert string to array if needed
            if (is_string($categoryIds)) {
                $categoryIds = explode(',', $categoryIds);
            }
            
            // Get departments using your existing query builder method
            $departments = $this->getCommonQuery(Departments::class)
                ->whereIn('department_category_id', $categoryIds)
                ->orderBy('department')
                ->get(['department_id', 'department', 'department_category_id']);
            
            return response()->json([
                'success' => true,
                'departments' => $departments
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getDepartmentsByCategories: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading departments'
            ]);
        }
    }

     public function getmultiEmployeesByDepartments(Request $request)
    {
        try {
            $departmentIds = $request->input('department_ids', []);
            
            if (empty($departmentIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No departments selected'
                ]);
            }
            
            // Convert string to array if needed
            if (is_string($departmentIds)) {
                $departmentIds = explode(',', $departmentIds);
            }
            
            // Get employees using your existing query builder method
            $employees = $this->getCommonQuery(EmployeeDetails::class)
                ->whereIn('department_id', $departmentIds)
                ->orderBy('name')
                ->get([
                    'id',
                    'employee_id',
                    'name',
                    'designation',
                    'employee_code',
                    'department_id',
                    'shift_id' // Keep this for reference
                ]);
            
            return response()->json([
                'success' => true,
                'employees' => $employees,
                'count' => $employees->count()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getEmployeesByDepartments: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading employees'
            ]);
        }
    }   

 
    public function shiftSchedule(Request $request)
    {
        $instituteId = $this->getCurrentInstituteId();
        $branchId = $this->getCurrentBranchId();

        $employeeId = $request->employee_id;
        $departmentId = $request->department_id;
        $shiftId = $request->shift_id;
        $startTime = $request->start_time;
        $endTime = $request->end_time;

        // :one: Base query
        $employeesQuery = EmployeeDetails::query()
            ->leftJoin('departments', 'employee_details.department_id', '=', 'departments.department_id')
            ->leftJoin('department_categories', 'departments.department_category_id', '=', 'department_categories.department_category_id')
            ->where('employee_details.institute_id', $instituteId)
            ->when($branchId !== null, function ($q) use ($branchId) {
                $q->where(function ($qq) use ($branchId) {
                    $qq->where('employee_details.branch_id', $branchId)
                        ->orWhereNull('employee_details.branch_id');
                });
            })

            ->when(
                $employeeId,
                fn($q) =>
                $q->where('employee_details.employee_id', $employeeId)
            )

            ->when(
                $departmentId,
                fn($q) =>
                $q->where('employee_details.department_id', $departmentId)
            )

            ->select(
                'employee_details.*',
                'departments.department as department_name',
                'department_categories.category_name as department_category_name'
            )
            ->orderBy('employee_details.name');

        // :two: PAGINATE FIRST
        $employees = $employeesQuery
            ->paginate(15)
            ->withQueryString();

        // :three: TRANSFORM PAGINATED COLLECTION (IMPORTANT)
        $employees->getCollection()->transform(function ($employee) use ($instituteId, $branchId, $shiftId, $startTime, $endTime) {

            $employeeShift = EmployeeShift::where('institute_id', $instituteId)
                ->where('employee_id', $employee->employee_id)
                ->where('status', 'active')
                ->when($branchId !== null, function ($q) use ($branchId) {
                    $q->where(function ($qq) use ($branchId) {
                        $qq->where('branch_id', $branchId)
                            ->orWhereNull('branch_id');
                    });
                })
                ->with('shift')
                ->first();

            $departmentShift = null;

            if ($employee->department_id) {
                $departmentShift = DepartmentShift::where('institute_id', $instituteId)
                    ->where('department_id', $employee->department_id)
                    ->where('status', 'active')
                    ->when(
                        $branchId,
                        fn($q) => $q->where('branch_id', $branchId),
                        fn($q) => $q->whereNull('branch_id')
                    )
                    ->with('shift')
                    ->first();
            }

            $shift = $employeeShift?->shift ?? $departmentShift?->shift ?? null;

            // :fire: SHIFT FILTERING
            if ($shift) {
                if ($shiftId && $shift->id != $shiftId)
                    return null;
                if ($startTime && $shift->start_time != $startTime)
                    return null;
                if ($endTime && $shift->end_time != $endTime)
                    return null;
            } elseif ($shiftId || $startTime || $endTime) {
                return null;
            }

            if ($employeeShift && $employeeShift->shift) {
                $employee->employee_shift = $employeeShift->shift;
                $employee->shift_source = 'individual';
            } elseif ($departmentShift && $departmentShift->shift) {
                $employee->department_shift = $departmentShift->shift;
                $employee->shift_source = 'department';
            }

            return $employee;
        });

        // :four: Remove NULL rows AFTER transform
        $employees->setCollection(
            $employees->getCollection()->filter()->values()
        );

        // :five: Datalists
        $allEmployees = EmployeeDetails::where('institute_id', $instituteId)->get();
        $departments = Departments::where('institute_id', $instituteId)->get();
        $shifts = Shifts::where('institute_id', $instituteId)->get();

        return view('instituteAdmin.Shifts.schedule', [
            'employees' => $employees,
            'allEmployees' => $allEmployees,
            'departments' => $departments,
            'shifts' => $shifts,

            // Persist filters
            'employeeId' => $employeeId,
            'departmentId' => $departmentId,
            'shiftId' => $shiftId,
            'startTime' => $startTime,
            'endTime' => $endTime,
        ]);
    }

    // Get shift statistics
    public function getShiftStatistics()
    {
        $shifts = $this->getCommonQuery(Shifts::class)
            ->withCount(['employees', 'students'])
            ->get();

        $stats = [
            'total_shifts' => $shifts->count(),
            'active_shifts' => $shifts->where('status', 'active')->count(),
            'total_assigned_employees' => $shifts->sum('employees_count'),
            'total_assigned_students' => $shifts->sum('students_count'),
            'shifts_by_priority' => $shifts->groupBy('priority')->map->count(),
        ];

        return response()->json($stats);
    }
    
    public function downloadShifts(Request $request)
    {
        $request->validate([
            'type' => 'required|in:excel,csv,pdf',
            'ids' => 'nullable|string',
            'select_all' => 'nullable|boolean',
        ]);

        $query = $this->getCommonQuery(Shifts::class)
            ->with(['employees', 'students']);

        /*
        |--------------------------------------
        | Apply Same Filters as Index
        |--------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('shift_name', 'like', "%{$search}%")
                    ->orWhere('start_time', 'like', "%{$search}%")
                    ->orWhere('end_time', 'like', "%{$search}%");
            });
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        /*
        |--------------------------------------
        | Select All OR Selected IDs
        |--------------------------------------
        */

        if ($request->select_all == 1) {

            // no extra condition, filters already applied

        } elseif ($request->filled('ids')) {

            $ids = array_filter(explode(',', $request->ids));
            $query->whereIn('id', $ids);

        } else {
            return response()->json([
                'status' => false,
                'message' => 'No records selected for export'
            ], 400);
        }

        $shifts = $query->get()->map(function ($shift) {
            $shift->employees_count = $shift->employees->count();
            $shift->students_count = $shift->students->count();
            return $shift;
        });

        if ($shifts->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No records found'
            ], 404);
        }

        $fileName = 'shifts_' . now()->format('Y_m_d_His');

        switch ($request->type) {

            case 'excel':
                return Excel::download(
                    new ShiftExport($shifts),
                    $fileName . '.xlsx'
                );

            case 'csv':
                return Excel::download(
                    new ShiftExport($shifts),
                    $fileName . '.csv'
                );

            case 'pdf':
                $pdf = Pdf::loadView('pdf.shift_export', compact('shifts'));
                return $pdf->download($fileName . '.pdf');

            default:
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid export type'
                ], 400);
        }
    }

    public function downloadShiftSchedule(Request $request)
    {
        $request->validate([
            'type' => 'required|in:excel,csv,pdf'
        ]);

        $instituteId = $this->getCurrentInstituteId();
        $branchId = $this->getCurrentBranchId();

        /*
        |--------------------------------------------------------------------------
        | BASE QUERY (same as shiftSchedule but NO filters + NO pagination)
        |--------------------------------------------------------------------------
        */
        $employees = EmployeeDetails::query()
            ->leftJoin('departments', 'employee_details.department_id', '=', 'departments.department_id')
            ->leftJoin('department_categories', 'departments.department_category_id', '=', 'department_categories.department_category_id')
            ->where('employee_details.institute_id', $instituteId)

            // ✅ SAFE branch handling
            ->when($branchId !== null, function ($q) use ($branchId) {
                $q->where('employee_details.branch_id', $branchId);
            })

            ->select(
                'employee_details.*',
                'departments.department as department_name',
                'department_categories.category_name as department_category_name'
            )
            ->orderBy('employee_details.name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | APPLY SHIFT RESOLUTION (same logic as view, no filters)
        |--------------------------------------------------------------------------
        */
        $employees = $employees->map(function ($employee) use ($instituteId, $branchId) {

            $employeeShift = EmployeeShift::where('institute_id', $instituteId)
                ->where('employee_id', $employee->employee_id)
                ->where('status', 'active')
                ->when(
                    $branchId !== null,
                    fn($q) => $q->where('branch_id', $branchId)
                )
                ->with('shift')
                ->first();

            $departmentShift = null;

            if ($employee->department_id) {
                $departmentShift = DepartmentShift::where('institute_id', $instituteId)
                    ->where('department_id', $employee->department_id)
                    ->where('status', 'active')
                    ->when(
                        $branchId !== null,
                        fn($q) => $q->where('branch_id', $branchId)
                    )
                    ->with('shift')
                    ->first();
            }

            $shift = $employeeShift?->shift ?? $departmentShift?->shift ?? null;

            $employee->shift_name = $shift->name ?? 'No Shift';
            $employee->start_time = $shift->start_time ?? '-';
            $employee->end_time = $shift->end_time ?? '-';

            if ($employeeShift && $employeeShift->shift) {
                $employee->shift_source = 'Individual';
            } elseif ($departmentShift && $departmentShift->shift) {
                $employee->shift_source = 'Department';
            } else {
                $employee->shift_source = 'None';
            }

            return $employee;
        });

        if ($employees->isEmpty()) {
            return response()->json(['message' => 'No data found'], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | EXPORT
        |--------------------------------------------------------------------------
        */
        switch ($request->type) {

            case 'excel':
                return Excel::download(
                    new ShiftScheduleExport($employees),
                    'shift_schedule.xlsx'
                );

            case 'csv':
                return Excel::download(
                    new ShiftScheduleExport($employees),
                    'shift_schedule.csv'
                );

            case 'pdf':
                $pdf = Pdf::loadView('pdf.shift_schedule_export', [
                    'employees' => $employees
                ]);
                return $pdf->download('shift_schedule.pdf');
        }
    }

    /**
     * Check if a specific notification channel is enabled for a module
     */
    private function isNotificationEnabled($instituteId, $moduleName, $channel)
    {
        try {
            $setting = InstituteNotificationSetting::where('institute_id', $instituteId)
                ->where('module_name', $moduleName)
                ->first();
            
            if (!$setting) {
                // If no setting found, use default (email enabled by default)
                return $channel === 'email';
            }
            
            switch ($channel) {
                case 'email':
                    return $setting->email_enabled;
                case 'whatsapp':
                    return $setting->whatsapp_enabled;
                case 'sms':
                    return $setting->sms_enabled;
                default:
                    return false;
            }
        } catch (\Exception $e) {
            \Log::error('Error checking notification status: ' . $e->getMessage());
            return $channel === 'email';
        }
    }

    /**
     * Send shift assignment notification to employee
     */
    private function sendShiftAssignmentNotification($employee, $shift, $assignmentType = 'individual', $context = [])
    {
        try {
            if (!$employee || empty($employee->email)) {
                return;
            }
            
            $instituteId = $context['institute_id'] ?? $this->getCurrentInstituteId();
            
            // Determine which module to check based on assignment type
            $moduleName = ($assignmentType === 'individual') ? 'shift_assignment' : 'department_shift_assignment';
            
            // Check if email notification is enabled
            $emailEnabled = $this->isNotificationEnabled($instituteId, $moduleName, 'email');
            
            if (!$emailEnabled) {
                return;
            }
            
            // Prepare weekly off days
            $weeklyOffDays = [];
            if ($shift->weekly_off_days) {
                if (is_string($shift->weekly_off_days)) {
                    $weeklyOffDays = json_decode($shift->weekly_off_days, true) ?: [];
                } elseif (is_array($shift->weekly_off_days)) {
                    $weeklyOffDays = $shift->weekly_off_days;
                }
            }
            
            // Send email notification
            Mail::send('emails.shift-assigned', [
                'employeeName' => $employee->name,
                'shiftName' => $shift->shift_name,
                'startTime' => date('h:i A', strtotime($shift->start_time)),
                'endTime' => date('h:i A', strtotime($shift->end_time)),
                'workingHours' => $shift->working_hours,
                'halfDayHours' => $shift->half_day_hours,
                'shortLeaveHours' => $shift->short_leave_hours,
                'breakStartTime' => $shift->break_start_time ? date('h:i A', strtotime($shift->break_start_time)) : null,
                'breakEndTime' => $shift->break_end_time ? date('h:i A', strtotime($shift->break_end_time)) : null,
                'breakMinutes' => $shift->break_minutes ?? 0,
                'graceMinutes' => $shift->grace_minutes ?? 15,
                'weeklyOffDays' => $weeklyOffDays,
                'startDate' => date('d-m-Y', strtotime($shift->start_date)),
                'endDate' => $shift->end_date ? date('d-m-Y', strtotime($shift->end_date)) : null,
                'assignmentType' => $assignmentType,
            ], function ($message) use ($employee, $shift) {
                $message->to($employee->email)
                        ->subject('Shift Assignment: ' . $shift->shift_name);
            });
            
         
            
        } catch (\Exception $e) {
            \Log::error('Failed to send shift assignment email: ' . $e->getMessage(), [
                'employee_id' => $employee->employee_id ?? null,
                'shift_id' => $shift->id ?? null
            ]);
        }
    }

    /**
     * Send bulk shift assignment notifications to multiple employees
     */
    private function sendBulkShiftNotifications($employees, $shift, $assignmentType = 'individual')
    {
        $sentCount = 0;
        $failedCount = 0;
        
        foreach ($employees as $employee) {
            try {
                $this->sendShiftAssignmentNotification($employee, $shift, $assignmentType);
                $sentCount++;
            } catch (\Exception $e) {
                $failedCount++;
                
            }
        }
        
        return ['sent' => $sentCount, 'failed' => $failedCount];
    }

    /**
     * Get all employees in a department and send shift notifications
     */
    private function notifyDepartmentEmployees($shift, $departmentId, $instituteId, $branchId)
    {
        try {
            // Get all employees in this department
            $employees = EmployeeDetails::where('department_id', $departmentId)
                ->where('institute_id', $instituteId)
                ->when($branchId !== null, function($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                })
                ->get();
            
            if ($employees->isEmpty()) {
                return ['sent' => 0, 'failed' => 0];
            }
            
            // Send bulk notifications
            return $this->sendBulkShiftNotifications($employees, $shift, 'department');
            
        } catch (\Exception $e) {
         
            return ['sent' => 0, 'failed' => 0];
        }
    }

}