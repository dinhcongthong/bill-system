<?php

namespace App\Http\Middleware;

use Closure;
use Config;

use Illuminate\Support\Facades\Auth;


class AdminMiddleware
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
            // if (!in_array(Auth::user()->id, config('permission.id'))) {
            //     return response()->view('errors.404');
            // }
            if (!in_array(Auth::user()->id, Config('permission.id'))) {
                return response()->view('errors.404');
            }
        }
        return $next($request);
    }
}
