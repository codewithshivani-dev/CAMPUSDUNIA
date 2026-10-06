<?php
// app/Http/Controllers/institute/Admin/WFHRequestController.php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WFHRequest;
use App\Models\EmployeeDetails;
use App\Models\EmployeeShift;
use App\Models\Shift;
use App\Traits\InstituteBranchAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AdminWFHRequestController extends Controller
{
    use InstituteBranchAccess;

    /**
     * Display a listing of WFH requests.
     */
    public function index(Request $request)
    {
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];

        if (!$instituteId) {
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        // Build query
        $query = WFHRequest::with(['employee', 'processor'])
            ->where('institute_id', $instituteId)
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('employee_name')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->employee_name . '%');
            });
        }

        if ($request->filled('employee_code')) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('employee_code', 'LIKE', '%' . $request->employee_code . '%');
            });
        }

        if ($request->filled('request_status')) {
            $query->where('request_status', $request->request_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('end_date', '<=', $request->date_to);
        }

        // Get requests
        $requests = $query->paginate(20);

        // Get filter options
        $statusOptions = ['pending', 'approved', 'rejected', 'cancelled', 'completed'];

        // Get employee list for filter
        $employees = EmployeeDetails::where('institute_id', $instituteId)
            ->select('employee_id', 'name', 'employee_code')
            ->get();

        // Statistics
        $stats = $this->getStatistics($instituteId);

        return view('instituteAdmin.WFHRequests.index', [
            'requests' => $requests,
            'statusOptions' => $statusOptions,
            'employees' => $employees,
            'filters' => $request->all(),
            'stats' => $stats,
            'user_type' => $context['is_branch_admin'] ? 'branch_admin' : 'institute_admin'
        ]);
    }

    /**
     * Get statistics for dashboard.
     */
    private function getStatistics($instituteId)
    {
        return [
            'total' => WFHRequest::where('institute_id', $instituteId)->count(),
            'pending' => WFHRequest::where('institute_id', $instituteId)
                ->where('request_status', 'pending')->count(),
            'approved' => WFHRequest::where('institute_id', $instituteId)
                ->where('request_status', 'approved')->count(),
            'rejected' => WFHRequest::where('institute_id', $instituteId)
                ->where('request_status', 'rejected')->count(),
            'completed' => WFHRequest::where('institute_id', $instituteId)
                ->where('request_status', 'completed')->count(),
            'today' => WFHRequest::where('institute_id', $instituteId)
                ->whereDate('created_at', today())->count(),
            'this_week' => WFHRequest::where('institute_id', $instituteId)
                ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->count(),
            'active_today' => WFHRequest::where('institute_id', $instituteId)
                ->where('request_status', 'approved')
                ->whereDate('start_date', '<=', today())
                ->whereDate('end_date', '>=', today())
                ->count(),
        ];
    }

    /**
     * Show form for creating a new WFH request (for admin to create on behalf).
     */
    public function create()
    {
        $context = $this->getInstituteBranchContext();
        $instituteId = $context['institute_id'];

        $employees = EmployeeDetails::where('institute_id', $instituteId)
            ->select('employee_id', 'name', 'employee_code', 'department_id')
            ->with('department')
            ->get();

        return view('instituteAdmin.WFHRequests.create', [
            'employees' => $employees,
            'user_type' => $context['is_branch_admin'] ? 'branch_admin' : 'institute_admin'
        ]);
    }

    /**
     * Store a newly created WFH request.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required|exists:employee_details,employee_id',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
                'start_time' => 'nullable|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i|after:start_time',
                'reason' => 'nullable|string|max:500',
                'work_plan' => 'nullable|string|max:1000',
                'emergency_contact' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $context = $this->getInstituteBranchContext();
            $instituteId = $context['institute_id'];
            $branchId = $context['branch_id'];

            // Check for overlapping WFH requests
            $overlapping = $this->checkOverlappingRequests(
                $request->employee_id,
                $request->start_date,
                $request->end_date
            );

            if ($overlapping) {
                return redirect()->back()
                    ->with('error', 'Employee already has a WFH request for this date range.')
                    ->withInput();
            }

            // Create WFH request
            $wfhRequest = WFHRequest::create([
                'institute_id' => $instituteId,
                'branch_id' => $branchId,
                'employee_id' => $request->employee_id,
                'request_date' => now()->toDateString(),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'reason' => $request->reason,
                'work_plan' => $request->work_plan,
                'emergency_contact' => $request->emergency_contact,
                'request_status' => 'pending',
                'is_active' => true,
                'additional_data' => [
                    'created_by_admin' => true,
                    'admin_id' => Auth::id(),
                    'admin_name' => Auth::user()->name,
                ]
            ]);

            // Log activity
            Log::info('WFH request created by admin', [
                'request_id' => $wfhRequest->request_id,
                'employee_id' => $request->employee_id,
                'admin_id' => Auth::id()
            ]);

            return redirect()->route('institute-admin.wfh-requests.index')
                ->with('success', 'WFH request created successfully. Request ID: ' . $wfhRequest->request_id);

        } catch (\Exception $e) {
            Log::error('Error creating WFH request: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create WFH request: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified WFH request.
     */
    public function show($id)
    {
        try {
            $request = WFHRequest::with(['employee', 'employee.department', 'processor'])
                ->findOrFail($id);

            // Get employee's shift for the date
            $shiftInfo = $this->getEmployeeShiftForDate($request->employee_id, $request->start_date);

            // Get overlapping requests
            $overlapping = $this->checkOverlappingRequests(
                $request->employee_id,
                $request->start_date,
                $request->end_date,
                $request->id
            );

            return view('instituteAdmin.WFHRequests.show', [
                'request' => $request,
                'shiftInfo' => $shiftInfo,
                'overlapping' => $overlapping,
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching WFH request: ' . $e->getMessage());
            return redirect()->route('institute-admin.wfh-requests.index')
                ->with('error', 'Request not found.');
        }
    }

    /**
     * Update request status (Approve/Reject).
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:approved,rejected,completed',
                'remarks' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status provided'
                ], 422);
            }

            $wfhRequest = WFHRequest::findOrFail($id);

            // Check if request can be processed
            if (!$wfhRequest->canApprove() && $request->status !== 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'This request cannot be processed as it is already ' . $wfhRequest->request_status
                ], 400);
            }

            // Check if already completed
            if ($request->status === 'completed' && !$wfhRequest->isApproved()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only approved requests can be marked as completed'
                ], 400);
            }

            // Update status
            if ($request->status === 'approved') {
                $wfhRequest->approve(Auth::id(), $request->remarks);
                $message = 'WFH request approved successfully';
            } elseif ($request->status === 'rejected') {
                $wfhRequest->reject(Auth::id(), $request->remarks);
                $message = 'WFH request rejected';
            } elseif ($request->status === 'completed') {
                $wfhRequest->complete();
                $message = 'WFH request marked as completed';
            }

            // Log activity
            Log::info('WFH request status updated', [
                'request_id' => $wfhRequest->request_id,
                'status' => $request->status,
                'admin_id' => Auth::id(),
                'remarks' => $request->remarks
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $wfhRequest
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating WFH request status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update WFH requests status.
     */
    public function bulkUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'request_ids' => 'required|array',
                'request_ids.*' => 'exists:wfh_requests,id',
                'status' => 'required|in:approved,rejected,completed',
                'remarks' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid input data'
                ], 422);
            }

            $updatedCount = 0;
            $errors = [];

            foreach ($request->request_ids as $requestId) {
                try {
                    $wfhRequest = WFHRequest::find($requestId);
                    
                    if (!$wfhRequest->canApprove() && $request->status !== 'completed') {
                        $errors[] = "Request {$wfhRequest->request_id} cannot be processed";
                        continue;
                    }

                    if ($request->status === 'approved') {
                        $wfhRequest->approve(Auth::id(), $request->remarks);
                    } elseif ($request->status === 'rejected') {
                        $wfhRequest->reject(Auth::id(), $request->remarks);
                    } elseif ($request->status === 'completed') {
                        $wfhRequest->complete();
                    }

                    $updatedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Error processing request ID: {$requestId}";
                    Log::error('Bulk update error: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => $updatedCount . ' request(s) updated successfully',
                'updated_count' => $updatedCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            Log::error('Error in bulk update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update requests: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export WFH requests to CSV/Excel.
     */
    public function export(Request $request)
    {
        try {
            $context = $this->getInstituteBranchContext();
            $instituteId = $context['institute_id'];

            $query = WFHRequest::with(['employee'])
                ->where('institute_id', $instituteId);

            // Apply filters
            if ($request->filled('status')) {
                $query->where('request_status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('start_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('end_date', '<=', $request->date_to);
            }

            $requests = $query->get();

            // Prepare CSV data
            $csvData = [];
            $csvData[] = [
                'Request ID',
                'Employee',
                'Employee Code',
                'Start Date',
                'End Date',
                'Duration',
                'Reason',
                'Status',
                'Request Date',
                'Approved Date'
            ];

            foreach ($requests as $wfhRequest) {
                $csvData[] = [
                    $wfhRequest->request_id,
                    $wfhRequest->employee->name ?? 'N/A',
                    $wfhRequest->employee->employee_code ?? 'N/A',
                    Carbon::parse($wfhRequest->start_date)->format('Y-m-d'),
                    Carbon::parse($wfhRequest->end_date)->format('Y-m-d'),
                    $wfhRequest->duration,
                    $wfhRequest->reason,
                    ucfirst($wfhRequest->request_status),
                    $wfhRequest->created_at->format('Y-m-d H:i'),
                    $wfhRequest->approved_at ? $wfhRequest->approved_at->format('Y-m-d H:i') : 'N/A'
                ];
            }

            // Generate CSV
            $filename = 'wfh_requests_' . date('Ymd_His') . '.csv';
            $handle = fopen('php://temp', 'r+');
            
            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }
            
            rewind($handle);
            $content = stream_get_contents($handle);
            fclose($handle);

            return response($content)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            Log::error('Error exporting WFH requests: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export data');
        }
    }

    /**
     * Get WFH request details for modal view.
     */
    public function getDetails($id)
    {
        try {
            $request = WFHRequest::with(['employee', 'employee.department', 'processor'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'request' => $request,
                    'employee' => $request->employee,
                    'formatted' => [
                        'date_range' => $request->formatted_date_range,
                        'duration' => $request->duration,
                        'status_badge' => $request->status_badge,
                        'request_date' => $request->created_at->format('d M, Y H:i'),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching WFH request details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch request details'
            ], 500);
        }
    }

    /**
     * Check for overlapping WFH requests.
     */
    private function checkOverlappingRequests($employeeId, $startDate, $endDate, $excludeId = null)
    {
        $query = WFHRequest::where('employee_id', $employeeId)
            ->where('is_active', true)
            ->whereIn('request_status', ['pending', 'approved'])
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                         ->where('end_date', '>=', $endDate);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get employee's shift for a specific date.
     */
    private function getEmployeeShiftForDate($employeeId, $date)
    {
        try {
            $employee = EmployeeDetails::where('employee_id', $employeeId)->first();
            if (!$employee) return null;

            // Get shift using your existing logic
            $shiftData = $this->getEmployeeEffectiveShift($employee, $date);
            
            if ($shiftData) {
                return [
                    'shift_name' => $shiftData->shift->shift_name ?? 'N/A',
                    'start_time' => $shiftData->shift->start_time ?? 'N/A',
                    'end_time' => $shiftData->shift->end_time ?? 'N/A',
                ];
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Error getting employee shift: ' . $e->getMessage());
            return null;
        }
    }

    // Helper method to get employee effective shift (adapt from your existing code)
    private function getEmployeeEffectiveShift($employee, $date)
    {
        // This should use your existing getEmployeeEffectiveShift logic
        // but with the provided date instead of current date
        // You can extract this logic to a trait or service class for reusability
        
        // For now, return null as placeholder
        return null;
    }
}