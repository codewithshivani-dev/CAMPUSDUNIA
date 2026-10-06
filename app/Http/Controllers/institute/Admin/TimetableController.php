<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDetails;
use App\Models\EmployeeSubjectLecture;
use App\Models\AssignSubjectsToEmployee;
use App\Models\SubjectsCoursewise;
use App\Models\DepartmentCategory;
use App\Models\AssignDuties;
use App\Models\DutyType;
use App\Models\HolidayEvent;
use App\Models\EmployeeShift;
use App\Models\DepartmentShift;
use App\Models\Shift;
use App\Models\Departments;
use App\Models\LectureModeOverride;
use App\Models\ProductDetails;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\InstituteBranchAccess;
use Illuminate\Support\Facades\DB;

class TimetableController extends Controller
{
    use InstituteBranchAccess;

    private $employeeShiftCache = [];
    
    public function index(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
        
        $institute_id = $context['institute_id'];
        $branch_id = $context['is_branch_admin'] ? $context['branch_id'] : null;
        
        $employeelist = EmployeeDetails::where('institute_id', $institute_id)
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->where('status', 'active')
            ->get();

        $departments = Departments::where('institute_id', $institute_id)
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->get();

        $courses = ProductDetails::where('institute_id', $institute_id)
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->get();

        $subjects = SubjectsCoursewise::where('institute_id', $institute_id)
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->get();

        return view('instituteAdmin.Calendar.unified-calendar', compact('employeelist', 'departments', 'courses', 'subjects'));
    }

