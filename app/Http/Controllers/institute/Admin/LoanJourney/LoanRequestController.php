<?php
namespace App\Http\Controllers\institute\Admin\LoanJourney;

use App\Http\Controllers\Controller;
use App\Models\StudentParentDetails;
use App\Models\InstituteBasicDetails;
use App\Models\StudentParentDocuments;
use App\Models\StudentParentAddress;
use App\Models\WeebhookFlyhiResponse;
use App\Models\LoanRequestDetails;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentAcademicTransportDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use PDF;

class LoanRequestController extends Controller
{
    public function showMultipleLoanRequests()
    {
       $loanRequests = LoanRequestDetails::where('institute_id', auth()->user()->institute_id)
        ->where('user_id', auth()->user()->id)
        ->get();
        if($loanRequests){
            return view('instituteAdmin.LoanFiles.showMultipleLoanRequest', compact('loanRequests')); 
        }else{  
            $loanRequests = [];    
            return view('instituteAdmin.LoanFiles.showMultipleLoanRequest', compact('loanRequests')); 
        }
    }
    public function getLoanByUserWise()
    {
        $loanRequests = LoanRequestDetails::where('institute_id', auth()->user()->institute_id)
            ->where('user_id', auth()->user()->id)
            ->get();
        if ($loanRequests->isEmpty()) {
            // Optional: Add a flash message
            session()->flash('info', 'No loan requests found.');
        }
        
        return view('instituteAdmin.LoanFiles.LoanRequestViewDetails', compact('loanRequests'));
    }
    public function selectloanRequestDetails()
    {
        return view('instituteAdmin.LoanFiles.LoanRequestDetails');
    }
    public function loanJourneyList($loan_request_id)
    {
        $loanRequests = LoanRequestDetails::where('institute_id', auth()->user()->institute_id)
            ->where('loan_request_id', $loan_request_id)
            ->first();
        
        if($loanRequests){
            $loan_lead_id = $loanRequests->lead_id ?? null;
            $loan_journey = WeebhookFlyhiResponse::where('webhook_flyhi_response_id', $loan_lead_id)
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            $loan_journey = collect(); // Empty collection
        }
        
        return view('instituteAdmin.LoanFiles.LoanJourney', compact('loan_journey')); 
    }
    public function submitRequest(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'loan_amount' => 'required|numeric|min:2000|max:100000',
            'tenure' => 'required|integer|min:3|max:12',
            'applicant_type' => 'required|in:self,parents,guardian',
            'relation' => 'required_if:applicant_type,parents,guardian|string|nullable',
        ], [
            'loan_amount.min' => 'Minimum loan amount is ₹10,000',
            'loan_amount.max' => 'Maximum loan amount is ₹1,00,000',
        ]);
        // Prepare data to store in session
        $loanRequestData = [
            'loan_amount' => $request->loan_amount,
            'tenure' => $request->tenure,
            'applicant_type' => $request->applicant_type,
            'relation' => $request->relation ?? null,
            'submitted_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'selected_fee_data' => $request->selectInstallmentData,
        ];
        
        // Store all data in session
        session(['loan_request' => $loanRequestData]);
        
        // Optional: Also store in flash session for one-time display
        // session()->flash('loan_request_success', $loanRequestData);

        // Return response with session data
        return redirect('loan/journey/user-conformation');
    }
    public function loanRequestDetails(Request $request)
    {
        // $applicant_type = $request->input('applicant_type');
        $user_id = auth()->user()->id;
        $studentParentDetail = StudentParentDetails::where('user_id', $user_id)
        ->where('status', 'active')
        ->first();
        if($studentParentDetail){
            $studentParentAddress = StudentParentAddress::where('student_hash_id', $studentParentDetail->student_hash_id)       
            ->first();
            $studentAcademicTransportDetails = StudentAcademicTransportDetails::where('student_hash_id', $studentParentDetail->student_hash_id)   
            ->first();
            $studentParentDocuments = StudentParentDocuments::where('student_hash_id', $studentParentDetail->student_hash_id)   
            ->first();
            $loanRequest = session('loan_request');
            $applicant_type = $loanRequest['relation'] ?? 'self'; // Default to 'self' if not set
            if($applicant_type == 'self' && $applicant_type) {  
                $name = $studentParentDetail->first_name ?? ""." ".$studentParentDetail->middle_name ?? ""." ".$studentParentDetail->last_name ?? "";
                $mobile_number = $studentParentDetail->mobile ?? "";
                $pan_number = $studentParentDocuments->student_pan_number ?? "";
                $email_id = $studentParentDetail->email ?? "";
                $current_residential_address = $studentParentAddress->student_comm_address_line1 ?? ""." ".$studentParentAddress->student_comm_address_line2 ?? "";
                $city = $studentParentAddress->student_comm_city ?? "";
                $state = $studentParentAddress->student_comm_state ?? "";
                $pincode = $studentParentAddress->student_comm_pincode ?? "";
                $marital_status = "Single";
                $no_of_dependents = 0;
                $monthly_family_income = $studentParentDetail->father_income ?? 0;
            }elseif($applicant_type == 'father' && $applicant_type) {
                $name = $studentParentDetail->father_first_name ?? ""." ".$studentParentDetail->father_middle_name ?? ""." ".$studentParentDetail->father_last_name ?? "";
                $mobile_number = $studentParentDetail->father_phone ?? "";
                $pan_number = $studentParentDocuments->parent_pan_number ?? "";
                $email_id = $studentParentDetail->father_email ?? "";
                $current_residential_address = $studentParentAddress->parent_comm_address_line1 ?? ""." ".$studentParentAddress->parent_comm_address_line2 ?? "";
                $city = $studentParentAddress->parent_comm_city ?? "";
                $state = $studentParentAddress->parent_comm_state ?? "";
                $pincode = $studentParentAddress->parent_comm_pincode ?? "";
                $marital_status = $studentParentDetail->father_marital_status ?? "";
                $no_of_dependents = $studentParentDetail->father_number_of_dependents ?? 1;
                $monthly_family_income = $studentParentDetail->father_income ?? 0;
            }elseif($applicant_type == 'mother' && $applicant_type) {
                $name = $studentParentDetail->mother_first_name ?? ""." ".$studentParentDetail->mother_middle_name ?? ""." ".$studentParentDetail->mother_last_name ?? "";
                $mobile_number = $studentParentDetail->mother_phone ?? "";
                $pan_number = $studentParentDocuments->parent_pan_number ?? "";
                $email_id = $studentParentDetail->mother_email ?? "";
                $current_residential_address = $studentParentAddress->parent_comm_address_line1 ?? ""." ".$studentParentAddress->parent_comm_address_line2 ?? "";
                $city = $studentParentAddress->parent_comm_city ?? "";
                $state = $studentParentAddress->parent_comm_state ?? "";
                $pincode = $studentParentAddress->parent_comm_pincode ?? "";
                $marital_status = $studentParentDetail->mother_marital_status ?? "";
                $no_of_dependents = $studentParentDetail->mother_number_of_dependents ?? 1;
                $monthly_family_income = $studentParentDetail->mother_income ?? 0;
            }elseif($applicant_type == 'guardian' && $applicant_type) {
                $name = $studentParentDetail->guardian_first_name ?? ""." ".$studentParentDetail->guardian_middle_name ?? ""." ".$studentParentDetail->guardian_last_name ?? "";
                $mobile_number = $studentParentDetail->guardian_phone ?? "";
                $pan_number = $studentParentDocuments->guardian_pan_number ?? "";
                $email_id = $studentParentDetail->guardian_email ?? "";
                $current_residential_address = $studentParentAddress->guardian_comm_address_line1 ?? ""." ".$studentParentAddress->guardian_comm_address_line2 ?? "";
                $city = $studentParentAddress->guardian_comm_city ?? "";
                $state = $studentParentAddress->guardian_comm_state ?? "";
                $pincode = $studentParentAddress->guardian_comm_pincode ?? "";
                $no_of_dependents = $studentParentDetail->guardian_number_of_dependents ?? 1;
                $marital_status = 'Married';
                $monthly_family_income = $studentParentDetail->guardian_income ?? 0;
            }else{
                return response()->json([
                    'message' => 'No active student parent details found for the given student hash ID.'
                ], 404);
            }
            $currentTime = Carbon::now()->toISOString();
            $serviceInstitutedetails = InstituteBasicDetails::where('fincap_merchant_id', $studentParentDetail->institute_id)->first();
            $institute_name = $serviceInstitutedetails->name ?? '';
            $institute_address_id = $serviceInstitutedetails->address_line_1 ?? ""." ".$serviceInstitutedetails->address_line_2 ?? ""." ".$serviceInstitutedetails->state ?? ""." ".$serviceInstitutedetails->city ?? ""." ".$serviceInstitutedetails->pincode ?? "";
            $enrollment_type = $studentParentDetail->enrollment_type ?? "";
            if($serviceInstitutedetails->type == 'Coaching') {
                $institute_type = "Coaching Classes";
            }elseif($serviceInstitutedetails->type == 'School') {
                $institute_type = "School (SC)"; 
            }elseif($serviceInstitutedetails->type == 'College' || $serviceInstitutedetails->type == 'University') {
                $institute_type = "College / University (CU)";        
            }else{
                $institute_type = "College / University (CU)";   
            }
            $data = [
                "signup_details" => [
                    "applicant_name" => $name ?? "",
                    "mobile_number" => $mobile_number,
                    "pan_number" => $pan_number,
                    "email_id" => $email_id,
                    "current_residential_address" => $current_residential_address,
                    "city" => $city,
                    "state" => $state,
                    "pincode" => $pincode,
                    "consent_timestamp" => $currentTime,
                ],
                "institute_details" => [
                    "institute_type" => $institute_type,
                    "institute_name" => $institute_name,
                    "institute_address_id" => $institute_address_id,
                    "institute_course_id" => $studentAcademicTransportDetails->course_subtype ?? "",
                    "loan_amount" => $loanRequest['loan_amount'] ?? 0,
                    "scheme_roi" => 0,
                    "scheme_subvention" => 12,
                    "scheme_pf" => 1.5,
                    "scheme_adv_emi_count" => 2,
                    "scheme_tenure" => $loanRequest['tenure'] ?? 12,
                ],
                "student_details" => [
                    "student_name" => $studentParentDetail->first_name ?? ""." ".$studentParentDetail->middle_name ?? ""." ".$studentParentDetail->last_name ?? "",
                    "student_dob" => $studentParentDetail->dob ?? "",
                    "enrollment_type" => $studentParentDetail->student_status ?? "",
                    "relationship_with_applicant" => $applicant_type
                ],
                "personal_employment_details" => [
                    "marital_status" => $marital_status,
                    "no_of_dependents" => $no_of_dependents,
                    "no_of_earning_family_members" => 1,
                    "monthly_family_income" => $monthly_family_income,
                    "no_of_emis_currently" => null,
                    "total_monthly_emi_amount" => null,
                    "employment_type" => null,
                    "employer_business_name" => null,
                    "employer_business_address" => null,
                    "current_work_experience_years" => 0,
                    "current_work_experience_months" => 0,
                    "total_work_experience_years" => 0,
                    "total_work_experience_months" => 0
                ]
            ];

            return view('instituteAdmin.UserConformation.UserConformation', compact('data'));
        }else{
            return response()->json([
                'message' => 'No active student parent details found for the given student hash ID.'
            ], 404);
        }
    }
    public function loanRequestApproval($loan_request_id)
    {
        $loanRequestDetails = LoanRequestDetails::where('loan_request_id', $loan_request_id)->first();
        if($loanRequestDetails){
            return view('instituteAdmin.LoanFiles.LoanWaitingApproval', compact('loanRequestDetails'));
        }else{
            return response()->json([
                'message' => 'No loan request details found for the given loan request ID.'
            ], 404);            
        }
    }
    public function preLoanFlyHiSanctionRequestDetails(Request $request)
    {
        // ✅ Validate Request
        $validator = Validator::make($request->all(), [
            'applicant_type' => 'nullable|in:self,father,mother,guardian',
            'no_of_earning_family_members' => 'nullable|integer|min:0',
            'monthly_family_income' => 'nullable|numeric|min:0',
            'no_of_emis_currently' => 'nullable|integer|min:0',
            'total_monthly_emi_amount' => 'nullable|numeric|min:0',
            'employment_type' => 'nullable|string|max:255',
            'employer_business_name' => 'nullable|string|max:255',
            'employer_business_address' => 'nullable|string|max:500',
            'current_work_experience_years' => 'nullable|integer|min:0',
            'total_work_experience_years' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        // ✅ Default Applicant Type
        $applicant_type = $request->input('applicant_type', 'father');

        $user_id = auth()->user()->id;

        // ✅ Fetch Data
        $studentParentDetail = StudentParentDetails::where('user_id', $user_id)
            ->where('status', 'active')
            ->first();

        if (!$studentParentDetail) {
            return response()->json([
                'message' => 'No active student parent details found.'
            ], 404);
        }

        $studentParentAddress = StudentParentAddress::where('student_hash_id', $studentParentDetail->student_hash_id)->first();
        $studentAcademicTransportDetails = StudentAcademicTransportDetails::where('student_hash_id', $studentParentDetail->student_hash_id)->first();
        $studentParentDocuments = StudentParentDocuments::where('student_hash_id', $studentParentDetail->student_hash_id)->first();

        // ✅ Initialize variables
        $mobile_number = "";
        $pan_number = "";
        $email_id = "";
        $current_residential_address = "";
        $city = "";
        $state = "";
        $pincode = "";
        $marital_status = "";
        $no_of_dependents = 0;
        $monthly_family_income = 0;

        // ✅ Applicant Type Logic
        if ($applicant_type == 'self') {

            $mobile_number = $studentParentDetail->mobile ?? "";
            $pan_number = optional($studentParentDocuments)->student_pan_number ?? "";
            $email_id = $studentParentDetail->email ?? "";

            $current_residential_address =
                (optional($studentParentAddress)->student_comm_address_line1 ?? '') . ' ' .
                (optional($studentParentAddress)->student_comm_address_line2 ?? '');

            $city = optional($studentParentAddress)->student_comm_city ?? "";
            $state = optional($studentParentAddress)->student_comm_state ?? "";
            $pincode = optional($studentParentAddress)->student_comm_pincode ?? "";

            $marital_status = "Single";
            $no_of_dependents = 0;
            $monthly_family_income = $studentParentDetail->father_income ?? 0;
            $applicant_type_flyhi = "SELF";
            $gender = $studentParentDetail->gender ?? null;

        } elseif ($applicant_type == 'father') {

            $mobile_number = $studentParentDetail->father_phone ?? "";
            $pan_number = optional($studentParentDocuments)->parent_pan_number ?? "";
            $email_id = $studentParentDetail->father_email ?? "";

            $current_residential_address =
                (optional($studentParentAddress)->parent_comm_address_line1 ?? '') . ' ' .
                (optional($studentParentAddress)->parent_comm_address_line2 ?? '');

            $city = optional($studentParentAddress)->parent_comm_city ?? "";
            $state = optional($studentParentAddress)->parent_comm_state ?? "";
            $pincode = optional($studentParentAddress)->parent_comm_pincode ?? "";

            $marital_status = $studentParentDetail->father_marital_status ?? "";
            $no_of_dependents = $studentParentDetail->father_number_of_dependents ?? 1;
            $monthly_family_income = $studentParentDetail->father_income ?? 0;
            $gender = "Male";
            if($studentParentDetail->gender == "Male"){
              $applicant_type_flyhi = "SON";
            }else{
              $applicant_type_flyhi = "DAUGHTER";  
            }

        } elseif ($applicant_type == 'mother') {

            $mobile_number = $studentParentDetail->mother_phone ?? "";
            $pan_number = optional($studentParentDocuments)->parent_pan_number ?? "";
            $email_id = $studentParentDetail->mother_email ?? "";

            $current_residential_address =
                (optional($studentParentAddress)->parent_comm_address_line1 ?? '') . ' ' .
                (optional($studentParentAddress)->parent_comm_address_line2 ?? '');

            $city = optional($studentParentAddress)->parent_comm_city ?? "";
            $state = optional($studentParentAddress)->parent_comm_state ?? "";
            $pincode = optional($studentParentAddress)->parent_comm_pincode ?? "";

            $marital_status = $studentParentDetail->mother_marital_status ?? "";
            $no_of_dependents = $studentParentDetail->mother_number_of_dependents ?? 1;
            $monthly_family_income = $studentParentDetail->mother_income ?? 0;
            $gender = "Female";
            if($studentParentDetail->gender == "Male"){
              $applicant_type_flyhi = "SON";
            }else{
              $applicant_type_flyhi = "DAUGHTER";  
            }

        } elseif ($applicant_type == 'guardian') {

            $mobile_number = $studentParentDetail->guardian_phone ?? "";
            $pan_number = optional($studentParentDocuments)->guardian_pan_number ?? "";
            $email_id = $studentParentDetail->guardian_email ?? "";

            $current_residential_address =
                (optional($studentParentAddress)->guardian_comm_address_line1 ?? '') . ' ' .
                (optional($studentParentAddress)->guardian_comm_address_line2 ?? '');

            $city = optional($studentParentAddress)->guardian_comm_city ?? "";
            $state = optional($studentParentAddress)->guardian_comm_state ?? "";
            $pincode = optional($studentParentAddress)->guardian_comm_pincode ?? "";

            $marital_status = "Married";
            $no_of_dependents = $studentParentDetail->guardian_number_of_dependents ?? 1;
            $monthly_family_income = $studentParentDetail->guardian_income ?? 0;
            $gender = $studentParentDetail->guardian_gender;
            $applicant_type_flyhi = "OTHER";
        } else {
            return response()->json([
                'message' => 'Invalid applicant type.'
            ], 422);
        }

        // ✅ Institute Details
        $serviceInstitutedetails = InstituteBasicDetails::where('fincap_merchant_id', $studentParentDetail->institute_id)->first();

        $institute_name = optional($serviceInstitutedetails)->name ?? '';

        $institute_address_id =
            (optional($serviceInstitutedetails)->address_line_1 ?? '') . ' ' .
            (optional($serviceInstitutedetails)->address_line_2 ?? '') . ' ' .
            (optional($serviceInstitutedetails)->city ?? '') . ' ' .
            (optional($serviceInstitutedetails)->state ?? '') . ' ' .
            (optional($serviceInstitutedetails)->pincode ?? '');

        if (optional($serviceInstitutedetails)->type == 'Coaching') {
            $institute_type = "Coaching Classes";
        } elseif (optional($serviceInstitutedetails)->type == 'School') {
            $institute_type = "School (SC)";
        } else {
            $institute_type = "College / University (CU)";
        }

        // ✅ Generate Loan ID
        $loanRequestId = 'LR' . strtoupper(uniqid());

        // ✅ Final Data
        $loanRequest = session('loan_request');
        $data = [
            'institute_id' => $studentParentDetail->institute_id,
            'user_id' => $studentParentDetail->user_id,
            'student_hash_id' => $studentParentDetail->student_hash_id,
            'loan_request_id' => $loanRequestId,

            // "mobile_number" => $mobile_number,
            "mobile_number" => 9915728338,
            // "pan_number" => $pan_number,
            "pan_number" => "FMMPB7800J",
            // "email_id" => $email_id,
            "email_id" => "tarun.entritt@gmail.com",
            "current_residential_address" => trim($current_residential_address),
            "city" => $city,
            "state" => $state,
            "pincode" => $pincode,
            "consent_timestamp" => Carbon::now()->format('Y-m-d H:i:s'),
            "marital_status" => $marital_status,
            "applicant_gender" => $gender,
            
            "institute_type" => $institute_type,
            "institute_name" => $institute_name,
            "institute_address" => $institute_address_id,
            "institute_course" => optional($studentAcademicTransportDetails)->course_subtype ?? "",
            // "institute_type" => "Coaching Class",
            // "institute_name" => "ALLEN CAREER INSTITUTE PRIVATE LIMITED",
            // "institute_address" => "1st Floor, Laxmi Society, Sambhaji Nagar, Sahar Road, Opp. Vijay Nagar Society D-Mart, Andheri East, Mumbai",
            // "institute_course" => "Higher Secondary Combo English Sanskrit",

            "loan_amount" => $loanRequest['loan_amount'] ?? 0,
            "scheme_roi" => 0,
            "scheme_subvention" => 12,
            "scheme_pf" => 1,
            "scheme_adv_emi_count" => 2,
            "scheme_tenure" => $loanRequest['tenure'] ?? 12,

            "student_name" =>
                ($studentParentDetail->first_name ?? '') . ' ' .
                ($studentParentDetail->middle_name ?? '') . ' ' .
                ($studentParentDetail->last_name ?? ''),

            "student_dob" => $studentParentDetail->dob ?? "",
            "enrollment_type" => $studentParentDetail->student_status ?? "",

            "relationship_with_applicant" => $applicant_type_flyhi,
            "marital_status" => $marital_status,
            "no_of_dependents" => $no_of_dependents,

            "no_of_earning_family_members" => $request->input('no_of_earning_family_members', 1),
            "monthly_family_income" => $request->input('monthly_family_income', $monthly_family_income),
            "no_of_emis_currently" => $request->input('no_of_emis_currently', 0),
            "total_monthly_emi_amount" => $request->input('total_monthly_emi_amount', 0),

            "employment_type" => $request->input('employment_type'),
            "employer_business_name" => $request->input('employer_business_name'),
            "employer_business_address" => $request->input('employer_business_address'),

            "current_work_experience_years" => $request->input('current_work_experience_years', 0),
            "current_work_experience_months" => ($request->input('current_work_experience_months', 0)) / 12,

            "total_work_experience_years" => $request->input('total_work_experience_years', 0),
            "total_work_experience_months" => ($request->input('total_work_experience_months', 0)) / 12,
        ];

        // ✅ Save Data
        LoanRequestDetails::create($data);
        $loan_request_id = $loanRequestId;
        // ✅ Return View
        return redirect()->route('loan.journey.bank.details', [
            'loan_request_id' => $loan_request_id
        ]);
    }
    public function redirectToAjivikaLoanJourney($loan_request_id)
    {
        $loanRequestDetails = LoanRequestDetails::where('loan_request_id', $loan_request_id)->first();
        if($loanRequestDetails){
            return view('instituteAdmin.LoanFiles.RedirectPageAvkJourney', compact('loanRequestDetails'));
        }else{
            return response()->json([
                'message' => 'No loan request details found for the given loan request ID.'
            ], 404);            
        }
    }
    public function preLoanSanctionRequestDetails(Request $request)
    {
        // ✅ Validate Request
        $validator = Validator::make($request->all(), [
            'applicant_type' => 'nullable|in:self,father,mother,guardian',
            'no_of_earning_family_members' => 'nullable|integer|min:0',
            'monthly_family_income' => 'nullable|numeric|min:0',
            'no_of_emis_currently' => 'nullable|integer|min:0',
            'total_monthly_emi_amount' => 'nullable|numeric|min:0',
            'employment_type' => 'nullable|string|max:255',
            'employer_business_name' => 'nullable|string|max:255',
            'employer_business_address' => 'nullable|string|max:500',
            'current_work_experience_years' => 'nullable|integer|min:0',
            'total_work_experience_years' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        // ✅ Default Applicant Type
        $applicant_type = $request->input('applicant_type', 'father');

        $user_id = auth()->user()->id;

        // ✅ Fetch Data
        $studentParentDetail = StudentParentDetails::where('user_id', $user_id)
            ->where('status', 'active')
            ->first();

        if (!$studentParentDetail) {
            return response()->json([
                'message' => 'No active student parent details found.'
            ], 404);
        }

        $studentParentAddress = StudentParentAddress::where('student_hash_id', $studentParentDetail->student_hash_id)->first();
        $studentAcademicTransportDetails = StudentAcademicTransportDetails::where('student_hash_id', $studentParentDetail->student_hash_id)->first();
        $studentParentDocuments = StudentParentDocuments::where('student_hash_id', $studentParentDetail->student_hash_id)->first();

        // ✅ Initialize variables
        $mobile_number = "";
        $pan_number = "";
        $email_id = "";
        $current_residential_address = "";
        $city = "";
        $state = "";
        $pincode = "";
        $marital_status = "";
        $no_of_dependents = 0;
        $monthly_family_income = 0;

        // ✅ Applicant Type Logic
        if ($applicant_type == 'self') {
            $applicant_first_name = $studentParentDetail->first_name?? "";
            $applicant_middle_name = $studentParentDetail->middle_name?? "";
            $applicant_last_name = $studentParentDetail->last_name?? "";
            $dob = $studentParentDetail->dob?? "";
            $mobile_number = $studentParentDetail->mobile ?? "";
            $pan_number = optional($studentParentDocuments)->student_pan_number ?? "";
            $email_id = $studentParentDetail->email ?? "";

            $current_residential_address =
                (optional($studentParentAddress)->student_comm_address_line1 ?? '') . ' ' .
                (optional($studentParentAddress)->student_comm_address_line2 ?? '');

            $city = optional($studentParentAddress)->student_comm_city ?? "";
            $state = optional($studentParentAddress)->student_comm_state ?? "";
            $pincode = optional($studentParentAddress)->student_comm_pincode ?? "";

            $marital_status = "Single";
            $no_of_dependents = 0;
            $monthly_family_income = $studentParentDetail->father_income ?? 0;
            $applicant_type_flyhi = "SELF";
            $gender = $studentParentDetail->gender ?? null;

        } elseif ($applicant_type == 'father') {
            $applicant_first_name = $studentParentDetail->father_first_name?? "";
            $applicant_middle_name = $studentParentDetail->father_middle_name?? "";
            $applicant_last_name = $studentParentDetail->father_last_name?? "";
            $dob = $studentParentDetail->father_dob?? "";
            $mobile_number = $studentParentDetail->father_phone ?? "";
            $pan_number = optional($studentParentDocuments)->parent_pan_number ?? "";
            $email_id = $studentParentDetail->father_email ?? "";

            $current_residential_address =
                (optional($studentParentAddress)->parent_comm_address_line1 ?? '') . ' ' .
                (optional($studentParentAddress)->parent_comm_address_line2 ?? '');

            $city = optional($studentParentAddress)->parent_comm_city ?? "";
            $state = optional($studentParentAddress)->parent_comm_state ?? "";
            $pincode = optional($studentParentAddress)->parent_comm_pincode ?? "";

            $marital_status = $studentParentDetail->father_marital_status ?? "";
            $no_of_dependents = $studentParentDetail->father_number_of_dependents ?? 1;
            $monthly_family_income = $studentParentDetail->father_income ?? 0;
            $gender = "0";
            if($studentParentDetail->gender == "Male"){
              $applicant_type_flyhi = "SON";
            }else{
              $applicant_type_flyhi = "DAUGHTER";  
            }

        } elseif ($applicant_type == 'mother') {
            $applicant_first_name = $studentParentDetail->mother_first_name?? "";
            $applicant_middle_name = $studentParentDetail->mother_middle_name?? "";
            $applicant_last_name = $studentParentDetail->mother_last_name?? "";
            $dob = $studentParentDetail->mother_dob?? "";
            $mobile_number = $studentParentDetail->mother_phone ?? "";
            $pan_number = optional($studentParentDocuments)->parent_pan_number ?? "";
            $email_id = $studentParentDetail->mother_email ?? "";

            $current_residential_address =
                (optional($studentParentAddress)->parent_comm_address_line1 ?? '') . ' ' .
                (optional($studentParentAddress)->parent_comm_address_line2 ?? '');

            $city = optional($studentParentAddress)->parent_comm_city ?? "";
            $state = optional($studentParentAddress)->parent_comm_state ?? "";
            $pincode = optional($studentParentAddress)->parent_comm_pincode ?? "";

            $marital_status = $studentParentDetail->mother_marital_status ?? "";
            $no_of_dependents = $studentParentDetail->mother_number_of_dependents ?? 1;
            $monthly_family_income = $studentParentDetail->mother_income ?? 0;
            $gender = "1";
            if($studentParentDetail->gender == "Male"){
              $applicant_type_flyhi = "SON";
            }else{
              $applicant_type_flyhi = "DAUGHTER";  
            }

        } elseif ($applicant_type == 'guardian') {
            $applicant_first_name = $studentParentDetail->guardian_first_name?? "";
            $applicant_middle_name = $studentParentDetail->guardian_middle_name?? "";
            $applicant_last_name = $studentParentDetail->guardian_last_name?? "";
            $dob = $studentParentDetail->guardian_dob ?? "";
            $mobile_number = $studentParentDetail->guardian_phone ?? "";
            $pan_number = optional($studentParentDocuments)->guardian_pan_number ?? "";
            $email_id = $studentParentDetail->guardian_email ?? "";

            $current_residential_address =
                (optional($studentParentAddress)->guardian_comm_address_line1 ?? '') . ' ' .
                (optional($studentParentAddress)->guardian_comm_address_line2 ?? '');

            $city = optional($studentParentAddress)->guardian_comm_city ?? "";
            $state = optional($studentParentAddress)->guardian_comm_state ?? "";
            $pincode = optional($studentParentAddress)->guardian_comm_pincode ?? "";
            $marital_status = "Married";
            $no_of_dependents = $studentParentDetail->guardian_number_of_dependents ?? 1;
            $monthly_family_income = $studentParentDetail->guardian_income ?? 0;
            $gender = $studentParentDetail->guardian_gender ?? "";
            if($gender == 'Male'){
                $gender = "0";
            }elseif($gender == 'Female'){
                $gender = "1";
            }else{
                $gender = "3";
            }
            $applicant_type_flyhi = "OTHER";
        } else {
            return response()->json([
                'message' => 'Invalid applicant type.'
            ], 422);
        }

        // ✅ Institute Details
        $serviceInstitutedetails = InstituteBasicDetails::where('fincap_merchant_id', $studentParentDetail->institute_id)->first();

        $institute_name = optional($serviceInstitutedetails)->name ?? '';

        $institute_address_id =
            (optional($serviceInstitutedetails)->address_line_1 ?? '') . ' ' .
            (optional($serviceInstitutedetails)->address_line_2 ?? '') . ' ' .
            (optional($serviceInstitutedetails)->city ?? '') . ' ' .
            (optional($serviceInstitutedetails)->state ?? '') . ' ' .
            (optional($serviceInstitutedetails)->pincode ?? '');

        if (optional($serviceInstitutedetails)->type == 'Coaching') {
            $institute_type = "Coaching Classes";
        } elseif (optional($serviceInstitutedetails)->type == 'School') {
            $institute_type = "School (SC)";
        } else {
            $institute_type = "College / University (CU)";
        }

        // ✅ Generate Loan ID
        $loanRequestId = 'LR' . strtoupper(uniqid());

        // ✅ Final Data
        $loanRequest = session('loan_request');
        $data = [
            "fincap_partner_id" => "FINCAPM4IZ5P0WJ1",
            "title" => 1,
            "first_name" => $applicant_first_name,
            "middle_name" => $applicant_middle_name ?? "",
            "last_name" => $applicant_last_name,
            "date_of_birth" => $dob,
            "email" => $email_id,
            "mobile_number" => $mobile_number,
            "gender" => $gender,
            "employment_status" => 0,
            "profession_id" => 2,
            "student_name" =>
                ($studentParentDetail->first_name ?? '') . ' ' .
                ($studentParentDetail->middle_name ?? '') . ' ' .
                ($studentParentDetail->last_name ?? ''),
        
            "communication_address" => $current_residential_address,
            "city" => $city,
            "state" => $state,
            "pincode" => $pincode,
            "registration_number" => $studentParentDetail->registration_number,
        
            "applicant_type" => "Parents",
        
            "father_name" =>
                ($studentParentDetail->father_first_name ?? '') . ' ' .
                ($studentParentDetail->father_middle_name ?? '') . ' ' .
                ($studentParentDetail->father_last_name ?? ''),
        
            "father_occupation" => "Business",
        
            "mother_name" =>
                ($studentParentDetail->mother_first_name ?? '') . ' ' .
                ($studentParentDetail->mother_middle_name ?? '') . ' ' .
                ($studentParentDetail->mother_last_name ?? ''),
        
            "parents_mobile" => $studentParentDetail->father_phone ?? "",
            "nationality" => "Indian",
        
            "institute_type" => optional($serviceInstitutedetails)->type,
            "merchant_id" => "FMKXIFDCM1G4",
            "institute_name" => $institute_name,
            "merchant_sub_category_id" => 5,
            'loan_request_id' => $loanRequestId,
            "course_type" => optional($studentAcademicTransportDetails)->course_type ?? "",
            "course_name" => optional($studentAcademicTransportDetails)->course_subtype ?? "",
        
            "fee_duration" => "0",
            "course_duration" => "0",
            "course_duration_fee" => 0,
        
            "total_payable_fee" => $loanRequest['loan_amount'] ?? 0,
            "total_fee" => 0
        ];
        
        $url = "https://loan-journey.campusdunia.co.in/api/erp/onboard/complete/applicant-details";
        
        $response = Http::timeout(60)
            ->acceptJson()
            ->post($url, $data);
        if ($response->successful()) {
        
            $responseData = $response->json();
            $loanRequestdata = [
            'institute_id' => $studentParentDetail->institute_id,
            'user_id' => $studentParentDetail->user_id,
            'student_hash_id' => $studentParentDetail->student_hash_id,
            'loan_request_id' => $loanRequestId,

            // "mobile_number" => $mobile_number,
            "mobile_number" => 9915728338,
            // "pan_number" => $pan_number,
            "pan_number" => "FMMPB7800J",
            // "email_id" => $email_id,
            "email_id" => "tarun.entritt@gmail.com",
            "current_residential_address" => trim($current_residential_address),
            "city" => $city,
            "state" => $state,
            "pincode" => $pincode,
            "consent_timestamp" => Carbon::now()->format('Y-m-d H:i:s'),
            "marital_status" => $marital_status,
            "applicant_gender" => $gender,
            
            "institute_type" => $institute_type,
            "institute_name" => $institute_name,
            "institute_address" => $institute_address_id,
            "institute_course" => optional($studentAcademicTransportDetails)->course_subtype ?? "",
            // "institute_type" => "Coaching Class",
            // "institute_name" => "ALLEN CAREER INSTITUTE PRIVATE LIMITED",
            // "institute_address" => "1st Floor, Laxmi Society, Sambhaji Nagar, Sahar Road, Opp. Vijay Nagar Society D-Mart, Andheri East, Mumbai",
            // "institute_course" => "Higher Secondary Combo English Sanskrit",

            "loan_amount" => $loanRequest['loan_amount'] ?? 0,
            "scheme_roi" => 0,
            "scheme_subvention" => 12,
            "scheme_pf" => 1,
            "scheme_adv_emi_count" => 2,
            "scheme_tenure" => $loanRequest['tenure'] ?? 12,
            "selected_fee_data" => $loanRequest['selected_fee_data'],
            "student_name" =>
                ($studentParentDetail->first_name ?? '') . ' ' .
                ($studentParentDetail->middle_name ?? '') . ' ' .
                ($studentParentDetail->last_name ?? ''),

            "student_dob" => $studentParentDetail->dob ?? "",
            "enrollment_type" => $studentParentDetail->student_status ?? "",

            "relationship_with_applicant" => $applicant_type_flyhi,
            "marital_status" => $marital_status,
            "no_of_dependents" => $no_of_dependents,

            "no_of_earning_family_members" => $request->input('no_of_earning_family_members', 1),
            "monthly_family_income" => $request->input('monthly_family_income', $monthly_family_income),
            "no_of_emis_currently" => $request->input('no_of_emis_currently', 0),
            "total_monthly_emi_amount" => $request->input('total_monthly_emi_amount', 0),

            "employment_type" => $request->input('employment_type'),
            "employer_business_name" => $request->input('employer_business_name'),
            "employer_business_address" => $request->input('employer_business_address'),

            "current_work_experience_years" => $request->input('current_work_experience_years', 0),
            "current_work_experience_months" => ($request->input('current_work_experience_months', 0)) / 12,

            "total_work_experience_years" => $request->input('total_work_experience_years', 0),
            "total_work_experience_months" => ($request->input('total_work_experience_months', 0)) / 12,
        ];

        // ✅ Save Data
        LoanRequestDetails::create($loanRequestdata);
        $loan_request_id = $loanRequestId;
                // ✅ Return View
        return redirect()->route('ajivika.loan.redirection.page', [
            'loan_request_id' => $loan_request_id
        ]);
            // return response()->json([
            //     'status' => true,
            //     'message' => 'Data pushed successfully',
            //     'response' => $responseData
            // ]);
        
        } else {
        
            return response()->json([
                'status' => false,
                'message' => 'Failed to push data',
                'response' => $response->body()
            ], $response->status());
        }

    }
    public function showBankdetails($loan_request_id){
        $loanRequestDetails = LoanRequestDetails::where('loan_request_id', $loan_request_id)->first();
        if($loanRequestDetails){
            return view('instituteAdmin.GetBankStatementLoan.GetBankStatement', compact('loan_request_id'));
        }else{
            return response()->json([
                'message' => 'No loan request details found for the given loan request ID.'
            ], 404);            
        }   

    }
    public function postBankdetails(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'loan_request_id' => 'required|string',
            'bank_name' => 'required|string|max:255',
            'bank_statement_password' => 'nullable|string|max:255',
            'bank_statement' => 'required|file|mimes:pdf|max:5120', // 5MB max
        ]);

        $loan_request_id = $request->input('loan_request_id');

        // ✅ Find record
        $loanRequestDetails = LoanRequestDetails::where('loan_request_id', $loan_request_id)->first();
        if (!$loanRequestDetails) {
            return response()->json([
                'message' => 'No loan request details found.'
            ], 404);
        }

        // ✅ Handle File Upload
        $fileBase64 = null;

        if ($request->hasFile('bank_statement')) {
            $file = $request->file('bank_statement');

            // Get file content
            $fileContent = file_get_contents($file->getRealPath());

            // Encode to base64
            $fileBase64 = base64_encode($fileContent);

            // Optional: include MIME type (recommended)
            $mimeType = $file->getMimeType();

            // $fileBase64 = 'data:' . $mimeType . ';base64,' . $fileBase64;
        }

        // // ✅ Update JSON (merge with existing data)
        // $existingData = $loanRequestDetails->request_details ?? [];

        $loanRequestDetails->update([
                'bank_name' => $request->bank_name,
                'bank_statement_b64' => $fileBase64,
                'period_months' => $request->month_of_statement ?? 6,
                'statement_password' => $request->bank_statement_password ?? null,
            ]);

        // ✅ Redirect / Next Step
        return redirect()->route('loan.journey.selfie', [
            'loan_request_id' => $loan_request_id
        ]);
    }
    public function showSelfie($loan_request_id){
        $loanRequestDetails = LoanRequestDetails::where('loan_request_id', $loan_request_id)->first();
        if($loanRequestDetails){
            return view('instituteAdmin.GetSelfieForLoan.GetSelfie', compact('loan_request_id'));
        }else{
            return response()->json([
                'message' => 'No loan request details found for the given loan request ID.'
            ], 404);            
        }
    }
    public function postSelfie(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'loan_request_id' => 'required',
            'selfie_image' => 'required|string', // base64 or file (adjust if needed)
        ]);

        $loan_request_id = $request->loan_request_id;

        // ✅ Get record
        $loanRequestDetails = LoanRequestDetails::where('loan_request_id', $loan_request_id)->first();

        if (!$loanRequestDetails) {
            return response()->json([
                'message' => 'Loan request not found'
            ], 404);
        }


        // ✅ Save
        $loanRequestDetails->update([
            'customer_selfie_b64' => $request->selfie_image
        ]);
        // ✅ Prepare Payload
        $payload = [
            "signup_details" => [
                "mobile_number" => $loanRequestDetails['mobile_number'],
                "pan_number" => $loanRequestDetails['pan_number'],
                "email_id" => $loanRequestDetails['email_id'],
                "current_residential_address" => $loanRequestDetails['current_residential_address'],
                "city" => $loanRequestDetails['city'],
                "state" => $loanRequestDetails['state'],
                "pincode" => (string) $loanRequestDetails['pincode'],
                "privacy_policy_accepted" => true,
                "mitc_accepted" => true,
                "gender" => $loanRequestDetails['gender']
            ],
        
            "institute_details" => [
                "institute_type" => $loanRequestDetails['institute_type'],
                "institute_name" => $loanRequestDetails['institute_name'],
                "course" => $loanRequestDetails['institute_course'],
                "loan_amount" => (int) $loanRequestDetails['loan_amount'],
                "institute_address" => $loanRequestDetails['institute_address'],
                "scheme_tenure" => (int) $loanRequestDetails['scheme_tenure'],
                "scheme_roi" => (int) $loanRequestDetails['scheme_roi'],
                "scheme_subvention" => (int) $loanRequestDetails['scheme_subvention'],
                "scheme_pf" => (int) $loanRequestDetails['scheme_pf'],
                "scheme_adv_emi_count" => (int) $loanRequestDetails['scheme_adv_emi_count']
            ],
        
            "student_details" => [
                "student_name" => $loanRequestDetails['student_name'],
                "student_dob" => $loanRequestDetails['student_dob'], // YYYY-MM-DD
                "enrollment_type" => $loanRequestDetails['enrollment_type'],
                "relationship_with_applicant" => $loanRequestDetails['relationship_with_applicant']
            ],
        
            "personal_employment_details" => [
                "marital_status" => "Single",
                "no_of_dependents" => (int) $loanRequestDetails['no_of_dependents'],
                "no_of_earning_family_members" => (int) $loanRequestDetails['no_of_earning_family_members'],
                "monthly_family_income" => (int) $loanRequestDetails['monthly_family_income'],
                "no_of_emis_currently" => (int) $loanRequestDetails['no_of_emis_currently'],
                "total_monthly_emi_amount" => (int) $loanRequestDetails['total_monthly_emi_amount'],
                "employment_type" => $loanRequestDetails['employment_type'],
                "employer_business_name" => $loanRequestDetails['employer_business_name'],
                "current_work_experience_years" => (int) $loanRequestDetails['current_work_experience_years'],
                "current_work_experience_months" => (int) $loanRequestDetails['current_work_experience_months'],
                "total_work_experience_years" => (int) $loanRequestDetails['total_work_experience_years'],
                "total_work_experience_months" => (int) $loanRequestDetails['total_work_experience_months']
            ],
        
            "income_analysis" => [
                "statement_password" => $loanRequestDetails['statement_password'] ?? "",
                "period_months" => $loanRequestDetails['period_months'] ?? 6, // IMPORTANT: string
                "bank_statement_b64" => $loanRequestDetails['bank_statement_b64']
            ],
        
            "facial_verification" => [
                "customer_selfie_b64" => $loanRequestDetails['customer_selfie_b64']
            ]
        ];

        // return response()->json([
        //     'status' => true,
        //     'link' => "/loan/journey/waiting-for-approval/".$loan_request_id,
        //     'message' => 'Lead created successfully',
        //     'data' => $payload
        // ]);
        // log::info($payload);
        //try{
            $response = Http::timeout(300) // ✅ 5 minutes
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'x-api-key' => "1c8f0558-fd41-3ef9-adba-a460a999621c",
                    'x-partner-id' => "CAMPUS_DUNIYA_001",
                ])
                ->post("https://uat.customerjourney.flyhifinance.com/api/v1/create-lead", $payload);
                // ->withHeaders([
                //     'Content-Type' => 'application/json',
                //     'x-api-key' => env('FLYHI_API_KEY'),
                //     'x-partner-id' => env('FLYHI_PARTNER_ID'),
                // ])
                // ->post(env('FLYHI_URL'), $payload);
            log::info($response);
            if ($response->successful()) {
    
                $responseData = $response->json();
                log::info($responseData);
    
                // Save API response
                if ($loanRequestDetails) {
                
                    // Map API status to loan status
                    $statusMap = [
                        'LEAD_CREATED'    => 'under_review',
                        'BRE_IN_PROGRESS' => 'under_review',
                        'BRE_REJECTED'    => 'rejected',
                    ];
                
                    $loanStatus = $statusMap[$responseData['status']] ?? 'reinitiate';
                
                    $loanRequestDetails->update([
                        'lead_id' => $responseData['lead_id'] ?? null,
                        'loan_request_api_response' => json_encode($responseData), // IMPORTANT FIX
                        'loan_status' => $loanStatus,
                    ]);
                }
    
                return response()->json([
                    'status' => true,
                    'link' => "/loan/journey/waiting-for-approval/".$loan_request_id,
                    'message' => 'Lead created successfully',
                    'data' => $responseData
                ]);
            }
    
            return response()->json([
                'status' => false,
                'message' => 'API failed',
                'error' => $response->body()
            ], 500);
        
            // } catch (\Exception $e) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'Exception occurred',
            //         'error' => $e->getMessage()
            //     ], 500);
            // }
        log::info($response->body());
        return response()->json([
            'status' => false,
            'message' => 'API failed',
            'error' => $response->body()
        ], 500);
    }
  
}
