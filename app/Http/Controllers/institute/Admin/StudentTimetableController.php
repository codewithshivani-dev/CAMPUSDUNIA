<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Traits\InstituteBranchAccess;
use App\Models\StudentParentDetails;
use App\Models\StudentAcademicTransportDetails;
use App\Models\EmployeeDetails;
use App\Models\EmployeeSubjectLecture;
use App\Models\AssignSubjectsToEmployee;
use App\Models\SubjectsCoursewise;
use App\Models\SubSubject;
use App\Models\ProductDetails;
use App\Models\LectureModeOverride; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StudentTimetableController extends Controller
{
    use InstituteBranchAccess;

    /**
     * Display student timetable view
     */
    public function index()
    {
        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->firstOrFail();
        
        if (!$student) {
            abort(404, 'Student not found');
        }
        
        // Get student's academic details
        $academicDetails = StudentAcademicTransportDetails::where('student_hash_id', $student->student_hash_id)
            ->where('status', 'active')
            ->first();
        
        if (!$academicDetails) {
            return redirect()->back()->with('error', 'Student academic details not found. Please contact administrator.');
        }
        
        // Get course and section info
        $course = ProductDetails::where('product_id', $academicDetails->course_subtype_id)->first();
        $departmentName = null;
        if ($academicDetails->department_id) {
            $department = DB::table('departments')->where('department_id', $academicDetails->department_id)->first();
            $departmentName = $department->department ?? null;
        }
        
        // Get section name
        $sectionName = $this->getSectionName(
            $academicDetails->course_subtype_id,
            $academicDetails->section_id,
            $student->institute_id
        );
        
        return view('instituteAdmin.StudentFiles.Timetable.index', compact(
            'student',
            'academicDetails',
            'course',
            'departmentName',
            'sectionName'
        ));
    }
    
    /**
     * Get student timetable data (grid format) with override support
     */
    public function getTimetableData(Request $request)
    {
        try {
            $user = Auth::user();
            $student = StudentParentDetails::where('user_id', $user->id)->firstOrFail();
            
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Student not found'], 401);
            }
            
            // Get student's academic details
            $academicDetails = StudentAcademicTransportDetails::where('student_hash_id', $student->student_hash_id)
                ->where('status', 'active')
                ->first();
            
            if (!$academicDetails) {
                return response()->json(['success' => false, 'message' => 'Academic details not found'], 404);
            }
            
            $institute_id = $student->institute_id;
            $branch_id = $student->branch_id;
            $student_course_id = $academicDetails->course_subtype_id;
            $student_section_id = $academicDetails->section_id;
            
            // Get date range
            $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfWeek();
            $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfWeek();
            
            // Limit to 90 days for performance
            $maxEndDate = Carbon::now()->addDays(90);
            if ($endDate->gt($maxEndDate)) {
                $endDate = $maxEndDate;
            }
            
            // Get all lectures for student's course and section with overrides applied
            $lectures = $this->getLecturesForStudentGridWithOverrides(
                $institute_id, 
                $branch_id, 
                $student_course_id, 
                $student_section_id, 
                $startDate, 
                $endDate
            );
            
            // Generate time slots
            $timeSlots = $this->generateTimeSlots($lectures);
            
            // Generate days between start and end date
            $days = $this->generateDaysArray($startDate, $endDate);
            
            // Build timetable grid
            $timetable = $this->buildTimetableGrid($lectures, $days, $timeSlots);
            
            // Generate list data
            $listData = $this->generateListData($lectures);
            
            // Generate month data
            $monthData = $this->generateMonthData($lectures, $startDate, $endDate);
            
            return response()->json([
                'success' => true,
                'timeSlots' => $timeSlots,
                'days' => $days,
                'timetable' => $timetable,
                'listData' => $listData,
                'monthData' => $monthData,
                'student_info' => [
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'course' => $academicDetails->course_subtype,
                    'section' => $this->getSectionName($student_course_id, $student_section_id, $institute_id)
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getTimetableData: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching timetable: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get lectures for student grid with override support (similar to employee controller)
     */
    private function getLecturesForStudentGridWithOverrides($institute_id, $branch_id, $course_id, $section_id, $startDate, $endDate)
    {
        if (!$course_id) {
            return [];
        }
        
        // Find all employee assignments for this course
        $employeeAssignments = AssignSubjectsToEmployee::where('institute_id', $institute_id)
            ->where('course_detail_id', $course_id)
            ->where('status', 'active')
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            });
        
        if ($section_id) {
            $employeeAssignments->where(function($query) use ($section_id) {
                $query->where('section_id', $section_id)
                    ->orWhereNull('section_id');
            });
        }
        
        $employeeAssignments = $employeeAssignments->get();
        
        if ($employeeAssignments->isEmpty()) {
            return [];
        }
        
        $empAssignSubjectIds = $employeeAssignments->pluck('emp_assign_subject_id')->unique()->values();
        
        // Get all lectures from these employees
        $lectures = EmployeeSubjectLecture::where('institute_id', $institute_id)
            ->whereIn('emp_assign_subject_id', $empAssignSubjectIds)
            ->where('status', 'active')
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->get();
        
        // Get employee details
        $employeeIds = $employeeAssignments->pluck('employee_id')->unique();
        $employees = EmployeeDetails::whereIn('employee_id', $employeeIds)
            ->select('employee_id', 'name', 'designation')
            ->get()
            ->keyBy('employee_id');
        
        // Get subject details
        $subjectDetails = [];
        foreach ($employeeAssignments as $assignment) {
            $mainSubjectName = $this->getMainSubjectName($assignment->subject_id);
            $subSubjectName = $this->getSubSubjectName($assignment->subject_id, $assignment->sub_subject_id);
            
            $subjectDetails[$assignment->emp_assign_subject_id] = [
                'subject_display_name' => $assignment->subject_display_name ?? $mainSubjectName,
                'subject_type' => $assignment->subject_type ?? 'main',
                'main_subject_name' => $mainSubjectName,
                'sub_subject_name' => $subSubjectName,
                'section_name' => $this->getSectionName($course_id, $assignment->section_id, $institute_id),
                'employee_id' => $assignment->employee_id,
                'department_id' => $assignment->department_id,
                'course_detail_id' => $assignment->course_detail_id
            ];
        }
        
        // Get department names
        $departmentIds = array_column($subjectDetails, 'department_id');
        $departments = DB::table('departments')
            ->whereIn('department_id', array_filter($departmentIds))
            ->pluck('department', 'department_id')
            ->toArray();
        
        // Process each lecture with override support
        $processedEvents = collect();
        
        foreach ($lectures as $lecture) {
            $subjectInfo = $subjectDetails[$lecture->emp_assign_subject_id] ?? null;
            
            if (!$subjectInfo) {
                continue;
            }
            
            $employee = $employees[$subjectInfo['employee_id']] ?? null;
            $teacherName = $employee ? $employee->name : 'Staff';
            $teacherDesignation = $employee ? $employee->designation : null;
            
            $subjectName = $subjectInfo['subject_display_name'] ?? 'Lecture';
            
            // Skip reassigned lectures
            if ($lecture->frequency === 'one_time' && $lecture->reassigned_to_employee_id) {
                continue;
            }
            
            // Process based on frequency with override support
            $eventsFromLecture = $this->expandLectureWithOverrides(
                $lecture, 
                $subjectName, 
                $teacherName, 
                $teacherDesignation, 
                $subjectInfo, 
                $departments, 
                $startDate, 
                $endDate,
                $institute_id
            );
            
            foreach ($eventsFromLecture as $event) {
                $processedEvents->push($event);
            }
        }
        
        return $processedEvents->values()->toArray();
    }
    
    /**
     * Expand a lecture into individual occurrences with override support
     * Similar to getLecturesWithOverridesForEmployee in EmployeeSubjectsController
     */
    private function expandLectureWithOverrides($lecture, $subjectName, $teacherName, $teacherDesignation, $subjectInfo, $departments, $startDate, $endDate, $institute_id)
    {
        $result = [];
        
        $lectureStart = Carbon::parse($lecture->valid_from);
        $lectureEnd = Carbon::parse($lecture->valid_to);
        
        // For one_time lectures, only generate a single occurrence if within date range
        if (in_array($lecture->frequency, ['one_time', 'once'])) {
            $date = $lectureStart->format('Y-m-d');
            if ($date >= $startDate->format('Y-m-d') && $date <= $endDate->format('Y-m-d')) {
                // Check for override on this specific date
                $override = LectureModeOverride::where('lecture_id', $lecture->id)
                    ->where('override_date', $date)
                    ->where('institute_id', $institute_id)
                    ->where('is_active', true)
                    ->first();
                
                $lectureMode = $lecture->lecture_mode ?? 'offline';
                $meetingLink = $lecture->meeting_link;
                $meetingPassword = $lecture->meeting_password;
                $meetingInstructions = $lecture->meeting_instructions;
                $hasOverride = false;
                $overrideId = null;
                $overrideReason = null;
                
                if ($override) {
                    $lectureMode = $override->lecture_mode;
                    $meetingLink = $override->meeting_link;
                    $meetingPassword = $override->meeting_password;
                    $meetingInstructions = $override->meeting_instructions;
                    $hasOverride = true;
                    $overrideId = $override->id;
                    $overrideReason = $override->remarks;
                }
                
                $result[] = $this->buildEventObject(
                    $lecture, $subjectName, $teacherName, $teacherDesignation,
                    $subjectInfo, $departments, $date, 'one_time',
                    $lectureMode, $meetingLink, $meetingPassword, $meetingInstructions,
                    $hasOverride, $overrideId, $overrideReason
                );
            }
        }
        // For recurring lectures, generate occurrences within date range with override checks
        else {
            $current = clone $lectureStart;
            if ($current < $startDate) {
                $current = clone $startDate;
            }
            
            while ($current <= $lectureEnd && $current <= $endDate) {
                $dayOfWeek = $current->dayOfWeek;
                $includeDate = false;
                
                switch ($lecture->frequency) {
                    case 'daily':
                        $includeDate = true;
                        break;
                    case 'weekly':
                        if (!empty($lecture->days_of_week)) {
                            $daysOfWeek = $this->parseDaysOfWeek($lecture->days_of_week);
                            if (in_array($dayOfWeek, $daysOfWeek)) {
                                $includeDate = true;
                            }
                        } else {
                            $includeDate = true;
                        }
                        break;
                    case 'monthly':
                        $dayOfMonth = $lecture->day_of_month ?: $lectureStart->day;
                        if ($current->day == $dayOfMonth) {
                            $includeDate = true;
                        }
                        break;
                    default:
                        $includeDate = true;
                        break;
                }
                
                if ($includeDate) {
                    $dateStr = $current->format('Y-m-d');
                    
                    // Check for override on this specific date
                    $override = LectureModeOverride::where('lecture_id', $lecture->id)
                        ->where('override_date', $dateStr)
                        ->where('institute_id', $institute_id)
                        ->where('is_active', true)
                        ->first();
                    
                    $lectureMode = $lecture->lecture_mode ?? 'offline';
                    $meetingLink = $lecture->meeting_link;
                    $meetingPassword = $lecture->meeting_password;
                    $meetingInstructions = $lecture->meeting_instructions;
                    $hasOverride = false;
                    $overrideId = null;
                    $overrideReason = null;
                    
                    if ($override) {
                        $lectureMode = $override->lecture_mode;
                        $meetingLink = $override->meeting_link;
                        $meetingPassword = $override->meeting_password;
                        $meetingInstructions = $override->meeting_instructions;
                        $hasOverride = true;
                        $overrideId = $override->id;
                        $overrideReason = $override->remarks;
                    }
                    
                    $result[] = $this->buildEventObject(
                        $lecture, $subjectName, $teacherName, $teacherDesignation,
                        $subjectInfo, $departments, $dateStr, $lecture->frequency,
                        $lectureMode, $meetingLink, $meetingPassword, $meetingInstructions,
                        $hasOverride, $overrideId, $overrideReason
                    );
                }
                
                $current->addDay();
            }
        }
        
        return $result;
    }
    
    /**
     * Build event object for grid with override support
     */
    private function buildEventObject($lecture, $subjectName, $teacherName, $teacherDesignation, $subjectInfo, $departments, $date, $frequency, 
                                        $lectureMode = null, $meetingLink = null, $meetingPassword = null, $meetingInstructions = null,
                                        $hasOverride = false, $overrideId = null, $overrideReason = null)
    {
        $departmentName = isset($departments[$subjectInfo['department_id']]) ? $departments[$subjectInfo['department_id']] : null;
        
        // Use provided mode or fallback to lecture's mode
        $mode = $lectureMode ?? ($lecture->lecture_mode ?? 'offline');
        $colorClass = $mode === 'online' ? 'online' : 'offline';
        
        return [
            'id' => $lecture->id,
            'type' => 'lecture',
            'title' => $subjectName,
            'subject' => $subjectName,
            'faculty' => $teacherName,
            'designation' => $teacherDesignation,
            'date' => $date,
            'start_time' => $lecture->start_time,
            'end_time' => $lecture->end_time,
            'start_time_formatted' => $lecture->start_time ? date('h:i A', strtotime($lecture->start_time)) : 'N/A',
            'end_time_formatted' => $lecture->end_time ? date('h:i A', strtotime($lecture->end_time)) : 'N/A',
            'location' => $lecture->location ?? 'TBA',
            'room' => $lecture->location ?? 'TBA',
            'frequency' => $frequency,
            'department' => $departmentName,
            'course' => $subjectInfo['course_detail_id'] ?? null,
            'section' => $subjectInfo['section_name'] ?? null,
            'lecture_mode' => $mode,
            'meeting_link' => $meetingLink,
            'meeting_password' => $meetingPassword,
            'meeting_instructions' => $meetingInstructions,
            'description' => $lecture->remarks ?? 'No description',
            'color_class' => $colorClass,
            'has_override' => $hasOverride,
            'override_id' => $overrideId,
            'override_reason' => $overrideReason,
            'is_reassigned' => !is_null($lecture->reassigned_to_employee_id),
            'assignment_id' => $lecture->emp_assign_subject_id
        ];
    }
    
    /**
     * Generate time slots from events
     */
    private function generateTimeSlots($events)
    {
        $timeSlots = [];
        foreach ($events as $event) {
            $key = $event['start_time'] . '-' . $event['end_time'];
            $display = date('h:i A', strtotime($event['start_time'])) . ' - ' . date('h:i A', strtotime($event['end_time']));
            if (!isset($timeSlots[$key])) {
                $timeSlots[$key] = [
                    'start_time' => $event['start_time'],
                    'end_time' => $event['end_time'],
                    'display' => $display,
                    'key' => $key
                ];
            }
        }
        
        // Sort by start time
        usort($timeSlots, function($a, $b) {
            return strcmp($a['start_time'], $b['start_time']);
        });
        
        return array_values($timeSlots);
    }
    
    /**
     * Generate days array
     */
    private function generateDaysArray($startDate, $endDate)
    {
        $days = [];
        $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $currentDate = clone $startDate;
        
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $dayOfWeek = $currentDate->dayOfWeek;
            $dayName = $dayNames[$dayOfWeek == 0 ? 6 : $dayOfWeek - 1];
            
            $days[$dayName] = [
                'name' => $dayName,
                'date' => $dateStr,
                'display_date' => $currentDate->format('d M Y'),
                'is_weekend' => ($dayOfWeek == 0 || $dayOfWeek == 6)
            ];
            
            $currentDate->addDay();
        }
        
        return $days;
    }
    
    /**
     * Build timetable grid
     */
    private function buildTimetableGrid($events, $days, $timeSlots)
    {
        $timetable = [];
        
        foreach ($events as $event) {
            $date = $event['date'];
            $timeKey = $event['start_time'] . '-' . $event['end_time'];
            
            if (!isset($timetable[$date])) {
                $timetable[$date] = [];
            }
            
            if (!isset($timetable[$date][$timeKey])) {
                $timetable[$date][$timeKey] = $event;
            } else {
                // Handle multiple events at same time
                $existing = $timetable[$date][$timeKey];
                if (isset($existing['has_multiple'])) {
                    $existing['events'][] = $event;
                    $existing['lecture_count'] = count(array_filter($existing['events'], fn($e) => $e['type'] === 'lecture'));
                    $timetable[$date][$timeKey] = $existing;
                } else {
                    $timetable[$date][$timeKey] = [
                        'has_multiple' => true,
                        'events' => [$existing, $event],
                        'lecture_count' => 2,
                        'duty_count' => 0
                    ];
                }
            }
        }
        
        return $timetable;
    }
    
    /**
     * Generate list data
     */
    private function generateListData($events)
    {
        usort($events, function($a, $b) {
            $dateCompare = strcmp($a['date'], $b['date']);
            if ($dateCompare == 0) {
                return strcmp($a['start_time'], $b['start_time']);
            }
            return $dateCompare;
        });
        
        return $events;
    }
    
    /**
     * Generate month data
     */
    private function generateMonthData($events, $startDate, $endDate)
    {
        $monthData = [];
        
        foreach ($events as $event) {
            $date = $event['date'];
            if (!isset($monthData[$date])) {
                $monthData[$date] = [
                    'lectures' => [],
                    'duties' => []
                ];
            }
            
            if ($event['type'] === 'lecture') {
                $monthData[$date]['lectures'][] = $event;
            } else {
                $monthData[$date]['duties'][] = $event;
            }
        }
        
        return $monthData;
    }
    
    /**
     * Parse days of week from JSON
     */
    private function parseDaysOfWeek($daysOfWeek)
    {
        if (is_string($daysOfWeek)) {
            $daysOfWeek = json_decode($daysOfWeek, true);
        }
        
        if (!is_array($daysOfWeek)) {
            return [];
        }
        
        $dayMap = [
            'Sunday' => 0, 'Sun' => 0,
            'Monday' => 1, 'Mon' => 1,
            'Tuesday' => 2, 'Tue' => 2,
            'Wednesday' => 3, 'Wed' => 3,
            'Thursday' => 4, 'Thu' => 4,
            'Friday' => 5, 'Fri' => 5,
            'Saturday' => 6, 'Sat' => 6
        ];
        
        $days = [];
        foreach ($daysOfWeek as $day) {
            if (isset($dayMap[$day])) {
                $days[] = $dayMap[$day];
            }
        }
        
        return $days;
    }
    
    /**
     * Get main subject name
     */
    private function getMainSubjectName($subjectId)
    {
        $subject = SubjectsCoursewise::where('subject_id', $subjectId)->first();
        return $subject ? $subject->subject_name : null;
    }
    
    /**
     * Get sub-subject name
     */
    private function getSubSubjectName($subjectId, $subSubjectId)
    {
        if (!$subSubjectId) return null;
        
        $subSubject = SubSubject::where('subject_id', $subjectId)
            ->where('sub_subject_id', $subSubjectId)
            ->first();
        return $subSubject ? $subSubject->sub_subject_name : null;
    }
    
    /**
     * Get section name
     */
    private function getSectionName($productId, $sectionId, $instituteId)
    {
        if (!$productId || !$sectionId) return 'N/A';
        
        $feeStructure = DB::table('course_fee_structures')
            ->where('product_id', $productId)
            ->where('institute_id', $instituteId)
            ->first();
        
        if ($feeStructure && $feeStructure->sections) {
            $sections = json_decode($feeStructure->sections, true);
            if (is_array($sections)) {
                foreach ($sections as $section) {
                    $secId = $section['section_id'] ?? $section['id'] ?? null;
                    if ($secId && (string)$secId === (string)$sectionId) {
                        return $section['name'] ?? $section['section_name'] ?? "Section " . $sectionId;
                    }
                }
            }
        }
        
        return "Section " . $sectionId;
    }
    
    /**
     * Get lecture details for modal with override support
     */
    public function getLectureDetails($id)
    {
        try {
            $lecture = EmployeeSubjectLecture::find($id);
            
            if (!$lecture) {
                return response()->json(['success' => false, 'message' => 'Lecture not found'], 404);
            }
            
            $assignment = AssignSubjectsToEmployee::where('emp_assign_subject_id', $lecture->emp_assign_subject_id)->first();
            
            if (!$assignment) {
                return response()->json(['success' => false, 'message' => 'Assignment not found'], 404);
            }
            
            $employee = EmployeeDetails::where('employee_id', $assignment->employee_id)->first();
            
            $departmentName = null;
            if ($assignment->department_id) {
                $department = DB::table('departments')->where('department_id', $assignment->department_id)->first();
                $departmentName = $department->department ?? null;
            }
            
            $course = ProductDetails::where('product_id', $assignment->course_detail_id)->first();
            
            $mainSubject = SubjectsCoursewise::where('subject_id', $assignment->subject_id)->first();
            $subSubject = null;
            if ($assignment->sub_subject_id) {
                $subSubject = SubSubject::where('sub_subject_id', $assignment->sub_subject_id)->first();
            }
            
            // Check for override on the specific date (if date parameter is provided)
            $dateParam = request()->get('date');
            $hasOverride = false;
            $overrideMode = null;
            $overrideMeetingLink = null;
            $overrideMeetingPassword = null;
            $overrideMeetingInstructions = null;
            $overrideReason = null;
            
            if ($dateParam) {
                $override = LectureModeOverride::where('lecture_id', $lecture->id)
                    ->where('override_date', $dateParam)
                    ->where('is_active', true)
                    ->first();
                
                if ($override) {
                    $hasOverride = true;
                    $overrideMode = $override->lecture_mode;
                    $overrideMeetingLink = $override->meeting_link;
                    $overrideMeetingPassword = $override->meeting_password;
                    $overrideMeetingInstructions = $override->meeting_instructions;
                    $overrideReason = $override->remarks;
                }
            }
            
            return response()->json([
                'success' => true,
                'lecture' => [
                    'id' => $lecture->id,
                    'title' => $assignment->subject_display_name ?? ($mainSubject->subject_name ?? 'Lecture'),
                    'teacher_name' => $employee ? $employee->name : 'Staff',
                    'teacher_designation' => $employee ? $employee->designation : null,
                    'department_name' => $departmentName,
                    'course_name' => $course ? $course->course_type : null,
                    'subject_name' => $mainSubject ? $mainSubject->subject_name : null,
                    'sub_subject_name' => $subSubject ? $subSubject->sub_subject_name : null,
                    'date' => $lecture->valid_from,
                    'start_time' => $lecture->start_time,
                    'end_time' => $lecture->end_time,
                    'frequency' => $lecture->frequency,
                    'location' => $lecture->location,
                    'lecture_mode' => $overrideMode ?? ($lecture->lecture_mode ?? 'offline'),
                    'default_lecture_mode' => $lecture->lecture_mode ?? 'offline',
                    'meeting_link' => $overrideMeetingLink ?? $lecture->meeting_link,
                    'meeting_password' => $overrideMeetingPassword ?? $lecture->meeting_password,
                    'meeting_instructions' => $overrideMeetingInstructions ?? $lecture->meeting_instructions,
                    'remarks' => $lecture->remarks,
                    'has_override' => $hasOverride,
                    'override_reason' => $overrideReason
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error getting lecture details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching lecture details'
            ], 500);
        }
    }
}