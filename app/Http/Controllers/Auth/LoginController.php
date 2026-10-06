<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::ADMIN_HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * 🎯 Detect email or phone dynamically
     */
    protected function credentials(Request $request)
    {
        $login = $request->input('login');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
                    ? 'email'
                    : 'phone';

        return [
            $field     => $login,
            'password' => $request->password,
        ];
    }

    /**
     * Validation
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);

        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($field, $login)->first();

        // Only block inactive users
        if ($user && $user->status === 'inactive') {

            throw ValidationException::withMessages([
                'login' => ['Your account is inactive. Please contact the administrator.'],
            ]);
        }

        return $this->guard()->attempt(
            $credentials,
            $request->filled('remember')
        );
    }

    /**
     * 🚨 SPECIFIC ERROR MESSAGES
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $login = $request->input('login');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
                    ? 'email'
                    : 'phone';

        $userExists = User::where($field, $login)->exists();

        if (!$userExists) {
            throw ValidationException::withMessages([
                'login' => ["This {$field} is not registered."],
            ]);
        }

        throw ValidationException::withMessages([
            'password' => ['The password you entered is incorrect.'],
        ]);
    }
    // Demo Login
    public function demoLogin(Request $request)
    {
        $role = Session::get('demo_verified_role');
        switch ($role) {

            case 'admin':
                $login = 'tarun.entritt@gmail.com';
                $password = '12345678';
                break;

            case 'employee':
                $login = 'harshitrana@gmail.com';
                $password = '12345678';
                break;

            case 'student':
                $login = 'meenubala.entritt@gmail.com';
                $password = '12345678';
                break;

            default:
                abort(404);
        }

        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';

        if (auth()->attempt([
            $field => $login,
            'password' => $password,
        ])) {

            $request->session()->regenerate();

            return redirect()->intended($this->redirectTo());
        }

        return back()->withErrors([
            'login' => 'Demo account is not configured.'
        ]);
    }
    /**
     * Role based redirect
     */
    protected function redirectTo()
    {
        $user = auth()->user();

        if ($user->hasRole('superadmin')) return RouteServiceProvider::SUPERADMIN_HOME;
        if ($user->hasRole('admin')) return RouteServiceProvider::ADMIN_HOME;
        if ($user->hasRole('employee')) return RouteServiceProvider::EMPLOYEE_HOME;
        if ($user->hasRole('manager')) return RouteServiceProvider::MANAGER_HOME;
        if ($user->hasRole('supervisor')) return RouteServiceProvider::SUPERVISOR_HOME;
        if ($user->hasRole('student')) return RouteServiceProvider::STUDENT_HOME;
        if ($user->hasRole('parents')) return RouteServiceProvider::PARENTS_HOME;
        if ($user->hasRole('agent')) return RouteServiceProvider::AGENT_HOME;

        return RouteServiceProvider::ADMIN_HOME;
    }
}