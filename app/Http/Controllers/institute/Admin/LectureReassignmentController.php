<?php

namespace App\Http\Controllers\Institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDetails;
use App\Models\Departments;
use App\Models\EmployeeSubjectLectures;
use App\Models\AssignSubjectsToEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Traits\EmployeeAvailability;
use App\Traits\InstituteBranchAccess;
use App\Traits\SectionNameHelper;
use App\Notifications\LectureReassigned;
use Illuminate\Support\Facades\Schema;
use App\Mail\LectureReassignedToNewEmployee;
use App\Mail\LectureReassignedFromOriginalEmployee;
use Illuminate\Support\Facades\Mail;
use App\Traits\SendsInstituteNotifications;

class LectureReassignmentController extends Controller
{
    use EmployeeAvailability, InstituteBranchAccess, SectionNameHelper,SendsInstituteNotifications;

    /**
     * Display the lecture reassignment page
     */
    public function index()
    {
        $instituteId = auth()->user()->institute_id;
        
        // Get departments for filter
        $departments = Departments::where('institute_id', $instituteId)
            // ->when(auth()->user()->is_branch_admin && auth()->user()->branch_id, function($query) {
            //     $query->where('branch_id', auth()->user()->branch_id);
            // }, function($query) {
            //     $query->whereNull('branch_id');
            // })
            ->orderBy('department')
            ->get();

        return view('instituteAdmin.LectureReassignment.index', compact('departments'));
    }

