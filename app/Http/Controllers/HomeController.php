<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
            $user = auth()->user();
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

    }
}
