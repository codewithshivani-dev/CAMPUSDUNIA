<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // ✅ Add this line
use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeAttendanceLogs; // Make sure you have this model
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function uploadAttendance(Request $request)
    {
        $records = $request->input('records', []);
        Log::info($records);
        dd('test');

        if (empty($records)) {
            return response()->json(['message' => 'No records received'], 400);
        }

        foreach ($records as $record) {
            Attendance::updateOrCreate(
                [
                    'user_id' => $record['user_id'],
                    'timestamp' => $record['timestamp']
                ],
                [
                    'synced_at' => now()
                ]
            );
        }

        return response()->json(['message' => 'Attendance data uploaded successfully']);
    }
}
