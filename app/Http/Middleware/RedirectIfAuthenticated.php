<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        // 通常ログインしている場合は強制的にホーム画面へリダイレクト
        if (Auth::guard()->check()) {
            return redirect(RouteServiceProvider::HOME);
        }
        // スタッフログインしている場合は強制的にスタッフホームへリダイレクト
        if (Auth::guard('staff')->check()) {
            return redirect(RouteServiceProvider::STAFF_HOME);
        }

        // if (Auth::guard($guard)->check()) {
        //     if ($guard == 'staff') {
        //         return redirect(RouteServiceProvider::STAFF_HOME);
        //     }
        //     return redirect(RouteServiceProvider::HOME);
        // }

        return $next($request);
    }
}
