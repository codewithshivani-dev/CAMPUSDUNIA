<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use PDF;
use App\Http\Controllers\Controller;
use App\Models\EmailOtpVerification;


class EmailOtpController extends Controller
{
    /**
     * Send OTP to Email
     */
    public function changeEmailIdByOtp(Request $request)
    {
        $attr = $request->all();

        $email = $attr['email_id'];

        // Store in session
        session(['change_email_id' => $email]);

        // OTP + Expiry
        $otp = rand(1000, 9999);
        $otp_expires_time = Carbon::now('Asia/Kolkata')->addMinutes(2);

        // Email data
        $data = [
            'otp'   => $otp,
            'email' => $email,
            'title' => "Email OTP Verification"
        ];

        // Send Mail
        Mail::send('user/emailOtpTemplate', $data, function ($message) use ($data) {
            $message->to($data["email"], $data["email"])
                ->subject($data["title"]);
        });

        // Extra params
        $otp_verification_type = $attr['otp_verification_type'] ?? 'change_email';
        $query_id = $attr['query_id'] ?? null;

        session([
            'otp_verification_type' => $otp_verification_type,
            'query_id' => $query_id
        ]);

        // Prepare DB data
        $email_data = [
            'user_id' => auth()->user()->id,
            'user_hash_id' => auth()->user()->user_hash_id,
            'otp_verification_type' => $otp_verification_type,
            'email_otp' => $otp,
            'expired_otp_time' => $otp_expires_time
        ];

        // Check existing record
        $email_vrf = EmailOtpVerification::where([
            'user_id' => auth()->user()->id,
            'user_hash_id' => auth()->user()->user_hash_id,
            'otp_verification_type' => $otp_verification_type
        ])->first();

        if ($email_vrf) {
            $email_vrf->update($email_data);
        } else {
            EmailOtpVerification::create($email_data);
        }

        return response()->json([
            'Success' => true,
            'link' => "email-verification"
        ]);
    }

    /**
     * Verify OTP
     */
    public function verifyEmailOtpApi(Request $request)
    {
        $attr = $request->all();

        // Validation
        $validator = Validator::make($attr, [
            'otp' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'Success' => false,
                'errors' => $validator->messages()
            ]);
        }

        $email = session('change_email_id');
        $otp_verification_type = session('otp_verification_type');
        $query_id = session('query_id');

        $otp = $attr['otp'];
        $otp_now_time = Carbon::now('Asia/Kolkata');

        // Fetch OTP record
        $email_vrf = EmailOtpVerification::where([
            'user_id' => auth()->user()->id,
            'user_hash_id' => auth()->user()->user_hash_id,
            'otp_verification_type' => $otp_verification_type
        ])->first();

        // ❗ Check if record exists
        if (!$email_vrf) {
            return response()->json([
                'Success' => false,
                'message' => "OTP record not found."
            ]);
        }

        // ❗ Expiry Check
        if ($otp_now_time > $email_vrf->expired_otp_time) {
            return response()->json([
                'Success' => false,
                'message' => "OTP has expired. Please generate a new one."
            ]);
        }

        // ❗ Correct OTP Comparison (FIXED BUG)
        if ($otp != $email_vrf->email_otp) {
            return response()->json([
                'Success' => false,
                'message' => "Invalid OTP entered."
            ]);
        }

        /**
         * CASE 1: Employee Email Flow
         */
        if ($otp_verification_type === "employee_email") {

            UserJourney::where('user_id', auth()->user()->id)->update([
                'user_employee_email_verify_step' => 1,
            ]);

            return response()->json([
                'Success' => true,
                'link' => '/employee-professional-details/' . $query_id
            ]);
        }

        /**
         * CASE 2: Normal Email Change
         */
        $formParams = [
            'user_hash_id' => auth()->user()->user_hash_id,
            'user_credential_type' => 'email',
            'user_credential_data' => $email
        ];

        $api_endpoint = "partner/update-credentials";

        $change_response = $this->fincapApiAccess($api_endpoint, $formParams);
        $response_data = json_decode($change_response, true);

        if (isset($response_data['code']) && $response_data['code'] == 200) {

            User::where('user_hash_id', auth()->user()->user_hash_id)->update([
                'email' => $email
            ]);

            return response()->json([
                'Success' => true,
                'link' => "user-profile"
            ]);
        }

        return response()->json([
            'Success' => false,
            'message' => $response_data['message'] ?? "Please try again later."
        ]);
    }
}