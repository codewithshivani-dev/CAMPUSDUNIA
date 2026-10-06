<?php

namespace App\Http\Controllers\institute\Admin;

use App\Http\Controllers\Controller;
use App\Models\HostelFee;
use App\Models\InstituteBasicDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    //send otp to phone
    function sendMobileOtp(Request $request) {
        $attr = $request->all();
        $validator = Validator::make($attr, [
            'mobile_number' => 'required|digits:10',
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->ValidationError($validator->messages(), 401);
        }
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
        echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response, true);
            if($response['type'] == 'success'){
            return response()->json(['code'=>200 ,  'Success' => true, 'data' => $response, 'message' => 'OTP sent successfully']);
            }else{
                return response()->json(['code'=>400 ,  'Success' => false, 'data' => $response, 'message' => 'OTP sent successfully']); 
            }
        }
    }
    
    function verifyOtp(Request $request) {
        $attr = $request->all();
        $validator = Validator::make($attr, [
            'mobile_number' => 'required|digits:10',
            'otp' => 'required|digits:4',
        ]);
        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->ValidationError($validator->messages(), 401);
        }
        $mobile = $attr['mobile_number'];
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
        echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response, true);
            if($response['type'] == 'success'){
                return response()->json(['code'=>200 ,  'Success' => true, 'data' => $response]);
            }else{
                return response()->json(['code'=>400,  'Success' => false, 'data' => $response]); 
            }
        }
    }
}