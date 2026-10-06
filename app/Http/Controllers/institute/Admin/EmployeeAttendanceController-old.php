<?php


namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeAttendanceLogs;
use App\Models\EmployeeDetails;
use App\Models\Shifts;
use Carbon\Carbon;
use DB;


class EmployeeAttendanceController extends Controller
{
public function showAttendance(Request $request)
{
      $userId = auth()->id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }
        $employee = EmployeeDetails::where('user_id', $userId)->first();
    if (!$employee) {
        return back()->with('error', 'Employee record not found.');
    }
    // employee_id is the code like EMPB410D1A3
    $employeeId = $employee->employee_id;
    $todayIST = Carbon::today('Asia/Kolkata')->toDateString();
    $startUTC = Carbon::parse($todayIST . ' 00:00:00', 'Asia/Kolkata')->setTimezone('UTC');
    $endUTC = Carbon::parse($todayIST . ' 23:59:59', 'Asia/Kolkata')->setTimezone('UTC');
    $attendance = EmployeeAttendance::where('employee_id', $employeeId)
        ->whereBetween('created_at', [$startUTC, $endUTC])
        ->with(['logs' => function ($q) {
            $q->orderBy('check_time');
        }])
        ->first();
    if ($attendance) {
        $attendance->logs->transform(function ($log) {
            $log->check_time = Carbon::parse($log->check_time, 'UTC')->setTimezone('Asia/Kolkata');
            return $log;
        });
    }
    return view('instituteAdmin.EmployeeFiles.ViewEmpAttendance', compact('attendance', 'employee'));
}



    // Check-in
 public function checkIn(Request $request)
    {
        $employeeId = $request->employee_id;
        $todayUTC = Carbon::today('UTC')->toDateString();
        $nowUTC = Carbon::now('UTC');

        // Create or get today's attendance
        $attendance = EmployeeAttendance::firstOrCreate(
            ['employee_id' => $employeeId, 'date' => $todayUTC],
            ['status' => 'Present', 'total_hours' => 0]
        );

        // Prevent duplicate check-in
        $existingInLog = EmployeeAttendanceLogs::where('attendance_id', $attendance->id)
            ->where('check_type', 'IN')
            ->exists();

        if ($existingInLog) {
            return response()->json(['error' => 'Already checked in today'], 422);
        }

        // Store in UTC
        $log = EmployeeAttendanceLogs::create([
            'attendance_id' => $attendance->id,
            'check_type' => 'IN',
            'check_time' => $nowUTC,
            'ip_address' => $request->getClientIp(),
            'device_info' => $request->header('User-Agent'),
            'location' => $request->location ?? null,
        ]);

        return response()->json([
            'success' => 'Check-in recorded',
            'log_time' => Carbon::parse($log->check_time, 'UTC')->setTimezone('Asia/Kolkata')->format('H:i:s')
        ]);
    }

