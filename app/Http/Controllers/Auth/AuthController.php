<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Signup
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:superadmin,admin,employee,student,parents,agent'
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        // Send verification email
        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')
            ->with('status', 'We have sent you a verification link! Please check your email.');
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if (! $user->hasVerifiedEmail()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Please verify your email before logging in.']);
            }

            return $this->redirectByRole($user);
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // Redirect based on role
    private function redirectByRole($user)
    {
        if ($user->hasRole('superadmin')) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('employee')) {
            return redirect()->route('employee.dashboard');
        }  elseif ($user->hasRole('student')) {
            return redirect()->route('student.dashboard');
         } elseif ($user->hasRole('parents')) {
            return redirect()->route('parents.dashboard');
        } elseif ($user->hasRole('agent')) {
            return redirect()->route('agent.dashboard');
        }
    }
}
