<?php

namespace App\Http\Middleware;

use Closure;
use Config;

use Illuminate\Support\Facades\Auth;


class SettingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        } else {
            if (!in_array(Auth::user()->id, Config::get('permission.id'), false)) {
                return response()->view('errors.404');
            }
        }
        return $next($request);
    }
}