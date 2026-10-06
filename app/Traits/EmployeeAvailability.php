<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

trait EmployeeAvailability
{
    public function checkEmployeeAvailability(string $employeeId, array $timeSlot, array $context): array
    {
        // Add debugging
        \Log::info('=== AVAILABILITY CHECK START ===');
        \Log::info('Employee ID:', ['id' => $employeeId]);
        \Log::info('Time Slot:', $timeSlot);
        \Log::info('Context:', $context);

        // Validate required parameters
        $required = ['start_time', 'end_time', 'frequency'];
        foreach ($required as $field) {
            if (!isset($timeSlot[$field])) {
                return [
                    'available' => false,
                    'message' => "Missing required parameter: {$field}",
                    'conflicting_assignments' => []
                ];
            }
        }

        if (empty($context['institute_id'])) {
            return [
                'available' => false,
                'message' => 'Institute context is required',
                'conflicting_assignments' => []
            ];
        }

        try {
            // Parse the time
            $startTime = Carbon::parse($timeSlot['start_time']);
            $endTime = Carbon::parse($timeSlot['end_time']);
            
            if ($endTime <= $startTime) {
                return [
                    'available' => false,
                    'message' => 'End time must be after start time',
                    'conflicting_assignments' => []
                ];
            }

            // Determine check date and date range based on frequency
            $frequency = $timeSlot['frequency'];
            $checkDate = null;
            $validFrom = null;
            $validTo = null;

            if ($frequency === 'once') {
                if (!isset($timeSlot['date'])) {
                    return [
                        'available' => false,
                        'message' => 'Date is required for one-time events',
                        'conflicting_assignments' => []
                    ];
                }
                $checkDate = Carbon::parse($timeSlot['date']);
                $validFrom = $checkDate;
                $validTo = $checkDate;
            } else {
                // Recurring events
                if (!isset($timeSlot['valid_from'])) {
                    return [
                        'available' => false,
                        'message' => 'Start date is required for recurring events',
                        'conflicting_assignments' => []
                    ];
                }
                $validFrom = Carbon::parse($timeSlot['valid_from']);
                $validTo = isset($timeSlot['valid_to']) ? Carbon::parse($timeSlot['valid_to']) : null;
                $checkDate = $validFrom; // Use start date for initial check
            }

            \Log::info('Parsed parameters:', [
                'check_date' => $checkDate ? $checkDate->format('Y-m-d') : null,
                'valid_from' => $validFrom->format('Y-m-d'),
                'valid_to' => $validTo ? $validTo->format('Y-m-d') : null,
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s')
            ]);

        } catch (\Exception $e) {
            return [
                'available' => false,
                'message' => 'Invalid date/time format: ' . $e->getMessage(),
                'conflicting_assignments' => []
            ];
        }

        // Get conflicting lectures
        $conflictingLectures = $this->getConflictingLectures(
            $employeeId,
            $timeSlot,
            $context,
            $startTime,
            $endTime,
            $validFrom,
            $validTo,
            $checkDate
        );

        // Get conflicting duties - FIXED TABLE NAME HERE
        $conflictingDuties = $this->getConflictingDuties(
            $employeeId,
            $timeSlot,
            $context,
            $startTime,
            $endTime,
            $validFrom,
            $validTo,
            $checkDate
        );

        $allConflicts = array_merge($conflictingLectures, $conflictingDuties);

        \Log::info('Conflict results:', [
            'lecture_conflicts' => count($conflictingLectures),
            'duty_conflicts' => count($conflictingDuties),
            'total_conflicts' => count($allConflicts)
        ]);

        if (empty($allConflicts)) {
            return [
                'available' => true,
                'message' => 'Employee is available for this time slot',
                'conflicting_assignments' => []
            ];
        }

        $formattedConflicts = $this->formatAllConflictingAssignments($allConflicts);

        return [
            'available' => false,
            'message' => 'Employee has conflicting schedule(s)',
            'conflicting_assignments' => $formattedConflicts
        ];
    }

