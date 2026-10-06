<?php
namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\ActiveDynamicLink;
use App\Models\CommonCustomFees;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Exception;
use App\Models\Lead;
use App\Models\StudentAdmissionProcess;

class ActiveDynamicLinkController extends Controller
{
    public function showAdmissionOtp(Request $request)
    {
        $verificationMobile = null;
        if ($request->filled('lead_id')) {
            abort_unless(URL::hasValidSignature($request), 403, 'Invalid or expired onboarding link.');

            $lead = Lead::where(function ($query) use ($request) {
                $query->where('lead_id', $request->lead_id)
                    ->orWhere('id', $request->lead_id);
            })->firstOrFail();

            if ($request->input('purpose') === 'journey') {
                session([
                    'candidate_journey_lead_id' => $lead->id,
                    'candidate_journey_otp_verified' => false,
                    'candidate_journey_redirect' => route('candidate.view', ['leadId' => $lead->lead_id]),
                ]);
            } else {
                session([
                    'onboarding_lead_id' => $lead->id,
                    'onboarding_institute_id' => $lead->institute_id,
                    'onboarding_otp_verified' => false,
                    'onboarding_redirect' => route('external.form'),
                ]);
            }

            $verificationMobile = preg_replace('/\D+/', '', (string) $lead->phone_no);
        }

        if ($request->filled('candidate_lead_id')) {
            $candidateLead = Lead::where('lead_type', 'Interview')
                ->where(function ($query) use ($request) {
                    $query->where('lead_id', $request->candidate_lead_id)
                        ->orWhere('id', $request->candidate_lead_id);
                })
                ->first();

            if ($candidateLead) {
                session([
                    'candidate_journey_lead_id' => $candidateLead->id,
                    'candidate_journey_institute_id' => $candidateLead->institute_id,
                    'candidate_journey_otp_verified' => false,
                ]);
                $verificationMobile = preg_replace('/\D+/', '', (string) $candidateLead->phone_no);
            }
        }

        return view('instituteAdmin.CreateDynamicLink.studentVerificationSet', compact('verificationMobile'));
    }

    // Generate activation link
    public function generate(Request $request)
    {
        $request->validate([
            'link_type' => 'required|string',
            'expiry_type' => 'required|in:minute,day,month',
            'expiry_value' => 'required|integer|min:1|max:365',
        ]);

        // Generate token
        $rawToken = Str::random(40);
        $hashedToken = hash('sha256', $rawToken);

        // Calculate expiry time
        $expiresAt = match ($request->expiry_type) {
            'minute' => now()->addMinutes($request->expiry_value),
            'day' => now()->addDays($request->expiry_value),
            'month' => now()->addMonths($request->expiry_value),
        };

        // Store
        ActiveDynamicLink::create([
            'link_type' => $request->link_type,
            'token' => $hashedToken,
            'expires_at' => $expiresAt,
            'is_used' => false,
        ]);

        return redirect()->back()->with([
            'activation_url' => route('activate.link', $rawToken),
            'expires_at' => $expiresAt->toDateTimeString(),
            'link_type' => $request->link_type,
            'expiry_type' => $request->expiry_type,
            'expiry_value' => $request->expiry_value,
        ]);
    }

    // Activate link
    public function activate($token)
    {
        $hashedToken = hash('sha256', $token);
        $link = ActiveDynamicLink::where('token', $hashedToken)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();
        if (!$link) {
            abort(403, 'Link expired or invalid');
        }
        $mobile = session()->get('lead_mobile_number');
        $lead = Lead::where('phone_no', $mobile)->first();
        if ($lead) {
            $studentlead = StudentAdmissionProcess::with([
                'admissionConfig',
                'counsellingSlot',
                'entranceTestSlot'
            ])
                ->where('lead_id', $lead->id)
                ->firstOrFail();

            $data = [
                'lead' => $lead,
                'studentlead' => $studentlead
            ];
            $form_fee = CommonCustomFees::where('custom_reference_id', $studentlead->admissionConfig->admission_form_fee_amount)->first();
            if ($form_fee) {
                $admission_fee = $form_fee->custom_fee_value;
            } else {
                $admission_fee = 0.00;
            }
            $test_fee = CommonCustomFees::where('custom_reference_id', $studentlead->admissionConfig->entrance_test_fee_amount)->first();
            if ($test_fee) {
                $test_fee = $test_fee->custom_fee_value;
            } else {
                $test_fee = 5000.00;
            }
            // Check authorization (if needed)
            return view('instituteAdmin.AddLead.StudentView', compact('data', 'admission_fee', 'test_fee'));
        } else {
            return redirect('/admission/send-otp');
        }

    }
    public function student_admission_send_otp(Request $request)
    {
        $attr = $request->all();
        $hasLeadContext = session()->has('onboarding_lead_id') || session()->has('candidate_journey_lead_id');
        $validator = Validator::make($attr, [
            'mobile_number' => $hasLeadContext ? 'nullable|digits:10' : 'required|digits:10',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            //return $this->ValidationError($validator->messages(), 200);
            return Redirect::back()->withErrors($validator);
        }
        //SMS Services

        try {

            $mobile = $attr['mobile_number'] ?? null;

            if (session()->has('onboarding_lead_id')) {
                $lead = Lead::whereKey(session('onboarding_lead_id'))->first();

                $mobile = $lead ? preg_replace('/\D+/', '', (string) $lead->phone_no) : null;
                if (!$lead || !$mobile) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The mobile number does not match this onboarding link.',
                    ], 422);
                }
            }

