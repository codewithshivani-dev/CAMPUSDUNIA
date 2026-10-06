<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Traits\InstituteBranchAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\StudentParentDetails;

class StudentAttendanceViewController extends Controller
{
    use InstituteBranchAccess;

    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get student details
        $student = StudentParentDetails::where('user_id', $user->id)->firstOrFail();
        $studentHashId = $student->student_hash_id;
        
        // Get context
        $context = $this->getInstituteBranchContext();
        
        // Get date from request or use today
        $selectedDate = $request->date ?? Carbon::today()->format('Y-m-d');
        $date = Carbon::parse($selectedDate);
        
        // Get attendance for the selected date
        $attendance = $this->getDailyAttendance($studentHashId, $selectedDate, $context);
        
        // Get attendance statistics
        $stats = $this->getAttendanceStatistics($studentHashId, $context);
        
        // Get recent attendance (last 7 days)
        $recentAttendance = $this->getRecentAttendance($studentHashId, $context);
        
        // Get monthly attendance summary
        $monthlySummary = $this->getMonthlySummary($studentHashId, $context);
        
        return view('instituteAdmin.StudentAttendance.StudentAttendanceView', compact(
            'student',
            'attendance',
            'stats',
            'recentAttendance',
            'monthlySummary',
            'selectedDate',
            'date'
        ));
    }
    
    public function getAttendanceByDate(Request $request)
    {
        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->firstOrFail();
        $studentHashId = $student->student_hash_id;
        
        $context = $this->getInstituteBranchContext();
        $selectedDate = $request->date ?? Carbon::today()->format('Y-m-d');
        
        $attendance = $this->getDailyAttendance($studentHashId, $selectedDate, $context);
        
        return response()->json([
            'success' => true,
            'date' => Carbon::parse($selectedDate)->format('l, F j, Y'),
            'attendance' => $attendance
        ]);
    }
    
    public function getAttendanceBySubject(Request $request)
    {
        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->firstOrFail();
        $studentHashId = $student->student_hash_id;
        
        $context = $this->getInstituteBranchContext();
        $subjectId = $request->subject_id;
        $subSubjectId = $request->sub_subject_id;
        $subjectType = $request->subject_type;
        
        $attendanceHistory = $this->getSubjectAttendanceHistory(
            $studentHashId, 
            $subjectId, 
            $subSubjectId, 
            $subjectType, 
            $context
        );
        
        $subjectName = $this->getSubjectName($subjectId, $subSubjectId, $subjectType);
        
        return response()->json([
            'success' => true,
            'subject_name' => $subjectName,
            'attendance_history' => $attendanceHistory // This already contains 'history' and 'summary'
        ]);
    }
    
    public function getAttendanceStatisticsData()
    {
        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->firstOrFail();
        $studentHashId = $student->student_hash_id;
        
        $context = $this->getInstituteBranchContext();
        $stats = $this->getAttendanceStatistics($studentHashId, $context);
        
        return response()->json([
            'success' => true,
            'statistics' => $stats
        ]);
    }
    
    private function getDailyAttendance($studentHashId, $date, $context)
    {
        $attendance = DB::table('student_attendance as sa')
            ->join('assign_subjects_to_employee as ase', 'sa.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->join('subjects_coursewise as sc', 'ase.subject_id', '=', 'sc.subject_id')
            ->leftJoin('sub_subjects as ss', function($join) {
                $join->on('ase.sub_subject_id', '=', 'ss.sub_subject_id')
                    ->on('ase.subject_id', '=', 'ss.subject_id');
            })
            ->join('employee_details as ed', 'ase.employee_id', '=', 'ed.employee_id')
            ->leftJoin('product_details as pd', 'sc.course_detail_id', '=', 'pd.product_id')
            ->where('sa.student_hash_id', $studentHashId)
            ->where('sa.date', $date)
            ->where('sa.institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('sa.branch_id', $context['branch_id']);
            })
            ->select(
                'sa.id as attendance_id',
                'sc.subject_id',
                'sc.subject_name',
                'ase.subject_type',
                'ase.sub_subject_id',
                'ss.sub_subject_name',
                'sa.status',
                'sa.remarks',
                'sa.date',
                'sa.start_time',
                'sa.end_time',
                'ed.name as teacher_name',
                'ed.employee_id',
                'pd.course_type',
                'pd.sub_type',
                DB::raw("CONCAT(DATE_FORMAT(sa.start_time, '%h:%i %p'), ' - ', DATE_FORMAT(sa.end_time, '%h:%i %p')) as time_slot"),
                DB::raw("CASE 
                    WHEN ase.subject_type = 'sub_subject' AND ss.sub_subject_name IS NOT NULL 
                    THEN CONCAT(sc.subject_name, ' > ', ss.sub_subject_name)
                    ELSE sc.subject_name
                END as display_name")
            )
            ->orderBy('sa.start_time')
            ->get();
        
        // Group by time slots
        $groupedAttendance = $attendance->groupBy('time_slot');
        
        return $groupedAttendance;
    }
    
    private function getSubjectAttendanceHistory($studentHashId, $subjectId, $subSubjectId = null, $subjectType = 'main_subject', $context)
    {
        $query = DB::table('student_attendance as sa')
            ->join('assign_subjects_to_employee as ase', 'sa.emp_assign_subject_id', '=', 'ase.emp_assign_subject_id')
            ->join('employee_details as ed', 'ase.employee_id', '=', 'ed.employee_id')
            ->where('sa.student_hash_id', $studentHashId)
            ->where('sa.institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('sa.branch_id', $context['branch_id']);
            });
        
        if ($subjectType === 'sub_subject' && $subSubjectId) {
            $query->where('ase.sub_subject_id', $subSubjectId);
        } else {
            $query->where('ase.subject_id', $subjectId);
        }
        
        $history = $query->select(
                'sa.date',
                'sa.status',
                'sa.remarks',
                'ed.name as teacher_name',
                'sa.start_time',
                'sa.end_time',
                'sa.section_id',
                DB::raw("DATE_FORMAT(sa.date, '%d-%m-%Y') as formatted_date"),
                DB::raw("DATE_FORMAT(sa.start_time, '%h:%i %p') as formatted_start_time"),
                DB::raw("DATE_FORMAT(sa.end_time, '%h:%i %p') as formatted_end_time"),
                DB::raw("DAYNAME(sa.date) as day_name")
            )
            ->orderBy('sa.date', 'desc')
            ->limit(50)
            ->get();
        
        // Calculate summary
        $summary = $this->calculateAttendanceSummary($history);
        
        return [
            'history' => $history,
            'summary' => $summary
        ];
    }
    
    private function getAttendanceStatistics($studentHashId, $context)
    {
        $today = Carbon::today()->format('Y-m-d');
        $currentMonth = Carbon::now()->format('Y-m');
        $weekStart = Carbon::now()->startOfWeek()->format('Y-m-d');
        $weekEnd = Carbon::now()->endOfWeek()->format('Y-m-d');
        
        // Today's statistics
        $todayStats = DB::table('student_attendance')
            ->where('student_hash_id', $studentHashId)
            ->where('date', $today)
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->select(
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present"),
                DB::raw("SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent"),
                DB::raw("SUM(CASE WHEN status = 'Leave' THEN 1 ELSE 0 END) as `leave`")  // Add backticks
            )
            ->first();
        
        // Weekly statistics
        $weeklyStats = DB::table('student_attendance')
            ->where('student_hash_id', $studentHashId)
            ->whereBetween('date', [$weekStart, $weekEnd])
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->select(
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present"),
                DB::raw("SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent"),
                DB::raw("SUM(CASE WHEN status = 'Leave' THEN 1 ELSE 0 END) as `leave`")  // Add backticks
            )
            ->first();
        
        // Monthly statistics
        $monthlyStats = DB::table('student_attendance')
        ->where('student_hash_id', $studentHashId)
        ->where(DB::raw("DATE_FORMAT(date, '%Y-%m')"), $currentMonth)
        ->where('institute_id', $context['institute_id'])
        ->when($context['branch_id'], function($query) use ($context) {
            return $query->where('branch_id', $context['branch_id']);
        })
        ->select(
            DB::raw("COUNT(*) as total"),
            DB::raw("SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present"),
            DB::raw("SUM(CASE WHEN status = 'Absent' THEN 1 ELSE 0 END) as absent"),
            DB::raw("SUM(CASE WHEN status = 'Leave' THEN 1 ELSE 0 END) as `leave`")  // Add backticks
        )
        ->first();
        // Calculate attendance streak
        $streak = $this->calculateAttendanceStreak($studentHashId, $context);
        
        return [
            'today' => [
                'total' => $todayStats->total ?? 0,
                'present' => $todayStats->present ?? 0,
                'absent' => $todayStats->absent ?? 0,
                'leave' => $todayStats->leave ?? 0,
                'percentage' => $todayStats->total > 0 ? 
                    round(($todayStats->present / $todayStats->total) * 100, 2) : 0
            ],
            'weekly' => [
                'total' => $weeklyStats->total ?? 0,
                'present' => $weeklyStats->present ?? 0,
                'percentage' => $weeklyStats->total > 0 ? 
                    round(($weeklyStats->present / $weeklyStats->total) * 100, 2) : 0
            ],
            'monthly' => [
                'total' => $monthlyStats->total ?? 0,
                'present' => $monthlyStats->present ?? 0,
                'absent' => $monthlyStats->absent ?? 0,
                'leave' => $monthlyStats->leave ?? 0,
                'percentage' => $monthlyStats->total > 0 ? 
                    round(($monthlyStats->present / $monthlyStats->total) * 100, 2) : 0
            ],
            'streak' => $streak,
            'current_month' => Carbon::now()->format('F Y'),
            'current_week' => 'Week ' . Carbon::now()->weekOfMonth
        ];
    }
    
    private function getRecentAttendance($studentHashId, $context)
    {
        $recentDates = DB::table('student_attendance')
            ->where('student_hash_id', $studentHashId)
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->orderBy('date', 'desc')
            ->limit(7)
            ->pluck('date')
            ->unique()
            ->values();
        
        $recentAttendance = [];
        
        foreach ($recentDates as $date) {
            $dayAttendance = DB::table('student_attendance')
                ->where('student_hash_id', $studentHashId)
                ->where('date', $date)
                ->where('institute_id', $context['institute_id'])
                ->when($context['branch_id'], function($query) use ($context) {
                    return $query->where('branch_id', $context['branch_id']);
                })
                ->select(
                    DB::raw("COUNT(*) as total"),
                    DB::raw("SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present")
                )
                ->first();
            
            $recentAttendance[] = [
                'date' => $date,
                'formatted_date' => Carbon::parse($date)->format('D, M d'),
                'total' => $dayAttendance->total ?? 0,
                'present' => $dayAttendance->present ?? 0,
                'percentage' => $dayAttendance->total > 0 ? 
                    round(($dayAttendance->present / $dayAttendance->total) * 100, 2) : 0
            ];
        }
        
        return $recentAttendance;
    }
    
    private function getMonthlySummary($studentHashId, $context)
    {
        $currentMonth = Carbon::now()->format('Y-m');
        
        $summary = DB::table('student_attendance')
            ->where('student_hash_id', $studentHashId)
            ->where(DB::raw("DATE_FORMAT(date, '%Y-%m')"), $currentMonth)
            ->where('institute_id', $context['institute_id'])
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->groupBy('date')
            ->select(
                'date',
                DB::raw("COUNT(*) as total_classes"),
                DB::raw("SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) as present"),
                DB::raw("DAYNAME(date) as day_name"),
                DB::raw("DAY(date) as day_number")
            )
            ->orderBy('date')
            ->get();
        
        return $summary;
    }
    
    private function calculateAttendanceSummary($attendance)
    {
        $total = count($attendance);
        $present = $attendance->where('status', 'Present')->count();
        $absent = $attendance->where('status', 'Absent')->count();
        $leave = $attendance->where('status', 'Leave')->count();
        
        $percentage = $total > 0 ? round(($present / $total) * 100, 2) : 0;
        
        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'leave' => $leave,
            'percentage' => $percentage,
            'status' => $this->getAttendanceStatus($percentage)
        ];
    }
    
    private function calculateAttendanceStreak($studentHashId, $context)
    {
        $dates = DB::table('student_attendance')
            ->where('student_hash_id', $studentHashId)
            ->where('institute_id', $context['institute_id'])
            ->where('status', 'Present')
            ->when($context['branch_id'], function($query) use ($context) {
                return $query->where('branch_id', $context['branch_id']);
            })
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->map(function($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->unique()
            ->values();
        
        $streak = 0;
        $today = Carbon::today()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        
        // Check if present today
        if ($dates->contains($today)) {
            $streak = 1;
            // Check consecutive days
            for ($i = 1; $i <= 30; $i++) {
                $checkDate = Carbon::today()->subDays($i)->format('Y-m-d');
                if ($dates->contains($checkDate)) {
                    $streak++;
                } else {
                    break;
                }
            }
        } elseif ($dates->contains($yesterday)) {
            $streak = 1;
            // Check consecutive days before yesterday
            for ($i = 2; $i <= 30; $i++) {
                $checkDate = Carbon::yesterday()->subDays($i)->format('Y-m-d');
                if ($dates->contains($checkDate)) {
                    $streak++;
                } else {
                    break;
                }
            }
        }
        
        return $streak;
    }
    
    private function getSubjectName($subjectId, $subSubjectId = null, $subjectType = 'main_subject')
    {
        if ($subjectType === 'sub_subject' && $subSubjectId) {
            $subSubject = DB::table('sub_subjects')
                ->where('sub_subject_id', $subSubjectId)
                ->first();
                
            if ($subSubject) {
                return $subSubject->sub_subject_name;
            }
        }
        
        $subject = DB::table('subjects_coursewise')
            ->where('subject_id', $subjectId)
            ->first();
            
        return $subject->subject_name ?? 'Unknown Subject';
    }
    
    private function getAttendanceStatus($percentage)
    {
        if ($percentage >= 75) return 'Excellent';
        if ($percentage >= 60) return 'Good';
        if ($percentage >= 50) return 'Average';
        if ($percentage >= 40) return 'Poor';
        return 'Critical';
    }
}