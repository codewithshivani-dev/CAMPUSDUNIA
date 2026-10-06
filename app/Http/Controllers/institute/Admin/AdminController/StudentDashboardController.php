<?php

namespace App\Http\Controllers\institute\Admin\AdminController;
use App\Http\Controllers\Controller;
use App\Models\StudentParentDetails;
use App\Models\SubjectsCoursewise;
use App\Models\StudentAttendence;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudentDashboardController extends Controller
{

    // In StudentParentDetails model (if relationships are defined)
    public function studentdashboard(Request $request)
    {
        $institute_id = auth()->user()->institute_id;
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Unauthorized');
        }

        $student = StudentParentDetails::with([
            'documents',
            'bankAccount',
            'academicTransportDetails',
            'address',
            'user'
        ])->where('user_id', $user->id)->firstOrFail();

        $studentHashId = $student->student_hash_id;
        
        //    COURSE DETAILS
        $courseType = $student->academicTransportDetails->course_type ?? null;
        $courseSubtype = $student->academicTransportDetails->course_subtype ?? null;

        //    ATTENDANCE LOGIC
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        // Total number of days in the month dynamically
        $totalDaysInMonth = $currentMonthEnd->day;

        // Your existing attendance query
        $monthlyAttendance = StudentAttendence::where('student_hash_id', $student->student_hash_id)
            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->get();

        // Optional: count present/absent/leave
        $present = $monthlyAttendance->where('status', 'Present')->count();
        $absent = $monthlyAttendance->where('status', 'Absent')->count();
        $leave = $monthlyAttendance->where('status', 'Leave')->count();


        //    LAST 7 DAYS DATA

        $last7Days = collect();

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::now()->subDays($i)->toDateString();

            $attendance = StudentAttendence::where('student_hash_id', $student->student_hash_id)
                ->whereDate('date', $date)
                ->first();

            $last7Days->push([
                'date' => $date,
                'status' => $attendance->status ?? 'Pending'
            ]);
        }

        //   TODAY'S CLASSES QUERY  

        $date = $request->get('date', Carbon::today()->toDateString());

        $todayClasses = DB::table('academic_transport_details as atd')
            ->join('assign_subjects_to_employee as aste', 'atd.course_subtype_id', '=', 'aste.course_detail_id')
            ->join('employee_subject_lectures as esl', 'aste.emp_assign_subject_id', '=', 'esl.emp_assign_subject_id')
            ->join('subjects_coursewise as sc', 'aste.course_detail_id', '=', 'sc.course_detail_id')
            ->where('atd.institute_id', $student->institute_id)
            ->select(
                'sc.subject_name',
                'esl.start_time',
                'esl.end_time'
            )
            ->distinct()
            ->orderBy('esl.start_time')
            ->get();
// dd($todayClasses);
        // Assignments
        $assignments = DB::table('assignments as a')
            ->join('subjects_coursewise as sc', 'a.subject_id', '=', 'sc.subject_id')
            ->where('a.institute_id', $student->institute_id)
            ->select(
                'sc.subject_name',
                'a.title',
                'a.description',
                'a.due_date'
            )
            ->groupBy(
                'sc.subject_name',
                'a.title',
                'a.description',
                'a.due_date'
            )
            ->orderBy('a.due_date')
            ->get();

        $subjects = DB::table('subjects_coursewise')
            ->where('institute_id', $student->institute_id)
            ->select('subject_name')
            ->distinct()
            ->orderBy('subject_name')
            ->get();

        // STUDENT LEAVES QUERY
        $studentLeaves = DB::table('student_leaves')
            ->where('institute_id', $student->institute_id)
            ->select(
                'leave_type',
                'leave_duration_type',
                'start_date',
                'end_date',
                'status'
            )
            ->orderBy('start_date', 'desc')
            ->get();


        // STUDENT FEES QUERY (ALL TYPES)

        $studentHashId = $student->student_hash_id;

        // Transport Fees
        $transportFees = DB::table('student_transport_fees')
            ->where('student_hash_id', $studentHashId)
            ->select(
                DB::raw('transport_fee AS fee'),
                DB::raw('fee_duration_type AS fee_duration'),
                'due_date',
                'payment_status',
                DB::raw("'Transport Fee' AS fee_type")
            );

        // Miscellaneous Fees
        $miscellaneousFees = DB::table('student_miscellaneous_fees')
            ->where('student_hash_id', $studentHashId)
            ->select(
                DB::raw('miscellaneous_fee AS fee'),
                DB::raw('fee_duration_type AS fee_duration'),
                'due_date',
                'payment_status',
                DB::raw("'Miscellaneous Fee' AS fee_type")
            );

        // Hostel Fees
        $hostelFees = DB::table('student_hostel_fees')
            ->where('student_hash_id', $studentHashId)
            ->select(
                DB::raw('hostel_fee AS fee'),
                DB::raw('fee_duration_type AS fee_duration'),
                'due_date',
                'payment_status',
                DB::raw("'Hostel Fee' AS fee_type")
            );

        // Custom Fees
        $customFees = DB::table('student_custom_fees')
            ->where('student_hash_id', $studentHashId)
            ->select(
                DB::raw('custom_fee_value AS fee'),
                DB::raw('fee_duration_type AS fee_duration'),
                'due_date',
                'payment_status',
                DB::raw("'Custom Fee' AS fee_type")
            );

        // Course Fees
        $courseFees = DB::table('student_course_fee_structures')
            ->where('student_hash_id', $studentHashId)
            ->select(
                DB::raw('course_fee AS fee'),
                DB::raw('fee_duration_type AS fee_duration'),
                'due_date',
                'payment_status',
                DB::raw("'Course Fee' AS fee_type")
            );

        // Registration Fees
        $registrationFees = DB::table('student_registration_fees')
            ->where('student_hash_id', $studentHashId)
            ->select(
                DB::raw('registration_fee AS fee'),
                DB::raw('fee_duration_type AS fee_duration'),
                'due_date',
                'payment_status',
                DB::raw("'Registration Fee' AS fee_type")
            );

        // UNION ALL
        $studentFees = $transportFees
            ->unionAll($miscellaneousFees)
            ->unionAll($hostelFees)
            ->unionAll($customFees)
            ->unionAll($courseFees)
            ->unionAll($registrationFees)
            ->orderBy('due_date', 'asc')
            ->get();
        $notice_board = Notice::where('institute_id', $institute_id)
            ->whereIn('notice_type', ['both', 'student'])
            ->where('status', 'published')
            ->latest()
            ->get();
        return view(
            'instituteAdmin.DashboardMainPagesFiles.student',
            compact(
                'student',
                'courseType',
                'courseSubtype',
                'totalDaysInMonth',
                'present',
                'absent',
                'leave',
                'last7Days',
                'todayClasses',
                'date',
                'assignments',
                'subjects',
                'studentLeaves',
                'studentFees',
                'notice_board'
            )
        );
    }
}