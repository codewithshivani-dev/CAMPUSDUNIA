<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDetails;
use App\Models\ProductDetails;
use App\Models\SubjectsCoursewise;
use App\Models\Departments;
use App\Models\DepartmentCategory;
use App\Models\AssignSubjectsToEmployee;
use App\Models\EmployeeSubjectLecture;
use App\Models\InstituteBasicDetails;
use App\Models\AssignDuties;
use App\Models\DutyType;
use App\Models\LectureModeOverride; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class EmployeeSubjectsController extends Controller
{
    use \App\Traits\InstituteBranchAccess;
    use \App\Traits\SectionNameHelper;
    
   public function getEmployeeSchedule()
{
    $context = $this->getInstituteBranchContext();
    
    if (!$context['institute_id']) {
        return redirect()->back()->with('error', 'You are not associated with any institute.');
    }
    
    $user = Auth::user();
    $employee = EmployeeDetails::where('user_id', $user->id)->firstOrFail();
    $employeeId = $employee->employee_id;
    
    if (!$employeeId) {
        return redirect()->back()->with('error', 'Employee ID not found.');
    }
    
    $today = Carbon::today();
    $todayDate = $today->format('Y-m-d');
    
    // Get all lectures
    $allLectures = collect();
    
    // 1. Get lectures directly assigned to this employee
    $directLectures = DB::table('employee_subject_lectures as esl')
        ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
        ->leftJoin('subjects_coursewise as s', 'ase.subject_id', '=', 's.subject_id')
        ->leftJoin('sub_subjects as ss', function($join) {
            $join->on('ase.sub_subject_id', '=', 'ss.sub_subject_id')
                ->on('ase.subject_id', '=', 'ss.subject_id');
        })
        ->leftJoin('departments as d', 'ase.department_id', '=', 'd.department_id')
        ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
        ->leftJoin('employee_details as reassigned_to_emp', 'esl.reassigned_to_employee_id', '=', 'reassigned_to_emp.employee_id')
        ->leftJoin('employee_details as reassigned_from_emp', 'esl.reassigned_from_employee_id', '=', 'reassigned_from_emp.employee_id')
        ->select(
            'esl.*',
            'ase.emp_assign_subject_id',
            'ase.subject_display_name',
            'ase.subject_type',
            'ase.semester_id',
            'ase.assigned_date',
            'ase.remarks as assignment_remarks',
            'ase.status as assignment_status',
            'ase.department_id',
            'ase.section_id',
            'ase.course_detail_id',
            'ase.branch_id',
            's.subject_name as main_subject_name',
            'ss.sub_subject_name',
            'd.department as department_name',
            'pd.course_type',
            'pd.sub_type as branch_name',
            'reassigned_to_emp.name as reassigned_to_employee_name',
            'reassigned_from_emp.name as reassigned_from_employee_name',
            DB::raw('TIME(esl.start_time) as start_time_only'),
            DB::raw('TIME(esl.end_time) as end_time_only'),
            DB::raw('TIMEDIFF(esl.end_time, esl.start_time) as duration'),
            DB::raw("'lecture' as event_type")
        )
        ->where('ase.employee_id', $employeeId)
        ->where('ase.institute_id', $context['institute_id'])
        ->where('ase.status', 'active')
        ->where('esl.status', 'active')
        ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            return $query->where('ase.branch_id', $context['branch_id']);
        })
        ->get();
    
    $allLectures = $allLectures->merge($directLectures);
    
    // 2. Get lectures reassigned TO this employee
    $reassignedToMe = DB::table('employee_subject_lectures as esl')
        ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
        ->leftJoin('subjects_coursewise as s', 'ase.subject_id', '=', 's.subject_id')
        ->leftJoin('sub_subjects as ss', function($join) {
            $join->on('ase.sub_subject_id', '=', 'ss.sub_subject_id')
                ->on('ase.subject_id', '=', 'ss.subject_id');
        })
        ->leftJoin('departments as d', 'ase.department_id', '=', 'd.department_id')
        ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
        ->leftJoin('employee_details as reassigned_from_emp', 'esl.reassigned_from_employee_id', '=', 'reassigned_from_emp.employee_id')
        ->select(
            'esl.*',
            'ase.emp_assign_subject_id',
            'ase.subject_display_name',
            'ase.subject_type',
            'ase.semester_id',
            'ase.assigned_date',
            'ase.remarks as assignment_remarks',
            'ase.status as assignment_status',
            'ase.department_id',
            'ase.section_id',
            'ase.course_detail_id',
            'ase.branch_id',
            's.subject_name as main_subject_name',
            'ss.sub_subject_name',
            'd.department as department_name',
            'pd.course_type',
            'pd.sub_type as branch_name',
            'reassigned_from_emp.name as reassigned_from_employee_name',
            DB::raw('TIME(esl.start_time) as start_time_only'),
            DB::raw('TIME(esl.end_time) as end_time_only'),
            DB::raw('TIMEDIFF(esl.end_time, esl.start_time) as duration'),
            DB::raw("'lecture' as event_type")
        )
        ->where('esl.reassigned_to_employee_id', $employeeId)
        ->where('esl.status', 'active')
        ->where('esl.frequency', 'one_time')
        ->where('esl.valid_to', '>=', $today)
        ->get();
    
    $allLectures = $allLectures->merge($reassignedToMe);
    
    // Process lectures with overrides
    $processedLectures = collect();
    foreach ($allLectures as $lecture) {
        $processedLectures = $processedLectures->merge($this->getLecturesWithOverridesForEmployee($lecture, $context['institute_id'], $context['branch_id']));
    }
    
    // 3. Get duties assigned to this employee - FIXED to match admin controller format
    $duties = collect();
    $dutyRecords = AssignDuties::with(['employee', 'dutyType'])
        ->where('institute_id', $context['institute_id'])
        ->where('employee_id', $employeeId)
        ->where('status', '!=', 'cancelled')
        ->where('status', '!=', 'completed')
        ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            return $query->where('branch_id', $context['branch_id']);
        })
        ->get();
    
    $priorityColors = [
        'low' => 'duty-low',
        'medium' => 'duty-medium', 
        'high' => 'duty-high',
        'urgent' => 'duty-urgent'
    ];
    
    foreach ($dutyRecords as $duty) {
        // Get department name
        $departmentName = null;
        if ($duty->department_id) {
            $department = DB::table('departments')->where('department_id', $duty->department_id)->first();
            $departmentName = $department->department ?? null;
        }
        
        // Parse days of week for weekly duties
        $daysOfWeek = [];
        if ($duty->frequency === 'weekly' && $duty->days_of_week) {
            if (is_string($duty->days_of_week)) {
                $daysOfWeek = json_decode($duty->days_of_week, true);
            } elseif (is_array($duty->days_of_week)) {
                $daysOfWeek = $duty->days_of_week;
            }
            if (!is_array($daysOfWeek)) {
                $daysOfWeek = [];
            }
        }
        
        // Format start and end times to just H:i:s (no date part)
        $startTime = date('H:i:s', strtotime($duty->start_time));
        $endTime = date('H:i:s', strtotime($duty->end_time));
        
        if ($duty->frequency === 'once' && $duty->date) {
            $dutyDate = Carbon::parse($duty->date);
            
            $duties->push((object)[
                'id' => $duty->id,
                'event_type' => 'duty',
                'subject_display_name' => $duty->title ?? $duty->duty_type ?? 'Duty',
                'subject_type' => 'duty',
                'subject_type_label' => 'Duty',
                'main_subject_name' => $duty->duty_type,
                'sub_subject_name' => null,
                'department_name' => $departmentName,
                'department_id' => $duty->department_id,
                'course_type' => null,
                'branch_name' => null,
                'section_name' => null,
                'semester_id' => null,
                'frequency' => $duty->frequency,
                'frequency_label' => $this->getFrequencyLabel($duty->frequency),
                'start_time' => $startTime, // Just time part
                'end_time' => $endTime,     // Just time part
                'valid_from' => $dutyDate->format('Y-m-d'),
                'valid_to' => $dutyDate->format('Y-m-d'),
                'days_of_week' => $daysOfWeek,
                'day_of_month' => $duty->day_of_month,
                'location' => $duty->location,
                'venue' => $duty->venue,
                'duration' => null,
                'status' => $duty->status,
                'remarks' => $duty->description,
                'instructions' => $duty->instructions,
                'is_reassigned' => false,
                'is_reassignment_lecture' => false,
                'reassignment_info' => null,
                'priority' => $duty->priority,
                'duty_type' => $duty->duty_type,
                'employee_duty_id' => $duty->employee_duty_id,
                'employee_name' => $duty->employee->name ?? null,
                'designation' => $duty->employee->designation ?? null,
                // For consistency with lecture structure
                'lecture_mode' => 'offline',
                'has_override' => false,
                'meeting_link' => null,
                'meeting_password' => null,
                'meeting_id' => null,
                'meeting_instructions' => null,
                'color_class' => $priorityColors[$duty->priority] ?? 'duty-medium'
            ]);
        } 
        elseif (in_array($duty->frequency, ['daily', 'weekly', 'monthly']) && $duty->from_date && $duty->to_date) {
            $fromDate = Carbon::parse($duty->from_date);
            $toDate = Carbon::parse($duty->to_date);
            
            // Limit to next 90 days for performance
            $maxEnd = Carbon::today()->addDays(90);
            if ($toDate->gt($maxEnd)) {
                $toDate = $maxEnd;
            }
            
            $current = clone $fromDate;
            while ($current <= $toDate) {
                $includeDate = false;
                
                switch ($duty->frequency) {
                    case 'daily':
                        $includeDate = true;
                        break;
                    case 'weekly':
                        if (empty($daysOfWeek)) {
                            $includeDate = true;
                        } else {
                            $currentDayNum = $current->dayOfWeek;
                            $includeDate = in_array($currentDayNum, $daysOfWeek);
                        }
                        break;
                    case 'monthly':
                        $dayOfMonth = $duty->day_of_month ?: $fromDate->day;
                        $includeDate = ($current->day == $dayOfMonth);
                        break;
                }
                
                if ($includeDate) {
                    $duties->push((object)[
                        'id' => $duty->id,
                        'event_type' => 'duty',
                        'subject_display_name' => $duty->title ?? $duty->duty_type ?? 'Duty',
                        'subject_type' => 'duty',
                        'subject_type_label' => 'Duty',
                        'main_subject_name' => $duty->duty_type,
                        'sub_subject_name' => null,
                        'department_name' => $departmentName,
                        'department_id' => $duty->department_id,
                        'course_type' => null,
                        'branch_name' => null,
                        'section_name' => null,
                        'semester_id' => null,
                        'frequency' => $duty->frequency,
                        'frequency_label' => $this->getFrequencyLabel($duty->frequency),
                        'start_time' => $startTime, // Just time part
                        'end_time' => $endTime,     // Just time part
                        'valid_from' => $current->format('Y-m-d'),
                        'valid_to' => $current->format('Y-m-d'),
                        'days_of_week' => $daysOfWeek,
                        'day_of_month' => $duty->day_of_month,
                        'location' => $duty->location,
                        'venue' => $duty->venue,
                        'duration' => null,
                        'status' => $duty->status,
                        'remarks' => $duty->description,
                        'instructions' => $duty->instructions,
                        'is_reassigned' => false,
                        'is_reassignment_lecture' => false,
                        'reassignment_info' => null,
                        'priority' => $duty->priority,
                        'duty_type' => $duty->duty_type,
                        'employee_duty_id' => $duty->employee_duty_id,
                        'employee_name' => $duty->employee->name ?? null,
                        'designation' => $duty->employee->designation ?? null,
                        'lecture_mode' => 'offline',
                        'has_override' => false,
                        'meeting_link' => null,
                        'meeting_password' => null,
                        'meeting_id' => null,
                        'meeting_instructions' => null,
                        'color_class' => $priorityColors[$duty->priority] ?? 'duty-medium'
                    ]);
                }
                
                $current->addDay();
            }
        }
    }
    
    $allEvents = $processedLectures->merge($duties);
    
    // Process and format all events
    $processedEvents = collect();
    
    foreach ($allEvents as $event) {
        if ($event->event_type === 'lecture' && isset($event->is_cancelled) && $event->is_cancelled == 1) {
            continue;
        }
        
        if ($event->event_type === 'lecture' && $event->frequency == 'one_time' && isset($event->is_reassigned) && $event->is_reassigned == 1) {
            if ($event->valid_to < $todayDate) {
                continue;
            }
        }
        
        $sectionName = null;
        if ($event->event_type === 'lecture' && isset($event->section_id) && isset($event->course_detail_id)) {
            $sectionName = $this->getSectionDisplayName(
                $event->section_id,
                $event->course_detail_id,
                $context['institute_id'],
                $event->branch_id ?? null
            );
        }
        
        $daysOfWeek = [];
        if (isset($event->days_of_week) && is_string($event->days_of_week)) {
            try {
                $daysOfWeek = json_decode($event->days_of_week, true) ?: [];
            } catch (\Exception $e) {
                $daysOfWeek = [];
            }
        } elseif (isset($event->days_of_week) && is_array($event->days_of_week)) {
            $daysOfWeek = $event->days_of_week;
        }
        
        $reassignmentInfo = null;
        if ($event->event_type === 'lecture') {
            $isReassignedToMe = (isset($event->reassigned_to_employee_id) && $event->reassigned_to_employee_id == $employeeId);
            $isReassignedFromMe = (isset($event->reassigned_from_employee_id) && $event->reassigned_from_employee_id == $employeeId);
            $isReassignmentLecture = ($event->frequency == 'one_time' && isset($event->is_reassigned) && $event->is_reassigned == 1);
            
            if ($isReassignmentLecture && $isReassignedToMe) {
                $reassignmentInfo = [
                    'is_reassigned_to_me' => true,
                    'is_reassigned_from_me' => false,
                    'reassigned_from_employee_name' => $event->reassigned_from_employee_name ?? 'Unknown',
                    'reassigned_at' => $event->reassigned_at ?? null,
                    'reassignment_reason' => $event->reassignment_reason ?? null,
                    'reassignment_date' => $event->reassignment_date ?? $event->valid_from,
                    'original_frequency' => 'regular'
                ];
            } 
            elseif (isset($event->reassignment_date) && $event->reassignment_date == $todayDate && isset($event->is_reassigned) && $event->is_reassigned == 1) {
                $reassignmentInfo = [
                    'is_reassigned_to_me' => false,
                    'is_reassigned_from_me' => true,
                    'reassigned_to_employee_name' => $event->reassigned_to_employee_name ?? 'Unknown',
                    'reassigned_at' => $event->reassigned_at ?? null,
                    'reassignment_reason' => $event->reassignment_reason ?? null,
                    'reassignment_date' => $event->reassignment_date,
                    'original_frequency' => $event->frequency
                ];
            }
        }
        
        // Ensure start_time and end_time are just time parts (H:i:s)
        $startTime = $event->start_time;
        $endTime = $event->end_time;
        
        // If they contain a date, extract just the time part
        if (strpos($startTime, ' ') !== false || strpos($startTime, 'T') !== false) {
            $startTime = date('H:i:s', strtotime($startTime));
        }
        if (strpos($endTime, ' ') !== false || strpos($endTime, 'T') !== false) {
            $endTime = date('H:i:s', strtotime($endTime));
        }
        
        $processedEvents->push((object)[
            'id' => $event->id,
            'event_type' => $event->event_type ?? 'lecture',
            'emp_assign_subject_id' => $event->emp_assign_subject_id ?? null,
            'subject_display_name' => $event->subject_display_name,
            'subject_type' => $event->subject_type,
            'subject_type_label' => $event->subject_type === 'sub_subject' ? 'Sub-Subject' : ($event->subject_type === 'duty' ? 'Duty' : 'Main Subject'),
            'main_subject_name' => $event->main_subject_name,
            'sub_subject_name' => $event->sub_subject_name,
            'department_name' => $event->department_name,
            'department_id' => $event->department_id ?? null,
            'course_type' => $event->course_type,
            'branch_name' => $event->branch_name,
            'section_name' => $sectionName ?? $event->section_name,
            'semester_id' => $event->semester_id,
            'frequency' => $event->frequency,
            'frequency_label' => $this->getFrequencyLabel($event->frequency),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'valid_from' => $event->valid_from,
            'valid_to' => $event->valid_to,
            'days_of_week' => $daysOfWeek,
            'day_of_month' => $event->day_of_month,
            'location' => $event->location,
            'venue' => $event->venue ?? null,
            'duration' => $event->duration,
            'status' => $event->status,
            'remarks' => $event->remarks,
            'is_reassigned' => isset($event->is_reassigned) ? ($event->is_reassigned == 1) : false,
            'is_reassignment_lecture' => isset($event->is_reassigned) && $event->is_reassigned == 1,
            'reassignment_info' => $reassignmentInfo,
            'lecture_mode' => $event->lecture_mode ?? 'offline',
            'has_override' => $event->has_override ?? false,
            'override_id' => $event->override_id ?? null,
            'override_reason' => $event->override_reason ?? null,
            'meeting_link' => $event->meeting_link ?? null,
            'meeting_password' => $event->meeting_password ?? null,
            'meeting_id' => $event->meeting_id ?? null,
            'meeting_instructions' => $event->meeting_instructions ?? null,
            'priority' => $event->priority ?? null,
            'duty_type' => $event->duty_type ?? null,
            'employee_duty_id' => $event->employee_duty_id ?? null,
            'instructions' => $event->instructions ?? null,
            'employee_name' => $event->employee_name ?? null,
            'designation' => $event->designation ?? null,
            'color_class' => $event->color_class ?? ($event->event_type === 'duty' ? 'duty-medium' : 'subject-default')
        ]);
    }
    
    $processedEvents = $processedEvents->unique(function($item) {
        return $item->id . '_' . ($item->valid_from ?? '');
    })->values();
    
    $processedEvents = $processedEvents->sortBy([
        ['valid_from', 'asc'],
        ['start_time', 'asc']
    ])->values();
    
    // Get all departments for filter
    $departments = Departments::where('institute_id', $context['institute_id'])
        ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            return $query->where('branch_id', $context['branch_id']);
        })
        ->get();
    
    // Get all employees for filter
    $employeelist = EmployeeDetails::where('institute_id', $context['institute_id'])
        ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            return $query->where('branch_id', $context['branch_id']);
        })
        ->get();
    
    // Get all courses for filter
    $courses = ProductDetails::where('institute_id', $context['institute_id'])
        ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            return $query->where('branch_id', $context['branch_id']);
        })
        ->get();
    
    // Get all subjects for filter
    $subjects = SubjectsCoursewise::where('institute_id', $context['institute_id'])
        ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
            return $query->where('branch_id', $context['branch_id']);
        })
        ->get();
    
    return view('instituteAdmin.EmployeeFiles.EmployeeScheduleCalendar', [
        'assignments' => $processedEvents,
        'employee' => $employee,
        'departments' => $departments,
        'employeelist' => $employeelist,
        'courses' => $courses,
        'subjects' => $subjects
    ]);
}
    
    /**
     * Get lectures with overrides applied for each occurrence date
     */
    private function getLecturesWithOverridesForEmployee($lecture, $institute_id, $branch_id)
    {
        $result = [];
        
        $start = Carbon::parse($lecture->valid_from);
        $end = Carbon::parse($lecture->valid_to);
        
        // Limit to next 90 days for performance
        $maxEnd = Carbon::today()->addDays(90);
        if ($end->gt($maxEnd)) {
            $end = $maxEnd;
        }
        
        // For one_time lectures, only generate a single occurrence
        if ($lecture->frequency == 'one_time' || $lecture->frequency == 'once') {
            // Check for override on this specific date
            $override = LectureModeOverride::where('lecture_id', $lecture->id)
                ->where('override_date', $start->format('Y-m-d'))
                ->where('institute_id', $institute_id)
                ->where('is_active', true)
                ->first();
            
            $lectureCopy = clone $lecture;
            $lectureCopy->has_override = false;
            $lectureCopy->lecture_mode = $lecture->lecture_mode ?? 'offline';
            $lectureCopy->original_frequency = $lecture->frequency; // Store original frequency
            
            if ($override) {
                $lectureCopy->lecture_mode = $override->lecture_mode;
                $lectureCopy->meeting_link = $override->meeting_link;
                $lectureCopy->meeting_password = $override->meeting_password;
                $lectureCopy->meeting_id = $override->meeting_id;
                $lectureCopy->meeting_instructions = $override->meeting_instructions;
                $lectureCopy->has_override = true;
                $lectureCopy->override_id = $override->id;
                $lectureCopy->override_reason = $override->remarks;
                
                if ($override->location_override) {
                    $lectureCopy->location = $override->location_override;
                }
                if ($override->room_override) {
                    $lectureCopy->room = $override->room_override;
                }
            }
            
            $result[] = $lectureCopy;
        }
        // For weekly/daily/monthly lectures, keep the original date range
        else {
            $current = clone $start;
            
            while ($current <= $end) {
                $dayOfWeek = $current->dayOfWeek;
                
                // Skip Sundays if needed
                if ($dayOfWeek == 0) {
                    $current->addDay();
                    continue;
                }
                
                $includeDate = false;
                
                switch ($lecture->frequency) {
                    case 'daily':
                        $includeDate = true;
                        break;
                    case 'weekly':
                        // Check if this day matches the days_of_week
                        if (!empty($lecture->days_of_week) && is_array($lecture->days_of_week)) {
                            $dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                            $currentDayName = $dayNames[$current->dayOfWeek];
                            if (in_array($currentDayName, $lecture->days_of_week)) {
                                $includeDate = true;
                            }
                        } else {
                            $includeDate = true;
                        }
                        break;
                    case 'monthly':
                        if ($lecture->day_of_month && $current->day == $lecture->day_of_month) {
                            $includeDate = true;
                        }
                        break;
                    default:
                        $includeDate = true;
                        break;
                }
                
                if ($includeDate) {
                    // Check for override on this specific date
                    $override = LectureModeOverride::where('lecture_id', $lecture->id)
                        ->where('override_date', $current->format('Y-m-d'))
                        ->where('institute_id', $institute_id)
                        ->where('is_active', true)
                        ->first();
                    
                    $lectureCopy = clone $lecture;
                    $lectureCopy->valid_from = $current->format('Y-m-d');
                    $lectureCopy->valid_to = $current->format('Y-m-d');
                    $lectureCopy->has_override = false;
                    $lectureCopy->lecture_mode = $lecture->lecture_mode ?? 'offline';
                    $lectureCopy->original_frequency = $lecture->frequency; // Store original frequency
                    
                    if ($override) {
                        $lectureCopy->lecture_mode = $override->lecture_mode;
                        $lectureCopy->meeting_link = $override->meeting_link;
                        $lectureCopy->meeting_password = $override->meeting_password;
                        $lectureCopy->meeting_id = $override->meeting_id;
                        $lectureCopy->meeting_instructions = $override->meeting_instructions;
                        $lectureCopy->has_override = true;
                        $lectureCopy->override_id = $override->id;
                        $lectureCopy->override_reason = $override->remarks;
                        
                        if ($override->location_override) {
                            $lectureCopy->location = $override->location_override;
                        }
                        if ($override->room_override) {
                            $lectureCopy->room = $override->room_override;
                        }
                    }
                    
                    $result[] = $lectureCopy;
                }
                
                $current->addDay();
            }
        }
        
        return collect($result);
    }
        
    /**
     * Get human-readable frequency label
     */
    private function getFrequencyLabel($frequency)
    {
        $labels = [
            'one_time' => 'One Time',
            'once' => 'One Time',
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly'
        ];
        
        return $labels[$frequency] ?? ucfirst($frequency);
    }
    
    /**
     * Get color based on event type and frequency
     */
    public function getEventColor($event)
    {
        if ($event->event_type === 'duty') {
            $priorityColors = [
                'low' => '#10b981',
                'medium' => '#3b82f6',
                'high' => '#f59e0b',
                'urgent' => '#ef4444'
            ];
            return $priorityColors[$event->priority] ?? '#6b7280';
        }
        
        // For lectures, if has override, use a special color
        if (isset($event->has_override) && $event->has_override) {
            return '#f59e0b'; // Orange for overridden lectures
        }
        
        $colors = [
            'one_time' => '#f59e0b',
            'daily' => '#10b981',
            'weekly' => '#3b82f6',
            'monthly' => '#8b5cf6'
        ];
        
        return $colors[$event->frequency] ?? '#6b7280';
    }
    
    /**
     * Get details for a specific event (lecture or duty)
     */
    public function getEventDetails($eventId, $eventType)
    {
        $context = $this->getInstituteBranchContext();
        $user = Auth::user();
        $employee = EmployeeDetails::where('user_id', $user->id)->first();
        
        if ($eventType === 'duty') {
            $duty = AssignDuties::with(['employee', 'dutyType'])
                ->where('id', $eventId)
                ->first();
            
            if ($duty) {
                $departmentName = null;
                if ($duty->department_id) {
                    $department = DB::table('departments')->where('department_id', $duty->department_id)->first();
                    $departmentName = $department->department ?? null;
                }
                
                return response()->json([
                    'success' => true,
                    'event' => [
                        'type' => 'duty',
                        'title' => $duty->title ?? $duty->duty_type,
                        'duty_type' => $duty->duty_type,
                        'employee_name' => $duty->employee->name ?? null,
                        'designation' => $duty->employee->designation ?? null,
                        'department_name' => $departmentName,
                        'priority' => $duty->priority,
                        'status' => $duty->status,
                        'frequency' => $duty->frequency,
                        'start_time' => $duty->start_time,
                        'end_time' => $duty->end_time,
                        'date' => $duty->date,
                        'from_date' => $duty->from_date,
                        'to_date' => $duty->to_date,
                        'location' => $duty->location,
                        'venue' => $duty->venue,
                        'description' => $duty->description,
                        'instructions' => $duty->instructions,
                        'required_materials' => $duty->required_materials,
                        'employee_duty_id' => $duty->employee_duty_id
                    ]
                ]);
            }
        } else {
            // Lecture details - also check for override
            $lecture = DB::table('employee_subject_lectures as esl')
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
                    'ase.subject_display_name',
                    'ase.subject_type',
                    'ase.semester_id',
                    'ase.section_id',
                    'ase.course_detail_id',
                    'ase.branch_id',
                    's.subject_name as main_subject_name',
                    'ss.sub_subject_name',
                    'd.department as department_name',
                    'pd.course_type',
                    'pd.sub_type as branch_name',
                )
                ->where('esl.id', $eventId)
                ->first();
            
            if ($lecture) {
                // Check for override on the specific date (if date parameter is provided)
                $dateParam = request()->get('date');
                if ($dateParam) {
                    $override = LectureModeOverride::where('lecture_id', $lecture->id)
                        ->where('override_date', $dateParam)
                        ->where('institute_id', $context['institute_id'])
                        ->where('is_active', true)
                        ->first();
                    
                    if ($override) {
                        $lecture->lecture_mode = $override->lecture_mode;
                        $lecture->meeting_link = $override->meeting_link;
                        $lecture->meeting_password = $override->meeting_password;
                        $lecture->meeting_id = $override->meeting_id;
                        $lecture->meeting_instructions = $override->meeting_instructions;
                        $lecture->has_override = true;
                        $lecture->override_reason = $override->remarks;
                    } else {
                        $lecture->has_override = false;
                    }
                } else {
                    $lecture->has_override = false;
                }
                
                $lecture->section_name = $this->getSectionDisplayName(
                    $lecture->section_id,
                    $lecture->course_detail_id,
                    $context['institute_id'],
                    $lecture->branch_id ?? $context['branch_id'] ?? null
                );
                $lecture->type = 'lecture';
            }
            
            return response()->json([
                'success' => true,
                'event' => $lecture
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Event not found'
        ]);
    }
}
