<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Role;
use App\Models\DemoLoginAccess;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DemoLoginController extends Controller
{
    /**
     * Send OTP to email for demo login
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:admin,employee,student',
        ]);

        $email = $request->email;
        $role = $request->role;
        $existingAccessCount = DemoLoginAccess::where('email', $email)
            ->where('role', $role)
            ->count();
        if ($existingAccessCount > 60) {
            return response()->json([
                'success' => false,
                'message' => 'Demo access limit exceeded for this email and role.',
            ]);
        }
        // Check if email exists for the role
        // $user = $this->findUserByRole($email, $role);

        // if (!$user) {
        //     // For demo purposes, we'll allow any email but show a message
        //     // You can modify this to only allow specific emails
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'No user found with this email for the selected role. Please use a registered email for demo access.'
        //     ], 404);
        // }

        // Generate OTP
        $otp = $this->generateOtp();
        
        // Store OTP in cache with email and role
        Cache::put('demo_otp_' . $email, [
            'otp' => $otp,
            'role' => $role,
            'email' => $email,
            'expires_at' => Carbon::now()->addMinutes(2)
        ], 600);

        // Send OTP via email
        try {
            DemoLoginAccess::create([
                'email' => $email,
                'role' => $role,
                'otp' => $otp,
                'expires_at' => Carbon::now()->addMinutes(2)
            ]);
            $this->sendOtpEmail($email, $otp, $role);
            
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully to your email.',
                'email' => $email
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification email. Please try again.'
            ], 500);
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
            'role' => 'required|in:admin,employee,student',
        ]);

        $email = $request->email;
        $otp = $request->otp;
        $role = $request->role;

        // Get stored OTP data
        $storedData = Cache::get('demo_otp_' . $email);

        if (!$storedData) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired. Please request a new one.'
            ], 400);
        }

        // Check if OTP is expired
        if (Carbon::now()->gt($storedData['expires_at'])) {
            Cache::forget('demo_otp_' . $email);
            return response()->json([
                'success' => false,
                'message' => 'OTP expired. Please request a new one.'
            ], 400);
        }

        // Verify OTP
        if ($storedData['otp'] !== $otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.'
            ], 400);
        }

        // Verify role matches
        if ($storedData['role'] !== $role) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request. Role mismatch.'
            ], 400);
        }

        // Store verified email in session for login
        Session::put('demo_verified_email', $email);
        Session::put('demo_verified_role', $role);

        // Clear OTP from cache
        Cache::forget('demo_otp_' . $email);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully.'
        ]);
    }

    /**
     * Generate 6-digit OTP
     */
    private function generateOtp()
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Send OTP email
     */
    private function sendOtpEmail($email, $otp, $role)
    {
        $roleNames = [
            'admin' => 'Administrator',
            'employee' => 'Employee',
            'student' => 'Student/Parent'
        ];

        $roleName = $roleNames[$role] ?? $role;

        Mail::send('emails.demo-otp', [
            'otp' => $otp,
            'role' => $roleName,
            'email' => $email
        ], function ($message) use ($email) {
            $message->to($email)
                    ->subject('Demo Login Verification Code');
        });
    }

    /**
     * Redirect based on user role
     */
    private function redirectBasedOnRole($user)
    {
        // Determine user role
        $role = $user->roles->first()->name ?? 'student';

        switch ($role) {
            case 'admin':
            case 'super-admin':
                return redirect()->route('admin.dashboard');
            case 'employee':
            case 'staff':
            case 'teacher':
                return redirect()->route('employee.dashboard');
            case 'parent':
            case 'student':
            default:
                return redirect()->route('student.dashboard');
        }
    }
}