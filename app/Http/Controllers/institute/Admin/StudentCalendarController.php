<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentParentDetails;
use App\Models\StudentAcademicTransportDetails;
use App\Models\HolidayEvent;
use App\Models\AcademicEvent;
use App\Models\ExamStructureOfflineExam;
use App\Models\Departments;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StudentCalendarController extends Controller
{
    /**
     * Display the student calendar view
     */
    public function index()
    {
        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->firstOrFail();
       
        $currentYear = Carbon::now()->year;
        
        // Get student's academic details
        $academicDetails = StudentAcademicTransportDetails::where('student_hash_id', $student->student_hash_id)->first();
        
        // Get department name
        $departmentName = null;
        if ($academicDetails && $academicDetails->department_id) {
            $department = Departments::where('department_id', $academicDetails->department_id)->first();
            $departmentName = $department->department ?? null;
        }
        
        // Get student's course and section info
        $courseName = null;
        $sectionName = null;
        if ($academicDetails && $academicDetails->course_subtype_id) {
            $courseName = $academicDetails->course_subtype;
            
            // Get section name from course fee structure
            if ($academicDetails->section_id) {
                $sectionName = $this->getSectionName($academicDetails->course_subtype_id, $academicDetails->section_id, $student->institute_id);
            }
        }
        
        return view('instituteAdmin.StudentFiles.StudentCalendar', compact(
            'student', 
            'currentYear', 
            'academicDetails',
            'departmentName',
            'courseName',
            'sectionName'
        ));
    }
    
    /**
     * API endpoint to get calendar events for student
     */
    public function getCalendarEvents(Request $request)
    {
        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->firstOrFail();
        
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        
        $institute_id = $student->institute_id;
        $branch_id = $student->branch_id;
        $student_department_id = $student->department_id;
        $student_course_id = null;
        $student_section_id = null;
        
        // Get student's academic details for filtering exams
        $academicDetails = StudentAcademicTransportDetails::where('student_hash_id', $student->student_hash_id)->first();
        if ($academicDetails) {
            $student_course_id = $academicDetails->course_subtype_id;
            $student_section_id = $academicDetails->section_id;
        }
        
        // Get filter parameters
        $filterCategory = $request->get('category', '');
        $filterYear = $request->get('year', Carbon::now()->year);
        
        // Get all events relevant to this student with filters AND target audience
        $holidayEvents = $this->getHolidayEventsForStudent($institute_id, $branch_id, $student_department_id, $filterCategory, $filterYear);
        $academicEvents = $this->getAcademicEventsForStudent($institute_id, $branch_id, $filterCategory, $filterYear);
        $examEvents = $this->getExamEventsForStudent($institute_id, $branch_id, $student_course_id, $student_section_id, $filterCategory, $filterYear);
        $otherEvents = $this->getOtherEventsForStudent($institute_id, $branch_id, $student_department_id, $filterCategory, $filterYear);
        
        // Merge all events
        $events = array_merge(
            $holidayEvents,
            $academicEvents,
            $examEvents,
            $otherEvents
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
     * Get holiday events for students (students see: events for 'students' or 'both')
     */
    private function getHolidayEventsForStudent($institute_id, $branch_id, $student_department_id, $filterCategory, $filterYear)
    {
        $query = HolidayEvent::with(['departmentCategory', 'department'])
            ->where('institute_id', $institute_id)
            ->where('type', 'holiday')
            ->where('status', 'active')
            ->whereIn('target_audience', ['students', 'both']) // Only show events meant for students
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->where(function($q) use ($student_department_id) {
                $q->where('scope', 'overall')
                  ->orWhere('department_id', $student_department_id);
            });
        
        // Apply year filter
        if ($filterYear) {
            $query->whereYear('start_date', $filterYear);
        }
        
        // Apply category filter
        if ($filterCategory && $filterCategory !== 'holiday') {
            return [];
        }
        
        $holidays = $query->get();
        
        return $holidays->map(function($event) {
            $startDate = $this->extractDate($event->start_date);
            $endDate = $startDate ? Carbon::parse($startDate)->addDay()->format('Y-m-d') : null;
            $emoji = $this->getHolidayEmoji($event->title);
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
                    "type" => "holiday",
                    "target_audience" => $event->target_audience,
                    "scope" => $event->scope,
                    "title" => $event->title,
                    "description" => $event->description,
                    "holiday_event_id" => $event->holiday_event_id,
                    "start_date" => $startDate,
                    "end_date" => $event->end_date,
                    "department_name" => $event->department->department ?? null
                ]
            ];
        })->toArray();
    }
    
    /**
     * Get academic events for students
     */
    private function getAcademicEventsForStudent($institute_id, $branch_id, $filterCategory, $filterYear)
    {
        if ($filterCategory && $filterCategory !== 'academic' && $filterCategory !== '') {
            return [];
        }
        
        $query = AcademicEvent::where('institute_id', $institute_id)
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            });
        
        // Filter by target audience if column exists
        if (Schema::hasColumn('academic_events', 'target_audience')) {
            $query->whereIn('target_audience', ['students', 'both']);
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
                    "target_audience" => $event->target_audience ?? 'both'
                ]
            ];
        })->toArray();
    }
    
    /**
     * Get exam events for student (only exams for their course and section)
     */
    private function getExamEventsForStudent($institute_id, $branch_id, $student_course_id, $student_section_id, $filterCategory, $filterYear)
    {
        if ($filterCategory && $filterCategory !== 'exam') {
            return [];
        }
        
        $query = ExamStructureOfflineExam::with(['subject', 'course', 'classroom'])
            ->where('institute_id', $institute_id)
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            });
        
        // Filter by student's course
        if ($student_course_id) {
            $query->where('course_id', $student_course_id);
        }
        
        // Filter by student's section if available
        if ($student_section_id) {
            $query->where('section_id', $student_section_id);
        }
        
        // Apply year filter
        if ($filterYear) {
            $query->whereYear('exam_date', $filterYear);
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
            
            // Get course name
            $courseName = $exam->course->course_type ?? 'N/A';
            
            // Get section name if available
            $sectionInfo = '';
            if ($exam->section_id) {
                $sectionInfo = " - Section " . $exam->section_id;
            }
            
            // Get subject name
            $subjectName = $exam->subject->subject_name ?? 'N/A';
            
            // Build title
            $title = "📝 " . $exam->exam_name;
            if ($subjectName != 'N/A') {
                $title .= " - " . $subjectName;
            }
            
            return [
                "id" => "exam_" . $exam->id,
                "title" => $title,
                "start" => $startDateTime,
                "end" => $endDateTime,
                "color" => "#ef4444",
                "allDay" => false,
                "extendedProps" => [
                    "category" => "exam",
                    "sub_category" => "offline_exam",
                    "target_audience" => "students",
                    "exam_id" => $exam->exam_id,
                    "exam_name" => $exam->exam_name,
                    "subject_name" => $subjectName,
                    "course_name" => $courseName . $sectionInfo,
                    "classroom_name" => $exam->classroom->room_name ?? 'N/A',
                    "total_marks" => $exam->total_marks,
                    "passing_marks" => $exam->passing_marks,
                    "duration_minutes" => $exam->duration_minutes,
                    "exam_date" => $this->extractDate($exam->exam_date),
                    "start_time" => $this->extractTime($exam->start_time),
                    "end_time" => $this->extractTime($exam->end_time),
                    "is_published" => $exam->is_published,
                    "description" => $exam->description ?? "Offline Examination"
                ]
            ];
        })->filter()->values()->toArray();
    }
    
    /**
     * Get other events for students
     */
    private function getOtherEventsForStudent($institute_id, $branch_id, $student_department_id, $filterCategory, $filterYear)
    {
        if ($filterCategory && $filterCategory !== 'other' && $filterCategory !== 'event') {
            return [];
        }
        
        $query = HolidayEvent::with(['departmentCategory', 'department'])
            ->whereIn('type', ['event', 'other'])
            ->where('status', 'active')
            ->where('institute_id', $institute_id)
            ->whereIn('target_audience', ['students', 'both']) // Only show events meant for students
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->where(function($q) use ($student_department_id) {
                $q->where('scope', 'overall')
                  ->orWhere('department_id', $student_department_id);
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
            $audienceIcon = $this->getAudienceIcon($event->target_audience);
            
            // Get department name
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
                    "target_audience" => $event->target_audience,
                    "title" => $event->title,
                    "description" => $event->description,
                    "start_date" => $startDate,
                    "end_date" => $event->end_date,
                    "start_time" => $startTime,
                    "end_time" => $endTime,
                    "scope" => $event->scope,
                    "department_name" => $departmentName
                ]
            ];
        })->toArray();
    }
    
    /**
     * Get section name from course fee structure
     */
    private function getSectionName($productId, $sectionId, $instituteId)
    {
        if (!$productId || !$sectionId) {
            return null;
        }
        
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
     * Helper: Get audience icon
     */
    private function getAudienceIcon($targetAudience)
    {
        $icons = [
            'students' => '🎓',
            'employees' => '💼',
            'both' => '👥'
        ];
        return $icons[$targetAudience] ?? '👥';
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