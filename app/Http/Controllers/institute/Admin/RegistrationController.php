<?php
namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Lead;
use App\Models\ProductDetails;
use App\Traits\InstituteBranchAccess;
use App\Traits\DepartmentRelationships;
use Illuminate\Http\Request;
use App\Models\AdmissionRegistration;
use App\Models\InterviewRegistration;
use App\Models\InterviewConfiguration;
use App\Models\AdmissionProcessConfig;
use App\Models\RolesForAdmissionInterviewProcess;
use App\Models\LeadLog;
use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class RegistrationController extends Controller
{
    use InstituteBranchAccess, DepartmentRelationships;

    public function getAdmissionForm()
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $instituteId = $context['institute_id'];
        $selectedAcademicYear = '2026-2027';

        // Step 1: Fetch multiple records
        $configs = AdmissionProcessConfig::where('institute_id', $instituteId)
            ->where('academic_year', $selectedAcademicYear)
            ->get();

        // Step 2: Extract all products
        $products = [];

        foreach ($configs as $config) {

            // ❌ REMOVE json_decode
            // $decodedProducts = json_decode($config->product_id, true);

            // ✅ Use directly
            $decodedProducts = $config->product_id;

            if (!empty($decodedProducts)) {
                foreach ($decodedProducts as $product) {
                    $products[] = [
                        'id' => $product['id'] ?? null,
                        'name' => $product['name'] ?? null,
                    ];
                }
            }
        }

        // Step 3: Remove duplicates
        $products = collect($products)->unique('id')->values();

        // Debug


        return view('instituteAdmin.RegistrationSystem.admissionForm', [
            'admissionclass' => $products,
        ]);
    }

    // public function getAdmissionForm()
    // {
    //     $context = $this->getInstituteBranchContext();

    //     if (!$context['institute_id']) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'You are not associated with any institute.'
    //         ], 403);
    //     }

    //     $instituteId = $context['institute_id'];
    //     $branchId = $context['branch_id'] ?? null;
    //     $data = ProductDetails::where('institute_id', $instituteId)->get();
    //     return view('instituteAdmin.RegistrationSystem.admissionForm', [
    //         'admissionclass' => $data,// Pass to view
    //     ]);
    // }


    public function getInterviewForm()
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $instituteId = $context['institute_id'];

        $departments = InterviewConfiguration::with('department')
            ->where('institute_id', $instituteId)
            ->get()
            ->pluck('department.department')
            ->filter()
            ->unique()
            ->values();


        $departmentIds = InterviewConfiguration::where('institute_id', $instituteId)
            ->pluck('department_id')
            ->filter()
            ->unique()
            ->values();


        $interview_config_id = InterviewConfiguration::where('institute_id', $instituteId)
            ->pluck('interview_config_id')
            ->filter()
            ->unique()
            ->values();
        // dd($config_ids);    
        return view(
            'instituteAdmin.RegistrationSystem.InterviewForm',
            compact('departments', 'departmentIds', 'interview_config_id')
        );
    }

    public function getInterviewEnroll()
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $instituteId = $context['institute_id'];

        $departments = InterviewConfiguration::with('department')
            ->where('institute_id', $instituteId)
            ->get()
            ->pluck('department.department')
            ->filter()
            ->unique()
            ->values();

        $departmentIds = InterviewConfiguration::where('institute_id', $instituteId)
            ->pluck('department_id')
            ->filter()
            ->unique()
            ->values();

        $interview_config_id = InterviewConfiguration::where('institute_id', $instituteId)
            ->pluck('interview_config_id')
            ->filter()
            ->unique()
            ->values();

        return view(
            'instituteAdmin.AddLead.Interview.interviewEnroll',
            compact('departments', 'departmentIds', 'interview_config_id')
        );
    }

    public function getProfilesByDepartment($department)
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $instituteId = $context['institute_id'];

        // Get profiles (form_titles) for the selected department
        $profiles = InterviewConfiguration::with('department')
            ->where('institute_id', $instituteId)
            ->whereHas('department', function ($query) use ($department) {
                $query->where('department', $department);
            })
            ->get()
            ->pluck('form_title')
            ->filter()
            ->unique()
            ->values();

        return response()->json([
            'success' => true,
            'profiles' => $profiles
        ]);
    }

    public function getSkillsBySelection(Request $request)
    {
        $context = $this->getInstituteBranchContext();

        if (!$context['institute_id']) {
            return response()->json([
                'success' => false,
                'message' => 'You are not associated with any institute.'
            ], 403);
        }

        $instituteId = $context['institute_id'];
        $department = $request->department;
        $profile = $request->profile;

        // Initialize empty arrays for different skill categories
        $teachingSkills = [];
        $technicalSkills = [];
        $softSkills = [];
        $languageSkills = [];

        if ($department && $profile) {
            // Get the interview configuration for the selected department and profile
            $config = InterviewConfiguration::with('department')
                ->where('institute_id', $instituteId)
                ->whereHas('department', function ($query) use ($department) {
                    $query->where('department', $department);
                })
                ->where('form_title', $profile)
                ->first();

            if ($config && $config->skills) {
                // Parse skills from JSON
                $skills = is_string($config->skills) ? json_decode($config->skills, true) : $config->skills;

                if (is_array($skills)) {
                    // Categorize skills
                    foreach ($skills as $skill) {
                        // Check if skill is an array or object and extract the string value
                        $skillString = '';

                        if (is_array($skill)) {
                            // If it's an array, try to get the skill name from common keys
                            if (isset($skill['name'])) {
                                $skillString = $skill['name'];
                            } elseif (isset($skill['skill'])) {
                                $skillString = $skill['skill'];
                            } elseif (isset($skill['value'])) {
                                $skillString = $skill['value'];
                            } else {
                                // If no recognizable key, take the first value
                                $skillString = reset($skill);
                            }
                        } elseif (is_object($skill)) {
                            // If it's an object, try to get properties
                            if (property_exists($skill, 'name')) {
                                $skillString = $skill->name;
                            } elseif (property_exists($skill, 'skill')) {
                                $skillString = $skill->skill;
                            } elseif (property_exists($skill, 'value')) {
                                $skillString = $skill->value;
                            } else {
                                // Convert object to string if possible
                                $skillString = (string) $skill;
                            }
                        } else {
                            // It's already a string
                            $skillString = (string) $skill;
                        }

                        // Skip if empty
                        if (empty($skillString)) {
                            continue;
                        }

                        $skillLower = strtolower(trim($skillString));

                        // Categorize based on keywords
                        if (in_array($skillLower, ['lesson planning', 'classroom management', 'curriculum development', 'student assessment', 'differentiated instruction', 'child psychology', 'special education', 'early childhood education'])) {
                            $teachingSkills[] = $skillString;
                        } elseif (in_array($skillLower, ['ms office', 'google classroom', 'zoom/meet', 'learning management system', 'educational technology', 'online teaching tools', 'data analysis', 'programming basics'])) {
                            $technicalSkills[] = $skillString;
                        } elseif (in_array($skillLower, ['communication', 'leadership', 'team collaboration', 'problem solving', 'patience', 'creativity', 'empathy', 'adaptability', 'conflict resolution', 'time management'])) {
                            $softSkills[] = $skillString;
                        } elseif (in_array($skillLower, ['english', 'hindi', 'regional language', 'foreign language'])) {
                            $languageSkills[] = $skillString;
                        } else {
                            // Default to teaching skills if not categorized
                            $teachingSkills[] = $skillString;
                        }
                    }
                }
            }
        }

        // Remove duplicates
        $teachingSkills = array_values(array_unique($teachingSkills));
        $technicalSkills = array_values(array_unique($technicalSkills));
        $softSkills = array_values(array_unique($softSkills));
        $languageSkills = array_values(array_unique($languageSkills));

        return response()->json([
            'success' => true,
            'skills' => [
                'teaching' => $teachingSkills,
                'technical' => $technicalSkills,
                'soft' => $softSkills,
                'language' => $languageSkills
            ]
        ]);
    }

    public function saveAdmission(Request $request)
    {
        try {
            // Get institute/branch context
            $context = $this->getInstituteBranchContext();

            if (!$context['institute_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not associated with any institute.'
                ], 403);
            }

            $instituteId = $context['institute_id'];
            $branchId = $context['branch_id'] ?? null;

            $normalizedPhone = preg_replace('/\D+/', '', (string) $request->input('phone'));
            $phoneConflict = Lead::where('institute_id', $instituteId)
                ->whereRaw("REPLACE(REPLACE(REPLACE(phone_no, '+91', ''), ' ', ''), '-', '') = ?", [$normalizedPhone])
                ->exists()
                || EmployeeDetails::where('institute_id', $instituteId)
                    ->where('mobile_number', $normalizedPhone)
                    ->exists();
            $emailConflict = $request->filled('email') && (
                Lead::where('institute_id', $instituteId)->where('email', $request->input('email'))->exists()
                || EmployeeDetails::where('institute_id', $instituteId)->where('email', $request->input('email'))->exists()
            );

            if ($phoneConflict || $emailConflict) {
                Log::warning('Duplicate interview registration blocked', [
                    'institute_id' => $instituteId,
                    'phone' => $normalizedPhone,
                    'email' => $request->input('email'),
                    'phone_conflict' => $phoneConflict,
                    'email_conflict' => $emailConflict,
                ]);

                $messages = [];
                if ($phoneConflict) {
                    $messages[] = 'You are already registered with us using this mobile number.';
                }
                if ($emailConflict) {
                    $messages[] = 'You are already registered with us using this email address.';
                }

                return response()->json([
                    'success' => false,
                    'message' => implode(' ', $messages),
                    'duplicate_mobile' => $phoneConflict,
                    'duplicate_email' => $emailConflict,
                ], 422);
            }

            // Get mode and source from session (set by main form)
            $registrationMode = Session::get('registration_mode', 'online');
            $source = Session::get('registration_source', 'website');

            // Check if this is initial lead creation or complete application
            $isInitialLead = $request->input('is_initial_lead', false);
            $isCompleteApplication = $request->input('is_complete_application', false);
            $leadId = $request->input('lead_id');

            if ($isInitialLead) {
                // STEP 1-2: Create initial lead with APPLICANT TYPE + BASIC STUDENT DETAILS + CONTACT
                return $this->createInitialLead($request, $instituteId, $branchId, $registrationMode, $source);
            } elseif ($isCompleteApplication && $leadId) {
                // STEP 3-5: Update existing lead with COMPLETE information
                return $this->updateLeadWithCompleteInfo($request, $leadId, $instituteId, $branchId);
            } else {
                // Fallback: Original complete submission (all in one)
                return $this->completeAdmissionSubmission($request, $instituteId, $branchId, $registrationMode, $source);
            }

        } catch (\Exception $e) {
            Log::error('Admission Error: ' . $e->getMessage());
            Log::error('Admission Error Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error processing admission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create initial lead with APPLICANT TYPE + BASIC DETAILS + CONTACT (Step 1-2)
     */
    private function createInitialLead(Request $request, $instituteId, $branchId, $registrationMode, $source)
    {
        DB::beginTransaction();

        try {

            // ✅ Validate required fields
            $requiredFields = [
                'applicant_type',
                'student_full_name',
                'student_dob',
                'student_gender',
                'applying_for_grade',
                'email',
                'phone'
            ];

            foreach ($requiredFields as $field) {
                if (!$request->filled($field)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Please fill all required fields: " . str_replace('_', ' ', $field)
                    ], 422);
                }
            }

            // ✅ Generate reference ID
            $referenceId = 'ADM-' . date('Y') . '-' . rand(1000, 9999);

            // ✅ Create Admission Registration
            $registration = AdmissionRegistration::create([
                'reference_id' => $referenceId,
                'registration_mode' => $registrationMode,
                'source' => $source,
                'applicant_type' => $request->input('applicant_type', 'self'),
                'status' => 'pending',
                'institute_id' => $instituteId,
                'branch_id' => $branchId,
                'student_full_name' => $request->input('student_full_name'),
                'student_dob' => $request->input('student_dob'),
                'student_gender' => $request->input('student_gender'),
                'student_nationality' => $request->input('student_nationality', ''),
                'applying_for_grade' => $request->input('applying_for_grade'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'alternate_phone' => $request->input('alternate_phone', ''),
                'address_line_1' => $request->input('address_line_1', ''),
                'address_line_2' => $request->input('address_line_2', ''),
                'address' => trim($request->input('address_line_1', '') . ' ' . $request->input('address_line_2', '')),
                'city' => $request->input('city', ''),
                'state' => $request->input('state', ''),
                'pincode' => $request->input('pincode', ''),
            ]);

            // ✅ Generate lead ID safely (prevents duplicate in high traffic)
            $lastLead = Lead::lockForUpdate()->orderBy('id', 'desc')->first();

            if ($lastLead && $lastLead->lead_id) {
                $lastNumber = (int) str_replace('LEAD-', '', $lastLead->lead_id);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            $lead_id = 'LEAD-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            // ✅ Determine Lead Name
            $leadName = $request->input('applicant_type') == 'parent'
                ? $request->input('student_full_name') . ' (Parent)'
                : $request->input('student_full_name');

            // ✅ Get First Active Agent
            $role = RolesForAdmissionInterviewProcess::with('employee')
                ->where('institute_id', $instituteId)
                ->where('type', 'agent')
                ->where('status', 'active')
                ->first(); // FIXED (not get())

            // Safe access
            $defaultAssignName = $role?->employee?->name ?? null;
            $defaultAssignType = $role?->type ?? null;
            $defaultAssignId = $role?->role_hash_id ?? null;
            Log::info('Assigning lead to: ' . ($defaultAssignName ?? 'No active agent found'));
            // ✅ Create Lead
            Lead::create([
                'lead_id' => $lead_id,
                'reference_id' => $referenceId,
                'institute_id' => $instituteId,
                'applicant_type' => $request->input('applicant_type', 'self'),
                'name' => $leadName,
                'email' => $request->input('email'),
                'phone_no' => $request->input('phone'),
                'registration_mode' => $registrationMode,
                'applying_for_grade' => $request->input('applying_for_grade'),
                'lead_type' => 'Admission',
                'lead_status' => 'cold',
                'default_assign' => $defaultAssignName,
                'default_assign_type' => $defaultAssignType,
                'default_assign_id' => $defaultAssignId,
            ]);
            Log::info('Lead created with ID: ' . $lead_id . ' and assigned to: ' . $defaultAssignName);
            LeadLog::create([
                'lead_id' => $lead_id,
                'action' => 'Lead Created',
                'remarks' => 'New lead created'
            ]);
            Log::info('lead_log entry created for lead ID: ' . $lead_id);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Lead created successfully! Please continue with additional details.',
                'reference_id' => $referenceId,
                'lead_id' => $lead_id,
                'step' => '2'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Lead creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    /**
     * Update lead with COMPLETE information (Step 3-5)
     */
    private function updateLeadWithCompleteInfo(Request $request, $leadId, $instituteId, $branchId)
    {
        // Find existing lead
        $lead = Lead::where('lead_id', $leadId)->first();

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found. Please start over.'
            ], 404);
        }

        // Find existing admission registration
        $registration = AdmissionRegistration::where('reference_id', $lead->reference_id)->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Admission registration not found.'
            ], 404);
        }

        // Prepare update data
        $updateData = [
            // Address Information (Step 3)
            'address_line_1' => $request->input('address_line_1', ''),
            'address_line_2' => $request->input('address_line_2', ''),
            'address' => $request->input('address', trim($request->input('address_line_1', '') . ' ' . $request->input('address_line_2', ''))),
            'city' => $request->input('city', ''),
            'state' => $request->input('state', ''),
            'pincode' => $request->input('pincode', ''),
            'alternate_phone' => $request->input('alternate_phone', $registration->alternate_phone),

            // Educational Background (Step 4)
            'previous_school' => $request->input('previous_school', ''),
            'previous_class' => $request->input('previous_class', ''),
            'school_location' => $request->input('school_location', ''),
            'percentage_cgpa' => $request->input('percentage_cgpa', ''),
            'marks_format' => $request->input('marks_format', 'percentage'),
            'current_school' => $request->input('previous_school', ''), // Map to current_school
            'current_grade' => $request->input('previous_class', ''), // Map to current_grade
            'previous_qualification' => $request->input('previous_class', ''),

            // Sibling information (Step 4)
            'sibling_option' => $request->input('sibling_option', 'no'),
            'sibling_name' => $request->input('sibling_name', ''),
            'sibling_class' => $request->input('sibling_class', ''),
            'sibling_section' => $request->input('sibling_section', ''),
            'sibling_admission_no' => $request->input('sibling_admission_no', ''),
            'sibling_academic_year' => $request->input('sibling_academic_year', ''),

            // Guardian information (Step 4)
            'father_name' => $request->input('father_name', ''),
            'father_occupation' => $request->input('father_occupation', ''),
            'father_phone' => $request->input('father_phone', ''),
            'father_email' => $request->input('father_email', ''),
            'mother_name' => $request->input('mother_name', ''),
            'mother_occupation' => $request->input('mother_occupation', ''),
            'mother_phone' => $request->input('mother_phone', ''),
            'mother_email' => $request->input('mother_email', ''),
        ];

        Log::info('Updating admission with data:', $updateData);

        // Update the registration
        $registration->update($updateData);

        // Update lead status (if notes column exists, add conditional check)
        $leadUpdateData = [
            'lead_status' => 'hot',
            'updated_at' => now(),
        ];

        // Only update notes if column exists
        // $leadUpdateData['notes'] = 'Application completed with all details';

        $lead->update($leadUpdateData);

        return response()->json([
            'success' => true,
            'message' => 'Application completed successfully!',
            'reference_id' => $registration->reference_id,
            'lead_id' => $leadId
        ]);
    }

    /**
     * New route for updating admission (for Step 3-5 updates)
     */
    public function updateAdmission(Request $request)
    {
        try {
            $leadId = $request->input('lead_id');

            if (!$leadId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lead ID is required for update.'
                ], 400);
            }

            // Get institute/branch context
            $context = $this->getInstituteBranchContext();

            if (!$context['institute_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not associated with any institute.'
                ], 403);
            }

            $instituteId = $context['institute_id'];
            $branchId = $context['branch_id'] ?? null;

            return $this->updateLeadWithCompleteInfo($request, $leadId, $instituteId, $branchId);

        } catch (\Exception $e) {
            Log::error('Update Admission Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating admission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Original complete submission (fallback - all in one)
     */
    private function completeAdmissionSubmission(Request $request, $instituteId, $branchId, $registrationMode, $source)
    {
        // Generate reference ID
        $referenceId = 'ADM-' . date('Y') . '-' . rand(1000, 9999);

        // Map request data to database columns
        $data = [
            'reference_id' => $referenceId,
            'registration_mode' => $registrationMode, // From session
            'source' => $source, // From session
            'applicant_type' => $request->input('applicant_type', 'self'),
            'status' => 'pending',
            'institute_id' => $instituteId,
            'branch_id' => $branchId,

            // Student Details
            'student_full_name' => $request->input('student_full_name'),
            'student_dob' => $request->input('student_dob'),
            'student_gender' => $request->input('student_gender'),
            'student_nationality' => $request->input('student_nationality'),
            'applying_for_grade' => $request->input('applying_for_grade'),

            // Educational Background
            'current_school' => $request->input('previous_school'),
            'current_grade' => $request->input('previous_class'),
            'previous_qualification' => 'Previous Class ' . $request->input('previous_class'),
            'percentage_cgpa' => $request->input('percentage_cgpa'),
            'marks_format' => $request->input('marks_format', 'percentage'),

            // Contact Information
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'alternate_phone' => $request->input('alternate_phone'),
            'address_line_1' => $request->input('address_line_1'),
            'address_line_2' => $request->input('address_line_2'),
            'address' => trim($request->input('address_line_1') . ' ' . $request->input('address_line_2')),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'pincode' => $request->input('pincode'),

            // Sibling Information
            'sibling_option' => $request->input('sibling_option', 'no'),
            'sibling_name' => $request->input('sibling_name', ''),
            'sibling_class' => $request->input('sibling_class', ''),
            'sibling_section' => $request->input('sibling_section', ''),
            'sibling_admission_no' => $request->input('sibling_admission_no', ''),
            'sibling_academic_year' => $request->input('sibling_academic_year', ''),

            // Guardian Information
            'father_name' => $request->input('father_name'),
            'father_occupation' => $request->input('father_occupation'),
            'father_phone' => $request->input('father_phone'),
            'father_email' => $request->input('father_email'),
            'mother_name' => $request->input('mother_name'),
            'mother_occupation' => $request->input('mother_occupation'),
            'mother_phone' => $request->input('mother_phone'),
            'mother_email' => $request->input('mother_email'),

            // For parent/guardian type
            'parent_full_name' => $request->input('applicant_type') == 'parent' ? $request->input('student_full_name') : '',
            'relationship' => $request->input('applicant_type') == 'parent' ? 'parent' : '',
        ];

        Log::info('Complete admission submission with data:', $data);

        // Save to database
        $registration = AdmissionRegistration::create($data);

        // Generate lead
        $lastLead = Lead::orderBy('id', 'desc')->first();
        if ($lastLead && $lastLead->lead_id) {
            $lastNumber = (int) str_replace('LEAD-', '', $lastLead->lead_id);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $lead_id = 'LEAD-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Determine name for lead
        $leadName = $request->input('applicant_type') == 'parent'
            ? $request->input('student_full_name') . ' (Parent)'
            : $request->input('student_full_name');

        // Create Lead WITHOUT notes and lead_source columns
        Lead::create([
            'lead_id' => $lead_id,
            'reference_id' => $referenceId,
            'institute_id' => $instituteId,
            'applicant_type' => $request->input('applicant_type', 'self'),
            'name' => $leadName,
            'email' => $request->input('email'),
            'phone_no' => $request->input('phone'),
            'registration_mode' => $registrationMode,
            'lead_type' => 'Admission',
            'lead_status' => 'warm',
            'default_assign' => 'Admin',
            // No notes or lead_source columns
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully!',
            'reference_id' => $referenceId,
            'lead_id' => $lead_id
        ]);
    }

    /**
     * SAVE INTERVIEW FORM
     */
    public function saveInterview(Request $request)
    {
        try {
            // Generate reference ID
            $referenceId = 'INT-' . date('Y') . '-' . rand(1000, 9999);

            // Get institute/branch context
            $context = $this->getInstituteBranchContext();

            if (!$context['institute_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not associated with any institute.'
                ], 403);
            }

            Log::info('Interview submission received:', $request->all());

            // Check if applying_for is present
            if (!$request->has('applying_for')) {
                Log::error('applying_for field is missing from request');
                return response()->json([
                    'success' => false,
                    'message' => 'Department field is required'
                ], 422);
            }

            $instituteId = $context['institute_id'];
            $branchId = $context['branch_id'] ?? null;

            // Get mode and source from session or request
            $registrationMode = $request->input('mode', Session::get('registration_mode', 'online'));
            $source = $request->input('source', Session::get('registration_source', 'website'));

            // FIND THE CORRECT CONFIG_ID BASED ON DEPARTMENT AND PROFILE
            $interview_config_id = null;
            $department = $request->input('applying_for');
            $profile = $request->input('applying_for_profile');

            if ($department && $profile && $department !== 'other' && $profile !== 'other') {
                // Find the interview configuration that matches this department and profile
                $config = InterviewConfiguration::with('department')
                    ->where('institute_id', $instituteId)
                    ->whereHas('department', function ($query) use ($department) {
                        $query->where('department', $department);
                    })
                    ->where('form_title', $profile)
                    ->first();

                if ($config) {
                    $interview_config_id = $config->interview_config_id;
                    Log::info('Found interview_config_id: ' . $interview_config_id . ' for department: ' . $department . ', profile: ' . $profile);
                }
            }


            // Handle file upload with more flexible validation
            $resumePath = null;
            if ($request->hasFile('resume')) {
                $file = $request->file('resume');

                // Log file details for debugging
                Log::info('Uploaded file details:', [
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'extension' => $file->getClientOriginalExtension(),
                    'error' => $file->getError()
                ]);

                // More flexible validation - check by extension first, then try MIME
                $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
                $extension = strtolower($file->getClientOriginalExtension());

                if (!in_array($extension, $allowedExtensions)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid file format. Allowed formats: PDF, DOC, DOCX, JPG, PNG'
                    ], 422);
                }

                // Check file size (5MB max)
                if ($file->getSize() > 5 * 1024 * 1024) {
                    $sizeInMB = round($file->getSize() / (1024 * 1024), 2);
                    return response()->json([
                        'success' => false,
                        'message' => "File size ({$sizeInMB} MB) exceeds the 5MB limit."
                    ], 422);
                }

                // Create directory if it doesn't exist
                $uploadPath = public_path('uploads/resumes');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                // Generate unique filename with original extension
                $fileName = time() . '_' . uniqid() . '.' . $extension;

                // Move file to public/uploads/resumes
                $file->move($uploadPath, $fileName);

                // Store path in database (relative path)
                $resumePath = 'uploads/resumes/' . $fileName;

                Log::info('Resume uploaded successfully: ' . $resumePath);
            }

            // Map request data to database columns
            $data = [
                'reference_id' => $referenceId,
                'registration_mode' => $registrationMode,
                'source' => $source,
                'institute_id' => $instituteId,
                'branch_id' => $branchId,
                'full_name' => $request->input('full_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'applying_for' => $request->input('applying_for'),
                'applying_for_profile' => $request->input('applying_for_profile'),
                'dob' => $request->input('dob'),
                'profession' => $request->input('profession'),
                'organization' => $request->input('organization'),
                'qualification' => $request->input('qualification'),
                'purpose' => $request->input('purpose'),
                'experience_in_year' => $request->input('experience_in_year', 0),
                'experience_in_month' => $request->input('experience_in_month', 0),
                'marital_status' => $request->input('marital_status'),
                'skills' => json_encode($request->input('skills', [])),
                'resume_path' => $resumePath,
                'interview_config_id' => $interview_config_id, // This will now be defined
            ];

            Log::info('Interview Registration Data:', $data);

            // Save to database
            $registration = InterviewRegistration::create($data);

            // Generate lead ID
            $lastLead = Lead::orderBy('id', 'desc')->first();
            if ($lastLead && $lastLead->lead_id) {
                $lastNumber = (int) str_replace('LEAD-', '', $lastLead->lead_id);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            // ✅ Get First Active Agent
            $role = RolesForAdmissionInterviewProcess::with('employee')
                ->where('institute_id', $instituteId)
                ->where('type', 'interviewer')
                ->where('status', 'active')
                ->first();

            // Safe access
            $defaultAssignName = $role?->employee?->name ?? null;
            $defaultAssignType = $role?->type ?? null;
            $defaultAssignId = $role?->role_hash_id ?? null;

            $lead_id = 'LEAD-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

            // Create Lead for Interview
            $lead = Lead::create([
                'lead_id' => $lead_id,
                'reference_id' => $referenceId,
                'institute_id' => $instituteId,
                'applicant_type' => 'interview',
                'name' => $request->input('full_name'),
                'email' => $request->input('email'),
                'phone_no' => $request->input('phone'),
                'registration_mode' => $registrationMode,
                'lead_type' => 'Interview',
                'lead_status' => 'Cold',
                'default_assign' => $defaultAssignName,
                'default_assign_id' => $defaultAssignId,
                'default_assign_type' => $defaultAssignType,

            ]);

            Log::info('Interview lead generated', [
                'lead_id' => $lead->id,
                'lead_identifier' => $lead->lead_id,
                'institute_id' => $lead->institute_id,
                'interview_config_id' => $interview_config_id,
            ]);

            if ($lead->email) {
                try {
                    $candidateJourneyUrl = URL::temporarySignedRoute(
                        'admission.send.otp',
                        now()->addDays(7),
                        ['lead_id' => $lead->lead_id, 'purpose' => 'journey']
                    );

                    Mail::send('emails.interview-lead-created', [
                        'lead' => $lead,
                        'candidateJourneyUrl' => $candidateJourneyUrl,
                    ], function ($message) use ($lead) {
                        $message->to($lead->email)
                            ->subject('Your interview application has been received');
                    });

                    Log::info('Interview lead journey email sent', [
                        'lead_id' => $lead->id,
                        'lead_identifier' => $lead->lead_id,
                        'email' => $lead->email,
                        'candidate_view_url' => $candidateJourneyUrl,
                    ]);
                } catch (\Throwable $mailException) {
                    Log::error('Interview lead journey email failed', [
                        'lead_id' => $lead->id,
                        'lead_identifier' => $lead->lead_id,
                        'email' => $lead->email,
                        'error' => $mailException->getMessage(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Interview scheduled successfully!',
                'reference_id' => $referenceId,
                'lead_id' => $lead_id,
                'interview_config_id' => $interview_config_id, // Return for debugging
                'resume_uploaded' => $resumePath ? true : false
            ]);

        } catch (\Exception $e) {
            Log::error('Interview Error: ' . $e->getMessage());
            Log::error('Interview Error Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error scheduling interview: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * SAVE PREFERENCES - Called by main form
     */
    public function savePreferences(Request $request)
    {
        // Save mode and source to session for later use
        Session::put('registration_mode', $request->mode);
        Session::put('registration_source', $request->source);

        return response()->json(['success' => true]);
    }

    public function saveInterviewRounds(Request $request)
    {
        try {
            $context = $this->getInstituteBranchContext();
            $instituteId = $context['institute_id'];
            $branchId = $context['branch_id'] ?? null;

            // Get the rounds data from request (assuming it's an array)
            $rounds = $request->rounds; // Example: [['name'=>'Round 1', 'type'=>'technical'], ...]

            // Get the last round ID number
            $lastRound = InterviewRound::orderBy('id', 'desc')->first();
            $lastNumber = 0;
            if ($lastRound && $lastRound->interview_round_id) {
                $lastNumber = (int) str_replace('ROUND-', '', $lastRound->interview_round_id);
            }

            $insertData = [];
            foreach ($rounds as $index => $round) {
                // Generate unique ID for each round
                $newNumber = $lastNumber + $index + 1;
                $interview_round_id = 'ROUND-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

                $insertData[] = [
                    'interview_round_id' => $interview_round_id,
                    'interview_config_id' => $request->interview_config_id,
                    'name' => $round['name'],
                    'desc' => $round['desc'] ?? null,
                    'type' => $round['type'],
                    'type_label' => $round['type_label'] ?? null,
                    'round_date' => $round['round_date'] ?? null,
                    'start_time' => $round['start_time'] ?? null,
                    'end_time' => $round['end_time'] ?? null,
                    'duration' => $round['duration'] ?? null,
                    'panel' => $round['panel'] ?? null,
                    'status' => $round['status'] ?? 'active',
                    'institute_id' => $instituteId,
                    'branch_id' => $branchId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insert all rounds at once
            InterviewRound::insert($insertData);

            return response()->json([
                'success' => true,
                'message' => count($insertData) . ' interview rounds saved successfully',
                'data' => $insertData
            ]);

        } catch (\Exception $e) {
            Log::error('Error saving interview rounds: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Add these methods to your LeadsController
    public function showLeadView()
    {
        return view('instituteAdmin.AddLead.LeadViewAdmission');
    }

    public function show($id)
    {
        $lead = Lead::findOrFail($id);

        // Get steps configuration or use default
        $steps = $lead->steps ? json_decode($lead->steps, true) : $this->getDefaultSteps();

        return response()->json([
            'success' => true,
            'lead' => $lead,
            'steps' => $steps
        ]);
    }

    public function updateSteps($id, Request $request)
    {
        $lead = Lead::findOrFail($id);

        $step = $request->input('step');
        $enabled = $request->input('enabled');

        // Get current steps
        $steps = $lead->steps ? json_decode($lead->steps, true) : $this->getDefaultSteps();

        // Update the specific step
        if (isset($steps[$step])) {
            $steps[$step]['enabled'] = $enabled;

            // If disabling a required step, make it not required
            if (!$enabled) {
                $steps[$step]['required'] = false;
            }

            // Save to database
            $lead->steps = json_encode($steps);
            $lead->save();

            return response()->json([
                'success' => true,
                'message' => 'Step updated successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Step not found'
        ]);
    }

    public function saveSteps($id, Request $request)
    {
        $lead = Lead::findOrFail($id);

        $newSteps = $request->input('steps');

        // Merge with existing steps to preserve status
        $currentSteps = $lead->steps ? json_decode($lead->steps, true) : $this->getDefaultSteps();

        foreach ($newSteps as $step => $data) {
            if (isset($currentSteps[$step])) {
                $currentSteps[$step]['enabled'] = $data['enabled'];
                $currentSteps[$step]['required'] = $data['required'];
            }
        }

        $lead->steps = json_encode($currentSteps);
        $lead->save();

        return response()->json([
            'success' => true,
            'message' => 'Steps configuration saved successfully'
        ]);
    }

    public function updateStepStatus($id, Request $request)
    {
        $lead = Lead::findOrFail($id);

        $direction = $request->input('direction');
        $steps = $lead->steps ? json_decode($lead->steps, true) : $this->getDefaultSteps();
        $stepsOrder = ['admission', 'entranceTest', 'counselling', 'onboarding'];

        // Find current step
        $currentStepIndex = -1;
        foreach ($stepsOrder as $index => $step) {
            if ($steps[$step]['enabled'] && $steps[$step]['status'] !== 'completed') {
                $currentStepIndex = $index;
                break;
            }
        }

        if ($direction === 'next' && $currentStepIndex < count($stepsOrder) - 1) {
            // Mark current step as completed
            $currentStep = $stepsOrder[$currentStepIndex];
            $steps[$currentStep]['status'] = 'completed';

            // Find and mark next enabled step as in-progress
            for ($i = $currentStepIndex + 1; $i < count($stepsOrder); $i++) {
                $nextStep = $stepsOrder[$i];
                if ($steps[$nextStep]['enabled']) {
                    $steps[$nextStep]['status'] = 'in-progress';
                    break;
                }
            }
        } elseif ($direction === 'prev' && $currentStepIndex > 0) {
            // Mark current step as pending
            $currentStep = $stepsOrder[$currentStepIndex];
            $steps[$currentStep]['status'] = 'pending';

            // Find and mark previous enabled step as in-progress
            for ($i = $currentStepIndex - 1; $i >= 0; $i--) {
                $prevStep = $stepsOrder[$i];
                if ($steps[$prevStep]['enabled']) {
                    $steps[$prevStep]['status'] = 'in-progress';
                    break;
                }
            }
        }

        $lead->steps = json_encode($steps);
        $lead->save();

        return response()->json([
            'success' => true,
            'message' => 'Step status updated'
        ]);
    }

    public function completeCurrentStep($id)
    {
        $lead = Lead::findOrFail($id);

        $steps = $lead->steps ? json_decode($lead->steps, true) : $this->getDefaultSteps();
        $stepsOrder = ['admission', 'entranceTest', 'counselling', 'onboarding'];

        // Find current in-progress step and mark it as completed
        foreach ($stepsOrder as $step) {
            if ($steps[$step]['enabled'] && $steps[$step]['status'] === 'in-progress') {
                $steps[$step]['status'] = 'completed';

                // Find next enabled step and mark it as in-progress
                $currentIndex = array_search($step, $stepsOrder);
                for ($i = $currentIndex + 1; $i < count($stepsOrder); $i++) {
                    $nextStep = $stepsOrder[$i];
                    if ($steps[$nextStep]['enabled']) {
                        $steps[$nextStep]['status'] = 'in-progress';
                        break;
                    }
                }
                break;
            }
        }

        $lead->steps = json_encode($steps);
        $lead->save();

        return response()->json([
            'success' => true,
            'message' => 'Current step marked as complete'
        ]);
    }

    private function getDefaultSteps()
    {
        return [
            'admission' => [
                'status' => 'pending',
                'fee' => false,
                'enabled' => true,
                'required' => true
            ],
            'entranceTest' => [
                'status' => 'pending',
                'fee' => false,
                'enabled' => true,
                'required' => true
            ],
            'counselling' => [
                'status' => 'pending',
                'enabled' => true,
                'required' => true
            ],
            'onboarding' => [
                'status' => 'pending',
                'enabled' => true,
                'required' => true
            ]
        ];
    }

    // Helper function to insert a round status record (call this when you need it)
    public function createRoundStatus($leadId, $data)
    {
        try {
            $context = $this->getInstituteBranchContext();

            $roundStatus = RoundStatus::create([
                'institute_id' => $context['institute_id'],
                'branch_id' => $context['branch_id'] ?? null,
                'lead_id' => $leadId,
                'name' => $data['name'],
                'round_status' => null, // Keep it empty initially
                'type' => $data['type'] ?? null,
                'type_label' => $data['type_label'] ?? null,
                'round_date' => $data['round_date'] ?? null,
                'start_time' => $data['start_time'] ?? null,
                'end_time' => $data['end_time'] ?? null,
                'duration' => $data['duration'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Round status created successfully',
                'data' => $roundStatus
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating round status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send OTP to Email for interview email verification
     */
    public function sendInterviewEmailOtp(Request $request)
    {
        $email = $request->input('email_id');
        $otp_verification_type = $request->input('otp_verification_type', 'interview_email_verification');

        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Email address is required'
            ]);
        }

        try {
            // Generate OTP
            $otp = rand(1000, 9999);
            $otp_expires_time = Carbon::now('Asia/Kolkata')->addMinutes(2);

            // Email data
            $data = [
                'otp' => $otp,
                'email' => $email,
                'title' => "Email OTP Verification"
            ];

            // Send Mail - Using direct Mail::send() with explicit from address
            Mail::send('user/emailOtpTemplate', $data, function ($message) use ($data) {
                $message->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($data["email"])
                    ->subject($data["title"]);
            });

            // Store OTP in session for verification
            session([
                'interview_email_otp' => $otp,
                'interview_email_otp_expires' => $otp_expires_time,
                'interview_email' => $email,
                'interview_otp_type' => $otp_verification_type
            ]);

            \Log::info('Interview Email OTP sent successfully to: ' . $email);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Interview Email OTP sending failed: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify Email OTP for interview
     */
    public function verifyInterviewEmailOtp(Request $request)
    {
        $otp = $request->input('otp');
        $email = $request->input('email');

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP is required'
            ]);
        }

        try {
            $stored_otp = session('interview_email_otp');
            $otp_expires = session('interview_email_otp_expires');
            $stored_email = session('interview_email');

            // Verify OTP exists
            if (!$stored_otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP not found. Please request a new OTP.'
                ], 400);
            }

            // Verify OTP hasn't expired
            if (Carbon::now('Asia/Kolkata') > $otp_expires) {
                session()->forget(['interview_email_otp', 'interview_email_otp_expires', 'interview_email', 'interview_otp_type']);
                return response()->json([
                    'success' => false,
                    'message' => 'OTP has expired. Please request a new one.'
                ], 400);
            }

            // Verify OTP matches
            if ($otp != $stored_otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP. Please try again.'
                ], 400);
            }

            // OTP verified successfully
            session(['interview_email_verified' => true]);

            \Log::info('Interview Email verified for: ' . $stored_email);

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Interview Email OTP verification failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error verifying OTP: ' . $e->getMessage()
            ], 500);
        }
    }
}