<?php
namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AssignSubjectsToEmployee;
use App\Models\EmployeeDetails;
use App\Models\EmployeeSubjectLecture;
use App\Models\EmployeeShift;
use Carbon\Carbon;

class LessonController extends Controller
{
    /**
     * Return syllabus/lectures, assigned employees and their shifts for a subject
     */
    public function subjectDetails($subjectId)
    {
        // Lectures / syllabus entries linked to this subject via emp_assign_subject_id
        $lectures = DB::table('employee_subject_lectures as esl')
            ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->where('ase.subject_id', $subjectId)
            ->select('esl.*')
            ->get();

        // Employees assigned to this subject
        $assigned = AssignSubjectsToEmployee::where('subject_id', $subjectId)
            ->where(function($q){ $q->whereNull('status')->orWhere('status','active'); })
            ->get();

        $employeeIds = $assigned->pluck('employee_id')->filter()->unique()->values()->all();

        $employees = [];
        if (!empty($employeeIds)) {
            // load full models so resolveEffectiveShift() has access to institute/branch and other attrs
            $employees = EmployeeDetails::whereIn('employee_id', $employeeIds)->get();
        }

        // Build shifts from employee_subject_lectures grouped by employee -> month -> weekday
        $shiftsGrouped = [];

        // Map emp_assign_subject_id => employee_id
        $assignMap = [];
        foreach ($assigned as $a) {
            if ($a->emp_assign_subject_id) $assignMap[$a->emp_assign_subject_id] = $a->employee_id;
        }

        // Get lectures by emp_assign_subject_id
        $empAssignIds = array_values(array_keys($assignMap));
        $lectureRows = [];
        if (!empty($empAssignIds)) {
            $lectureRows = EmployeeSubjectLecture::whereIn('emp_assign_subject_id', $empAssignIds)->get();
        }

        // create quick employee map for employee_code and name
        $employeeMap = [];
        foreach ($employees as $e) {
            $employeeMap[$e->employee_id] = $e;
        }

        foreach ($lectureRows as $lr) {
            $empAssignId = $lr->emp_assign_subject_id;
            $employeeId = $assignMap[$empAssignId] ?? null;
            if (!$employeeId) continue;

            // parse date range
            $start = $lr->valid_from ? Carbon::parse($lr->valid_from) : null;
            $end = $lr->valid_to ? Carbon::parse($lr->valid_to) : $start;
            if (!$start) continue;

            // determine days of week array and normalize to full weekday names
            $rawDays = [];
            if ($lr->days_of_week) {
                if (is_string($lr->days_of_week)) {
                    $decoded = json_decode($lr->days_of_week, true);
                    if (is_array($decoded)) $rawDays = $decoded;
                    else $rawDays = [$lr->days_of_week];
                } elseif (is_array($lr->days_of_week)) {
                    $rawDays = $lr->days_of_week;
                }
            }

            // fallback to the start date's weekday when none provided
            if (empty($rawDays) && $start) {
                $rawDays = [ $start->format('l') ];
            }

            $days = [];
            foreach ($rawDays as $d) {
                if ($d === null || $d === '') continue;
                // numeric forms
                if (is_numeric($d)) {
                    $n = intval($d);
                    // accept 0..6 (Carbon: 0=Sunday)
                    if ($n >= 0 && $n <= 6) {
                        $map = [0=>'Sunday',1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday'];
                        $days[] = $map[$n];
                        continue;
                    }
                    // accept 1..7 (1=Monday,7=Sunday)
                    if ($n >= 1 && $n <= 7) {
                        $map2 = [1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday',7=>'Sunday'];
                        $days[] = $map2[$n];
                        continue;
                    }
                }

                // string forms: normalize
                $s = strtolower(trim((string)$d));
                $short = substr($s,0,3);
                switch ($short) {
                    case 'sun': $days[] = 'Sunday'; break;
                    case 'mon': $days[] = 'Monday'; break;
                    case 'tue': $days[] = 'Tuesday'; break;
                    case 'wed': $days[] = 'Wednesday'; break;
                    case 'thu': $days[] = 'Thursday'; break;
                    case 'fri': $days[] = 'Friday'; break;
                    case 'sat': $days[] = 'Saturday'; break;
                    default:
                        // lastly, if it's full name capitalized already
                        $cap = ucfirst($s);
                        $valid = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                        if (in_array($cap, $valid)) $days[] = $cap;
                        break;
                }
            }

            // dedupe days
            $days = array_values(array_unique($days));

            // iterate months between start and end inclusive
            $cursor = $start->copy()->startOfMonth();
            $endMonth = $end->copy()->startOfMonth();

            // map weekday names to Carbon dayOfWeek numbers
            $dayMap = [
                'Sunday' => Carbon::SUNDAY,
                'Monday' => Carbon::MONDAY,
                'Tuesday' => Carbon::TUESDAY,
                'Wednesday' => Carbon::WEDNESDAY,
                'Thursday' => Carbon::THURSDAY,
                'Friday' => Carbon::FRIDAY,
                'Saturday' => Carbon::SATURDAY,
            ];

            while ($cursor <= $endMonth) {
                $monthKey = $cursor->format('F Y');

                // effective range inside this month for the lecture
                $monthStart = $cursor->copy()->startOfMonth();
                $monthEnd = $cursor->copy()->endOfMonth();
                $effStart = $start->copy();
                if ($effStart->lessThan($monthStart)) $effStart = $monthStart->copy();
                $effEnd = $end->copy();
                if ($effEnd->greaterThan($monthEnd)) $effEnd = $monthEnd->copy();

                foreach ($days as $wd) {
                    // compute dates in [effStart, effEnd] that fall on $wd
                    $dates = [];

                    if (!isset($dayMap[$wd])) {
                        $norm = ucfirst(strtolower($wd));
                        if (isset($dayMap[$norm])) $wdKey = $norm;
                        else $wdKey = null;
                    } else {
                        $wdKey = $wd;
                    }

                    if ($wdKey) {
                        $targetNum = $dayMap[$wdKey];
                        $d = $effStart->copy();
                        $curNum = $d->dayOfWeek;
                        $delta = ($targetNum - $curNum + 7) % 7;
                        $first = $d->copy()->addDays($delta);
                        if ($first->lessThan($effStart)) $first->addDays(7);
                        while ($first->lessThanOrEqualTo($effEnd)) {
                            $dates[] = $first->format('Y-m-d');
                            $first->addDays(7);
                        }
                    }

                    $shiftsGrouped[$employeeId][$monthKey][$wd][] = [
                        'start' => $lr->start_time ?? null,
                        'end' => $lr->end_time ?? null,
                        'valid_from' => $lr->valid_from,
                        'valid_to' => $lr->valid_to,
                        'emp_assign_subject_id' => $empAssignId,
                        'employee_code' => $employeeMap[$employeeId]->employee_code ?? null,
                        'lecture_id' => $lr->id ?? null,
                        'dates' => $dates,
                        'count' => count($dates),
                    ];
                }

                $cursor->addMonth();
            }
        }

        return response()->json([
            'syllabus' => $lectures,
            'assigned' => $assigned,
            'employees' => $employees,
            'shifts' => $shiftsGrouped,
        ]);
    }

// In your LessonPlanController.php

public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'nullable|string|max:255',
        'plan_level' => 'required|in:day,week',
        'category_id' => 'nullable|exists:categories,id',
        'department_id' => 'nullable|exists:departments,id',
        'course_type' => 'nullable|string',
        'sub_type' => 'nullable|string',
        'academic_year' => 'nullable|string',
        'subject_id' => 'nullable|exists:subjects,id',
        'teacher_id' => 'nullable|exists:employees,id',
        'month' => 'nullable|string',
        'year' => 'nullable|string',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
        'working_days' => 'integer|min:0',
        'topics_count' => 'integer|min:0',
        'syllabus_data' => 'nullable|array',
        'working_days_list' => 'nullable|array',
        'distributed_topics' => 'nullable|array',
        'topics' => 'required|array|min:1',
        'topics.*.title' => 'required|string',
        'topics.*.video' => 'nullable|url',
        'topics.*.resources' => 'nullable|array',
        'topics.*.files' => 'nullable|array',
    ]);

