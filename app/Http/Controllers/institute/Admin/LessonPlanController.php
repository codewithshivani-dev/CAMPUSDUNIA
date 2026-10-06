<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\LessonPlan;
use App\Models\LessonTopic;
use App\Models\SubjectsCoursewise;
use App\Models\EmployeeDetails;
use App\Models\StudentParentDetails;
use App\Models\LessonTopicMediaView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LessonPlanController extends Controller     
{
    use \App\Traits\InstituteBranchAccess;

    public function studentDetail(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        $employee = $this->plannerEmployee($request, $context['institute_id']);
        abort_unless($employee, 404, 'Employee record not found.');

        $courseParts = explode(':', (string) $request->query('course'), 2);
        $courseDetailId = $courseParts[0] ?? null;
        $subjectId = $courseParts[1] ?? null;

        $assignment = DB::table('assign_subjects_to_employee as ase')
            ->join('subjects_coursewise as sc', 'ase.subject_id', '=', 'sc.subject_id')
            ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
            ->leftJoin('departments as d', 'ase.department_id', '=', 'd.department_id')
            ->where('ase.employee_id', $employee->employee_id)
            ->where('ase.department_id', $request->query('department'))
            ->where('ase.course_detail_id', $courseDetailId)
            ->where('ase.subject_id', $subjectId)
            ->whereRaw('LOWER(ase.status) = ?', ['active'])
            ->select('ase.*', 'sc.subject_name', 'pd.course_type', 'pd.sub_type', 'd.department as department_name')
            ->first();

        abort_unless($assignment, 404, 'Assigned subject not found.');

        $student = DB::table('academic_transport_details as atd')
            ->join('student_parent_details as spd', 'atd.student_hash_id', '=', 'spd.student_hash_id')
            ->where('atd.student_hash_id', $request->query('student_id'))
            ->where('atd.course_type', $assignment->course_type)
            ->where('atd.course_subtype', $assignment->sub_type)
            ->where('spd.institute_id', $employee->institute_id)
            ->when($assignment->semester_id && $assignment->semester_id !== 'all_semesters', fn ($query) => $query->where('atd.semester_id', $assignment->semester_id))
            ->when($assignment->section_id && $assignment->section_id !== 'all', fn ($query) => $query->where('atd.section_id', $assignment->section_id))
            ->select('spd.student_hash_id', 'spd.registration_number', 'spd.first_name', 'spd.middle_name', 'spd.last_name', 'spd.email')
            ->first();

        abort_unless($student, 404, 'Student not found in this assignment.');

        $plans = LessonPlan::with('topics')
            ->where('subject_id', $assignment->subject_id)
            ->where(function ($query) use ($assignment) {
                $query->where('teacher_id', $assignment->employee_id)->orWhereNull('teacher_id');
            })
            ->where(function ($query) use ($assignment) {
                $query->where('course_type', $assignment->course_detail_id)
                    ->orWhere('course_type', $assignment->course_type)
                    ->orWhereNull('course_type');
            })
            ->get();

        $year = (int) now()->format('Y');
        $topics = $plans->flatMap->topics->filter(fn ($topic) => $topic->scheduled_date && Carbon::parse($topic->scheduled_date)->year === $year);
        $attendance = DB::table('student_attendance')
            ->where('emp_assign_subject_id', $assignment->emp_assign_subject_id)
            ->where('student_hash_id', $student->student_hash_id)
            ->get()->keyBy('date');
        $lectures = DB::table('employee_subject_lectures as esl')
            ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->where('ase.employee_id', $employee->employee_id)
            ->where('ase.course_detail_id', $assignment->course_detail_id)
            ->where('ase.subject_id', $assignment->subject_id)
            ->where('esl.emp_assign_subject_id', $assignment->emp_assign_subject_id)
            ->select('esl.*')
            ->get();
        $views = DB::table('lesson_topic_media_views')
            ->where('student_hash_id', $student->student_hash_id)
            ->whereIn('lesson_topic_id', $topics->pluck('id')->all())
            ->get()->keyBy(fn ($view) => $view->lesson_topic_id . ':' . $view->media_type . ':' . $view->media_url);
        $studentData = [
            'name' => trim($student->first_name . ' ' . ($student->middle_name ?: '') . ' ' . $student->last_name),
            'regNo' => $student->registration_number,
            'email' => $student->email ?? '',
        ];
        $attendanceStatuses = $attendance->map(fn ($record) => strtolower($record->status))->all();

        $months = [];
        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create($year, $month, 1);
            $days = [];
            while ($date->month === $month) {
                $dateKey = $date->format('Y-m-d');
                $dayTopics = $topics->filter(fn ($topic) => Carbon::parse($topic->scheduled_date)->format('Y-m-d') === $dateKey);
                $classScheduled = $this->isLectureScheduled($date, $lectures);
                $materials = $dayTopics
                    ->flatMap(function ($topic) use ($views) {
                        $items = [];
                        $videoUrls = array_values(array_filter(array_map('trim', explode(',', (string) $topic->video_url))));
                        foreach ($videoUrls as $videoIndex => $videoUrl) {
                            $view = $views->get($topic->id . ':video:' . $videoUrl);
                            $items[] = ['id' => $topic->id . '-video-' . $videoIndex, 'title' => 'YouTube Video ' . ($videoIndex + 1), 'type' => 'video', 'url' => $videoUrl, 'viewed' => (bool) $view, 'clicks' => (int) ($view->opened_count ?? 0)];
                        }
                        foreach ((array) $topic->files as $file) {
                            $url = is_array($file) ? ($file['url'] ?? $file['path'] ?? '') : $file;
                            if ($url) {
                                $view = $views->get($topic->id . ':file:' . $url);
                                $items[] = ['id' => $topic->id . '-file-' . md5($url), 'title' => is_array($file) ? ($file['name'] ?? 'Lesson File') : basename($url), 'type' => 'link', 'url' => $url, 'viewed' => (bool) $view, 'clicks' => (int) ($view->opened_count ?? 0)];
                            }
                        }
                        return $items;
                    })->values()->all();
                $days[] = ['date' => $dateKey, 'day' => $date->day, 'shortDay' => $date->format('D'), 'isWeekend' => $date->isWeekend(), 'classScheduled' => $classScheduled, 'topicTitles' => $dayTopics->map(fn ($topic) => $topic->title ?: 'Scheduled Lesson')->values()->all(), 'materials' => $materials];
                $date->addDay();
            }
            $months[$month] = $days;
        }

        return view('instituteAdmin.Lesson-planner.studentDetail', compact('studentData', 'assignment', 'attendanceStatuses', 'months', 'year'));
    }

    private function isLectureScheduled(Carbon $date, $lectures): bool
    {
        return $lectures->contains(function ($lecture) use ($date) {
            $validFrom = Carbon::parse($lecture->valid_from);
            $validTo = $lecture->valid_to ? Carbon::parse($lecture->valid_to) : null;

            if ($date->lt($validFrom) || ($validTo && $date->gt($validTo))) {
                return false;
            }

            $weekdays = array_map('strtolower', array_map('strval', (array) json_decode($lecture->days_of_week ?: '[]', true)));
            $dayNames = [strtolower($date->format('D')), strtolower($date->format('l')), (string) $date->dayOfWeek];

            return match (strtolower($lecture->frequency)) {
                'one_time' => $date->isSameDay($validFrom),
                'daily' => true,
                'weekly' => (bool) array_intersect($dayNames, $weekdays),
                'monthly' => (int) $date->day === (int) $lecture->day_of_month,
                default => false,
            };
        });
    }

    public function review(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        $employee = $this->plannerEmployee($request, $context['institute_id']);

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee record not found.');
        }

        $assignments = DB::table('assign_subjects_to_employee as ase')
            ->join('subjects_coursewise as sc', 'ase.subject_id', '=', 'sc.subject_id')
            ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
            ->leftJoin('departments as d', 'ase.department_id', '=', 'd.department_id')
            ->where('ase.employee_id', $employee->employee_id)
            ->whereRaw('LOWER(ase.status) = ?', ['active'])
            ->select('ase.*', 'sc.subject_name', 'pd.course_type', 'pd.sub_type', 'd.department as department_name')
            ->get();

        $reviewData = [];
        $year = (int) now()->format('Y');

        foreach ($assignments as $assignment) {
            $plans = LessonPlan::with('topics')
                ->where('subject_id', $assignment->subject_id)
                ->where(function ($query) use ($assignment) {
                    $query->where('teacher_id', $assignment->employee_id)
                        ->orWhereNull('teacher_id');
                })
                ->where(function ($query) use ($assignment) {
                    $query->where('course_type', $assignment->course_detail_id)
                        ->orWhere('course_type', $assignment->course_type)
                        ->orWhereNull('course_type');
                })
                ->get();

            $studentQuery = DB::table('academic_transport_details as atd')
                ->join('student_parent_details as spd', 'atd.student_hash_id', '=', 'spd.student_hash_id')
                ->join('course_fee_structures as cfs', function ($join) use ($employee) {
                    $join->on('atd.academic_year_id', '=', 'cfs.academic_year_id')
                        ->where('cfs.institute_id', $employee->institute_id);
                })
                ->where('cfs.product_id', $assignment->course_detail_id)
                ->where('atd.course_type', $assignment->course_type)
                ->where('atd.course_subtype', $assignment->sub_type)
                ->where('spd.institute_id', $employee->institute_id);

            if ($assignment->semester_id && $assignment->semester_id !== 'all_semesters') {
                $studentQuery->where('atd.semester_id', $assignment->semester_id);
            }
            if (!empty($assignment->section_id) && $assignment->section_id !== 'all') {
                $studentQuery->where('atd.section_id', $assignment->section_id);
            }

            $students = $studentQuery->select(
                'spd.student_hash_id', 'spd.registration_number', 'spd.first_name',
                'spd.middle_name', 'spd.last_name', 'spd.email', 'atd.section_id'
            )->orderBy('spd.registration_number')->get();

            $attendance = DB::table('student_attendance')
                ->where('emp_assign_subject_id', $assignment->emp_assign_subject_id)
                ->select('student_hash_id', 'date', 'status')
                ->get()
                ->groupBy('student_hash_id');

            $topics = $plans->flatMap->topics->filter(function ($topic) use ($year) {
                return $topic->scheduled_date && Carbon::parse($topic->scheduled_date)->year === $year;
            });
            $lectures = DB::table('employee_subject_lectures as esl')
                ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
                ->where('ase.employee_id', $employee->employee_id)
                ->where('ase.course_detail_id', $assignment->course_detail_id)
                ->where('ase.subject_id', $assignment->subject_id)
                ->where('esl.emp_assign_subject_id', $assignment->emp_assign_subject_id)
                ->select('esl.*')
                ->get();
            $topicIds = $topics->pluck('id')->all();
            $views = DB::table('lesson_topic_media_views')
                ->whereIn('lesson_topic_id', $topicIds)
                ->get()
                ->groupBy(function ($view) {
                    return $view->student_hash_id . ':' . $view->lesson_topic_id . ':' . $view->media_type . ':' . $view->media_url;
                });

            $months = [];
            for ($month = 1; $month <= 12; $month++) {
                $days = [];
                $date = Carbon::create($year, $month, 1);
                while ($date->month === $month) {
                    $dateKey = $date->format('Y-m-d');
                    $dayTopics = $topics->filter(fn ($topic) => Carbon::parse($topic->scheduled_date)->format('Y-m-d') === $dateKey);
                    $classScheduled = $this->isLectureScheduled($date, $lectures);
                    $materials = $dayTopics->flatMap(function ($topic) use ($assignment, $views) {
                        $items = [];
                        $videoUrls = array_values(array_filter(array_map('trim', explode(',', (string) $topic->video_url))));
                        foreach ($videoUrls as $videoIndex => $videoUrl) {
                            $key = $topic->id . ':video:' . $videoUrl;
                            $items[] = ['id' => $topic->id . '-video-' . $videoIndex, 'title' => 'YouTube Video ' . ($videoIndex + 1), 'type' => 'video', 'url' => $videoUrl, 'viewKey' => $key];
                        }
                        foreach ((array) $topic->files as $file) {
                            $url = is_array($file) ? ($file['url'] ?? $file['path'] ?? '') : $file;
                            if ($url) {
                                $key = $topic->id . ':file:' . $url;
                                $items[] = ['id' => $topic->id . '-file-' . md5($url), 'title' => is_array($file) ? ($file['name'] ?? 'Lesson File') : basename($url), 'type' => 'link', 'url' => $url, 'viewKey' => $key];
                            }
                        }
                        return $items;
                    })->values()->all();
                    $days[] = ['date' => $dateKey, 'day' => $date->day, 'month' => $month, 'monthName' => $date->format('F'), 'dayOfWeek' => $date->dayOfWeek, 'shortDay' => $date->format('D'), 'classScheduled' => $classScheduled, 'topicTitles' => $dayTopics->map(fn ($topic) => $topic->title ?: 'Scheduled Lesson')->values()->all(), 'materials' => $materials];
                    $date->addDay();
                }
                $months[$month] = $days;
            }

            $studentData = $students->map(function ($student) use ($months, $attendance, $views) {
                $studentAttendance = $attendance->get($student->student_hash_id, collect())->keyBy('date');
                $result = ['id' => $student->student_hash_id, 'name' => trim($student->first_name . ' ' . ($student->middle_name ?: '') . ' ' . $student->last_name), 'email' => $student->email ?? '', 'regNo' => $student->registration_number];
                foreach ($months as $month => $days) {
                    foreach ($days as $day) {
                        $dayMaterials = collect($day['materials'])->map(function ($material) use ($student, $views) {
                            $view = $views->get($student->student_hash_id . ':' . $material['viewKey'])?->first();
                            $material['viewed'] = (bool) $view;
                            $material['clicks'] = (int) ($view->opened_count ?? 0);
                            unset($material['viewKey']);
                            return $material;
                        })->values()->all();
                        $status = $studentAttendance->get($day['date'])->status ?? 'absent';
                        $result["day_{$month}_{$day['day']}"] = ['status' => $day['classScheduled'] ? strtolower($status) : 'no_class', 'materials' => $dayMaterials, 'viewedCount' => collect($dayMaterials)->where('viewed', true)->count(), 'totalCount' => count($dayMaterials)];
                    }
                }
                return $result;
            })->values()->all();

            $department = $assignment->department_id ?: 'unassigned';
            $course = $assignment->course_detail_id ?: ($assignment->course_type ?: 'unassigned');
            $reviewData[$department]['department_name'] = $assignment->department_name ?: 'Unassigned Department';
            $reviewData[$department]['courses'][$course . ':' . $assignment->subject_id] = [
                'course' => $assignment->course_type ?: $assignment->course_detail_id,
                'subject' => $assignment->subject_name,
                'students' => $studentData,
                'months' => $months,
            ];
        }

        return view('instituteAdmin.Lesson-planner.review', compact('reviewData', 'year'));
    }

    public function reports(Request $request)
    {
        $rows = $this->teacherReportRows($request);
        $monthOrder = collect(range(1, 12))->map(fn ($month) => Carbon::create()->month($month)->format('F'));

        return view('instituteAdmin.Lesson-planner.reports', [
            'reportData' => $rows,
            'reportSubjects' => collect($rows)->pluck('subject')->filter()->unique()->sort()->values(),
            'reportClasses' => collect($rows)->pluck('class')->filter()->unique()->sort()->values(),
            'reportMonths' => collect($rows)->pluck('month')->filter()->unique()->sortBy(function ($month) use ($monthOrder) {
                $position = $monthOrder->search($month);
                return $position === false ? 99 : $position;
            })->values(),
        ]);
    }

    public function exportReportsCsv(Request $request)
    {
        $rows = $this->filterReportRows($this->teacherReportRows($request), $request);
        $filename = 'lesson-planner-report-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Class', 'Subject', 'Month', 'Plan', 'Plan Type', 'Coverage', 'Covered Topics', 'Total Topics']);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row['class'], $row['subject'], $row['month'], $row['title'], $row['plan_type'],
                    $row['coverage'] . '%', $row['covered_topics'], $row['total_topics'],
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportReportsPdf(Request $request)
    {
        $rows = $this->filterReportRows($this->teacherReportRows($request), $request);

        return Pdf::loadView('instituteAdmin.Lesson-planner.report-pdf', [
            'rows' => $rows,
            'generatedAt' => now()->format('d M Y, h:i A'),
        ])->download('lesson-planner-report-' . now()->format('Y-m-d') . '.pdf');
    }

    private function teacherReportRows(Request $request): array
    {
        $context = $this->getInstituteBranchContext();
        $employee = $this->plannerEmployee($request, $context['institute_id']);

        if (!$employee) {
            return [];
        }

        $assignments = DB::table('assign_subjects_to_employee as ase')
            ->join('subjects_coursewise as sc', 'ase.subject_id', '=', 'sc.subject_id')
            ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
            ->where('ase.employee_id', $employee->employee_id)
            ->whereRaw('LOWER(ase.status) = ?', ['active'])
            ->select('ase.subject_id', 'ase.employee_id', 'ase.course_detail_id', 'pd.course_type', 'sc.subject_name')
            ->get();

        $rows = [];
        foreach ($assignments as $assignment) {
            $plans = LessonPlan::with('topics')
                ->where('subject_id', $assignment->subject_id)
                ->where(function ($query) use ($assignment) {
                    $query->where('teacher_id', $assignment->employee_id)->orWhereNull('teacher_id');
                })
                ->where(function ($query) use ($assignment) {
                    $query->where('course_type', $assignment->course_detail_id)
                        ->orWhere('course_type', $assignment->course_type)
                        ->orWhereNull('course_type');
                })
                ->get();

            foreach ($plans as $plan) {
                $topics = $plan->topics;
                $coveredTopics = $topics->filter(fn ($topic) => $topic->covered || $topic->coverage_status === 'covered')->count();
                $partialTopics = $topics->where('coverage_status', 'partial')->count();
                $coverage = $topics->count() > 0
                    ? (int) round((($coveredTopics + ($partialTopics * 0.5)) / $topics->count()) * 100)
                    : 0;

                $rows[$plan->id] = [
                    'class' => $assignment->course_type ?: 'All Classes',
                    'subject' => $assignment->subject_name ?: 'General',
                    'month' => $plan->month ?: ($plan->start_date ? Carbon::parse($plan->start_date)->format('F') : 'Unknown'),
                    'title' => $plan->title ?: 'Untitled Plan',
                    'plan_type' => $plan->plan_level === 'week' ? 'Weekly' : 'Monthly',
                    'coverage' => $coverage,
                    'covered_topics' => $coveredTopics,
                    'partial_topics' => $partialTopics,
                    'not_covered_topics' => max(0, $topics->count() - $coveredTopics - $partialTopics),
                    'total_topics' => $topics->count(),
                ];
            }
        }

        return array_values($rows);
    }

    private function filterReportRows(array $rows, Request $request): array
    {
        return array_values(array_filter($rows, function ($row) use ($request) {
            return (!$request->filled('class') || $row['class'] === $request->input('class'))
                && (!$request->filled('subject') || $row['subject'] === $request->input('subject'))
                && (!$request->filled('month') || $row['month'] === $request->input('month'));
        }));
    }

    public function studentPlans()
    {
        return view('instituteAdmin.student.Lesson-planner.plans', [
            'plans' => $this->getStudentPlans(),
        ]);
    }

