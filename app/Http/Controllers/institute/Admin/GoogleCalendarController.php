<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDetails;
use App\Models\DepartmentCategory;
use App\Models\HolidayEvent;
use App\Models\AcademicYear;
use App\Models\AcademicEvent;
use App\Models\ExamStructureOfflineExam;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\InstituteBranchAccess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GoogleCalendarController extends Controller
{
    use InstituteBranchAccess;
    
    public function googleCalendarEvents(Request $request)
    {
        // Get institute context
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
        
        $institute_id = $context['institute_id'];
        $branch_id = $context['is_branch_admin'] ? $context['branch_id'] : null;
        
        // Auto-sync holidays when page loads (run in background)
        $this->autoSyncHolidays();
        
        // Get or create current academic year
        $currentYear = Carbon::now()->year;
        $academicYear = AcademicYear::where('institute_id', $institute_id)
            ->where('is_active', 1)
            ->first();
            
        if (!$academicYear) {
            // Create default academic year (April to March)
            $academicYear = AcademicYear::create([
                'academic_year_id' => 'AY-' . date('Y') . '-' . (date('Y') + 1),
                'institute_id' => $institute_id,
                'branch_id' => $branch_id,
                'year_name' => date('Y') . '-' . (date('Y') + 1),
                'start_date' => date('Y') . '-04-01',
                'end_date' => (date('Y') + 1) . '-03-31',
                'is_active' => 1,
                'current_year' => date('Y')
            ]);
        }
        
        // Get all employees for dropdown
        $employeelist = EmployeeDetails::where('institute_id', $institute_id)
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->where('status', 'active')
            ->get();

        // Get department categories with their departments
        $categories = DepartmentCategory::whereHas('departments', function($query) use ($institute_id, $branch_id) {
            $query->where('institute_id', $institute_id)
                ->when($branch_id, function($q) use ($branch_id) {
                    return $q->where('branch_id', $branch_id);
                });
        })->with(['departments' => function($query) use ($institute_id, $branch_id) {
            $query->where('institute_id', $institute_id)
                ->when($branch_id, function($q) use ($branch_id) {
                    return $q->where('branch_id', $branch_id);
                });
        }])->get();

        // Debug: Log the categories to see their IDs
        \Log::info('Categories loaded:', $categories->map(function($cat) {
            return [
                'id' => $cat->department_category_id,
                'name' => $cat->category_name,
                'type' => gettype($cat->department_category_id)
            ];
        })->toArray());

        // Get all holidays
        $holidays = HolidayEvent::where('institute_id', $institute_id)
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->whereYear('start_date', $currentYear)
            ->orderBy('start_date', 'asc')
            ->get();

        // Get academic events
        $academicEvents = AcademicEvent::where('institute_id', $institute_id)
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->whereYear('event_date', $currentYear)
            ->orderBy('event_date', 'asc')
            ->get();

        return view('instituteAdmin.DashboardFiles.GoogleCalendar', compact(
            'employeelist', 
            'categories', 
            'holidays',
            'academicEvents',
            'academicYear',
            'currentYear'
        ));
    }

    /**
     * Auto-sync holidays without user interaction (runs in background)
     */
    private function autoSyncHolidays()
    {
        try {
            $context = $this->getInstituteBranchContext();
            if (!$context['institute_id']) {
                return;
            }
            
            $currentYear = Carbon::now()->year;
            $nextYear = $currentYear + 1;
            
            // Sync for current year and next year (for academic calendar)
            $this->syncHolidaysForYear($currentYear);
            $this->syncHolidaysForYear($nextYear);
            
        } catch (\Exception $e) {
            \Log::error('Auto sync holidays failed: ' . $e->getMessage());
        }
    }
    
    private function syncHolidaysForYear($year)
    {
        $result = $this->syncPublicHolidaysInternal($year);

        \Log::info('Holiday sync completed', [
            'year' => $year,
            'result' => $result
        ]);

        return $result;
    }
    
    /**
     * Internal sync method with tracking
     */
    private function syncPublicHolidaysInternal($year)
    {
        $context = $this->getInstituteBranchContext();
        $result = [
            'total_from_api' => 0,
            'newly_added' => 0,
            'skipped_duplicates' => 0,
            'errors' => 0
        ];
        
        try {
            // Use multiple API sources for better coverage
            $holidays = $this->fetchHolidaysFromMultipleSources($year);
            
            $result['total_from_api'] = count($holidays);
            
            foreach ($holidays as $holiday) {
                $title = $holiday['title'];
                $date = $holiday['date'];
                
                // Extract only the date part
                $cleanDate = $this->extractDate($date);
                
                if (!$cleanDate) {
                    $result['errors']++;
                    continue;
                }
                
                $exists = HolidayEvent::where('institute_id', $context['institute_id'])
                    ->where('title', $title)
                    ->where('start_date', $cleanDate)
                    ->exists();
                
                if (!$exists) {
                    HolidayEvent::create([
                        'holiday_event_id' => 'HLD-' . strtoupper(Str::random(8)),
                        'institute_id' => $context['institute_id'],
                        'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                        'title' => $title,
                        'type' => 'holiday',
                        'start_date' => $cleanDate,
                        'end_date' => $cleanDate,
                        'scope' => 'overall',
                        'description' => $holiday['description'] ?? 'Public Holiday',
                    ]);
                    $result['newly_added']++;
                } else {
                    $result['skipped_duplicates']++;
                }
            }
            
        } catch (\Exception $e) {
            \Log::error('Holiday sync failed for year ' . $year . ': ' . $e->getMessage());
            $result['errors']++;
        }
        
        return $result;
    }
    
    /**
     * Fetch holidays from multiple API sources
     */
    private function fetchHolidaysFromMultipleSources($year)
    {
        $allHolidays = [];
        
        // Source 1: OpenHolidays API
        $holidays1 = $this->fetchFromOpenHolidaysAPI($year);
        if (!empty($holidays1)) {
            $allHolidays = array_merge($allHolidays, $holidays1);
        }
        
        // Source 2: Nager.Date API (free, no key required)
        $holidays2 = $this->fetchFromNagerAPI($year);
        if (!empty($holidays2)) {
            $allHolidays = array_merge($allHolidays, $holidays2);
        }
        
        // Source 3: Static predefined important holidays (fallback)
        $holidays3 = $this->getPredefinedHolidays($year);
        if (!empty($holidays3)) {
            $allHolidays = array_merge($allHolidays, $holidays3);
        }
        
        // Remove duplicates by title+date
        $uniqueHolidays = [];
        $keys = [];
        foreach ($allHolidays as $holiday) {
            $key = $holiday['title'] . '_' . $holiday['date'];
            if (!in_array($key, $keys)) {
                $keys[] = $key;
                $uniqueHolidays[] = $holiday;
            }
        }
        
        return $uniqueHolidays;
    }
    
    /**
     * Fetch from OpenHolidays API
     */
    private function fetchFromOpenHolidaysAPI($year)
    {
        $holidays = [];
        
        try {
            $url = "https://openholidaysapi.org/PublicHolidays?countryIsoCode=IN&languageIsoCode=EN&validFrom={$year}-01-01&validTo={$year}-12-31";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $response = curl_exec($ch);
            
            if (curl_errno($ch)) {
                throw new \Exception(curl_error($ch));
            }
            curl_close($ch);
            
            $data = json_decode($response, true);
            
            if (is_array($data)) {
                foreach ($data as $holiday) {
                    if (isset($holiday['name'][0]['text']) && isset($holiday['startDate'])) {
                        $holidays[] = [
                            'title' => $holiday['name'][0]['text'],
                            'date' => $holiday['startDate'],
                            'description' => $holiday['type'] ?? 'Public Holiday'
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::warning('OpenHolidays API failed: ' . $e->getMessage());
        }
        
        return $holidays;
    }
    
    /**
     * Fetch from Nager.Date API
     */
    private function fetchFromNagerAPI($year)
    {
        $holidays = [];
        
        try {
            $url = "https://date.nager.at/api/v3/PublicHolidays/{$year}/IN";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $response = curl_exec($ch);
            
            if (curl_errno($ch)) {
                throw new \Exception(curl_error($ch));
            }
            curl_close($ch);
            
            $data = json_decode($response, true);
            
            if (is_array($data)) {
                foreach ($data as $holiday) {
                    if (isset($holiday['name']) && isset($holiday['date'])) {
                        $holidays[] = [
                            'title' => $holiday['name'],
                            'date' => $holiday['date'],
                            'description' => $holiday['localName'] ?? 'National Holiday'
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Nager API failed: ' . $e->getMessage());
        }
        
        return $holidays;
    }
    
    /**
     * Get predefined important holidays (fallback)
     */
    private function getPredefinedHolidays($year)
    {
        $holidays = [];
        
        // Important Indian holidays (these are consistent)
        $predefined = [
            ['title' => 'Republic Day', 'date' => $year . '-01-26', 'description' => 'National Holiday'],
            ['title' => 'International Women\'s Day', 'date' => $year . '-03-08', 'description' => 'Women\'s Day Celebration'],
            ['title' => 'Independence Day', 'date' => $year . '-08-15', 'description' => 'National Holiday'],
            ['title' => 'Gandhi Jayanti', 'date' => $year . '-10-02', 'description' => 'National Holiday'],
            ['title' => 'Christmas Day', 'date' => $year . '-12-25', 'description' => 'Christian Holiday'],
            ['title' => 'New Year\'s Day', 'date' => $year . '-01-01', 'description' => 'New Year Celebration'],
            ['title' => 'Labour Day', 'date' => $year . '-05-01', 'description' => 'International Workers\' Day'],
            ['title' => 'Teachers\' Day', 'date' => $year . '-09-05', 'description' => 'Celebrating Teachers'],
            ['title' => 'Children\'s Day', 'date' => $year . '-11-14', 'description' => 'Celebrating Children'],
            ['title' => 'Ambedkar Jayanti', 'date' => $year . '-04-14', 'description' => 'Birth Anniversary of Dr. B.R. Ambedkar'],
        ];
        
        foreach ($predefined as $holiday) {
            if ($holiday['date']) {
                $holidays[] = $holiday;
            }
        }
        
        return $holidays;
    }

    /**
     * Helper function to extract only date part from a datetime field
     */
    private function extractDate($dateTimeValue)
    {
        if (empty($dateTimeValue)) {
            return null;
        }
        
        // If it's already a date string (Y-m-d), return as is
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTimeValue)) {
            return $dateTimeValue;
        }
        
        // Otherwise parse and extract only the date part
        try {
            return Carbon::parse($dateTimeValue)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Helper function to extract only time part from a datetime field
     */
    private function extractTime($timeValue)
    {
        if (empty($timeValue)) {
            return null;
        }
        
        // If it's already a time string (H:i:s), return as is
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $timeValue) || preg_match('/^\d{2}:\d{2}$/', $timeValue)) {
            return $timeValue;
        }
        
        // Otherwise parse and extract only the time part
        try {
            return Carbon::parse($timeValue)->format('H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * API endpoint to get all calendar events
     */
    public function getCalendarEventsApi(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return response()->json(['success' => false, 'message' => 'No institute found'], 401);
        }
        
        $institute_id = $context['institute_id'];
        $branch_id = $context['is_branch_admin'] ? $context['branch_id'] : null;
        
        // Get all events
        $holidayEvents = $this->getHolidayEvents($request, $institute_id, $branch_id);
        $academicEvents = $this->getAcademicEvents($request, $institute_id, $branch_id);
        $birthdayEvents = $this->getBirthdayEvents($request, $institute_id, $branch_id);
        $examEvents = $this->getExamEvents($request, $institute_id, $branch_id);
        $staffMeetings = $this->getStaffMeetings($request, $institute_id, $branch_id);
        $otherEvents = $this->getOtherEvents($request, $institute_id, $branch_id);

        // Merge all events
        $events = array_merge(
            $holidayEvents,
            $academicEvents,
            $birthdayEvents,
            $examEvents,
            $staffMeetings,
            $otherEvents
        );

        // Apply filters
        $events = $this->applyFilters($events, $request);

        // Filter by calendar view range
        if ($request->has('calendar_start') && $request->has('calendar_end')) {
            $calendarStart = Carbon::parse($request->calendar_start);
            $calendarEnd = Carbon::parse($request->calendar_end);
            
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

    private function getHolidayEvents(Request $request, $institute_id, $branch_id = null)
    {
        $query = HolidayEvent::with(['departmentCategory', 'department'])
            ->where('institute_id', $institute_id)
            ->where('type', 'holiday')
            ->where('status', 'active')
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            });
        
        // Apply target audience filter
        if ($request->has('audience') && $request->audience != '') {
            if ($request->audience === 'students') {
                $query->whereIn('target_audience', ['students', 'both']);
            } elseif ($request->audience === 'employees') {
                $query->whereIn('target_audience', ['employees', 'both']);
            }
        }
        
        // Apply department filter
        if ($request->has('department_id') && $request->department_id != '' && $request->department_id !== 'overall') {
            $query->where(function($q) use ($request) {
                $q->where('scope', 'overall')
                ->orWhere('department_id', $request->department_id);
            });
        }
        
        $holidays = $query->get();
        
        return $holidays->map(function($event) {
            $startDate = $this->extractDate($event->start_date);
            $endDate = $startDate ? Carbon::parse($startDate)->addDay()->format('Y-m-d') : null;
            $emoji = $this->getHolidayEmoji($event->title);
            
            // Add audience icon to title
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
                    "target_audience" => $event->target_audience, // Add this
                    "scope" => $event->scope,
                    "title" => $event->title,
                    "description" => $event->description,
                    "holiday_event_id" => $event->holiday_event_id,
                    "start_date" => $startDate,
                    "end_date" => $event->end_date,
                    "status" => $event->status,
                    "is_auto_synced" => $event->is_auto_synced,
                    "department_category_id" => $event->department_category_id,
                    "department_id" => $event->department_id,
                    "department_category_name" => $event->departmentCategory->category_name ?? null,
                    "department_name" => $event->department->department ?? null
                ]
            ];
        })->toArray();
    }

    // Add helper method for audience icon
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
     * Get academic events
     */
    private function getAcademicEvents(Request $request, $institute_id, $branch_id = null)
    {
        $query = AcademicEvent::where('institute_id', $institute_id)
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            });
        
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
            $endDate = $event->end_date ? Carbon::parse($this->extractDate($event->end_date))->addDay()->format('Y-m-d') : $startDate;
            
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
                    "academic_event_id" => $event->academic_event_id,
                    "event_date" => $startDate,
                    "end_date" => $event->end_date
                ]
            ];
        })->toArray();
    }

    /**
     * Get employee birthday events
     */
    private function getBirthdayEvents(Request $request, $institute_id, $branch_id = null)
    {
        $query = EmployeeDetails::where('institute_id', $institute_id)
            ->where('status', 'active')
            ->whereNotNull('dob')
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            });
        
        // Apply department filter
        if ($request->has('department_id') && $request->department_id != '' && $request->department_id !== 'overall') {
            $query->where('department_id', $request->department_id);
        }
        
        // Apply designation filter
        if ($request->has('designation') && $request->designation != '') {
            $query->where('designation', $request->designation);
        }
        
        $employees = $query->get();
        $birthdays = [];
        $currentYear = Carbon::now()->year;
        
        foreach ($employees as $employee) {
            $dob = Carbon::parse($employee->dob);
            $birthdayThisYear = Carbon::create($currentYear, $dob->month, $dob->day);
            
            $age = $currentYear - $dob->year;
            $displayTitle = '🎂 ' . $employee->name . "'s Birthday" . ($age > 0 ? " ({$age} years)" : "");
            
            $birthdays[] = [
                "id" => "birthday_" . $employee->employee_id . "_" . $currentYear,
                "title" => $displayTitle,
                "start" => $birthdayThisYear->format('Y-m-d'),
                "end" => $birthdayThisYear->format('Y-m-d'),
                "color" => "#ec4898",
                "allDay" => true,
                "extendedProps" => [
                    "category" => "birthday",
                    "sub_category" => "employee_birthday",
                    "employee_id" => $employee->employee_id,
                    "employee_name" => $employee->name,
                    "designation" => $employee->designation,
                    "department_id" => $employee->department_id,
                    "age" => $age,
                    "dob" => $dob->format('F j')
                ]
            ];
        }
        
        return $birthdays;
    }

    private function getExamEvents(Request $request, $institute_id, $branch_id = null)
    {
        $query = ExamStructureOfflineExam::with(['subject', 'course'])
            ->where('institute_id', $institute_id)
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            });
        
        // Apply department filter
        if ($request->has('department_id') && $request->department_id != '' && $request->department_id !== 'overall') {
            $query->where('department_id', $request->department_id);
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
            
            // Get class/course name (without section relationship)
            $className = '';
            if ($exam->course) {
                $className = $exam->course->course_type ?? '';
                // Add section_id if available (stored as section_id column, not relationship)
                if ($exam->section_id) {
                    // Try to get section name from CourseFeeStructure if needed
                    $sectionName = $this->getSectionNameFromCourseFee($exam->subtype_id, $exam->section_id);
                    if ($sectionName) {
                        $className .= ' - ' . $sectionName;
                    }
                }
            }
            
            // Get subject name
            $subjectName = $exam->subject->subject_name ?? 'N/A';
            
            // Build a more descriptive title
            $title = "📝 " . $exam->exam_name;
            if ($subjectName != 'N/A') {
                $title .= " - " . $subjectName;
            }
            if ($className) {
                $title .= " (" . $className . ")";
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
                    "subject_id" => $exam->subject_id,
                    "course_name" => $exam->course->course_type ?? 'N/A',
                    "course_id" => $exam->course_id,
                    "section_id" => $exam->section_id, // This is just an ID, not a relationship
                    "classroom_name" => $exam->room_name ?? 'N/A',
                    "classroom_id" => $exam->classroom_id,
                    "total_marks" => $exam->total_marks,
                    "passing_marks" => $exam->passing_marks,
                    "duration_minutes" => $exam->duration_minutes,
                    "exam_date" => $examDate,
                    "start_time" => $startTime,
                    "end_time" => $endTime,
                    "is_published" => $exam->is_published,
                    "description" => $exam->description ?? "Offline Examination"
                ]
            ];
        })->filter()->values()->toArray();
    }

    // Add helper method to get section name from CourseFeeStructure
    private function getSectionNameFromCourseFee($courseId, $sectionId)
    {
       
        if (!$courseId || !$sectionId) {
            return null;
        }
        
        // try {
            // Look for the section in CourseFeeStructure where course_id matches
            $feeStructure = \App\Models\CourseFeeStructure::where('product_id', $courseId)
                ->whereNotNull('sections')
                ->first();
          
            if ($feeStructure && $feeStructure->sections) {
                $sections = json_decode($feeStructure->sections, true);
                if (is_array($sections)) {
                    foreach ($sections as $section) {
                        // Check various possible ID fields
                        if ((isset($section['section_id']) && $section['section_id'] == $sectionId) ||
                            (isset($section['id']) && $section['id'] == $sectionId)) {
                            return $section['name'] ?? $section['section_name'] ?? null;
                        }
                    }
                }
            }
            
            // If not found, return a default section name with the ID
            return "Section " . $sectionId;
        // } catch (\Exception $e) {
        //     \Log::warning('Error getting section name: ' . $e->getMessage());
        //     return "Section " . $sectionId;
        // }
    }

  
    private function getStaffMeetings(Request $request, $institute_id, $branch_id = null)
    {
        $query = HolidayEvent::with(['departmentCategory', 'department'])
            ->where('type', 'meeting')
            ->where('institute_id', $institute_id)
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            });
        
        // Apply target audience filter
        if ($request->has('audience') && $request->audience != '') {
            if ($request->audience === 'students') {
                $query->whereIn('target_audience', ['students', 'both']);
            } elseif ($request->audience === 'employees') {
                $query->whereIn('target_audience', ['employees', 'both']);
            }
        }
        
        // Apply department filter
        if ($request->has('department_id') && $request->department_id != '' && $request->department_id !== 'overall') {
            $query->where(function($q) use ($request) {
                $q->where('scope', 'overall')
                    ->orWhere('department_id', $request->department_id);
            });
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
            
            // Build title with department info
            $title = $event->title;
            if ($event->scope === 'department_wise' && $event->department) {
                $title .= " [" . $event->department->department . "]";
            } elseif ($event->scope === 'department_wise') {
                $title .= " [Department ID: " . ($event->department_id ?? 'Unknown') . "]";
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
                    "sub_category" => "staff_meeting",
                    "type" => "meeting",
                    "target_audience" => $event->target_audience, // Add this
                    "scope" => $event->scope,
                    "title" => $event->title,
                    "description" => $event->description,
                    "holiday_event_id" => $event->holiday_event_id,
                    "start_date" => $startDate,
                    "end_date" => $endDate,
                    "start_time" => $startTime,
                    "end_time" => $endTime,
                    "department_category_id" => $event->department_category_id,
                    "department_id" => $event->department_id,
                    "department_category_name" => $event->departmentCategory->category_name ?? null,
                    "department_name" => $event->department->department ?? null
                ]
            ];
        })->filter()->values()->toArray();
    }

    private function getOtherEvents(Request $request, $institute_id, $branch_id = null)
    {
        $query = HolidayEvent::with(['departmentCategory', 'department'])
            ->whereIn('type', ['event', 'other'])
            ->where('institute_id', $institute_id)
            ->where('status', 'active')
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            });
        
        // Apply department filter
        if ($request->has('department_id') && $request->department_id != '' && $request->department_id !== 'overall') {
            $query->where(function($q) use ($request) {
                $q->where('scope', 'overall')
                    ->orWhere('department_id', $request->department_id);
            });
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
            
            // Build title with department info
            $title = $event->title;
            if ($event->scope === 'department_wise' && $event->department) {
                $title .= " [" . $event->department->department . "]";
            }
            
            return [
                "id" => "event_" . $event->holiday_event_id,
                "title" => $emoji . ' ' . $title,
                "start" => $isTimed ? $startDateTime : $startDate,
                "end" => ($isTimed && $endDateTime) ? $endDateTime : ($endDate ?: $startDate),
                "color" => $event->color ?? ($event->type == 'event' ? '#28a745' : '#6c757d'),
                "allDay" => !$isTimed,
                "extendedProps" => [
                    "category" => "other",
                    "sub_category" => $event->type,
                    "type" => $event->type,
                    "scope" => $event->scope,
                    "title" => $event->title,
                    "description" => $event->description,
                    "holiday_event_id" => $event->holiday_event_id,
                    "start_date" => $startDate,
                    "end_date" => $event->end_date,
                    "start_time" => $startTime,
                    "end_time" => $endTime,
                    "department_category_id" => $event->department_category_id,
                    "department_id" => $event->department_id,
                    "department_category_name" => $event->departmentCategory->category_name ?? null,
                    "department_name" => $event->department->department ?? null
                ]
            ];
        })->toArray();
    }

    /**
     * Get holiday emoji based on title
     */
    private function getHolidayEmoji($title)
    {
        $title = strtolower($title);
        
        $emojiMap = [
            'republic' => '🇮🇳',
            'independence' => '🇮🇳',
            'gandhi' => '🕊️',
            'diwali' => '🪔',
            'holi' => '🎨',
            'eid' => '🕌',
            'christmas' => '🎄',
            'new year' => '🎊',
            'good friday' => '✝️',
            'shivratri' => '🔱',
            'ram navami' => '🕉️',
            'janmashtami' => '🕉️',
            'gurunanak' => '🕊️',
            'buddha' => '🪷',
            'mahavir' => '🪷',
            'labour' => '👷',
            'teachers' => '👨‍🏫',
            'children' => '🧒',
            'women' => '👩',
            'ambedkar' => '📚'
        ];
        
        foreach ($emojiMap as $keyword => $emoji) {
            if (strpos($title, $keyword) !== false) {
                return $emoji;
            }
        }
        
        return '📅';
    }

    /**
     * Get academic event emoji
     */
    private function getAcademicEventEmoji($eventType)
    {
        $emojis = [
            'exam' => '📝',
            'result' => '📊',
            'vacation' => '🏖️',
            'admission' => '📋',
            'workshop' => '🔧',
            'seminar' => '🎓',
            'sports' => '⚽',
            'cultural' => '🎭',
            'meeting' => '👥'
        ];
        
        return $emojis[$eventType] ?? '📌';
    }

    /**
     * Apply filters to events
     */
    private function applyFilters($events, Request $request)
    {
        $filteredEvents = $events;

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $filteredEvents = array_filter($filteredEvents, function($event) use ($request) {
                return isset($event['extendedProps']['category']) && 
                       $event['extendedProps']['category'] == $request->category;
            });
        }

        // Filter by event type
        if ($request->has('type') && $request->type != '') {
            $filteredEvents = array_filter($filteredEvents, function($event) use ($request) {
                return isset($event['extendedProps']['sub_category']) && 
                       $event['extendedProps']['sub_category'] == $request->type;
            });
        }

        return array_values($filteredEvents);
    }

   public function storeHolidayEvent(Request $request)
    {
        try {
            $context = $this->getInstituteBranchContext();
            if (!$context['institute_id']) {
                return response()->json(['success' => false, 'message' => 'No institute found'], 401);
            }
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'type' => 'required|in:holiday,event,meeting,other',
                'target_audience' => 'required|in:students,employees,both', // Add validation
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'color' => 'nullable|string',
                'scope' => 'required|in:overall,department_wise',
                'department_category_id' => 'required_if:scope,department_wise',
                'department_id' => 'required_if:scope,department_wise',
                'description' => 'nullable|string',
                'is_recurring' => 'boolean',
                'recurring_type' => 'nullable|in:yearly,monthly,weekly',
                'start_time' => 'nullable|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i|after:start_time'
            ]);
            
            $holidayEventId = 'HLD-' . strtoupper(Str::random(8));
            
            $holiday = HolidayEvent::create([
                'holiday_event_id' => $holidayEventId,
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                'title' => $request->title,
                'type' => $request->type,
                'target_audience' => $request->target_audience, // Add this
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'start_time' => $request->start_time ?? null,
                'end_time' => $request->end_time ?? null,
                'color' => $request->color ?? ($request->type == 'holiday' ? '#0d0666' : ($request->type == 'meeting' ? '#ffc107' : '#6c757d')),
                'scope' => $request->scope,
                'department_id' => $request->scope == 'department_wise' ? $request->department_id : null,
                'description' => $request->description,
                'is_recurring' => $request->is_recurring ?? false,
                'recurring_type' => $request->is_recurring ? $request->recurring_type : null,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Event created successfully',
                'event' => $holiday
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error creating holiday event: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create event: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manual sync endpoint with detailed tracking
     */
    public function syncPublicHolidays(Request $request)
    {
        try {
            $context = $this->getInstituteBranchContext();
            $year = $request->year ?? Carbon::now()->year;
            
            $result = $this->syncHolidaysForYear($year);
            
            return response()->json([
                'success' => true,
                'message' => "Sync completed for {$year}: Total: {$result['total_from_api']}, New: {$result['newly_added']}, Duplicates: {$result['skipped_duplicates']}, Errors: {$result['errors']}",
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Manual holiday sync failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get holidays list with pagination and filters for management
     */
    public function getHolidaysList(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return response()->json(['success' => false, 'message' => 'No institute found'], 401);
        }
        
        $query = HolidayEvent::where('institute_id', $context['institute_id'])
            ->where('type', 'holiday')
            ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                return $q->where('branch_id', $context['branch_id']);
            });
        
        // Apply filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('year') && $request->year != '') {
            $query->whereYear('start_date', $request->year);
        }
        
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $holidays = $query->orderBy('start_date', 'asc')->paginate(20);
        
        return response()->json([
            'success' => true,
            'holidays' => $holidays
        ]);
    }

    /**
     * Toggle holiday status (activate/deactivate)
     */
    public function toggleHolidayStatus(Request $request, $id)
    {
        try {
            $context = $this->getInstituteBranchContext();
            if (!$context['institute_id']) {
                return response()->json(['success' => false, 'message' => 'No institute found'], 401);
            }
            
            $holiday = HolidayEvent::where('holiday_event_id', $id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if (!$holiday) {
                return response()->json(['success' => false, 'message' => 'Holiday not found'], 404);
            }
            
            $newStatus = $holiday->status === 'active' ? 'inactive' : 'active';
            $holiday->update(['status' => $newStatus]);
            
            return response()->json([
                'success' => true,
                'message' => 'Holiday ' . ($newStatus === 'active' ? 'activated' : 'deactivated') . ' successfully',
                'status' => $newStatus
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error toggling holiday status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update holiday status'
            ], 500);
        }
    }

    /**
     * Bulk update holiday statuses
     */
    public function bulkUpdateHolidayStatus(Request $request)
    {
        try {
            $context = $this->getInstituteBranchContext();
            if (!$context['institute_id']) {
                return response()->json(['success' => false, 'message' => 'No institute found'], 401);
            }
            
            $request->validate([
                'holiday_ids' => 'required|array',
                'status' => 'required|in:active,inactive'
            ]);
            
            $updated = HolidayEvent::whereIn('holiday_event_id', $request->holiday_ids)
                ->where('institute_id', $context['institute_id'])
                ->update(['status' => $request->status]);
            
            return response()->json([
                'success' => true,
                'message' => $updated . ' holidays updated successfully',
                'updated_count' => $updated
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error bulk updating holiday status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update holidays'
            ], 500);
        }
    }

    /**
     * Delete a holiday (soft delete or permanent)
     */
    public function deleteHoliday($id)
    {
        try {
            $context = $this->getInstituteBranchContext();
            if (!$context['institute_id']) {
                return response()->json(['success' => false, 'message' => 'No institute found'], 401);
            }
            
            $holiday = HolidayEvent::where('holiday_event_id', $id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if (!$holiday) {
                return response()->json(['success' => false, 'message' => 'Holiday not found'], 404);
            }
            
            $holiday->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Holiday deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error deleting holiday: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete holiday'
            ], 500);
        }
    }

    /**
     * Edit holiday details
     */
    public function editHoliday(Request $request, $id)
    {
        try {
            $context = $this->getInstituteBranchContext();
            if (!$context['institute_id']) {
                return response()->json(['success' => false, 'message' => 'No institute found'], 401);
            }
            
            $holiday = HolidayEvent::where('holiday_event_id', $id)
                ->where('institute_id', $context['institute_id'])
                ->first();
            
            if (!$holiday) {
                return response()->json(['success' => false, 'message' => 'Holiday not found'], 404);
            }
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'color' => 'nullable|string',
                'description' => 'nullable|string'
            ]);
            
            $holiday->update([
                'title' => $request->title,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'color' => $request->color ?? $holiday->color,
                'description' => $request->description,
                'updated_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Holiday updated successfully',
                'holiday' => $holiday
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error editing holiday: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update holiday'
            ], 500);
        }
    }

    /**
     * Resync holidays (fetch new holidays and mark them as auto-synced)
     */
    public function resyncHolidays(Request $request)
    {
        try {
            $context = $this->getInstituteBranchContext();
            $year = $request->year ?? Carbon::now()->year;
            
            // First, optionally deactivate old auto-synced holidays
            if ($request->has('deactivate_old') && $request->deactivate_old) {
                HolidayEvent::where('institute_id', $context['institute_id'])
                    ->where('type', 'holiday')
                    ->where('is_auto_synced', true)
                    ->where('synced_year', $year)
                    ->update(['status' => 'inactive']);
            }
            
            // Fetch and sync new holidays
            $result = $this->syncPublicHolidaysInternal($year);
            
            // Mark newly created holidays as auto-synced
            HolidayEvent::where('institute_id', $context['institute_id'])
                ->where('type', 'holiday')
                ->where('synced_year', $year)
                ->whereNull('is_auto_synced')
                ->update(['is_auto_synced' => true, 'status' => 'active']);
            
            return response()->json([
                'success' => true,
                'message' => "Resync completed: {$result['newly_added']} new holidays added",
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error resyncing holidays: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to resync holidays'
            ], 500);
        }
    }
}