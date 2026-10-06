<?php

namespace App\Http\Controllers\institute\Admin\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\StudentParentDetails;
use App\Models\StudentParentAddress;
use App\Models\StudentParentBankAccount;
use App\Models\StudentParentDocuments;
use App\Models\StudentAcademicTransportDetails;
use App\Models\User;
use App\Models\StudentProfileEdit;
use Carbon\Carbon;

class StudentProfileSettingController extends Controller
{
    const MAX_CHANGE_COUNT = 2; // Maximum number of changes allowed
    
    /**
     * Display student profile page with tabs
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get student details with profile edits
       $student = StudentParentDetails::where('user_id', $user->id)
            ->with([
                'profileEdits' => function ($query) {
                    $query->where('field_name', '!=', 'password')
                        ->orderBy('created_at', 'desc');
                }
            ])
            ->first();
        
        if (!$student) {
            return redirect()->back()->with('error', 'Student profile not found.');
        }

        // Generate CAPTCHA for password change page
        $captchaText = $this->generateCaptcha();
        session(['captcha_text' => $captchaText]);

        // Get remaining change counts
        $remainingEmailChanges = max(0, self::MAX_CHANGE_COUNT - ($student->email_change_count ?? 0));
        $remainingPhoneChanges = max(0, self::MAX_CHANGE_COUNT - ($student->phone_change_count ?? 0));

        //Get used changes count
        $usedEmailChanges =  max(0, self::MAX_CHANGE_COUNT - $remainingEmailChanges);
        
        $usedPhoneChanges =  max(0, self::MAX_CHANGE_COUNT - $remainingPhoneChanges);

        return view('instituteAdmin.StudentFiles.profile.index', compact('student', 'user', 'captchaText', 'remainingEmailChanges', 'remainingPhoneChanges','usedEmailChanges','usedPhoneChanges'));
    }

    /**
     * Generate random CAPTCHA text
     */
    private function generateCaptcha($length = 6)
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
        $captcha = '';
        for ($i = 0; $i < $length; $i++) {
            $captcha .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $captcha;
    }

    /**
     * Refresh CAPTCHA
     */
    public function refreshCaptcha()
    {
        $captchaText = $this->generateCaptcha();
        session(['captcha_text' => $captchaText]);
        
        return response()->json([
            'success' => true,
            'captcha_text' => $captchaText
        ]);
    }