            if (session()->has('candidate_journey_lead_id')) {
                $lead = Lead::whereKey(session('candidate_journey_lead_id'))
                    ->where('lead_type', 'Interview')
                    ->first();

                if (!$lead || preg_replace('/\D+/', '', $lead->phone_no) !== $mobile) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The mobile number does not match this candidate journey.',
                    ], 422);
                }

                \Log::info('Candidate Journey OTP requested', [
                    'lead_id' => $lead->id,
                    'lead_identifier' => $lead->lead_id,
                ]);
            }
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.msg91.com/api/v5/otp?template_id=63aa9abe2b190d6d07363e4a&mobile=91$mobile&authkey=196733AcZiJ1EG25e84a365P1",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/JSON"
                ],
            ]);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kindly retry after some time',
                ]);
            } else {
                $response = json_decode($response, true);

                if (isset($response['type']) && $response['type'] == 'success') {
                    session(['lead_mobile_number' => $mobile]);

                    return response()->json([
                        'status' => 200,
                        'success' => true,
                        'message' => 'OTP sent successfully',
                        'redirect' => url('/applicant-verify-otp')
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kindly retry after some time',
                    ]);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "errorCode" => "880",
                "description" => "Note : Initaite Status check after Some time"
            ]);
        }
        //END SMS Services
    }
    function student_admission_verify_otp(Request $request)
    {
        $attr = $request->all();
        $validator = Validator::make($attr, [
            'otp' => 'required|digits:4',
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->ValidationError($validator->messages(), 200);
        }
        try {
            $mobile = $request->session()->get('lead_mobile_number');
            $otp = $attr['otp'];
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.msg91.com/api/v5/otp/verify?otp=$otp&authkey=196733AcZiJ1EG25e84a365P1&mobile=91$mobile",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Content-Type: application/JSON"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return response()->json(['Success' => false, 'message' => "Kindly try after sometime"]);
            } else {
                $response = json_decode($response, true);
                if (isset($response['type']) && $response['type'] == 'success') {

                    if (session()->has('onboarding_lead_id')) {
                        $lead = Lead::whereKey(session('onboarding_lead_id'))->first();

                        if (!$lead || preg_replace('/\D+/', '', $lead->phone_no) !== $mobile) {
                            return response()->json(['success' => false, 'message' => 'Invalid onboarding link.'], 422);
                        }

                        session(['onboarding_otp_verified' => true]);

                        return response()->json([
                            'success' => true,
                            'redirect_url' => session('onboarding_redirect', route('external.form')),
                        ]);
                    }

                    if (session()->has('candidate_journey_lead_id')) {
                        $lead = Lead::whereKey(session('candidate_journey_lead_id'))
                            ->where('lead_type', 'Interview')
                            ->first();

                        if (!$lead || preg_replace('/\D+/', '', $lead->phone_no) !== $mobile) {
                            return response()->json(['success' => false, 'message' => 'Invalid candidate journey.'], 422);
                        }

                        session(['candidate_journey_otp_verified' => true]);
                        \Log::info('Candidate Journey mobile OTP verified', [
                            'lead_id' => $lead->id,
                            'lead_identifier' => $lead->lead_id,
                        ]);

                        return response()->json([
                            'success' => true,
                            'redirect_url' => session('candidate_journey_redirect', route('candidate.view', ['leadId' => $lead->lead_id])),
                        ]);
                    }

                    $rawToken = Str::random(40);
                    $hashedToken = hash('sha256', $rawToken);

                    $link_type = 'admission_registration';
                    $expiry_type = 'minute';
                    $expiry_value = 60;
                    // Calculate expiry time
                    $expiresAt = match ($expiry_type) {
                        'minute' => now()->addMinutes($expiry_value),
                        'day' => now()->addDays($expiry_value),
                        'month' => now()->addMonths($expiry_value),
                    };

                    // Store
                    ActiveDynamicLink::create([
                        'link_type' => $link_type,
                        'token' => $hashedToken,
                        'expires_at' => $expiresAt,
                        'is_used' => false,
                    ]);
                    return response()->json([
                        'activation_url' => route('activate.link', $rawToken),
                        'expires_at' => $expiresAt->toDateTimeString(),
                        'link_type' => $link_type,
                        'expiry_type' => $expiry_type,
                        'expiry_value' => $expiry_value,
                    ]);



                } else {
                    return response()->json(['Success' => false, 'message' => "Invalid OTP"]);
                }
            }
        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "errorCode" => "881",
                "description" => "Note : Initaite Status check after Some time"
            ]);
        }
    }
}
