<?php

namespace App\Http\Controllers\institute\Admin\AdminController;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDetails;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeLeave;
use App\Models\HolidayEvent;
use App\Models\Notice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Syllabus;
use Illuminate\Support\Facades\Storage;

class EmployeeDashboardController extends Controller
{
    public function getemployeeDashboard()
    {
        //  Employee (Safe)
        $institute_id = auth()->user()->institute_id;
        $employee = EmployeeDetails::query()
            ->join('departments', 'employee_details.department_id', '=', 'departments.department_id')
            ->where('employee_details.user_id', auth()->id())
            ->select(
                'employee_details.*',
                'departments.department as department_name'
            )
            ->firstOrFail();
        $employeeId = $employee->employee_id;

        // Employee lectures
        $employeeLectures = DB::table('assign_subjects_to_employee as ase')
            ->join('employee_subject_lectures as esl', 'ase.emp_assign_subject_id', '=', 'esl.emp_assign_subject_id')
            ->leftJoin('course_fee_structures as cfs', 'ase.course_detail_id', '=', 'cfs.product_id')
            ->where('ase.employee_id', $employeeId)
            ->where('ase.status', 'Active')
            ->select(
                'esl.start_time',
                'esl.end_time',
                'cfs.sections',
                'ase.subject_display_name'
            )
            ->orderBy('esl.start_time')
            ->get();

        $employeeLectures = $employeeLectures->map(function ($item) {
            $sections = json_decode($item->sections, true);
            $item->section_name = $sections[0]['name'] ?? null;
            return $item;
        });


        // Last 7 Days Attendance

        $startDate = Carbon::now()->subDays(6)->toDateString();
        $endDate = Carbon::now()->toDateString();

        $attendanceData = EmployeeAttendance::with('logs')
            ->where('employee_id', $employeeId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get()
            ->keyBy('date');
        
        $todayAttendance = EmployeeAttendance::where('employee_id', $employeeId)
            ->whereDate('date', Carbon::today())
            ->first();

        //  Monthly Attendance

        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $monthlyAttendance = EmployeeAttendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get();

        // Employee leaves (this month)
        $leaves = EmployeeLeave::where('employee_id', $employeeId)
        ->orderBy('start_date', 'desc')
        ->limit(5)
        ->get();

        // Holiday/Events
        $events = HolidayEvent::where(function ($q) use ($employee) {
            $q->where('scope', 'overall')
                ->orWhere(function ($q2) use ($employee) {
                    $q2->where('scope', 'department_wise')
                        ->where('department_id', $employee->department_id);
                });
        })
            ->whereDate('start_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        // Syllabus

        $syllabuses = Syllabus::query()
            ->join(
                'course_fee_structures as cfs',
                'cfs.product_id',
                '=',
                'syllabuses.course_detail_id'
            )
            ->join(
                'assign_subjects_to_employee as ase',
                'ase.subject_id',
                '=',
                'syllabuses.subject_id'
            )
            ->where('syllabuses.employee_id', $employeeId)
            ->where('ase.employee_id', $employeeId)
            ->where('ase.status', 'Active')
            ->orderBy('syllabuses.uploaded_date', 'desc')
            ->limit(5) // dashboard limit
            ->select(
                'syllabuses.*',
                'cfs.course_type',
                'ase.subject_display_name'
            )
            ->get()
            ->map(function ($item) {
                $item->file_url = Storage::url($item->file_path);
                return $item;
            });
            $notice_board = Notice::where('institute_id', $institute_id)
                ->whereIn('notice_type', ['both', 'employee'])
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->get();
        // Return View

        return view(
            'instituteAdmin.DashboardMainPagesFiles.employee',
            compact(
                'employee',
                'employeeLectures',
                'attendanceData',
                'todayAttendance',
                'leaves',
                'events',
                'startDate',
                'endDate',
                'monthlyAttendance',
                'syllabuses',
                'notice_board'
            )
        );
    }
}

