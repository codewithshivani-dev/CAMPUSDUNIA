<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Default redirect (will be overridden in redirectTo()).
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::ADMIN_HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validate registration input.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'in:superadmin,admin,manager,supervisor,employee,student,parents,agent'],
        ]);
    }

    /**
     * Create user and assign role.
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Assign role (Spatie Permission required)
        // $user->assignRole($data['role']);
        $user->syncRoles([$data['role']]);

        return $user;
    }

    /**
     * Redirect user after registration/login based on role.
     */
    protected function redirectTo()
    {
        $user = auth()->user();

        if ($user->hasRole('superadmin')) {
            // return route('superadmin.dashboard');
            return RouteServiceProvider::SUPERADMIN_HOME;
        } elseif ($user->hasRole('admin')) {
            return RouteServiceProvider::ADMIN_HOME;
            return route('admin.dashboard');
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
            return route('parents.dashboard');
        } elseif ($user->hasRole('agent')) {
            return RouteServiceProvider::AGENT_HOME;
            return route('agent.dashboard');
        }

        
    }
}
