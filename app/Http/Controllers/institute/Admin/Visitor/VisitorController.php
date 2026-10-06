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
use Carbon\Carbon;
use App\Mail\CommonMail;
use Illuminate\Support\Facades\Mail;

class VisitorController extends Controller
{
    use InstituteBranchAccess,DepartmentRelationships;

    public function visitorRegister(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'visitor_code' => 'required|unique:visitors,visitor_code',
                'name' => 'required|string|max:255',
                'contact_number' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'purpose' => 'required|string|max:255',
                'vehicle_type' => 'nullable|string|max:50',
                'vehicle_number' => 'nullable|string|max:50',
                'vehicle_color' => 'nullable|string|max:50',
                'visitor_photo' => 'nullable|image|max:5120', // 5MB max
                'vehicle_photos.*' => 'nullable|image|max:5120',
                'otp_verified' => 'required|boolean',
                'otp' => 'nullable|string|max:6',
                'additional_notes' => 'nullable|string',
            ]);
            
            // Handle file uploads
            $visitorPhotoPath = null;
            if ($request->hasFile('visitor_photo')) {
                $visitorPhotoPath = $request->file('visitor_photo')->store('visitor_photos', 'public');
            }
            
            $vehiclePhotosPaths = [];
            if ($request->hasFile('vehicle_photos')) {
                foreach ($request->file('vehicle_photos') as $photo) {
                    if ($photo->isValid()) {
                        $vehiclePhotosPaths[] = $photo->store('vehicle_photos', 'public');
                    }
                }
            }
            $context = $this->getInstituteBranchContext();
            // Check if user has institute access
            if (!$context['institute_id']) {
                throw new \Exception('You are not associated with any institute.');
            }
            // Create visitor record
            $visitor = Visitor::create([
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                'visitor_code' => $request->visitor_code,
                'name' => $request->name,
                'contact_number' => $request->contact_number,
                'email' => $request->email,
                'purpose' => $request->purpose,
                'visitor_photo' => $visitorPhotoPath,
                'vehicle_type' => $request->vehicle_type,
                'vehicle_number' => $request->vehicle_number,
                'vehicle_color' => $request->vehicle_color,
                'vehicle_photos' => !empty($vehiclePhotosPaths) ? json_encode($vehiclePhotosPaths) : null,
                'otp_verified' => $request->otp_verified,
                'otp' => $request->otp,
                'otp_expires_at' => now()->addMinutes(5),
                'registration_type' => $request->registration_type,
                'status' => $request->status,
                'registration_time' => $request->registration_time ?? now(),
                'additional_notes' => $request->additional_notes,
            ]);

            if($request->registration_type  == 'Walk-in'){
                $last = VisitorCheckin::orderBy('id', 'desc')->first();
                $nextNumber = $last 
                    ? intval(substr($last->visitor_checkin_id, 4)) + 1 
                    : 1;
                $visitorCheckinId = 'VCI-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
                VisitorCheckin::create([
                    'institute_id' => $context['institute_id'],
                    'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
                    "visitor_code"=>$request->visitor_code,
                    "gate_id"=>null,
                    "visitor_checkin_id"=>$visitorCheckinId,
                    "employee_id"=>null,
                    "assign_by"=>null,
                    "check_in_time"=>now(),
                    "front_desk_id"=>"FDI-1544HHGGVH",
                    "check_in_status"=> "allowed",
                    'checkin_remarks' => null,
                    'updated_at' => now(),
                ]);
            }
            $datetime = $request->registration_time;
            $carbon = Carbon::parse($datetime)->setTimezone('Asia/Kolkata');
            Mail::to($request->email)->send(
                new CommonMail(
                    'emails.visitor_registration',
                    [
                        'visitor_name' => $request->name,
                        'purpose'      => $request->purpose,
                        'visit_date'   => $carbon->format('d-m-Y'),
                        'visit_time'   => $carbon->format('H:i:s'),
                        'visitor_code' => $request->visitor_code,
                    ],
                    'Visitor Registration Successful'
                )
            );
            return response()->json([
                'success' => true,
                'message' => 'Visitor registered successfully!',
                'data' => $visitor,
                'visitor_code' => $visitor->visitor_code
            ], 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }
    public function index(Request $request)
    {
        $query = Visitor::query();
        
        // Apply institute/branch filter if needed
        if (Auth::user()->institute_id) {
            $query->where('institute_id', Auth::user()->institute_id);
        }
        
        if (Auth::user()->branch_id) {
            $query->where('branch_id', Auth::user()->branch_id);
        }
        
        // Apply search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('visitor_code', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Order by latest
        $query->orderBy('created_at', 'desc');
        
        // Get paginated results
        $visitors = $query->paginate(25);
        
        // Get statistics
        $context = $this->getInstituteBranchContext();
        $totalVisitors = Visitor::where('institute_id', $context['institute_id'])->count();
        $registeredVisitors = Visitor::where('institute_id', $context['institute_id'])->where('status', 'registered')->count();
        $checkedInVisitors = Visitor::where('institute_id', $context['institute_id'])->where('status', 'check-in')->count();
        $checkedOutVisitors = Visitor::where('institute_id', $context['institute_id'])->where('status', 'check-out')->count();
        
        // Time-based statistics
        $todayVisitors = Visitor::where('institute_id', $context['institute_id'])->whereDate('created_at', today())->count();
        $weekVisitors = Visitor::where('institute_id', $context['institute_id'])->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $monthVisitors = Visitor::where('institute_id', $context['institute_id'])->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $otpVerifiedVisitors = Visitor::where('institute_id', $context['institute_id'])->where('otp_verified', true)->count();
        return view('instituteAdmin.VisitorManagement.showRegistration', compact(
            'visitors',
            'totalVisitors',
            'registeredVisitors',
            'checkedInVisitors',
            'checkedOutVisitors',
            'todayVisitors',
            'weekVisitors',
            'monthVisitors',
            'otpVerifiedVisitors'
        ));
    }
    public function show($visitorId)
    {
        try {
            $context = $this->getInstituteBranchContext();
            
            // Get visitor details
            $visitor = Visitor::where('id', $visitorId)
                ->orWhere('visitor_code', $visitorId)
                ->first();
            
            if (!$visitor) {
                return response()->json([
                    'status' => false,
                    'message' => 'Visitor not found'
                ], 404);
            }
            
            $timeline = [];
            
            // 1. Registration (always exists)
            $timeline[] = [
                'type' => 'registration',
                'title' => 'Registration Created',
                'description' => 'Visitor registration was successfully created',
                'details' => 'Visitor code: ' . $visitor->visitor_code . 
                           ($visitor->registration_type ? ' | Type: ' . ucfirst($visitor->registration_type) : ''),
                'icon' => 'user-plus',
                'color' => 'primary',
                'timestamp' => $visitor->created_at,
                'status' => 'completed'
            ];
            
            // 2. OTP Verification (if applicable)
            if ($visitor->otp_verified && $visitor->otp_verified_at) {
                $timeline[] = [
                    'type' => 'otp_verification',
                    'title' => 'OTP Verified',
                    'description' => 'Mobile number verified via OTP',
                    'details' => 'Phone: ' . $visitor->contact_number,
                    'icon' => 'mobile-alt',
                    'color' => 'success',
                    'timestamp' => $visitor->otp_verified_at,
                    'status' => 'completed'
                ];
            }
            
            // 3. Check-in (if exists)
            $checkin = VisitorCheckin::where('visitor_code', $visitor->visitor_code)->first();
            if ($checkin) {
                $timeline[] = [
                    'type' => 'check_in',
                    'title' => 'Checked In',
                    'description' => 'Visitor checked in at reception',
                    'details' => 'Gate/Entry: ' . ($checkin->gate_id ?? 'Main Entrance') . 
                               ($checkin->check_in_method ? ' | Method: ' . ucfirst(str_replace('_', ' ', $checkin->check_in_method)) : ''),
                    'icon' => 'sign-in-alt',
                    'color' => 'info',
                    'timestamp' => $checkin->check_in_time,
                    'status' => 'completed'
                ];
            }
            
            // 4. Assigned to Employee (if status shows assigned)
            if (in_array($visitor->status, ['assign-to-employee', 'assigned'])) {
                $meeting = VisitorMeeting::where('visitor_code', $visitor->visitor_code)
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($meeting) {
                    $timeline[] = [
                        'type' => 'assigned',
                        'title' => 'Assigned to Employee',
                        'description' => 'Visitor assigned to employee for meeting',
                        'details' => 'Employee: ' . ($meeting->employee_name ?? 'N/A') . 
                                   ' | Department: ' . ($meeting->department_name ?? 'N/A') .
                                   ($meeting->meeting_date ? ' | Scheduled: ' . Carbon::parse($meeting->meeting_date)->format('d M Y') : ''),
                        'icon' => 'user-tie',
                        'color' => 'warning',
                        'timestamp' => $meeting->created_at,
                        'status' => 'completed'
                    ];
                }
            }
            
            // 5. Attended by (if status shows attend-by)
            if (in_array($visitor->status, ['attend-by', 'attended'])) {
                $attendentLog = VisitorFrontdeskLogs::where('visitor_code', $visitor->visitor_code)
                    ->where('visitors_log_status', 'completed')
                    ->first();
                
                if ($attendentLog) {
                    $timeline[] = [
                        'type' => 'attended',
                        'title' => 'Meeting Attended',
                        'description' => 'Visitor meeting completed',
                        'details' => 'Purpose: ' . $attendentLog->meeting_purpose . 
                                   ' | Type: ' . ucfirst($attendentLog->meeting_attendent_type ?? 'N/A'),
                        'icon' => 'handshake',
                        'color' => 'success',
                        'timestamp' => $attendentLog->updated_at,
                        'status' => 'completed'
                    ];
                }
            }
            
            // 6. Pass Generated (if out_pass_id exists)
            if ($visitor->out_pass_id) {
                $outPass = VisitorOutPass::where('out_pass_id', $visitor->out_pass_id)->first();
                if ($outPass) {
                    $timeline[] = [
                        'type' => 'pass_generated',
                        'title' => 'Out Pass Generated',
                        'description' => 'Visitor out pass issued',
                        'details' => 'Pass ID: ' . $outPass->out_pass_id . 
                                   ' | Valid: ' . Carbon::parse($outPass->valid_from)->format('h:i A') . 
                                   ' to ' . Carbon::parse($outPass->valid_to)->format('h:i A'),
                        'icon' => 'file-contract',
                        'color' => 'info',
                        'timestamp' => $outPass->created_at,
                        'status' => 'completed'
                    ];
                }
            }
            
            // 7. Check-out (if exists)
            $checkout = Visitorcheckout::where('visitor_code', $visitor->visitor_code)->first();
            if ($checkout) {
                $timeline[] = [
                    'type' => 'check_out',
                    'title' => 'Checked Out',
                    'description' => 'Visitor checked out from premises',
                    'details' => 'Status: ' . ucfirst($checkout->check_out_status ?? 'Verified') . 
                               ($checkout->checkout_remarks ? ' | Remarks: ' . $checkout->checkout_remarks : ''),
                    'icon' => 'sign-out-alt',
                    'color' => 'warning',
                    'timestamp' => $checkout->check_out_time,
                    'status' => 'completed'
                ];
            }
            
            // Sort timeline by timestamp
            usort($timeline, function($a, $b) {
                return strtotime($a['timestamp']) - strtotime($b['timestamp']);
            });
            
            // Get additional details for each status
            $visitorDetails = [
                'id' => $visitor->id,
                'visitor_code' => $visitor->visitor_code,
                'name' => $visitor->name,
                'contact_number' => $visitor->contact_number,
                'email' => $visitor->email,
                'purpose' => $visitor->purpose,
                'status' => $visitor->status,
                'registration_type' => $visitor->registration_type,
                'otp_verified' => $visitor->otp_verified,
                'out_pass_id' => $visitor->out_pass_id,
                'created_at' => $visitor->created_at,
                'updated_at' => $visitor->updated_at,
                'timeline' => $timeline
            ];
            
            // return response()->json([
            //     'status' => true,
            //     'data' => $visitorDetails
            // ]);
            return view('instituteAdmin.VisitorManagement.viewRegistration', compact('visitor','visitorDetails'));
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch visitor timeline',
                'error' => $e->getMessage()
            ], 500);
        }
        // $visitor = Visitor::findOrFail($id);
        // return view('instituteAdmin.VisitorManagement.viewRegistration', compact('visitor'));
    }
    
    // public function create()
    // {
    //     return view('visitors.create');
    // }
    
    public function edit($id)
    {
        $visitor = Visitor::findOrFail($id);
        return view('visitors.edit', compact('visitor'));
    }
    
    public function checkIn($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->update([
            'status' => 'Checked In',
            'checked_in_at' => now()
        ]);
        
        return redirect()->route('visitors.index')
            ->with('success', 'Visitor checked in successfully.');
    }
    
    public function checkOut($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->update([
            'status' => 'Checked Out',
            'checked_out_at' => now()
        ]);
        
        return redirect()->route('visitors.index')
            ->with('success', 'Visitor checked out successfully.');
    }
    
    public function destroy($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->delete();
        
        return redirect()->route('visitors.index')
            ->with('success', 'Visitor deleted successfully.');
    }

    // Fetch visitor by code
    public function fetchByCode(Request $request)
    {
        $request->validate([
            'visitor_code' => 'required|string|max:20',
        ]);
        $context = $this->getInstituteBranchContext();
        // Check if user has institute access
        if (!$context['institute_id']) {
            throw new \Exception('You are not associated with any institute.');
        }
        $visitor = $this->getCommonQuery(Visitor::class)
            ->where('visitor_code', $request->visitor_code)
            ->first();
        // $visitor = Visitor::where('visitor_code', $request->visitor_code)->first();

        if (!$visitor) {
            return response()->json([
                'success' => false,
                'message' => 'Visitor not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'visitor' => [
                'id' => $visitor->id,
                'code' => $visitor->visitor_code,
                'name' => $visitor->name,
                'contact' => $visitor->contact_number,
                'email' => $visitor->email,
                'purpose' => $visitor->purpose,
                'vehicle_type' => $visitor->vehicle_type,
                'vehicle_number' => $visitor->vehicle_number,
                'vehicle_color' => $visitor->vehicle_color,
                'registration_time' => $visitor->registration_time,
                'registration_type'=>$visitor->registration_type,
                'status' => $visitor->status,
                'visitor_photo' => $visitor->visitor_photo,
                'vehicle_photos' => json_decode($visitor->vehicle_photos, true) ?? [],
                'additional_notes' => $visitor->additional_notes,
                'out_pass_id' => $visitor->out_pass_id ?? null,
                'created_at' => $visitor->created_at,
            ]
        ]);
    }
     // Check-in visitor
    public function visitorcheckIn(Request $request)
    {
        $request->validate([
            'visitor_id' => 'required|integer',
        ]);
        $context = $this->getInstituteBranchContext();
        $visitor = Visitor::find($request->visitor_id);

        if (!$visitor) {
            return response()->json([
                'success' => false,
                'message' => 'Visitor not found'
            ], 404);
        }
        $last = VisitorCheckin::orderBy('id', 'desc')->first();

        $nextNumber = $last 
            ? intval(substr($last->visitor_checkin_id, 4)) + 1 
            : 1;

        $visitorCheckinId = 'VCI-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        
        VisitorCheckin::create([
            'institute_id' => $context['institute_id'],
            'branch_id' => $context['is_branch_admin'] ? $context['branch_id'] : null,
            "visitor_code"=>$visitor->visitor_code,
            "gate_id"=>null,
            "visitor_checkin_id"=>$visitorCheckinId,
            "employee_id"=>null,
            "assign_by"=>null,
            "check_in_time"=>now(),
            "front_desk_id"=>"FDI-1544HHGGVH",
            "check_in_status"=> "allowed",
            'checkin_remarks' => null,
            'updated_at' => now(),
        ]);
        // Update status to checked_in
        $visitor->update([
            'status' => 'check-in',
            'updated_at' => now(),
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Check-in successful',
            'visitor' => [
                'code' => $visitor->visitor_code,
                'name' => $visitor->name,
                'status' => $visitor->status,
            ]
        ]);
    }
}