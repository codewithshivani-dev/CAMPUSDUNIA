<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDetails;
use App\Models\HolidayEvent;
use App\Models\AcademicEvent;
use App\Models\ExamStructureOfflineExam;
use App\Models\Departments;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EmployeeCalendarController extends Controller
{
    /**
     * Display the employee calendar view
     */
    public function index()
    {
        $user = Auth::user();
        $employee = EmployeeDetails::with('department')->where('user_id', $user->id)->firstOrFail();
        
        $currentYear = Carbon::now()->year;
        
        // Get all departments for filter
        $departments = Departments::where('institute_id', $employee->institute_id)
            ->when($employee->branch_id, function($query) use ($employee) {
                return $query->where('branch_id', $employee->branch_id);
            })
            ->get();
            
        $employee = \DB::table('employee_details as e')
            ->leftJoin('departments as d', 'e.department_id', '=', 'd.department_id')
            ->where('e.id', $employee->id)
            ->select(
                'e.*',
                'd.department as department_name'
            )
            ->first();
            
        // Get all designations for filter
        $designations = EmployeeDetails::where('institute_id', $employee->institute_id)
            ->where('status', 'active')
            ->distinct()
            ->pluck('designation')
            ->filter();
        
        return view('instituteAdmin.EmployeeFiles.calendar.index', compact('employee', 'currentYear', 'departments', 'designations'));
    }
    
    /**
     * API endpoint to get calendar events for employee
     */
    public function getCalendarEvents(Request $request)
    {
        $user = Auth::user();
        $employee = EmployeeDetails::with('department')->where('user_id', $user->id)->firstOrFail();
        
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        
        $institute_id = $employee->institute_id;
        $branch_id = $employee->branch_id;
        $employee_department_id = $employee->department_id;
        
        // Get filter parameters
        $filterCategory = $request->get('category', '');
        $filterDepartment = $request->get('department_id', '');
        $filterDesignation = $request->get('designation', '');
        $filterYear = $request->get('year', Carbon::now()->year);
        
        // Get all events relevant to this employee with filters AND target audience
        $holidayEvents = $this->getHolidayEventsForEmployee($institute_id, $branch_id, $employee_department_id, $filterCategory, $filterYear);
        $academicEvents = $this->getAcademicEventsForEmployee($institute_id, $branch_id, $filterCategory, $filterYear);
        $examEvents = $this->getExamEventsForEmployee($institute_id, $branch_id, $employee_department_id, $filterCategory, $filterYear);
        $staffMeetings = $this->getStaffMeetingsForEmployee($institute_id, $branch_id, $employee_department_id, $filterCategory, $filterYear);
        $otherEvents = $this->getOtherEventsForEmployee($institute_id, $branch_id, $employee_department_id, $filterCategory, $filterYear);
        $birthdayEvents = $this->getBirthdayEventsForEmployee($institute_id, $branch_id, $filterDesignation, $filterYear);
        
        // Merge all events
        $events = array_merge(
            $holidayEvents,
            $academicEvents,
            $examEvents,
            $staffMeetings,
            $otherEvents,
            $birthdayEvents
        );
        
        // Filter by calendar view range
        if ($request->has('start') && $request->has('end')) {
            $calendarStart = Carbon::parse($request->start);
            $calendarEnd = Carbon::parse($request->end);
            
            $events = array_filter($events, function($event) use ($calendarStart, $calendarEnd) {
                $eventStart = Carbon::parse($event['start']);
                $eventEnd = isset($event['end']) ? Carbon::parse($event['end']) : $eventStart;
                return $eventStart <= $calendarEnd && $eventEnd >= $calendarStart;
            });
        }
        
        return response()->json([
            'success' => true,
            'events' => array_values($events),
            'total' => count($events)
        ]);
    }
    
    /**
     * Get holiday events with filters and target audience check
     * Employees should see: events for 'employees' or 'both'
     */
    private function getHolidayEventsForEmployee($institute_id, $branch_id, $employee_department_id, $filterCategory, $filterYear)
    {
        $query = HolidayEvent::with(['departmentCategory', 'department'])
            ->where('institute_id', $institute_id)
            ->where('type', 'holiday')
            ->where('status', 'active')
            ->whereIn('target_audience', ['employees', 'both']) // Only show events meant for employees
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->where(function($q) use ($employee_department_id) {
                $q->where('scope', 'overall')
                  ->orWhere('department_id', $employee_department_id);
            });
        
        // Apply year filter
        if ($filterYear) {
            $query->whereYear('start_date', $filterYear);
        }
        
        // Apply category filter (if 'holiday' is selected)
        if ($filterCategory && $filterCategory !== 'holiday') {
            return []; // Return empty if filtering for other categories
        }
        
        $holidays = $query->get();
        
        return $holidays->map(function($event) {
            $startDate = $this->extractDate($event->start_date);
            $endDate = $startDate ? Carbon::parse($startDate)->addDay()->format('Y-m-d') : null;
            $emoji = $this->getHolidayEmoji($event->title);
            
            // Get department name properly
            $departmentName = null;
            if ($event->department) {
                $departmentName = $event->department->department;
            } elseif ($event->department_id) {
                $dept = Departments::find($event->department_id);
                $departmentName = $dept->department ?? null;
            }
            
            // Add audience icon
            $audienceIcon = $this->getAudienceIcon($event->target_audience);
            
            return [
                "id" => "holiday_" . $event->holiday_event_id,
                "title" => $emoji . ' ' . $audienceIcon . ' ' . $event->title,
                "start" => $startDate,
                "end" => $endDate,
                "color" => $event->color ?? "#0d0666",
                "allDay" => true,
                "extendedProps" => [
                    "category" => "holiday",
                    "sub_category" => "public_holiday",
                    "title" => $event->title,
                    "description" => $event->description,
                    "start_date" => $startDate,
                    "end_date" => $event->end_date,
                    "is_holiday" => true,
                    "scope" => $event->scope,
                    "target_audience" => $event->target_audience,
                    "department_id" => $event->department_id,
                    "department_name" => $departmentName
                ]
            ];
        })->toArray();
    }
    
    /**
     * Get academic events with target audience check
     */
    private function getAcademicEventsForEmployee($institute_id, $branch_id, $filterCategory, $filterYear)
    {
        // If filtering for a specific category other than 'academic', return empty
        if ($filterCategory && $filterCategory !== 'academic' && $filterCategory !== '') {
            return [];
        }
        
        $query = AcademicEvent::where('institute_id', $institute_id)
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            });
        
        // Note: AcademicEvent model might not have target_audience field yet
        // If it doesn't, we assume all academic events are for both students and employees
        if (Schema::hasColumn('academic_events', 'target_audience')) {
            $query->whereIn('target_audience', ['employees', 'both']);
        }
        
        // Apply year filter
        if ($filterYear) {
            $query->whereYear('event_date', $filterYear);
        }
        
        $events = $query->get();
        
        $eventColors = [
            'exam' => '#ef4444',
            'result' => '#10b981',
            'vacation' => '#f59e0b',
            'admission' => '#3b82f6',
            'workshop' => '#8b5cf6',
            'seminar' => '#ec4898',
            'sports' => '#14b8a6',
            'cultural' => '#f97316',
            'meeting' => '#ffc107'
        ];
        
        return $events->map(function($event) use ($eventColors) {
            $color = $event->color ?? ($eventColors[$event->event_type] ?? '#6c757d');
            $emoji = $this->getAcademicEventEmoji($event->event_type);
            
            $startDate = $this->extractDate($event->event_date);
            $endDate = $event->end_date ? $this->extractDate($event->end_date) : $startDate;
            
            return [
                "id" => "academic_" . $event->academic_event_id,
                "title" => $emoji . ' ' . $event->title,
                "start" => $startDate,
                "end" => $endDate,
                "color" => $color,
                "allDay" => true,
                "extendedProps" => [
                    "category" => "academic",
                    "sub_category" => $event->event_type,
                    "title" => $event->title,
                    "description" => $event->description,
                    "event_type" => $event->event_type,
                    "event_date" => $startDate,
                    "end_date" => $event->end_date,
                    "academic_event_id" => $event->academic_event_id,
                    "target_audience" => $event->target_audience ?? 'both'
                ]
            ];
        })->toArray();
    }
    
    /**
     * Get exam events (exams are always for students, not employees)
     * Employees typically don't see exams unless they are invigilators
     */
    private function getExamEventsForEmployee($institute_id, $branch_id, $employee_department_id, $filterCategory, $filterYear)
    {
        // By default, employees don't see exams on their calendar
        // If you want employees to see exams they are invigilating, modify this method
        
        if ($filterCategory && $filterCategory !== 'exam') {
            return [];
        }
        
        // Check if employee is an invigilator for any exams
        // You can add a relationship or setting to determine if employee should see exams
        $showExamsToEmployees = false; // Set to true if employees should see exams
        
        if (!$showExamsToEmployees) {
            return [];
        }
        
        $query = ExamStructureOfflineExam::where('institute_id', $institute_id)
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            });
        
        // Apply year filter
        if ($filterYear) {
            $query->whereYear('exam_date', $filterYear);
        }
        
        // Filter by department if column exists
        if (Schema::hasColumn('exam_structure_offline_exams', 'department_id')) {
            $query->where(function($q) use ($employee_department_id) {
                $q->whereNull('department_id')
                  ->orWhere('department_id', $employee_department_id);
            });
        }
        
        $exams = $query->get();
        
        return $exams->map(function($exam) {
            $examDate = $this->extractDate($exam->exam_date);
            $startTime = $this->extractTime($exam->start_time);
            $endTime = $this->extractTime($exam->end_time);
            
            $startDateTime = $examDate && $startTime ? $examDate . 'T' . $startTime : null;
            $endDateTime = $examDate && $endTime ? $examDate . 'T' . $endTime : null;
            
            if (!$startDateTime || !$endDateTime) {
                return null;
            }
            
            return [
                "id" => "exam_" . $exam->id,
                "title" => "📝 " . $exam->exam_name . ($exam->subject ? " - " . $exam->subject->subject_name : ''),
                "start" => $startDateTime,
                "end" => $endDateTime,
                "color" => "#ef4444",
                "allDay" => false,
                "extendedProps" => [
                    "category" => "exam",
                    "exam_name" => $exam->exam_name,
                    "subject_name" => $exam->subject->subject_name ?? 'N/A',
                    "course_name" => $exam->course->course_type ?? 'N/A',
                    "classroom" => $exam->classroom->room_name ?? 'N/A',
                    "total_marks" => $exam->total_marks,
                    "passing_marks" => $exam->passing_marks,
                    "duration_minutes" => $exam->duration_minutes,
                    "exam_date" => $examDate,
                    "start_time" => $startTime,
                    "end_time" => $endTime,
                    "target_audience" => "students"
                ]
            ];
        })->filter()->values()->toArray();
    }
    
    /**
     * Get staff meetings with target audience check
     * Employees should see: meetings for 'employees' or 'both'
     */
    private function getStaffMeetingsForEmployee($institute_id, $branch_id, $employee_department_id, $filterCategory, $filterYear)
    {
        if ($filterCategory && $filterCategory !== 'meeting') {
            return [];
        }
        
        $query = HolidayEvent::with(['departmentCategory', 'department'])
            ->where('type', 'meeting')
            ->where('status', 'active')
            ->where('institute_id', $institute_id)
            ->whereIn('target_audience', ['employees', 'both']) // Only show meetings meant for employees
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->where(function($q) use ($employee_department_id) {
                $q->where('scope', 'overall')
                  ->orWhere('department_id', $employee_department_id);
            });
        
        // Apply year filter
        if ($filterYear) {
            $query->whereYear('start_date', $filterYear);
        }
        
        $meetings = $query->orderBy('start_date', 'asc')->get();
        
        return $meetings->map(function($event) {
            $startDate = $this->extractDate($event->start_date);
            $endDate = $event->end_date ? $this->extractDate($event->end_date) : $startDate;
            $startTime = $this->extractTime($event->start_time ?? '10:00:00');
            $endTime = $this->extractTime($event->end_time ?? '12:00:00');
            
            $startDateTime = $startDate && $startTime ? $startDate . 'T' . $startTime : null;
            $endDateTime = $endDate && $endTime ? $endDate . 'T' . $endTime : null;
            
            if (!$startDateTime) {
                return null;
            }
            
            // Get department name properly
            $departmentName = null;
            if ($event->department) {
                $departmentName = $event->department->department;
            } elseif ($event->department_id) {
                $dept = Departments::find($event->department_id);
                $departmentName = $dept->department ?? null;
            }
            
            $title = $event->title;
            if ($event->scope === 'department_wise' && $departmentName) {
                $title .= " (" . $departmentName . " Dept)";
            }
            
            $audienceIcon = $this->getAudienceIcon($event->target_audience);
            
            return [
                "id" => "meeting_" . $event->holiday_event_id,
                "title" => "📅 " . $audienceIcon . " " . $title,
                "start" => $startDateTime,
                "end" => $endDateTime ?: $startDateTime,
                "color" => $event->color ?? "#ffc107",
                "allDay" => false,
                "extendedProps" => [
                    "category" => "meeting",
                    "title" => $event->title,
                    "description" => $event->description,
                    "start_date" => $startDate,
                    "end_date" => $endDate,
                    "start_time" => $startTime,
                    "end_time" => $endTime,
                    "scope" => $event->scope,
                    "target_audience" => $event->target_audience,
                    "department_id" => $event->department_id,
                    "department_name" => $departmentName,
                    "venue" => $event->venue ?? null,
                    "agenda" => $event->agenda ?? null
                ]
            ];
        })->filter()->values()->toArray();
    }
    
    /**
     * Get other events with target audience check
     * Employees should see: events for 'employees' or 'both'
     */
    private function getOtherEventsForEmployee($institute_id, $branch_id, $employee_department_id, $filterCategory, $filterYear)
    {
        if ($filterCategory && $filterCategory !== 'other' && $filterCategory !== 'event') {
            return [];
        }
        
        $query = HolidayEvent::with(['departmentCategory', 'department'])
            ->whereIn('type', ['event', 'other'])
            ->where('status', 'active')
            ->where('institute_id', $institute_id)
            ->whereIn('target_audience', ['employees', 'both']) // Only show events meant for employees
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->where(function($q) use ($employee_department_id) {
                $q->where('scope', 'overall')
                  ->orWhere('department_id', $employee_department_id);
            });
        
        // Apply year filter
        if ($filterYear) {
            $query->whereYear('start_date', $filterYear);
        }
        
        $otherEvents = $query->get();
        
        return $otherEvents->map(function($event) {
            $startDate = $this->extractDate($event->start_date);
            $endDate = $event->end_date ? $this->extractDate($event->end_date) : $startDate;
            $startTime = $this->extractTime($event->start_time ?? null);
            $endTime = $this->extractTime($event->end_time ?? null);
            $startDateTime = $startDate && $startTime ? $startDate . 'T' . $startTime : null;
            $endDateTime = $endDate && $endTime ? $endDate . 'T' . $endTime : null;
            $isTimed = $startDateTime && $endDateTime;
            $emoji = $event->type == 'event' ? '📌' : '📋';
            
            // Get department name properly
            $departmentName = null;
            if ($event->department) {
                $departmentName = $event->department->department;
            } elseif ($event->department_id) {
                $dept = Departments::find($event->department_id);
                $departmentName = $dept->department ?? null;
            }
            
            $title = $event->title;
            if ($event->scope === 'department_wise' && $departmentName) {
                $title .= " (" . $departmentName . " Dept)";
            }
            
            $audienceIcon = $this->getAudienceIcon($event->target_audience);
            
            return [
                "id" => "event_" . $event->holiday_event_id,
                "title" => $emoji . ' ' . $audienceIcon . ' ' . $title,
                "start" => $isTimed ? $startDateTime : $startDate,
                "end" => ($isTimed && $endDateTime) ? $endDateTime : ($endDate ?: $startDate),
                "color" => $event->color ?? ($event->type == 'event' ? '#28a745' : '#6c757d'),
                "allDay" => !$isTimed,
                "extendedProps" => [
                    "category" => "other",
                    "type" => $event->type,
                    "title" => $event->title,
                    "description" => $event->description,
                    "start_date" => $startDate,
                    "end_date" => $event->end_date,
                    "start_time" => $startTime,
                    "end_time" => $endTime,
                    "scope" => $event->scope,
                    "target_audience" => $event->target_audience,
                    "department_id" => $event->department_id,
                    "department_name" => $departmentName
                ]
            ];
        })->toArray();
    }
    
    /**
     * Get birthday events (birthdays are for employees)
     */
    private function getBirthdayEventsForEmployee($institute_id, $branch_id, $filterDesignation, $filterYear)
    {
        $query = EmployeeDetails::where('institute_id', $institute_id)
            ->where('status', 'active')
            ->whereNotNull('dob')
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            });
        
        // Apply designation filter
        if ($filterDesignation) {
            $query->where('designation', $filterDesignation);
        }
        
        $employees = $query->get();
        $birthdays = [];
        $currentYear = $filterYear ?? Carbon::now()->year;
        
        foreach ($employees as $emp) {
            $dob = Carbon::parse($emp->dob);
            $birthdayThisYear = Carbon::create($currentYear, $dob->month, $dob->day);
            
            $age = $currentYear - $dob->year;
            $displayTitle = '🎂 ' . $emp->name . "'s Birthday" . ($age > 0 ? " ({$age})" : "");
            
            $birthdays[] = [
                "id" => "birthday_" . $emp->employee_id . "_" . $currentYear,
                "title" => $displayTitle,
                "start" => $birthdayThisYear->format('Y-m-d'),
                "end" => $birthdayThisYear->format('Y-m-d'),
                "color" => "#ec4898",
                "allDay" => true,
                "extendedProps" => [
                    "category" => "birthday",
                    "employee_name" => $emp->name,
                    "designation" => $emp->designation,
                    "age" => $age,
                    "employee_id" => $emp->employee_id,
                    "target_audience" => "employees"
                ]
            ];
        }
        
        return $birthdays;
    }
    
    /**
     * Helper: Get audience icon
     */
    private function getAudienceIcon($targetAudience)
    {
        $icons = [
            'students' => '🎓',
            'employees' => '💼',
        ];
        return $icons[$targetAudience] ?? '';
    }
    
    /**
     * Helper: Extract date from datetime
     */
    private function extractDate($dateTimeValue)
    {
        if (empty($dateTimeValue)) return null;
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTimeValue)) return $dateTimeValue;
        try {
            return Carbon::parse($dateTimeValue)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Helper: Extract time from datetime
     */
    private function extractTime($timeValue)
    {
        if (empty($timeValue)) return null;
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $timeValue) || preg_match('/^\d{2}:\d{2}$/', $timeValue)) {
            return $timeValue;
        }
        try {
            return Carbon::parse($timeValue)->format('H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Get holiday emoji based on title
     */
    private function getHolidayEmoji($title)
    {
        $title = strtolower($title);
        $emojiMap = [
            'republic' => '🇮🇳', 'independence' => '🇮🇳', 'gandhi' => '🕊️',
            'diwali' => '🪔', 'holi' => '🎨', 'eid' => '🕌', 'christmas' => '🎄',
            'new year' => '🎊', 'good friday' => '✝️', 'shivratri' => '🔱',
            'labour' => '👷', 'teachers' => '👨‍🏫', 'children' => '🧒', 'women' => '👩',
            'ambedkar' => '📚'
        ];
        foreach ($emojiMap as $keyword => $emoji) {
            if (strpos($title, $keyword) !== false) return $emoji;
        }
        return '📅';
    }
    
    /**
     * Get academic event emoji
     */
    private function getAcademicEventEmoji($eventType)
    {
        $emojis = [
            'exam' => '📝', 'result' => '📊', 'vacation' => '🏖️',
            'admission' => '📋', 'workshop' => '🔧', 'seminar' => '🎓',
            'sports' => '⚽', 'cultural' => '🎭', 'meeting' => '👥'
        ];
        return $emojis[$eventType] ?? '📌';
    }
}