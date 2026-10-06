<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected function redirectTo()
    {
        $user = auth()->user();

        if ($user->hasRole('superadmin')) {
            // return route('superadmin.dashboard');
            return RouteServiceProvider::SUPERADMIN_HOME;
        } elseif ($user->hasRole('admin')) {
            return RouteServiceProvider::ADMIN_HOME;
        } elseif ($user->hasRole('employee')) {
            return RouteServiceProvider::EMPLOYEE_HOME;
        } elseif ($user->hasRole('manager')) {
            return RouteServiceProvider::MANAGER_HOME;
        } elseif ($user->hasRole('supervisor')) {
            return RouteServiceProvider::SUPERVISOR_HOME;
        } elseif ($user->hasRole('student')) {
            return RouteServiceProvider::STUDENT_HOME;
        } elseif ($user->hasRole('parents')) {
            return RouteServiceProvider::PARENTS_HOME;
        } elseif ($user->hasRole('agent')) {
            return RouteServiceProvider::AGENT_HOME;
        }

        
    }
}
