<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                $user = Auth::guard($guard)->user();

                // ✅ ALWAYS return a RedirectResponse
                if ($user->hasRole('superadmin')) {
                    return redirect(RouteServiceProvider::SUPERADMIN_HOME);
                }

                if ($user->hasRole('admin')) {
                    return redirect(RouteServiceProvider::ADMIN_HOME);
                }

                if ($user->hasRole('employee')) {
                    return redirect(RouteServiceProvider::EMPLOYEE_HOME);
                }

                if ($user->hasRole('manager')) {
                    return redirect(RouteServiceProvider::MANAGER_HOME);
                }

                if ($user->hasRole('supervisor')) {
                    return redirect(RouteServiceProvider::SUPERVISOR_HOME);
                }

                if ($user->hasRole('student')) {
                    return redirect(RouteServiceProvider::STUDENT_HOME);
                }

                if ($user->hasRole('parents')) {
                    return redirect(RouteServiceProvider::PARENTS_HOME);
                }

                if ($user->hasRole('agent')) {
                    return redirect(RouteServiceProvider::AGENT_HOME);
                }

                // 🔐 Fallback (VERY IMPORTANT)
                return redirect(RouteServiceProvider::ADMIN_HOME);
            }
        }

        return $next($request);
    }
}
