<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * Default redirect (overridden below).
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Redirect user after email verification based on role.
     *
     * @return string
     */
    protected function redirectTo()
    {
        $user = auth()->user();
        dd($user);
        if ($user->hasRole('superadmin')) {
            return route('superadmin.dashboard');
        } elseif ($user->hasRole('admin')) {
            return route('admin.dashboard');
        } elseif ($user->hasRole('employee')) {
            return route('employee.dashboard');
        } elseif ($user->hasRole('student')) {
            return route('student.dashboard');
        } elseif ($user->hasRole('parents')) {
            return route('parents.dashboard');
        } elseif ($user->hasRole('agent')) {
            return route('agent.dashboard');
        }

        // fallback
        return RouteServiceProvider::HOME;
    }
}
