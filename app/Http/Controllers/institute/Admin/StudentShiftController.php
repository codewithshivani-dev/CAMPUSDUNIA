<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Traits\InstituteBranchAccess;
use App\Models\Shifts;
use App\Models\Departments;
use App\Models\StudentParentDetails;
use App\Models\DepartmentCategory;
use App\Models\DepartmentShift;
use App\Models\StudentShift;
use App\Models\StudentAcademicTransportDetails; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class StudentShiftController extends Controller
{
    use InstituteBranchAccess;

    public function assignForm()
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

        return view('instituteAdmin.Shifts.assigntostudents', compact(
            'shifts', 
            'departmentCategories'
        ));
    }
    public function assignShift(Request $request)
    {   
        // Validate single category, multiple shifts & departments
        $request->validate([
            // 'shift_ids' => 'required|array|min:1',
            // 'shift_ids.*' => 'exists:shifts,id',
            // 'department_category_id' => 'required|exists:department_categories,department_category_id',
            // 'department_ids' => 'required|array|min:1',
            // 'department_ids.*' => 'exists:departments,department_id',
            // 'assign_to_type' => 'required|in:whole_department,selected_students',
            // 'student_ids' => 'required_if:assign_to_type,selected_students|array',
        ]);      
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
                        $successCount = $this->assignToDepartmentStudents($shift, $request->department_ids, $instituteId, $branchId);
                        break;
                        
                    case 'selected_students':
                        if (empty($request->student_ids)) {
                            throw new \Exception('Please select students');
                        }
                        $successCount = $this->assignToSelectedStudents($shift, $request->student_ids, $instituteId, $branchId);
                        break;
                        
                    default:
                        $successCount = 0;
                        break;
                }
                
                $totalSuccessCount += $successCount;
            }         
           
            $message = $this->getSuccessMessage($totalSuccessCount, count($request->shift_ids), $request->assign_to_type);
            return redirect()->back()->with('success', $message);
    }
    private function assignToDepartmentStudents($shift, $departmentIds, $instituteId, $branchId)
    {
        $successCount = 0;

        foreach ($departmentIds as $departmentId) {

            // Check if already assigned
            $query = DepartmentShift::where('institute_id', $instituteId)
                ->where('department_id', $departmentId)
                ->where('shift_type', 'student')
                ->where('shift_id', $shift->id);

            if ($branchId !== null) {
                $query->where('branch_id', $branchId);
            } else {
                $query->whereNull('branch_id');
            }

            $existing = $query->first();

            if ($existing) {
                // Already exists → do nothing
                continue;
            }

            // Insert new record
            DepartmentShift::create([
                'institute_id' => $instituteId,
                'branch_id' => $branchId,
                'department_id' => $departmentId,
                'shift_id' => $shift->id,
                'shift_type' => 'student', 
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $successCount++;
        }

        return $successCount;
    }
    /**
     * Assign shift to selected students
     */
    private function assignToSelectedStudents($shift, $studentIds, $instituteId, $branchId)
    {
        $successCount = 0;
      
        foreach ($studentIds as $studentId) {
            try {
                $result = $this->assignShiftToStudent($shift, $studentId, $instituteId, $branchId, 'direct');
                if ($result === 'assigned') {
                    $successCount++;
                }
            } catch (\Exception $e) {
                \Log::error("Error assigning shift to student {$studentId}: " . $e->getMessage());
            }
        }
        
        return $successCount;
    }
    /**
     * Helper function to assign shift to individual student
     */
    private function assignShiftToStudent($shift, $studentId, $instituteId, $branchId, $assignmentType = 'direct')
    {
            $student = StudentParentDetails::where('student_hash_id', $studentId)
                ->where('institute_id', $instituteId)
                ->first();
             
            if (!$student) {
                \Log::warning('Student not found', ['student_hash_id' => $studentId]);
                return 'error';
            }
            
            // Prepare data for creation
            $data = [
                'institute_id' => $instituteId,
                'shift_id' => $shift->id,
                'student_hash_id' => $studentId,
                'status' => 'active',
                'assignment_type' => $assignmentType,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            // Add branch_id only if it's not null
            if ($branchId !== null) {
                $data['branch_id'] = $branchId;
            }
            
            // Check if this assignment already exists
            $query = StudentShift::where('institute_id', $instituteId)
                ->where('shift_id', $shift->id)
                ->where('student_hash_id', $studentId);
            

            // Condition for branch_id
            if ($branchId !== null) {
                $query->where('branch_id', $branchId);
            } else {
                $query->whereNull('branch_id');
            }
            
            $existing = $query->first();
        
            if ($existing) {
                if ($existing->status == 'inactive') {
                    $existing->update(['status' => 'active', 'updated_at' => now()]);
                    return 'assigned';
                }
                return 'already'; // Already active
            }
           
            // Create new assignment
            StudentShift::create($data);
            return 'assigned';
        
    } 
    public function getStudentsByDepartments(Request $request)
    {
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
            
            $instituteId = $this->getCurrentInstituteId();
            $branchId = $this->getCurrentBranchId();
            
            // Get student hash IDs from academic_transport_details
            $academicDetails = StudentAcademicTransportDetails::where('institute_id', $instituteId)
                ->whereIn('department_id', $departmentIds)
                ->when($branchId, function($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                }, function($query) {
                    return $query->whereNull('branch_id');
                })
                ->get(['student_hash_id', 'department_id']);
            
            if ($academicDetails->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'students' => [],
                    'count' => 0,
                    'message' => 'No students found in selected departments'
                ]);
            }
            
            // Group by department for easier reference
            $departmentStudents = [];
            foreach ($academicDetails as $detail) {
                $departmentStudents[$detail->student_hash_id] = $detail->department_id;
            }           
            // Get student details using hash IDs
            $studentHashIds = $academicDetails->pluck('student_hash_id')->unique();
            $students = StudentParentDetails::where('institute_id', $instituteId)
                ->whereIn('student_hash_id', $studentHashIds)
                ->orderBy('first_name')
                ->get(['id', 'student_hash_id', 'first_name', 'registration_number']);          
            $departmentStudents = [];
                foreach ($academicDetails as $detail) {
                    $departmentStudents[$detail->student_hash_id] = $detail->department_id;
                }

                $students = $students->map(function($student) use ($departmentStudents) {
                    $fullName = trim($student->first_name . ' ' . 
                                    ($student->middle_name ?? '') . ' ' . 
                                    ($student->last_name ?? ''));

                    return [
                        'id' => $student->id,
                        'student_hash_id' => $student->student_hash_id,
                        'full_name' => $fullName,
                        'registration_number' => $student->registration_number,
                        'department_id' => $departmentStudents[$student->student_hash_id] ?? null,
                    ];
                });
            return response()->json([
                'success' => true,
                'students' => $students,
                'count' => $students->count()
            ]);
    }

    public function getStudentsByDepartmentsWithDetails(Request $request)
    {
            $departmentIds = $request->input('department_ids', []);
            
            if (empty($departmentIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No departments selected'
                ]);
            }
            
            $instituteId = $this->getCurrentInstituteId();
            $branchId = $this->getCurrentBranchId();
            
            // Get students with department details in one query
            $students = StudentParentDetails::where('student_parent_details.institute_id', $instituteId)
                ->when($branchId, function($query) use ($branchId) {
                    return $query->where('student_parent_details.branch_id', $branchId);
                }, function($query) {
                    return $query->whereNull('student_parent_details.branch_id');
                })
                ->join('academic_transport_details', function($join) use ($instituteId, $branchId, $departmentIds) {
                    $join->on('student_parent_details.student_hash_id', '=', 'academic_transport_details.student_hash_id')
                         ->where('academic_transport_details.institute_id', $instituteId)
                         ->whereIn('academic_transport_details.department_id', $departmentIds);
                    
                    if ($branchId !== null) {
                        $join->where('academic_transport_details.branch_id', $branchId);
                    } else {
                        $join->whereNull('academic_transport_details.branch_id');
                    }
                })
                ->leftJoin('departments', 'academic_transport_details.department_id', '=', 'departments.department_id')
                ->select(
                    'student_parent_details.id',
                    'student_parent_details.student_hash_id',
                    'student_parent_details.first_name',
                    'student_parent_details.middle_name',
                    'student_parent_details.last_name',
                    'student_parent_details.registration_number',
                    'academic_transport_details.department_id',
                    'departments.department as department_name'
                )
                ->orderBy('student_parent_details.first_name')
                ->distinct()
                ->get();
            
            return response()->json([
                'success' => true,
                'students' => $students,
                'count' => $students->count()
            ]);
    }
    private function getSuccessMessage($totalCount, $shiftCount, $assignToType)
    {
        if ($assignToType === 'whole_department') {
            return "{$shiftCount} shift(s) assigned to all students in selected departments. Total: {$totalCount} assignment(s) created!";
        } else {
            return "{$shiftCount} shift(s) assigned to selected students. Total: {$totalCount} assignment(s) created!";
        }
    }


    //view students shifts *******************************************************************************

     public function getCurrentShift($studentHashId = null)
    {
        $instituteId = $this->getCurrentInstituteId();
        $branchId = $this->getCurrentBranchId();
        
        if (!$studentHashId) {     
            $user = Auth::user();
           $student = StudentParentDetails::where('user_id', $user->id)->firstOrFail();
           $studentHashId = $student->student_hash_id ?? null;
            if (!$studentHashId) {
                return view('instituteAdmin.Shifts.studentshift')->with([
                    'assignmentType' => 'holiday',
                    'message' => 'Student not found or not logged in'
                ]);
            }
        }
        
        $currentDate = now();
        
        // 1. First check for individual student shifts (highest priority)
        $individualShifts = StudentShift::where('student_hash_id', $studentHashId)
            ->where('institute_id', $instituteId)
            ->where('status', 'active')
            ->when($branchId, function($query) use ($branchId) {
                return $query->where('branch_id', $branchId);
            }, function($query) {
                return $query->whereNull('branch_id');
            })
            ->with(['shift' => function($query) {
                $query->orderBy('priority', 'asc'); 
            }])
            ->get()
            ->filter(function($studentShift) use ($currentDate) {
                $shift = $studentShift->shift;
                if (!$shift) return false;
                
                // Check if shift is active based on dates
                if ($shift->start_date && $currentDate->lt($shift->start_date)) {
                    return false;
                }
                if ($shift->end_date && $currentDate->gt($shift->end_date)) {
                    return false;
                }
                
                return true;
            })
            ->sortBy(function($studentShift) {
                return $studentShift->shift->priority ?? 999;
            });
          
        // Get highest priority individual shift
        $individualShift = $individualShifts->first();
        
        // 2. If no individual shift, check department shifts
        if (!$individualShift) {
            // Get student's department from academic details
            $academicDetails = StudentAcademicTransportDetails::where('student_hash_id', $studentHashId)
                ->where('institute_id', $instituteId)
                ->when($branchId, function($query) use ($branchId) {
                    return $query->where('branch_id', $branchId);
                }, function($query) {
                    return $query->whereNull('branch_id');
                })
                ->first();
            
            if ($academicDetails && $academicDetails->department_id) {
               $departmentShifts = DepartmentShift::where('department_id', $academicDetails->department_id)
                ->where('institute_id', $instituteId)
                ->where('shift_type', 'student')
                ->where('status', 'active')
                ->when($branchId, fn($q) => $q->where('branch_id', $branchId),
                                fn($q) => $q->whereNull('branch_id'))
                ->with('shift')
                ->whereHas('shift', function($q) use ($currentDate) {
                    $q->where(function($s) use ($currentDate) {
                        $s->whereNull('start_date')
                        ->orWhere('start_date', '<=', $currentDate);
                    })
                    ->where(function($s) use ($currentDate) {
                        $s->whereNull('end_date')
                        ->orWhere('end_date', '>=', $currentDate);
                    });
                })
                ->orderBy(Shifts::select('priority')->whereColumn('shifts.id', 'department_shifts.shift_id'))
                ->get()
                ->filter(function($departmentShift) use ($currentDate) {
                        $shift = $departmentShift->shift;
                        if (!$shift) return false;
                        
                        // Check if shift is active based on dates
                        if ($shift->start_date && $currentDate->lt($shift->start_date)) {
                            return false;
                        }
                        if ($shift->end_date && $currentDate->gt($shift->end_date)) {
                            return false;
                        }
                        
                        return true;
                    })
                    ->sortBy(function($departmentShift) {
                        return $departmentShift->shift->priority ?? 999;
                    });
                
                $departmentShift = $departmentShifts->first();
                
                if ($departmentShift) {
                    return $this->prepareShiftResponse($departmentShift->shift, 'department', true);
                }
            }
            
            // If no department shift found, it's a holiday
            return view('instituteAdmin.Shifts.studentshift')->with([
                'assignmentType' => 'holiday',
                'message' => 'No shift assigned'
            ]);
        }
        
        // 3. Return individual shift
        return $this->prepareShiftResponse($individualShift->shift, 'individual', true, $individualShift);
    }
    
    /**
     * Get shift history for student
     */
    public function getShiftHistory($studentHashId = null)
    {
        $instituteId = $this->getCurrentInstituteId();
        $branchId = $this->getCurrentBranchId();
        
        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->firstOrFail();
        $studentHashId = $student->student_hash_id;
    
        $history = [];
        $currentDate = now();

        /**
         * 1️⃣ STUDENT SHIFT HISTORY (student_shifts table)
         */
        $individualShifts = StudentShift::where('student_hash_id', $studentHashId)
            ->where('institute_id', $instituteId)
            ->where('status', 'active')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId),
                            fn($q) => $q->whereNull('branch_id'))
            
            ->with('shift')       
            ->whereHas('shift', function($q) use ($currentDate) {
                $q->where(function($s) use ($currentDate) {
                    $s->whereNull('start_date')
                    ->orWhere('start_date', '<=', $currentDate);
                })
                ->where(function($s) use ($currentDate) {
                    $s->whereNull('end_date')
                    ->orWhere('end_date', '>=', $currentDate);
                });
            })
            ->orderBy(
                Shifts::select('priority')->whereColumn('shifts.id', 'student_shifts.shift_id')
            )
            ->get();
        foreach ($individualShifts as $studentShift) {
            if ($studentShift->shift) {
                $history[] = [
                    'type' => 'individual',
                    'shift' => $studentShift->shift,
                    'status' => $studentShift->status,
                    'assigned_at' => $studentShift->created_at,
                    'ended_at' => $studentShift->deleted_at,
                    'assignment' => $studentShift,
                ];
            }
        }

        /**
         * 2️⃣ DEPARTMENT SHIFT HISTORY (department_shifts table)
         */
        $academicDetails = StudentAcademicTransportDetails::where('student_hash_id', $studentHashId)
            ->where('institute_id', $instituteId)
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId),
                            fn($q) => $q->whereNull('branch_id'))
            ->first();

        if ($academicDetails && $academicDetails->department_id) {

            $departmentShifts = DepartmentShift::where('department_id', $academicDetails->department_id)
                ->where('institute_id', $instituteId)
                ->where('shift_type', 'student')
                ->when($branchId, fn($q) => $q->where('branch_id', $branchId),
                                fn($q) => $q->whereNull('branch_id'))

                ->with('shift')   // join shift table
                ->whereHas('shift', function($q) use ($currentDate) {
                    $q->where(function($s) use ($currentDate) {
                        $s->whereNull('start_date')
                        ->orWhere('start_date', '<=', $currentDate);
                    })
                    ->where(function($s) use ($currentDate) {
                        $s->whereNull('end_date')
                        ->orWhere('end_date', '>=', $currentDate);
                    });
                })
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($departmentShifts as $deptShift) {
                if ($deptShift->shift) {
                    $history[] = [
                        'type' => 'department',
                        'shift' => $deptShift->shift,
                        'status' => $deptShift->status,
                        'assigned_at' => $deptShift->created_at,
                        'ended_at' => $deptShift->deleted_at,
                        'assignment' => $deptShift,
                    ];
                }
            }
        }

        /**
         * 3️⃣ SORT (student + department) shift history
         */
        usort($history, fn($a, $b) => $b['assigned_at'] <=> $a['assigned_at']);

        return response()->json([
            'success' => true,
            'history' => $history,
            'student' => [
                'name' => $student->first_name,
                'registration_number' => $student->registration_number,
                'department' => $academicDetails->department->department ?? 'N/A'
            ]
        ]);
    }
    
    /**
     * Prepare shift response data
     */
    private function prepareShiftResponse($shift, $assignmentType, $isActive, $assignment = null)
    {
        $weeklyOffDays = $shift->weekly_off_days;
        if (is_string($weeklyOffDays)) {
                $weeklyOffDays = json_decode($weeklyOffDays, true);
        }
        $shiftDetails = [
            'shift_id' => $shift->id,
            'shift_name' => $shift->shift_name,
            'start_time' => $shift->start_time,
            'end_time' => $shift->end_time,
            'formatted_start_time' => $shift->formatted_start_time,
            'formatted_end_time' => $shift->formatted_end_time,
            'duration' => $this->calculateDuration($shift->start_time, $shift->end_time),
            'priority' => $shift->priority,
            'break_minutes' => $shift->break_minutes ?? 0,
            'grace_minutes' => $shift->grace_minutes ?? 0,
            'weekly_off_days' => $weeklyOffDays ?: [],
            'start_date' => $shift->start_date,
            'end_date' => $shift->end_date,
            'assignment_type' => $assignmentType,
            'is_active' => $isActive,
            'assignment_date' => $assignment->created_at ?? now(),
        ];
        
        return view('instituteAdmin.Shifts.studentshift')->with([
            'assignmentType' => $assignmentType,
            'shiftDetails' => $shiftDetails
        ]);
    }
    
    /**
     * Calculate duration between times
     */
    private function calculateDuration($startTime, $endTime)
    {
        if (!$startTime || !$endTime) {
            return 'N/A';
        }
        
        try {
            $start = \Carbon\Carbon::parse($startTime);
            $end = \Carbon\Carbon::parse($endTime);
            
            $diff = $start->diff($end);
            
            if ($diff->h > 0 && $diff->i > 0) {
                return "{$diff->h}h {$diff->i}m";
            } elseif ($diff->h > 0) {
                return "{$diff->h} hours";
            } else {
                return "{$diff->i} minutes";
            }
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}