public function checkOut(Request $request)
{
    $employeeId = $request->employee_id;
    $todayUTC = Carbon::today('UTC')->toDateString();
    $nowUTC = Carbon::now('UTC');

    $attendance = EmployeeAttendance::where('employee_id', $employeeId)
        ->where('date', $todayUTC)
        ->first();

    if (!$attendance) {
        return response()->json(['error' => 'No attendance for today'], 422);
    }

    $existingOutLog = EmployeeAttendanceLogs::where('attendance_id', $attendance->id)
        ->where('check_type', 'OUT')
        ->first();

    if ($existingOutLog) {
        return response()->json(['error' => 'Already checked out today'], 422);
    }

    // Save log in UTC
    $log = EmployeeAttendanceLogs::create([
        'attendance_id' => $attendance->id,
        'check_type' => 'OUT',
        'check_time' => $nowUTC, // stored in UTC
        'ip_address' => $request->ip(),
        'device_info' => $request->header('User-Agent'),
        'location' => $request->location ?? null,
    ]);

    // Calculate total hours in IST
    $logs = EmployeeAttendanceLogs::where('attendance_id', $attendance->id)
        ->orderBy('check_time')
        ->get();

    $totalMinutes = 0;
    $lastIn = null;
    $lastOutIST = null;

    foreach ($logs as $l) {
        $logTimeIST = Carbon::parse($l->check_time, 'UTC')->setTimezone('Asia/Kolkata');
        if ($l->check_type === 'IN') {
            $lastIn = $logTimeIST;
        } elseif ($l->check_type === 'OUT' && $lastIn) {
            $totalMinutes += $lastIn->diffInMinutes($logTimeIST);
            $lastOutIST = $logTimeIST;
            $lastIn = null;
        }
    }

    $attendance->total_hours = round($totalMinutes / 60, 2);

    // ----------------------------
    // SHIFT STATUS COMPARISON (in IST)
    // ----------------------------
    $status = 'Present';
    $shift = EmployeeDetails::where('employee_id', $employeeId)->with('shift')->first()?->shift;

    if ($shift) {
        $shiftStartIST = Carbon::today('Asia/Kolkata')->setTimeFromTimeString($shift->start_time);
        $shiftEndIST   = Carbon::today('Asia/Kolkata')->setTimeFromTimeString($shift->end_time);

        if ($shiftEndIST->lessThanOrEqualTo($shiftStartIST)) {
            $shiftEndIST->addDay(); // for overnight shifts
        }

        $earlyLeaveThreshold = $shiftEndIST->copy()->subMinutes(10);

        // Use last OUT time in IST
        $compareTime = $lastOutIST ?? Carbon::now('Asia/Kolkata');

        if ($attendance->total_hours <= 0) {
            $status = 'Absent';
        } elseif ($compareTime->lt($earlyLeaveThreshold)) {
            $status = 'Early Leave';
        } elseif ($compareTime->gt($shiftEndIST)) {
            $status = 'Overtime';
        } else {
            $status = 'Present';
        }
    }

    $attendance->status = $status;
    $attendance->save();

    return response()->json([
        'success' => 'Check-out recorded successfully',
        'total_hours' => $attendance->total_hours,
        'status' => $attendance->status,
        'log' => $log
    ]);
}

    /**
     * Monthly Attendance Report
     */
    public function monthlyAttendance(Request $request)
    {
        $merchantId = auth()->user()->institute_id;
        $month = $request->get('month', now()->month);
        $year  = $request->get('year', now()->year);

        $employees = EmployeeDetails::where('institute_id',$merchantId)->with('shift')->get();

        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate   = $startDate->copy()->endOfMonth();

        // Dates list
        $dates = collect();
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates->push($date->toDateString());
        }

        // Attendance records
        $attendance = EmployeeAttendance::whereBetween('date', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($item) {
                return $item->employee_id . '_' . Carbon::parse($item->date)->toDateString();
            });

        return view('instituteAdmin.EmployeeFiles.viewEmployeeMonthlyAttendance', compact(
            'employees', 'dates', 'attendance', 'month', 'year'
        ));
    }

public function myAttendance(Request $request)
{
       $userId = auth()->id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }
        $employee = EmployeeDetails::where('user_id', $userId)->first();
    if (!$employee) {
        return back()->with('error', 'Employee record not found.');
    }
    // employee_id is the code like EMPB410D1A3
    $employeeId = $employee->employee_id;
    // $userId = Auth::id();
    $month = $request->month ?? date('m');
    $year  = $request->year ?? date('Y');
    // Step 1: Find employee by user ID
    $employee = EmployeeDetails::where('user_id', $userId)->first();
    if (!$employee) {
        return back()->with('error', 'Employee record not found.');
    }
    // Step 2: Get all attendances for this month
    $attendances = EmployeeAttendance::where('employee_id', $employeeId)
        ->whereMonth('date', $month)
        ->whereYear('date', $year)
        ->orderBy('date')
        ->get()
        ->keyBy('date');
    // calculate totals
    $totalPresent = $attendances->where('status', 'Present')->count();
    $totalAbsent = $attendances->where('status', 'Absent')->count();
    $totalLeave  = $attendances->where('status', 'Leave')->count();
    $totalNoRecord = now()->daysInMonth - ($totalPresent + $totalAbsent + $totalLeave);
    return view('instituteAdmin.EmployeeFiles.ParticularEmployeeAttendance', compact('attendances', 'month', 'year','totalPresent','totalAbsent','totalLeave','totalNoRecord'));
}


}
