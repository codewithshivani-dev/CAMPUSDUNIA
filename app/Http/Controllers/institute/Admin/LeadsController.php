<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\AdmissionSlotAssignmentService;
use App\Models\StudentAdmissionProcess;
use App\Models\AdmissionProcessConfig;
use App\Models\CounsellingTimeSlot;
use App\Models\EntranceTest;
use App\Models\EntranceTestSlot;
use App\Models\InterviewRound;
use App\Models\FincapMerchantSubCategories;
use App\Models\InterviewConfiguration;
use App\Models\Departments;
use App\Models\InterviewRegistration;
use App\Models\EmployeeDetails;
use App\Models\CommonCustomFees;
use App\Models\RoundStatus;
use App\Models\LeadLog;
use App\Models\InterviewEditLog;
use App\Models\RolesForAdmissionInterviewProcess;
use App\Models\InstituteBasicDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class LeadsController extends Controller
{
    private function onboardingLink(Lead $lead): string
    {
        return URL::temporarySignedRoute(
            'admission.send.otp',
            now()->addDays(7),
            [
                'lead_id' => $lead->id,
                'institute_id' => $lead->institute_id,
                'purpose' => 'onboarding',
            ]
        );
    }

    private function candidateJourneyOtpLink(Lead $lead): string
    {
        return URL::temporarySignedRoute(
            'admission.send.otp',
            now()->addDays(7),
            [
                'lead_id' => $lead->lead_id,
                'purpose' => 'journey',
            ]
        );
    }

    /**
     * Display leads management page with tabs
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 🔹 Check Institute
        if (!$user || !$user->institute_id) {

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not associated with any institute.'
                ], 422);
            }

            return redirect()->back()
                ->with('error', 'You are not associated with any institute.');
        }


        $roles = RolesForAdmissionInterviewProcess::with(['employee', 'department'])
            ->where('institute_id', $user->institute_id)
            ->where('type', '!=', 'interviewer')
            ->get();

        $query = Lead::query()
            ->where('leads.institute_id', $user->institute_id)
            ->with([
                'AdmissionRegistration',
                'studentAdmissionProcess',
                'studentAdmissionProcess.admissionConfig',
                'studentAdmissionProcess.counsellingSlot',
                'studentAdmissionProcess.entranceTestSlot',
                'department'
            ])
            // Only show leads that have an associated admission registration
            ->whereHas('AdmissionRegistration', function ($query) {
                $query->whereNotNull('reference_id');
            });

        if ($request->filled('counselor')) {
            $query->where('leads.default_assign', $request->counselor);
        }

        $leads = $query
            ->orderBy('leads.created_at', 'desc')
            ->get();

        $departments = Departments::where('institute_id', $user->institute_id)->get();
        $Classes = FincapMerchantSubCategories::where('institute_id', $user->institute_id)->get();

        // 🔹 Assign Slots Service
        $service = app(AdmissionSlotAssignmentService::class);
        $service->assignSlots($user);
        // 🔹 Process steps JSON for each lead (optional)
        foreach ($leads as $lead) {
            if ($lead->steps) {
                $lead->steps = json_decode($lead->steps, true);
            } else {
                // Set default steps structure
                $lead->steps = [
                    'admission' => [
                        'status' => 'completed',
                        'fee' => false,
                        'enabled' => true,
                        'required' => true
                    ],
                    'entrance_test' => [
                        'status' => 'pending',
                        'fee' => false,
                        'enabled' => true,
                        'required' => false
                    ],
                    'counselling' => [
                        'status' => 'pending',
                        'assigned_to' => null,
                        'enabled' => true,
                        'required' => false
                    ],
                    'onboarding' => [
                        'status' => 'pending',
                        'enabled' => true,
                        'required' => true
                    ]
                ];
            }

            // 🔹 Attach admission config enabled fields to lead
            if ($lead->studentAdmissionProcess && $lead->studentAdmissionProcess->admissionConfig) {
                $config = $lead->studentAdmissionProcess->admissionConfig;
                $lead->admission_config_enabled = [
                    'admission_form' => (bool) $config->admission_form_enabled,
                    'entrance_tests' => (bool) $config->entrance_tests_enabled,
                    'counselling' => (bool) $config->counselling_enabled,
                    'onboarding' => (bool) $config->onboarding_enabled
                ];
            } else {
                // Default: all steps enabled if no config found
                $lead->admission_config_enabled = [
                    'admission_form' => true,
                    'entrance_tests' => true,
                    'counselling' => true,
                    'onboarding' => true
                ];
            }
        }
        Log::info($leads);
        // 🔹 Return JSON (AJAX)
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'leads' => $leads
            ]);
        }
        // dd($leads);
        // 🔹 Return View with studentlead data available through leads relationship
        return view('instituteAdmin.AddLead.CreateLead', compact('leads', 'roles', 'departments', 'Classes'));
    }

    /**
     * Display leads management page with tabs
     */
    public function getInterviewData(Request $request)
    {
        $user = Auth::user();

        // 🔹 Check Institute
        if (!$user || !$user->institute_id) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not associated with any institute.'
                ], 422);
            }
            return redirect()->back()
                ->with('error', 'You are not associated with any institute.');
        }

        $roles = RolesForAdmissionInterviewProcess::with(['employee', 'department'])
            ->where('institute_id', $user->institute_id)
            ->where('type', 'interviewer')
            ->get();

        // 🔥 Initialize RoundStatusController
        $roundStatusController = app(RoundStatusController::class);

        $query = Lead::query()
            ->where('leads.institute_id', $user->institute_id)
            ->where('lead_type', 'Interview')
            ->with([
                'InterviewRegistration.interviewConfiguration.interviewRounds',
                'roundStatuses'
            ]);

        $leads = $query
            ->orderBy('leads.created_at', 'desc')
            ->paginate(10);

        // 🔥 Initialize round status for all leads that don't have it
        foreach ($leads as $lead) {
            $exists = DB::table('round_status')
                ->where('lead_id', $lead->id)
                ->where('institute_id', $user->institute_id)
                ->exists();

            \Log::info("Lead ID: {$lead->id}, Exists: " . ($exists ? 'Yes' : 'No'));

            if (!$exists) {
                // Check if lead has interview rounds
                \Log::info("Lead {$lead->id} - InterviewRegistration: " . ($lead->InterviewRegistration ? 'Yes' : 'No'));

                if ($lead->InterviewRegistration) {
                    \Log::info("Lead {$lead->id} - InterviewConfiguration: " . ($lead->InterviewRegistration->interviewConfiguration ? 'Yes' : 'No'));

                    if ($lead->InterviewRegistration->interviewConfiguration) {
                        \Log::info("Lead {$lead->id} - InterviewRounds count: " . $lead->InterviewRegistration->interviewConfiguration->interviewRounds->count());
                    }
                }

                if (
                    $lead->InterviewRegistration &&
                    $lead->InterviewRegistration->interviewConfiguration &&
                    $lead->InterviewRegistration->interviewConfiguration->interviewRounds &&
                    $lead->InterviewRegistration->interviewConfiguration->interviewRounds->count() > 0
                ) {

                    \Log::info("Initializing round status for lead: {$lead->id}");
                    $result = $roundStatusController->initializeRoundStatus($lead->id);
                    \Log::info("Initialization result for lead {$lead->id}: " . json_encode($result));
                }
            }
        }

        // 🔥 Refresh leads to include the newly created round statuses
        $leads = $query
            ->orderBy('leads.created_at', 'desc')
            ->paginate(10);

        // Debug: Check round statuses after refresh
        foreach ($leads as $lead) {
            \Log::info("Lead {$lead->id} - RoundStatuses count after refresh: " . $lead->roundStatuses->count());
        }

        $departments = Departments::where('institute_id', $user->institute_id)->get();

        // 🔹 Return JSON (AJAX)
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'leads' => $leads
            ]);
        }

        return view('instituteAdmin.AddLead.Interview.AdminInterview', compact('leads', 'roles', 'departments'));
    }

    public function getHRInterviewData(Request $request)
    {
        $user = Auth::user();

        // 🔹 Check Institute
        if (!$user || !$user->institute_id) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not associated with any institute.'
                ], 422);
            }
            return redirect()->back()
                ->with('error', 'You are not associated with any institute.');
        }

        // Get the logged-in employee details
        $employee = EmployeeDetails::where('user_id', $user->id)->first();

        // Get all interviewers (for dropdown/filtering)
        $roles = RolesForAdmissionInterviewProcess::with(['employee', 'department'])
            ->where('institute_id', $user->institute_id)
            ->where('type', 'interviewer')
            ->get();

        // Build the query
        $query = Lead::query()
            ->where('leads.institute_id', $user->institute_id)
            ->where('lead_type', 'Interview')
            ->with([
                'interviewRegistration.interviewConfiguration.interviewRounds',
                'roundStatuses'
            ]);

        // 🔥 INTERVIEWER-SPECIFIC FILTERING
        if ($employee) {
            // Find the interviewer role for this employee
            $interviewerRole = $roles->first(function ($role) use ($employee) {
                return $role->employee_id == $employee->employee_id;
            });

            if ($interviewerRole) {
                // Show only leads assigned to this specific interviewer
                $query->where('default_assign_id', $interviewerRole->role_hash_id);
                \Log::info('Filtering leads for interviewer: ' . $employee->name);
            } else {
                // If user is not an interviewer, show empty results or handle appropriately
                \Log::warning('User is not an interviewer: ' . $employee->name);
                // Option A: Show no leads
                $query->whereRaw('1 = 0');
                // Option B: Show all leads (uncomment if you want)
                // No additional filter
            }
        } else {
            // If no employee record found, show no leads
            \Log::warning('No employee record found for user: ' . $user->id);
            $query->whereRaw('1 = 0');
        }

        $leads = $query
            ->orderBy('leads.created_at', 'desc')
            ->paginate(10);

        $departments = Departments::where('institute_id', $user->institute_id)->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'leads' => $leads
            ]);
        }

        return view('instituteAdmin.AddLead.Interview.interviewer', compact('leads', 'roles', 'departments'));
    }

    /**
     * Store a new lead (AJAX)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_no' => 'required|string|max:20',
            'applicant_type' => 'required|in:student,parent,guardian,other',
            'registration_mode' => 'nullable|in:online,offline,walkin,phone,referral',
            'lead_type' => 'required|in:hot,warm,cold',
            'lead_status' => 'required|in:new,contacted,follow_up,converted,lost,hot,warm,cold',
            'follow_up' => 'nullable|date',
            'default_assign' => 'nullable',
            'reference_id' => 'nullable|string|max:100'
        ]);

        $user = Auth::user();

        if (!$user || !$user->institute_id) {
            return response()->json([
                'success' => false,
                'message' => 'Institute not found'
            ], 422);
        }

        // Generate lead ID
        $leadCount = Lead::where('institute_id', $user->institute_id)->count();
        $lead_id = 'LEAD-' . str_pad($leadCount + 1, 4, '0', STR_PAD_LEFT);

        // Create lead
        $lead = Lead::create([
            'institute_id' => $user->institute_id,
            'branch_id' => $user->branch_id ?? null,
            'lead_id' => $lead_id,
            'reference_id' => $request->reference_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'applicant_type' => $request->applicant_type,
            'registration_mode' => $request->registration_mode,
            'lead_type' => $request->lead_type,
            'lead_status' => $request->lead_status,
            'follow_up' => now(),
            'default_assign' => $request->default_assign,
            "default_assign_id" => null,
            "default_assign_type" => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully',
            'lead' => $lead
        ]);
    }

    /**
     * Get lead for editing (AJAX)
     */
    public function edit($id)
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found'
            ], 404);
        }

        // Check if lead belongs to user's institute
        if ($lead->institute_id !== Auth::user()->institute_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'lead' => $lead
        ]);
    }

    /**
     * Update lead (AJAX) - Supports partial updates
     */


    public function update(Request $request, $id)
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found'
            ], 404);
        }

        // Check if lead belongs to user's institute
        if ($lead->institute_id !== Auth::user()->institute_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        Log::info('Update lead ID: ' . $id . ' with status: ' . $request->lead_status);
        // Prepare validation rules based on what's being updated
        $rules = [];
        $data = [];

        // Only validate and add fields that are present in request
        if ($request->has('name')) {
            $rules['name'] = 'required|string|max:255';
            $data['name'] = $request->name;
            if ($lead->name != $request->name) {

                LeadLog::create([
                    'lead_id' => $lead->lead_id,
                    'action' => 'Status Changed',
                    'field_name' => 'name',
                    'old_value' => $lead->name,
                    'new_value' => $request->name,
                ]);
            }
        }

        if ($request->has('email')) {
            $rules['email'] = 'nullable|email|max:255';
            $data['email'] = $request->email;
            if ($lead->email != $request->email) {

                LeadLog::create([
                    'lead_id' => $lead->lead_id,
                    'action' => 'Status Changed',
                    'field_name' => 'email',
                    'old_value' => $lead->email,
                    'new_value' => $request->email,
                ]);
            }
        }

        if ($request->has('phone_no')) {
            $rules['phone_no'] = 'required|string|max:20';
            $data['phone_no'] = $request->phone_no;
            if ($lead->phone_no != $request->phone_no) {

                LeadLog::create([
                    'lead_id' => $lead->lead_id,
                    'action' => 'Status Changed',
                    'field_name' => 'phone_no',
                    'old_value' => $lead->phone_no,
                    'new_value' => $request->phone_no,
                ]);
            }
        }

        if ($request->has('applicant_type')) {
            $rules['applicant_type'] = 'required|in:student,parent,guardian,other';
            $data['applicant_type'] = $request->applicant_type;
            if ($lead->applicant_type != $request->applicant_type) {

                LeadLog::create([
                    'lead_id' => $lead->lead_id,
                    'action' => 'Status Changed',
                    'field_name' => 'applicant_type',
                    'old_value' => $lead->applicant_type,
                    'new_value' => $request->applicant_type,
                ]);
            }
        }

        if ($request->has('registration_mode')) {
            $rules['registration_mode'] = 'nullable|in:online,offline,walkin,phone,referral';
            $data['registration_mode'] = $request->registration_mode;
            if ($lead->registration_mode != $request->registration_mode) {

                LeadLog::create([
                    'lead_id' => $lead->lead_id,
                    'action' => 'Status Changed',
                    'field_name' => 'registration_mode',
                    'old_value' => $lead->registration_mode,
                    'new_value' => $request->registration_mode,
                ]);
            }
        }

        if ($request->has('lead_type')) {
            $rules['lead_type'] = 'required';
            $data['lead_type'] = $request->lead_type;
            if ($lead->lead_type != $request->lead_type) {

                LeadLog::create([
                    'lead_id' => $lead->lead_id,
                    'action' => 'Status Changed',
                    'field_name' => 'lead_type',
                    'old_value' => $lead->lead_type,
                    'new_value' => $request->lead_type,
                ]);
            }
        }

        if ($request->has('lead_status')) {
            $rules['lead_status'] = 'required|in:new,contacted,follow_up,converted,lost,hot,warm,cold';
            $data['lead_status'] = $request->lead_status;
            if ($lead->lead_status != $request->lead_status) {

                LeadLog::create([
                    'lead_id' => $lead->lead_id,
                    'action' => 'Status Changed',
                    'field_name' => 'lead_status',
                    'old_value' => $lead->lead_status,
                    'new_value' => $request->lead_status,
                ]);
            }
        }

        if ($request->has('follow_up')) {
            $rules['follow_up'] = 'nullable|date';
            $data['follow_up'] = $request->follow_up ? date('Y-m-d H:i:s', strtotime($request->follow_up)) : null;
            if ($lead->follow_up != $data['follow_up']) {

                LeadLog::create([
                    'lead_id' => $lead->lead_id,
                    'action' => 'Status Changed',
                    'field_name' => 'follow_up',
                    'old_value' => $lead->follow_up,
                    'new_value' => $data['follow_up'],
                ]);
            }
        }

        if ($request->has('default_assign')) {
            $rules['default_assign'] = 'nullable|string|max:255';
            $data['default_assign'] = $request->default_assign;
            $data['default_assign_type'] = $request->type;
            $data['default_assign_id'] = $request->role_hash_id;
            $assignFields = [
                'default_assign' => $request->default_assign ?? null,
                'default_assign_type' => $request->type ?? null,
                'default_assign_id' => $request->role_hash_id ?? null,
            ];
            foreach ($assignFields as $field => $newValue) {

                $oldValue = $lead->$field ?? null;

                if ($oldValue != $newValue) {

                    LeadLog::create([
                        'lead_id' => $lead->lead_id,
                        'action' => 'Status Changed',
                        'field_name' => $field,
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                        'user_id' => auth()->id(),
                    ]);
                }
            }
        }

        if ($request->has('reference_id')) {
            $rules['reference_id'] = 'nullable|string|max:100';
            $data['reference_id'] = $request->reference_id;
            if ($lead->reference_id != $request->reference_id) {

                LeadLog::create([
                    'lead_id' => $lead->lead_id,
                    'action' => 'Status Changed',
                    'field_name' => 'reference_id',
                    'old_value' => $lead->reference_id,
                    'new_value' => $request->reference_id,
                ]);
            }
        }
        // Determine role type based on lead type
        $leadType = $request->has('lead_status')
            ? $request->lead_status
            : $lead->lead_status;   // fallback to existing DB value


        $type = null;

        if (in_array($leadType, ['hot', 'converted'])) {
            $type = 'counsellor';
        } elseif (in_array($leadType, ['warm', 'cold'])) {
            $type = 'agent';
        }
        if ($type) {

            $roles = RolesForAdmissionInterviewProcess::with(['employee', 'department'])
                ->where('institute_id', Auth::user()->institute_id)
                ->where('type', $type)
                ->when($type == 'counsellor', function ($query) use ($lead) {
                    $query->whereJsonContains('class_id', $lead->applying_for_grade);
                })
                ->first();   // 👈 get single record

            if ($roles && $roles->employee) {
                $data['default_assign'] = $roles->employee->name ?? null;
                $data['default_assign_type'] = $roles->type ?? null;
                $data['default_assign_id'] = $roles->role_hash_id ?? null;
            }
            $assignFields = [
                'default_assign' => $roles->employee->name ?? null,
                'default_assign_type' => $roles->type ?? null,
                'default_assign_id' => $roles->role_hash_id ?? null,
            ];
            foreach ($assignFields as $field => $newValue) {

                $oldValue = $lead->$field ?? null;

                if ($oldValue != $newValue) {

                    LeadLog::create([
                        'lead_id' => $lead->lead_id,
                        'action' => 'Status Changed',
                        'field_name' => $field,
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                        'user_id' => auth()->id(),
                    ]);
                }
            }
        }

        // Only validate if we have rules
        if (!empty($rules)) {
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }
        }

        // Only update if we have data
        if (!empty($data)) {
            $lead->update($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lead updated successfully',
            'lead' => $lead
        ]);
    }

    /**
     * Update lead status only
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'lead_status' => 'required|in:new,contacted,follow_up,converted,lost,hot,warm,cold'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found'
            ], 404);
        }

        // Check authorization
        $user = Auth::user();
        if ($lead->institute_id !== $user->institute_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        // Determine role type based on lead type
        $leadType = $request->has('lead_status')
            ? $request->lead_status
            : $lead->lead_status;   // fallback to existing DB value

        $type = null;

        if (in_array($leadType, ['hot', 'converted'])) {
            $type = 'counsellor';
        } elseif (in_array($leadType, ['warm', 'cold'])) {
            $type = 'agent';
        }
        if ($type) {

            $roles = RolesForAdmissionInterviewProcess::with(['employee', 'department'])
                ->where('institute_id', Auth::user()->institute_id)
                ->where('type', $type)
                ->when($type == 'counsellor', function ($query) use ($lead) {
                    $query->whereJsonContains('class_id', $lead->applying_for_grade);
                })
                ->first();   // 👈 get single record
            if ($roles && $roles->employee) {
                $lead->update([
                    'default_assign' => $roles->employee->name ?? null,
                    'default_assign_type' => $roles->type ?? null,
                    'default_assign_id' => $roles->role_hash_id ?? null,
                ]);
            }

            $assignFields = [
                'default_assign' => $roles->employee->name ?? null,
                'default_assign_type' => $roles->type ?? null,
                'default_assign_id' => $roles->role_hash_id ?? null,
            ];

            foreach ($assignFields as $field => $newValue) {
                $oldValue = $lead->$field ?? null;
                if ($oldValue != $newValue) {
                    LeadLog::create([
                        'lead_id' => $lead->lead_id,
                        'action' => 'Status Changed',
                        'field_name' => $field,
                        'old_value' => $oldValue,
                        'new_value' => $newValue,
                        'user_id' => auth()->id(),
                    ]);
                }
            }
        }
        Log::info('Update lead ID: ' . $lead->lead_status . ' with status: ' . $request->lead_status);
        if ($lead->lead_status != $request->lead_status) {

            LeadLog::create([
                'lead_id' => $lead->lead_id,
                'action' => 'Status Changed',
                'field_name' => 'lead_status',
                'old_value' => $lead->lead_status,
                'new_value' => $request->lead_status,
            ]);
        }
        $lead->update([
            'lead_status' => $request->lead_status
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'lead' => $lead
        ]);
    }

    /**
     * Update lead assignee only
     */
    public function updateAssignee(Request $request, $id)
    {
        \Log::info('updateAssignee called', [
            'id' => $id,
            'request_data' => $request->all(),
            'user_id' => auth()->id()
        ]);

        $validator = Validator::make($request->all(), [
            'default_assign' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $lead = Lead::find($id);

        if (!$lead) {
            \Log::error('Lead not found', ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Lead not found'
            ], 404);
        }

        // Check authorization
        $user = Auth::user();
        if ($lead->institute_id !== $user->institute_id) {
            \Log::error('Unauthorized access', ['lead_institute' => $lead->institute_id, 'user_institute' => $user->institute_id]);
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        \Log::info('Updating lead assignee', [
            'lead_id' => $id,
            'old_default_assign' => $lead->default_assign,
            'old_default_assign_id' => $lead->default_assign_id,
            'new_default_assign' => $request->default_assign,
            'new_role_hash_id' => $request->role_hash_id,
            'new_type' => $request->type
        ]);

        $lead->update([
            'default_assign' => $request->default_assign,
            'default_assign_id' => $request->role_hash_id,
            'default_assign_type' => $request->type,
        ]);

        \Log::info('Lead updated successfully', [
            'lead_id' => $id,
            'new_default_assign' => $lead->default_assign,
            'new_default_assign_id' => $lead->default_assign_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Assignee updated successfully',
            'lead' => $lead
        ]);
    }

    /**
     * Update lead follow-up only
     */

    public function updateFollowup(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'follow_up' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found'
            ], 404);
        }

        // Check authorization
        $user = Auth::user();
        if ($lead->institute_id !== $user->institute_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $lead->update([
            'follow_up' => $request->follow_up ? date('Y-m-d H:i:s', strtotime($request->follow_up)) : null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Follow-up date updated successfully',
            'lead' => $lead
        ]);
    }

    /**
     * Update lead name only
     */
    public function updateName(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found'
            ], 404);
        }

        // Check authorization
        $user = Auth::user();
        if ($lead->institute_id !== $user->institute_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $lead->update([
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Name updated successfully',
            'lead' => $lead
        ]);
    }

    /**
     * Delete lead (AJAX)
     */
    public function destroy($id)
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found'
            ], 404);
        }

        // Check if lead belongs to user's institute
        if ($lead->institute_id !== Auth::user()->institute_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $lead->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lead deleted successfully'
        ]);
    }

    /**
     * Get dashboard statistics (AJAX)
     */
    public function stats()
    {
        $user = Auth::user();

        if (!$user || !$user->institute_id) {
            return response()->json(['error' => 'Institute not found'], 422);
        }

        $stats = [
            'total' => Lead::where('institute_id', $user->institute_id)->count(),
            'hot' => Lead::where('institute_id', $user->institute_id)
                ->where('lead_type', 'hot')
                ->count(),
            'warm' => Lead::where('institute_id', $user->institute_id)
                ->where('lead_type', 'warm')
                ->count(),
            'cold' => Lead::where('institute_id', $user->institute_id)
                ->where('lead_type', 'cold')
                ->count(),
            'converted' => Lead::where('institute_id', $user->institute_id)
                ->where('lead_status', 'converted')
                ->count(),
            'today_followups' => Lead::where('institute_id', $user->institute_id)
                ->whereDate('follow_up', today())
                ->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Show lead view page
     */
    public function show($id)
    {
        $user = Auth::user();

        $lead = Lead::where('institute_id', $user->institute_id)
            ->findOrFail($id);

        $studentlead = StudentAdmissionProcess::with([
            'admissionConfig',
            'counsellingSlot',
            'entranceTestSlot',
            'entranceTests'
        ])
            ->where('lead_id', $lead->id)
            ->firstOrFail();

        $admissionProcessConfig = $studentlead->admissionConfig;
        $counsellingTimeSlot = $studentlead->counsellingSlot;
        $entranceTestSlot = $studentlead->entranceTestSlot;


        $user = Auth::user();
        if ($lead->institute_id !== $user->institute_id) {
            abort(403, 'Unauthorized access to this lead.');
        }

        // Parse steps JSON if it exists
        if ($lead->steps) {
            $lead->steps = json_decode($lead->steps, true);
        } else {
            // Set default steps structure
            $lead->steps = [
                'admission' => [
                    'status' => 'complted',
                    'fee' => false,
                    'enabled' => true,
                    'required' => true
                ],
                'entrance_test' => [
                    'status' => 'pending',
                    'fee' => false,
                    'enabled' => true,
                    'required' => false
                ],
                'counselling' => [
                    'status' => 'pending',
                    'assigned_to' => null,
                    'enabled' => true,
                    'required' => false
                ],
                'onboarding' => [
                    'status' => 'pending',
                    'enabled' => true,
                    'required' => true
                ]
            ];
        }
        $admission_form_fee = CommonCustomFees::where('custom_reference_id', $studentlead->admissionConfig->admission_form_fee_amount)->first();
        if ($admission_form_fee) {
            $admission_form_fee = $admission_form_fee->custom_fee_value;
        } else {
            $admission_form_fee = 0.00;
        }

        // Lookup entrance test fee from custom fees using the ID stored in entrance_test_fee_amount
        $entrance_test_fee = 0.00;
        if ($studentlead->admissionConfig->entrance_test_fee_amount) {
            $entranceFeeLookup = CommonCustomFees::where('custom_reference_id', $studentlead->admissionConfig->entrance_test_fee_amount)->first();
            if ($entranceFeeLookup) {
                $entrance_test_fee = $entranceFeeLookup->custom_fee_value;
            }
        }

        // dd($admission_form_fee);
        return view('instituteAdmin.AddLead.LeadViewAdmission', compact('lead', 'studentlead', 'admission_form_fee', 'entrance_test_fee'));
    }

    public function updateEntranceFee(Request $request, $id)
    {
        try {
            $request->validate([
                'payment_type' => 'required|in:cash,bank transfer',
                'transaction_id' => 'required|string',
                'amount' => 'required|numeric'
            ]);

            // Find the student admission process record
            $studentAdmission = StudentAdmissionProcess::where('lead_id', (string) $id)->first();

            if (!$studentAdmission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student admission record not found'
                ], 404);
            }

            // Update entrance fee fields
            $studentAdmission->entrance_payment = $request->amount;
            $studentAdmission->entrance_payment_type = $request->payment_type;
            $studentAdmission->entrance_fee_transaction_id = $request->transaction_id;
            $studentAdmission->payment_date = now();
            $studentAdmission->step_second = 'completed'; // This updates the timeline

            $studentAdmission->save();

            return response()->json([
                'success' => true,
                'message' => 'Entrance fee recorded successfully',
                'data' => [
                    'transaction_id' => $request->transaction_id,
                    'payment_type' => $request->payment_type,
                    'amount' => $request->amount
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing payment: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateRegistrationFee(Request $request, $id)
    {
        try {
            $request->validate([
                'payment_type' => 'required|in:cash,bank transfer',
                'transaction_id' => 'required|string',
                'amount' => 'required|numeric'
            ]);

            // Find the student admission process record
            $studentAdmission = StudentAdmissionProcess::where('lead_id', (string) $id)->first();

            if (!$studentAdmission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student admission record not found'
                ], 404);
            }

            // Update registration fee fields
            $studentAdmission->admission_payment_type = $request->payment_type;
            $studentAdmission->admission_fee_transaction_id = $request->transaction_id;
            $studentAdmission->registration_payment = $request->amount; // Registration fee amount
            $studentAdmission->payment_date = now();
            $studentAdmission->step_first = 'completed'; // Mark admission form step as completed

            $studentAdmission->save();

            return response()->json([
                'success' => true,
                'message' => 'Registration fee recorded successfully',
                'data' => [
                    'transaction_id' => $request->transaction_id,
                    'payment_type' => $request->payment_type,
                    'amount' => $request->amount
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing payment: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Update test marks for a lead
     */
    public function updateTestMarks(Request $request, $id)
    {
        try {
            $request->validate([
                'obtained_marks' => 'required|numeric|min:0',
                'test_status' => 'required|in:pending,in_progress,completed,failed',
                'entrance_test_slot_id' => 'required|exists:entrance_test_slots,id'
            ]);

            $lead = Lead::findOrFail($id);
            $process = $lead->studentAdmissionProcess;

            if (!$process) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student admission process not found'
                ], 404);
            }

            $slot = EntranceTestSlot::findOrFail($request->entrance_test_slot_id);
            $process->load('admissionConfig.entranceTestSlots');

            $dbTestStatus = $request->test_status;
            if ($dbTestStatus === 'completed') {
                $dbTestStatus = 'passed';
            } elseif ($dbTestStatus === 'in_progress') {
                $dbTestStatus = 'pending';
            }

            $entranceTest = EntranceTest::where('reference_id', $lead->reference_id)
                ->where('entrance_test_slot_id', $slot->id)
                ->first();

            if (!$entranceTest) {
                $entranceTest = EntranceTest::create([
                    'lead_id' => $lead->id,
                    'reference_id' => $lead->reference_id,
                    'institute_id' => $lead->institute_id,
                    'branch_id' => $lead->branch_id,
                    'admission_config_id' => $process->admission_config_id,
                    'entrance_test_slot_id' => $slot->id,
                    'test_id' => $slot->test_id,
                    'entrance_test_date' => $slot->test_date ?? null,
                    'entrance_test_time' => $slot->start_time ?? null,
                    'test_name' => $slot->test_name ?? null,
                    'test_marks' => $slot->test_marks ?? 100,
                    'test_passing_marks' => $slot->test_passing_marks_percentage ?? 33,
                    'test_status' => $dbTestStatus,
                    'test_obtained_marks' => $request->obtained_marks,
                ]);
            } else {
                $entranceTest->update([
                    'entrance_test_date' => $slot->test_date ?? $entranceTest->entrance_test_date,
                    'entrance_test_time' => $slot->start_time ?? $entranceTest->entrance_test_time,
                    'test_name' => $slot->test_name ?? $entranceTest->test_name,
                    'test_marks' => $slot->test_marks ?? $entranceTest->test_marks ?? 100,
                    'test_passing_marks' => $slot->test_passing_marks_percentage ?? $entranceTest->test_passing_marks ?? 33,
                    'test_status' => $dbTestStatus,
                    'test_obtained_marks' => $request->obtained_marks,
                ]);
            }

            $allEntranceTests = EntranceTest::where('reference_id', $lead->reference_id)->get();
            $normalizedStatuses = $allEntranceTests->map(function ($test) {
                if (in_array($test->test_status, ['passed', 'completed'], true)) {
                    return 'completed';
                }
                if (in_array($test->test_status, ['failed', 'rejected'], true)) {
                    return 'failed';
                }
                return $test->test_status;
            })->all();

            if ($allEntranceTests->isEmpty()) {
                $process->step_second = 'pending';
                $process->test_status = 'pending';
            } elseif (in_array('failed', $normalizedStatuses, true)) {
                $process->step_second = 'rejected';
                $process->test_status = 'failed';
            } elseif (
                collect($normalizedStatuses)->every(function ($status) {
                    return $status === 'completed';
                })
            ) {
                $process->step_second = 'completed';
                $process->test_status = 'completed';
            } elseif (in_array('in_progress', $normalizedStatuses, true)) {
                $process->step_second = 'pending';
                $process->test_status = 'in_progress';
            } else {
                $process->step_second = 'pending';
                $process->test_status = 'pending';
            }

            if ($process->entrance_test_slot_id == $slot->id) {
                $process->test_obtained_marks = $request->obtained_marks;
                $process->test_status = $request->test_status;
                $process->test_name = $slot->test_name;
                $process->test_marks = $slot->test_marks ?? 100;
                $process->test_passing_marks = $slot->test_passing_marks_percentage ?? 33;
            }

            $process->save();

            return response()->json([
                'success' => true,
                'message' => 'Test marks updated successfully',
                'data' => [
                    'obtained_marks' => $request->obtained_marks,
                    'test_status' => $request->test_status,
                    'step_second' => $process->step_second,
                    'entrance_test_results' => $process->entrance_test_results ?? null
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating test marks: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating test marks: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign counselor to lead (AJAX)
     */
    public function assignCounselor(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'counselor' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found'
            ], 404);
        }

        // Check authorization
        $user = Auth::user();
        if ($lead->institute_id !== $user->institute_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $lead->update([
            'default_assign' => $request->counselor
        ]);

        // Update counseling step if needed
        $steps = json_decode($lead->steps, true) ?? [];
        if (isset($steps['counselling'])) {
            $steps['counselling']['assigned_to'] = $request->counselor;
            if ($steps['counselling']['status'] === 'pending') {
                $steps['counselling']['status'] = 'in_progress';
            }
            $lead->update(['steps' => json_encode($steps)]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Counselor assigned successfully'
        ]);
    }

    /**
     * Update steps configuration (AJAX)
     */
    public function updateSteps(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'steps' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found'
            ], 404);
        }

        // Check authorization
        $user = Auth::user();
        if ($lead->institute_id !== $user->institute_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $lead->update([
            'steps' => json_encode($request->steps)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Steps configuration updated successfully'
        ]);
    }
    public function updateStepStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'step' => 'required|string|in:step_first,step_second,step_third,step_fourth',
                'status' => 'required|string|in:pending,in_progress,completed,skipped,rejected'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find the student admission process by lead_id
            $studentAdmission = StudentAdmissionProcess::where('lead_id', $id)->first();

            if (!$studentAdmission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student admission process not found'
                ], 404);
            }

            // Map step names to display names for logging
            $stepNames = [
                'step_first' => 'Admission Form',
                'step_second' => 'Entrance Test',
                'step_third' => 'Counselling',
                'step_fourth' => 'Onboarding'
            ];

            // Get old status for logging
            $oldStatus = $studentAdmission->{$request->step};

            // Update the step status
            $studentAdmission->{$request->step} = $request->status;
            $studentAdmission->save();

            // Log the status change if you have a lead log
            if (class_exists('App\Models\LeadLog')) {
                LeadLog::create([
                    'lead_id' => $studentAdmission->lead_id,
                    'action' => 'Step Status Changed',
                    'field_name' => $request->step,
                    'old_value' => $oldStatus,
                    'new_value' => $request->status,
                    'user_id' => auth()->id(),
                ]);
            }

            // Also update the steps JSON in leads table if it exists
            $lead = Lead::find($id);
            if ($lead && $lead->steps) {
                $steps = json_decode($lead->steps, true);

                // Map step to the corresponding key in steps JSON
                $stepMapping = [
                    'step_first' => 'admission',
                    'step_second' => 'entrance_test',
                    'step_third' => 'counselling',
                    'step_fourth' => 'onboarding'
                ];

                $stepKey = $stepMapping[$request->step] ?? null;

                if ($stepKey && isset($steps[$stepKey])) {
                    $steps[$stepKey]['status'] = $request->status;
                    $lead->update(['steps' => json_encode($steps)]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => $stepNames[$request->step] . ' status updated to ' . ucfirst(str_replace('_', ' ', $request->status)),
                'data' => [
                    'step' => $request->step,
                    'status' => $request->status,
                    'old_status' => $oldStatus
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating step status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Move to next/previous step (AJAX)
     */

    public function moveStep(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'direction' => 'required|in:next,prev'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found'
            ], 404);
        }

        // Check authorization
        $user = Auth::user();
        if ($lead->institute_id !== $user->institute_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $steps = json_decode($lead->steps, true) ?? [];

        // Get step order
        $stepOrder = ['admission', 'entrance_test', 'counselling', 'onboarding'];

        if ($request->direction === 'next') {
            // Find current step and move to next
            foreach ($stepOrder as $index => $step) {
                if (isset($steps[$step]) && $steps[$step]['status'] === 'in_progress') {
                    // Mark current as completed
                    $steps[$step]['status'] = 'completed';

                    // Find next enabled step
                    for ($i = $index + 1; $i < count($stepOrder); $i++) {
                        $nextStep = $stepOrder[$i];
                        if (isset($steps[$nextStep]) && $steps[$nextStep]['enabled']) {
                            $steps[$nextStep]['status'] = 'in_progress';
                            break;
                        }
                    }
                    break;
                }
            }
        } else {
            // Find current step and move to previous
            for ($i = count($stepOrder) - 1; $i >= 0; $i--) {
                $step = $stepOrder[$i];
                if (isset($steps[$step]) && $steps[$step]['status'] === 'in_progress') {
                    // Mark current as pending
                    $steps[$step]['status'] = 'pending';

                    // Find previous enabled step
                    for ($j = $i - 1; $j >= 0; $j--) {
                        $prevStep = $stepOrder[$j];
                        if (isset($steps[$prevStep]) && $steps[$prevStep]['enabled']) {
                            $steps[$prevStep]['status'] = 'in_progress';
                            break;
                        }
                    }
                    break;
                }
            }
        }

        $lead->update([
            'steps' => json_encode($steps)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Step moved successfully'
        ]);
    }

    /**
     * Complete current step (AJAX)
     */
    public function completeStep(Request $request, $id)
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found'
            ], 404);
        }

        // Check authorization
        $user = Auth::user();
        if ($lead->institute_id !== $user->institute_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $steps = json_decode($lead->steps, true) ?? [];
        $stepOrder = ['admission', 'entrance_test', 'counselling', 'onboarding'];

        // Find current in-progress step and mark as completed
        foreach ($stepOrder as $index => $step) {
            if (isset($steps[$step]) && $steps[$step]['status'] === 'in_progress') {
                $steps[$step]['status'] = 'completed';

                // Find next enabled step and mark as in-progress
                for ($i = $index + 1; $i < count($stepOrder); $i++) {
                    $nextStep = $stepOrder[$i];
                    if (isset($steps[$nextStep]) && $steps[$nextStep]['enabled']) {
                        $steps[$nextStep]['status'] = 'in_progress';
                        break;
                    }
                }
                break;
            }
        }

        $lead->update([
            'steps' => json_encode($steps)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Step completed successfully'
        ]);
    }

    /**
     * Student view page
     */
    /**
     * Student view page
     */
    public function studentView($id)
    {
        $user = Auth::user();

        $lead = Lead::where('institute_id', $user->institute_id)
            ->findOrFail($id);

        $studentlead = StudentAdmissionProcess::with([
            'admissionConfig',
            'counsellingSlot',
            'entranceTestSlot',
            'entranceTests'
        ])
            ->where('lead_id', $lead->id)
            ->firstOrFail();

        $data = [
            'lead' => $lead,
            'studentlead' => $studentlead
        ];
        // dd($data);
        // Check authorization (if needed)
        $user = Auth::user();
        if ($lead->institute_id !== $user->institute_id) {
            abort(403, 'Unauthorized access to this lead.');
        }



        return view('instituteAdmin.AddLead.StudentView', compact('data'));
    }

    /**
     * Display counselor dashboard with assigned leads
     */
    public function counselorView()
    {
        $user = Auth::user();

        if (!$user || !$user->institute_id) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not associated with any institute.'
                ], 422);
            }
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }

        // Step 1: Get employee
        $employee = EmployeeDetails::where('user_id', $user->id)->first();


        if (!$employee) {
            $leads = [];
            return view('instituteAdmin.AddLead.CounselorView', compact('leads'));
        }

        // Step 2: Get role
        $role = RolesForAdmissionInterviewProcess::where('institute_id', $user->institute_id)
            ->where('employee_id', $employee->employee_id)
            ->where('type', 'counsellor')
            ->first();

        if (!$role) {
            $leads = [];
            return view('instituteAdmin.AddLead.CounselorView', compact('leads'));
        }

        $roleHashId = $role?->role_hash_id ?? null;


        /*
        |--------------------------------------------------------------------------
        | Step 3 : Fetch Leads With Dynamic Disabled Column
        |--------------------------------------------------------------------------
        */
        $leads = Lead::with([
            'AdmissionRegistration',
            'department',
            'merchantSubCategory'
        ])
            ->where('institute_id', $user->institute_id)
            ->where('lead_type', 'Admission')
            ->select('leads.*')
            ->selectRaw("
                CASE
                    WHEN ? IS NOT NULL
                        AND default_assign_id = ?
                    THEN FALSE
                    ELSE TRUE
                END AS disabled
            ", [$roleHashId, $roleHashId])
            ->orderByDesc('disabled')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('instituteAdmin.AddLead.CounselorView', compact('leads'));
    }

    /**
     * Get lead details in JSON format (AJAX)
     */
    public function getLeadJson($id)
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found'
            ], 404);
        }

        // Check authorization
        $user = Auth::user();
        if ($lead->institute_id !== $user->institute_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'lead' => $lead
        ]);
    }

    /**
     * Show lead details for counselor (simplified view)
     */
    public function counselorShow($id)
    {
        $lead = Lead::findOrFail($id);

        // Check authorization - counselor can only see leads assigned to them
        $user = Auth::user();

        // Check 1: Lead belongs to same institute
        if ($lead->institute_id !== $user->institute_id) {
            abort(403, 'You are not authorized to view this lead.');
        }

        // Check 2: Lead is assigned to this counselor (or admin can see all)
        if ($lead->default_assign !== $user->name) {
            // If not admin, check if they're assigned
            abort(403, 'This lead is not assigned to you.');
        }

        // Return simplified counselor view (NO journey steps, NO admission config)
        return view('instituteAdmin.AddLead.CounselorLeadView', compact('lead'));
    }



    public function agentView(Request $request, )
    {
        $user = Auth::user();
        if (!$user || !$user->institute_id) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not associated with any institute.'
                ], 422);
            }
            return redirect()->back()->with('error', 'You are not associated with any institute.');
        }
        // Step 1: Get employee
        $employee = EmployeeDetails::where('user_id', $user->id)->first();
        if (!$employee) {
            $leads = [];
            return view('instituteAdmin.AddLead.agentView', compact('leads'));
        }
        // Step 2: Get role
        $role = RolesForAdmissionInterviewProcess::where('institute_id', $user->institute_id)
            ->where('employee_id', $employee->employee_id)
            ->where('type', 'agent')
            ->first();
        if (!$role) {
            $leads = [];
            return view('instituteAdmin.AddLead.agentView', compact('leads'));
        }
        $roleHashId = $role?->role_hash_id ?? null;
        /*
        |--------------------------------------------------------------------------
        | Step 3 : Fetch Leads With Dynamic Disabled Column
        |--------------------------------------------------------------------------
        */
        $leads = Lead::with([
            'AdmissionRegistration',
            'department',
            'merchantSubCategory'
        ])
            ->where('institute_id', $user->institute_id)
            ->where('lead_type', 'Admission')
            ->select('leads.*')
            ->selectRaw("
                        CASE
                            WHEN ? IS NOT NULL
                                AND default_assign_id = ?
                            THEN FALSE
                            ELSE TRUE
                        END AS disabled
                    ", [$roleHashId, $roleHashId])
            ->orderByDesc('disabled')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('instituteAdmin.AddLead.agentView', compact('leads'));

    }

    /**
     * Get agent data in JSON format for AJAX calls
     */
    private function getAgentData(Request $request, $agentName)
    {

        $user = Auth::user();

        // Get all leads assigned to this agent
        $leadsQuery = Lead::with('AdmissionRegistration')
            ->where('institute_id', $user->institute_id)
            ->where('default_assign_id', $roles->role_hash_id);

        // Apply filters if provided
        if ($request->has('department') && $request->department) {
            $leadsQuery->whereHas('AdmissionRegistration', function ($q) use ($request) {
                $q->where('applying_for_grade', $request->department);
            });
        }

        if ($request->has('source') && $request->source) {
            $source = strtolower($request->source);
            $leadsQuery->where('registration_mode', $source);
        }

        if ($request->has('lead_status') && $request->lead_status) {
            $leadsQuery->where('lead_type', $request->lead_status);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $leadsQuery->where(function ($q) use ($search) {
                $q->where('lead_id', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone_no', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Get all leads for sessions (ordered by latest first)
        $sessions = $leadsQuery->orderBy('created_at', 'desc')->get();

        // Get follow-ups (leads with follow_up date)
        $followupsQuery = Lead::with('AdmissionRegistration')
            ->where('institute_id', $user->institute_id)
            ->where('default_assign', $agentName)
            ->whereNotNull('follow_up');

        // Apply follow-up filters
        if ($request->has('followup_date') && $request->followup_date) {
            $followupsQuery->whereDate('follow_up', $request->followup_date);
        }

        if ($request->has('followup_status') && $request->followup_status) {
            $status = $request->followup_status;
            $today = now()->toDateString();

            if ($status === 'pending') {
                $followupsQuery->whereDate('follow_up', '>=', $today)
                    ->where('lead_status', '!=', 'converted')
                    ->where('lead_status', '!=', 'lost');
            } elseif ($status === 'scheduled') {
                $followupsQuery->whereDate('follow_up', '>=', $today);
            } elseif ($status === 'completed') {
                $followupsQuery->where('lead_status', 'converted');
            } elseif ($status === 'missed') {
                $followupsQuery->whereDate('follow_up', '<', $today)
                    ->where('lead_status', '!=', 'converted')
                    ->where('lead_status', '!=', 'lost');
            }
        }

        if ($request->has('followup_search') && $request->followup_search) {
            $search = $request->followup_search;
            $followupsQuery->where(function ($q) use ($search) {
                $q->where('lead_id', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone_no', 'LIKE', "%{$search}%");
            });
        }

        $followups = $followupsQuery->orderBy('follow_up', 'asc')->get();

        // Calculate stats
        $stats = [
            'total' => Lead::where('institute_id', $user->institute_id)
                ->where('default_assign', $agentName)
                ->count(),
            'pending' => Lead::where('institute_id', $user->institute_id)
                ->where('default_assign', $agentName)
                ->whereDate('follow_up', '>=', now()->toDateString())
                ->where('lead_status', '!=', 'converted')
                ->where('lead_status', '!=', 'lost')
                ->count(),
            'completed' => Lead::where('institute_id', $user->institute_id)
                ->where('default_assign', $agentName)
                ->where('lead_status', 'converted')
                ->count(),
            'scheduled_followups' => Lead::where('institute_id', $user->institute_id)
                ->where('default_assign', $agentName)
                ->whereNotNull('follow_up')
                ->count(),
        ];

        // Format sessions data for display
        $formattedSessions = $sessions->map(function ($lead) {
            return $this->formatAgentSessionData($lead);
        });

        // Format followups data for display
        $formattedFollowups = $followups->map(function ($lead) {
            return $this->formatAgentFollowupData($lead);
        });

        return response()->json([
            'success' => true,
            'sessions' => $formattedSessions,
            'followups' => $formattedFollowups,
            'stats' => $stats,
            'agent_name' => $agentName
        ]);
    }

    /**
     * Format lead data for agent sessions table
     */
    private function formatAgentSessionData($lead)
    {
        $admission = $lead->AdmissionRegistration;

        // Determine session status based on lead data
        $sessionStatus = 'pending';
        if ($lead->lead_status === 'converted') {
            $sessionStatus = 'completed';
        } elseif ($lead->lead_status === 'lost') {
            $sessionStatus = 'lost';
        } elseif ($lead->follow_up) {
            $sessionStatus = 'followup';
        }

        return [
            'id' => $lead->id,
            'lead_id' => $lead->lead_id ?? 'LEAD-' . str_pad($lead->id, 4, '0', STR_PAD_LEFT),
            'date' => $lead->created_at->format('Y-m-d'),
            'department' => $admission->applying_for_grade ?? 'Not Specified',
            'name' => $lead->name,
            'person_type' => $lead->applicant_type ?? 'student',
            'assignedTo' => $lead->default_assign,
            'session_status' => $sessionStatus,
            'contact' => $lead->phone_no,
            'email' => $lead->email,
            'source' => ucfirst($lead->registration_mode ?? 'Online'),
            'lead_status' => $lead->lead_type ?? 'cold',
            'last_contact' => $lead->updated_at->format('Y-m-d'),
            'follow_up_date' => $lead->follow_up ? date('Y-m-d', strtotime($lead->follow_up)) : null,
            'follow_up_time' => $lead->follow_up ? date('h:i A', strtotime($lead->follow_up)) : null,
            'notes' => $lead->remarks ?? '',
        ];
    }

    /**
     * Format lead data for agent followup cards
     */
    private function formatAgentFollowupData($lead)
    {
        $admission = $lead->AdmissionRegistration;

        // Generate a consistent follow-up time if not set
        $followupTime = $lead->follow_up ? date('h:i A', strtotime($lead->follow_up)) : '10:00 AM';

        // Determine follow-up status
        $status = 'pending';
        if ($lead->lead_status === 'converted') {
            $status = 'completed';
        } elseif ($lead->follow_up) {
            $followupDate = date('Y-m-d', strtotime($lead->follow_up));
            $today = now()->toDateString();

            if ($followupDate < $today) {
                $status = 'missed';
            } elseif ($followupDate >= $today) {
                $status = 'scheduled';
            }
        }

        return [
            'id' => $lead->id,
            'lead_id' => $lead->lead_id ?? 'LEAD-' . str_pad($lead->id, 4, '0', STR_PAD_LEFT),
            'name' => $lead->name,
            'person_type' => $lead->applicant_type ?? 'student',
            'contact' => $lead->phone_no,
            'email' => $lead->email,
            'source' => ucfirst($lead->registration_mode ?? 'Online'),
            'lead_status' => $lead->lead_type ?? 'cold',
            'follow_up_date' => $lead->follow_up ? date('Y-m-d', strtotime($lead->follow_up)) : null,
            'follow_up_time' => $followupTime,
            'status' => $status,
            'notes' => $lead->remarks ?? 'Follow-up needed',
            'counselor_notes' => $lead->remarks ?? '',
            'department' => $admission->applying_for_grade ?? 'Not Specified',
        ];
    }
    /**
     * Save remarks/notes for a lead
     */
    public function saveRemarks(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'remarks' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found'
            ], 404);
        }

        // Check authorization
        $user = Auth::user();
        if ($lead->institute_id !== $user->institute_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Append new remarks with timestamp
        $timestamp = now()->format('M d, Y h:i A');
        $newRemark = "\n\n--- {$timestamp} ---\n{$request->remarks}";

        $lead->remarks = $lead->remarks ? $lead->remarks . $newRemark : $newRemark;
        $lead->save();

        return response()->json([
            'success' => true,
            'message' => 'Remarks saved successfully',
            'remarks' => $lead->remarks
        ]);
    }


    public function updateInterviewStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'lead_status' => 'required|in:hot,warm,cold,selected,converted,rejected,lost'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $lead = Lead::where('lead_type', 'Interview')->find($id);

            if (!$lead) {
                return response()->json([
                    'success' => false,
                    'message' => 'Interview lead not found'
                ], 404);
            }

            // Check authorization
            $user = Auth::user();
            if ($lead->institute_id !== $user->institute_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $lead->update([
                'lead_status' => $request->lead_status
            ]);

            $onboardingUrl = $request->lead_status === 'selected'
                ? $this->onboardingLink($lead)
                : null;

            Log::info('Interview lead status updated', [
                'lead_id' => $lead->id,
                'lead_identifier' => $lead->lead_id,
                'institute_id' => $lead->institute_id,
                'lead_status' => $lead->lead_status,
                'onboarding_url_generated' => !empty($onboardingUrl),
                'onboarding_url' => $onboardingUrl,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'lead' => $lead,
                'onboarding_url' => $onboardingUrl,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update interview lead follow-up only
     */
    public function updateInterviewFollowup(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'follow_up' => 'nullable|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $lead = Lead::where('lead_type', 'Interview')->find($id);

            if (!$lead) {
                return response()->json([
                    'success' => false,
                    'message' => 'Interview lead not found'
                ], 404);
            }

            // Check authorization
            $user = Auth::user();
            if ($lead->institute_id !== $user->institute_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $lead->update([
                'follow_up' => $request->follow_up ? date('Y-m-d H:i:s', strtotime($request->follow_up)) : null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Follow-up date updated successfully',
                'lead' => $lead
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get interview edit logs for a lead.
     */
    public function getInterviewEditLogs(Request $request, $id)
    {
        try {
            $user = Auth::user();
            if (!$user || !$user->institute_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $lead = Lead::where('lead_type', 'Interview')->find($id);
            if (!$lead || $lead->institute_id !== $user->institute_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Interview lead not found'
                ], 404);
            }

            $institute = InstituteBasicDetails::where('fincap_merchant_id', $lead->institute_id)->first();
            $instituteNumericId = $institute ? $institute->id : null;

            $logsQuery = InterviewEditLog::where('lead_id', $id);
            if ($instituteNumericId) {
                $logsQuery->where('institute_id', $instituteNumericId);
            }

            $logs = $logsQuery->orderBy('edited_at', 'desc')->get();

            $formattedLogs = $logs->map(function ($log) {
                return [
                    'field_name' => $log->field_name,
                    'value_before' => $log->value_before,
                    'value_after' => $log->value_after,
                    'edit_count' => $log->edit_count,
                    'edited_by' => optional($log->editor)->name,
                    'edited_at' => optional($log->edited_at)->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'logs' => $formattedLogs,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display candidate dashboard view
     */
    // Add this method to your LeadsController.php


    public function candidateView($leadId)
    {
        $candidateLead = Lead::where('lead_type', 'Interview')
            ->where(function ($query) use ($leadId) {
                $query->where('lead_id', $leadId)->orWhere('id', $leadId);
            })
            ->first();

        if (!$candidateLead) {
            return response('Candidate not found.', 404);
        }

        $user = Auth::user();

        if (!$user) {
            if (
                (int) session('candidate_journey_lead_id') !== (int) $candidateLead->id ||
                session('candidate_journey_otp_verified') !== true
            ) {
                return redirect()->to($this->candidateJourneyOtpLink($candidateLead));
            }
        }

        // Check Institute
        if (!$user && session('candidate_journey_otp_verified') !== true) {
            return redirect()->back()
                ->with('error', 'You are not associated with any institute.');
        }

        // Load Lead with rounds + round statuses
        $lead = Lead::whereKey($candidateLead->id)
            ->with([
                'interviewRegistration.interviewConfiguration' => function ($q) {
                    $q->with(['interviewRounds', 'department', 'venueBuilding', 'venueBlock', 'venueFloor', 'venueRoom']);
                },
                'roundStatuses' // ✅ ADD THIS LINE
            ])
            ->first();

        if (!$lead) {
            return redirect()->back()->with('error', 'Candidate not found.');
        }

        if ($lead->lead_type !== 'Interview') {
            return redirect()->back()->with('error', 'Invalid candidate type.');
        }

        Log::info('Candidate Journey rendered after verification', [
            'lead_id' => $lead->id,
            'lead_identifier' => $lead->lead_id,
            'institute_id' => $lead->institute_id,
        ]);

        $onboardingUrl = null;
        if (strtolower((string) $lead->lead_status) === 'selected') {
            $onboardingUrl = $this->onboardingLink($lead);
            Log::info('Candidate View onboarding link generated', [
                'lead_id' => $lead->id,
                'lead_identifier' => $lead->lead_id,
                'institute_id' => $lead->institute_id,
                'lead_status' => $lead->lead_status,
                'onboarding_url' => $onboardingUrl,
            ]);
        } else {
            Log::info('Candidate View onboarding link hidden because lead is not selected', [
                'lead_id' => $lead->id,
                'institute_id' => $lead->institute_id,
                'lead_status' => $lead->lead_status,
            ]);
        }

        $data = [
            'lead' => $lead,
            'interviewRegistration' => $lead->interviewRegistration,
            'interviewConfig' => $lead->interviewRegistration->interviewConfiguration ?? null,
            'interviewRounds' => $lead->interviewRegistration->interviewConfiguration->interviewRounds ?? collect([]),
            'department' => $lead->interviewRegistration->interviewConfiguration->department ?? null,
            'roundStatuses' => $lead->roundStatuses ?? collect([]), // ✅ pass to blade
            'onboardingUrl' => $onboardingUrl,
        ];

        Log::info('Candidate View payload prepared', [
            'lead_id' => $lead->id,
            'institute_id' => $lead->institute_id,
            'lead_status' => $lead->lead_status,
            'has_onboarding_url' => !empty($onboardingUrl),
            'organization' => $lead->interviewRegistration?->organization,
            'interview_config_id' => $lead->interviewRegistration?->interview_config_id,
        ]);

        return view('instituteAdmin.AddLead.Interview.candidateView', compact('data'));
    }


    public function showInterviewLeadDetails($id)
    {
        $user = Auth::user();

        // Find the lead with all relations
        $lead = Lead::with([
            'interviewRegistration.interviewConfiguration.interviewRounds'
        ])->find($id);

        if (!$lead) {
            // If lead not found, redirect back with error
            return redirect()->route('institute-admin.leads.index')
                ->with('error', 'Lead not found');
        }
        $interviewRegistration = $lead->interviewRegistration;
        $interviewRegistration = $lead->interviewRegistration;
        $interviewConfig = $lead->interviewRegistration?->interviewConfiguration;
        $interviewRounds = $interviewConfig?->interviewRounds ?? collect([]);
        $onboardingUrl = null;
        if (strtolower((string) $lead->lead_status) === 'selected') {
            $onboardingUrl = $this->onboardingLink($lead);
        }

        $employeeDetails = null;
        if (!empty($lead->email) || !empty($lead->phone_no)) {
            $employeeDetails = EmployeeDetails::with(['department', 'designationRelation'])
                ->where('institute_id', $lead->institute_id)
                ->where(function ($query) use ($lead) {
                    $hasEmail = !empty($lead->email);
                    $hasMobile = !empty($lead->phone_no);

                    if ($hasEmail) {
                        $query->where('email', $lead->email);
                    }

                    if ($hasMobile) {
                        $mobile = preg_replace('/\D+/', '', $lead->phone_no);
                        $query->{$hasEmail ? 'orWhere' : 'where'}('mobile_number', $mobile);
                    }
                })
                ->first();
        }

        Log::info('Lead details employee lookup', [
            'lead_id' => $lead->id,
            'lead_identifier' => $lead->lead_id,
            'institute_id' => $lead->institute_id,
            'employee_found' => (bool) $employeeDetails,
            'lookup_fields' => ['email', 'mobile_number'],
        ]);

        return view(
            'instituteAdmin.AddLead.Interview.lead-details',
            compact('lead', 'interviewRegistration', 'interviewConfig', 'interviewRounds', 'onboardingUrl', 'employeeDetails')
        );
    }
    private function createTimeline($lead)
    {
        $timeline = [];

        // 1. Lead Creation
        $timeline[] = [
            'date' => $lead->created_at,
            'type' => 'creation',
            'title' => 'Lead Created',
            'description' => 'Candidate was added to the system',
            'icon' => 'fa-plus-circle',
            'color' => 'primary'
        ];

        // 2. Interview Registration
        if ($lead->interviewRegistration) {
            $timeline[] = [
                'date' => $lead->interviewRegistration->created_at,
                'type' => 'registration',
                'title' => 'Interview Registration',
                'description' => 'Candidate registered for interview - ' . ($lead->interviewRegistration->application_status ?? 'Pending'),
                'icon' => 'fa-file-signature',
                'color' => 'info'
            ];

            // 3. Interview Status Changes
            if ($lead->interviewRegistration->interview_status) {
                $timeline[] = [
                    'date' => $lead->interviewRegistration->updated_at ?? $lead->interviewRegistration->created_at,
                    'type' => 'status_change',
                    'title' => 'Interview Status Updated',
                    'description' => 'Interview status changed to: ' . ucfirst($lead->interviewRegistration->interview_status),
                    'icon' => 'fa-exchange-alt',
                    'color' => 'warning'
                ];
            }

            // 4. Selection Status
            if ($lead->interviewRegistration->selection_status && $lead->interviewRegistration->selection_status != 'pending') {
                $color = $lead->interviewRegistration->selection_status == 'selected' ? 'success' : 'danger';
                $icon = $lead->interviewRegistration->selection_status == 'selected' ? 'fa-check-circle' : 'fa-times-circle';

                $timeline[] = [
                    'date' => $lead->interviewRegistration->updated_at,
                    'type' => 'selection',
                    'title' => 'Selection Decision',
                    'description' => 'Candidate ' . $lead->interviewRegistration->selection_status,
                    'icon' => $icon,
                    'color' => $color
                ];
            }
        }

        // 5. Status Changes (if you track them)
        if ($lead->lead_status) {
            $timeline[] = [
                'date' => $lead->updated_at,
                'type' => 'lead_status',
                'title' => 'Lead Status Updated',
                'description' => 'Lead status changed to: ' . ucfirst($lead->lead_status),
                'icon' => 'fa-tag',
                'color' => $this->getStatusColor($lead->lead_status)
            ];
        }

        // 6. Follow-ups (if any)
        if (isset($lead->follow_up) && $lead->follow_up) {
            $timeline[] = [
                'date' => $lead->follow_up . ' ' . ($lead->follow_up_time ?? ''),
                'type' => 'followup',
                'title' => 'Follow-up Scheduled',
                'description' => 'Next follow-up on ' . \Carbon\Carbon::parse($lead->follow_up)->format('d M Y') .
                    ($lead->follow_up_time ? ' at ' . $lead->follow_up_time : ''),
                'icon' => 'fa-bell',
                'color' => 'secondary'
            ];
        }

        // Sort timeline by date (newest first)
        usort($timeline, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return $timeline;
    }

    private function getStatusColor($status)
    {
        switch (strtolower($status)) {
            case 'hot':
                return 'danger';
            case 'warm':
                return 'warning';
            case 'cold':
                return 'primary';
            case 'selected':
                return 'success';
            case 'rejected':
                return 'secondary';
            case 'lost':
                return 'dark';
            default:
                return 'secondary';
        }
    }

    private function getTimelineEvents($lead)
    {
        $timeline = [];

        // Lead creation
        $timeline[] = [
            'date' => $lead->created_at,
            'type' => 'creation',
            'title' => 'Lead Created',
            'description' => 'Lead was created in the system',
            'icon' => 'fa-plus-circle',
            'color' => 'primary'
        ];

        // Interview registration  
        if ($lead->interviewRegistration) {
            $timeline[] = [
                'date' => $lead->interviewRegistration->created_at,
                'type' => 'registration',
                'title' => 'Interview Registration',
                'description' => 'Candidate registered for interview',
                'icon' => 'fa-file-signature',
                'color' => 'info'
            ];

            // Interview rounds
            if (
                $lead->interviewRegistration->interviewConfiguration &&
                $lead->interviewRegistration->interviewConfiguration->interviewRounds
            ) {
                foreach ($lead->interviewRegistration->interviewConfiguration->interviewRounds as $round) {
                    $timeline[] = [
                        'date' => $round->created_at ?? $lead->interviewRegistration->created_at,
                        'type' => 'round',
                        'title' => 'Interview Round: ' . $round->round_name,
                        'description' => 'Round ' . $round->round_order . ' - ' . ($round->status ?? 'Pending'),
                        'icon' => 'fa-circle',
                        'color' => $this->getRoundColor($round->status ?? 'pending')
                    ];
                }
            }
        }

        // Status changes (you might need to track these separately)
        // This is just an example - you'd need a status_history table

        // Sort by date
        usort($timeline, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        return $timeline;
    }

    private function getRoundColor($status)
    {
        switch (strtolower($status)) {
            case 'completed':
                return 'success';
            case 'in_progress':
                return 'warning';
            case 'failed':
                return 'danger';
            case 'passed':
                return 'success';
            default:
                return 'secondary';
        }
    }

    /**
     * parwinder sir work from here
     */
    public function updateJourneyStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'field' => 'required|string|in:application_status,interview_status,selection_status,onboarding_status',
                'status' => 'required|string|in:pending,in_progress,completed,rejected'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data',
                    'errors' => $validator->errors()
                ], 422);
            }

            $lead = Lead::where('lead_type', 'Interview')->find($id);

            if (!$lead || !$lead->interviewRegistration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Interview registration not found'
                ], 404);
            }

            // Update the specific status field
            $lead->interviewRegistration->update([
                $request->field => $request->status
            ]);

            $onboardingUrl = null;
            if ($request->field === 'selection_status' && $request->status === 'completed') {
                $lead->update(['lead_status' => 'selected']);
                $onboardingUrl = $this->onboardingLink($lead);
            }

            // If interview status is rejected, also update lead status
            if ($request->field == 'interview_status' && $request->status == 'rejected') {
                $lead->update(['lead_status' => 'rejected']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'status' => $request->status,
                'onboarding_url' => $onboardingUrl
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateRoundStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required|string|in:pending,inprogress,passed,failed'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status value',
                    'errors' => $validator->errors()
                ], 422);
            }

            $roundStatus = RoundStatus::find($id);

            if (!$roundStatus) {
                return response()->json([
                    'success' => false,
                    'message' => 'Round status not found'
                ], 404);
            }

            // Update round status
            $roundStatus->round_status = $request->status;
            $roundStatus->save();

            // Find the associated lead - FIX: Use where('lead_id', $roundStatus->lead_id) instead of find()
            $lead = Lead::where('lead_id', $roundStatus->lead_id)->first();

            if (!$lead) {
                return response()->json([
                    'success' => false,
                    'message' => 'Associated lead not found with lead_id: ' . $roundStatus->lead_id
                ], 404);
            }

            // If round is failed - reject interview, selection, onboarding only
            if ($request->status == 'failed') {
                if ($lead->interviewRegistration) {
                    $lead->interviewRegistration->update([
                        'interview_status' => 'rejected',
                        'selection_status' => 'rejected',
                        'onboarding_status' => 'rejected'
                    ]);
                    $lead->update(['lead_status' => 'rejected']);

                    \Log::info('Round failed - Rejected interview, selection, onboarding for lead: ' . $lead->id);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Round failed - Interview, Selection, Onboarding rejected',
                    'data' => [
                        'id' => $roundStatus->id,
                        'round_status' => $roundStatus->round_status,
                        'interview_status' => 'rejected',
                        'selection_status' => 'rejected',
                        'onboarding_status' => 'rejected'
                    ]
                ]);
            }

            // Check if all rounds are passed
            if ($request->status == 'passed') {
                $allRounds = RoundStatus::where('lead_id', $roundStatus->lead_id)->get();
                $allPassed = $allRounds->every(function ($round) {
                    return $round->round_status == 'passed';
                });

                if ($allPassed && $lead->interviewRegistration) {
                    $lead->interviewRegistration->update([
                        'interview_status' => 'completed',
                        'selection_status' => 'completed',
                        'onboarding_status' => 'pending'
                    ]);
                    $lead->update(['lead_status' => 'selected']);
                    $onboardingUrl = $this->onboardingLink($lead);

                    \Log::info('All rounds passed - Interview and Selection completed, Onboarding pending for lead: ' . $lead->id);

                    return response()->json([
                        'success' => true,
                        'message' => 'All rounds passed! Interview and Selection completed, Onboarding pending',
                        'data' => [
                            'id' => $roundStatus->id,
                            'round_status' => $roundStatus->round_status,
                            'interview_status' => 'completed',
                            'selection_status' => 'completed',
                            'onboarding_status' => 'pending',
                            'all_rounds_passed' => true,
                            'onboarding_url' => $onboardingUrl
                        ]
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Round status updated successfully',
                'data' => [
                    'id' => $roundStatus->id,
                    'round_status' => $roundStatus->round_status
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Round status update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating round status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update contact information (Name, Email, Phone)
     */
    public function updateContact(Request $request, $id)
    {
        try {
            // Build validation rules based on what fields are present in the request
            $rules = [];
            if ($request->has('name')) {
                $rules['name'] = 'required|string|max:255';
            }
            if ($request->has('email')) {
                $rules['email'] = 'required|email|max:255';
            }
            if ($request->has('phone_no')) {
                $rules['phone_no'] = 'required|string|max:20';
            }

            // If no fields are provided, return error
            if (empty($rules)) {
                return response()->json([
                    'success' => false,
                    'message' => 'At least one field (name, email, or phone_no) is required'
                ], 422);
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $lead = Lead::find($id);

            if (!$lead) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lead not found'
                ], 404);
            }

            $user = Auth::user();
            if ($lead->institute_id !== $user->institute_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Get old values for logging
            $oldName = $lead->name;
            $oldEmail = $lead->email;
            $oldPhone = $lead->phone_no;

            // Build update array with only the fields provided
            $updateData = [];
            if ($request->has('name')) {
                $updateData['name'] = $request->name;
            }
            if ($request->has('email')) {
                $updateData['email'] = $request->email;
            }
            if ($request->has('phone_no')) {
                $updateData['phone_no'] = $request->phone_no;
            }

            // Update lead record
            $lead->update($updateData);

            // Update interview registration if it exists
            if ($lead->interviewRegistration) {
                $interviewUpdateData = [];
                if ($request->has('name')) {
                    $interviewUpdateData['full_name'] = $request->name;
                }
                if ($request->has('email')) {
                    $interviewUpdateData['email'] = $request->email;
                }
                if ($request->has('phone_no')) {
                    $interviewUpdateData['phone'] = $request->phone_no;
                }
                $lead->interviewRegistration->update($interviewUpdateData);
            }

            // Log edits to interview_edit_logs
            // Convert institute hash to numeric ID
            $institute = InstituteBasicDetails::where('fincap_merchant_id', $lead->institute_id)->first();
            $instituteNumericId = $institute ? $institute->id : null;

            $nameEditLog = null;
            $emailEditLog = null;
            $phoneEditLog = null;

            if ($instituteNumericId && $request->has('name') && $oldName !== $request->name) {
                $nameEditLog = \App\Models\InterviewEditLog::logEdit(
                    $lead->id,
                    $instituteNumericId,
                    'name',
                    $oldName,
                    $request->name,
                    $user->id
                );
            }

            if ($instituteNumericId && $request->has('email') && $oldEmail !== $request->email) {
                $emailEditLog = \App\Models\InterviewEditLog::logEdit(
                    $lead->id,
                    $instituteNumericId,
                    'email',
                    $oldEmail,
                    $request->email,
                    $user->id
                );
            }

            if ($instituteNumericId && $request->has('phone_no') && $oldPhone !== $request->phone_no) {
                $phoneEditLog = \App\Models\InterviewEditLog::logEdit(
                    $lead->id,
                    $instituteNumericId,
                    'phone_no',
                    $oldPhone,
                    $request->phone_no,
                    $user->id
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Contact information updated successfully',
                'lead' => [
                    'id' => $lead->id,
                    'name' => $lead->name,
                    'email' => $lead->email,
                    'phone_no' => $lead->phone_no,
                    'name_edit_count' => $nameEditLog?->edit_count ?? 0,
                    'email_edit_count' => $emailEditLog?->edit_count ?? 0,
                    'phone_edit_count' => $phoneEditLog?->edit_count ?? 0
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Contact update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating contact information: ' . $e->getMessage()
            ], 500);
        }
    }
}