    /**
     * Get employees by department with attendance status
     */
    public function getEmployeesByDepartment(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,department_id',
            'date' => 'required|date'
        ]);

        $instituteId = auth()->user()->institute_id;
        $date = $request->date;
        $context = $this->getInstituteBranchContext();

        // Get employees in the selected department
        $employees = EmployeeDetails::where('institute_id', $instituteId)
            ->where('department_id', $request->department_id)
            ->where('status', 'active')
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->orderBy('name')
            ->get();

        // Get attendance and leave status for each employee
        $employeesWithStatus = $employees->map(function($employee) use ($date, $instituteId) {
            // Check attendance for the selected date
            $attendance = DB::table('employee_attendances')
                ->where('employee_id', $employee->employee_id)
                ->where('date', $date)
                ->where('institute_id', $instituteId)
                ->first();

            // Check if employee is on leave
            $leave = DB::table('employee_leaves')
                ->where('employee_id', $employee->employee_id)
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->where('final_status', 'Approved')
                ->where('institute_id', $instituteId)
                ->first();

            // Determine status
            if ($leave) {
                $status = 'on_leave';
                $status_label = 'On Leave';
                $status_color = 'warning';
            } elseif ($attendance && $attendance->status === 'Present') {
                $status = 'present';
                $status_label = 'Present';
                $status_color = 'success';
            } else {
                $status = 'absent';
                $status_label = 'Absent';
                $status_color = 'danger';
            }

            return [
                'employee_id' => $employee->employee_id,
                'name' => $employee->name,
                'employee_code' => $employee->employee_code ?? $employee->employee_id,
                'department_id' => $employee->department_id,
                'status' => $status,
                'status_label' => $status_label,
                'status_color' => $status_color,
                'attendance_data' => $attendance,
                'leave_data' => $leave
            ];
        });

        return response()->json([
            'success' => true,
            'employees' => $employeesWithStatus
        ]);
    }

    /**
     * Get employee schedule with lectures
     */
    public function getEmployeeSchedule(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'date' => 'required|date'
        ]);

        $instituteId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();
        $selectedDate = Carbon::parse($request->date);
        
        // Check if columns exist
        $hasIsReassigned = Schema::hasColumn('employee_subject_lectures', 'is_reassigned');
        $hasIsCancelled = Schema::hasColumn('employee_subject_lectures', 'is_cancelled');
        
        // Build the query
        $query = DB::table('employee_subject_lectures as esl')
            ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->leftJoin('subjects_coursewise as s', 'ase.subject_id', '=', 's.subject_id')
            ->leftJoin('sub_subjects as ss', function($join) {
                $join->on('ase.sub_subject_id', '=', 'ss.sub_subject_id')
                    ->on('ase.subject_id', '=', 'ss.subject_id');
            })
            ->leftJoin('departments as d', 'ase.department_id', '=', 'd.department_id')
            ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
            ->select(
                'esl.*',
                'ase.emp_assign_subject_id',
                'ase.subject_display_name',
                'ase.subject_type',
                'ase.section_id',
                'ase.course_detail_id',
                'ase.semester_id',
                'ase.department_id',
                'ase.branch_id',
                's.subject_name as main_subject_name',
                'ss.sub_subject_name',
                'd.department as department_name',
                'pd.course_type',
                'pd.sub_type as branch_name'
            )
            ->where('ase.employee_id', $request->employee_id)
            ->where('ase.institute_id', $instituteId)
            ->where('ase.status', 'Active')
            ->where('esl.status', 'active');
        
        // Only exclude cancelled lectures if column exists
        if ($hasIsCancelled) {
            $query->where('esl.is_cancelled', false);
        }
        
        // Add branch condition
        $query->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            return $query->where('ase.branch_id', $context['branch_id']);
        });
        
        $lectures = $query->get();
        // dd($lectures);
        
        // Filter lectures that occur on the selected date
        $filteredLectures = $lectures->filter(function($lecture) use ($selectedDate) {
            return $this->doesLectureOccurOnDate($lecture, $selectedDate);
        });
        
        // Process lectures with section names
        $processedLectures = $filteredLectures->map(function($lecture) use ($selectedDate, $request, $instituteId, $context) {
            $startTime = Carbon::parse($lecture->start_time);
            $endTime = Carbon::parse($lecture->end_time);

            // Get section name using helper trait
            $sectionName = $this->getSectionDisplayName(
                $lecture->section_id,
                $lecture->course_detail_id,
                $instituteId,
                $lecture->branch_id ?? $context['branch_id'] ?? null
            );
            
            // Check if reassigned fields exist
            $isReassignedFromMe = false;
            $isReassignedToMe = false;
            $isOriginalLecture = false;
            
            if (property_exists($lecture, 'reassigned_from_employee_id')) {
                $isReassignedFromMe = ($lecture->reassigned_from_employee_id == $request->employee_id);
            }
            
            if (property_exists($lecture, 'reassigned_to_employee_id')) {
                $isReassignedToMe = ($lecture->reassigned_to_employee_id == $request->employee_id);
            }
            
            if (property_exists($lecture, 'is_original_lecture')) {
                $isOriginalLecture = (bool)$lecture->is_original_lecture;
            }
            
            // Determine if this lecture should be shown
            $shouldShow = true;
            
            // Hide original lectures that have been reassigned away
            if ($isReassignedFromMe && !$isReassignedToMe) {
                $shouldShow = false;
            }
            
            if (!$shouldShow) {
                return null;
            }
            
            // Get frequency label
            $frequencyLabels = [
                'one_time' => 'One Time',
                'daily' => 'Daily',
                'weekly' => 'Weekly',
                'monthly' => 'Monthly'
            ];
            $frequencyLabel = $frequencyLabels[$lecture->frequency] ?? ucfirst($lecture->frequency);
            
            return [
                'id' => $lecture->id,
                'emp_assign_subject_id' => $lecture->emp_assign_subject_id,
                'subject_name' => $lecture->subject_display_name ?? 
                                ($lecture->main_subject_name . ($lecture->sub_subject_name ? ' - ' . $lecture->sub_subject_name : '')),
                'subject_type' => $lecture->subject_type,
                'subject_type_label' => $lecture->subject_type == 'sub_subject' ? 'Sub-Subject' : 'Main Subject',
                'department' => $lecture->department_name,
                'department_id' => $lecture->department_id,
                'course' => $lecture->course_type,
                'course_id' => $lecture->course_detail_id,
                'branch' => $lecture->branch_name,
                'section_id' => $lecture->section_id,
                'section_name' => $sectionName,
                'semester_id' => $lecture->semester_id,
                'start_time' => $lecture->start_time,  
                'end_time' => $lecture->end_time,      
                'start_time_formatted' => $startTime->format('h:i A'),  
                'end_time_formatted' => $endTime->format('h:i A'),     
                'duration' => $startTime->diffInMinutes($endTime),
                'duration_formatted' => $this->formatDuration($startTime->diffInMinutes($endTime)),
                'location' => $lecture->location,
                'frequency' => $lecture->frequency,
                'frequency_label' => $frequencyLabel,
                'status' => $lecture->status,
                'is_cancelled' => property_exists($lecture, 'is_cancelled') ? (bool)$lecture->is_cancelled : false,
                'is_reassigned' => property_exists($lecture, 'is_reassigned') ? (bool)$lecture->is_reassigned : false,
                'is_reassigned_from_me' => $isReassignedFromMe,
                'is_reassigned_to_me' => $isReassignedToMe,
                'is_original_lecture' => $isOriginalLecture,
                'reassignment_reason' => $lecture->reassignment_reason ?? null,
                'reassigned_from_employee_id' => $lecture->reassigned_from_employee_id ?? null,
                'reassigned_to_employee_id' => $lecture->reassigned_to_employee_id ?? null,
                'date' => $selectedDate->format('Y-m-d'),
                'date_formatted' => $selectedDate->format('l, F j, Y'),
                'valid_from' => $lecture->valid_from,
                'valid_to' => $lecture->valid_to,
                'days_of_week' => $lecture->days_of_week,
                'day_of_month' => $lecture->day_of_month
            ];
        })->filter()->values();
        
        // Get employee details
        $employee = EmployeeDetails::where('employee_id', $request->employee_id)
            ->where('institute_id', $instituteId)
            ->first();
        
        return response()->json([
            'success' => true,
            'lectures' => $processedLectures,
            'employee' => [
                'employee_id' => $employee->employee_id,
                'name' => $employee->name,
                'employee_code' => $employee->employee_code ?? $employee->employee_id,
                'department' => $employee->department->department ?? 'N/A'
            ],
            'selected_date' => $selectedDate->format('Y-m-d'),
            'selected_date_formatted' => $selectedDate->format('l, F j, Y'),
            'total_lectures' => $processedLectures->count()
        ]);
    }

    /**
     * Check if a lecture occurs on a specific date based on its frequency
     */
    private function doesLectureOccurOnDate($lecture, Carbon $date)
    {
        // Parse the valid from date
        $validFrom = Carbon::parse($lecture->valid_from);
        
        // Check if date is within valid range
        if ($date->lt($validFrom)) {
            return false;
        }
        
        if ($lecture->valid_to) {
            $validTo = Carbon::parse($lecture->valid_to);
            if ($date->gt($validTo)) {
                return false;
            }
        }
        
        // Check based on frequency
        switch ($lecture->frequency) {
            case 'one_time':
                return $validFrom->toDateString() === $date->toDateString();
                
            case 'daily':
                return true;
                
            case 'weekly':
                $daysOfWeek = $this->parseDaysOfWeek($lecture->days_of_week);
                $dateDayName = $date->format('l');
                $dateDayShort = $date->format('D');
                $dateDayNumber = $date->dayOfWeek;
                
                $dayRepresentations = [$dateDayName, $dateDayShort, $dateDayNumber];
                
                $dayNumberMap = [
                    'Sunday' => 0, 'Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3,
                    'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6,
                    'Sun' => 0, 'Mon' => 1, 'Tue' => 2, 'Wed' => 3,
                    'Thu' => 4, 'Fri' => 5, 'Sat' => 6
                ];
                
                if (isset($dayNumberMap[$dateDayName])) {
                    $dayRepresentations[] = $dayNumberMap[$dateDayName];
                }
                if (isset($dayNumberMap[$dateDayShort])) {
                    $dayRepresentations[] = $dayNumberMap[$dateDayShort];
                }
                
                foreach ($daysOfWeek as $day) {
                    $storedDay = trim((string)$day);
                    if (in_array($storedDay, $dayRepresentations)) {
                        return true;
                    }
                    if (is_numeric($storedDay) && in_array((int)$storedDay, $dayRepresentations)) {
                        return true;
                    }
                }
                return false;
                
            case 'monthly':
                if ($lecture->day_of_month && $date->day == $lecture->day_of_month) {
                    return true;
                }
                return false;
                
            default:
                return false;
        }
    }

    /**
     * Parse days_of_week from JSON string or array
     */
    private function parseDaysOfWeek($daysOfWeek)
    {
        if (is_array($daysOfWeek)) {
            return $daysOfWeek;
        }
        
        if (is_string($daysOfWeek)) {
            $cleaned = stripslashes($daysOfWeek);
            $decoded = json_decode($cleaned, true);
            if (is_array($decoded)) {
                return $decoded;
            }
            $decoded = json_decode($daysOfWeek, true);
            if (is_array($decoded)) {
                return $decoded;
            }
            return array_map('trim', explode(',', $daysOfWeek));
        }
        
        return [];
    }

   
    /**
     * Reassign a lecture to another employee for a specific date
     */
    public function reassignLecture(Request $request)
    {
        $request->validate([
            'lecture_id' => 'required|exists:employee_subject_lectures,id',
            'current_employee_id' => 'required',
            'new_employee_id' => 'required|different:current_employee_id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'required|string|min:10'
        ]);

        $instituteId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();
        $reassignmentDate = Carbon::parse($request->date);

        DB::beginTransaction();

        try {
            // Get the original lecture with assignment details
            $originalLecture = DB::table('employee_subject_lectures as esl')
                ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
                ->leftJoin('departments as d', 'ase.department_id', '=', 'd.department_id')
                ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
                ->select('esl.*', 'ase.*', 'esl.id as lecture_id', 'd.department as department_name', 'pd.course_type')
                ->where('esl.id', $request->lecture_id)
                ->first();

            if (!$originalLecture) {
                throw new \Exception('Original lecture not found');
            }

            // Check if new employee is available at this time
            $hasConflict = $this->checkEmployeeTimeConflict(
                $request->new_employee_id,
                $request->date,
                $request->start_time,
                $request->end_time,
                $request->lecture_id
            );

            if ($hasConflict) {
                throw new \Exception("The selected employee is not available at the requested time.");
            }

            // Find or create assignment for the new employee
            $newAssignment = $this->findOrCreateAssignment(
                $request->new_employee_id,
                $originalLecture,
                $instituteId
            );

            // Get section name for email
            $sectionName = $this->getSectionDisplayName(
                $originalLecture->section_id,
                $originalLecture->course_detail_id,
                $instituteId,
                $context['branch_id'] ?? null
            );

            // CREATE REASSIGNED LECTURE FOR THE NEW EMPLOYEE
            $reassignedLectureData = [
                'emp_assign_subject_id' => $newAssignment->emp_assign_subject_id,
                'frequency' => 'one_time',
                'section_id' => $originalLecture->section_id,
                'subject_type' => $originalLecture->subject_type,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'valid_from' => $request->date,
                'valid_to' => $request->date,
                'days_of_week' => null,
                'day_of_month' => null,
                'location' => $originalLecture->location,
                'status' => 'active',
                'is_reassigned' => true,
                'reassigned_from_employee_id' => $request->current_employee_id,
                'reassigned_to_employee_id' => $request->new_employee_id,
                'reassigned_at' => now(),
                'reassignment_reason' => $request->reason,
                'reassigned_by' => auth()->id(),
                'is_original_lecture' => false,
                'original_lecture_id' => $request->lecture_id,
                'reassignment_date' => $request->date,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
                'remarks' => "Reassigned from employee ID: {$request->current_employee_id} for date {$request->date}. Reason: {$request->reason}",
                'institute_id' => $instituteId,
                'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : $originalLecture->branch_id
            ];

            $newLectureId = DB::table('employee_subject_lectures')->insertGetId($reassignedLectureData);

            // MARK ORIGINAL LECTURE AS REASSIGNED
            DB::table('employee_subject_lectures')
                ->where('id', $request->lecture_id)
                ->update([
                    'is_reassigned' => true,
                    'reassigned_to_employee_id' => $request->new_employee_id,
                    'reassigned_at' => now(),
                    'reassignment_reason' => $request->reason,
                    'reassigned_by' => auth()->id(),
                    'reassignment_date' => $request->date,
                    'updated_at' => now()
                ]);

            // Log the reassignment
            $this->logReassignment($request, $originalLecture, $newLectureId, $context);

            DB::commit();

            // Get employee details for email
            $newEmployee = EmployeeDetails::where('employee_id', $request->new_employee_id)->first();
            $oldEmployee = EmployeeDetails::where('employee_id', $request->current_employee_id)->first();

            // Prepare lecture details for email
            $lectureDetails = [
                'subject_name' => $originalLecture->subject_display_name,
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $originalLecture->location,
                'department' => $originalLecture->department_name ?? null,
                'course' => $originalLecture->course_type ?? null,
                'section_name' => $sectionName
            ];

            // Send email notifications
            $this->sendReassignmentEmails($oldEmployee, $newEmployee, $lectureDetails, $request->reason);

            return response()->json([
                'success' => true,
                'message' => 'Lecture successfully reassigned and email notifications sent!',
                'new_lecture_id' => $newLectureId,
                'reassignment_details' => [
                    'original_employee' => $oldEmployee->name,
                    'new_employee' => $newEmployee->name,
                    'new_employee_id' => $request->new_employee_id,
                    'date' => $request->date,
                    'time' => $request->start_time . ' - ' . $request->end_time
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Lecture reassignment error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Find or create assignment for employee
     */
    private function findOrCreateAssignment($employeeId, $originalAssignment, $instituteId)
    {
        // Try to find existing assignment
        $existingAssignment = DB::table('assign_subjects_to_employee')
            ->where('employee_id', $employeeId)
            ->where('subject_id', $originalAssignment->subject_id)
            ->where('department_id', $originalAssignment->department_id)
            ->where('course_detail_id', $originalAssignment->course_detail_id)
            ->where('institute_id', $instituteId)
            ->where('status', 'active')
            ->first();
        
        if ($existingAssignment) {
            return $existingAssignment;
        }
        
        // Create new assignment
        $newEmpAssignSubjectId = $this->generateEmpAssignSubjectId();
        
        $assignmentData = [
            'emp_assign_subject_id' => $newEmpAssignSubjectId,
            'employee_id' => $employeeId,
            'subject_id' => $originalAssignment->subject_id,
            'sub_subject_id' => $originalAssignment->sub_subject_id,
            'subject_display_name' => $originalAssignment->subject_display_name,
            'subject_type' => $originalAssignment->subject_type,
            'department_id' => $originalAssignment->department_id,
            'course_detail_id' => $originalAssignment->course_detail_id,
            'section_id' => $originalAssignment->section_id,
            'semester_id' => $originalAssignment->semester_id,
            'branch_id' => $originalAssignment->branch_id,
            'institute_id' => $instituteId,
            'status' => 'active',
            'assigned_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => auth()->id(),
            'remarks' => "Created during lecture reassignment from employee ID: {$originalAssignment->employee_id}"
        ];
        
        $assignmentId = DB::table('assign_subjects_to_employee')->insertGetId($assignmentData);
        
        return DB::table('assign_subjects_to_employee')->where('id', $assignmentId)->first();
    }

    /**
     * Generate unique emp_assign_subject_id
     */
    private function generateEmpAssignSubjectId()
    {
        return 'EMP_SUB_' . now()->format('YmdHis') . '_' . strtoupper(substr(uniqid(), -6));
    }
  
    /**
     * Log the reassignment details
     */
    private function logReassignment($request, $originalLecture, $newLectureId, $context)
    {
        // Check if lecture_reassignment_logs table exists
        if (!Schema::hasTable('lecture_reassignment_logs')) {
            // Create table if it doesn't exist
            Schema::create('lecture_reassignment_logs', function ($table) {
                $table->id();
                $table->unsignedBigInteger('lecture_id');
                $table->unsignedBigInteger('new_lecture_id')->nullable();
                $table->unsignedBigInteger('original_employee_id');
                $table->unsignedBigInteger('new_employee_id');
                $table->date('original_date');
                $table->date('new_date');
                $table->time('original_start_time');
                $table->time('original_end_time');
                $table->time('new_start_time');
                $table->time('new_end_time');
                $table->text('reason');
                $table->unsignedBigInteger('reassigned_by');
                $table->timestamp('reassigned_at');
                $table->unsignedBigInteger('institute_id');
                $table->unsignedBigInteger('branch_id')->nullable();
                $table->timestamps();
            });
        }
        
        DB::table('lecture_reassignment_logs')->insert([
            'lecture_id' => $request->lecture_id,
            'new_lecture_id' => $newLectureId,
            'original_employee_id' => $request->current_employee_id,
            'new_employee_id' => $request->new_employee_id,
            'original_date' => $request->date,
            'new_date' => $request->date,
            'original_start_time' => $originalLecture->start_time,
            'original_end_time' => $originalLecture->end_time,
            'new_start_time' => $request->start_time,
            'new_end_time' => $request->end_time,
            'reason' => $request->reason,
            'reassigned_by' => auth()->id(),
            'reassigned_at' => now(),
            'institute_id' => $context['institute_id'],
            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Check if employee has time conflict
     */
    private function checkEmployeeTimeConflict($employeeId, $date, $startTime, $endTime, $excludeLectureId = null)
    {
        $hasIsCancelled = Schema::hasColumn('employee_subject_lectures', 'is_cancelled');
        
        $query = DB::table('employee_subject_lectures as esl')
            ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->where('ase.employee_id', $employeeId)
            ->where('esl.status', 'active')
            ->where('esl.valid_from', '<=', $date)
            ->where(function($q) use ($date) {
                $q->where('esl.valid_to', '>=', $date)
                    ->orWhereNull('esl.valid_to');
            })
            ->where(function($q) use ($startTime, $endTime) {
                $q->where('esl.start_time', '<', $endTime)
                ->where('esl.end_time', '>', $startTime);
            });
            
        if ($hasIsCancelled) {
            $query->where('esl.is_cancelled', false);
        }

        if ($excludeLectureId) {
            $query->where('esl.id', '!=', $excludeLectureId);
        }

        return $query->exists();
    }

    /**
     * Get available employees for a specific time slot
     */
    public function getAvailableEmployees(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'department_id' => 'required|exists:departments,department_id',
            'exclude_employee_id' => 'required'
        ]);

        $instituteId = auth()->user()->institute_id;
        $context = $this->getInstituteBranchContext();

        // Get all active employees in the same department
        $employees = EmployeeDetails::where('institute_id', $instituteId)
            ->where('department_id', $request->department_id)
            ->where('status', 'active')
            ->where('employee_id', '!=', $request->exclude_employee_id)
            ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->orderBy('name')
            ->get();

        $availableEmployees = [];

        foreach ($employees as $employee) {
            // Check if employee has any lecture at this time
            $hasConflict = $this->checkEmployeeTimeConflict(
                $employee->employee_id,
                $request->date,
                $request->start_time,
                $request->end_time
            );
            
            if (!$hasConflict) {
                // Check if employee is present on this date
                $attendance = DB::table('employee_attendances')
                    ->where('employee_id', $employee->employee_id)
                    ->where('date', $request->date)
                    ->where('institute_id', $instituteId)
                    ->first();
                
                $availableEmployees[] = [
                    'employee_id' => $employee->employee_id,
                    'name' => $employee->name,
                    'employee_code' => $employee->employee_code ?? $employee->employee_id,
                    'department' => $employee->department->department ?? 'N/A',
                    'is_present' => ($attendance && $attendance->status === 'Present'),
                    'is_available' => true
                ];
            }
        }

        return response()->json([
            'success' => true,
            'available_employees' => $availableEmployees,
            'total_available' => count($availableEmployees),
            'time_slot' => [
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time
            ]
        ]);
    }

     /**
     * Check if a specific notification channel is enabled for a module
     */
    private function isNotificationEnabled($instituteId, $moduleName, $channel)
    {
        try {
            $setting = \App\Models\InstituteNotificationSetting::where('institute_id', $instituteId)
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
     * Send email notifications for lecture reassignment (with setting check)
     */
    private function sendReassignmentEmails($originalEmployee, $newEmployee, $lectureDetails, $reason)
    {
        try {
            $instituteId = auth()->user()->institute_id;
            
            // Check if email notifications are enabled for lecture reassignment module
            $emailEnabled = $this->isNotificationEnabled(
                $instituteId,
                'lecture_reassignment',  // Module name for lecture reassignment
                'email'
            );
            
            if (!$emailEnabled) {
                \Log::info('Email notifications disabled for lecture_reassignment module', [
                    'institute_id' => $instituteId
                ]);
                return false;
            }
            
            // Get employee email addresses
            $originalEmployeeEmail = $originalEmployee->email ?? 
                                    DB::table('users')->where('id', $originalEmployee->user_id)->value('email');
            
            $newEmployeeEmail = $newEmployee->email ?? 
                                DB::table('users')->where('id', $newEmployee->user_id)->value('email');
            
            // Prepare email details
            $emailDetails = [
                'subject_name' => $lectureDetails['subject_name'],
                'date' => $lectureDetails['date'],
                'start_time' => $lectureDetails['start_time'],
                'end_time' => $lectureDetails['end_time'],
                'location' => $lectureDetails['location'] ?? 'Not specified',
                'department' => $lectureDetails['department'] ?? 'N/A',
                'course' => $lectureDetails['course'] ?? 'N/A',
                'section_name' => $lectureDetails['section_name'] ?? null,
                'reason' => $reason,
                'original_employee_name' => $originalEmployee->name,
                'new_employee_name' => $newEmployee->name
            ];
            
            $emailsSent = 0;
            
            // Send email to original employee (the one losing the lecture)
            if ($originalEmployeeEmail) {
                Mail::to($originalEmployeeEmail)->send(new LectureReassignedFromOriginalEmployee($emailDetails, $originalEmployee));
                $emailsSent++;
                \Log::info('Reassignment email sent to original employee: ' . $originalEmployeeEmail);
            }
            
            // Send email to new employee (the one getting the lecture)
            if ($newEmployeeEmail) {
                Mail::to($newEmployeeEmail)->send(new LectureReassignedToNewEmployee($emailDetails, $newEmployee));
                $emailsSent++;
                \Log::info('Reassignment email sent to new employee: ' . $newEmployeeEmail);
            }
            
            return $emailsSent > 0;
            
        } catch (\Exception $e) {
            \Log::error('Failed to send reassignment emails: ' . $e->getMessage());
            return false;
        }
    }

   
}