    DB::beginTransaction();

    try {
        // Create Lesson Plan
        $lessonPlan = LessonPlan::create([
            'title' => $validated['title'] ?? null,
            'plan_level' => $validated['plan_level'],
            'status' => $request->input('submit_type') === 'submit' ? 'pending' : 'draft',
            'category_id' => $validated['category_id'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
            'course_type' => $validated['course_type'] ?? null,
            'sub_type' => $validated['sub_type'] ?? null,
            'academic_year' => $validated['academic_year'] ?? null,
            'subject_id' => $validated['subject_id'] ?? null,
            'teacher_id' => $validated['teacher_id'] ?? null,
            'month' => $validated['month'] ?? null,
            'year' => $validated['year'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'working_days' => $validated['working_days'] ?? 0,
            'topics_count' => count($validated['topics']),
            'syllabus_data' => $validated['syllabus_data'] ?? null,
            'working_days_list' => $validated['working_days_list'] ?? null,
            'distributed_topics' => $validated['distributed_topics'] ?? null,
        ]);

        // Create Topics
        foreach ($validated['topics'] as $index => $topicData) {
            LessonTopic::create([
                'lesson_plan_id' => $lessonPlan->id,
                'topic_number' => $index + 1,
                'title' => $topicData['title'],
                'description' => $topicData['description'] ?? null,
                'video_url' => $topicData['video'] ?? null,
                'resources' => $topicData['resources'] ?? [],
                'files' => $topicData['files'] ?? [],
                'scheduled_date' => $topicData['scheduled_date'] ?? null,
                'weekday' => $topicData['weekday'] ?? null,
                'time_slot' => $topicData['time_slot'] ?? null,
                'week_info' => $topicData['week_info'] ?? null,
                'distribution_data' => $topicData['distribution_data'] ?? null,
                'covered' => false,
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Lesson plan saved successfully!',
            'data' => $lessonPlan->load('topics')
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error saving lesson plan: ' . $e->getMessage()
        ], 500);
    }
}
}
