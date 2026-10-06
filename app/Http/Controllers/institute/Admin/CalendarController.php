<?php

namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use App\Models\EmployeeSubjectLecture;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function events(Request $request)
    {
        $employeeIdFilter = $request->query('employee_id'); // optional filter

        $lectures = EmployeeSubjectLecture::with(['assignment.subject', 'assignment.employee'])
            ->when($employeeIdFilter, function ($q) use ($employeeIdFilter) {
                $q->whereHas('assignment', fn ($qq) => $qq->where('employee_id', $employeeIdFilter));
            })
            ->get();

        $events = [];

        foreach ($lectures as $lec) {
            $title = ($lec->assignment->employee->name ?? 'Employee') . ' - ' . ($lec->assignment->subject->subject_name ?? 'Subject');

            // Build RRULE & FullCalendar fields
            $rrule = $this->buildRRuleArray($lec);

            if ($lec->frequency === 'one_time') {
                $events[] = [
                    'title' => $title,
                    'start' => $lec->valid_from->format('Y-m-d') . 'T' . $lec->start_time,
                    'end'   => $lec->valid_from->format('Y-m-d') . 'T' . $lec->end_time,
                    'extendedProps' => [
                        'employee_id' => $lec->assignment->employee_id,
                        'subject_id'  => $lec->assignment->subject_id,
                        'location'    => $lec->location,
                    ],
                ];
            } else {
                $events[] = [
                    'title'      => $title,
                    'rrule'      => $rrule,                   // FullCalendar rrule
                    'startTime'  => $lec->start_time,         // time part
                    'endTime'    => $lec->end_time,           // time part
                    'extendedProps' => [
                        'employee_id' => $lec->assignment->employee_id,
                        'subject_id'  => $lec->assignment->subject_id,
                        'location'    => $lec->location,
                    ],
                ];
            }
        }

        return response()->json($events);
    }

    protected function buildRRuleArray($lec): array
    {
        $dtstart = $lec->valid_from->format('Y-m-d\T') . $lec->start_time;
        $until   = $lec->valid_to ? $lec->valid_to->format('Y-m-d\T') . $lec->end_time : null;

        switch ($lec->frequency) {
            case 'daily':
                return [
                    'freq'    => 'daily',
                    'dtstart' => $dtstart,
                    'until'   => $until,
                ];

            case 'weekly':
                // days_of_week: [0..6] (Sun..Sat) -> ['SU','MO',...]
                $map = ['SU','MO','TU','WE','TH','FR','SA'];
                $byDay = collect($lec->days_of_week ?? [])
                    ->map(fn ($n) => $map[(int)$n])
                    ->values()
                    ->all();

                return [
                    'freq'    => 'weekly',
                    'byweekday' => $byDay,
                    'dtstart' => $dtstart,
                    'until'   => $until,
                ];

            case 'monthly':
                return [
                    'freq'        => 'monthly',
                    'bymonthday'  => (int) $lec->day_of_month,
                    'dtstart'     => $dtstart,
                    'until'       => $until,
                ];

            default:
                // fallback
                return [
                    'freq'    => 'daily',
                    'dtstart' => $dtstart,
                    'until'   => $until,
                ];
        }
    }
}