    /**
     * Update student password with tracking
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string|min:8',
            'captcha' => 'required|string'
        ], [
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
            'captcha.required' => 'CAPTCHA code is required.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify CAPTCHA
        if (strtolower($request->captcha) !== strtolower(session('captcha_text'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid CAPTCHA code. Please try again.'
            ], 422);
        }

        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found.'], 404);
        }

        try {
            DB::beginTransaction();

            // Update password in users table
            if ($user) {
                $user->password = Hash::make($request->new_password);
                $user->save();
            }

            // Log the password change
            StudentProfileEdit::create([
                'student_hash_id' => $student->student_hash_id,
                'field_name' => 'password',
                'old_value' => '[PROTECTED]',
                'new_value' => '[PROTECTED]',
                'verified_at' => now(),
                'verification_method' => 'captcha'
            ]);

            // Clear CAPTCHA session
            session()->forget('captcha_text');
            
            // Generate new CAPTCHA for next use
            $newCaptcha = $this->generateCaptcha();
            session(['captcha_text' => $newCaptcha]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully!',
                'new_captcha' => $newCaptcha
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Password update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password. Please try again.'
            ], 500);
        }
    }

    /**
     * Update student name with tracking
     */
    public function updateName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'nullable|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'middle_name' => 'nullable|string|max:255|regex:/^[a-zA-Z\s]+$/'
        ], [
            'first_name.required' => 'First name is required.',
            'first_name.regex' => 'First name should only contain letters and spaces.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found.'], 404);
        }

        // Check if any name changed
        $oldFirstName = $student->first_name;
        $oldMiddleName = $student->middle_name;
        $oldLastName = $student->last_name;
        
        $firstNameChanged = ($oldFirstName !== $request->first_name);
        $middleNameChanged = ($oldMiddleName !== $request->middle_name);
        $lastNameChanged = ($oldLastName !== $request->last_name);
        
        if (!$firstNameChanged && !$middleNameChanged && !$lastNameChanged) {
            return response()->json(['success' => false, 'message' => 'No changes detected.']);
        }

        try {
            DB::beginTransaction();
            
            // Build old and new name strings for logging
            $oldFullName = trim($oldFirstName . ' ' . ($oldMiddleName ? $oldMiddleName . ' ' : '') . $oldLastName);
            $newFullName = trim($request->first_name . ' ' . ($request->middle_name ? $request->middle_name . ' ' : '') . $request->last_name);
            
            // Update student name
            $student->first_name = $request->first_name;
            $student->middle_name = $request->middle_name;
            $student->last_name = $request->last_name;
            $student->save();

            // Update user name
            if ($user) {
                $user->name = $newFullName;
                $user->save();
            }

            // Log the name change
            StudentProfileEdit::create([
                'student_hash_id' => $student->student_hash_id,
                'field_name' => 'name',
                'old_value' => $oldFullName,
                'new_value' => $newFullName,
                'verified_at' => now(),
                'verification_method' => 'direct'
            ]);

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Name updated successfully!',
                'old_name' => $oldFullName,
                'new_name' => $newFullName,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Name update failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update name. Please try again.'], 500);
        }
    }

    /**
     * Check if email can be changed
     */
    private function canChangeEmail($student)
    {
        $changeCount = $student->email_change_count ?? 0;
        
        if ($changeCount >= self::MAX_CHANGE_COUNT) {
            return [
                'can_change' => false,
                'message' => 'You have reached the maximum limit of ' . self::MAX_CHANGE_COUNT . ' email changes. Please contact administrator for further changes.'
            ];
        }
        
        return ['can_change' => true];
    }

    /**
     * Check if phone can be changed
     */
    private function canChangePhone($student)
    {
        $changeCount = $student->phone_change_count ?? 0;
        
        if ($changeCount >= self::MAX_CHANGE_COUNT) {
            return [
                'can_change' => false,
                'message' => 'You have reached the maximum limit of ' . self::MAX_CHANGE_COUNT . ' phone number changes. Please contact administrator for further changes.'
            ];
        }
        
        return ['can_change' => true];
    }

    /**
     * Send OTP for email verification
     */
    public function sendEmailOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found.'], 404);
        }

        // Check if email can be changed
        $canChange = $this->canChangeEmail($student);
        if (!$canChange['can_change']) {
            return response()->json([
                'success' => false,
                'message' => $canChange['message']
            ], 403);
        }

        // Check if email is the same as current
        if ($student->email === $request->email) {
            return response()->json(['success' => false, 'message' => 'New email must be different from current email.']);
        }

        $email = $request->email;

        try {
            // Generate OTP
            $otp = rand(1000, 9999);
            $otp_expires_time = Carbon::now('Asia/Kolkata')->addMinutes(2);

            // Email data
            $data = [
                'otp'   => $otp,
                'email' => $email,
                'title' => "Email OTP Verification - Student Profile Update"
            ];

            // Send Mail
            Mail::send('user/emailOtpTemplate', $data, function ($message) use ($data) {
                $message->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($data["email"])
                    ->subject($data["title"]);
            });

            // Store OTP in session for verification
            session([
                'student_email_otp' => $otp,
                'student_email_otp_expires' => $otp_expires_time,
                'student_new_email' => $email
            ]);

            \Log::info('Student Email OTP sent successfully to: ' . $email);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email. Please verify to complete the update.',
                'otp_expiry_minutes' => 2,
                'remaining_changes' => self::MAX_CHANGE_COUNT - ($student->email_change_count ?? 0) - 1
            ]);
        } catch (\Exception $e) {
            \Log::error('Student Email OTP sending failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.'
            ], 500);
        }
    }

    /**
     * Verify email OTP and update email with tracking
     */
    public function verifyEmailOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:4',
            'email' => 'required|email|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found.'], 404);
        }

        // Double-check change limit before updating
        $canChange = $this->canChangeEmail($student);
        if (!$canChange['can_change']) {
            return response()->json([
                'success' => false,
                'message' => $canChange['message']
            ], 403);
        }

        $otp = $request->otp;
        $email = $request->email;

        // Get stored OTP from session
        $stored_otp = session('student_email_otp');
        $otp_expires = session('student_email_otp_expires');
        $stored_email = session('student_new_email');

        // Verify OTP exists
        if (!$stored_otp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP not found. Please request a new OTP.'
            ], 400);
        }

        // Verify OTP hasn't expired
        if (Carbon::now('Asia/Kolkata') > $otp_expires) {
            session()->forget(['student_email_otp', 'student_email_otp_expires', 'student_new_email']);
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

        // Verify email matches
        if ($email != $stored_email) {
            return response()->json([
                'success' => false,
                'message' => 'Email mismatch. Please try again.'
            ], 400);
        }

        try {
            DB::beginTransaction();
            
            $oldEmail = $student->email;
            
            // Update student email and increment change count
            $student->email = $email;
            $student->email_change_count = ($student->email_change_count ?? 0) + 1;
            $student->last_email_change_at = now();
            $student->save();

            // Update user email
            if ($user) {
                $user->email = $email;
                $user->save();
            }

            // Log the email change
            StudentProfileEdit::create([
                'student_hash_id' => $student->student_hash_id,
                'field_name' => 'email',
                'old_value' => $oldEmail,
                'new_value' => $email,
                'verified_at' => now(),
                'verification_method' => 'otp'
            ]);

            // Clear session
            session()->forget(['student_email_otp', 'student_email_otp_expires', 'student_new_email']);

            $remainingChanges = self::MAX_CHANGE_COUNT - ($student->email_change_count ?? 0);

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Email updated successfully! You have ' . $remainingChanges . ' change(s) remaining.',
                'old_email' => $oldEmail,
                'new_email' => $email,
                'remaining_changes' => $remainingChanges
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Email update failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update email. Please try again.'], 500);
        }
    }

    /**
     * Send OTP for phone verification
     */
    public function sendPhoneOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^[6-9]\d{9}$/'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found.'], 404);
        }

        // Check if phone can be changed
        $canChange = $this->canChangePhone($student);
        if (!$canChange['can_change']) {
            return response()->json([
                'success' => false,
                'message' => $canChange['message']
            ], 403);
        }

        // Check if phone is the same as current
        if ($student->mobile === $request->phone) {
            return response()->json(['success' => false, 'message' => 'New phone number must be different from current phone number.']);
        }

        try {
            // Store new phone in session
            session(['student_new_phone' => $request->phone]);
            
            // Create request for OtpController
            $otpRequest = new Request();
            $otpRequest->merge([
                'mobile_number' => $request->phone
            ]);
            
            // Call OtpController to send OTP
            $otpController = new \App\Http\Controllers\institute\Admin\OtpController();
            $response = $otpController->sendMobileOtp($otpRequest);
            
            $responseData = json_decode($response->getContent(), true);
            
            if (isset($responseData['Success']) && $responseData['Success'] === true) {
                return response()->json([
                    'success' => true, 
                    'message' => "OTP sent to your new phone number {$request->phone}. Please verify to complete the update.",
                    'otp_expiry_minutes' => 10,
                    'phone_number' => $request->phone,
                    'remaining_changes' => self::MAX_CHANGE_COUNT - ($student->phone_change_count ?? 0) - 1
                ]);
            } else {
                return response()->json([
                    'success' => false, 
                    'message' => $responseData['message'] ?? 'Failed to send OTP. Please try again.'
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Phone OTP sending failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send OTP. Please try again.'], 500);
        }
    }

    /**
     * Verify phone OTP and update phone with tracking
     */
    public function verifyPhoneOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:4',
            'phone' => 'required|string|regex:/^[6-9]\d{9}$/'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $student = StudentParentDetails::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found.'], 404);
        }

        // Double-check change limit before updating
        $canChange = $this->canChangePhone($student);
        if (!$canChange['can_change']) {
            return response()->json([
                'success' => false,
                'message' => $canChange['message']
            ], 403);
        }

        try {
            // Create request for OtpController to verify OTP
            $otpRequest = new Request();
            $otpRequest->merge([
                'mobile_number' => $request->phone,
                'otp' => $request->otp
            ]);
            
            // Call OtpController to verify OTP
            $otpController = new \App\Http\Controllers\institute\Admin\OtpController();
            $response = $otpController->verifyOtp($otpRequest);
            
            $responseData = json_decode($response->getContent(), true);
            
            if (isset($responseData['Success']) && $responseData['Success'] === true) {
                DB::beginTransaction();
                
                $oldPhone = $student->mobile;
                $newPhone = session('student_new_phone') ?? $request->phone;
                
                // Update student phone and increment change count
                $student->mobile = $newPhone;
                $student->phone_change_count = ($student->phone_change_count ?? 0) + 1;
                $student->last_phone_change_at = now();
                $student->save();

                // Update user phone
                if ($user) {
                    $user->phone = $newPhone;
                    $user->save();
                }

                // Log the phone change
                StudentProfileEdit::create([
                    'student_hash_id' => $student->student_hash_id,
                    'field_name' => 'phone',
                    'old_value' => $oldPhone,
                    'new_value' => $newPhone,
                    'verified_at' => now(),
                    'verification_method' => 'otp'
                ]);

                // Clear session
                session()->forget('student_new_phone');

                $remainingChanges = self::MAX_CHANGE_COUNT - ($student->phone_change_count ?? 0);

                DB::commit();

                return response()->json([
                    'success' => true, 
                    'message' => 'Phone number updated successfully! You have ' . $remainingChanges . ' change(s) remaining.',
                    'old_phone' => $oldPhone,
                    'new_phone' => $newPhone,
                    'remaining_changes' => $remainingChanges
                ]);
            } else {
                return response()->json([
                    'success' => false, 
                    'message' => $responseData['message'] ?? 'Invalid OTP. Please try again.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Phone verification failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to verify phone number. Please try again.'], 500);
        }
    }

    /**
     * Resend OTP
     */
    public function resendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:email,phone'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request type.'
            ]);
        }

        if ($request->type === 'email') {
            $newEmail = session('student_new_email');
            if (!$newEmail) {
                return response()->json(['success' => false, 'message' => 'No email update in progress.']);
            }
            return $this->sendEmailOTP(new Request(['email' => $newEmail]));
        } 
        
        if ($request->type === 'phone') {
            $newPhone = session('student_new_phone');
            if (!$newPhone) {
                return response()->json(['success' => false, 'message' => 'No phone update in progress.']);
            }
            return $this->sendPhoneOTP(new Request(['phone' => $newPhone]));
        }

        return response()->json(['success' => false, 'message' => 'Invalid request.']);
    }
}