public function studentDetails()
{
    $subjectId = request()->query('subject');
    if (!$subjectId) {
        return redirect()->route('student.lesson-planner.performance');
    }
    
    $student = StudentParentDetails::with('academicTransportDetails.departmentCategory')
        ->where('user_id', Auth::id())
        ->firstOrFail();
    
    $courseType = optional($student->academicTransportDetails)->course_type;
    $courseSubtype = optional($student->academicTransportDetails)->course_subtype;
    $year = (int) now()->format('Y');
    
    // Get the specific assignment for this student and subject
    $assignment = DB::table('assign_subjects_to_employee as ase')
        ->join('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
        ->join('academic_transport_details as atd', function ($join) use ($student) {
            $join->on('atd.course_type', '=', 'pd.course_type')
                ->on('atd.course_subtype', '=', 'pd.sub_type')
                ->where('atd.student_hash_id', $student->student_hash_id);
        })
        ->where('ase.subject_id', $subjectId)
        ->where('pd.course_type', $courseType)
        ->where('pd.sub_type', $courseSubtype)
        ->whereRaw('LOWER(ase.status) = ?', ['active'])
        ->when($student->academicTransportDetails->semester_id ?? null, function ($query, $semesterId) {
            $query->where(function ($query) use ($semesterId) {
                $query->where('ase.semester_id', $semesterId)
                    ->orWhere('ase.semester_id', 'all_semesters');
            });
        })
        ->when($student->academicTransportDetails->section_id ?? null, function ($query, $sectionId) {
            $query->where(function ($query) use ($sectionId) {
                $query->where('ase.section_id', $sectionId)
                    ->orWhere('ase.section_id', 'all');
            });
        })
        ->select('ase.*', 'pd.sub_type')
        ->first();
    
    // If no assignment found, show empty state
    if (!$assignment) {
        return view('instituteAdmin.student.Lesson-planner.student-details', [
            'student' => $student,
            'courseType' => $courseType,
            'subject' => SubjectsCoursewise::where('subject_id', $subjectId)->first(),
            'months' => [],
            'year' => $year,
            'noAssignment' => true,
        ]);
    }
    
    // Get lesson plans for this subject
    $plans = LessonPlan::with('topics')
        ->where('subject_id', $subjectId)
        ->where(function ($query) use ($courseType) {
            $query->where('course_type', $courseType)
                ->orWhereNull('course_type');
        })
        ->get();
    
    $topicIds = $plans->flatMap->topics->pluck('id')->all();
    
    // Get attendance for this specific assignment
    $attendance = DB::table('student_attendance as sa')
        ->where('sa.student_hash_id', $student->student_hash_id)
        ->where('sa.emp_assign_subject_id', $assignment->emp_assign_subject_id)
        ->get()
        ->groupBy('date');
    
    // Get lectures for this specific assignment
    $lectures = DB::table('employee_subject_lectures as esl')
        ->where('esl.emp_assign_subject_id', $assignment->emp_assign_subject_id)
        ->select('esl.*')
        ->get();
    
    // Get views for materials
    $views = DB::table('lesson_topic_media_views')
        ->where('student_hash_id', $student->student_hash_id)
        ->whereIn('lesson_topic_id', $topicIds ?: [0])
        ->get()
        ->keyBy(fn ($view) => $view->lesson_topic_id . ':' . $view->media_type . ':' . $view->media_url);
    
    $months = [];
    for ($month = 1; $month <= 12; $month++) {
        $days = [];
        $date = Carbon::create($year, $month, 1);
        while ($date->month === $month) {
            $dateKey = $date->format('Y-m-d');
            
            // Get topics for this day
            $dayTopics = $plans->flatMap->topics->filter(function ($topic) use ($dateKey) {
                return $topic->scheduled_date && Carbon::parse($topic->scheduled_date)->format('Y-m-d') === $dateKey;
            });
            
            // Check if lecture is scheduled
            $scheduled = !$date->isWeekend() && $this->isLectureScheduled($date, $lectures);
            
            // Get attendance status for this specific date
            $attendanceRecords = $attendance->get($dateKey, collect());
            $isPresent = $attendanceRecords->contains(function ($record) {
                return strtolower($record->status) === 'present';
            });
            $isAbsent = $attendanceRecords->contains(function ($record) {
                return strtolower($record->status) === 'absent';
            });
            
            $status = $scheduled
                ? ($isPresent ? 'present' : ($isAbsent ? 'absent' : 'na'))
                : 'no_class';
            
            // Build materials
            $materials = $dayTopics->flatMap(function ($topic) use ($views) {
                $items = [];
                
                // Video materials
                $videoUrls = array_values(array_filter(array_map('trim', explode(',', (string) $topic->video_url))));
                foreach ($videoUrls as $index => $url) {
                    $view = $views->get($topic->id . ':video:' . $url);
                    $items[] = [
                        'title' => 'YouTube Video ' . ($index + 1),
                        'type' => 'video',
                        'url' => $url,
                        'viewed' => (bool) $view,
                        'clicks' => (int) ($view->opened_count ?? 0)
                    ];
                }
                
                // File materials
                foreach ((array) $topic->files as $file) {
                    $url = is_array($file) ? ($file['url'] ?? $file['path'] ?? '') : $file;
                    if ($url) {
                        $view = $views->get($topic->id . ':file:' . $url);
                        $items[] = [
                            'title' => is_array($file) ? ($file['name'] ?? 'Lesson File') : basename($url),
                            'type' => 'link',
                            'url' => $url,
                            'viewed' => (bool) $view,
                            'clicks' => (int) ($view->opened_count ?? 0)
                        ];
                    }
                }
                
                return $items;
            })->values()->all();
            
            // Topic titles
            $topicTitles = $dayTopics->map(function ($topic) {
                return $topic->title ?: 'Scheduled Lesson';
            })->values()->all();
            
            $days[] = [
                'date' => $dateKey,
                'day' => $date->day,
                'shortDay' => $date->format('D'),
                'status' => $status,
                'scheduled' => $scheduled,
                'topics' => $topicTitles,
                'materials' => $materials,
                'isWeekend' => $date->isWeekend(),
            ];
            
            $date->addDay();
        }
        $months[$month] = $days;
    }
    
    $subject = SubjectsCoursewise::where('subject_id', $subjectId)->first();
    
    return view('instituteAdmin.student.Lesson-planner.student-details', compact('student', 'courseType', 'subject', 'months', 'year'));
}

    public function studentPerformance()
    {
        $month = max(1, min(12, (int) request()->query('month', now()->month)));
        $subjectFilter = request()->query('subject');
        $student = StudentParentDetails::with('academicTransportDetails.departmentCategory')
            ->where('user_id', Auth::id())
            ->first();
        $courseType = optional($student ? $student->academicTransportDetails : null)->course_type;
        $departmentName = optional(optional($student ? $student->academicTransportDetails : null)->departmentCategory)->category_name;

        if (!$student || !$courseType) {
            return view('instituteAdmin.student.Lesson-planner.performance', [
                'student' => null,
                'className' => null,
                'departmentName' => null,
                'teacherNames' => [],
                'performanceData' => [],
                'subjects' => [],
                'month' => $month,
                'subjectFilter' => $subjectFilter,
                'year' => (int) now()->format('Y'),
            ]);
        }

        $plans = LessonPlan::with('topics')
            ->where(function ($query) use ($courseType) {
                $query->where('course_type', $courseType)->orWhereNull('course_type');
            })
            ->get();
        $year = (int) now()->format('Y');
        $teacherNames = DB::table('assign_subjects_to_employee as ase')
            ->join('employee_details as ed', 'ase.employee_id', '=', 'ed.employee_id')
            ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
            ->where('pd.course_type', $courseType)
            ->whereRaw('LOWER(ase.status) = ?', ['active'])
            ->whereNotNull('ed.name')
            ->distinct()
            ->pluck('ed.name')
            ->values()
            ->all();
        $topicIds = $plans->flatMap->topics->pluck('id')->all();
        $views = DB::table('lesson_topic_media_views')
            ->where('student_hash_id', $student->student_hash_id)
            ->whereIn('lesson_topic_id', $topicIds ?: [0])
            ->get()
            ->groupBy(function ($view) {
                return $view->lesson_topic_id . ':' . $view->media_type . ':' . $view->media_url;
            });
        $attendance = DB::table('student_attendance as sa')
            ->join('assign_subjects_to_employee as ase', 'sa.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
            ->where('sa.student_hash_id', $student->student_hash_id)
            ->where('pd.course_type', $courseType)
            ->select('ase.subject_id', 'sa.date', 'sa.status')
            ->get()
            ->groupBy('subject_id');
        $lectures = DB::table('employee_subject_lectures as esl')
            ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
            ->where('pd.course_type', $courseType)
            ->select('ase.subject_id', 'esl.*')
            ->get()
            ->groupBy('subject_id');

        $subjects = $plans->groupBy('subject_id')->keys()->mapWithKeys(function ($subjectId) {
            $subject = SubjectsCoursewise::where('subject_id', $subjectId)->first();
            return [$subjectId => $subject->subject_name ?? 'General'];
        });
        $performanceData = $plans->groupBy('subject_id')->map(function ($subjectPlans, $subjectId) use ($views, $attendance, $lectures, $year, $month) {
            $topics = $subjectPlans->flatMap->topics->filter(function ($topic) use ($month, $year) {
                return $topic->scheduled_date
                    && Carbon::parse($topic->scheduled_date)->year === $year
                    && Carbon::parse($topic->scheduled_date)->month === $month;
            });
            $materials = $topics->flatMap(function ($topic) {
                $items = array_map(fn ($url) => ['topic_id' => $topic->id, 'type' => 'video', 'url' => $url], array_values(array_filter(array_map('trim', explode(',', (string) $topic->video_url)))));
                foreach ((array) $topic->files as $file) {
                    $url = is_array($file) ? ($file['url'] ?? $file['path'] ?? '') : $file;
                    if ($url) {
                        $items[] = ['topic_id' => $topic->id, 'type' => 'file', 'url' => $url];
                    }
                }
                return $items;
            });
            $viewedMaterials = $materials->filter(function ($material) use ($views) {
                return $views->has($material['topic_id'] . ':' . $material['type'] . ':' . $material['url']);
            })->count();
            $attendanceByDate = $attendance->get($subjectId, collect())->groupBy('date');
            $subjectLectures = $lectures->get($subjectId, collect());
            $scheduledDates = collect();
            $date = Carbon::create($year, $month, 1);
            while ($date->month === $month) {
                if (!$date->isWeekend() && $this->isLectureScheduled($date, $subjectLectures)) {
                    $scheduledDates->push($date->format('Y-m-d'));
                }
                $date->addDay();
            }
            $present = $scheduledDates->filter(function ($date) use ($attendanceByDate) {
                return $attendanceByDate->get($date, collect())->contains(fn ($record) => strtolower($record->status) === 'present');
            })->count();
            $attendanceTotal = $scheduledDates->count();
            $subject = SubjectsCoursewise::where('subject_id', $subjectId)->first();

            return [
                'subject_id' => $subjectId,
                'subject' => $subject->subject_name ?? 'General',
                'plans' => $subjectPlans->count(),
                'topics' => $topics->count(),
                'covered_topics' => $topics->where('covered', true)->count(),
                'materials' => $materials->count(),
                'viewed_materials' => min($viewedMaterials, $materials->count()),
                'present' => $present,
                'attendance_total' => $attendanceTotal,
            ];
        })->when($subjectFilter, function ($data) use ($subjectFilter) {
            return $data->filter(fn ($item, $subjectId) => (string) $subjectId === (string) $subjectFilter);
        })->values()->all();

        return view('instituteAdmin.student.Lesson-planner.performance', [
            'student' => $student,
            'className' => $courseType,
            'departmentName' => $departmentName,
            'teacherNames' => $teacherNames,
            'performanceData' => $performanceData,
            'subjects' => $subjects,
            'month' => $month,
            'subjectFilter' => $subjectFilter,
            'year' => $year,
        ]);
    }

    public function getStudentPlans(): array
    {
        $student = StudentParentDetails::with('academicTransportDetails')
            ->where('user_id', Auth::id())
            ->first();

        $courseType = optional($student ? $student->academicTransportDetails : null)->course_type;
        if (!$student || !$courseType) {
            return [];
        }

        $plans = LessonPlan::with('topics')
            ->where('course_type', $courseType)
            ->where('status', 'active')
            ->orderBy('start_date')
            ->get();

        return $plans->map(function ($plan) use ($courseType) {
            $subject = $plan->subject_id
                ? SubjectsCoursewise::where('subject_id', $plan->subject_id)->first()
                : null;
            $dailyTopics = [];

            foreach ($plan->topics as $topic) {
                $date = $topic->scheduled_date ?: $plan->start_date;
                if (!$date) {
                    continue;
                }

                $dateKey = \Carbon\Carbon::parse($date)->format('Y-m-d');
                $dailyTopics[$dateKey][] = [
                    'id' => $topic->id,
                    'number' => (int) ($topic->topic_number ?: 1),
                    'title' => $topic->title,
                    'topics' => $topic->topics,
                    'description' => $topic->description,
                    'video' => $topic->video_url,
                    'files' => is_array($topic->files) ? $topic->files : [],
                    'resources' => is_array($topic->resources) ? $topic->resources : [],
                    'covered' => (bool) $topic->covered,
                    'coverage_status' => $topic->coverage_status ?: ($topic->covered ? 'covered' : 'not_covered'),
                    'weekday_info' => $topic->weekday,
                    'week_info' => $topic->week_info,
                ];
            }

            return [
                'id' => $plan->id,
                'title' => $plan->title,
                'class' => $courseType,
                'subject' => $subject->subject_name ?? $courseType,
                'month' => $plan->month ?: ($plan->start_date ? \Carbon\Carbon::parse($plan->start_date)->format('F') : 'Monthly'),
                'year' => $plan->year ?: ($plan->start_date ? \Carbon\Carbon::parse($plan->start_date)->format('Y') : date('Y')),
                'week' => $plan->plan_level === 'week' ? 'Weekly Plan' : 'Monthly Plan',
                'plan_level' => $plan->plan_level,
                'start_date' => $plan->start_date ? \Carbon\Carbon::parse($plan->start_date)->format('Y-m-d') : null,
                'end_date' => $plan->end_date ? \Carbon\Carbon::parse($plan->end_date)->format('Y-m-d') : null,
                'dailyTopics' => $dailyTopics,
            ];
        })->values()->all();
    }

    private function firstTopicFileUrl(LessonTopic $topic): ?string
    {
        $files = is_array($topic->files) ? $topic->files : [];
        $file = $files[0] ?? null;
        if (is_string($file)) {
            return $file;
        }
        return is_array($file) ? ($file['url'] ?? null) : null;
    }

    public function recordStudentMediaView(Request $request)
    {
        $validated = $request->validate([
            'lesson_topic_id' => 'required|integer|exists:lesson_topics,id',
            'media_type' => 'required|in:video,file',
            'media_url' => 'required|string|max:2048',
            'media_name' => 'nullable|string|max:255',
        ]);

        $student = StudentParentDetails::with('academicTransportDetails')
            ->where('user_id', Auth::id())
            ->first();
        $courseType = optional($student ? $student->academicTransportDetails : null)->course_type;

        $topic = LessonTopic::with('lessonPlan')->findOrFail($validated['lesson_topic_id']);
        if (!$student || !$courseType || $topic->lessonPlan->course_type !== $courseType) {
            abort(403);
        }

        $view = LessonTopicMediaView::firstOrNew([
            'student_hash_id' => $student->student_hash_id,
            'lesson_topic_id' => $topic->id,
            'media_type' => $validated['media_type'],
            'media_url' => $validated['media_url'],
        ]);
        $view->user_id = Auth::id();
        $view->media_name = $validated['media_name'] ?? $view->media_name;
        $view->opened_count = ((int) $view->opened_count) + 1;
        $view->first_opened_at = $view->first_opened_at ?: now();
        $view->last_opened_at = now();
        $view->save();

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Basic info
            'title' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:department_categories,department_category_id',
            'department_id' => 'nullable|exists:departments,department_id',
            'course_type' => 'nullable|string|max:255',
            'course_sub_type' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:50',
            'subject_id' => 'nullable|exists:subjects_coursewise,subject_id',
            'teacher_id' => 'nullable|exists:employee_details,employee_id',
            
            // Plan settings
            'plan_level' => 'required|in:day,week',
            'plan_type_label' => 'nullable|string|max:255',
            'month' => 'nullable|string|max:50',
            'year' => 'nullable|string|max:10',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            
            // Status
            'status' => 'required|in:draft,active',
            
            // JSON data
            'syllabus_data' => 'nullable|array',
            'working_days' => 'nullable|array',
            'distributed_topics' => 'nullable|array',
            
            // Topics
            'topics' => 'required|array|min:1',
            'topics.*.title' => 'required|string|max:255',
            'topics.*.topics' => 'nullable|string|max:1000',
            'topics.*.description' => 'nullable|string',
            'topics.*.video_url' => 'nullable|string',
            'topics.*.video' => 'nullable|string',
            'topics.*.resources' => 'nullable|array',
            'topics.*.files' => 'nullable|array',
            'topics.*.files.*' => 'nullable|file|max:10240', // 10MB max per file
            'topics.*.date_info' => 'nullable|string',
            'topics.*.day_number' => 'nullable|integer',
            'topics.*.week_number' => 'nullable|integer',
            'topics.*.topic_date' => 'nullable|date',
            'topics.*.covered' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $workingDays = $validated['working_days'] ?? [];

            // 1. Create Lesson Plan
            $lessonPlan = LessonPlan::create([
                'title' => $validated['title'] ?? $this->generateTitle($validated),
                'category_id' => $validated['category_id'] ?? null,
                'department_id' => $validated['department_id'] ?? null,
                'course_type' => $validated['course_type'] ?? null,
                'sub_type' => $validated['course_sub_type'] ?? null,
                'academic_year' => $validated['academic_year'] ?? null,
                'subject_id' => $validated['subject_id'] ?? null,
                'teacher_id' => $validated['teacher_id'] ?? null,
                'plan_level' => $validated['plan_level'],
                'month' => $validated['month'] ?? null,
                'year' => $validated['year'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'status' => $validated['status'],
                'syllabus_data' => $validated['syllabus_data'] ?? null,
                'working_days' => count($workingDays),
                'working_days_list' => $workingDays,
                'distributed_topics' => $validated['distributed_topics'] ?? [],
                'topics_count' => count($validated['topics']),
            ]);

            // 2. Create Topics
            $order = 0;
            foreach ($validated['topics'] as $topicData) {
                $normalizedTopic = $this->normalizeTopicPayload($topicData);

                // Handle file uploads
                $uploadedFiles = [];
                if (!empty($normalizedTopic['files'])) {
                    foreach ($normalizedTopic['files'] as $file) {
                        if ($file instanceof \Illuminate\Http\UploadedFile) {
                            $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                            $path = $file->storeAs('lesson-topics', $fileName, 'public');
                            $uploadedFiles[] = [
                                'name' => $file->getClientOriginalName(),
                                'path' => $path,
                                'size' => $file->getSize(),
                                'type' => $file->getMimeType(),
                                'url' => Storage::url($path)
                            ];
                        }
                    }
                }

                LessonTopic::create([
                    'lesson_plan_id' => $lessonPlan->id,
                    'topic_number' => $order + 1,
                    'title' => $normalizedTopic['title'],
                    'topics' => $normalizedTopic['topics'],
                    'description' => $normalizedTopic['description'],
                    'video_url' => $normalizedTopic['video_url'],
                    'resources' => $normalizedTopic['resources'],
                    'files' => $uploadedFiles,
                    'scheduled_date' => $normalizedTopic['topic_date'],
                    'weekday' => $normalizedTopic['date_info'],
                    'week_info' => !empty($normalizedTopic['week_number']) ? 'Week ' . $normalizedTopic['week_number'] : null,
                    'covered' => $normalizedTopic['covered'],
                    'distribution_data' => [
                        'day_number' => $normalizedTopic['day_number'],
                        'week_number' => $normalizedTopic['week_number'],
                        'date_info' => $normalizedTopic['date_info'],
                    ],
                ]);
                $order++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Lesson plan saved successfully!',
                'data' => $lessonPlan->load('topics'),
                'redirect' => route('lesson-planner.plans'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save lesson plan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function downloadFile($topicId, $fileIndex)
    {
        $topic = LessonTopic::findOrFail($topicId);
        $files = $topic->files ?? [];
        
        if (!isset($files[$fileIndex])) {
            abort(404, 'File not found');
        }
        
        $file = $files[$fileIndex];
        $filePath = storage_path('app/public/' . $file['path']);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found on server');
        }
        
        return response()->download($filePath, $file['name']);
    }

    private function normalizeTopicPayload(array $topicData): array
    {
        $videoUrl = $topicData['video_url'] ?? $topicData['video'] ?? null;
        $videoUrl = is_string($videoUrl) ? trim($videoUrl) : null;

        $resources = $topicData['resources'] ?? [];
        if (is_string($resources)) {
            $resources = array_values(array_filter(array_map('trim', preg_split('/[,;\n]+/', $resources))));
        } elseif (!is_array($resources)) {
            $resources = [];
        }

        $files = $topicData['files'] ?? [];
        if (!is_array($files)) {
            $files = [];
        }

        $title = trim((string) ($topicData['title'] ?? ''));

        return [
            'title' => $title !== '' ? $title : 'Topic ' . (($topicData['day_number'] ?? 0) ?: 1),
            'topics' => $topicData['topics'] ?? null,
            'description' => $topicData['description'] ?? null,
            'video_url' => $videoUrl !== '' ? $videoUrl : null,
            'resources' => $resources,
            'files' => $files,
            'topic_date' => $topicData['topic_date'] ?? null,
            'date_info' => $topicData['date_info'] ?? null,
            'day_number' => $topicData['day_number'] ?? null,
            'week_number' => $topicData['week_number'] ?? null,
            'covered' => (bool) ($topicData['covered'] ?? false),
        ];
    }

    private function generateTitle($data)
    {
        $parts = [];
        if (!empty($data['subject_id'])) {
            $subject = \App\Models\SubjectsCoursewise::where('subject_id', $data['subject_id'])->first();
            if ($subject) $parts[] = $subject->subject_name;
        }
        if (!empty($data['course_type'])) $parts[] = $data['course_type'];
        if (!empty($data['month'])) $parts[] = $data['month'];
        if (!empty($data['year'])) $parts[] = $data['year'];
        
        return empty($parts) ? 'Lesson Plan' : implode(' - ', $parts);
    }

public function calendarData(Request $request)
{
    $context = $this->getInstituteBranchContext();
    $employee = $this->plannerEmployee($request, $context['institute_id']);

    if (!$employee) {
        return response()->json([]);
    }

    $plans = LessonPlan::with('topics')
        ->where(function ($query) use ($employee) {
            $query->where('teacher_id', $employee->employee_id)
                ->orWhereNull('teacher_id');
        })
        ->when($request->filled('status'), function ($query) use ($request) {
            $query->where('status', $request->input('status'));
        }, function ($query) {
            $query->where('status', 'active');
        })
        ->when($request->filled('class'), function ($query) use ($request) {
            $query->where('course_type', $request->input('class'));
        })
        ->when($request->filled('subject'), function ($query) use ($request) {
            $subject = SubjectsCoursewise::where('subject_name', $request->input('subject'))->first();
            $query->where('subject_id', $subject?->subject_id ?? 0);
        })
        ->when($request->filled('month'), function ($query) use ($request) {
            $month = $request->input('month');
            $query->where(function ($query) use ($month) {
                $query->where('month', $month)
                    ->orWhere(function ($query) use ($month) {
                        $query->whereNull('month')
                            ->whereMonth('start_date', Carbon::parse('1 ' . $month)->month);
                    });
            });
        })
        ->orderBy('start_date', 'asc')
        ->get()
        ->map(function ($plan) {
            $subject = $plan->subject_id ? SubjectsCoursewise::where('subject_id', $plan->subject_id)->first() : null;
            $teacher = $plan->teacher_id ? EmployeeDetails::where('employee_id', $plan->teacher_id)->first() : null;

            $dailyTopics = [];
            
            // Determine the date range for this plan
            $startDate = $plan->start_date ? \Carbon\Carbon::parse($plan->start_date) : null;
            $endDate = $plan->end_date ? \Carbon\Carbon::parse($plan->end_date) : null;
            
            // For weekly plans, we need to track which days have topics
            $topicDates = [];
            $weeklyTopicDates = [];

            if ($plan->plan_level === 'week' && is_array($plan->working_days_list)) {
                foreach ($plan->working_days_list as $workingDay) {
                    $workingDate = is_array($workingDay) ? ($workingDay['date'] ?? null) : null;
                    if (!$workingDate) {
                        continue;
                    }

                    $workingDate = \Carbon\Carbon::parse($workingDate);
                    $weekKey = $workingDate->copy()->startOfWeek()->format('Y-m-d');
                    $weeklyTopicDates[$weekKey] ??= $workingDate->format('Y-m-d');
                }
                $weeklyTopicDates = array_values($weeklyTopicDates);
            }
            
            foreach ($plan->topics as $topic) {
                $dateKey = null;

                if ($plan->plan_level === 'week' && !empty($weeklyTopicDates)) {
                    $weekNumber = $topic->distribution_data['week_number'] ?? null;
                    if (!$weekNumber && $topic->week_info && preg_match('/(\d+)/', $topic->week_info, $matches)) {
                        $weekNumber = (int) $matches[1];
                    }
                    if ($weekNumber && isset($weeklyTopicDates[$weekNumber - 1])) {
                        $dateKey = $weeklyTopicDates[$weekNumber - 1];
                    }
                }
                
                if (!$dateKey && $topic->scheduled_date) {
                    $dateKey = $topic->scheduled_date instanceof \Carbon\Carbon 
                        ? $topic->scheduled_date->format('Y-m-d')
                        : \Carbon\Carbon::parse($topic->scheduled_date)->format('Y-m-d');
                } elseif (!$dateKey && $topic->weekday && preg_match('/(\d{4}-\d{2}-\d{2})/', $topic->weekday, $matches)) {
                    $dateKey = $matches[1];
                } elseif (!$dateKey && $plan->start_date) {
                    $dateKey = $plan->start_date instanceof \Carbon\Carbon 
                        ? $plan->start_date->format('Y-m-d')
                        : \Carbon\Carbon::parse($plan->start_date)->format('Y-m-d');
                }

                if (!$dateKey) {
                    continue;
                }
                
                $topicDates[] = $dateKey;
                
                $dailyTopics[$dateKey][] = [
                    'id' => $topic->id,
                    'number' => (int) ($topic->topic_number ?? 1),
                    'title' => $topic->title,
                    'topics' => $topic->topics,
                    'description' => $topic->description,
                    'video' => $topic->video_url,
                    'files' => is_array($topic->files) ? $topic->files : [],
                    'resources' => is_array($topic->resources) ? $topic->resources : [],
                    'covered' => (bool) $topic->covered,
                    'coverage_status' => $topic->coverage_status ?? 'not_covered',
                    'weekday_info' => $topic->weekday,
                    'week_info' => $topic->week_info,
                ];
            }
            
            // For weekly plans, fill in all days in the week range with empty topics
            if ($plan->plan_level === 'week' && $startDate && $endDate) {
                $current = clone $startDate;
                while ($current <= $endDate) {
                    $dateKey = $current->format('Y-m-d');
                    // If this date doesn't have topics, add an empty entry
                    if (!isset($dailyTopics[$dateKey])) {
                        $dailyTopics[$dateKey] = [];
                    }
                    $current->addDay();
                }
            }

            // Calculate coverage considering all status levels
            $fullyCovagedTopics = $plan->topics->filter(fn ($topic) => ($topic->coverage_status ?? '') === 'covered')->count();
            $partialTopics = $plan->topics->filter(fn ($topic) => ($topic->coverage_status ?? '') === 'partial')->count();
            $coverage = $plan->topics->count() > 0
                ? (int) round((($fullyCovagedTopics + ($partialTopics * 0.5)) / $plan->topics->count()) * 100)
                : 0;

            // Determine month/year for the plan
            $planMonth = $plan->month;
            $planYear = $plan->year;
            
            if (!$planMonth && $startDate) {
                $planMonth = $startDate->format('F');
                $planYear = $startDate->format('Y');
            }

            return [
                'id' => $plan->id,
                'title' => $plan->title,
                'class' => $plan->course_type ?? 'N/A',
                'section' => $plan->sub_type ?? 'A',
                'subject' => $subject->subject_name ?? ($plan->subject_id ?? 'Subject'),
                'month' => $planMonth ?: 'Monthly',
                'year' => $planYear ?: date('Y'),
                'week' => $plan->plan_level === 'week' ? 'Weekly Plan' : 'Monthly Plan',
                'plan_level' => $plan->plan_level,
                'periods' => (int) ($plan->topics_count ?? $plan->topics->count()),
                'status' => $plan->status,
                'teacher' => $teacher
                    ? ($teacher->employee_name ?: ($teacher->name ?: $teacher->employee_id))
                    : ($plan->teacher_id ? 'Teacher #' . $plan->teacher_id : 'Unassigned Teacher'),
                'objectives' => $plan->title ?? 'Lesson plan objectives',
                'methods' => [],
                'aids' => [],
                'resources' => '',
                'coverage' => $coverage,
                'start_date' => $startDate ? $startDate->format('Y-m-d') : null,
                'end_date' => $endDate ? $endDate->format('Y-m-d') : null,
                'dailyTopics' => $dailyTopics,
                'topicDates' => array_unique($topicDates),
            ];
        })
        ->values()
        ->all();

    return response()->json($plans);
}

private function plannerEmployee(Request $request, $instituteId)
{
    $user = Auth::user();
    $isAdmin = $user && $user->hasAnyRole(['admin', 'superadmin', 'super_admin', 'institute_admin']);

    if ($isAdmin && $request->filled('employee_id')) {
        return EmployeeDetails::where('employee_id', $request->input('employee_id'))
            ->where('institute_id', $instituteId)
            ->first();
    }

    return EmployeeDetails::where('user_id', Auth::id())
        ->where('institute_id', $instituteId)
        ->first();
}

    public function coverageData()
    {
        // Same as calendarData but focus on coverage stats
        return $this->calendarData();
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,active',
        ]);

        $plan = LessonPlan::findOrFail($id);
        $plan->status = $validated['status'];
        $plan->save();

        return response()->json([
            'success' => true,
            'status' => $plan->status,
            'message' => $validated['status'] === 'active' ? 'Plan activated successfully.' : 'Plan moved to draft.'
        ]);
    }

    public function show($id)
    {
        $lessonPlan = LessonPlan::with('topics')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $lessonPlan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $lessonPlan = LessonPlan::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'status' => 'in:draft,active',
            'topics' => 'nullable|array',
            'topics.*.title' => 'required|string|max:255',
            'topics.*.description' => 'nullable|string',
            'topics.*.video_url' => 'nullable|url',
            'topics.*.resources' => 'nullable|array',
            'topics.*.files' => 'nullable|array',
            'topics.*.covered' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            // Update plan
            $lessonPlan->update($validated);

            // Update or create topics
            if (isset($validated['topics'])) {
                $lessonPlan->topics()->delete(); // Remove old topics
                
                $order = 0;
                foreach ($validated['topics'] as $topicData) {
                    LessonTopic::create([
                        'lesson_plan_id' => $lessonPlan->id,
                        'topic_number' => $order + 1,
                        'title' => $topicData['title'],
                        'description' => $topicData['description'] ?? null,
                        'video_url' => $topicData['video_url'] ?? null,
                        'resources' => $topicData['resources'] ?? [],
                        'files' => $topicData['files'] ?? [],
                        'covered' => $topicData['covered'] ?? false,
                        'order' => $order,
                    ]);
                    $order++;
                }
                
                $lessonPlan->update(['topics_count' => $order]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Lesson plan updated successfully!',
                'data' => $lessonPlan->load('topics'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update lesson plan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $lessonPlan = LessonPlan::findOrFail($id);
        $lessonPlan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lesson plan deleted successfully!',
        ]);
    }

    public function updateTopicCoverage(Request $request, $topicId)
    {
        $topic = LessonTopic::findOrFail($topicId);
        
        $validated = $request->validate([
            'coverage_status' => 'nullable|in:not_covered,partial,covered',
            'covered' => 'nullable|boolean',
            'covered_date' => 'nullable|date',
        ]);

        try {
            $updateData = [
                'covered_date' => $validated['covered_date'] ?? null,
            ];

            // Handle coverage_status (new preferred field)
            if (!empty($validated['coverage_status'])) {
                $updateData['coverage_status'] = $validated['coverage_status'];
                // Also update the boolean field for backward compatibility
                $updateData['covered'] = ($validated['coverage_status'] === 'covered') ? 1 : 0;
            } 
            // Fallback to covered boolean if status not provided
            elseif (!is_null($validated['covered'])) {
                $updateData['covered'] = $validated['covered'];
                $updateData['coverage_status'] = $validated['covered'] ? 'covered' : 'not_covered';
            }

            // Auto-set covered_date if marking as covered
            if (($updateData['coverage_status'] ?? null) === 'covered' && empty($updateData['covered_date'])) {
                $updateData['covered_date'] = now();
            }

            $topic->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Topic coverage updated!',
                'data' => $topic,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update topic coverage: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate test data for dashboard testing
     * Route: GET /lesson-planner/test-data
     */
    public function generateTestData()
    {
        try {
            DB::beginTransaction();

            // Create test lesson plans
            $statuses = ['draft', 'pending', 'approved'];
            $months = ['January', 'February', 'March', 'April'];
            
            for ($i = 0; $i < 5; $i++) {
                $status = $statuses[$i % count($statuses)];
                $month = $months[$i % count($months)];
                
                $plan = LessonPlan::create([
                    'title' => "Test Lesson Plan - $month " . ($i + 1),
                    'plan_level' => 'week',
                    'month' => $month,
                    'year' => '2026',
                    'start_date' => now()->addDays($i * 7),
                    'end_date' => now()->addDays($i * 7 + 6),
                    'status' => $status,
                    'course_type' => 'Class ' . (($i % 3) + 9),
                    'sub_type' => chr(65 + ($i % 3)), // A, B, C
                    'topics_count' => 5,
                ]);

                // Create test topics
                $topicStatuses = ['covered', 'partial', 'not_covered'];
                for ($t = 0; $t < 5; $t++) {
                    $topicStatus = $topicStatuses[$t % 3];
                    
                    LessonTopic::create([
                        'lesson_plan_id' => $plan->id,
                        'topic_number' => $t + 1,
                        'title' => "Topic " . ($t + 1) . ": " . ucfirst($topicStatus),
                        'description' => "This is a test topic demonstrating $topicStatus coverage status.",
                        'coverage_status' => $topicStatus,
                        'covered' => $topicStatus === 'covered' ? 1 : 0,
                        'scheduled_date' => now()->addDays($i * 7 + $t),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Test data created successfully! Created 5 lesson plans with 25 topics total.',
                'data' => [
                    'plans_created' => 5,
                    'topics_created' => 25,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create test data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear test data
     * Route: DELETE /lesson-planner/test-data
     */
    public function clearTestData()
    {
        try {
            DB::beginTransaction();

            // Delete all lesson topics
            LessonTopic::truncate();
            
            // Delete all lesson plans
            LessonPlan::truncate();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'All test data cleared successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear test data: ' . $e->getMessage(),
            ], 500);
        }
    }
}