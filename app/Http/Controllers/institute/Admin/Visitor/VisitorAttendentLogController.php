<?php
namespace App\Http\Controllers\institute\Admin\Visitor;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\VisitorCheckin;
use App\Models\VisitorOutPass;
use App\Models\VisitorFrontdeskLogs;
use App\Models\Visitorcheckout;
use App\Models\Departments;
use App\Models\VisitorMeeting;
use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;

class VisitorAttendentLogController extends Controller
{
    use InstituteBranchAccess,DepartmentRelationships;
    /**
     * Store Visitor Attendent Log
     */
    
    public function getVisitorDepartment(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            throw new \Exception('You are not associated with any institute.');
        }

        $departments = $this->getCommonQuery(Departments::class)
            ->orderBy('department')
            ->get(['department_id', 'department']);

        return response()->json([
            'status' => true,
            'data' => $departments
        ]);
    }
    public function visitorLogsStore(Request $request)
    {
        $request->validate([
            'visitor_code' => 'required|string',
            'meeting_purpose' => 'required|string',
            'meeting_attendent_type' => 'required|in:self,transfer',
        ]);

        DB::beginTransaction();

        try {

            // Generate Visitor Attendent Log ID
            do {
                $visitorFrontdeskLogsId = 'VAL-' . now()->format('ymdHis') . rand(100,999);
            } while (
                VisitorFrontdeskLogs::where('visitor_attendent_log_id', $visitorFrontdeskLogsId)->exists()
            );

            $visitors_log_status = $request->meeting_attendent_type === 'self'
                ? 'completed'
                : 'in_progress';

            $context = $this->getInstituteBranchContext();
            // Check if user has institute access
            if (!$context['institute_id']) {
                throw new \Exception('You are not associated with any institute.');
            }

            $log = VisitorFrontdeskLogs::create([
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                'visitor_attendent_log_id' => $visitorFrontdeskLogsId,
                'front_desk_id' => $request->front_desk_id ?? null,
                'visitor_code' => $request->visitor_code,
                'meeting_purpose' => $request->meeting_purpose,
                'meeting_attendent_type' => $request->meeting_attendent_type,
                'self_attendent_remarks' => $request->self_attendent_remarks ?? null,
                'visitors_log_status' => $visitors_log_status,
            ]);
            Visitor::where('visitor_code',$request->visitor_code)->update([
                 'status'=> 'assign-to-employee'
            ]);
            // Auto create Out Pass for self
            if ($request->meeting_attendent_type === 'self') {

                do {
                    $letters = Str::upper(Str::random(4));
                    $numbers = rand(10, 99); // 2 digits
                    $outPassId = 'OP-' . $letters . $numbers;
                } while (
                    VisitorOutPass::where('out_pass_id', $outPassId)->exists()
                );
                Visitor::where('visitor_code',$request->visitor_code)->update([
                 'status'=> 'attended-by'
                ]);
                VisitorOutPass::create([
                    'institute_id' => $context['institute_id'],
                    'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                    'out_pass_id' => $outPassId,
                    'visitor_attendent_log_id' => $visitorFrontdeskLogsId,
                    'front_desk_id' => $request->front_desk_id ?? null,
                    'visitor_code' => $request->visitor_code,
                    'valid_date' => $request->valid_date ?? now()->toDateString(),
                    'valid_from' => $request->valid_from ?? now(),
                    'valid_to' => $request->valid_to ?? now()->addHours(2),
                    'pass_status' => 'active',
                    'remarks' => $request->remarks ?? null,
                ]);
                Visitor::where('visitor_code',$request->visitor_code)->update([
                 'status'=> 'generate-pass',
                 'out_pass_id'=>$outPassId
                ]);
            }else{
                do {
                    $meetingId = 'MT-' . Str::upper(Str::random(6));
                } while (
                    VisitorMeeting::where('meeting_id', $meetingId)->exists()
                );
                $meeting = VisitorMeeting::create([
                    'institute_id' => $context['institute_id'],
                    'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                    'front_desk_id' => $request->front_desk_id ?? null,
                    'visitor_attendent_log_id' => $visitorFrontdeskLogsId ?? null,

                    'meeting_id' => $meetingId,
                    'meeting_purpose' => $request->meeting_purpose,
                    'title' => $request->meetingTitle,
                    'description' => $request->description ?? null,

                    'visitor_code' => $request->visitor_code,

                    'employee_id' => $request->employee,
                    'employee_name' => $request->employee_name ?? null,

                    'department_category_id' => $request->department_category_id ?? null,
                    'department_category_name' => $request->department_category_name ?? null,

                    'department_id' => $request->department,
                    'department_name' => $request->department_name ?? null,
                    'meeting_date' => $request->meetingDate,
                    'meeting_time' => $request->meetingTime,
                    'scheduled_at' => now(),
                    'duration_minutes' => $request->duration,
                    'location' => $request->location ?? null,

                    'status' => 'scheduled',

                    'meeting_notes' => $request->notes ?? null,
                    'attachments' => $request->attachments ?? null,

                    'outcome_notes' => null,
                    'outcome_status' => 'needs_followup',
                    'follow_up_date' => $request->follow_up_date ?? null,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Visitor attendent log created successfully',
                'data' => $log,
                'out_pass_id' => $outPassId ?? null
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getVisitorDepartments(Request $request, $departmentId){
        
        $context = $this->getInstituteBranchContext();
        // Check if user has institute access
        if (!$context['institute_id']) {
            throw new \Exception('You are not associated with any institute.');
        }
        $department = $this->getCommonQuery(Departments::class)
            ->where('department_id', $departmentId)
            ->select('department')
            ->first();
        if($department){
            return response()->json([
                'status' => true,
                'data' => $department,
            ], 201);
        }else{
            return response()->json([
                'status' => false,
                'data' => " ",
            ], 400);
        }

    }
    public function getVisitorEmployee(Request $request, $employeeId){
        $context = $this->getInstituteBranchContext();
        // Check if user has institute access
        if (!$context['institute_id']) {
            throw new \Exception('You are not associated with any institute.');
        }
        $employeeid = $this->getCommonQuery(EmployeeDetails::class)
            ->where('employee_id', $employeeId)
            ->select('employee_code', 'name')
            ->first();

        if($employeeid){
            return response()->json([
                'success' => true,
                'data' => $employeeid,
            ], 201);
        }else{
            return response()->json([
                'success' => false,
                'data' => " ",
            ], 400);
        }
    }
    public function getMeetingSchedule(Request $request){

        $request->validate([
            'visitor_code' => 'required|string'
        ]);

        $context = $this->getInstituteBranchContext();
        // Check if user has institute access
        if (!$context['institute_id']) {
            throw new \Exception('You are not associated with any institute.');
        }
        $visitorMeetings = $this->getCommonQuery(VisitorMeeting::class)
            ->where('visitor_code', $request->visitor_code)
            ->first();

        if($visitorMeetings){
            return response()->json([
                'success' => true,
                'data' => $visitorMeetings,
            ], 201);
        }else{
            return response()->json([
                'success' => false,
                'data' => " ",
            ], 400);
        }
    }
    public function updateAttendentVerification(Request $request, $meetingId)
    {
        $context = $this->getInstituteBranchContext();

        // Institute access check
        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $visitorMeeting = $this->getCommonQuery(VisitorMeeting::class)
            ->where('meeting_id', $meetingId)
            ->first();

        if (!$visitorMeeting) {
            return response()->json([
                'success' => false,
                'message' => 'Meeting not found.'
            ], 404);
        }

        // Update verification fields (adjust column names as per DB)
        $visitorMeeting->update([
            // 'attendent_verified' => $request->attendent_verified ?? 1,
            // 'verified_by'        => auth()->id(),
            // 'verified_at'        => now(),
            'meeting_cancel_reason' => $request->cancellation_reason ?? null,
            'additional_details' => $request->cancellation_notes ?? null,
            'outcome_notes' => $request->outcome ?? null,
            'status' => $request->status,
        ]);
        Visitor::where('visitor_code',$visitorMeeting->visitor_code)->update([
            'status'=> 'attended-by'
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Attendent verification updated successfully.',
            'data'    => $visitorMeeting
        ], 200);
    }

    /**
     * List Logs (Institute / Branch wise)
     */
    // public function index(Request $request)
    // {
    //     $logs = VisitorAttendentLog::where('institute_id', $request->institute_id)
    //         ->when($request->branch_id, function ($q) use ($request) {
    //             $q->where('branch_id', $request->branch_id);
    //         })
    //         ->orderBy('id', 'desc')
    //         ->get();

    //     return response()->json([
    //         'status' => true,
    //         'data' => $logs
    //     ]);
    // }

    /**
     * Update Visitor Log Status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'visitors_log_status' => 'required|in:pending,approved,rejected,completed'
        ]);

        $log = VisitorAttendentLog::findOrFail($id);

        $log->update([
            'visitors_log_status' => $request->visitors_log_status
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Visitor log status updated successfully'
        ]);
    }
}
