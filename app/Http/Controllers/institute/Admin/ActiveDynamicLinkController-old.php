<?php
namespace App\Http\Controllers\institute\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\ActiveDynamicLink;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Lead;
use App\Models\StudentAdmissionProcess; 

class ActiveDynamicLinkController extends Controller
{
    // Generate activation link
    public function generate(Request $request)
    {
        $request->validate([
        'link_type'    => 'required|string',
        'expiry_type'  => 'required|in:minute,day,month',
        'expiry_value' => 'required|integer|min:1|max:365',
        ]);

        // Generate token
        $rawToken    = Str::random(40);
        $hashedToken = hash('sha256', $rawToken);

        // Calculate expiry time
        $expiresAt = match ($request->expiry_type) {
            'minute' => now()->addMinutes($request->expiry_value),
            'day'    => now()->addDays($request->expiry_value),
            'month'  => now()->addMonths($request->expiry_value),
        };

        // Store
        ActiveDynamicLink::create([
            'link_type'  => $request->link_type,
            'token'      => $hashedToken,
            'expires_at' => $expiresAt,
            'is_used'    => false,
        ]);

        return redirect()->back()->with([
            'activation_url' => route('activate.link', $rawToken),
            'expires_at'     => $expiresAt->toDateTimeString(),
            'link_type'      => $request->link_type,
            'expiry_type'    => $request->expiry_type,
            'expiry_value'   => $request->expiry_value,
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
        if($lead){
            $studentlead = StudentAdmissionProcess::with([
                                'admissionConfig',
                                'counsellingSlot',
                                'entranceTestSlot'
                            ])
                            ->where('lead_id', $lead->id)
                            ->firstOrFail();
            
            $data = [
                'lead'=> $lead,
                'studentlead' => $studentlead
            ];
            // Check authorization (if needed)
            return view('instituteAdmin.AddLead.StudentView', compact('data'));
        }else{
            return redirect('/admission/send-otp');
        }

    }
    public function student_admission_send_otp(Request $request){
        $attr = $request->all();
        $validator = Validator::make($attr, [
            'mobile_number' => 'required|digits:10',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            //return $this->ValidationError($validator->messages(), 200);
            return Redirect::back()->withErrors($validator);
        }
        //SMS Services
 
        try
        {

            $mobile = $attr['mobile_number'];
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
        }
        catch(Exception $e)
        {
            return response()->json([
                "success"=>false,
                "errorCode"=>"880",
                "description"=>"Note : Initaite Status check after Some time"
            ]);
        }
        //END SMS Services
    }
    function student_admission_verify_otp(Request $request) {
        $attr = $request->all();
        $validator = Validator::make($attr, [
            'otp' => 'required|digits:4',
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->ValidationError($validator->messages(), 200);
        }
        try{
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
                if(isset($response['type']) &&  $response['type'] == 'success'){

                    $rawToken    = Str::random(40);
                    $hashedToken = hash('sha256', $rawToken);

                    $link_type = 'admission_registration';
                    $expiry_type = 'minute';
                    $expiry_value = 60;
                    // Calculate expiry time
                    $expiresAt = match ($expiry_type) {
                        'minute' => now()->addMinutes($expiry_value),
                        'day'    => now()->addDays($expiry_value),
                        'month'  => now()->addMonths($expiry_value),
                    };

                    // Store
                    ActiveDynamicLink::create([
                        'link_type'  => $link_type,
                        'token'      => $hashedToken,
                        'expires_at' => $expiresAt,
                        'is_used'    => false,
                    ]);
                    return response()->json([
                        'activation_url' => route('activate.link', $rawToken),
                        'expires_at'     => $expiresAt->toDateTimeString(),
                        'link_type'      => $link_type,
                        'expiry_type'    => $expiry_type,
                        'expiry_value'   => $expiry_value,
                    ]);


        
                }else{                
                    return response()->json(['Success' => false, 'message' => "Invalid OTP"]);
                }   
        }
        }
        catch(Exception $e)
        {
            return response()->json([
                "success"=>false,
                "errorCode"=>"881",
                "description"=>"Note : Initaite Status check after Some time"
            ]);
        }
    }
}