    /**
     * API endpoint to get timetable data
     */
    public function getTimetableData(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        if (!$context['institute_id']) {
            return response()->json(['success' => false, 'message' => 'No institute found'], 401);
        }
        
        $institute_id = $context['institute_id'];
        $branch_id = $context['is_branch_admin'] ? $context['branch_id'] : null;

        // Get date range
        $startDate = $request->get('start_date', Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfWeek(Carbon::SUNDAY)->format('Y-m-d'));
        
        // Get filters
        $departmentId = $request->get('department_id');
        $courseId = $request->get('course_id');
        $employeeId = $request->get('employee_id');
        $subjectId = $request->get('subject_id');
        $designation = $request->get('designation');
        $priority = $request->get('priority');
        
        // Get lectures for the date range
        $lectures = $this->getLecturesForDateRange($request, $institute_id, $branch_id, $startDate, $endDate, $departmentId, $courseId, $employeeId, $subjectId, $designation);
        
        // Get duties for the date range
        $duties = $this->getDutiesForDateRange($request, $institute_id, $branch_id, $startDate, $endDate, $departmentId, $employeeId, $designation, $priority);
        
        // Get all unique time slots
        $timeSlots = $this->getUniqueTimeSlots($lectures, $duties);
        
        // Get days between start and end date
        $days = $this->getDaysInRange($startDate, $endDate);
        
        // Build timetable grid
        $timetable = $this->buildTimetableGrid($days, $timeSlots, $lectures, $duties);
        
        // Get month view data (for current month)
        $monthData = $this->getMonthViewDataFixed($request, $institute_id, $branch_id, $departmentId, $courseId, $employeeId, $subjectId, $designation, $priority);
        
        // Get list view data
        $listData = $this->getListViewDataFixed($lectures, $duties);
        
        // Calculate stats
        $stats = [
            'lecture_count' => count($lectures),
            'duty_count' => count($duties),
            'time_slot_count' => count($timeSlots),
            'date_range' => $startDate . ' to ' . $endDate
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'timeSlots' => $timeSlots,
            'days' => $days,
            'timetable' => $timetable,
            'monthData' => $monthData,
            'listData' => $listData
        ]);
    }

    /**
     * Get all unique time slots from lectures and duties - FIXED
     */
    private function getUniqueTimeSlots($lectures, $duties)
    {
        $timeSlots = [];
        $allEvents = array_merge($lectures, $duties);
        
        foreach ($allEvents as $event) {
            // Extract just the time part (H:i:s) from start_time
            $startTime = date('H:i:s', strtotime($event['start_time']));
            $endTime = date('H:i:s', strtotime($event['end_time']));
            $key = $startTime . '-' . $endTime;
            
            if (!isset($timeSlots[$key])) {
                $timeSlots[$key] = [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'display' => date('h:i A', strtotime($startTime)) . ' - ' . date('h:i A', strtotime($endTime)),
                    'sort_time' => $startTime
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
     * Get days in range - ensure all days are included
     */
    private function getDaysInRange($startDate, $endDate)
    {
        $days = [];
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $current = clone $start;
        $dayNumber = 0;
        while ($current <= $end) {
            $dayName = strtolower($current->format('l'));
            $dayOfWeek = $current->dayOfWeek;
            $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            
            $days[$dayName] = [
                'name' => ucfirst($dayName),
                'full_name' => $dayNames[$dayOfWeek],
                'date' => $current->format('Y-m-d'),
                'display_date' => $current->format('M d, Y'),
                'day_index' => $dayOfWeek,
                'day_number' => $dayNumber++,
                'is_weekend' => in_array($dayName, ['saturday', 'sunday'])
            ];
            $current->addDay();
        }
        
        return $days;
    }

    /**
     * Build timetable grid - Group multiple events at same time slot
     */
    private function buildTimetableGrid($days, $timeSlots, $lectures, $duties)
    {
        $timetable = [];
        $allEvents = array_merge($lectures, $duties);
        
        // Group events by date and time (using standardized time keys)
        $eventsByDateTime = [];
        foreach ($allEvents as $event) {
            $date = $event['date'];
            // Standardize time key using just the time parts
            $startTime = date('H:i:s', strtotime($event['start_time']));
            $endTime = date('H:i:s', strtotime($event['end_time']));
            $timeKey = $startTime . '-' . $endTime;
            
            if (!isset($eventsByDateTime[$date])) {
                $eventsByDateTime[$date] = [];
            }
            if (!isset($eventsByDateTime[$date][$timeKey])) {
                $eventsByDateTime[$date][$timeKey] = [];
            }
            $eventsByDateTime[$date][$timeKey][] = $event;
          
        }
        
        // Build grid for each day
        foreach ($days as $dayName => $dayInfo) {
            $date = $dayInfo['date'];
            $timetable[$date] = [];
            
            foreach ($timeSlots as $slot) {
                $timeKey = $slot['start_time'] . '-' . $slot['end_time'];
                $events = $eventsByDateTime[$date][$timeKey] ?? [];
                
                if (count($events) === 1) {
                    // Single event - display normally
                    $timetable[$date][$timeKey] = $events[0];
                   
                } elseif (count($events) > 1) {
                    // Multiple events at same time - Group them together
                    $lectureCount = 0;
                    $dutyCount = 0;
                    $departments = [];
                    $sections = [];
                    
                    foreach ($events as $event) {
                        if ($event['type'] === 'lecture') {
                            $lectureCount++;
                            if (!empty($event['department'])) {
                                $departments[] = $event['department'];
                            }
                            if (!empty($event['section'])) {
                                $sections[] = $event['section'];
                            }
                        } else {
                            $dutyCount++;
                        }
                    }
                    
                    $departments = array_unique($departments);
                    $sections = array_unique($sections);
                    
                    // Create a grouped event
                    $timetable[$date][$timeKey] = [
                        'has_multiple' => true,
                        'events' => $events,
                        'type' => 'multiple',
                        'lecture_count' => $lectureCount,
                        'duty_count' => $dutyCount,
                        'total_count' => count($events),
                        'departments' => $departments,
                        'sections' => $sections,
                        'title' => $lectureCount . ' Lecture' . ($lectureCount > 1 ? 's' : '') . 
                                ($dutyCount > 0 ? ' + ' . $dutyCount . ' Duty' . ($dutyCount > 1 ? 'ies' : '') : ''),
                        'color_class' => 'multiple-event'
                    ];
                   
                } else {
                    $timetable[$date][$timeKey] = null;
                }
            }
        }
        
        return $timetable;
    }

    private function getEmployeeShiftSummary($employeeId, $date, $institute_id, $branch_id)
    {
        if (!$employeeId || !$date) {
            return null;
        }

        $cacheKey = $employeeId . '|' . $date;
        if (isset($this->employeeShiftCache[$cacheKey])) {
            return $this->employeeShiftCache[$cacheKey];
        }

        $employee = EmployeeDetails::where('employee_id', $employeeId)
            ->where('institute_id', $institute_id)
            ->when($branch_id, function ($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->first();

        if (!$employee) {
            return $this->employeeShiftCache[$cacheKey] = null;
        }

        $shiftData = $employee->resolveEffectiveShift(Carbon::parse($date));
        if (!$shiftData || empty($shiftData['shift'])) {
            return $this->employeeShiftCache[$cacheKey] = [
                'shift_name' => 'No shift assigned',
                'shift_timing' => 'N/A',
                'shift_priority' => null,
                'shift_type' => $shiftData['type'] ?? null,
                'shift_start_time' => null,
                'shift_end_time' => null,
                'is_active' => false,
            ];
        }

        $shift = $shiftData['shift'];
        $start = $shift->start_time ? date('h:i A', strtotime($shift->start_time)) : null;
        $end = $shift->end_time ? date('h:i A', strtotime($shift->end_time)) : null;

        return $this->employeeShiftCache[$cacheKey] = [
            'shift_name' => $shift->shift_name ?? 'N/A',
            'shift_timing' => trim(($start ? $start : '') . ($start && $end ? ' - ' : '') . ($end ? $end : '')) ?: 'N/A',
            'shift_priority' => $shift->priority ?? null,
            'shift_type' => $shiftData['type'] ?? null,
            'shift_start_time' => $shift->start_time ?? null,
            'shift_end_time' => $shift->end_time ?? null,
            'is_active' => true,
        ];
    }

    private function getMonthViewDataFixed($request, $institute_id, $branch_id, $departmentId, $courseId, $employeeId, $subjectId, $designation, $priority)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        
        // Get ALL days in the month
        $allDays = [];
        $current = clone $startDate;
        while ($current <= $endDate) {
            $dateKey = $current->format('Y-m-d');
            $allDays[$dateKey] = [
                'date' => $current->format('d'),
                'full_date' => $dateKey,
                'day_name' => $current->format('l'),
                'lectures' => [],
                'duties' => []
            ];
            $current->addDay();
        }
        
        // Get lectures for month
        $lectures = $this->getLecturesForDateRange($request, $institute_id, $branch_id, $startDate->format('Y-m-d'), $endDate->format('Y-m-d'), $departmentId, $courseId, $employeeId, $subjectId, $designation);
        
        // Get duties for month
        $duties = $this->getDutiesForDateRange($request, $institute_id, $branch_id, $startDate->format('Y-m-d'), $endDate->format('Y-m-d'), $departmentId, $employeeId, $designation, $priority);
        
        // Add lectures to allDays
        foreach ($lectures as $lecture) {
            $date = $lecture['date'];
            if (isset($allDays[$date])) {
                // Ensure start_time_formatted and end_time_formatted exist
                if (!isset($lecture['start_time_formatted'])) {
                    $lecture['start_time_formatted'] = date('h:i A', strtotime($lecture['start_time']));
                    $lecture['end_time_formatted'] = date('h:i A', strtotime($lecture['end_time']));
                }
                $allDays[$date]['lectures'][] = $lecture;
            }
        }
        
        // Add duties to allDays
        foreach ($duties as $duty) {
            $date = $duty['date'];
            if (isset($allDays[$date])) {
                // Ensure start_time_formatted and end_time_formatted exist
                if (!isset($duty['start_time_formatted'])) {
                    $duty['start_time_formatted'] = date('h:i A', strtotime($duty['start_time']));
                    $duty['end_time_formatted'] = date('h:i A', strtotime($duty['end_time']));
                }
                $allDays[$date]['duties'][] = $duty;
            }
        }
        
        return $allDays;
    }

    private function getListViewDataFixed($lectures, $duties)
    {
        $listData = [];
        $allEvents = array_merge($lectures, $duties);
        
        // Sort by date and time
        usort($allEvents, function($a, $b) {
            $dateCompare = strcmp($a['date'], $b['date']);
            if ($dateCompare === 0) {
                return strcmp($a['start_time'], $b['start_time']);
            }
            return $dateCompare;
        });
        
        foreach ($allEvents as $event) {
            if ($event['type'] === 'lecture') {
                $listData[] = [
                    'type' => 'lecture',
                    'id' => $event['id'],
                    'date' => $event['date'],
                    'start_time' => date('h:i A', strtotime($event['start_time'])),
                    'end_time' => date('h:i A', strtotime($event['end_time'])),
                    'title' => $event['title'],
                    'subject' => $event['subject'],
                    'faculty' => $event['faculty'],
                    'designation' => $event['designation'] ?? 'N/A',
                    'department' => $event['department'] ?? 'N/A',
                    'course' => $event['course'] ?? 'N/A',
                    'section' => $event['section'] ?? 'N/A',
                    'location' => $event['location'] ?? 'N/A',
                    'frequency' => $event['frequency'] ?? 'N/A',
                    'shift_name' => $event['shift_name'] ?? 'No shift assigned',
                    'shift_timing' => $event['shift_timing'] ?? 'N/A',
                    'employee_id' => $event['employee_id'] ?? null,
                    'assignment_id' => $event['assignment_id'] ?? null,
                    'lecture_mode' => $event['lecture_mode'] ?? 'offline',
                    'has_override' => $event['has_override'] ?? false,
                    'override_id' => $event['override_id'] ?? null,
                    'override_reason' => $event['override_reason'] ?? null,
                    'meeting_link' => $event['meeting_link'] ?? null,
                    'meeting_id' => $event['meeting_id'] ?? null,
                    'meeting_password' => $event['meeting_password'] ?? null,
                    'meeting_instructions' => $event['meeting_instructions'] ?? null,
                    'description' => $event['description'] ?? 'No description',
                    'is_reassigned' => $event['is_reassigned'] ?? false,
                    'reassigned_to_employee_id' => $event['reassigned_to_employee_id'] ?? null,
                    'reassigned_from_employee_id' => $event['reassigned_from_employee_id'] ?? null,
                    'reassigned_to_employee_name' => $event['reassigned_to_employee_name'] ?? null,
                    'reassigned_from_employee_name' => $event['reassigned_from_employee_name'] ?? null,
                    'reassignment_reason' => $event['reassignment_reason'] ?? null,
                    'reassignment_date' => $event['reassignment_date'] ?? null,
                    'reassigned_at' => $event['reassigned_at'] ?? null,
                    'start_time_formatted' => date('h:i A', strtotime($event['start_time'])),
                    'end_time_formatted' => date('h:i A', strtotime($event['end_time']))
                ];
            } else {
                $listData[] = [
                    'type' => 'duty',
                    'id' => $event['id'],
                    'date' => $event['date'],
                    'start_time' => date('h:i A', strtotime($event['start_time'])),
                    'end_time' => date('h:i A', strtotime($event['end_time'])),
                    'title' => $event['title'],
                    'faculty' => $event['faculty'],
                    'designation' => $event['designation'] ?? 'N/A',
                    'department' => $event['department'] ?? 'N/A',
                    'priority' => $event['priority'] ?? 'N/A',
                    'status' => $event['status'] ?? 'N/A',
                    'location' => $event['location'] ?? 'N/A',
                    'venue' => $event['venue'] ?? 'N/A',
                    'frequency' => $event['frequency'] ?? 'Once',
                    'shift_name' => $event['shift_name'] ?? 'No shift assigned',
                    'shift_timing' => $event['shift_timing'] ?? 'N/A',
                    'employee_id' => $event['employee_id'] ?? null,
                    'description' => $event['description'] ?? 'No description',
                    'instructions' => $event['instructions'] ?? 'No instructions',
                    'start_time_formatted' => date('h:i A', strtotime($event['start_time'])),
                    'end_time_formatted' => date('h:i A', strtotime($event['end_time']))
                ];
            }
        }
        
        return $listData;
    }
    
    /**
     * Update the getLecturesForDateRange method to use overrides
     */
    // private function getLecturesForDateRange($request, $institute_id, $branch_id, $startDate, $endDate, $departmentId, $courseId, $employeeId, $subjectId, $designation)
    // {
    //     $lectures = [];
        
    //     $query = EmployeeSubjectLecture::query()
    //         ->join('assign_subjects_to_employee as ase', 'employee_subject_lectures.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
    //         ->join('employee_details as ed', 'ase.employee_id', '=', 'ed.employee_id')
    //         ->leftJoin('subjects_coursewise as sc', 'ase.subject_id', '=', 'sc.subject_id')
    //         ->leftJoin('sub_subjects as ss', function($join) {
    //             $join->on('ase.sub_subject_id', '=', 'ss.sub_subject_id')
    //                 ->on('ase.subject_id', '=', 'ss.subject_id');
    //         })
    //         ->leftJoin('departments as d', 'ase.department_id', '=', 'd.department_id')
    //         ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
    //         ->leftJoin('course_fee_structures as cfs', function($join) use ($institute_id, $branch_id) {
    //             $join->on('ase.course_detail_id', '=', 'cfs.product_id')
    //                 ->where('cfs.institute_id', '=', $institute_id);
    //             if ($branch_id) {
    //                 $join->where('cfs.branch_id', '=', $branch_id);
    //             }
    //         })
    //         ->leftJoin('employee_details as reassigned_to_emp', 'employee_subject_lectures.reassigned_to_employee_id', '=', 'reassigned_to_emp.employee_id')
    //         ->leftJoin('employee_details as reassigned_from_emp', 'employee_subject_lectures.reassigned_from_employee_id', '=', 'reassigned_from_emp.employee_id')
    //         ->select(
    //             'employee_subject_lectures.*',
    //             'ase.employee_id',
    //             'ase.department_id',
    //             'ase.subject_display_name',
    //             'ase.subject_id',
    //             'ase.section_id',
    //             'ase.course_detail_id',
    //             'ed.name as employee_name',
    //             'ed.designation',
    //             'sc.subject_name as main_subject_name',
    //             'ss.sub_subject_name',
    //             'd.department as department_name',
    //             'pd.course_type',
    //             'pd.sub_type as branch_name',
    //             'pd.mode_of_course as course_mode',
    //             'reassigned_to_emp.name as reassigned_to_employee_name',
    //             'reassigned_from_emp.name as reassigned_from_employee_name',
    //             'cfs.sections as fee_sections'
    //         )
    //         ->where('ase.institute_id', $institute_id)
    //         ->where('ase.status', 'active')
    //         ->where('employee_subject_lectures.status', 'active')
    //         ->where('employee_subject_lectures.is_cancelled', 0)
    //         ->when($branch_id, function($query) use ($branch_id) {
    //             return $query->where('ase.branch_id', $branch_id);
    //         })
    //         ->where(function($query) use ($startDate, $endDate) {
    //             $query->where('employee_subject_lectures.valid_from', '<=', $endDate)
    //                 ->where('employee_subject_lectures.valid_to', '>=', $startDate);
    //         });

    //     // Apply filters
    //     if ($departmentId) {
    //         $query->where('ase.department_id', $departmentId);
    //     }
    //     if ($courseId) {
    //         $query->where('ase.course_detail_id', $courseId);
    //     }
    //     if ($employeeId) {
    //         $query->where('ase.employee_id', $employeeId);
    //     }
    //     if ($subjectId) {
    //         $query->where('ase.subject_id', $subjectId);
    //     }
    //     if ($designation) {
    //         $query->where('ed.designation', $designation);
    //     }

    //     $lectureRecords = $query->get();

    //     $start = Carbon::parse($startDate);
    //     $end = Carbon::parse($endDate);
        
    //     foreach ($lectureRecords as $lecture) {
    //         $validFrom = Carbon::parse($lecture->valid_from);
    //         $validTo = Carbon::parse($lecture->valid_to);
            
    //         $lectureStart = $validFrom->gt($start) ? clone $validFrom : clone $start;
    //         $lectureEnd = $validTo->lt($end) ? clone $validTo : clone $end;
            
    //         $shiftInfo = $this->getEmployeeShiftSummary($lecture->employee_id, $lectureStart->format('Y-m-d'), $institute_id, $branch_id);
    //         $lecture->shift_name = $shiftInfo['shift_name'] ?? 'No shift assigned';
    //         $lecture->shift_timing = $shiftInfo['shift_timing'] ?? 'N/A';
            
    //         $current = clone $lectureStart;
    //         while ($current <= $lectureEnd) {
    //             $dayOfWeek = $current->dayOfWeek;
                
    //             if ($dayOfWeek == 0) {
    //                 $current->addDay();
    //                 continue;
    //             }
                
    //             $includeDate = false;
                
    //             switch ($lecture->frequency) {
    //                 case 'daily':
    //                     $includeDate = true;
    //                     break;
    //                 case 'weekly':
    //                     $includeDate = true;
    //                     break;
    //                 case 'once':
    //                     if ($current->format('Y-m-d') == $validFrom->format('Y-m-d')) {
    //                         $includeDate = true;
    //                     }
    //                     break;
    //                 default:
    //                     $includeDate = true;
    //                     break;
    //             }
                
    //             if ($includeDate) {
    //                 // Use the override-aware method to get lecture data
    //                 $lectures[] = $this->getLectureWithOverrides($lecture, $current->format('Y-m-d'), $institute_id, $branch_id);
    //             }
                
    //             $current->addDay();
    //         }
    //     }
        
    //     return $lectures;
    // }
    
    private function getLecturesForDateRange(
        $request,
        $institute_id,
        $branch_id,
        $startDate,
        $endDate,
        $departmentId,
        $courseId,
        $employeeId,
        $subjectId,
        $designation
    ) {
        $lectures = [];
    
        $query = EmployeeSubjectLecture::query()
            ->join(
                'assign_subjects_to_employee as ase',
                'employee_subject_lectures.emp_assign_subject_id',
                '=',
                'ase.emp_assign_subject_id'
            )
            ->join(
                'employee_details as ed',
                'ase.employee_id',
                '=',
                'ed.employee_id'
            )
            ->leftJoin(
                'subjects_coursewise as sc',
                'ase.subject_id',
                '=',
                'sc.subject_id'
            )
            ->leftJoin('sub_subjects as ss', function ($join) {
                $join->on('ase.sub_subject_id', '=', 'ss.sub_subject_id')
                    ->on('ase.subject_id', '=', 'ss.subject_id');
            })
            ->leftJoin(
                'departments as d',
                'ase.department_id',
                '=',
                'd.department_id'
            )
            ->leftJoin(
                'product_details as pd',
                'ase.course_detail_id',
                '=',
                'pd.product_id'
            )
    
            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            | Do NOT directly JOIN course_fee_structures here.
            |
            | One product/course can have multiple fee structure records.
            | That JOIN was causing one lecture to become multiple rows.
            |--------------------------------------------------------------------------
            */
    
            ->leftJoin(
                'employee_details as reassigned_to_emp',
                'employee_subject_lectures.reassigned_to_employee_id',
                '=',
                'reassigned_to_emp.employee_id'
            )
            ->leftJoin(
                'employee_details as reassigned_from_emp',
                'employee_subject_lectures.reassigned_from_employee_id',
                '=',
                'reassigned_from_emp.employee_id'
            )
    
            ->select(
                'employee_subject_lectures.*',
    
                'ase.employee_id',
                'ase.department_id',
                'ase.subject_display_name',
                'ase.subject_id',
                'ase.section_id',
                'ase.course_detail_id',
    
                'ed.name as employee_name',
                'ed.designation',
    
                'sc.subject_name as main_subject_name',
                'ss.sub_subject_name',
    
                'd.department as department_name',
    
                'pd.course_type',
                'pd.sub_type as branch_name',
                'pd.mode_of_course as course_mode',
    
                'reassigned_to_emp.name as reassigned_to_employee_name',
                'reassigned_from_emp.name as reassigned_from_employee_name'
            )
    
            ->where('ase.institute_id', $institute_id)
    
            ->where('ase.status', 'active')
    
            ->where(
                'employee_subject_lectures.status',
                'active'
            )
    
            ->where(
                'employee_subject_lectures.is_cancelled',
                0
            )
    
            ->when($branch_id, function ($query) use ($branch_id) {
                return $query->where(
                    'ase.branch_id',
                    $branch_id
                );
            })
    
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(
                    'employee_subject_lectures.valid_from',
                    '<=',
                    $endDate
                )
                ->where(
                    'employee_subject_lectures.valid_to',
                    '>=',
                    $startDate
                );
            });
    
        /*
        |--------------------------------------------------------------------------
        | Apply filters
        |--------------------------------------------------------------------------
        */
    
        if ($departmentId) {
            $query->where(
                'ase.department_id',
                $departmentId
            );
        }
    
        if ($courseId) {
            $query->where(
                'ase.course_detail_id',
                $courseId
            );
        }
    
        if ($employeeId) {
            $query->where(
                'ase.employee_id',
                $employeeId
            );
        }
    
        if ($subjectId) {
            $query->where(
                'ase.subject_id',
                $subjectId
            );
        }
    
        if ($designation) {
            $query->where(
                'ed.designation',
                $designation
            );
        }
    
        /*
        |--------------------------------------------------------------------------
        | Get lectures
        |--------------------------------------------------------------------------
        |
        | unique('id') is an additional safety check.
        |
        */
    
        $lectureRecords = $query
            ->get()
            ->unique(function ($lecture) {
                return $lecture->id;
            })
            ->values();
    
        /*
        |--------------------------------------------------------------------------
        | Generate lecture occurrences
        |--------------------------------------------------------------------------
        */
    
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
    
        foreach ($lectureRecords as $lecture) {
    
            $validFrom = Carbon::parse(
                $lecture->valid_from
            );
    
            $validTo = Carbon::parse(
                $lecture->valid_to
            );
    
            $lectureStart = $validFrom->gt($start)
                ? $validFrom->copy()
                : $start->copy();
    
            $lectureEnd = $validTo->lt($end)
                ? $validTo->copy()
                : $end->copy();
    
            /*
            |--------------------------------------------------------------------------
            | Employee shift
            |--------------------------------------------------------------------------
            */
    
            $shiftInfo = $this->getEmployeeShiftSummary(
                $lecture->employee_id,
                $lectureStart->format('Y-m-d'),
                $institute_id,
                $branch_id
            );
    
            $lecture->shift_name =
                $shiftInfo['shift_name']
                ?? 'No shift assigned';
    
            $lecture->shift_timing =
                $shiftInfo['shift_timing']
                ?? 'N/A';
    
            /*
            |--------------------------------------------------------------------------
            | Generate dates
            |--------------------------------------------------------------------------
            */
    
            $current = $lectureStart->copy();
    
            while ($current <= $lectureEnd) {
    
                $dayOfWeek = $current->dayOfWeek;
    
                /*
                |--------------------------------------------------------------------------
                | Skip Sunday
                |--------------------------------------------------------------------------
                */
    
                if ($dayOfWeek == 0) {
                    $current->addDay();
                    continue;
                }
    
                $includeDate = false;
    
                switch (strtolower($lecture->frequency)) {
    
                    case 'daily':
    
                        $includeDate = true;
    
                        break;
    
                    case 'weekly':
    
                        /*
                        |--------------------------------------------------------------------------
                        | Existing behaviour:
                        | weekly lecture is included on Monday-Saturday.
                        |--------------------------------------------------------------------------
                        */
    
                        $includeDate = true;
    
                        break;
    
                    case 'once':
    
                        if (
                            $current->format('Y-m-d')
                            ===
                            $validFrom->format('Y-m-d')
                        ) {
                            $includeDate = true;
                        }
    
                        break;
    
                    default:
    
                        $includeDate = true;
    
                        break;
                }
    
                if ($includeDate) {
    
                    $lectureEvent =
                        $this->getLectureWithOverrides(
                            $lecture,
                            $current->format('Y-m-d'),
                            $institute_id,
                            $branch_id
                        );
    
                    /*
                    |--------------------------------------------------------------------------
                    | Extra duplicate protection
                    |--------------------------------------------------------------------------
                    |
                    | Same lecture ID + same date + same time
                    | should only appear once.
                    |
                    */
    
                    $duplicateKey =
                        $lectureEvent['id']
                        . '|'
                        . $lectureEvent['date']
                        . '|'
                        . $lectureEvent['start_time']
                        . '|'
                        . $lectureEvent['end_time'];
    
                    $alreadyExists = false;
    
                    foreach ($lectures as $existingLecture) {
    
                        $existingKey =
                            $existingLecture['id']
                            . '|'
                            . $existingLecture['date']
                            . '|'
                            . $existingLecture['start_time']
                            . '|'
                            . $existingLecture['end_time'];
    
                        if ($existingKey === $duplicateKey) {
                            $alreadyExists = true;
                            break;
                        }
                    }
    
                    if (!$alreadyExists) {
                        $lectures[] = $lectureEvent;
                    }
                }
    
                $current->addDay();
            }
        }
    
        return $lectures;
    }

    /**
     * Get duties for date range with filters - FIXED to properly include all duties
     */
     private function getDutiesForDateRange($request, $institute_id, $branch_id, $startDate, $endDate, $departmentId, $employeeId, $designation, $priority)
    {
        $duties = [];
        
        $query = AssignDuties::with(['employee', 'dutyType'])
            ->leftJoin('departments as d', 'employee_duties.department_id', '=', 'd.department_id')
            ->select('employee_duties.*', 'd.department as department_name')
            ->where('employee_duties.institute_id', $institute_id)
            ->where('employee_duties.status', '!=', 'cancelled')
            ->where('employee_duties.status', '!=', 'completed')
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('employee_duties.branch_id', $branch_id);
            });

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
        if ($designation) {
            $query->whereHas('employee', function($q) use ($designation) {
                $q->where('designation', $designation);
            });
        }
        if ($priority) {
            $query->where('priority', $priority);
        }

        $dutyRecords = $query->get();
        
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $priorityColors = [
            'low' => 'duty-low',
            'medium' => 'duty-medium', 
            'high' => 'duty-high',
            'urgent' => 'duty-urgent'
        ];
        
        foreach ($dutyRecords as $duty) {
           
            if ($duty->frequency === 'once' && $duty->date) {
                $dutyDate = Carbon::parse($duty->date);
                if ($dutyDate->between($start, $end)) {
                    $duties[] = $this->formatDutyForTimetable($duty, $dutyDate, $priorityColors);
                }
            } elseif (in_array($duty->frequency, ['daily', 'weekly', 'monthly']) && $duty->from_date && $duty->to_date) {
                $fromDate = Carbon::parse($duty->from_date);
                $toDate = Carbon::parse($duty->to_date);
                
                // Start from max of from_date and filter start
                $current = $fromDate->gt($start) ? clone $fromDate : clone $start;
                $loopEnd = $toDate->lt($end) ? clone $toDate : clone $end;
                
                $daysOfWeek = [];
                if ($duty->frequency === 'weekly' && $duty->days_of_week) {
                    if (is_string($duty->days_of_week)) {
                        $daysOfWeek = json_decode($duty->days_of_week, true);
                    } elseif (is_array($duty->days_of_week)) {
                        $daysOfWeek = $duty->days_of_week;
                    }
                    if (is_array($daysOfWeek)) {
                        $daysOfWeek = array_map('intval', $daysOfWeek);
                    }
                }
                
                while ($current <= $loopEnd) {
                    $include = false;
                    
                    switch ($duty->frequency) {
                        case 'daily':
                            $include = true;
                            break;
                        case 'weekly':
                            // If no specific days specified, include all days
                            if (empty($daysOfWeek)) {
                                $include = true;
                            } else {
                                $include = in_array($current->dayOfWeek, $daysOfWeek);
                            }
                            break;
                        case 'monthly':
                            $dayOfMonth = $duty->day_of_month ?: $fromDate->day;
                            $include = ($current->day == $dayOfMonth);
                            break;
                    }
                    
                    if ($include) {
                        $duties[] = $this->formatDutyForTimetable($duty, $current, $priorityColors);
                    }
                    
                    $current->addDay();
                }
            }
        }
        
        return $duties;
    }
    
    /**
     * Format duty for timetable - FIXED to use proper time format
     */
     private function formatDutyForTimetable($duty, $date, $priorityColors)
    {
        // Extract just the time part (H:i:s) from start_time and end_time
        $startTime = date('H:i:s', strtotime($duty->start_time));
        $endTime = date('H:i:s', strtotime($duty->end_time));
        
        $shiftInfo = $this->getEmployeeShiftSummary($duty->employee_id, $date->format('Y-m-d'), $duty->institute_id, $duty->branch_id ?? null);

        return [
            'id' => $duty->id,
            'type' => 'duty',
            'date' => $date->format('Y-m-d'),
            'day_name' => strtolower($date->format('l')),
            'start_time' => $startTime,  // Use just the time, not the full datetime
            'end_time' => $endTime,      // Use just the time, not the full datetime
            'title' => $duty->title ?? $duty->duty_type ?? 'Duty',
            'faculty' => $duty->employee->name ?? 'N/A',
            'designation' => $duty->employee->designation ?? 'N/A',
            'department' => $duty->department_name ?? optional($duty->department)->department ?? 'N/A',
            'department_id' => $duty->department_id,
            'employee_id' => $duty->employee_id,
            'shift_name' => $shiftInfo['shift_name'] ?? 'No shift assigned',
            'shift_timing' => $shiftInfo['shift_timing'] ?? 'N/A',
            'shift_priority' => $shiftInfo['shift_priority'] ?? null,
            'shift_type' => $shiftInfo['shift_type'] ?? null,
            'priority' => $duty->priority,
            'status' => $duty->status,
            'location' => $duty->location ?? 'Not specified',
            'venue' => $duty->venue ?? 'Not specified',
            'description' => $duty->description ?? 'No description',
            'instructions' => $duty->instructions ?? 'No instructions',
            'color_class' => $priorityColors[$duty->priority] ?? 'duty-medium',
            'frequency' => ucfirst($duty->frequency),
            'frequency_detail' => $this->formatFrequencyDetail($duty->frequency, $duty, $date),
            'days_of_week' => is_array($duty->days_of_week) ? $duty->days_of_week : ($duty->days_of_week ? json_decode($duty->days_of_week, true) : []),
            'day_of_month' => $duty->day_of_month ?? null,
            'duty_type' => $duty->duty_type,
            'start_time_formatted' => date('h:i A', strtotime($duty->start_time)),
            'end_time_formatted' => date('h:i A', strtotime($duty->end_time))
        ];
    }

    /**
     * Get frequency detail text for events
     */
    private function formatFrequencyDetail($frequency, $event, $date = null)
    {
        $frequency = strtolower(trim($frequency ?? ''));
        $dateObj = $date ? Carbon::parse($date) : null;

        switch ($frequency) {
            case 'once':
                return $dateObj ? 'Once on ' . $dateObj->format('l, d M Y') : 'One-time';
            case 'daily':
                return 'Daily';
            case 'weekly':
                $daysOfWeek = $this->parseDaysOfWeek($event->days_of_week ?? $event->weekly_off_days ?? []);
                if (!empty($daysOfWeek)) {
                    $dayNames = $this->getDayNamesFromNumbers($daysOfWeek);
                    return 'Weekly on ' . implode(', ', $dayNames);
                }
                return $dateObj ? 'Weekly on ' . $dateObj->format('l') : 'Weekly';
            case 'monthly':
                $dayOfMonth = $event->day_of_month ?? ($dateObj ? $dateObj->day : null);
                return $dayOfMonth ? 'Monthly on day ' . $dayOfMonth : 'Monthly';
            default:
                return ucfirst($frequency ?: 'N/A');
        }
    }

    private function getDayNamesFromNumbers(array $daysOfWeek)
    {
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        return array_map(function ($day) use ($dayNames) {
            return isset($dayNames[$day]) ? $dayNames[$day] : $day;
        }, $daysOfWeek);
    }


    /**
     * Get lectures with frequency handling
     */
    private function getLecturesWithFrequency(Request $request, $institute_id, $branch_id, $startDate, $endDate)
    {
        $lectures = [];
        
        $query = EmployeeSubjectLecture::query()
            ->join('assign_subjects_to_employee as ase', 'employee_subject_lectures.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->join('employee_details as ed', 'ase.employee_id', '=', 'ed.employee_id')
            ->leftJoin('subjects_coursewise as sc', 'ase.subject_id', '=', 'sc.subject_id')
            ->leftJoin('sub_subjects as ss', function($join) {
                $join->on('ase.sub_subject_id', '=', 'ss.sub_subject_id')
                    ->on('ase.subject_id', '=', 'ss.subject_id');
            })
            ->leftJoin('departments as d', 'ase.department_id', '=', 'd.department_id')
            ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
            ->leftJoin('course_fee_structures as cfs', function($join) use ($institute_id, $branch_id) {
                $join->on('ase.course_detail_id', '=', 'cfs.product_id')
                    ->where('cfs.institute_id', '=', $institute_id);
                if ($branch_id) {
                    $join->where('cfs.branch_id', '=', $branch_id);
                }
            })
            ->select(
                'employee_subject_lectures.*',
                'ase.employee_id',
                'ase.department_id',
                'ase.subject_display_name',
                'ase.section_id',
                'ase.course_detail_id',
                'ed.name as employee_name',
                'ed.designation',
                'sc.subject_name as main_subject_name',
                'ss.sub_subject_name',
                'd.department as department_name',
                'pd.course_type',
                'pd.sub_type as branch_name',
                'cfs.sections as fee_sections'
            )
            ->where('ase.institute_id', $institute_id)
            ->where('ase.status', 'active')
            ->where('employee_subject_lectures.status', 'active')
            ->where('employee_subject_lectures.is_cancelled', 0)
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('ase.branch_id', $branch_id);
            });

        $this->applyTimetableFilters($query, $request);
        $lectureRecords = $query->get();
        
        foreach ($lectureRecords as $lecture) {
            $validFrom = Carbon::parse($lecture->valid_from);
            $validTo = Carbon::parse($lecture->valid_to);
            
            // Get lecture days based on frequency
            $lectureDates = $this->getOccurrenceDates($lecture, $validFrom, $validTo, $startDate, $endDate);
            
            $subjectName = $lecture->subject_display_name;
            if (empty($subjectName)) {
                $subjectName = $lecture->main_subject_name ?? $lecture->sub_subject_name ?? 'Lecture';
            }
            
            $sectionName = $this->getSectionNameFromFeeStructure(
                $lecture->section_id,
                $lecture->course_detail_id,
                $lecture->fee_sections
            );
            
            $colorClass = $this->getSubjectColorClass($subjectName);
            
            foreach ($lectureDates as $date) {
                $dayName = strtolower($date->format('l'));
                $timeKey = $lecture->start_time . '-' . $lecture->end_time;
                
                $lectures[] = [
                    'id' => $lecture->id,
                    'type' => 'lecture',
                    'day' => $dayName,
                    'date' => $date->format('Y-m-d'),
                    'time_key' => $timeKey,
                    'start_time' => $lecture->start_time,
                    'end_time' => $lecture->end_time,
                    'title' => $subjectName,
                    'subject' => $subjectName,
                    'faculty' => $lecture->employee_name,
                    'designation' => $lecture->designation ?? 'N/A',
                    'room' => $lecture->location ?? 'Not specified',
                    'department' => $lecture->department_name ?? 'N/A',
                    'department_id' => $lecture->department_id,
                    'course' => $lecture->course_type ?? 'N/A',
                    'branch' => $lecture->branch_name ?? 'N/A',
                    'section' => $sectionName,
                    'employee_id' => $lecture->employee_id,
                    'valid_from' => $lecture->valid_from,
                    'valid_to' => $lecture->valid_to,
                    'description' => $lecture->description ?? 'No description',
                    'color_class' => $colorClass,
                    'frequency' => ucfirst($lecture->frequency ?? 'N/A'),
                    'sub_category' => $lecture->sub_category ?? 'regular'
                    
                ];
            }
        }
        
        return $lectures;
    }

    /**
     * Get duties with frequency handling
     */
    private function getDutiesWithFrequency(Request $request, $institute_id, $branch_id, $startDate, $endDate)
    {
        $duties = [];
        
        $query = AssignDuties::with(['employee', 'dutyType'])
            ->leftJoin('departments as d', 'employee_duties.department_id', '=', 'd.department_id')
            ->select('employee_duties.*', 'd.department as department_name')
            ->where('employee_duties.institute_id', $institute_id)
            ->where('employee_duties.status', '!=', 'cancelled')
            ->when($branch_id, function($query) use ($branch_id) {
                return $query->where('employee_duties.branch_id', $branch_id);
            });

        $this->applyDutyFilters($query, $request);
        $dutyRecords = $query->get();
        
        $priorityColors = [
            'low' => 'duty-low',
            'medium' => 'duty-medium', 
            'high' => 'duty-high',
            'urgent' => 'duty-urgent'
        ];
        
        foreach ($dutyRecords as $duty) {
            $fromDate = $duty->frequency === 'once' ? Carbon::parse($duty->date) : Carbon::parse($duty->from_date);
            $toDate = $duty->frequency === 'once' ? Carbon::parse($duty->date) : Carbon::parse($duty->to_date);
            
            $dutyDates = $this->getOccurrenceDates($duty, $fromDate, $toDate, $startDate, $endDate);
            
            $colorClass = $priorityColors[$duty->priority] ?? 'duty-medium';
            
            foreach ($dutyDates as $date) {
                $dayName = strtolower($date->format('l'));
                $timeKey = $duty->start_time . '-' . $duty->end_time;
                
                $duties[] = [
                    'id' => $duty->id,
                    'type' => 'duty',
                    'day' => $dayName,
                    'date' => $date->format('Y-m-d'),
                    'time_key' => $timeKey,
                    'start_time' => $duty->start_time,
                    'end_time' => $duty->end_time,
                    'title' => $duty->title ?? $duty->duty_type ?? 'Duty',
                    'faculty' => $duty->employee->name ?? 'N/A',
                    'designation' => $duty->employee->designation ?? 'N/A',
                    'department' => $duty->department_name ?? optional($duty->department)->department ?? 'N/A',
                    'department_id' => $duty->department_id,
                    'employee_id' => $duty->employee_id,
                    'priority' => $duty->priority,
                    'status' => $duty->status,
                    'location' => $duty->location ?? 'Not specified',
                    'venue' => $duty->venue ?? 'Not specified',
                    'description' => $duty->description ?? 'No description',
                    'instructions' => $duty->instructions ?? 'No instructions',
                    'color_class' => $colorClass,
                    'frequency' => ucfirst($duty->frequency),
                    'frequency_detail' => $this->formatFrequencyDetail($duty->frequency, $duty, $date),
                    'days_of_week' => is_array($duty->days_of_week) ? $duty->days_of_week : ($duty->days_of_week ? json_decode($duty->days_of_week, true) : []),
                    'day_of_month' => $duty->day_of_month ?? null,
                    'duty_type' => $duty->duty_type
                ];
            }
        }
        
        return $duties;
    }

    /**
     * Get occurrence dates based on frequency rules
     */
    private function getOccurrenceDates($event, $startDate, $endDate, $filterStart, $filterEnd)
    {
        $dates = [];
        $current = $startDate->copy()->max(Carbon::parse($filterStart));
        $end = $endDate->copy()->min(Carbon::parse($filterEnd));
        
        if ($current > $end) {
            return $dates;
        }
        
        switch ($event->frequency) {
            case 'once':
                if ($current->between($startDate, $endDate)) {
                    $dates[] = $current;
                }
                break;
                
            case 'daily':
                while ($current <= $end) {
                    $dates[] = $current->copy();
                    $current->addDay();
                }
                break;
                
            case 'weekly':
                $daysOfWeek = $this->parseDaysOfWeek($event->days_of_week ?? $event->weekly_off_days);
                
                // If no specific days, check all days except weekends (Monday to Saturday by default)
                if (empty($daysOfWeek)) {
                    $daysOfWeek = [1, 2, 3, 4, 5, 6]; // Monday to Saturday, excluding Sunday
                }
                
                // Exclude Sundays by default unless explicitly included
                $excludeSunday = !in_array(0, $daysOfWeek);
                
                while ($current <= $end) {
                    $currentDayOfWeek = $current->dayOfWeek;
                    
                    // Skip Sunday if not included
                    if ($excludeSunday && $currentDayOfWeek == 0) {
                        $current->addDay();
                        continue;
                    }
                    
                    if (in_array($currentDayOfWeek, $daysOfWeek)) {
                        $dates[] = $current->copy();
                    }
                    $current->addDay();
                }
                break;
                
            case 'monthly':
                $dayOfMonth = $event->day_of_month ?? $startDate->day;
                while ($current <= $end) {
                    if ($current->day == $dayOfMonth) {
                        $dates[] = $current->copy();
                    }
                    $current->addDay();
                }
                break;
                
            default:
                // Single date event
                if ($current->between($startDate, $endDate)) {
                    $dates[] = $current;
                }
                break;
        }
        
        return $dates;
    }

    /**
     * Parse days of week from various formats
     */
    private function parseDaysOfWeek($daysData)
    {
        $daysOfWeek = [];
        
        if (empty($daysData)) {
            return $daysOfWeek;
        }
        
        // Day name to number mapping (Carbon: 0=Sunday, 1=Monday, ..., 6=Saturday)
        $dayNameToNumber = [
            'sunday' => 0, 'Sunday' => 0,
            'monday' => 1, 'Monday' => 1,
            'tuesday' => 2, 'Tuesday' => 2,
            'wednesday' => 3, 'Wednesday' => 3,
            'thursday' => 4, 'Thursday' => 4,
            'friday' => 5, 'Friday' => 5,
            'saturday' => 6, 'Saturday' => 6
        ];
        
        if (is_string($daysData)) {
            // Try to decode JSON
            $decoded = json_decode($daysData, true);
            if (is_array($decoded)) {
                $daysData = $decoded;
            } else {
                // Try comma-separated values
                $daysData = explode(',', $daysData);
            }
        }
        
        if (is_array($daysData)) {
            foreach ($daysData as $day) {
                $day = trim($day, '"\' ');
                if (isset($dayNameToNumber[$day])) {
                    $daysOfWeek[] = $dayNameToNumber[$day];
                } elseif (is_numeric($day)) {
                    $daysOfWeek[] = intval($day);
                }
            }
        }
        
        return array_unique($daysOfWeek);
    }

   
    /**
     * Get month view data
     */
    private function getMonthViewData(Request $request, $institute_id, $branch_id)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        
        // Get lectures for month
        $lectures = $this->getLecturesWithFrequency($request, $institute_id, $branch_id, $startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
        
        // Get duties for month
        $duties = $this->getDutiesWithFrequency($request, $institute_id, $branch_id, $startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
        
        // Group by date
        $monthData = [];
        $allEvents = array_merge($lectures, $duties);
        
        foreach ($allEvents as $event) {
            $date = $event['date'];
            if (!isset($monthData[$date])) {
                $monthData[$date] = [
                    'date' => Carbon::parse($date)->format('d'),
                    'full_date' => $date,
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
     * Get list view data
     */
    private function getListViewData(Request $request, $institute_id, $branch_id, $startDate, $endDate, $lectures, $duties)
    {
        $listData = [];
        $allEvents = array_merge($lectures, $duties);
        
        // Sort by date and time
        usort($allEvents, function($a, $b) {
            $dateCompare = strcmp($a['date'], $b['date']);
            if ($dateCompare === 0) {
                return strcmp($a['start_time'], $b['start_time']);
            }
            return $dateCompare;
        });
        
        foreach ($allEvents as $event) {
            if ($event['type'] === 'lecture') {
                $listData[] = [
                    'type' => 'lecture',
                    'id' => $event['id'],
                    'title' => $event['title'],
                    'subject' => $event['subject'],
                    'faculty' => $event['faculty'],
                    'designation' => $event['designation'],
                    'department' => $event['department'],
                    'course' => $event['course'],
                    'section' => $event['section'],
                    'date' => $event['date'],
                    'start_time' => date('h:i A', strtotime($event['start_time'])),
                    'end_time' => date('h:i A', strtotime($event['end_time'])),
                    'location' => $event['room'],
                    'frequency' => $event['frequency'],
                    'frequency_detail' => $event['frequency_detail'] ?? ($event['frequency'] ? ucfirst($event['frequency']) : 'N/A'),
                    'days_of_week' => $event['days_of_week'] ?? [],
                    'day_of_month' => $event['day_of_month'] ?? null,
                    'description' => $event['description']
                ];
            } else {
                $listData[] = [
                    'type' => 'duty',
                    'id' => $event['id'],
                    'title' => $event['title'],
                    'faculty' => $event['faculty'],
                    'designation' => $event['designation'],
                    'department' => $event['department'],
                    'priority' => $event['priority'],
                    'status' => $event['status'],
                    'date' => $event['date'],
                    'start_time' => date('h:i A', strtotime($event['start_time'])),
                    'end_time' => date('h:i A', strtotime($event['end_time'])),
                    'location' => $event['location'],
                    'venue' => $event['venue'],
                    'frequency' => $event['frequency'],
                    'frequency_detail' => $event['frequency_detail'] ?? ($event['frequency'] ? ucfirst($event['frequency']) : 'N/A'),
                    'days_of_week' => $event['days_of_week'] ?? [],
                    'day_of_month' => $event['day_of_month'] ?? null,
                    'description' => $event['description'],
                    'instructions' => $event['instructions']
                ];
            }
        }
        
        return $listData;
    }

    /**
     * Get subject color class
     */
    private function getSubjectColorClass($subjectName)
    {
        $subjectName = strtolower($subjectName);
        
        if (strpos($subjectName, 'math') !== false || strpos($subjectName, 'mathematics') !== false) {
            return 'subject-math';
        } elseif (strpos($subjectName, 'science') !== false || strpos($subjectName, 'physics') !== false || strpos($subjectName, 'chemistry') !== false || strpos($subjectName, 'biology') !== false) {
            return 'subject-science';
        } elseif (strpos($subjectName, 'english') !== false) {
            return 'subject-english';
        } elseif (strpos($subjectName, 'computer') !== false || strpos($subjectName, 'programming') !== false || strpos($subjectName, 'it') !== false) {
            return 'subject-computer';
        } else {
            return 'subject-default';
        }
    }

    /**
     * Apply timetable filters
     */
    private function applyTimetableFilters($query, Request $request)
    {
        if ($request->has('department_id') && $request->department_id != '' && $request->department_id !== 'overall') {
            $query->where('ase.department_id', $request->department_id);
        }

        if ($request->has('course_id') && $request->course_id != '') {
            $query->where('ase.course_detail_id', $request->course_id);
        }

        if ($request->has('employee_id') && $request->employee_id != '') {
            $query->where('ase.employee_id', $request->employee_id);
        }

        if ($request->has('subject_id') && $request->subject_id != '') {
            $query->where('ase.subject_id', $request->subject_id);
        }

        if ($request->has('designation') && $request->designation != '') {
            $query->where('ed.designation', $request->designation);
        }
    }

    /**
     * Apply duty filters
     */
    private function applyDutyFilters($query, Request $request)
    {
        if ($request->has('department_id') && $request->department_id != '' && $request->department_id !== 'overall') {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('employee_id') && $request->employee_id != '') {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->has('designation') && $request->designation != '') {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('designation', $request->designation);
            });
        }

        if ($request->has('priority') && $request->priority != '') {
            $query->where('priority', $request->priority);
        }
    }

    /**
     * Get section name from fee structure
     */
    private function getSectionNameFromFeeStructure($sectionId, $productId, $sectionsData)
    {
        if (!$sectionId) {
            return 'N/A';
        }
        
        if (is_array($sectionsData)) {
            $sections = $sectionsData;
        } elseif (is_string($sectionsData) && !empty($sectionsData)) {
            try {
                $sections = json_decode($sectionsData, true);
                if (!is_array($sections)) {
                    $sections = [];
                }
            } catch (\Exception $e) {
                $sections = [];
            }
        } else {
            $sections = [];
        }
        
        if (!empty($sections)) {
            foreach ($sections as $section) {
                if ((isset($section['section_id']) && $section['section_id'] == $sectionId) ||
                    (isset($section['id']) && $section['id'] == $sectionId) ||
                    (isset($section['code']) && $section['code'] == $sectionId)) {
                    
                    return $section['section_name'] ?? $section['name'] ?? $sectionId;
                }
            }
        }
        
        return $sectionId;
    }


    /**
     * Get lecture with overrides applied for a specific date
     */
    private function getLectureWithOverrides($lecture, $date, $institute_id, $branch_id)
    {
        // Check if there's an override for this specific date
        $override = LectureModeOverride::where('lecture_id', $lecture->id)
            ->where('override_date', $date)
            ->where('institute_id', $institute_id)
            ->where('is_active', true)
            ->first();

        $lectureArray = [
            'id' => $lecture->id,
            'type' => 'lecture',
            'date' => $date,
            'day_name' => strtolower(Carbon::parse($date)->format('l')),
            'start_time' => $lecture->start_time,
            'end_time' => $lecture->end_time,
            'title' => $lecture->subject_display_name ?? $lecture->main_subject_name ?? $lecture->sub_subject_name ?? 'Lecture',
            'subject' => $lecture->subject_display_name ?? $lecture->main_subject_name ?? $lecture->sub_subject_name ?? 'Lecture',
            'faculty' => $lecture->employee_name,
            'designation' => $lecture->designation ?? 'N/A',
            'department' => $lecture->department_name ?? 'N/A',
            'department_id' => $lecture->department_id,
            'course' => $lecture->course_type ?? 'N/A',
            'course_id' => $lecture->course_detail_id,
            'branch' => $lecture->branch_name ?? 'N/A',
            'section' => $this->getSectionNameFromFeeStructure($lecture->section_id, $lecture->course_detail_id, $lecture->fee_sections ?? null),
            'employee_id' => $lecture->employee_id,
            'shift_name' => $lecture->shift_name ?? 'No shift assigned',
            'shift_timing' => $lecture->shift_timing ?? 'N/A',
            'color_class' => $this->getSubjectColorClass($lecture->subject_display_name ?? $lecture->main_subject_name ?? ''),
            'frequency' => ucfirst($lecture->frequency ?? 'Weekly'),
            'start_time_formatted' => date('h:i A', strtotime($lecture->start_time)),
            'end_time_formatted' => date('h:i A', strtotime($lecture->end_time)),
            'course_mode' => $lecture->course_mode ?? 'offline',
            'assignment_id' => $lecture->emp_assign_subject_id ?? null,
            // Reassignment metadata
            'is_reassigned' => isset($lecture->is_reassigned) ? (bool)$lecture->is_reassigned : false,
            'reassigned_to_employee_id' => $lecture->reassigned_to_employee_id ?? null,
            'reassigned_from_employee_id' => $lecture->reassigned_from_employee_id ?? null,
            'reassigned_to_employee_name' => $lecture->reassigned_to_employee_name ?? null,
            'reassigned_from_employee_name' => $lecture->reassigned_from_employee_name ?? null,
            'reassigned_at' => isset($lecture->reassigned_at) ? Carbon::parse($lecture->reassigned_at)->format('Y-m-d H:i:s') : null,
            'reassignment_reason' => $lecture->reassignment_reason ?? null,
            'reassignment_date' => $lecture->reassignment_date ?? null,
            // Default values from lecture
            'lecture_mode' => $lecture->lecture_mode ?? 'offline',
            'meeting_link' => $lecture->meeting_link,
            'meeting_password' => $lecture->meeting_password,
            'meeting_id' => $lecture->meeting_id ?? null,
            'meeting_instructions' => $lecture->meeting_instructions,
            'location' => $lecture->location ?? 'Not specified',
            'has_override' => false
        ];

        // Apply override if exists
        if ($override) {
            $lectureArray['lecture_mode'] = $override->lecture_mode;
            $lectureArray['meeting_link'] = $override->meeting_link;
            $lectureArray['meeting_password'] = $override->meeting_password;
            $lectureArray['meeting_id'] = $override->meeting_id;
            $lectureArray['meeting_instructions'] = $override->meeting_instructions;
            
            // Override location if provided
            if ($override->location_override) {
                $lectureArray['location'] = $override->location_override;
            }
            if ($override->room_override) {
                $lectureArray['room'] = $override->room_override;
            }
            
            $lectureArray['has_override'] = true;
            $lectureArray['override_id'] = $override->id;
            $lectureArray['override_reason'] = $override->remarks;
        }

        return $lectureArray;
    }

    /**
     * API endpoint to create/update a lecture mode override
     */
    public function saveLectureModeOverride(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 401);
        }

        $rules = [
            'lecture_id' => 'required',
            'assignment_id' => 'required',
            'override_date' => 'required|date',
            'lecture_mode' => 'required|in:offline,online',
            'meeting_link' => 'nullable|url',
            'meeting_password' => 'nullable|string|max:50',
            'meeting_id' => 'nullable|string|max:100',
            'meeting_instructions' => 'nullable|string',
            'location_override' => 'nullable|string|max:255',
            'room_override' => 'nullable|string|max:255',
            'remarks' => 'nullable|string'
        ];

        // If switching to online and no existing override, require meeting link
        $existingOverride = LectureModeOverride::where('lecture_id', $request->lecture_id)
            ->where('override_date', $request->override_date)
            ->first();

        if ($request->lecture_mode === 'online') {
            if (!$existingOverride || !$existingOverride->meeting_link) {
                $rules['meeting_link'] = 'required|url';
            }
        }

        $request->validate($rules);

        // try {
            // Verify the lecture belongs to this institute/branch
            $lecture = EmployeeSubjectLecture::where('id', $request->lecture_id)
                ->where('institute_id', $context['institute_id'])
                ->when($context['is_branch_admin'] && $context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                })
                ->first();

            if (!$lecture) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lecture not found or you do not have access.'
                ], 404);
            }

            // Check if override already exists for this date
            $override = LectureModeOverride::updateOrCreate(
                [
                    'lecture_id' => $request->lecture_id,
                    'override_date' => $request->override_date,
                    'institute_id' => $context['institute_id'],
                ],
                [
                    'assignment_id' => $request->assignment_id,
                    'lecture_mode' => $request->lecture_mode,
                    'meeting_link' => $request->lecture_mode === 'online' ? $request->meeting_link : null,
                    'meeting_password' => $request->lecture_mode === 'online' ? $request->meeting_password : null,
                    'meeting_id' => $request->lecture_mode === 'online' ? $request->meeting_id : null,
                    'meeting_instructions' => $request->lecture_mode === 'online' ? $request->meeting_instructions : null,
                    'location_override' => $request->location_override,
                    'room_override' => $request->room_override,
                    'remarks' => $request->remarks,
                    'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                    'created_by' => auth()->user()->employee_id ?? auth()->id(),
                    'is_active' => true
                ]
            );

            return response()->json([
                'success' => true,
                'message' => $override->wasRecentlyCreated ? 'Override created successfully' : 'Override updated successfully',
                'data' => $override
            ]);

        // } catch (\Illuminate\Validation\ValidationException $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Validation failed',
        //         'errors' => $e->errors()
        //     ], 422);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Failed to save override: ' . $e->getMessage()
        //     ], 500);
        // }
    }

    /**
     * API endpoint to delete/remove an override (revert to default)
     */
    public function deleteLectureModeOverride(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 401);
        }

        $request->validate([
            'override_id' => 'required|exists:lecture_mode_overrides,id'
        ]);

        try {
            $override = LectureModeOverride::where('id', $request->override_id)
                ->where('institute_id', $context['institute_id'])
                ->first();

            if (!$override) {
                return response()->json([
                    'success' => false,
                    'message' => 'Override not found or you do not have access.'
                ], 404);
            }

            $override->delete();

            return response()->json([
                'success' => true,
                'message' => 'Override removed. Lecture will use default mode.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove override: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint to get all overrides for a lecture
     */
    public function getLectureOverrides(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 401);
        }

        $request->validate([
            'lecture_id' => 'required|exists:employee_subject_lectures,id'
        ]);

        $overrides = LectureModeOverride::where('lecture_id', $request->lecture_id)
            ->where('institute_id', $context['institute_id'])
            ->orderBy('override_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $overrides
        ]);
    }

}