    private function getConflictingLectures(
        string $employeeId,
        array $timeSlot,
        array $context,
        Carbon $newStartTime,
        Carbon $newEndTime,
        Carbon $newValidFrom,
        ?Carbon $newValidTo,
        Carbon $checkDate
    ): array {
        \Log::info('=== DEBUGGING LECTURE QUERY ===');
        
        // Test each filter one by one
        \Log::info('Testing employee_id filter...');
        $byEmployee = DB::table('employee_subject_lectures as esl')
            ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->where('ase.employee_id', $employeeId)
            ->count();
        \Log::info('By employee_id only: ' . $byEmployee);
        
        \Log::info('Testing institute_id filter...');
        $byInstitute = DB::table('employee_subject_lectures as esl')
            ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->where('ase.employee_id', $employeeId)
            ->where('ase.institute_id', $context['institute_id'])
            ->count();
        \Log::info('+ institute_id: ' . $byInstitute);
        
        \Log::info('Testing status filter...');
        $byStatus = DB::table('employee_subject_lectures as esl')
            ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->where('ase.employee_id', $employeeId)
            ->where('ase.institute_id', $context['institute_id'])
            ->where('ase.status', 'active')
            ->count();
        \Log::info('+ status active: ' . $byStatus);
        
        \Log::info('Testing lecture status filter...');
        $byLectureStatus = DB::table('employee_subject_lectures as esl')
            ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->where('ase.employee_id', $employeeId)
            ->where('ase.institute_id', $context['institute_id'])
            ->where('ase.status', 'active')
            ->where('esl.status', '!=', 'cancelled')
            ->count();
        \Log::info('+ lecture not cancelled: ' . $byLectureStatus);
        
        // Also log the exact data from your table
        $lectureData = DB::table('employee_subject_lectures as esl')
            ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->select('ase.*', 'esl.*')
            ->where('ase.employee_id', $employeeId)
            ->first();
        
        \Log::info('Raw lecture data from database:', (array) $lectureData);
        
        // Get ALL lectures for this employee
        $allLectures = DB::table('employee_subject_lectures as esl')
            ->join('assign_subjects_to_employee as ase', 'esl.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->leftJoin('subjects_coursewise as s', 'ase.subject_id', '=', 's.subject_id')
            ->leftJoin('sub_subjects as ss', function ($join) {
                $join->on('ase.sub_subject_id', '=', 'ss.sub_subject_id')
                    ->on('ase.subject_id', '=', 'ss.subject_id');
            })
            ->leftJoin('product_details as pd', 'ase.course_detail_id', '=', 'pd.product_id')
            ->select(
                'esl.emp_assign_subject_id as id',
                'ase.emp_assign_subject_id',
                'ase.employee_id',
                'ase.subject_display_name as title',
                's.subject_name as main_subject_name',
                'ss.sub_subject_name',
                'pd.course_type',
                'esl.frequency',
                'esl.start_time',
                'esl.end_time',
                'esl.valid_from',
                'esl.valid_to',
                'esl.days_of_week',
                'esl.day_of_month',
                'esl.section_id',
                'esl.location',
                'esl.status',
                DB::raw('"lecture" as type'),
                DB::raw('TIMEDIFF(esl.end_time, esl.start_time) as duration')
            )
            ->where('ase.employee_id', $employeeId)
            ->where('ase.status', 'active')
            ->where('ase.institute_id', $context['institute_id'])
            ->where('esl.status', '!=', 'cancelled')
            ->get();

        \Log::info('Found ' . $allLectures->count() . ' lectures for employee');

        $conflicts = [];

        foreach ($allLectures as $lecture) {
            \Log::info('Checking lecture:', [
                'id' => $lecture->id,
                'title' => $lecture->title,
                'frequency' => $lecture->frequency,
                'start_time' => $lecture->start_time,
                'end_time' => $lecture->end_time,
                'valid_from' => $lecture->valid_from,
                'valid_to' => $lecture->valid_to
            ]);

            // Use the SIMPLE version that only checks date/time (ignore frequency)
            if ($this->isTimeConflictSimple(
                $lecture,
                $newStartTime,
                $newEndTime,
                $checkDate
            )) {
                \Log::info('FOUND LECTURE CONFLICT!');
                $conflicts[] = $lecture;
            }
        }

        return $conflicts;
    }

    private function getConflictingDuties(
        string $employeeId,
        array $timeSlot,
        array $context,
        Carbon $newStartTime,
        Carbon $newEndTime,
        Carbon $newValidFrom,
        ?Carbon $newValidTo,
        Carbon $checkDate
    ): array {
        \Log::info('Checking duties for employee ' . $employeeId);
        
        // FIXED: Using correct table name 'employee_duties' instead of 'employee_duty_assignments'
        $allDuties = DB::table('employee_duties as ed')
            ->leftJoin('employee_details as emp', 'ed.supervisor_id', '=', 'emp.id')
            ->select(
                'ed.id',
                'ed.employee_duty_id',
                'ed.employee_id',
                'ed.title',
                'ed.description',
                'ed.duty_type',
                'ed.frequency',
                'ed.date',
                'ed.from_date',
                'ed.to_date',
                'ed.start_time',
                'ed.end_time',
                'ed.days_of_week',
                'ed.day_of_month',
                'ed.location',
                'ed.venue',
                'ed.priority',
                'ed.status',
                'ed.instructions',
                'emp.name as supervisor_name',
                DB::raw('TIMEDIFF(ed.end_time, ed.start_time) as duration'),
                DB::raw('"duty" as type')
            )
            ->where('ed.employee_id', $employeeId)
            ->where('ed.institute_id', $context['institute_id'])
            ->where('ed.status', '!=', 'cancelled')
            ->when($context['is_branch_admin'] && $context['branch_id'], function($q) use ($context) {
                return $q->where('ed.branch_id', $context['branch_id']);
            }, function($q) {
                return $q->whereNull('ed.branch_id');
            })
            ->get();

        \Log::info('Found ' . $allDuties->count() . ' duties for employee');

        $conflicts = [];

        foreach ($allDuties as $duty) {
            \Log::info('Checking duty:', [
                'id' => $duty->id,
                'title' => $duty->title,
                'frequency' => $duty->frequency,
                'start_time' => $duty->start_time,
                'end_time' => $duty->end_time,
                'date' => $duty->date,
                'from_date' => $duty->from_date,
                'to_date' => $duty->to_date
            ]);

            if ($this->isTimeConflict(
                $duty,
                $timeSlot,
                $newStartTime,
                $newEndTime,
                $newValidFrom,
                $newValidTo,
                $checkDate
            )) {
                \Log::info('FOUND DUTY CONFLICT!');
                $conflicts[] = $duty;
            }
        }

        return $conflicts;
    }
    
       private function isTimeConflict(
        $assignment,
        array $newSlot,
        Carbon $newStartTime,
        Carbon $newEndTime,
        Carbon $newValidFrom,
        ?Carbon $newValidTo,
        Carbon $checkDate
    ): bool {
        // Parse assignment times
        $assignStartTime = Carbon::parse($assignment->start_time);
        $assignEndTime = Carbon::parse($assignment->end_time);

        \Log::info('Time comparison:', [
            'new_time' => $newStartTime->format('H:i') . '-' . $newEndTime->format('H:i'),
            'assign_time' => $assignStartTime->format('H:i') . '-' . $assignEndTime->format('H:i'),
            'time_overlap' => !($newEndTime <= $assignStartTime || $newStartTime >= $assignEndTime)
        ]);

        // 1. Check time overlap first (most important)
        if ($newEndTime <= $assignStartTime || $newStartTime >= $assignEndTime) {
            return false; // No time overlap
        }

        \Log::info('TIME OVERLAP DETECTED - checking dates...');

        // 2. Get assignment date range
        $assignValidFrom = null;
        $assignValidTo = null;
        
        if ($assignment->type === 'lecture') {
            $assignValidFrom = Carbon::parse($assignment->valid_from);
            $assignValidTo = $assignment->valid_to ? Carbon::parse($assignment->valid_to) : null;
        } else {
            // Duty
            if ($assignment->frequency === 'once') {
                $assignValidFrom = Carbon::parse($assignment->date);
                $assignValidTo = Carbon::parse($assignment->date);
            } else {
                $assignValidFrom = Carbon::parse($assignment->from_date);
                $assignValidTo = $assignment->to_date ? Carbon::parse($assignment->to_date) : null;
            }
        }

        \Log::info('Date ranges:', [
            'new_from' => $newValidFrom->format('Y-m-d'),
            'new_to' => $newValidTo ? $newValidTo->format('Y-m-d') : 'null',
            'assign_from' => $assignValidFrom->format('Y-m-d'),
            'assign_to' => $assignValidTo ? $assignValidTo->format('Y-m-d') : 'null'
        ]);

        // 3. Check date range overlap
        if (!$this->dateRangesOverlap($newValidFrom, $newValidTo, $assignValidFrom, $assignValidTo)) {
            \Log::info('NO DATE RANGE OVERLAP');
            return false;
        }

        \Log::info('DATE RANGE OVERLAP DETECTED - checking frequency...');

        // 4. Check frequency-specific conflicts
        $frequencyConflict = $this->checkFrequencyConflict(
            $assignment,
            $newSlot,
            $checkDate,
            $newValidFrom,
            $newValidTo,
            $assignValidFrom,
            $assignValidTo
        );

        \Log::info('Frequency conflict result:', ['result' => $frequencyConflict]);

        return $frequencyConflict;
    }
    private function isTimeConflictSimple(
        $assignment,
        Carbon $newStartTime,
        Carbon $newEndTime,
        Carbon $checkDate
    ): bool {
        \Log::info('=== SIMPLE TIME CHECK (ignoring frequency) ===');
        
        // Parse assignment times
        $assignStartTime = Carbon::parse($assignment->start_time);
        $assignEndTime = Carbon::parse($assignment->end_time);
        $assignValidFrom = Carbon::parse($assignment->valid_from);
        $assignValidTo = $assignment->valid_to ? Carbon::parse($assignment->valid_to) : null;

        \Log::info('Checking:', [
            'check_date' => $checkDate->format('Y-m-d'),
            'assign_date_range' => $assignValidFrom->format('Y-m-d') . ' to ' . ($assignValidTo ? $assignValidTo->format('Y-m-d') : '∞'),
            'check_time' => $newStartTime->format('H:i') . '-' . $newEndTime->format('H:i'),
            'assign_time' => $assignStartTime->format('H:i') . '-' . $assignEndTime->format('H:i')
        ]);

        // 1. Check if date is within valid range
        if ($checkDate->lt($assignValidFrom)) {
            \Log::info('❌ Date is BEFORE assignment start');
            return false;
        }
        
        if ($assignValidTo && $checkDate->gt($assignValidTo)) {
            \Log::info('❌ Date is AFTER assignment end');
            return false;
        }

        // 2. Check time overlap
        if ($newEndTime <= $assignStartTime || $newStartTime >= $assignEndTime) {
            \Log::info('❌ No time overlap');
            return false;
        }

        \Log::info('✅ CONFLICT FOUND! Date within range and time overlaps');
        return true;
    }

    private function dateRangesOverlap(
        Carbon $start1,
        ?Carbon $end1,
        Carbon $start2,
        ?Carbon $end2
    ): bool {
        // If either is open-ended (null), consider it as far future
        $end1 = $end1 ?? Carbon::createFromDate(9999, 12, 31);
        $end2 = $end2 ?? Carbon::createFromDate(9999, 12, 31);

        return $start1 <= $end2 && $start2 <= $end1;
    }

    private function checkFrequencyConflict(
        $assignment,
        array $newSlot,
        Carbon $checkDate,
        Carbon $newValidFrom,
        ?Carbon $newValidTo,
        Carbon $assignValidFrom,
        ?Carbon $assignValidTo
    ): bool {
        $newFrequency = $newSlot['frequency'];
        $assignFrequency = $assignment->frequency;

        \Log::info('Frequency check:', [
            'new_frequency' => $newFrequency,
            'assign_frequency' => $assignFrequency
        ]);

        // Daily frequency conflicts with anything
        if ($newFrequency === 'daily' || $assignFrequency === 'daily') {
            \Log::info('Daily frequency - conflict');
            return true;
        }

        // One-time event: check specific date
        if ($newFrequency === 'once' && $assignFrequency === 'once') {
            $conflict = $checkDate->eq($assignValidFrom);
            \Log::info('Once vs Once:', ['conflict' => $conflict]);
            return $conflict;
        }

        // Weekly frequency
        if ($newFrequency === 'weekly' && $assignFrequency === 'weekly') {
            $newDays = $newSlot['days_of_week'] ?? [];
           $assignDays = json_decode($assignment->days_of_week, true) ?? [];

            $map = [
                'Sun' => 0, 'Mon' => 1, 'Tue' => 2, 'Wed' => 3,
                'Thu' => 4, 'Fri' => 5, 'Sat' => 6
            ];

            $assignDays = array_map(fn($d) => is_numeric($d) ? (int)$d : ($map[$d] ?? null), $assignDays);
            $assignDays = array_filter($assignDays, fn($d) => $d !== null);
            $intersection = array_intersect($newDays, $assignDays);
            $conflict = !empty($intersection);
            \Log::info('Weekly vs Weekly:', [
                'new_days' => $newDays,
                'assign_days' => $assignDays,
                'intersection' => $intersection,
                'conflict' => $conflict
            ]);
            return $conflict;
        }

        // Monthly frequency
        if ($newFrequency === 'monthly' && $assignFrequency === 'monthly') {
            $newDay = $newSlot['day_of_month'] ?? null;
            $assignDay = $assignment->day_of_month ?? null;
            
            $conflict = ($newDay && $assignDay && $newDay == $assignDay);
            \Log::info('Monthly vs Monthly:', [
                'new_day' => $newDay,
                'assign_day' => $assignDay,
                'conflict' => $conflict
            ]);
            return $conflict;
        }

        // Mixed frequencies
        return $this->checkMixedFrequencyConflict(
            $assignment,
            $newSlot,
            $checkDate,
            $newValidFrom,
            $newValidTo,
            $assignValidFrom,
            $assignValidTo
        );
    }

    private function checkMixedFrequencyConflict(
        $assignment,
        array $newSlot,
        Carbon $checkDate,
        Carbon $newValidFrom,
        ?Carbon $newValidTo,
        Carbon $assignValidFrom,
        ?Carbon $assignValidTo
    ): bool {
        $newFrequency = $newSlot['frequency'];
        $assignFrequency = $assignment->frequency;

        \Log::info('Mixed frequency check:', [
            'new' => $newFrequency,
            'assign' => $assignFrequency
        ]);

        // If new is one-time, check if it falls on assignment's recurring pattern
        if ($newFrequency === 'once') {
            return $this->doesDateMatchRecurringPattern(
                $checkDate,
                $assignFrequency,
                $assignment->days_of_week ?? [],
                $assignment->day_of_month ?? null,
                $assignValidFrom,
                $assignValidTo
            );
        }

        // If assignment is one-time, check if new recurring pattern includes assignment date
        if ($assignFrequency === 'once') {
            return $this->doesDateMatchRecurringPattern(
                $assignValidFrom,
                $newFrequency,
                $newSlot['days_of_week'] ?? [],
                $newSlot['day_of_month'] ?? null,
                $newValidFrom,
                $newValidTo
            );
        }

        // For other combinations, check if date ranges overlap
        \Log::info('Other combination - checking date range overlap');
        return $this->dateRangesOverlap(
            $newValidFrom,
            $newValidTo,
            $assignValidFrom,
            $assignValidTo
        );
    }

    private function doesDateMatchRecurringPattern(
        Carbon $date,
        string $frequency,
        $daysOfWeek,
        ?int $dayOfMonth,
        Carbon $patternStart,
        ?Carbon $patternEnd
    ): bool {
        // Check if date is within valid range
        if ($date < $patternStart || ($patternEnd && $date > $patternEnd)) {
            return false;
        }

        // Process days_of_week if it's a JSON string
      // Normalize days_of_week to integers (0-6)
        if (is_string($daysOfWeek)) {
            $daysOfWeek = json_decode($daysOfWeek, true) ?? [];
        }

        $map = [
            'Sun' => 0, 'Mon' => 1, 'Tue' => 2, 'Wed' => 3,
            'Thu' => 4, 'Fri' => 5, 'Sat' => 6
        ];

        $daysOfWeek = array_map(function ($day) use ($map) {
            return is_numeric($day) ? (int)$day : ($map[$day] ?? null);
        }, $daysOfWeek);

        $daysOfWeek = array_filter($daysOfWeek, fn($d) => $d !== null);

        switch ($frequency) {
            case 'daily':
                return true;
                
            case 'weekly':
                return in_array($date->dayOfWeek, $daysOfWeek);
                
            case 'monthly':
                return $date->day == $dayOfMonth;
                
            case 'once':
                return $date->eq($patternStart);
                
            default:
                return false;
        }
    }

    private function formatAllConflictingAssignments(array $assignments): array
{
    return array_map(function ($assignment) {
        $formatted = [
            'type' => $assignment->type,
            'id' => $assignment->id,
            'title' => $assignment->title,
            'frequency' => $assignment->frequency,
            'start_time' => $assignment->start_time,
            'end_time' => $assignment->end_time,
            'time' => $assignment->start_time . ' - ' . $assignment->end_time,
            'status' => $assignment->status,
            'assignment_type' => ucfirst($assignment->type)
        ];

        if ($assignment->type === 'lecture') {
            // Parse days_of_week from JSON string to array
            $daysOfWeek = $assignment->days_of_week;
            if (is_string($daysOfWeek)) {
                $daysOfWeek = json_decode($daysOfWeek, true) ?? [];
            }
            
            $formatted['main_subject'] = $assignment->main_subject_name ?? null;
            $formatted['sub_subject'] = $assignment->sub_subject_name ?? null;
            $formatted['course_type'] = $assignment->course_type ?? null;
            $formatted['valid_from'] = $assignment->valid_from;
            $formatted['valid_to'] = $assignment->valid_to;
            $formatted['section'] = $assignment->section_id;
            $formatted['location'] = $assignment->location;
            $formatted['days_of_week'] = $daysOfWeek; // Use parsed array
            $formatted['day_of_month'] = $assignment->day_of_month;
            $formatted['days_of_week_display'] = !empty($daysOfWeek) ? 
                implode(', ', $daysOfWeek) : 'None';
        } else {
            // Parse days_of_week for duties too
            $daysOfWeek = $assignment->days_of_week;
            if (is_string($daysOfWeek)) {
                $daysOfWeek = json_decode($daysOfWeek, true) ?? [];
            }
            
            $formatted['duty_type'] = $assignment->duty_type;
            $formatted['description'] = $assignment->description;
            $formatted['date'] = $assignment->date ?? null;
            $formatted['from_date'] = $assignment->from_date ?? null;
            $formatted['to_date'] = $assignment->to_date ?? null;
            $formatted['location'] = $assignment->location;
            $formatted['venue'] = $assignment->venue;
            $formatted['priority'] = $assignment->priority;
            $formatted['supervisor'] = $assignment->supervisor_name;
            $formatted['instructions'] = $assignment->instructions;
            $formatted['days_of_week'] = $daysOfWeek; // Use parsed array
            $formatted['day_of_month'] = $assignment->day_of_month;
            $formatted['duty_id'] = $assignment->employee_duty_id;
            $formatted['days_of_week_display'] = !empty($daysOfWeek) ? 
                implode(', ', $daysOfWeek) : 'None';
        }

        return $formatted;
    }, $assignments);
}

    public function isEmployeeFree(
        string $employeeId,
        string $date,
        string $startTime,
        string $endTime,
        array $context,
        ?string $sectionId = null,
        ?string $ignoreAssignmentId = null
    ): bool {
        $timeSlot = [
            'start_time' => $startTime,
            'end_time' => $endTime,
            'date' => $date,
            'frequency' => 'once',
            'section_id' => $sectionId,
            'ignore_assignment_id' => $ignoreAssignmentId
        ];

        $result = $this->checkEmployeeAvailability($employeeId, $timeSlot, $context);
        
        return $result['available'];
    }
}